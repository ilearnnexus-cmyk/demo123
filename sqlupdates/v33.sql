ALTER TABLE `members`
CHANGE `birthday` `birthday` DATETIME NULL DEFAULT NULL,
CHANGE `current_package_id` `current_package_id` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;

ALTER TABLE `users` ADD `balance` DOUBLE(20,0) NOT NULL DEFAULT '0.00' AFTER `approved`;

UPDATE `settings` SET `value` = '3.3' WHERE `settings`.`type` = 'current_version';

ALTER TABLE `settings` CHANGE `id` `id` BIGINT(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `permissions` CHANGE `id` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;

INSERT INTO `settings` (`id`, `type`, `value`, `created_at`, `updated_at`, `deleted_at`)
VALUES
(NULL, 'wallet_system', '1', current_timestamp(), current_timestamp(), NULL);

ALTER TABLE `physical_attributes` CHANGE `height` `height` DOUBLE(5,2) NULL DEFAULT NULL;
ALTER TABLE `partner_expectations`
CHANGE `height` `height` DOUBLE(5,2) NULL DEFAULT NULL, 
CHANGE `weight` `weight` DOUBLE(5,2) NULL DEFAULT NULL;

CREATE TABLE `wallets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` double(20,2) NOT NULL,
  `payment_method` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_details` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `approval` int(1) NOT NULL DEFAULT 0,
  `offline_payment` int(1) NOT NULL DEFAULT 0,
  `reciept` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `wallets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `permissions` (`id`, `name`, `parent`, `guard_name`, `created_at`, `updated_at`)
VALUES
(NULL, 'wallet_transaction_history', 'wallet', 'web', '2022-07-27 11:28:21', NULL),
(NULL, 'manage_wallet_withdraw_requests', 'wallet', 'web', '2022-07-27 12:35:59', NULL)
(NULL, 'offline_wallet_recharge_requests', 'wallet', 'web', '2022-07-27 11:46:08', NULL),
(NULL, 'set_referral_commission', 'referral_system', 'web', '2022-07-27 12:03:32', NULL),
(NULL, 'view_refferal_users', 'referral_system', 'web', '2022-07-27 11:45:54', NULL),
(NULL, 'view_refferal_earnings', 'referral_system', 'web', '2022-07-27 12:07:11', NULL);

COMMIT;
