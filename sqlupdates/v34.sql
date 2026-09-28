INSERT INTO `settings` (`id`, `type`, `value`, `created_at`, `updated_at`, `deleted_at`)
VALUES
(NULL, 'paystack_payment_activation', '1', current_timestamp(), current_timestamp(), NULL);

UPDATE `settings` SET `value` = '3.4' WHERE `settings`.`type` = 'current_version';

CREATE TABLE `blog_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `blog_categories`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `blog_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

CREATE TABLE `blogs` (
    `id` bigint(20) UNSIGNED NOT NULL,
    `category_id` int(11) NOT NULL,
    `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `short_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `banner` int(11) DEFAULT NULL,
    `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `meta_img` int(11) DEFAULT NULL,
    `meta_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `meta_keywords` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `status` int(1) NOT NULL DEFAULT 1,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `blogs`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `blogs`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

INSERT INTO `permissions` (`id`, `name`, `parent`, `guard_name`, `created_at`, `updated_at`)
VALUES
(NULL, 'show_blog_categories', 'blog', 'web', '2022-07-27 11:28:21', NULL),
(NULL, 'add_blog_category', 'blog', 'web', '2022-07-27 11:28:21', NULL),
(NULL, 'edit_blog_category', 'blog', 'web', '2022-07-27 11:28:21', NULL),
(NULL, 'delete_blog_category', 'blog', 'web', '2022-07-27 11:28:21', NULL),
(NULL, 'show_blogs', 'blog', 'web', '2022-07-27 11:28:21', NULL),
(NULL, 'add_blog', 'blog', 'web', '2022-07-27 11:28:21', NULL),
(NULL, 'edit_blog', 'blog', 'web', '2022-07-27 11:28:21', NULL),
(NULL, 'delete_blog', 'blog', 'web', '2022-07-27 11:28:21', NULL);

COMMIT;
