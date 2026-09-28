import re
import sqlite3
from pathlib import Path

import sqlglot

base = Path('/Users/mac/Downloads/codecanyon-21627663-active-matrimonial-cms/install')
sql_path = base / 'matrimonial.sql'
db_path = base / 'database' / 'database.sqlite'

if not sql_path.exists():
    raise FileNotFoundError(sql_path)

text = sql_path.read_text(encoding='utf-8', errors='ignore')

# Remove MySQL-only bits that SQLite rejects.
for needle in [
    'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";',
    'START TRANSACTION;',
    'SET time_zone = "+00:00";',
    '/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;',
    '/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;',
    '/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;',
    '/*!40101 SET NAMES utf8mb4 */;',
]:
    text = text.replace(needle, '')

text = re.sub(r'/\*.*?\*/', '', text, flags=re.S)
text = re.sub(r'--.*?\n', '\n', text)
text = re.sub(r'\bENGINE\s*=\s*[^;]+', '', text, flags=re.I)
text = re.sub(r'\bDEFAULT\s+CHARSET\s*=\s*[^;]+', '', text, flags=re.I)
text = re.sub(r'\bCOLLATE\s*=\s*[^;]+', '', text, flags=re.I)
text = re.sub(r'\bAUTO_INCREMENT\b', 'AUTOINCREMENT', text, flags=re.I)
text = re.sub(r'\bUNSIGNED\b', '', text, flags=re.I)
text = re.sub(r'`([^`]+)`', r'\1', text)
text = re.sub(r'\bint\((\d+)\)', 'INTEGER', text, flags=re.I)
text = re.sub(r'\bbigint\((\d+)\)', 'INTEGER', text, flags=re.I)
text = re.sub(r'\bmediumint\((\d+)\)', 'INTEGER', text, flags=re.I)
text = re.sub(r'\bsmallint\((\d+)\)', 'INTEGER', text, flags=re.I)
text = re.sub(r'\btinyint\((\d+)\)', 'INTEGER', text, flags=re.I)
text = re.sub(r'\bdecimal\([^)]*\)', 'REAL', text, flags=re.I)
text = re.sub(r'\bcurrent_timestamp\(\)', 'CURRENT_TIMESTAMP', text, flags=re.I)

# Split SQL statements safely enough for this project.
statements = []
current = []
in_single = False
in_double = False
escape = False
for ch in text:
    if escape:
        current.append(ch)
        escape = False
        continue
    if ch == '\\':
        current.append(ch)
        escape = True
        continue
    if ch == "'" and not in_double:
        in_single = not in_single
        current.append(ch)
        continue
    if ch == '"' and not in_single:
        in_double = not in_double
        current.append(ch)
        continue
    if ch == ';' and not in_single and not in_double:
        stmt = ''.join(current).strip()
        if stmt:
            statements.append(stmt)
        current = []
    else:
        current.append(ch)

final_stmt = ''.join(current).strip()
if final_stmt:
    statements.append(final_stmt)

conn = sqlite3.connect(str(db_path))
conn.execute('PRAGMA foreign_keys = OFF;')
conn.execute('PRAGMA journal_mode = WAL;')

ok = 0
skipped = []
for idx, raw in enumerate(statements, 1):
    s = raw.strip()
    if not s:
        continue
    if s.upper().startswith('USE '):
        continue
    if s.upper().startswith('SET '):
        continue
    if s.upper().startswith('DELIMITER '):
        continue
    if s.upper().startswith('LOCK TABLES') or s.upper().startswith('UNLOCK TABLES'):
        continue
    try:
        transpiled = sqlglot.transpile(s, read='mysql', write='sqlite')
        for part in transpiled:
            p = (part or '').strip()
            if not p:
                continue
            if p.upper().startswith('BEGIN') or p.upper().startswith('COMMIT'):
                continue
            if p.upper().startswith('SET '):
                continue
            conn.execute(p)
        ok += 1
    except Exception as exc:
        skipped.append((idx, s[:180], str(exc)))
        # Keep going; some MySQL dump syntax is not convertible, but most schema is what matters.
        continue

conn.commit()
print(f'statements_processed={ok}')
print(f'statements_skipped={len(skipped)}')
if skipped:
    print('sample_skip=', skipped[0])

for tbl in ['addons', 'settings', 'translations', 'users', 'members', 'countries', 'states', 'cities', 'blogs', 'blog_categories', 'roles', 'permissions']:
    try:
        count = conn.execute(f'SELECT COUNT(*) FROM "{tbl}"').fetchone()[0]
        print(f'{tbl}={count}')
    except Exception:
        print(f'{tbl}=missing')

conn.close()
