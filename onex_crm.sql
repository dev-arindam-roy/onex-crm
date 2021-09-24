-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 22, 2021 at 06:46 AM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `onex_crm`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `email_verify_token` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `mobile_verified_at` timestamp NULL DEFAULT NULL,
  `email_verify_token_expire_at` timestamp NULL DEFAULT NULL,
  `profile_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_id_unique` (`email_id`),
  KEY `admins_role_id_foreign` (`role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `role_id`, `first_name`, `last_name`, `email_id`, `mobile_number`, `password`, `status`, `email_verify_token`, `email_verified_at`, `mobile_verified_at`, `email_verify_token_expire_at`, `profile_image`, `remember_token`, `created_at`, `updated_at`) VALUES
(4, 1, 'Arindam', 'Roy', 'arindam.roy.master@yopmail.com', '9836395513', '$2y$10$rddSjS2VDtrqDliSAmpPne6d.V01bbAN.kQ6ME8UpFN2cOMi1coZS', 0, NULL, '2021-06-08 10:21:18', NULL, NULL, NULL, NULL, NULL, '2021-06-08 10:21:18'),
(3, 1, 'xxx', 'xxx', 'arindam1987@yopmail.com', '1212121212', '$2y$10$neAv27gcR9nmKD6meGznXeJbCCsgmqg0acQfE1XWxmX9fNvcKR5uG', 1, NULL, '2021-06-09 14:05:16', '2021-06-09 18:30:00', NULL, NULL, NULL, NULL, '2021-06-09 14:05:16');

-- --------------------------------------------------------

--
-- Table structure for table `admin_roles`
--

DROP TABLE IF EXISTS `admin_roles`;
CREATE TABLE IF NOT EXISTS `admin_roles` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_roles_name_unique` (`name`),
  UNIQUE KEY `admin_roles_description_unique` (`description`) USING HASH
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_roles`
--

INSERT INTO `admin_roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'Onex Master - Admin Role have all access', NULL, NULL),
(2, 'User', 'Onex Master - User Role have limited access', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `business_accounts`
--

DROP TABLE IF EXISTS `business_accounts`;
CREATE TABLE IF NOT EXISTS `business_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `account_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `organization_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registered_address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `official_email_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `official_contact_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `official_fax_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `business_accounts_user_id_unique` (`user_id`),
  UNIQUE KEY `business_accounts_account_id_unique` (`account_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `business_accounts`
--

INSERT INTO `business_accounts` (`id`, `user_id`, `account_id`, `organization_name`, `business_name`, `business_logo`, `business_description`, `registered_address`, `official_email_id`, `official_contact_number`, `official_fax_number`, `status`, `created_at`, `updated_at`) VALUES
(1, 11, 'ONEX-0000000569', 'CSS Pvt. Ltd.', 'Creative Syntax@123', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-06-06 02:24:19', '2021-06-06 02:24:19'),
(2, 12, 'ONEX-0000000572', 'cssssss', 'sadassasa asdasdas1212', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-06-06 02:30:37', '2021-06-06 02:30:37'),
(3, 13, 'ONEX-0000000581', 'sdfsf', 'sdfdsfsd', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-06-06 03:28:22', '2021-06-06 03:28:22'),
(4, 14, 'ONEX-0000000583', 'Roy Technologies Pvt. Ltd', 'Creative Syntax', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-06-06 04:47:50', '2021-06-06 04:47:50'),
(5, 16, 'ONEX-0000000592', 'asdasd', 'asdsa', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-06-07 01:25:29', '2021-06-07 01:25:29'),
(6, 8, 'ONEX-0000000594', 'asdas', 'asdsad', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-06-07 01:49:43', '2021-06-07 01:49:43'),
(7, 17, 'ONEX-0000000601', 'cxzczx', 'zxczxc', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-06-07 02:43:55', '2021-06-07 02:43:55'),
(8, 18, 'ONEX-0000000605', 'Onex', 'Onex', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-06-07 14:38:08', '2021-06-07 14:38:08'),
(9, 19, 'ONEX-0000000607', 'sdfsdfsd', 'sdfsfsdf', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-06-07 23:22:03', '2021-06-07 23:22:03');

-- --------------------------------------------------------

--
-- Table structure for table `configurations`
--

DROP TABLE IF EXISTS `configurations`;
CREATE TABLE IF NOT EXISTS `configurations` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `configurations_key_unique` (`key`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `configurations`
--

INSERT INTO `configurations` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'last_account', '607', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2021_06_01_172716_create_users_table', 1),
(2, '2021_06_01_191826_create_business_accounts_table', 1),
(3, '2021_06_01_193843_create_status_master_table', 1),
(4, '2021_06_02_095602_create_configurations_table', 1),
(5, '2021_06_02_195104_create_jobs_table', 1),
(6, '2021_06_07_095652_create_reset_password_table', 2),
(7, '2021_06_07_192006_create_admins_table', 3),
(8, '2021_06_07_192543_create_admin_roles_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `reset_password`
--

DROP TABLE IF EXISTS `reset_password`;
CREATE TABLE IF NOT EXISTS `reset_password` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token_expire_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reset_password`
--

INSERT INTO `reset_password` (`id`, `user_type`, `email_id`, `token`, `token_expire_at`, `created_at`, `updated_at`) VALUES
(3, 'client', 'onex17@yopmail.com', 'MTYyMzA2Mjg5NGZjaTdsNno0dDFjbDEyYXg1dmxqbWlmcTNrdnFkNmxoMmdzcmg4ZjMwd3B2aTllYjBuengzaXg4cnNkdw==', '2021-06-08 05:18:14', '2021-06-07 05:18:14', '2021-06-07 05:18:14');

-- --------------------------------------------------------

--
-- Table structure for table `status_master`
--

DROP TABLE IF EXISTS `status_master`;
CREATE TABLE IF NOT EXISTS `status_master` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `status` tinyint(4) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `status_master`
--

INSERT INTO `status_master` (`id`, `status`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'Active', 'Active', NULL, NULL),
(2, 0, 'Inactive', 'Inactive', NULL, NULL),
(3, 2, 'Account Blocked', 'Account Blocked By ONEX admin', NULL, NULL),
(4, 3, 'Deleted', 'Deleted', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `hash_id` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sex` enum('male','female') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_owner` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=No, 1=Yes',
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `email_verify_token` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `mobile_verified_at` timestamp NULL DEFAULT NULL,
  `email_verify_token_expire_at` timestamp NULL DEFAULT NULL,
  `signup_completed_at` timestamp NULL DEFAULT NULL,
  `agree_signup_terms` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=No, 1=Yes',
  `profile_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_id_unique` (`email_id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_hash_id_unique` (`hash_id`) USING HASH
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `hash_id`, `first_name`, `last_name`, `email_id`, `username`, `mobile_number`, `password`, `sex`, `is_owner`, `status`, `email_verify_token`, `email_verified_at`, `mobile_verified_at`, `email_verify_token_expire_at`, `signup_completed_at`, `agree_signup_terms`, `profile_image`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, 'e65ba09c323cc3d4381a16a538f7acf2', 'Arindam', 'Roy', 'onext1@yopmail.com', '3b74156597e946cc4df8b91337abcf28', NULL, '3b74156597e946cc4df8b91337abcf28', NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2021-06-05 08:41:33', '2021-06-05 08:41:52'),
(3, '15004bd09b2d0bb6dee2dcb1ad6ffc37', 'Arindam', 'Roy', 'onext2@yopmail.com', 'aa483970ba93b38d7d478b46a2ced025', NULL, 'aa483970ba93b38d7d478b46a2ced025', NULL, 1, 0, NULL, '2021-06-05 08:43:15', NULL, NULL, NULL, 1, NULL, NULL, '2021-06-05 08:42:28', '2021-06-05 08:43:15'),
(4, '41ab75bb4f45481ce797da76f53a696e', 'Arindam', 'Roy', 'onext3@yopmail.com', '064613bd53a777fbb9489f4c206a708d', NULL, '064613bd53a777fbb9489f4c206a708d', NULL, 1, 0, NULL, '2021-06-05 08:44:48', NULL, NULL, NULL, 1, NULL, NULL, '2021-06-05 08:43:36', '2021-06-05 08:44:48'),
(5, '0d343d446b0375dfbbd6f40831baee9c', 'Arindam', 'Roy', 'onext4@yopmail.com', '715bd222e5c83e1479d6077a73579941', NULL, '715bd222e5c83e1479d6077a73579941', NULL, 1, 0, NULL, '2021-06-05 09:04:18', NULL, NULL, NULL, 1, NULL, NULL, '2021-06-05 09:00:41', '2021-06-05 09:04:18'),
(6, '0185150f39641400e5ad5df47ce06227', 'Arindam', 'Roy', 'onext5@yopmail.com', 'd0f55405fb69ac0a2e04e7ad511c1a6c', NULL, 'd0f55405fb69ac0a2e04e7ad511c1a6c', NULL, 1, 0, NULL, '2021-06-05 09:17:40', NULL, NULL, NULL, 1, NULL, NULL, '2021-06-05 09:11:25', '2021-06-05 09:17:40'),
(7, '64232df51c0c55184b25de52df11b6b6', 'Arindam', 'Roy', 'onext6@yopmail.com', '21cd8215be627c4bf04fe6c49b61ede0', NULL, '21cd8215be627c4bf04fe6c49b61ede0', NULL, 1, 0, NULL, '2021-06-05 11:20:37', NULL, NULL, NULL, 1, NULL, NULL, '2021-06-05 11:20:18', '2021-06-05 11:20:37'),
(8, 'ac7c05916e2e7fcaec44630816a76c67', 'Arindam', 'Roy', 'onex7@yopmail.com', 'hello3111111', '1111111100', '$2y$10$43K6SWTAd5gRk1YcsufN9uRrO9SvTDftIfMZlk8TAJb0F5TH090Fu', NULL, 1, 0, NULL, '2021-06-05 11:40:45', '2021-06-05 11:40:45', NULL, '2021-06-07 01:49:43', 1, NULL, NULL, '2021-06-05 11:40:07', '2021-06-07 01:49:43'),
(9, 'bca0044fc07b392e5e4e33f81fdb8718', 'Arindam', 'Roy', 'onex8@yopmail.com', 'Ari19871', '1232123431', '$2y$10$zfn96EaGz1AH6GiqNEdT4eiokq8ocYo6TeA5UdZUJQLKHeaRleKsy', NULL, 1, 0, NULL, '2021-06-05 13:59:46', NULL, NULL, NULL, 1, NULL, NULL, '2021-06-05 13:59:32', '2021-06-05 15:00:13'),
(10, '8783f0d03f4ca6ce44646f06b31098a9', 'Arindam', 'Roy', 'onex10@yopmail.com', '3f7109bada6ba1b302d8c4fd11bef9c4', NULL, '3f7109bada6ba1b302d8c4fd11bef9c4', NULL, 1, 0, NULL, '2021-06-06 00:52:44', NULL, NULL, NULL, 1, NULL, NULL, '2021-06-06 00:52:27', '2021-06-06 00:52:44'),
(11, '56784a57d83372789da35b275b2af189', 'Arindam', 'Roy', 'onex11@yopmail.com', 'Ari198710', '1212321234', '$2y$10$y1lyj4P0kfFdxqkUJdYvfOHNTe95leWv2dKCEwmr8LHHtB9MBjSd.', NULL, 1, 0, NULL, '2021-06-06 00:56:18', NULL, NULL, '2021-06-06 02:24:19', 0, NULL, NULL, '2021-06-06 00:56:05', '2021-06-06 02:24:19'),
(12, '89c595bd4062fdc2fb515ee9697c4f1d', 'Arindam', 'Roy', 'onex12@yopmail.com', 'Ari19879', '1212321234', '$2y$10$EhW6duV5mPt6NKowgu/ijOyx9BvKu5S35ncXPOuleRMsM0.yYpsle', NULL, 1, 0, NULL, '2021-06-06 02:29:22', NULL, NULL, '2021-06-06 02:30:37', 1, NULL, NULL, '2021-06-06 02:29:05', '2021-06-06 02:30:37'),
(13, '93339842162a14e6793be75f86815d1e', 'Arindam', 'Roy', 'onex13@yopmail.com', 'Ari1987232', '1232123432', '$2y$10$ofMHGnAbdCnkQDNJ3WtA6uXifOZ5N8NCDafZ7oudrind09vQ.PmxG', NULL, 1, 0, NULL, '2021-06-06 02:38:19', NULL, NULL, '2021-06-06 03:28:22', 1, NULL, NULL, '2021-06-06 02:38:03', '2021-06-06 03:28:22'),
(14, '7dff307bcca06d13d77c2b9fb885bb87', 'Arindam', 'Roy', 'onex14@yopmail.com', 'Arindam1987', '1212121212', '$2y$10$sLxtXvoynsaUYId4ifJMCOrSZV/KvODOtOP5G3/w.VgFkXNcYB7yW', NULL, 1, 0, NULL, '2021-06-06 04:46:41', NULL, NULL, '2021-06-06 04:47:50', 1, NULL, NULL, '2021-06-06 04:46:23', '2021-06-06 04:47:50'),
(15, '864f8c15cb8445bc4a90815fbd2154c1', 'Arindam', 'Roy', 'onex15@yopmail.com', 'd49e9eba8c5a62ad838612497fe00354', NULL, 'd49e9eba8c5a62ad838612497fe00354', NULL, 1, 0, 'ODY0ZjhjMTVjYjg0NDViYzRhOTA4MTVmYmQyMTU0YzFzajhvbzI2dzg3dXFubjN0NGdiYjN4dzhnZ2Y1Mjh4azJsYjFycm50enR0bHIyNjBqeXd1c2g2eGR3ZnI=', NULL, NULL, '2021-06-08 01:19:54', NULL, 1, NULL, NULL, '2021-06-07 01:19:25', '2021-06-07 01:19:54'),
(16, '0584730a4fea2ee018360d3f50363a81', 'Arindam', 'Roy', 'onex16@yopmail.com', 'ari898767', '1234321234', '$2y$10$C6gUrccEzIbrxyq72ykf0OoOGicNuMIioPi1RDEDEFKeTaHMtiGve', NULL, 1, 0, NULL, '2021-06-07 01:23:35', NULL, NULL, '2021-06-07 01:25:29', 1, NULL, NULL, '2021-06-07 01:22:02', '2021-06-08 14:30:24'),
(17, '39eb31526fa7fd113b1cce3681929195', 'Arindam', 'Roy', 'onex17@yopmail.com', 'sdfsdf', '1231231221', '$2y$10$XwFzJpUmUWO.iZ1jLs38QuelvFX/ryR63JC4Uj7qCOh9SpQR1QEWe', NULL, 1, 0, NULL, '2021-06-07 02:23:20', NULL, NULL, '2021-06-07 02:43:55', 1, NULL, NULL, '2021-06-07 02:23:04', '2021-06-07 02:43:55'),
(18, 'd275f56e5083f4a8c7006a43d82031b5', 'Arindam', 'Roy', 'onex18@yopmail.com', 'Ari18987', '1232123432', '$2y$10$gshyex35Ie7gbzq2BVubfei90X93TKsZUmzVsAsTQJmjyvKTQJtAe', NULL, 1, 1, NULL, '2021-06-07 14:37:24', NULL, NULL, '2021-06-07 14:38:08', 1, NULL, NULL, '2021-06-07 14:37:09', '2021-06-07 14:39:28'),
(19, 'ae18544f20b4707255d7b31f46ceda57', 'Arindam', 'Roy', 'onex19@yopmail.com', '123456', '1289876543', '$2y$10$XlnlWmY4HET.typGzrXD5uITrhbYyUe2T1z5msC6SJZqLj3R1dxQG', NULL, 1, 1, NULL, '2021-06-07 23:21:28', NULL, NULL, '2021-06-07 23:22:03', 1, NULL, NULL, '2021-06-07 23:21:06', '2021-06-07 23:22:03');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
