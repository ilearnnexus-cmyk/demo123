UPDATE `settings` SET `value` = '3.6' WHERE `settings`.`type` = 'current_version';

ALTER TABLE `currencies` CHANGE `exchange_rate` `exchange_rate` DOUBLE(10,5) NULL;
ALTER TABLE `currencies` CHANGE `status` `status` INT(10) NOT NULL DEFAULT '1';

ALTER TABLE `addons` ADD `purchase_code` VARCHAR(255) NULL DEFAULT NULL AFTER `image`;
ALTER TABLE `physical_attributes` CHANGE `height` `height` DOUBLE(3,2) NULL DEFAULT NULL;

ALTER TABLE `users` ADD `fcm_token` TEXT NULL DEFAULT NULL AFTER `provider_id`;

CREATE TABLE `contact_us` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `reply` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `contact_us`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

INSERT INTO `permissions` (`id`, `name`, `parent`, `guard_name`, `created_at`, `updated_at`)
VALUES
(NULL, 'show_contact_us_queries', 'contact_us', 'web', '2022-07-27 11:28:21', NULL),
(NULL, 'view_contact_us_query', 'contact_us', 'web', '2022-07-27 11:46:08', NULL),
(NULL, 'update_contact_us_query', 'contact_us', 'web', '2022-07-27 12:03:32', NULL),
(NULL, 'delete_contact_us_query', 'contact_us', 'web', '2022-07-27 11:45:54', NULL),
(NULL, 'firebase_push_notification', 'settings', 'web', '2022-07-27 12:07:11', NULL),
(NULL, 'admin_dashboard', 'dashboard', 'web', '2022-07-27 12:35:59', NULL);


INSERT INTO `pages` (`id`, `type`, `title`, `slug`, `content`, `meta_title`, `meta_description`, `keywords`, `meta_image`, `created_at`, `updated_at`) 
VALUES 
(Null, 'faq', 'Frequently Asked Questions (FAQ)', 'faq', '', NULL, NULL, NULL, NULL, '2022-08-28 07:44:49', '2022-08-28 07:44:49');

INSERT INTO `settings` (`id`, `type`, `value`, `created_at`, `updated_at`, `deleted_at`) 
VALUES 
(NULL, 'item_name', 'matrimonial', current_timestamp(), current_timestamp(), NULL);

COMMIT;


