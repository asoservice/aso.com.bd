INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES (NULL, 'backend.customer.index', 'web', NULL, NULL), (NULL, 'backend.customer.create', 'web', NULL, NULL), (NULL, 'backend.customer.edit', 'web', NULL, NULL), (NULL, 'backend.customer.destroy', 'web', NULL, NULL);
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES (NULL, 'backend.additional-service.index', 'web', '2024-10-03 14:34:22', '2024-10-03 14:34:22'), (NULL, 'backend.additional-service.create', 'web', '2024-10-03 14:34:22', '2024-10-03 14:34:22'), (NULL, 'backend.additional-service.edit', 'web', '2024-10-03 14:34:22', '2024-10-03 14:34:22'), (NULL, 'backend.additional-service.destroy', 'web', '2024-10-03 14:34:22', '2024-10-03 14:34:22')
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('125', '1'), ('126', '1'), ('127', '1'), ('128', '1'), ('125', '3');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('135', '1'), ('136', '1'), ('137', '1'), ('138', '1'), ('135', '3'), ('136', '3'), ('137', '3'), ('138', '3')
DELETE FROM `role_has_permissions` WHERE `role_has_permissions`.`permission_id` = 125 AND `role_has_permissions`.`role_id` = 3;
UPDATE `permissions` SET `name` = 'backend.wallet.credit' WHERE `permissions`.`id` = 52;
UPDATE `permissions` SET `name` = 'backend.wallet.debit' WHERE `permissions`.`id` = 53;

DELETE FROM `role_has_permissions` WHERE `role_has_permissions`.`permission_id` = 32 AND `role_has_permissions`.`role_id` = 2;
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES ('129', 'backend.serviceman_wallet.index', 'web', NULL, NULL), ('130', 'backend.serviceman_wallet.credit', 'web', NULL, NULL), ('131', 'backend.serviceman_wallet.debit', 'web', NULL, NULL);
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('129', '1'), ('130', '1'), ('131', '1'), ('129', '4');

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('101', '2');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('101', '3'), ('102', '3'), ('103', '3'), ('104', '3');

ALTER TABLE `exclude_services_coupons` ADD PRIMARY KEY( `id`);
ALTER TABLE `exclude_services_coupons` CHANGE `id` `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `services_coupons` CHANGE `id` `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;
UPDATE `currencies` SET `system_reserve` = '0' WHERE `currencies`.`id` = 1;

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES ('132', 'backend.serviceman_withdraw_request.index', 'web', NULL, NULL), ('133', 'backend.serviceman_withdraw_request.create', 'web', NULL, NULL), ('134', 'backend.serviceman_withdraw_request.action', 'web', NULL, NULL);
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('132', '1'), ('133', '1'), ('134', '1'), ('132', '3'), ('134', '3'), ('132', '4'), ('133', '4');

UPDATE `settings` SET `values` = '{\r\n    \"mail\": null,\r\n    \"email\": {\r\n        \"mail_host\": \"smtp.gmail.com\",\r\n        \"mail_port\": \"587\",\r\n        \"mail_mailer\": \"smtp\",\r\n        \"mail_password\": \"kpsdqncnjdwgbeld\",\r\n        \"mail_username\": \"fixit.pixelstrap@gmail.com\",\r\n        \"mail_from_name\": \"Fixit\",\r\n        \"mail_encryption\": \"tls\",\r\n        \"mail_from_address\": \"fixit.pixelstrap@gmail.com\"\r\n    },\r\n    \"general\": {\r\n        \"mode\": \"light\",\r\n        \"favicon\": \"https://laravel.pixelstrap.net/fixit/storage/545/faviconIcon.png\",\r\n        \"copyright\": \"Copyright 2024 Â© Fixit theme by pwixelstrap\",\r\n        \"dark_logo\": \"https://laravel.pixelstrap.net/fixit/storage/547/logo-dark.png\",\r\n        \"site_name\": \"Fixit\",\r\n        \"light_logo\": \"https://laravel.pixelstrap.net/fixit/storage/546/Logo-Light.png\",\r\n        \"platform_fees\": \"10\",\r\n        \"default_timezone\": \"Pacific/Wake\",\r\n        \"min_booking_amount\": \"10\",\r\n        \"platform_fees_type\": \"fixed\",\r\n        \"default_currency_id\": \"1\",\r\n        \"default_language_id\": \"1\"\r\n    },\r\n    \"firebase\": {\r\n        \"service_json\": {\r\n            \"type\": \"service_account\",\r\n            \"auth_uri\": \"https://accounts.google.com/o/oauth2/auth\",\r\n            \"client_id\": \"106003570537958311205\",\r\n            \"token_uri\": \"https://oauth2.googleapis.com/token\",\r\n            \"project_id\": \"fixit-db226\",\r\n            \"private_key\": \"-----BEGIN PRIVATE KEY-----\\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDT2xgBDx+q1gxt\\nyPN9guKC8JVIstknDYa6xrfK5zsofUiTHlirWq0VJ/+mHlfmkBBABJ14WaEmVQ8p\\nGVpDQovwR1HtGzDkoHM9pwOmGZBzQv41bR3YPSw53JaJqHJakCOrbgE/O6rUJLIM\\nLcjxTCUL9OQrFtuaOV6y3VbHnOlidpE2nWr9E15DKm6XntHvRWL1bVq3SL9ZVD7P\\nzvE9mHOIm+SQRjGRuboHogeilyjXmC0Nhts37wLb/M6vs+v/xD98lW921B9x3S2Z\\nMqgKGj8YWsGCu1BeFtgoOHz60qhBPktVMjoIwBpaoEsNhBLcsgLUX1XO0KnO11i4\\nj+nHC/LnAgMBAAECggEAU21j9obOIahJHLKVsEdqi8XSA97qRMa+166Jkg2c7kTn\\n34eDw3bh0gL+WZx5YQI6Y/ttR4eEPmQgpD6nnPUHxodPa9/ZUS8eMpkihrZqe/lV\\nwhRGPHFaiS6k2XDMF33LjiaztwL4MrKAquscxmkF7b9yWsWVlRYihK1FDzZrcaon\\ngnaM3ZTZH+zR81pDmQmJhRVkMQQiB9ADUqYxH7ZfKLTvq0kUGx2qJ9oSULyf2XjP\\nkjT4opMNLCX45z6myB1Uxp/WsjUoxkBfQU7R7++kxS/VWJ9Dp9xXxaE5wEDRDIB3\\nppj5Tb9Hu9uwL0JXYd18Zz3nj0vR2NdKb8uvTkURgQKBgQD6GHAiGcGUxSeM6li9\\nRhMWQ0Lni5hs425CAaMIVnw7VwFB35bmPMBuwc/l1C3pVN5fMQVFqb+kTE6q5yvB\\nIm/yXewJ3IrtbarCqrFbAMNJZamn/8+ib71Vhn8DsaX3rpgBSw/P4+6GJAmlVIDB\\n/LvIjKHWOPGXQwWyuNGLf1cakwKBgQDY24mpLyqE4cf7Oj8HS+c0RKqYkBHmGfwY\\ntkVEGkyjLHwUe79UftsWNW6FDUVfqxxurzqId1+esC9HrfPK77LsbQTXOBXSrUql\\nUgkf+ZyWQ93AZBXGTUIu+MRuUfUgzskqEZZu1qXhE8wDaXlUpGWo2sM5TfEncyDP\\n/KrbFiU23QKBgF4EY9sd7Zz8xNJ/op58wl4jKPqcis+ca+2aaeyPfqJcIdfesv6Y\\npgq9B2ex7RSDWBlW91Fp7+ZW3Vf4EYXIaWcmkb5fT0bUbFZEDupUDhYAhtfmHetF\\nsFp/di4wUWEcHH6X9jjDyf5Ze9rQOpsyZHGPFKPQwlmH05ONURDs7RTLAoGBAM9m\\nkC9N29WA9rlwyI0a7AISVjJZP7UZTwD3eiGbIYbB6d3RSHjwZmrEKXJ48cuApE27\\nqziPKtVjXaSpWsvRGgeCcKnBiyWV9RlN70o0ea1BNRlm32hrxYuVApEcM1vwSXbB\\noWVaRwWP4IO24YKxREUNDL+Gqsh3FH+3AFVOxcLFAoGAOqs1vG0wZwPoEegYs5dB\\nuDfX4oKibtFN+hegKeRZeG6p0QBEeP/FQtKiUFzSzEfooni4kWtI9hDWnHz5VMR/\\na+de7UQrU9sSHRy/42EEZV9mZ+YFfuiSm15OgipHaVK3Mc0EysYxq+qOj1BV2FQT\\nQTR3pra+lZtYzcDkhs2295I=\\n-----END PRIVATE KEY-----\\n\",\r\n            \"client_email\": \"firebase-adminsdk-fd7gn@fixit-db226.iam.gserviceaccount.com\",\r\n            \"private_key_id\": \"cf47d079e87639acd62596406da41f03d3dd9dcd\",\r\n            \"universe_domain\": \"googleapis.com\",\r\n            \"client_x509_cert_url\": \"https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-fd7gn%40fixit-db226.iam.gserviceaccount.com\",\r\n            \"auth_provider_x509_cert_url\": \"https://www.googleapis.com/oauth2/v1/certs\"\r\n        },\r\n        \"google_map_api_key\": \"AIzaSyDNuJFHTBoAJeSsDdJhyuQrpkDo5_bl6As\"\r\n    },\r\n    \"activation\": {\r\n        \"cash\": \"1\",\r\n\"subscription_enable\": \"1\",\r\n        \"coupon_enable\": \"1\",\r\n        \"wallet_enable\": \"1\",\r\n        \"default_credentials\": \"1\",\r\n        \"extra_charge_status\": \"1\",\r\n        \"platform_fees_status\": \"1\",\r\n        \"service_auto_approve\": \"1\",\r\n        \"provider_auto_approve\": \"1\"\r\n    },\r\n    \"google_reCaptcha\": {\r\n        \"secret\": null,\r\n        \"status\": \"0\",\r\n        \"site_key\": null\r\n    },\r\n    \"subscription_plan\": {\r\n        \"free_trial_days\": \"7\",\r\n        \"free_trial_enabled\": \"1\"\r\n    },\r\n    \"provider_commissions\": {\r\n        \"status\": \"0\",\r\n        \"min_withdraw_amount\": \"500\",\r\n        \"default_commission_rate\": \"10\",\r\n        \"is_category_based_commission\": \"1\"\r\n    },\r\n    \"default_creation_limits\": {\r\n        \"allowed_max_services\": \"5\",\r\n        \"allowed_max_addresses\": \"5\",\r\n        \"allowed_max_servicemen\": \"10\",\r\n        \"allowed_max_service_packages\": \"3\"\r\n    }\r\n}' WHERE `settings`.`id` = 1;

CREATE TABLE `serviceman_wallets` (
  `id` bigint UNSIGNED NOT NULL,
  `serviceman_id` bigint UNSIGNED DEFAULT NULL,
  `balance` decimal(8,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `serviceman_wallets`
--
ALTER TABLE `serviceman_wallets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `serviceman_wallets_serviceman_id_foreign` (`serviceman_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `serviceman_wallets`
--
ALTER TABLE `serviceman_wallets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `serviceman_wallets`
--
ALTER TABLE `serviceman_wallets`
  ADD CONSTRAINT `serviceman_wallets_serviceman_id_foreign` FOREIGN KEY (`serviceman_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;


CREATE TABLE `serviceman_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `serviceman_wallet_id` bigint UNSIGNED DEFAULT NULL,
  `serviceman_id` bigint UNSIGNED DEFAULT NULL,
  `amount` decimal(8,2) NOT NULL DEFAULT '0.00',
  `type` enum('credit','debit') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `detail` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `serviceman_transactions`
--
ALTER TABLE `serviceman_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `serviceman_transactions_serviceman_wallet_id_foreign` (`serviceman_wallet_id`),
  ADD KEY `serviceman_transactions_serviceman_id_foreign` (`serviceman_id`),
  ADD KEY `serviceman_transactions_from_foreign` (`from`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `serviceman_transactions`
--
ALTER TABLE `serviceman_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `serviceman_transactions`
--
ALTER TABLE `serviceman_transactions`
  ADD CONSTRAINT `serviceman_transactions_from_foreign` FOREIGN KEY (`from`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `serviceman_transactions_serviceman_id_foreign` FOREIGN KEY (`serviceman_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `serviceman_transactions_serviceman_wallet_id_foreign` FOREIGN KEY (`serviceman_wallet_id`) REFERENCES `serviceman_wallets` (`id`) ON DELETE CASCADE;


CREATE TABLE `serviceman_withdraw_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `amount` decimal(8,2) DEFAULT '0.00',
  `message` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_message` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `serviceman_wallet_id` bigint UNSIGNED DEFAULT NULL,
  `serviceman_id` bigint UNSIGNED DEFAULT NULL,
  `payment_type` enum('paypal','bank') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'bank',
  `is_used_by_admin` int NOT NULL DEFAULT '0',
  `is_used` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `withdraw_requests`
--
ALTER TABLE `serviceman_withdraw_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `serviceman_withdraw_requests_serviceman_wallet_id_foreign` (`serviceman_wallet_id`),
  ADD KEY `serviceman_withdraw_requests_serviceman_id_foreign` (`serviceman_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `withdraw_requests`
--
ALTER TABLE `serviceman_withdraw_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `withdraw_requests`
--
ALTER TABLE `serviceman_withdraw_requests`
  ADD CONSTRAINT `serviceman_withdraw_requests_serviceman_id_foreign` FOREIGN KEY (`serviceman_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `serviceman_withdraw_requests_serviceman_wallet_id_foreign` FOREIGN KEY (`serviceman_wallet_id`) REFERENCES `serviceman_wallets` (`id`) ON DELETE CASCADE;
COMMIT;

ALTER TABLE `users` DROP FOREIGN KEY `users_company_id_foreign`; ALTER TABLE `users` ADD CONSTRAINT `users_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `time_slots` DROP FOREIGN KEY `time_slots_provider_id_foreign`; ALTER TABLE `time_slots` ADD CONSTRAINT `time_slots_provider_id_foreign` FOREIGN KEY (`provider_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT; ALTER TABLE `time_slots` DROP FOREIGN KEY `time_slots_serviceman_id_foreign`; ALTER TABLE `time_slots` ADD CONSTRAINT `time_slots_serviceman_id_foreign` FOREIGN KEY (`serviceman_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

UPDATE `currencies` SET `system_reserve` = '0' WHERE `currencies`.`id` = 1;

ALTER TABLE `services` ADD `per_serviceman_commission` DECIMAL(4,2) NULL DEFAULT NULL AFTER `discount`;

DELETE FROM `role_has_permissions` WHERE `role_has_permissions`.`permission_id` = 32 AND `role_has_permissions`.`role_id` = 4;

--
-- Table structure for table `serviceman_commissions`
--

CREATE TABLE `serviceman_commissions` (
  `id` bigint UNSIGNED NOT NULL,
  `commission_history_id` bigint UNSIGNED NOT NULL,
  `serviceman_id` bigint UNSIGNED NOT NULL,
  `commission` decimal(8,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `serviceman_commissions`
--
ALTER TABLE `serviceman_commissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `serviceman_commissions_commission_history_id_foreign` (`commission_history_id`),
  ADD KEY `serviceman_commissions_serviceman_id_foreign` (`serviceman_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `serviceman_commissions`
--
ALTER TABLE `serviceman_commissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `serviceman_commissions`
--
ALTER TABLE `serviceman_commissions`
  ADD CONSTRAINT `serviceman_commissions_commission_history_id_foreign` FOREIGN KEY (`commission_history_id`) REFERENCES `commission_histories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `serviceman_commissions_serviceman_id_foreign` FOREIGN KEY (`serviceman_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('39', '4')

-------------------- New Query :- 26-08-2024 -: -------------------------

--
-- Table structure for table `booking_additional_services`
--

CREATE TABLE `booking_additional_services` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_id` bigint UNSIGNED NOT NULL,
  `additional_service_id` bigint UNSIGNED NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `booking_additional_services`
--

--
-- Table structure for table `service_requests`
--

CREATE TABLE `service_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `duration` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration_unit` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `required_servicemen` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `initial_price` decimal(10,2) DEFAULT NULL,
  `final_price` decimal(10,2) DEFAULT NULL,
  `status` enum('open','pending','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `service_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `provider_id` bigint UNSIGNED DEFAULT NULL,
  `created_by_id` bigint UNSIGNED DEFAULT NULL,
  `booking_date` datetime DEFAULT NULL,
  `category_ids` json DEFAULT NULL,
  `locations` json DEFAULT NULL,
  `location_coordinates` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_requests_service_id_foreign` (`service_id`),
  ADD KEY `service_requests_user_id_foreign` (`user_id`),
  ADD KEY `service_requests_provider_id_foreign` (`provider_id`),
  ADD KEY `service_requests_created_by_id_foreign` (`created_by_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `service_requests`
--
ALTER TABLE `service_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD CONSTRAINT `service_requests_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_requests_provider_id_foreign` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_requests_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

--
-- Table structure for table `bids`
--

CREATE TABLE `bids` (
  `id` bigint UNSIGNED NOT NULL,
  `service_request_id` bigint UNSIGNED DEFAULT NULL,
  `provider_id` bigint UNSIGNED DEFAULT NULL,
  `amount` decimal(8,4) DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `status` enum('rejected','accepted','requested') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'requested',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bids`
--
ALTER TABLE `bids`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bids_service_request_id_foreign` (`service_request_id`),
  ADD KEY `bids_provider_id_foreign` (`provider_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bids`
--
ALTER TABLE `bids`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bids`
--
ALTER TABLE `bids`
  ADD CONSTRAINT `bids_provider_id_foreign` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bids_service_request_id_foreign` FOREIGN KEY (`service_request_id`) REFERENCES `service_requests` (`id`) ON DELETE CASCADE;
COMMIT;

--
-- Table structure for table `service_request_zones`
--

CREATE TABLE `service_request_zones` (
  `id` bigint UNSIGNED NOT NULL,
  `service_request_id` bigint UNSIGNED DEFAULT NULL,
  `zone_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `service_request_zones`
--
ALTER TABLE `service_request_zones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_request_zones_service_request_id_foreign` (`service_request_id`),
  ADD KEY `service_request_zones_zone_id_foreign` (`zone_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `service_request_zones`
--
ALTER TABLE `service_request_zones` MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `service_request_zones`
--
ALTER TABLE `service_request_zones`
  ADD CONSTRAINT `service_request_zones_service_request_id_foreign` FOREIGN KEY (`service_request_id`) REFERENCES `service_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_request_zones_zone_id_foreign` FOREIGN KEY (`zone_id`) REFERENCES `zones` (`id`) ON DELETE CASCADE;
COMMIT;


ALTER TABLE `services` ADD `parent_id` BIGINT UNSIGNED NULL DEFAULT NULL AFTER `user_id`; 

INSERT INTO `modules` (`id`, `name`, `actions`, `created_at`, `updated_at`) VALUES (NULL, 'service_request', '{\"index\":\"backend.service_request.index\",\"create\":\"backend.service_request.create\",\"edit\":\"backend.service_request.edit\",\"destroy\":\"backend.service_request.destroy\"}', '2024-10-07 18:52:20', '2024-10-07 18:52:20'), (NULL, 'bids', '{\"index\":\"backend.bid.index\",\"create\":\"backend.bid.create\",\"edit\":\"backend.bid.edit\",\"destroy\":\"backend.bid.destroy\"}', NULL, NULL);

--
-- Table structure for table `banner_zones`
--

CREATE TABLE `banner_zones` (
  `id` bigint UNSIGNED NOT NULL,
  `banner_id` bigint UNSIGNED NOT NULL,
  `zone_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banner_zones`
--
ALTER TABLE `banner_zones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `banner_zones_banner_id_foreign` (`banner_id`),
  ADD KEY `banner_zones_zone_id_foreign` (`zone_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banner_zones`
--
ALTER TABLE `banner_zones`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `banner_zones`
--
ALTER TABLE `banner_zones`
  ADD CONSTRAINT `banner_zones_banner_id_foreign` FOREIGN KEY (`banner_id`) REFERENCES `banners` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `banner_zones_zone_id_foreign` FOREIGN KEY (`zone_id`) REFERENCES `zones` (`id`) ON DELETE CASCADE;
COMMIT;


INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES (150, 'backend.review.edit', 'web', '2024-10-11 09:41:01', '2024-10-11 09:41:01');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('150', '1'), ('150', '2');
UPDATE `modules` SET `actions` = '{\r\n \"index\": \"backend.review.index\",\r\n \"create\": \"backend.review.create\",\r\n \"edit\": \"backend.review.edit\",\r\n \"destroy\": \"backend.review.destroy\"\r\n}' WHERE `modules`.`id` = 17;


--
-- Table structure for table `coupon_users`
--

CREATE TABLE `coupon_users` (
  `id` bigint UNSIGNED NOT NULL,
  `coupon_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_zones`
--

CREATE TABLE `coupon_zones` (
  `id` bigint UNSIGNED NOT NULL,
  `coupon_id` bigint UNSIGNED NOT NULL,
  `zone_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Indexes for dumped tables
--

--
-- Indexes for table `coupon_users`
--
ALTER TABLE `coupon_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coupon_users_coupon_id_foreign` (`coupon_id`),
  ADD KEY `coupon_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `coupon_zones`
--
ALTER TABLE `coupon_zones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coupon_zones_coupon_id_foreign` (`coupon_id`),
  ADD KEY `coupon_zones_zone_id_foreign` (`zone_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `coupon_users`
--
ALTER TABLE `coupon_users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupon_zones`
--
ALTER TABLE `coupon_zones`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `coupon_users`
--
ALTER TABLE `coupon_users`
  ADD CONSTRAINT `coupon_users_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coupon_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `coupon_zones`
--
ALTER TABLE `coupon_zones`
  ADD CONSTRAINT `coupon_zones_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coupon_zones_zone_id_foreign` FOREIGN KEY (`zone_id`) REFERENCES `zones` (`id`) ON DELETE CASCADE;
COMMIT;


ALTER TABLE `services` ADD COLUMN `destination_location` JSON NULL;

ALTER TABLE `services` CHANGE `type` `type` ENUM('fixed','provider_site','remotely') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'fixed';
ALTER TABLE `bookings` ADD `type` ENUM('fixed','provider_site','remotely','') NOT NULL DEFAULT 'fixed' AFTER `service_price`;


-- Insert modules
INSERT INTO `modules` (`id`, `name`, `actions`, `created_at`, `updated_at`) VALUES
(NULL, 'sms_templates', '{\"index\":\"backend.sms_template.index\",\"edit\":\"backend.sms_template.edit\"}', '2024-10-17 11:57:19', '2024-10-17 11:57:19'),
(NULL, 'email_templates', '{\"index\":\"backend.email_template.index\",\"edit\":\"backend.email_template.edit\"}', '2024-10-17 11:57:19', '2024-10-17 11:57:19'),
(NULL, 'push_notification_templates', '{\"index\":\"backend.push_notification_template.index\",\"edit\":\"backend.push_notification_template.edit\"}', '2024-10-17 11:57:19', '2024-10-17 11:57:19');

-- Insert permissions
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(160, 'backend.sms_template.index', 'web', '2024-10-17 12:18:21', '2024-10-17 12:18:21'),
(161, 'backend.sms_template.edit', 'web', '2024-10-17 12:18:21', '2024-10-17 12:18:21'),
(162, 'backend.email_template.index', 'web', '2024-10-17 12:18:21', '2024-10-17 12:18:21'),
(163, 'backend.email_template.edit', 'web', '2024-10-17 12:18:21', '2024-10-17 12:18:21'),
(164, 'backend.push_notification_template.index', 'web', '2024-10-17 12:18:21', '2024-10-17 12:18:21'),
(165, 'backend.push_notification_template.edit', 'web', '2024-10-17 12:18:21', '2024-10-17 12:18:21');

-- Assign permissions to roles
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
('160', '1'),
('161', '1'),
('162', '1'),
('163', '1'),
('164', '1'),
('165', '1');

INSERT INTO `modules` (`id`, `name`, `actions`, `created_at`, `updated_at`) VALUES (NULL, 'sms_gateways', '{\"index\":\"backend.sms_gateway.index\",\"edit\":\"backend.sms_gateway.edit\"}', '2024-10-21 15:56:13', '2024-10-21 15:56:13');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES (166, 'backend.sms_gateway.index', 'web', '2024-10-21 15:59:23', '2024-10-21 15:59:23'), (167, 'backend.sms_gateway.edit', 'web', '2024-10-21 15:59:23', '2024-10-21 15:59:23');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('166', '1'), ('167', '1');


INSERT INTO `modules` (`id`, `name`, `actions`, `created_at`, `updated_at`) VALUES (NULL, 'custom_sms_gateways', '{\"index\":\"backend.custom_sms_gateway.index\",\"edit\":\"backend.custom_sms_gateway.edit\"}', '2024-10-23 16:50:56', '2024-10-23 16:50:56');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES (168, 'backend.custom_sms_gateway.index', 'web', '2024-10-23 16:49:03', '2024-10-23 16:49:03'), (169, 'backend.custom_sms_gateway.edit', 'web', '2024-10-23 16:49:03', '2024-10-23 16:49:03');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('168', '1'), ('169', '1');

INSERT INTO `modules` (`id`, `name`, `actions`, `created_at`, `updated_at`) VALUES (NULL, 'provider_dashboard', '{\"index\":\"backend.provider_dashboard.index\"}', '2024-10-25 17:21:37', '2024-10-25 17:21:37'), (NULL, 'consumer_dashboard', '{\"index\":\"backend.consumer_dashboard.index\"}', '2024-10-25 17:22:55', '2024-10-25 17:22:55'), (NULL, 'servicemen_dashboard', '{\"index\":\"backend.servicemen_dashboard.index\"}', '2024-10-25 17:22:55', '2024-10-25 17:22:55');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES (170, 'backend.provider_dashboard.index', 'web', '2024-10-25 17:27:33', '2024-10-25 17:27:33'), (171, 'backend.consumer_dashboard.index', 'web', '2024-10-25 17:27:33', '2024-10-25 17:27:33'), (172, 'backend.servicemen_dashboard.index', 'web', '2024-10-25 17:27:33', '2024-10-25 17:27:33');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('170', '1'), ('171', '1'), ('172', '1');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('172', '3');

CREATE TABLE `email_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `title` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `button_text` longtext COLLATE utf8mb4_unicode_ci,
  `button_url` longtext COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `email_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

CREATE TABLE `sms_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `title` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `url` longtext COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `sms_templates`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `sms_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;


CREATE TABLE `push_notification_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `title` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `url` longtext COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `push_notification_templates`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `push_notification_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

ALTER TABLE users ADD COLUMN location_cordinates JSON NULL;


INSERT INTO `modules` (`id`, `name`, `actions`, `created_at`, `updated_at`) VALUES (NULL, 'serviceman_locations', '{\r\n \"index\": \"backend.serviceman_location.index\",\r\n \"create\": \"backend.serviceman_location.create\",\r\n \"edit\": \"backend.serviceman_location.edit\"\r\n}', '2024-11-04 16:54:18', NULL);

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES (173, 'backend.serviceman_location.index', 'web', '2024-11-04 16:59:55', '2024-11-04 16:59:55'), (174, 'backend.serviceman_location.create', 'web', '2024-11-04 16:59:55', '2024-11-04 16:59:55'), (175, 'backend.serviceman_location.edit', 'web', '2024-11-04 17:00:52', '2024-11-04 17:00:52');

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('173', '1'), ('174', '1'), ('175', '1'), ('173', '3'), ('174', '3'), ('175', '3');

INSERT INTO `modules` (`id`, `name`, `actions`, `created_at`, `updated_at`) VALUES (NULL, 'unverified_user', '{\"index\":\"backend.unverified_user.index\",\"edit\":\"backend.unverified_user.edit\"}', '2024-11-06 09:17:29', '2024-11-06 09:17:29');
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES (176, 'backend.unverified_user.index', 'web', '2024-11-06 09:26:59', '2024-11-06 09:26:59'), (177, 'backend.unverified_user.edit', 'web', '2024-11-06 09:26:59', '2024-11-06 09:26:59');
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('176', '1'), ('177', '1');

CREATE TABLE `custom_sms_gateways` (
  `id` bigint UNSIGNED NOT NULL,
  `base_url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auth_token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_config` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `custom_keys` json DEFAULT NULL,
  `config` json DEFAULT NULL,
  `body` json DEFAULT NULL,
  `params` json DEFAULT NULL,
  `headers` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `custom_sms_gateways` (`id`, `base_url`, `method`, `sid`, `auth_token`, `is_config`, `from`, `custom_keys`, `config`, `body`, `params`, `headers`, `created_at`, `updated_at`) VALUES
(1, 'https://api.twilio.com/2010-04-01/Accounts/AC29dca1a7ff0237356fd999c6ecb5d992/Messages.json', 'post', 'AC29dca1a7ff0237356fd999c6ecb5d992', '562b4f1ff7ade37ee543ae6e18bee36d', '[\"sid\",\"auth_token\"]', '16572084747', NULL, NULL, '{\"To\": \"{to}\", \"Body\": \"{message}\", \"From\": \"+16572084747\"}', '{\"\": null}', '{\"\": null}', '2024-10-23 00:06:58', '2024-11-05 05:16:22');

ALTER TABLE `custom_sms_gateways`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `custom_sms_gateways`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`)
 VALUES (192, 'backend.service_request.index', 'web', '2024-11-06 09:26:59', '2024-11-06 09:26:59'),
  (193, 'backend.service_request.edit', 'web', '2024-11-06 09:26:59', '2024-11-06 09:26:59'),
  (194, 'backend.service_request.create', 'web', '2024-11-06 09:26:59', '2024-11-06 09:26:59'),
  (195, 'backend.service_request.destroy', 'web', '2024-11-06 09:26:59', '2024-11-06 09:26:59');



INSERT INTO `role_has_permissions` (`permission_id`, `role_id`)
 VALUES ('192', '1'), ('193', '1'), ('194', '1'), ('195', '1'), ('192', '3'), ('193', '3'), ('192', '2'), ('194', '2') ;


