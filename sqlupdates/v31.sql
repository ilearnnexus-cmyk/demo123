ALTER TABLE `users` ADD `permanently_delete` TINYINT(1) NOT NULL DEFAULT '0' AFTER `deactivated`;
ALTER TABLE `users` CHANGE `code` `code` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;

UPDATE `settings` SET `value` = '3.1' WHERE `settings`.`type` = 'current_version';

COMMIT;
