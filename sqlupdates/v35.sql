UPDATE `settings` SET `value` = '3.5' WHERE `settings`.`type` = 'current_version';
INSERT INTO `settings` (`id`, `type`, `value`, `created_at`, `updated_at`, `deleted_at`) 
VALUES 
(NULL, 'profile_picture_approval_by_admin', NULL, current_timestamp(), current_timestamp(), NULL),
(NULL, 'profile_picture_privacy', 'all', current_timestamp(), current_timestamp(), NULL),
(NULL, 'gallery_image_privacy', 'all', current_timestamp(), current_timestamp(), NULL);


ALTER TABLE `users` ADD `photo_approved` INT(1) NOT NULL DEFAULT '1' AFTER `photo`;

ALTER TABLE `packages` 
ADD `profile_image_view` INT NOT NULL AFTER `contact`, 
ADD `gallery_image_view` INT NOT NULL AFTER `profile_image_view`;

ALTER TABLE `members` 
ADD `remaining_profile_image_view` INT NOT NULL DEFAULT '0' AFTER `remaining_contact_view`, 
ADD `remaining_gallery_image_view` INT NOT NULL DEFAULT '0' AFTER `remaining_profile_image_view`;

ALTER TABLE `members` 
DROP `profile_picture_privacy`, 
DROP `gallery_image_privacy`;

INSERT INTO `email_templates` (`id`, `identifier`, `subject`, `body`, `status`, `created_at`, `updated_at`) 
VALUES 
(null, 'profile_picture_view_request_email', 'Profile Picture View Request', '<p>Hi [[name]], </p><p>\r\n[[member_name]] sent you Profile Picture View Request.\r\n</p><p>Please contact the&nbsp;administration&nbsp;team if you have any further questions. </p><p>\r\nThanks,\r\n</p><p> [[from]]</p>', 1, '2021-12-14 03:48:39', '2021-12-14 10:08:28'),
(null, 'profile_picture_view_request_accepted_email', 'Profile Picture View Request Accepted', '<p>Hi [[name]], </p><p>\r\n[[member_name]] has accepted your Profile Picture View Request<br></p><p>Please contact the&nbsp;administration&nbsp;team if you have any further questions. </p><p>\r\nThanks,\r\n</p><p> [[from]]</p>', 1, '2021-12-14 10:05:16', '2021-12-14 10:07:48'),
(null, 'gallery_image_view_request_email', 'Gallery Image View Request', '<p>Hi [[name]], </p><p>\r\n[[member_name]] sent you Gallery Image View Request.\r\n</p><p>Please contact the&nbsp;administration&nbsp;team if you have any further questions. </p><p>\r\nThanks,\r\n</p><p> [[from]]</p>', 1, '2021-12-14 10:05:31', '2021-12-14 10:07:03'),
(null, 'gallery_image_view_request_accepted_email', 'Gallery Image View Request Accepted.', '<p>Hi [[name]], </p><p>\r\n[[member_name]] has accepted your Gallery Image View Request<br></p><p>Please contact the&nbsp;administration&nbsp;team if you have any further questions. </p><p>\r\nThanks,\r\n</p><p> [[from]]</p>', 1, '2021-12-14 10:05:51', '2021-12-14 10:08:14');

CREATE TABLE `view_gallery_images` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `requested_by` bigint(20) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0 COMMENT '0= pending 1=Accepted',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `view_gallery_images`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `view_gallery_images`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;


CREATE TABLE `view_profile_pictures` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `requested_by` bigint(20) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0 COMMENT '0= pending 1=Accepted',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `view_profile_pictures`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `view_profile_pictures`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

CREATE TABLE `transactions` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `gateway` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `additional_content` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `transactions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

INSERT INTO `permissions` (`id`, `name`, `parent`, `guard_name`, `created_at`, `updated_at`)
VALUES
(NULL, 'show_unapproved_profile_picrures', 'member', 'web', '2022-07-27 11:28:21', NULL),
(NULL, 'approve_profile_picrures', 'member', 'web', '2022-07-27 11:28:21', NULL);

COMMIT;
