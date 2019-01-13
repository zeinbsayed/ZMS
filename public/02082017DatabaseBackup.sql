-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.9-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for pdreg
CREATE DATABASE IF NOT EXISTS `pdreg` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `pdreg`;

-- Dumping structure for table pdreg.entrypoints
CREATE TABLE IF NOT EXISTS `entrypoints` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` text COLLATE utf8_unicode_ci,
  `location` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.entrypoints: ~0 rows (approximately)
/*!40000 ALTER TABLE `entrypoints` DISABLE KEYS */;
INSERT INTO `entrypoints` (`id`, `name`, `type`, `location`, `created_at`, `updated_at`) VALUES
	(4, 'حجز تذاكر', 'r', 'العيادات', '2017-02-26 10:42:51', '2017-02-26 10:43:29');
/*!40000 ALTER TABLE `entrypoints` ENABLE KEYS */;

-- Dumping structure for table pdreg.entrypoint_user
CREATE TABLE IF NOT EXISTS `entrypoint_user` (
  `user_id` int(10) unsigned NOT NULL,
  `entrypoint_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `entrypoint_user_user_id_index` (`user_id`),
  KEY `entrypoint_user_entrypoint_id_index` (`entrypoint_id`),
  CONSTRAINT `entrypoint_user_entrypoint_id_foreign` FOREIGN KEY (`entrypoint_id`) REFERENCES `entrypoints` (`id`),
  CONSTRAINT `entrypoint_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.entrypoint_user: ~2 rows (approximately)
/*!40000 ALTER TABLE `entrypoint_user` DISABLE KEYS */;
INSERT INTO `entrypoint_user` (`user_id`, `entrypoint_id`, `created_at`, `updated_at`) VALUES
	(5, 4, '2017-02-26 10:45:30', '2017-02-26 10:45:30'),
	(7, 4, '2017-03-02 12:44:07', '2017-03-02 12:44:07');
/*!40000 ALTER TABLE `entrypoint_user` ENABLE KEYS */;

-- Dumping structure for table pdreg.medical_devices
CREATE TABLE IF NOT EXISTS `medical_devices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `location` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.medical_devices: ~0 rows (approximately)
/*!40000 ALTER TABLE `medical_devices` DISABLE KEYS */;
/*!40000 ALTER TABLE `medical_devices` ENABLE KEYS */;

-- Dumping structure for table pdreg.medical_order_items
CREATE TABLE IF NOT EXISTS `medical_order_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `visit_id` int(10) unsigned NOT NULL,
  `proc_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `medical_order_items_visit_id_index` (`visit_id`),
  CONSTRAINT `medical_order_items_visit_id_foreign` FOREIGN KEY (`visit_id`) REFERENCES `visits` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.medical_order_items: ~0 rows (approximately)
/*!40000 ALTER TABLE `medical_order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `medical_order_items` ENABLE KEYS */;

-- Dumping structure for table pdreg.medical_units
CREATE TABLE IF NOT EXISTS `medical_units` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `parent_department_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.medical_units: ~4 rows (approximately)
/*!40000 ALTER TABLE `medical_units` DISABLE KEYS */;
INSERT INTO `medical_units` (`id`, `name`, `type`, `parent_department_id`, `created_at`, `updated_at`) VALUES
	(42, 'اطفال', 'c', 43, '2017-02-26 10:45:41', '2017-02-26 10:45:53'),
	(43, 'اطفال', 'd', NULL, '2017-02-26 10:45:49', '2017-02-26 10:45:49'),
	(44, 'باطنة', 'c', 45, '2017-03-08 08:17:05', '2017-03-08 08:17:16'),
	(45, 'باطنة', 'd', NULL, '2017-03-08 08:17:11', '2017-03-08 08:17:11'),
	(46, 'جراحة', 'c', NULL, '2017-05-30 10:24:39', '2017-05-30 10:24:39');
/*!40000 ALTER TABLE `medical_units` ENABLE KEYS */;

-- Dumping structure for table pdreg.medical_unit_user
CREATE TABLE IF NOT EXISTS `medical_unit_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `medical_unit_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `medical_unit_user_medical_unit_id_index` (`medical_unit_id`),
  KEY `medical_unit_user_user_id_index` (`user_id`),
  CONSTRAINT `medical_unit_user_medical_unit_id_foreign` FOREIGN KEY (`medical_unit_id`) REFERENCES `medical_units` (`id`),
  CONSTRAINT `medical_unit_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.medical_unit_user: ~4 rows (approximately)
/*!40000 ALTER TABLE `medical_unit_user` DISABLE KEYS */;
INSERT INTO `medical_unit_user` (`id`, `medical_unit_id`, `user_id`, `created_at`, `updated_at`) VALUES
	(16, 43, 2, '2017-02-26 10:45:59', '2017-02-26 10:45:59'),
	(17, 42, 2, '2017-02-26 10:45:59', '2017-02-26 10:45:59'),
	(18, 45, 12, '2017-03-08 08:17:41', '2017-03-08 08:17:41'),
	(19, 44, 12, '2017-03-08 08:17:41', '2017-03-08 08:17:41');
/*!40000 ALTER TABLE `medical_unit_user` ENABLE KEYS */;

-- Dumping structure for table pdreg.medical_unit_visit
CREATE TABLE IF NOT EXISTS `medical_unit_visit` (
  `visit_id` int(10) unsigned NOT NULL,
  `medical_unit_id` int(10) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `convert_to` int(11) DEFAULT NULL,
  `department_conversion` tinyint(1) NOT NULL DEFAULT '0',
  `seen` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `medical_unit_visit_visit_id_index` (`visit_id`),
  KEY `medical_unit_visit_medical_unit_id_index` (`medical_unit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.medical_unit_visit: ~82 rows (approximately)
/*!40000 ALTER TABLE `medical_unit_visit` DISABLE KEYS */;
INSERT INTO `medical_unit_visit` (`visit_id`, `medical_unit_id`, `user_id`, `convert_to`, `department_conversion`, `seen`, `created_at`, `updated_at`) VALUES
	(34, 42, 0, NULL, 0, 0, '2017-02-26 13:13:19', '2017-02-26 13:13:19'),
	(35, 42, 0, NULL, 0, 0, '2017-02-28 08:25:52', '2017-02-28 08:25:52'),
	(36, 42, 0, NULL, 0, 0, '2017-03-01 08:00:36', '2017-03-01 08:00:36'),
	(37, 42, 0, NULL, 0, 0, '2017-03-02 09:26:52', '2017-03-02 09:26:52'),
	(38, 42, 0, NULL, 0, 0, '2017-03-02 12:44:40', '2017-03-02 12:44:40'),
	(39, 44, 0, 42, 0, 0, '2017-03-08 08:18:01', '2017-03-08 08:29:38'),
	(39, 42, 12, NULL, 0, 0, '2017-03-08 08:29:38', '2017-03-08 08:29:38'),
	(40, 42, 0, NULL, 0, 0, '2017-03-08 12:39:00', '2017-03-08 12:39:00'),
	(43, 42, 0, NULL, 0, 0, '2017-03-09 08:16:17', '2017-03-09 08:16:17'),
	(44, 42, 0, NULL, 0, 0, '2017-03-09 08:16:42', '2017-03-09 08:16:42'),
	(45, 42, 0, NULL, 0, 0, '2017-03-12 10:53:13', '2017-03-12 10:53:13'),
	(46, 42, 0, NULL, 0, 0, '2017-03-13 08:45:30', '2017-03-13 08:45:30'),
	(47, 42, 0, NULL, 0, 0, '2017-03-13 08:47:42', '2017-03-13 08:47:42'),
	(48, 42, 0, NULL, 0, 0, '2017-03-13 11:13:05', '2017-03-13 11:13:05'),
	(49, 42, 0, NULL, 0, 0, '2017-03-13 11:17:58', '2017-03-13 11:17:58'),
	(50, 42, 0, NULL, 0, 0, '2017-03-13 11:19:20', '2017-03-13 11:19:20'),
	(51, 42, 0, NULL, 0, 0, '2017-03-14 09:41:33', '2017-03-14 09:41:33'),
	(52, 42, 0, NULL, 0, 0, '2017-03-14 11:23:18', '2017-03-14 11:23:18'),
	(53, 42, 0, NULL, 0, 0, '2017-03-14 12:58:54', '2017-03-14 12:58:54'),
	(54, 42, 0, NULL, 0, 0, '2017-03-14 13:00:27', '2017-03-14 13:00:27'),
	(55, 42, 0, NULL, 0, 0, '2017-03-14 13:09:13', '2017-03-14 13:09:13'),
	(56, 42, 0, NULL, 0, 1, '2017-03-15 07:57:02', '2017-03-15 11:17:23'),
	(57, 42, 0, NULL, 0, 1, '2017-03-15 10:52:57', '2017-03-15 10:53:00'),
	(58, 42, 0, NULL, 0, 1, '2017-03-15 10:53:12', '2017-03-15 10:53:15'),
	(59, 42, 0, NULL, 0, 1, '2017-03-15 10:57:38', '2017-03-15 10:57:39'),
	(60, 42, 0, NULL, 0, 1, '2017-03-15 10:58:03', '2017-03-15 10:58:13'),
	(61, 42, 0, NULL, 0, 1, '2017-03-15 11:02:45', '2017-03-15 11:03:01'),
	(62, 42, 0, NULL, 0, 0, '2017-03-15 11:25:37', '2017-03-15 11:25:37'),
	(63, 42, 0, NULL, 0, 0, '2017-03-15 11:27:59', '2017-03-15 11:27:59'),
	(64, 42, 0, NULL, 0, 1, '2017-03-15 11:29:09', '2017-03-15 11:29:38'),
	(65, 42, 0, NULL, 0, 1, '2017-03-15 11:30:32', '2017-03-15 11:30:52'),
	(66, 42, 0, NULL, 0, 1, '2017-03-15 11:40:49', '2017-03-15 11:41:08'),
	(67, 42, 0, NULL, 0, 1, '2017-03-15 11:53:10', '2017-03-15 11:53:30'),
	(68, 42, 0, NULL, 0, 1, '2017-03-15 11:53:16', '2017-03-15 11:53:30'),
	(69, 42, 0, NULL, 0, 1, '2017-03-15 11:53:22', '2017-03-15 11:53:30'),
	(70, 42, 0, NULL, 0, 1, '2017-03-15 11:53:31', '2017-03-15 11:53:59'),
	(71, 42, 0, NULL, 0, 1, '2017-03-15 11:55:43', '2017-03-15 11:55:52'),
	(72, 42, 0, NULL, 0, 1, '2017-03-15 11:57:44', '2017-03-15 11:58:02'),
	(73, 42, 0, NULL, 0, 1, '2017-03-15 11:59:21', '2017-03-15 11:59:38'),
	(74, 42, 0, NULL, 0, 1, '2017-03-15 12:00:56', '2017-03-15 12:01:20'),
	(75, 42, 0, NULL, 0, 1, '2017-03-15 12:08:35', '2017-03-15 12:08:58'),
	(76, 42, 0, NULL, 0, 1, '2017-03-15 12:11:19', '2017-03-15 12:11:42'),
	(77, 42, 0, NULL, 0, 1, '2017-03-15 12:12:12', '2017-03-15 12:12:42'),
	(78, 42, 0, NULL, 0, 1, '2017-03-20 08:02:36', '2017-03-20 11:16:25'),
	(79, 42, 0, NULL, 0, 1, '2017-03-20 11:09:56', '2017-03-20 11:16:25'),
	(80, 42, 0, NULL, 0, 1, '2017-03-28 08:37:25', '2017-03-28 09:03:17'),
	(81, 42, 0, NULL, 0, 1, '2017-03-28 10:04:46', '2017-03-28 13:54:30'),
	(82, 42, 0, NULL, 0, 1, '2017-03-30 10:06:39', '2017-03-30 11:57:14'),
	(83, 42, 0, NULL, 0, 1, '2017-03-30 11:33:51', '2017-03-30 11:57:14'),
	(84, 42, 0, NULL, 0, 1, '2017-04-02 10:08:51', '2017-04-02 10:19:59'),
	(85, 42, 0, 44, 0, 1, '2017-04-02 10:21:00', '2017-04-02 12:14:01'),
	(85, 44, 2, NULL, 0, 1, '2017-04-02 12:14:01', '2017-04-02 12:21:10'),
	(86, 42, 0, NULL, 0, 1, '2017-04-02 12:22:36', '2017-04-02 12:32:47'),
	(87, 42, 0, NULL, 0, 1, '2017-04-02 12:22:45', '2017-04-02 12:32:47'),
	(88, 42, 0, NULL, 0, 1, '2017-04-03 08:15:18', '2017-04-03 10:06:23'),
	(89, 42, 0, NULL, 0, 0, '2017-04-03 08:15:19', '2017-04-03 08:15:19'),
	(90, 42, 0, NULL, 0, 1, '2017-04-03 08:25:49', '2017-04-03 10:06:23'),
	(91, 42, 0, NULL, 0, 0, '2017-04-26 11:15:49', '2017-04-26 11:15:49'),
	(92, 42, 0, NULL, 0, 0, '2017-04-26 11:34:03', '2017-04-26 11:34:03'),
	(93, 42, 0, NULL, 0, 0, '2017-04-26 12:04:38', '2017-04-26 12:04:38'),
	(94, 42, 0, NULL, 0, 0, '2017-04-26 12:05:09', '2017-04-26 12:05:09'),
	(95, 44, 0, NULL, 0, 0, '2017-04-27 10:43:02', '2017-04-27 10:43:02'),
	(96, 42, 0, NULL, 0, 0, '2017-04-27 10:45:46', '2017-04-27 10:45:46'),
	(97, 42, 0, NULL, 0, 0, '2017-04-27 11:38:37', '2017-04-27 11:38:37'),
	(98, 42, 0, 44, 0, 1, '2017-04-30 11:25:37', '2017-04-30 11:26:23'),
	(98, 44, 2, NULL, 0, 0, '2017-04-30 11:26:23', '2017-04-30 11:26:23'),
	(99, 42, 0, 43, 1, 1, '2017-04-30 11:35:18', '2017-04-30 11:36:58'),
	(99, 43, 2, NULL, 0, 0, '2017-04-30 11:36:58', '2017-04-30 11:36:58'),
	(100, 42, 0, NULL, 0, 0, '2017-05-09 08:14:12', '2017-05-09 08:14:12'),
	(101, 42, 0, NULL, 0, 0, '2017-05-09 08:14:52', '2017-05-09 08:14:52'),
	(102, 42, 0, NULL, 0, 0, '2017-05-10 11:29:29', '2017-05-10 11:29:29'),
	(103, 44, 0, NULL, 0, 0, '2017-05-11 11:44:37', '2017-05-11 11:44:37'),
	(104, 42, 0, NULL, 0, 0, '2017-05-18 08:56:37', '2017-05-18 08:56:37'),
	(105, 42, 0, NULL, 0, 0, '2017-05-18 08:58:55', '2017-05-18 08:58:55'),
	(106, 42, 0, NULL, 0, 0, '2017-05-18 09:00:47', '2017-05-18 09:00:47'),
	(107, 42, 0, NULL, 0, 0, '2017-05-18 09:21:15', '2017-05-18 09:21:15'),
	(108, 42, 0, NULL, 0, 1, '2017-05-28 11:46:45', '2017-05-28 11:46:49'),
	(109, 44, 0, NULL, 0, 0, '2017-05-30 09:58:04', '2017-05-30 09:58:04'),
	(110, 46, 0, NULL, 0, 0, '2017-05-30 10:27:31', '2017-05-30 10:27:31'),
	(120, 46, 0, NULL, 0, 0, '2017-05-30 10:35:45', '2017-05-30 10:35:45'),
	(124, 45, 0, NULL, 0, 0, '2017-05-30 10:55:15', '2017-05-30 10:55:15'),
	(125, 42, 0, NULL, 0, 0, '2017-05-31 10:49:42', '2017-05-31 10:49:42'),
	(126, 42, 0, NULL, 0, 0, '2017-05-31 10:50:04', '2017-05-31 10:50:04'),
	(127, 42, 0, NULL, 0, 0, '2017-06-04 10:41:34', '2017-06-04 10:41:34'),
	(128, 44, 0, NULL, 0, 0, '2017-06-04 10:46:41', '2017-06-04 10:46:41'),
	(129, 44, 0, NULL, 0, 0, '2017-06-04 10:48:45', '2017-06-04 10:48:45'),
	(130, 45, 0, NULL, 0, 0, '2017-06-04 11:21:50', '2017-06-04 11:21:50'),
	(131, 43, 0, NULL, 0, 0, '2017-06-04 11:24:01', '2017-06-04 11:24:01'),
	(132, 43, 0, NULL, 0, 0, '2017-06-04 11:38:34', '2017-06-04 11:38:34'),
	(133, 43, 0, NULL, 0, 0, '2017-06-05 09:09:42', '2017-06-05 09:09:42'),
	(134, 45, 0, NULL, 0, 0, '2017-06-05 09:10:44', '2017-06-05 09:10:44'),
	(135, 44, 0, NULL, 0, 0, '2017-06-05 09:41:05', '2017-06-05 09:41:05'),
	(136, 42, 0, NULL, 0, 0, '2017-06-05 09:41:43', '2017-06-05 09:41:43'),
	(137, 44, 0, NULL, 0, 0, '2017-06-07 08:57:05', '2017-06-07 08:57:05'),
	(138, 42, 0, NULL, 0, 0, '2017-06-07 09:03:33', '2017-06-07 09:03:33'),
	(139, 44, 0, NULL, 0, 0, '2017-06-07 09:03:55', '2017-06-07 09:03:55'),
	(140, 42, 0, NULL, 0, 0, '2017-06-07 12:15:00', '2017-06-07 12:15:00'),
	(141, 42, 0, NULL, 0, 1, '2017-06-13 12:49:55', '2017-06-13 13:23:33'),
	(142, 42, 0, NULL, 0, 1, '2017-06-21 11:54:33', '2017-06-21 11:54:44');
/*!40000 ALTER TABLE `medical_unit_visit` ENABLE KEYS */;

-- Dumping structure for table pdreg.mf_logs
CREATE TABLE IF NOT EXISTS `mf_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `loggable_id` int(11) NOT NULL,
  `loggable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `action` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `before` text COLLATE utf8_unicode_ci,
  `after` text COLLATE utf8_unicode_ci,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=455 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.mf_logs: ~225 rows (approximately)
/*!40000 ALTER TABLE `mf_logs` DISABLE KEYS */;
INSERT INTO `mf_logs` (`id`, `user_id`, `loggable_id`, `loggable_type`, `action`, `before`, `after`, `created_at`) VALUES
	(135, 5, 5, 'App\\User', 'updated', NULL, '{"remember_token":"qIIpSfOUzMGxxo7HKXIaNzE3Bf0LciPqsfgg2hTR12mwOjeQlS3TolGt2vrP"}', '2017-02-26 10:42:08'),
	(136, 1, 4, 'App\\Entrypoint', 'created', NULL, '{"name":"حجز تذاكر","type":"r","location":"العيا"}', '2017-02-26 10:42:51'),
	(137, 1, 4, 'App\\Entrypoint', 'updated', '{"location":"العيا"}', '{"location":"العيادات"}', '2017-02-26 10:43:29'),
	(138, 1, 42, 'App\\MedicalUnit', 'created', NULL, '{"name":"اطفال","type":"c"}', '2017-02-26 10:45:42'),
	(139, 1, 43, 'App\\MedicalUnit', 'created', NULL, '{"name":"اطفال","type":"d"}', '2017-02-26 10:45:49'),
	(140, 1, 42, 'App\\MedicalUnit', 'updated', '{"parent_department_id":null}', '{"parent_department_id":"43"}', '2017-02-26 10:45:53'),
	(141, 1, 1, 'App\\User', 'updated', NULL, '{"remember_token":"FARg3bPbDU54ltD0CPUC6PfPxNIAjtlLBXGVziHYNcBwAkiXK3AnsOJ8BrpV"}', '2017-02-26 10:46:06'),
	(142, 5, 5, 'App\\User', 'updated', NULL, '{"remember_token":"4SWiw0YsKFeJesaqXQ4k1ru2k4fejvSnYbiUIkD0jmQtdCJfHYmNokuuHa65"}', '2017-02-26 10:46:23'),
	(143, 1, 1, 'App\\User', 'updated', NULL, '{"remember_token":"9DgohhThrxoM5YaB1XmGUw6Ed7ngpqjuclGpkNECW8g8BucNnNmDdmhzMI6F"}', '2017-02-26 10:57:18'),
	(144, 1, 1, 'App\\User', 'updated', NULL, '{"remember_token":"5EFWJ5EYd68JzVwlkKh7pnPZCKvogC8dbvr2jteea5NZptbi9tjmn0gWSSzX"}', '2017-02-26 10:58:25'),
	(145, 5, 5, 'App\\User', 'updated', NULL, '{"remember_token":"GNhOuMSPLDTFeOnfic428Audz7aqHZA0OehQpi5pHDrWcwZtgstIWEQyYffm"}', '2017-02-26 10:58:48'),
	(146, 1, 1, 'App\\User', 'updated', NULL, '{"remember_token":"iXVOUnXhEARYn1oIUsDQl4U0lL5H8EQBgUnAHYuJ9BT32Mxm2uxUbTZJJGMi"}', '2017-02-26 10:59:05'),
	(147, 5, 5, 'App\\User', 'updated', NULL, '{"remember_token":"gIbl569kIc1uNhabr0kFSAKPJ54bhuYXV4qlZTk2E8Z90yKaUel7V9ku7HTg"}', '2017-02-26 11:01:17'),
	(148, 1, 1, 'App\\User', 'updated', NULL, '{"remember_token":"1BQai24yE1mDXNgqn6ORNmXUA5fqblxl49FHZqaCNh9WTjtGzMUhzXK4JXrI"}', '2017-02-26 11:02:51'),
	(149, 5, 19, 'App\\Patient', 'created', NULL, '{"name":"باسم سمير عبدالسيد ","gender":"M","address":"اسيوط","birthdate":"2005-02-26","age":"12"}', '2017-02-26 13:13:19'),
	(150, 5, 34, 'App\\Visit', 'created', NULL, '{"patient_id":19,"user_id":5,"entry_id":"4"}', '2017-02-26 13:13:19'),
	(151, 5, 5, 'App\\User', 'updated', NULL, '{"remember_token":"N1VFWxaktzoqJfYSzlAmeZ0StuOrfRLTtfeu85tfg5u1yd3FyTTHzQO5OM7l"}', '2017-02-28 08:12:07'),
	(152, 3, 3, 'App\\User', 'updated', NULL, '{"remember_token":"Dkp3KmVNAIDdiLUpJHGqx0w7kaJTW4CzJEpCPBeCeEThENlB4GsjVe9G2DUL"}', '2017-02-28 08:25:38'),
	(153, 5, 35, 'App\\Visit', 'created', NULL, '{"patient_id":"19","user_id":5,"entry_id":"4"}', '2017-02-28 08:25:52'),
	(154, 5, 5, 'App\\User', 'updated', NULL, '{"remember_token":"K4qas2QiSsbuj1IrqLpyB9abBhn9YXX7BtHgWfvadOkiFhrbVx4WHu5OIV6T"}', '2017-02-28 08:25:57'),
	(155, 2, 2, 'App\\User', 'updated', NULL, '{"remember_token":"6kM1haFxEhKfEv1asGPYJNNWgK3MEuqLTvDPtmFkaeAD3h2zFAM415oFX6lO"}', '2017-02-28 13:35:34'),
	(156, 5, 5, 'App\\User', 'updated', NULL, '{"remember_token":"gO1h8GZEletTM72cQtC6w1KlPAoDLcq4AK3rPDE35XPB7C4tDpNN7LDtTZMh"}', '2017-02-28 13:36:02'),
	(157, 5, 5, 'App\\User', 'updated', NULL, '{"remember_token":"gb7LEOlmbYRtjFRtwICnwXctwl4QGxIwOmPCIkgtQGd8cCJBNYWawmCkEiSU"}', '2017-03-01 08:00:11'),
	(158, 5, 36, 'App\\Visit', 'created', NULL, '{"patient_id":"19","user_id":5,"entry_id":"4"}', '2017-03-01 08:00:36'),
	(159, 5, 5, 'App\\User', 'updated', NULL, '{"remember_token":"zcq6bCq6MgDBDNbsmFZSgXFUJi6UNm22kaWiXso6znpTjuNhbRryrEsRKb27"}', '2017-03-01 08:00:39'),
	(160, 2, 1, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"36","content":"ألم","typist_id":2}', '2017-03-01 08:08:45'),
	(161, 2, 2, 'App\\User', 'updated', NULL, '{"remember_token":"ZuPpCjx5zlDzque0c8xcFp5v6n8HdSr36CC9Kf8HSbMqwTZk7GfVPVFgAWsc"}', '2017-03-01 08:09:11'),
	(162, 5, 37, 'App\\Visit', 'created', NULL, '{"patient_id":"19","user_id":5,"entry_id":"4"}', '2017-03-02 09:26:52'),
	(163, 7, 20, 'App\\Patient', 'created', NULL, '{"name":"سامي عبدالبديع علي ","gender":"M","address":"اسيوط","birthdate":"2005-03-02","age":"12"}', '2017-03-02 12:44:40'),
	(164, 7, 38, 'App\\Visit', 'created', NULL, '{"patient_id":20,"user_id":7,"entry_id":"4"}', '2017-03-02 12:44:40'),
	(165, 14, 44, 'App\\MedicalUnit', 'created', NULL, '{"name":"باطنة","type":"c"}', '2017-03-08 08:17:05'),
	(166, 14, 45, 'App\\MedicalUnit', 'created', NULL, '{"name":"باطنة","type":"d"}', '2017-03-08 08:17:11'),
	(167, 14, 44, 'App\\MedicalUnit', 'updated', '{"parent_department_id":null}', '{"parent_department_id":"45"}', '2017-03-08 08:17:16'),
	(168, 5, 39, 'App\\Visit', 'created', NULL, '{"patient_id":"19","user_id":5,"entry_id":"4"}', '2017-03-08 08:18:01'),
	(169, 14, 9, 'App\\User', 'updated', '{"name":"أ \\/ عبد الغفور البرعي حمد"}', '{"name":"أ \\/ عبد الغفور البرعي حمد علي"}', '2017-03-08 11:11:37'),
	(170, 14, 7, 'App\\User', 'updated', '{"name":"أ \\/ عبد الغفور البرعي"}', '{"name":"أ \\/ عبد الغفور البرعي محمد"}', '2017-03-08 11:22:01'),
	(171, 14, 9, 'App\\User', 'updated', '{"role_id":5}', '{"role_id":"4"}', '2017-03-08 11:22:48'),
	(172, 2, 2, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"39","content":"ألم","typist_id":2}', '2017-03-08 12:28:39'),
	(173, 5, 40, 'App\\Visit', 'created', NULL, '{"patient_id":"20","user_id":5,"entry_id":"4"}', '2017-03-08 12:39:00'),
	(174, 2, 1, 'App\\VisitMedicine', 'created', NULL, '{"visit_id":"40","name":"Ketofan","typist_id":2}', '2017-03-08 12:47:24'),
	(175, 5, 21, 'App\\Patient', 'created', NULL, '{"name":"سيد سيد علي ","gender":"M","address":"أسيوط","birthdate":"1962-03-08","age":"55"}', '2017-03-08 12:56:18'),
	(176, 5, 41, 'App\\Visit', 'created', NULL, '{"patient_id":21,"user_id":5,"entry_id":"4"}', '2017-03-08 12:56:18'),
	(181, 5, 41, 'App\\Visit', 'deleted', '{"patient_id":21,"c_name":null,"sid":null,"relation_id":null,"address":null,"job":null,"city":null,"phone_num":null,"entry_id":4,"user_id":5,"closed":0}', NULL, '2017-03-08 12:59:53'),
	(215, 5, 41, 'App\\Visit', 'deleted', '{"patient_id":21,"c_name":null,"sid":null,"relation_id":null,"address":null,"job":null,"city":null,"phone_num":null,"entry_id":4,"user_id":5,"closed":0}', NULL, '2017-03-08 13:06:22'),
	(216, 5, 42, 'App\\Visit', 'created', NULL, '{"patient_id":"21","user_id":5,"entry_id":"4"}', '2017-03-08 13:09:21'),
	(221, 5, 42, 'App\\Visit', 'deleted', '{"patient_id":21,"c_name":null,"sid":null,"relation_id":null,"address":null,"job":null,"city":null,"phone_num":null,"entry_id":4,"user_id":5,"closed":0}', NULL, '2017-03-08 13:11:17'),
	(222, 5, 42, 'App\\Visit', 'deleted', '{"patient_id":21,"c_name":null,"sid":null,"relation_id":null,"address":null,"job":null,"city":null,"phone_num":null,"entry_id":4,"user_id":0,"closed":0,"visit_id":42,"medical_unit_id":44,"convert_to":null}', NULL, '2017-03-08 13:11:17'),
	(223, 5, 41, 'App\\Visit', 'created', NULL, '{"patient_id":"20","user_id":5,"entry_id":"4"}', '2017-03-09 08:02:56'),
	(224, 5, 42, 'App\\Visit', 'created', NULL, '{"patient_id":"19","user_id":5,"entry_id":"4"}', '2017-03-09 08:13:46'),
	(225, 5, 43, 'App\\Visit', 'created', NULL, '{"patient_id":"20","user_id":5,"entry_id":"4"}', '2017-03-09 08:16:17'),
	(226, 5, 44, 'App\\Visit', 'created', NULL, '{"patient_id":"19","user_id":5,"entry_id":"4"}', '2017-03-09 08:16:42'),
	(227, 5, 45, 'App\\Visit', 'created', NULL, '{"patient_id":"21","user_id":5,"entry_id":"4"}', '2017-03-12 10:53:13'),
	(228, 5, 22, 'App\\Patient', 'created', NULL, '{"name":"علي محمد سيد ","gender":"M","address":"اسيوط","birthdate":"1969-07-01","age":""}', '2017-03-13 08:45:30'),
	(229, 5, 46, 'App\\Visit', 'created', NULL, '{"patient_id":22,"user_id":5,"entry_id":"4"}', '2017-03-13 08:45:30'),
	(230, 5, 23, 'App\\Patient', 'created', NULL, '{"name":"هاله سيد علي ","gender":"F","address":"أسيوط","birthdate":"2016-11-13","age":""}', '2017-03-13 08:47:41'),
	(231, 5, 47, 'App\\Visit', 'created', NULL, '{"patient_id":23,"user_id":5,"entry_id":"4"}', '2017-03-13 08:47:42'),
	(232, 5, 24, 'App\\Patient', 'created', NULL, '{"name":"كيرلس ماجد مقبل ","gender":"M","address":"اسيوط","birthdate":"2011-12-13"}', '2017-03-13 11:13:05'),
	(233, 5, 48, 'App\\Visit', 'created', NULL, '{"patient_id":24,"user_id":5,"entry_id":"4"}', '2017-03-13 11:13:05'),
	(234, 5, 25, 'App\\Patient', 'created', NULL, '{"name":"سامي هشام سيد ","gender":"M","address":"أسيوط","birthdate":"2012-01-13"}', '2017-03-13 11:17:58'),
	(235, 5, 49, 'App\\Visit', 'created', NULL, '{"patient_id":25,"user_id":5,"entry_id":"4"}', '2017-03-13 11:17:58'),
	(236, 5, 26, 'App\\Patient', 'created', NULL, '{"name":"خالد ماجد سيد ","gender":"M","address":"أسيوط","birthdate":"2001-10-13","age_in_year":"15","age_in_month":"5"}', '2017-03-13 11:19:19'),
	(237, 5, 50, 'App\\Visit', 'created', NULL, '{"patient_id":26,"user_id":5,"entry_id":"4"}', '2017-03-13 11:19:20'),
	(238, 14, 26, 'App\\Patient', 'updated', '{"name":"خالد ماجد سيد "}', '{"name":"خالد ماجد سيد سيد"}', '2017-03-13 13:23:51'),
	(239, 14, 26, 'App\\Patient', 'updated', '{"name":"خالد ماجد سيد سيد"}', '{"name":"خالد ماجد سيد "}', '2017-03-13 13:31:26'),
	(240, 14, 26, 'App\\Patient', 'updated', '{"name":"خالد ماجد سيد "}', '{"name":"خالد ماجد سيد سيد"}', '2017-03-13 13:32:34'),
	(241, 14, 26, 'App\\Patient', 'updated', '{"gender":"M"}', '{"gender":"F"}', '2017-03-13 13:42:44'),
	(242, 14, 26, 'App\\Patient', 'updated', '{"gender":"F"}', '{"gender":"M"}', '2017-03-13 13:42:50'),
	(243, 14, 26, 'App\\Patient', 'updated', '{"birthdate":"2001-10-13"}', '{"birthdate":"2001-08-13"}', '2017-03-13 13:42:55'),
	(244, 14, 26, 'App\\Patient', 'updated', '{"birthdate":"2001-08-13","age":15}', '{"birthdate":"1997-08-13","age":"19"}', '2017-03-13 13:43:02'),
	(245, 14, 26, 'App\\Patient', 'updated', '{"birthdate":"1997-08-13"}', '{"birthdate":"1998-03-13"}', '2017-03-13 13:43:06'),
	(246, 14, 26, 'App\\Patient', 'updated', '{"address":"أسيوط"}', '{"address":"أسيوط  مركز الفتح"}', '2017-03-13 13:43:17'),
	(247, 5, 51, 'App\\Visit', 'created', NULL, '{"patient_id":"26","user_id":5,"entry_id":"4"}', '2017-03-14 09:41:33'),
	(248, 5, 51, 'App\\Visit', 'updated', '{"cancelled":0}', '{"cancelled":true}', '2017-03-14 09:41:52'),
	(249, 14, 8, 'App\\User', 'deleted', '{"name":"د \\/ ريم ","email":"rim@pdreg.com","role_id":2,"deleted_at":null}', NULL, '2017-03-14 10:26:15'),
	(250, 14, 9, 'App\\User', 'deleted', '{"name":"أ \\/ عبد الغفور البرعي حمد علي","email":"ad@pdreg.com","role_id":4,"deleted_at":null}', NULL, '2017-03-14 10:27:52'),
	(251, 14, 12, 'App\\User', 'deleted', '{"name":"د \\/ مايكل","email":"m@pdreg.com","role_id":2,"deleted_at":null}', NULL, '2017-03-14 10:30:43'),
	(252, 14, 12, 'App\\User', 'deleted', '{"name":"د \\/ مايكل","email":"m@pdreg.com","role_id":2,"deleted_at":null}', NULL, '2017-03-14 10:31:32'),
	(253, 14, 12, 'App\\User', 'deleted', '{"name":"د \\/ مايكل","email":"m@pdreg.com","role_id":2,"deleted_at":null}', NULL, '2017-03-14 10:37:31'),
	(254, 14, 7, 'App\\User', 'deleted', '{"name":"أ \\/ عبد الغفور البرعي محمد","email":"abd@pdreg.com","role_id":5,"deleted_at":null}', NULL, '2017-03-14 10:39:14'),
	(255, 5, 27, 'App\\Patient', 'created', NULL, '{"name":"محمد ماجد سيد ","gender":"M","address":"أسيوط","birthdate":"1992-03-14","age":"25"}', '2017-03-14 11:23:18'),
	(256, 5, 52, 'App\\Visit', 'created', NULL, '{"patient_id":27,"user_id":5,"entry_id":"4"}', '2017-03-14 11:23:18'),
	(257, 5, 53, 'App\\Visit', 'created', NULL, '{"patient_id":"23","user_id":5,"entry_id":"4"}', '2017-03-14 12:58:54'),
	(258, 5, 53, 'App\\Visit', 'updated', '{"cancelled":0}', '{"cancelled":true}', '2017-03-14 12:59:01'),
	(259, 5, 54, 'App\\Visit', 'created', NULL, '{"patient_id":"23","user_id":5,"entry_id":"4"}', '2017-03-14 13:00:27'),
	(260, 5, 28, 'App\\Patient', 'created', NULL, '{"sid":"29211022702755","name":"باسم ماجد مقبل ","gender":"M","address":"اسيوط","birthdate":"1992-11-02","age":"24"}', '2017-03-14 13:09:13'),
	(261, 5, 55, 'App\\Visit', 'created', NULL, '{"patient_id":28,"user_id":5,"entry_id":"4"}', '2017-03-14 13:09:13'),
	(262, 5, 56, 'App\\Visit', 'created', NULL, '{"patient_id":"28","user_id":5,"entry_id":"4"}', '2017-03-15 07:57:02'),
	(263, 5, 57, 'App\\Visit', 'created', NULL, '{"patient_id":"23","user_id":5,"entry_id":"4"}', '2017-03-15 10:52:57'),
	(264, 5, 58, 'App\\Visit', 'created', NULL, '{"patient_id":"25","user_id":5,"entry_id":"4"}', '2017-03-15 10:53:12'),
	(265, 5, 59, 'App\\Visit', 'created', NULL, '{"patient_id":"24","user_id":5,"entry_id":"4"}', '2017-03-15 10:57:38'),
	(266, 5, 60, 'App\\Visit', 'created', NULL, '{"patient_id":"19","user_id":5,"entry_id":"4"}', '2017-03-15 10:58:03'),
	(267, 5, 61, 'App\\Visit', 'created', NULL, '{"patient_id":"20","user_id":5,"entry_id":"4"}', '2017-03-15 11:02:44'),
	(268, 2, 3, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"57","content":"ألم","typist_id":2}', '2017-03-15 11:03:20'),
	(269, 5, 62, 'App\\Visit', 'created', NULL, '{"patient_id":"21","user_id":5,"entry_id":"4"}', '2017-03-15 11:25:37'),
	(270, 2, 62, 'App\\Visit', 'updated', '{"closed":0}', '{"closed":true}', '2017-03-15 11:27:44'),
	(271, 5, 63, 'App\\Visit', 'created', NULL, '{"patient_id":"21","user_id":5,"entry_id":"4"}', '2017-03-15 11:27:59'),
	(272, 5, 63, 'App\\Visit', 'updated', '{"cancelled":0}', '{"cancelled":true}', '2017-03-15 11:28:55'),
	(273, 5, 64, 'App\\Visit', 'created', NULL, '{"patient_id":"21","user_id":5,"entry_id":"4"}', '2017-03-15 11:29:09'),
	(274, 2, 61, 'App\\Visit', 'updated', '{"closed":0}', '{"closed":true}', '2017-03-15 11:30:21'),
	(275, 5, 65, 'App\\Visit', 'created', NULL, '{"patient_id":"20","user_id":5,"entry_id":"4"}', '2017-03-15 11:30:32'),
	(276, 5, 65, 'App\\Visit', 'updated', '{"cancelled":0}', '{"cancelled":true}', '2017-03-15 11:40:32'),
	(277, 5, 66, 'App\\Visit', 'created', NULL, '{"patient_id":"20","user_id":5,"entry_id":"4"}', '2017-03-15 11:40:49'),
	(278, 2, 4, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"66","content":"ألم","typist_id":2}', '2017-03-15 11:41:26'),
	(279, 2, 66, 'App\\Visit', 'updated', '{"closed":0}', '{"closed":true}', '2017-03-15 11:52:31'),
	(280, 2, 64, 'App\\Visit', 'updated', '{"closed":0}', '{"closed":true}', '2017-03-15 11:52:35'),
	(281, 2, 60, 'App\\Visit', 'updated', '{"closed":0}', '{"closed":true}', '2017-03-15 11:52:38'),
	(282, 2, 59, 'App\\Visit', 'updated', '{"closed":0}', '{"closed":true}', '2017-03-15 11:52:41'),
	(283, 2, 58, 'App\\Visit', 'updated', '{"closed":0}', '{"closed":true}', '2017-03-15 11:52:45'),
	(284, 2, 57, 'App\\Visit', 'updated', '{"closed":0}', '{"closed":true}', '2017-03-15 11:52:51'),
	(285, 2, 56, 'App\\Visit', 'updated', '{"closed":0}', '{"closed":true}', '2017-03-15 11:52:55'),
	(286, 5, 67, 'App\\Visit', 'created', NULL, '{"patient_id":"20","user_id":5,"entry_id":"4"}', '2017-03-15 11:53:10'),
	(287, 5, 68, 'App\\Visit', 'created', NULL, '{"patient_id":"21","user_id":5,"entry_id":"4"}', '2017-03-15 11:53:16'),
	(288, 5, 69, 'App\\Visit', 'created', NULL, '{"patient_id":"19","user_id":5,"entry_id":"4"}', '2017-03-15 11:53:22'),
	(289, 5, 70, 'App\\Visit', 'created', NULL, '{"patient_id":"24","user_id":5,"entry_id":"4"}', '2017-03-15 11:53:31'),
	(290, 2, 70, 'App\\Visit', 'updated', '{"closed":0}', '{"closed":true}', '2017-03-15 11:55:02'),
	(291, 2, 69, 'App\\Visit', 'updated', '{"closed":0}', '{"closed":true}', '2017-03-15 11:55:10'),
	(292, 2, 68, 'App\\Visit', 'updated', '{"closed":0}', '{"closed":true}', '2017-03-15 11:55:16'),
	(293, 2, 67, 'App\\Visit', 'updated', '{"closed":0}', '{"closed":true}', '2017-03-15 11:55:21'),
	(294, 5, 71, 'App\\Visit', 'created', NULL, '{"patient_id":"19","user_id":5,"entry_id":"4"}', '2017-03-15 11:55:43'),
	(295, 2, 71, 'App\\Visit', 'updated', '{"closed":0}', '{"closed":true}', '2017-03-15 11:57:32'),
	(296, 5, 72, 'App\\Visit', 'created', NULL, '{"patient_id":"24","user_id":5,"entry_id":"4"}', '2017-03-15 11:57:43'),
	(297, 2, 72, 'App\\Visit', 'updated', '{"closed":0}', '{"closed":true}', '2017-03-15 11:59:07'),
	(298, 5, 73, 'App\\Visit', 'created', NULL, '{"patient_id":"21","user_id":5,"entry_id":"4"}', '2017-03-15 11:59:21'),
	(299, 2, 73, 'App\\Visit', 'updated', '{"closed":0}', '{"closed":true}', '2017-03-15 12:00:49'),
	(300, 5, 74, 'App\\Visit', 'created', NULL, '{"patient_id":"19","user_id":5,"entry_id":"4"}', '2017-03-15 12:00:56'),
	(301, 2, 74, 'App\\Visit', 'updated', '{"closed":0}', '{"closed":true}', '2017-03-15 12:08:27'),
	(302, 5, 75, 'App\\Visit', 'created', NULL, '{"patient_id":"24","user_id":5,"entry_id":"4"}', '2017-03-15 12:08:35'),
	(303, 2, 75, 'App\\Visit', 'updated', '{"closed":0}', '{"closed":true}', '2017-03-15 12:11:11'),
	(304, 5, 76, 'App\\Visit', 'created', NULL, '{"patient_id":"21","user_id":5,"entry_id":"4"}', '2017-03-15 12:11:19'),
	(305, 5, 77, 'App\\Visit', 'created', NULL, '{"patient_id":"19","user_id":5,"entry_id":"4"}', '2017-03-15 12:12:12'),
	(306, 5, 78, 'App\\Visit', 'created', NULL, '{"patient_id":"21","user_id":5,"entry_id":"4"}', '2017-03-20 08:02:36'),
	(307, 2, 1, 'App\\VisitDiagnose', 'created', NULL, '{"visit_id":"78","content":"اشتباة فى كسر","typist_id":2}', '2017-03-20 08:53:13'),
	(308, 2, 5, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"78","content":"ألم","typist_id":2}', '2017-03-20 08:53:13'),
	(309, 2, 2, 'App\\VisitMedicine', 'created', NULL, '{"visit_id":"78","name":"Brofen","typist_id":2}', '2017-03-20 08:53:29'),
	(310, 2, 3, 'App\\VisitMedicine', 'created', NULL, '{"visit_id":"78","name":"Cold flu","typist_id":2}', '2017-03-20 08:54:26'),
	(311, 5, 79, 'App\\Visit', 'created', NULL, '{"patient_id":"24","user_id":5,"entry_id":"4"}', '2017-03-20 11:09:56'),
	(312, 5, 80, 'App\\Visit', 'created', NULL, '{"patient_id":"24","user_id":5,"entry_id":"4"}', '2017-03-28 08:37:25'),
	(313, 2, 6, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"80","content":"ألم","typist_id":2}', '2017-03-28 08:37:46'),
	(314, 2, 7, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"80","content":"ألم","typist_id":2}', '2017-03-28 08:37:46'),
	(315, 2, 8, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"80","content":"ألم","typist_id":2}', '2017-03-28 08:37:47'),
	(316, 2, 2, 'App\\VisitDiagnose', 'created', NULL, '{"visit_id":"80","content":"اشتباة فى كسر","typist_id":2}', '2017-03-28 08:40:26'),
	(317, 2, 80, 'App\\Visit', 'updated', '{"closed":0}', '{"closed":true}', '2017-03-28 09:04:14'),
	(318, 5, 81, 'App\\Visit', 'created', NULL, '{"patient_id":"19","user_id":5,"entry_id":"4"}', '2017-03-28 10:04:45'),
	(319, 2, 9, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"81","content":"ألم","typist_id":2}', '2017-03-28 12:52:16'),
	(320, 2, 3, 'App\\VisitDiagnose', 'created', NULL, '{"visit_id":"81","content":"اشتباة فى كسر","typist_id":2}', '2017-03-28 13:41:28'),
	(321, 2, 4, 'App\\VisitDiagnose', 'created', NULL, '{"visit_id":"81","content":"اشتباة فى كسر","typist_id":2}', '2017-03-28 13:42:56'),
	(322, 2, 10, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"81","content":"سشسي","typist_id":2}', '2017-03-28 13:43:18'),
	(323, 2, 5, 'App\\VisitDiagnose', 'created', NULL, '{"visit_id":"81","content":"سشش","typist_id":2}', '2017-03-28 13:44:02'),
	(324, 2, 11, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"81","content":"عيان","typist_id":2}', '2017-03-28 13:49:02'),
	(325, 2, 6, 'App\\VisitDiagnose', 'created', NULL, '{"visit_id":"81","content":"اورام","typist_id":2}', '2017-03-28 13:52:36'),
	(326, 5, 82, 'App\\Visit', 'created', NULL, '{"patient_id":"27","user_id":5,"entry_id":"4"}', '2017-03-30 10:06:38'),
	(327, 2, 12, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"82","content":"شكوي","typist_id":2}', '2017-03-30 10:59:49'),
	(328, 5, 83, 'App\\Visit', 'created', NULL, '{"patient_id":"24","user_id":5,"entry_id":"4"}', '2017-03-30 11:33:51'),
	(329, 2, 7, 'App\\VisitDiagnose', 'created', NULL, '{"visit_id":"82","content":"تشخيص","typist_id":2}', '2017-03-30 11:58:30'),
	(330, 5, 84, 'App\\Visit', 'created', NULL, '{"patient_id":"24","user_id":5,"entry_id":"4"}', '2017-04-02 10:08:51'),
	(331, 2, 8, 'App\\VisitDiagnose', 'created', NULL, '{"visit_id":"84","content":"اشتباة فى كسر","typist_id":2}', '2017-04-02 10:13:41'),
	(332, 2, 9, 'App\\VisitDiagnose', 'created', NULL, '{"visit_id":"84","content":"اشتباة فى كسر","typist_id":2}', '2017-04-02 10:13:41'),
	(333, 2, 13, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"84","content":"ألم","typist_id":2}', '2017-04-02 10:13:42'),
	(334, 2, 14, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"84","content":"ألم","typist_id":2}', '2017-04-02 10:13:42'),
	(335, 2, 10, 'App\\VisitDiagnose', 'created', NULL, '{"visit_id":"84","content":"اشتباة فى كسر","typist_id":2}', '2017-04-02 10:15:44'),
	(336, 2, 11, 'App\\VisitDiagnose', 'created', NULL, '{"visit_id":"84","content":"اشتباة فى كسر","typist_id":2}', '2017-04-02 10:15:44'),
	(337, 2, 15, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"84","content":"ألم","typist_id":2}', '2017-04-02 10:15:45'),
	(338, 2, 16, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"84","content":"ألم","typist_id":2}', '2017-04-02 10:15:45'),
	(339, 2, 17, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"84","content":"عيان","typist_id":2}', '2017-04-02 10:18:20'),
	(340, 2, 18, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"84","content":"عيان","typist_id":2}', '2017-04-02 10:18:20'),
	(341, 2, 19, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"84","content":"عيان","typist_id":2}', '2017-04-02 10:20:08'),
	(342, 2, 84, 'App\\Visit', 'updated', '{"closed":0}', '{"closed":true}', '2017-04-02 10:20:32'),
	(343, 5, 85, 'App\\Visit', 'created', NULL, '{"patient_id":"21","user_id":5,"entry_id":"4"}', '2017-04-02 10:20:59'),
	(344, 2, 12, 'App\\VisitDiagnose', 'created', NULL, '{"visit_id":"85","content":"اشتباة فى كسر","typist_id":2}', '2017-04-02 10:22:10'),
	(345, 2, 20, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"85","content":"ألم","typist_id":2}', '2017-04-02 10:22:10'),
	(346, 2, 13, 'App\\VisitDiagnose', 'created', NULL, '{"visit_id":"85","content":"تشخيص","typist_id":2}', '2017-04-02 10:25:31'),
	(347, 2, 21, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"85","content":"عيان","typist_id":2}', '2017-04-02 10:27:04'),
	(348, 2, 14, 'App\\VisitDiagnose', 'created', NULL, '{"visit_id":"85","content":"اورام","typist_id":2}', '2017-04-02 10:27:36'),
	(349, 2, 22, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"85","content":"سشسي","typist_id":2}', '2017-04-02 10:28:15'),
	(350, 2, 4, 'App\\VisitMedicine', 'created', NULL, '{"visit_id":"85","name":"Cold flu","typist_id":2}', '2017-04-02 11:27:40'),
	(351, 2, 23, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"85","content":"الم فى الظهر","typist_id":2}', '2017-04-02 11:28:07'),
	(352, 2, 5, 'App\\VisitMedicine', 'created', NULL, '{"visit_id":"85","name":"SeyMedicine","typist_id":2}', '2017-04-02 11:37:33'),
	(353, 2, 6, 'App\\VisitMedicine', 'created', NULL, '{"visit_id":"85","name":"Medicine","typist_id":2}', '2017-04-02 12:13:01'),
	(354, 12, 85, 'App\\Visit', 'updated', '{"closed":0}', '{"closed":true}', '2017-04-02 12:21:14'),
	(355, 5, 86, 'App\\Visit', 'created', NULL, '{"patient_id":"23","user_id":5,"entry_id":"4"}', '2017-04-02 12:22:36'),
	(356, 5, 87, 'App\\Visit', 'created', NULL, '{"patient_id":"27","user_id":5,"entry_id":"4"}', '2017-04-02 12:22:45'),
	(357, 2, 24, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"86","content":"شكوي","typist_id":2}', '2017-04-02 12:31:56'),
	(358, 2, 7, 'App\\VisitMedicine', 'created', NULL, '{"visit_id":"86","name":"Cold flu","typist_id":2}', '2017-04-02 12:32:04'),
	(359, 5, 29, 'App\\Patient', 'created', NULL, '{"name":"سيد سيد علي سيد","gender":"M","address":"اسيوط","birthdate":"2005-04-03","age":"12"}', '2017-04-03 08:15:18'),
	(360, 5, 88, 'App\\Visit', 'created', NULL, '{"patient_id":29,"user_id":5,"entry_id":"4"}', '2017-04-03 08:15:18'),
	(361, 5, 30, 'App\\Patient', 'created', NULL, '{"name":"سيد سيد علي سيد","gender":"M","address":"اسيوط","birthdate":"2005-04-03","age":"12"}', '2017-04-03 08:15:19'),
	(362, 5, 89, 'App\\Visit', 'created', NULL, '{"patient_id":30,"user_id":5,"entry_id":"4"}', '2017-04-03 08:15:19'),
	(363, 5, 89, 'App\\Visit', 'updated', '{"cancelled":0}', '{"cancelled":true}', '2017-04-03 08:15:37'),
	(364, 5, 90, 'App\\Visit', 'created', NULL, '{"patient_id":"23","user_id":5,"entry_id":"4"}', '2017-04-03 08:25:49'),
	(365, 2, 25, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"90","content":"شكوي","typist_id":2}', '2017-04-03 08:26:57'),
	(366, 2, 15, 'App\\VisitDiagnose', 'created', NULL, '{"visit_id":"90","content":"اشتباة فى كسر","typist_id":2}', '2017-04-03 09:10:32'),
	(367, 2, 8, 'App\\VisitMedicine', 'created', NULL, '{"visit_id":"90","name":"Cold flu","typist_id":2}', '2017-04-03 09:10:46'),
	(368, 5, 91, 'App\\Visit', 'created', NULL, '{"patient_id":"23","user_id":5,"entry_id":"4"}', '2017-04-26 11:15:49'),
	(369, 7, 92, 'App\\Visit', 'created', NULL, '{"patient_id":"20","user_id":7,"entry_id":"4"}', '2017-04-26 11:34:03'),
	(370, 5, 91, 'App\\Visit', 'updated', '{"cancelled":0}', '{"cancelled":true}', '2017-04-26 11:53:21'),
	(371, 7, 92, 'App\\Visit', 'updated', '{"cancelled":0}', '{"cancelled":true}', '2017-04-26 12:02:05'),
	(372, 5, 93, 'App\\Visit', 'created', NULL, '{"patient_id":"27","user_id":5,"entry_id":"4"}', '2017-04-26 12:04:38'),
	(373, 7, 94, 'App\\Visit', 'created', NULL, '{"patient_id":"20","user_id":7,"entry_id":"4"}', '2017-04-26 12:05:09'),
	(374, 5, 30, 'App\\Patient', 'created', NULL, '{"name":"عبدالله علي سيد ","gender":"M","address":"اسيوط","birthdate":"1992-04-27","age":"25"}', '2017-04-27 10:43:02'),
	(375, 5, 95, 'App\\Visit', 'created', NULL, '{"patient_id":30,"user_id":5,"entry_id":"4"}', '2017-04-27 10:43:02'),
	(376, 5, 31, 'App\\Patient', 'created', NULL, '{"name":"أحمد علي سيد ","gender":"M","address":"اسيوط","birthdate":"1952-04-27","age":"65"}', '2017-04-27 10:45:46'),
	(377, 5, 96, 'App\\Visit', 'created', NULL, '{"patient_id":31,"ticket_number":"98745646","user_id":5,"entry_id":"4"}', '2017-04-27 10:45:46'),
	(378, 5, 97, 'App\\Visit', 'created', NULL, '{"patient_id":"30","ticket_number":"1231212","user_id":5,"entry_id":"4"}', '2017-04-27 11:38:37'),
	(379, 5, 98, 'App\\Visit', 'created', NULL, '{"patient_id":"30","ticket_number":"999999999","user_id":5,"entry_id":"4"}', '2017-04-30 11:25:37'),
	(380, 5, 99, 'App\\Visit', 'created', NULL, '{"patient_id":"31","ticket_number":"9634555","user_id":5,"entry_id":"4"}', '2017-04-30 11:35:18'),
	(381, 5, 100, 'App\\Visit', 'created', NULL, '{"patient_id":"30","ticket_number":"01212121","user_id":5,"entry_id":"4"}', '2017-05-09 08:14:12'),
	(382, 5, 101, 'App\\Visit', 'created', NULL, '{"patient_id":"27","ticket_number":"456456456","user_id":5,"entry_id":"4"}', '2017-05-09 08:14:52'),
	(383, 14, 31, 'App\\Patient', 'updated', '{"birthdate":"1952-04-27"}', '{"birthdate":"1952-05-09"}', '2017-05-09 12:04:02'),
	(384, 5, 102, 'App\\Visit', 'created', NULL, '{"patient_id":"27","ticket_number":"21212121","user_id":5,"entry_id":"4"}', '2017-05-10 11:29:29'),
	(385, 5, 102, 'App\\Visit', 'updated', '{"cancelled":0}', '{"cancelled":true}', '2017-05-10 11:31:01'),
	(386, 5, 32, 'App\\Patient', 'created', NULL, '{"name":"سمير عبدالسيد ابراهيم ","gender":"M","address":"اسيوط","birthdate":"1962-02-05","age":"55"}', '2017-05-11 11:44:37'),
	(387, 5, 103, 'App\\Visit', 'created', NULL, '{"patient_id":32,"ticket_number":"655555","user_id":5,"entry_id":"4"}', '2017-05-11 11:44:37'),
	(388, 14, 32, 'App\\Patient', 'updated', '{"birthdate":"1962-02-05"}', '{"birthdate":"1962-02-06"}', '2017-05-11 11:56:41'),
	(389, 14, 32, 'App\\Patient', 'updated', '{"sid":null,"birthdate":"1962-02-06","age":55}', '{"sid":"27202022702500","birthdate":"1972-02-02","age":"45"}', '2017-05-14 11:44:45'),
	(390, 5, 104, 'App\\Visit', 'created', NULL, '{"patient_id":"32","ticket_number":"65464564","user_id":5,"entry_id":"4"}', '2017-05-18 08:56:37'),
	(391, 5, 105, 'App\\Visit', 'created', NULL, '{"patient_id":"27","ticket_number":"12121212","user_id":5,"entry_id":"4"}', '2017-05-18 08:58:55'),
	(392, 5, 106, 'App\\Visit', 'created', NULL, '{"patient_id":"30","ticket_number":"78978978978","user_id":5,"entry_id":"4"}', '2017-05-18 09:00:47'),
	(393, 5, 107, 'App\\Visit', 'created', NULL, '{"patient_id":"23","ticket_number":"5445678","user_id":5,"entry_id":"4"}', '2017-05-18 09:21:15'),
	(394, 5, 108, 'App\\Visit', 'created', NULL, '{"patient_id":"23","ticket_number":"142121212","user_id":5,"entry_id":"4"}', '2017-05-28 11:46:45'),
	(395, 5, 33, 'App\\Patient', 'created', NULL, '{"name":"علي محمد علي ","gender":"M","address":"اسيوط","birthdate":"1965-05-30","age":"52"}', '2017-05-30 09:58:04'),
	(396, 5, 109, 'App\\Visit', 'created', NULL, '{"patient_id":33,"ticket_number":"9887879","user_id":5,"entry_id":"4","c_name":"هالة سيد علي","sid":"26602022700000","relation_id":"5","address":"اسيوط","job":"","entry_time":"08:45 AM","entry_reason_desc":"عملية فتح قلب من المستشفي الرئيسي"}', '2017-05-30 09:58:04'),
	(397, 14, 46, 'App\\MedicalUnit', 'created', NULL, '{"name":"جراحة","type":"c"}', '2017-05-30 10:24:39'),
	(398, 5, 34, 'App\\Patient', 'created', NULL, '{"name":"عبدالله عبدالسيد ابراهيم ","gender":"M","address":"اسيوط","birthdate":"1996-05-30","age":"21"}', '2017-05-30 10:27:31'),
	(399, 5, 110, 'App\\Visit', 'created', NULL, '{"patient_id":34,"ticket_number":"987879","user_id":5,"entry_id":"4","c_name":"عبد السيد ابراهيم","sid":"26702022702222","relation_id":"2","address":"اسيوط","job":null,"entry_time":"10:15 AM","entry_reason_desc":"عملية جراحية"}', '2017-05-30 10:27:31'),
	(418, 5, 44, 'App\\Patient', 'created', NULL, '{"name":"سمير باسم سمير ","gender":"M","address":"اسيوط","birthdate":"2014-05-30","age":"3"}', '2017-05-30 10:35:45'),
	(419, 5, 120, 'App\\Visit', 'created', NULL, '{"patient_id":44,"ticket_number":"121212121","user_id":5,"entry_id":"4","c_name":"باسم سمير","sid":"26602022700001","relation_id":"2","address":"اسيوط","job":null,"entry_time":"10:15 AM","entry_reason_desc":"عملية جراحية"}', '2017-05-30 10:35:45'),
	(426, 5, 48, 'App\\Patient', 'created', NULL, '{"name":"عماد كيرلس عماد ","gender":"M","address":"اسيوط","birthdate":"2007-05-30","age":"10"}', '2017-05-30 10:55:15'),
	(427, 5, 124, 'App\\Visit', 'created', NULL, '{"patient_id":48,"ticket_number":"987456146","user_id":5,"entry_id":"4","c_name":"كيرلس عماد","sid":"26602022700005","relation_id":"2","address":"اسيوط","job":null,"entry_time":"10:45 AM","entry_reason_desc":"عملية جراحية"}', '2017-05-30 10:55:15'),
	(428, 7, 125, 'App\\Visit', 'created', NULL, '{"patient_id":"20","ticket_number":"2452452","user_id":7,"entry_id":"4"}', '2017-05-31 10:49:42'),
	(429, 5, 126, 'App\\Visit', 'created', NULL, '{"patient_id":"27","ticket_number":"52452","user_id":5,"entry_id":"4"}', '2017-05-31 10:50:04'),
	(430, 5, 127, 'App\\Visit', 'created', NULL, '{"patient_id":"44","ticket_number":"121212","user_id":5,"entry_id":"4"}', '2017-06-04 10:41:34'),
	(431, 5, 128, 'App\\Visit', 'created', NULL, '{"patient_id":"34","ticket_number":"787878","user_id":5,"entry_id":"4"}', '2017-06-04 10:46:41'),
	(432, 5, 129, 'App\\Visit', 'created', NULL, '{"patient_id":"33","ticket_number":"54545454","user_id":5,"entry_id":"4"}', '2017-06-04 10:48:45'),
	(433, 5, 130, 'App\\Visit', 'created', NULL, '{"patient_id":"27","ticket_number":"15942441","user_id":5,"entry_id":"4","c_name":"عبد السيد ابراهيم","sid":"46262622400000","relation_id":"25","address":"اسيوط","job":null,"entry_time":"11:15 AM","entry_reason_desc":"عملية جراحية"}', '2017-06-04 11:21:50'),
	(434, 5, 131, 'App\\Visit', 'created', NULL, '{"patient_id":"20","ticket_number":"1212124","user_id":5,"entry_id":"4","c_name":"عبد السيد ابراهيم","sid":"46262622400000","relation_id":"10","address":"اسيوط","job":null,"entry_time":"11:15 AM","entry_reason_desc":"عملية جراحية"}', '2017-06-04 11:24:01'),
	(435, 5, 132, 'App\\Visit', 'created', NULL, '{"patient_id":"24","ticket_number":"78718787","user_id":5,"entry_id":"4","c_name":"كيرلس عماد سيد","sid":"26702022702222","relation_id":"16","address":"اسيوط","job":null,"entry_time":"11:30 AM","entry_reason_desc":"عملية جراحية"}', '2017-06-04 11:38:34'),
	(436, 7, 133, 'App\\Visit', 'created', NULL, '{"patient_id":"25","ticket_number":"6172354","user_id":7,"entry_id":"4","c_name":"هشام سيد","sid":"27702122700000","relation_id":"2","address":"اسيوط","job":null,"entry_time":"09:00 AM","entry_reason_desc":"عملية جراحية"}', '2017-06-05 09:09:42'),
	(437, 5, 134, 'App\\Visit', 'created', NULL, '{"patient_id":"33","ticket_number":"37951","user_id":5,"entry_id":"4","c_name":"سيد علي محمد علي","sid":"28802132700000","relation_id":"6","address":"اسيوط","job":null,"entry_time":"09:00 AM","entry_reason_desc":"عملية جراحية"}', '2017-06-05 09:10:43'),
	(438, 5, 135, 'App\\Visit', 'created', NULL, '{"patient_id":"26","ticket_number":"996969","user_id":5,"entry_id":"4"}', '2017-06-05 09:41:05'),
	(439, 7, 136, 'App\\Visit', 'created', NULL, '{"patient_id":"22","ticket_number":"466568","user_id":7,"entry_id":"4"}', '2017-06-05 09:41:43'),
	(440, 5, 137, 'App\\Visit', 'created', NULL, '{"patient_id":"26","ticket_number":"98898","user_id":5,"entry_id":"4"}', '2017-06-07 08:57:05'),
	(441, 5, 137, 'App\\Visit', 'updated', '{"ticket_number":98898,"cancelled":0}', '{"ticket_number":null,"cancelled":true}', '2017-06-07 09:03:07'),
	(442, 5, 138, 'App\\Visit', 'created', NULL, '{"patient_id":"26","ticket_number":"98898","user_id":5,"entry_id":"4"}', '2017-06-07 09:03:33'),
	(443, 5, 138, 'App\\Visit', 'updated', '{"ticket_number":98898,"cancelled":0}', '{"ticket_number":null,"cancelled":true}', '2017-06-07 09:03:44'),
	(444, 5, 139, 'App\\Visit', 'created', NULL, '{"patient_id":"26","ticket_number":"98898","user_id":5,"entry_id":"4"}', '2017-06-07 09:03:55'),
	(445, 5, 140, 'App\\Visit', 'created', NULL, '{"patient_id":"44","ticket_number":"99799","user_id":5,"entry_id":"4"}', '2017-06-07 12:15:00'),
	(446, 5, 141, 'App\\Visit', 'created', NULL, '{"patient_id":"44","ticket_number":"11212","user_id":5,"entry_id":"4"}', '2017-06-13 12:49:55'),
	(447, 14, 2, 'App\\User', 'updated', '{"name":"د\\/ سامي"}', '{"name":"د\\/ سامي ug"}', '2017-06-13 13:15:37'),
	(448, 2, 26, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"141","content":"شكوي","typist_id":2}', '2017-06-13 13:23:45'),
	(449, 2, 141, 'App\\Visit', 'updated', '{"closed":0}', '{"closed":true}', '2017-06-13 13:23:52'),
	(450, 5, 142, 'App\\Visit', 'created', NULL, '{"patient_id":"44","ticket_number":"9854","user_id":5,"entry_id":"4"}', '2017-06-21 11:54:33'),
	(451, 2, 27, 'App\\VisitComplaint', 'created', NULL, '{"visit_id":"142","content":"Casds","typist_id":2}', '2017-06-21 11:54:52'),
	(452, 2, 142, 'App\\Visit', 'updated', '{"closed":0}', '{"closed":true}', '2017-06-21 11:54:56'),
	(453, 14, 7, 'App\\User', 'deleted', '{"name":"أ \\/ عبد الغفور البرعي محمد","email":"abd@pdreg.com","role_id":5,"deleted_at":null}', NULL, '2017-07-24 08:34:21'),
	(454, 14, 4, 'App\\User', 'deleted', '{"name":"أ\\/ سعيد سيد","email":"said@yahoo.com","role_id":2,"deleted_at":null}', NULL, '2017-07-24 08:52:30');
/*!40000 ALTER TABLE `mf_logs` ENABLE KEYS */;

-- Dumping structure for table pdreg.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.migrations: ~32 rows (approximately)
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`migration`, `batch`) VALUES
	('2014_10_12_000000_create_users_table', 1),
	('2014_10_12_100000_create_password_resets_table', 1),
	('2016_08_11_070935_create_patients_table', 2),
	('2016_08_25_082811_relations_table', 3),
	('2016_08_29_071342_create_epoints_table', 4),
	('2016_08_31_063550_create_visits_table', 5),
	('2016_09_01_104557_create_medical_units_table', 6),
	('2016_09_20_110746_create_medical_order_items_table', 7),
	('2016_09_20_111214_create_medical_devices_table', 8),
	('2016_09_20_112306_create_procedure_types_table', 9),
	('2016_09_20_112143_create_procedures_table', 10),
	('2016_10_13_084547_create_roles_table', 11),
	('2016_10_13_085433_add_role_id_attribute_user_table', 12),
	('2016_10_16_112841_add_diagnoses_attribute_medical_unit_visit_table', 19),
	('2016_10_17_071930_add_close_visit_attrbuite_visit_table', 14),
	('2016_10_20_074618_add_convert_to_attribute_medical_unit_visit_table', 15),
	('2016_10_20_095421_create_medical_unit__user_pivot_table', 16),
	('2016_12_01_095514_add_department_id_to_medical_units', 17),
	('2016_12_05_093717_create_visit_diagnoses_table', 18),
	('2016_12_08_101948_set_some_patients_table_attributes_to_null', 20),
	('2016_12_08_103617_set_some_visit_table_attributes_to_null', 21),
	('2016_12_21_095722_create_mf_logs_table', 21),
	('2016_12_19_082013_create_web_service_config_table', 22),
	('2017_01_01_105929_make_sid_null_patients_table', 23),
	('2017_01_04_091541_create_visit_complaints_table', 24),
	('2017_01_09_130845_add_procedure_ris_id_to_procedure_table', 25),
	('2017_01_10_122712_add_type_attribute_to_entrypoints_table', 25),
	('2017_01_22_100145_create_visit_medicines_table', 26),
	('2017_03_14_092809_add_attr_canceled_visits_table', 27),
	('2017_03_14_100530_add_attr_deleted_at_users_table', 28),
	('2017_03_15_084046_add_seen_attr_medical_unit_visits_pivot_table', 29),
	('2016_12_28_081006_create_sessions_table', 30),
	('2017_04_27_100730_add_attr_ticket_num_visits_table', 31),
	('2017_04_30_130259_add_department_conversion_attr_medical_unit_visits', 32),
	('2017_05_30_094423_add_two_attributes_entry_desc_and_time_visit_table', 33);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- Dumping structure for table pdreg.patients
CREATE TABLE IF NOT EXISTS `patients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `gender` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `sid` bigint(20) DEFAULT NULL,
  `address` text COLLATE utf8_unicode_ci,
  `birthdate` date NOT NULL,
  `age` int(11) NOT NULL,
  `issuer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_num` int(11) DEFAULT NULL,
  `nationality` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `job` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.patients: ~18 rows (approximately)
/*!40000 ALTER TABLE `patients` DISABLE KEYS */;
INSERT INTO `patients` (`id`, `name`, `gender`, `sid`, `address`, `birthdate`, `age`, `issuer`, `phone_num`, `nationality`, `job`, `created_at`, `updated_at`) VALUES
	(19, 'باسم سمير عبدالسيد ', 'M', NULL, 'اسيوط', '2005-02-26', 12, NULL, NULL, NULL, NULL, '2017-02-26 13:13:18', '2017-02-26 13:13:18'),
	(20, 'سامي عبدالبديع علي ', 'M', NULL, 'اسيوط', '2005-03-02', 12, NULL, NULL, NULL, NULL, '2017-03-02 12:44:39', '2017-03-02 12:44:39'),
	(21, 'سيد سيد علي ', 'M', NULL, 'أسيوط', '1962-03-08', 55, NULL, NULL, NULL, NULL, '2017-03-08 12:56:18', '2017-03-08 12:56:18'),
	(22, 'علي محمد سيد ', 'M', NULL, 'اسيوط', '2016-10-13', 0, NULL, NULL, NULL, NULL, '2017-03-13 08:45:30', '2017-03-13 08:45:30'),
	(23, 'هاله سيد علي ', 'F', NULL, 'أسيوط', '2016-11-13', 0, NULL, NULL, NULL, NULL, '2017-03-13 08:47:41', '2017-03-13 08:47:41'),
	(24, 'كيرلس ماجد مقبل ', 'M', NULL, 'اسيوط', '2011-12-13', 0, NULL, NULL, NULL, NULL, '2017-03-13 11:13:05', '2017-03-13 11:13:05'),
	(25, 'سامي هشام سيد ', 'M', NULL, 'أسيوط', '2012-01-13', 0, NULL, NULL, NULL, NULL, '2017-03-13 11:17:58', '2017-03-13 11:17:58'),
	(26, 'خالد ماجد سيد سيد', 'M', NULL, 'أسيوط  مركز الفتح', '1998-03-13', 19, NULL, NULL, NULL, NULL, '2017-03-13 11:19:19', '2017-03-13 13:43:17'),
	(27, 'محمد ماجد سيد ', 'M', NULL, 'أسيوط', '1992-03-14', 25, NULL, NULL, NULL, NULL, '2017-03-14 11:23:18', '2017-03-14 11:23:18'),
	(28, 'باسم ماجد مقبل ', 'M', 29211022702755, 'اسيوط', '1992-11-02', 24, NULL, NULL, NULL, NULL, '2017-03-14 13:09:13', '2017-03-14 13:09:13'),
	(29, 'سيد سيد علي سيد', 'M', NULL, 'اسيوط', '2005-04-03', 12, NULL, NULL, NULL, NULL, '2017-04-03 08:15:18', '2017-04-03 08:15:18'),
	(30, 'عبدالله علي سيد ', 'M', NULL, 'اسيوط', '1992-04-27', 25, NULL, NULL, NULL, NULL, '2017-04-27 10:43:02', '2017-04-27 10:43:02'),
	(31, 'أحمد علي سيد ', 'M', NULL, 'اسيوط', '1952-05-09', 65, NULL, NULL, NULL, NULL, '2017-04-27 10:45:46', '2017-05-09 12:04:02'),
	(32, 'سمير عبدالسيد ابراهيم ', 'M', 27202022702500, 'اسيوط', '1972-02-02', 45, NULL, NULL, NULL, NULL, '2017-05-11 11:44:37', '2017-05-14 11:44:46'),
	(33, 'علي محمد علي ', 'M', NULL, 'اسيوط', '1965-05-30', 52, NULL, NULL, NULL, NULL, '2017-05-30 09:58:04', '2017-05-30 09:58:04'),
	(34, 'عبدالله عبدالسيد ابراهيم ', 'M', NULL, 'اسيوط', '1996-05-30', 21, NULL, NULL, NULL, NULL, '2017-05-30 10:27:31', '2017-05-30 10:27:31'),
	(44, 'سمير باسم سمير ', 'M', NULL, 'اسيوط', '2014-05-30', 3, NULL, NULL, NULL, NULL, '2017-05-30 10:35:45', '2017-05-30 10:35:45'),
	(48, 'عماد كيرلس عماد ', 'M', NULL, 'اسيوط', '2007-05-30', 10, NULL, NULL, NULL, NULL, '2017-05-30 10:55:15', '2017-05-30 10:55:15');
/*!40000 ALTER TABLE `patients` ENABLE KEYS */;

-- Dumping structure for table pdreg.procedures
CREATE TABLE IF NOT EXISTS `procedures` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `proc_ris_id` text COLLATE utf8_unicode_ci,
  `type_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `device_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `procedures_type_id_index` (`type_id`),
  CONSTRAINT `procedures_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `procedure_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.procedures: ~0 rows (approximately)
/*!40000 ALTER TABLE `procedures` DISABLE KEYS */;
/*!40000 ALTER TABLE `procedures` ENABLE KEYS */;

-- Dumping structure for table pdreg.procedure_types
CREATE TABLE IF NOT EXISTS `procedure_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.procedure_types: ~2 rows (approximately)
/*!40000 ALTER TABLE `procedure_types` DISABLE KEYS */;
INSERT INTO `procedure_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'Radiology', '2016-11-21 07:18:01', '2016-11-21 07:18:01'),
	(2, 'Lab', '2016-11-21 07:18:01', '2016-11-21 07:18:01');
/*!40000 ALTER TABLE `procedure_types` ENABLE KEYS */;

-- Dumping structure for table pdreg.relations
CREATE TABLE IF NOT EXISTS `relations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.relations: ~28 rows (approximately)
/*!40000 ALTER TABLE `relations` DISABLE KEYS */;
INSERT INTO `relations` (`id`, `name`, `created_at`, `updated_at`) VALUES
	(1, '', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(2, 'الوالد', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(3, 'الأم', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(4, 'الزوج', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(5, 'الزوجة', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(6, 'الابن', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(7, 'البنت', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(8, 'الجد', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(9, 'الجدة', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(10, 'الاخ', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(11, 'الاخت', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(12, 'ابن الابن', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(13, 'ابن الاخت', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(14, 'العم', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(15, 'العمة', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(16, 'الخال', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(17, 'الخالة', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(18, 'ابن الاخ', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(19, 'بنت الاخ', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(20, ' ابن الاخت', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(21, 'ابن العم', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(22, 'بنت العم', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(23, 'ابن العمة', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(24, 'بنت العمة', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(25, 'ابن الخال', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(26, 'بنت الخال', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(27, 'ابن الخالة', '2016-11-21 07:16:17', '2016-11-21 07:16:17'),
	(28, 'بنت الخالة', '2016-11-21 07:16:17', '2016-11-21 07:16:17');
/*!40000 ALTER TABLE `relations` ENABLE KEYS */;

-- Dumping structure for table pdreg.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.roles: ~6 rows (approximately)
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'Admin', '2016-11-21 07:18:15', '2016-11-21 07:18:15'),
	(2, 'Doctor', '2016-11-21 07:18:15', '2016-11-21 07:18:15'),
	(3, 'Nursing', '2016-11-21 07:18:15', '2016-11-21 07:18:15'),
	(4, 'Entrypoint', '2016-11-21 07:18:15', '2016-11-21 07:18:15'),
	(5, 'Receiption', '2016-12-26 10:39:11', '2016-12-26 10:39:11'),
	(6, 'SubAdmin', '2017-03-01 08:13:13', '2017-03-01 08:13:13');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

-- Dumping structure for table pdreg.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8_unicode_ci,
  `payload` text COLLATE utf8_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  UNIQUE KEY `sessions_id_unique` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.sessions: ~0 rows (approximately)
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;

-- Dumping structure for table pdreg.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.users: ~13 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role_id`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'basem', 'basemlovephp@gmail.com', '$2y$10$X6s9Ev2Q.jV0w/MzJ0SQe./5VX0Niyx5.1X553irnSFFgz..77.uG', 1, '1BQai24yE1mDXNgqn6ORNmXUA5fqblxl49FHZqaCNh9WTjtGzMUhzXK4JXrI', '2016-08-11 07:16:02', '2017-02-26 11:02:51', NULL),
	(2, 'د/ سامي ug', 'sam@yahoo.com', '$2y$10$2O4SeWctstO9otjYQ6m2r.JrQ35BD7ZFRwW.pzI7r.pJJtEfmOdD2', 2, 'ZuPpCjx5zlDzque0c8xcFp5v6n8HdSr36CC9Kf8HSbMqwTZk7GfVPVFgAWsc', '2016-11-21 07:24:45', '2017-06-13 13:15:37', NULL),
	(3, 'أ/ علي محمد علي', 'ali@yahoo.com', '$2y$10$Tc.EYs20IbiK7mbHko.e3.Yi9svgu2hpntLR.pQUwBXUg3GWS70vq', 4, 'Dkp3KmVNAIDdiLUpJHGqx0w7kaJTW4CzJEpCPBeCeEThENlB4GsjVe9G2DUL', '2016-11-21 07:31:22', '2017-02-28 08:25:38', NULL),
	(4, 'أ/ سعيد سيد', 'said@yahoo.com', '$2y$10$Poii5ypBp4zV9gnjb3/zhuinK00wC3Iy84lL0DxzdQRRR/oSS3JQm', 2, 'EPacQ4Bti2VnYhp4XuWyPTV6Woi0vJNGAkkuM97J5HhIRUYwxvm8i6Q53XvR', '2016-12-05 11:09:04', '2017-07-24 08:52:30', '2017-07-24 08:52:30'),
	(5, 'أ/ محمد أحمد', 'rec@pdreg.com', '$2y$10$sHbbzc4uCuAjnQEgbp5YfeDpeciwIGDtPicEc8O8v92B5Wv22Bb7u', 5, 'zcq6bCq6MgDBDNbsmFZSgXFUJi6UNm22kaWiXso6znpTjuNhbRryrEsRKb27', '2016-12-07 12:57:50', '2017-03-01 08:00:39', NULL),
	(7, 'أ / عبد الغفور البرعي محمد', 'abd@pdreg.com', '$2y$10$1gG5Q9G6gOvppAKQh2.jzueWWrxZLLNMmdA//PNuhQa5a0dEP6dTK', 5, NULL, '2017-01-09 13:27:47', '2017-07-24 08:34:21', '2017-07-24 08:34:21'),
	(8, 'د / ريم ', 'rim@pdreg.com', '$2y$10$0rioxfd3SyasgRDzX54PoOfPUGAIDssfwQMwEGcOJh2cBujHkXDUW', 2, '9dPRGslGoq1NcuNMkatYGA1NGQvHJCAuUyKwsO2X9P1MVRO5UlXSVPlRSSde', '2017-01-10 11:29:30', '2017-03-14 10:26:15', '2017-03-14 10:26:15'),
	(9, 'أ / عبد الغفور البرعي حمد علي', 'ad@pdreg.com', '$2y$10$7oQTQ6HNrHAP4N0kkPYieOd1zIrGE81tkiPNPFCBm.o8TKhA.4dom', 4, NULL, '2017-01-10 13:03:12', '2017-03-14 10:27:52', '2017-03-14 10:27:52'),
	(10, 'د / ظاظا ', 'zaza@pdreg.com', '$2y$10$OAHAcyEzAcQTlKskYvSeHeSVp7690W0AMDomhlU.GDDuS2.6JarVu', 2, 'x5iXkiwuC1B2TZf6N1yyu2h9f1BsHPjrLnHDkelZyLa97ceroIFb2YZVoWH0', '2017-01-12 08:12:46', '2017-01-15 10:15:16', NULL),
	(11, 'د / حسين', 'hu@pdreg.com', '$2y$10$1dzhTghRpKH4NobtLTK9vO7dN7YrwYQJ6swVm.9LsLxvXnZbC.szi', 2, 'jZ8ZTs5xuD9jJ968RN0duw6jnVDUASKWfH4HcmvNLnEe9orU8jntGm9qpYDZ', '2017-01-12 08:41:30', '2017-01-12 09:26:10', NULL),
	(12, 'د / مايكل', 'm@pdreg.com', '$2y$10$3KE.diC4tuWvpwnVSwJumekOoJfmmG0ja6iLBpGDtCu2CZpJu37Wu', 2, 'GmrHbrvfgCwpqtp8etvh98yNL3VIr1hVEBWfyd1707aLCAa0k3ESW9jWCHfI', '2017-01-15 10:18:05', '2017-03-14 10:37:31', NULL),
	(13, 'أ/ علي', 'a@pdreg.com', '$2y$10$HL.kJC4kVcvVjAa3SPf5EOG54d5TUUm8Q10MsFrghQDmx9YPeLctG', 5, NULL, '2017-01-15 11:22:41', '2017-01-15 11:23:11', NULL),
	(14, 'SubTest', 'sub@pdreg.com', '$2y$10$c5hzzaJEwp/gM0rHiT2jxOtdkTk/MAvulI8m0QQbg7/9JQOlafGK6', 6, 'GiNupcm9S1AM69BfSOc8PG5JwBA37NcDioesJHXaijCfhC6e3q2hqJGt9Jwk', '2017-01-15 11:37:57', '2017-01-15 12:07:17', NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Dumping structure for table pdreg.visits
CREATE TABLE IF NOT EXISTS `visits` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` int(10) unsigned NOT NULL,
  `ticket_number` bigint(20) NOT NULL,
  `c_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sid` bigint(20) DEFAULT NULL,
  `relation_id` int(11) DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `job` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_num` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entry_reason_desc` text COLLATE utf8_unicode_ci,
  `entry_time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entry_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `cancelled` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `visits_patient_id_index` (`patient_id`),
  KEY `visits_relation_id_index` (`relation_id`),
  CONSTRAINT `visits_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=143 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.visits: ~75 rows (approximately)
/*!40000 ALTER TABLE `visits` DISABLE KEYS */;
INSERT INTO `visits` (`id`, `patient_id`, `ticket_number`, `c_name`, `sid`, `relation_id`, `address`, `job`, `city`, `phone_num`, `entry_reason_desc`, `entry_time`, `entry_id`, `user_id`, `closed`, `cancelled`, `created_at`, `updated_at`) VALUES
	(34, 19, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-02-26 13:13:19', '2017-02-26 13:13:19'),
	(35, 19, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-02-28 08:25:52', '2017-02-28 08:25:52'),
	(36, 19, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-01 08:00:35', '2017-03-01 08:00:35'),
	(37, 19, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-02 09:26:52', '2017-03-02 09:26:52'),
	(38, 20, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 7, 0, 0, '2017-03-02 12:44:40', '2017-03-02 12:44:40'),
	(39, 19, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-08 08:18:01', '2017-03-08 08:18:01'),
	(40, 20, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-08 12:39:00', '2017-03-08 12:39:00'),
	(43, 20, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-09 08:16:17', '2017-03-09 08:16:17'),
	(44, 19, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-09 08:16:42', '2017-03-09 08:16:42'),
	(45, 21, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-12 10:53:13', '2017-03-12 10:53:13'),
	(46, 22, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-13 08:45:30', '2017-03-13 08:45:30'),
	(47, 23, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-13 08:47:42', '2017-03-13 08:47:42'),
	(48, 24, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-13 11:13:05', '2017-03-13 11:13:05'),
	(49, 25, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-13 11:17:58', '2017-03-13 11:17:58'),
	(50, 26, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-13 11:19:20', '2017-03-13 11:19:20'),
	(51, 26, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 1, '2017-03-14 09:41:33', '2017-03-14 09:41:52'),
	(52, 27, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-14 11:23:18', '2017-03-14 11:23:18'),
	(53, 23, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 1, '2017-03-14 12:58:54', '2017-03-14 12:59:01'),
	(54, 23, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-14 13:00:27', '2017-03-14 13:00:27'),
	(55, 28, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-14 13:09:13', '2017-03-14 13:09:13'),
	(56, 28, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 1, 0, '2017-03-15 07:57:01', '2017-03-15 11:52:55'),
	(57, 23, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 1, 0, '2017-03-15 10:52:56', '2017-03-15 11:52:51'),
	(58, 25, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 1, 0, '2017-03-15 10:53:12', '2017-03-15 11:52:45'),
	(59, 24, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 1, 0, '2017-03-15 10:57:37', '2017-03-15 11:52:41'),
	(60, 19, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 1, 0, '2017-03-15 10:58:02', '2017-03-15 11:52:38'),
	(61, 20, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 1, 0, '2017-03-15 11:02:44', '2017-03-15 11:30:21'),
	(62, 21, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 1, 0, '2017-03-15 11:25:37', '2017-03-15 11:27:44'),
	(63, 21, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 1, '2017-03-15 11:27:59', '2017-03-15 11:28:55'),
	(64, 21, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 1, 0, '2017-03-15 11:29:09', '2017-03-15 11:52:35'),
	(65, 20, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 1, '2017-03-15 11:30:32', '2017-03-15 11:40:32'),
	(66, 20, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 1, 0, '2017-03-15 11:40:49', '2017-03-15 11:52:31'),
	(67, 20, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 1, 0, '2017-03-15 11:53:10', '2017-03-15 11:55:21'),
	(68, 21, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 1, 0, '2017-03-15 11:53:16', '2017-03-15 11:55:16'),
	(69, 19, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 1, 0, '2017-03-15 11:53:22', '2017-03-15 11:55:10'),
	(70, 24, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 1, 0, '2017-03-15 11:53:31', '2017-03-15 11:55:02'),
	(71, 19, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 1, 0, '2017-03-15 11:55:43', '2017-03-15 11:57:32'),
	(72, 24, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 1, 0, '2017-03-15 11:57:43', '2017-03-15 11:59:07'),
	(73, 21, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 1, 0, '2017-03-15 11:59:21', '2017-03-15 12:00:49'),
	(74, 19, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 1, 0, '2017-03-15 12:00:56', '2017-03-15 12:08:27'),
	(75, 24, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 1, 0, '2017-03-15 12:08:35', '2017-03-15 12:11:11'),
	(76, 21, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-15 12:11:19', '2017-03-15 12:11:19'),
	(77, 19, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-15 12:12:12', '2017-03-15 12:12:12'),
	(78, 21, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-20 08:02:36', '2017-03-20 08:02:36'),
	(79, 24, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-20 11:09:56', '2017-03-20 11:09:56'),
	(80, 24, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 1, 0, '2017-03-28 08:37:25', '2017-03-28 09:04:14'),
	(81, 19, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-28 10:04:45', '2017-03-28 10:04:45'),
	(82, 27, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-30 10:06:38', '2017-03-30 10:06:38'),
	(83, 24, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-03-30 11:33:51', '2017-03-30 11:33:51'),
	(84, 24, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 1, 0, '2017-04-02 10:08:51', '2017-04-02 10:20:32'),
	(85, 21, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 1, 0, '2017-04-02 10:20:59', '2017-04-02 12:21:15'),
	(86, 23, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-04-02 12:22:36', '2017-04-02 12:22:36'),
	(87, 27, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-04-02 12:22:45', '2017-04-02 12:22:45'),
	(88, 29, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-04-03 08:15:18', '2017-04-03 08:15:18'),
	(90, 23, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-04-03 08:25:49', '2017-04-03 08:25:49'),
	(91, 23, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 1, '2017-04-26 11:15:49', '2017-04-26 11:53:21'),
	(92, 20, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 7, 0, 1, '2017-04-26 11:34:03', '2017-04-26 12:02:05'),
	(93, 27, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-04-26 12:04:38', '2017-04-26 12:04:38'),
	(94, 20, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 7, 0, 0, '2017-04-26 12:05:09', '2017-04-26 12:05:09'),
	(95, 30, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-04-27 10:43:02', '2017-04-27 10:43:02'),
	(96, 31, 98745646, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-04-27 10:45:46', '2017-04-27 10:45:46'),
	(97, 30, 1231212, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-04-27 11:38:37', '2017-04-27 11:38:37'),
	(98, 30, 999999999, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-04-30 11:25:36', '2017-04-30 11:25:36'),
	(99, 31, 9634555, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-04-30 11:35:18', '2017-04-30 11:35:18'),
	(100, 30, 1212121, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-05-09 08:14:12', '2017-05-09 08:14:12'),
	(101, 27, 456456456, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-05-09 08:14:51', '2017-05-09 08:14:51'),
	(102, 27, 21212121, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 1, '2017-05-10 11:29:28', '2017-05-10 11:31:01'),
	(103, 32, 655555, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-05-11 11:44:37', '2017-05-11 11:44:37'),
	(104, 32, 65464564, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-05-18 08:56:37', '2017-05-18 08:56:37'),
	(105, 27, 12121212, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-05-18 08:58:55', '2017-05-18 08:58:55'),
	(106, 30, 78978978978, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-05-18 09:00:47', '2017-05-18 09:00:47'),
	(107, 23, 5445678, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-05-18 09:21:15', '2017-05-18 09:21:15'),
	(108, 23, 142121212, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-05-28 11:46:45', '2017-05-28 11:46:45'),
	(109, 33, 9887879, 'هالة سيد علي', 26602022700000, 5, 'اسيوط', '', NULL, NULL, 'عملية فتح قلب من المستشفي الرئيسي', '08:45 AM', 4, 5, 0, 0, '2017-05-30 09:58:04', '2017-05-30 09:58:04'),
	(110, 34, 987879, 'عبد السيد ابراهيم', 26702022702222, 2, 'اسيوط', NULL, NULL, NULL, 'عملية جراحية', '10:15 AM', 4, 5, 0, 0, '2017-05-30 10:27:31', '2017-05-30 10:27:31'),
	(120, 44, 121212121, 'باسم سمير', 26602022700001, 2, 'اسيوط', NULL, NULL, NULL, 'عملية جراحية', '10:15 AM', 4, 5, 0, 0, '2017-05-30 10:35:45', '2017-05-30 10:35:45'),
	(124, 48, 987456146, 'كيرلس عماد', 26602022700005, 2, 'اسيوط', NULL, NULL, NULL, 'عملية جراحية', '10:45 AM', 4, 5, 0, 0, '2017-05-30 10:55:15', '2017-05-30 10:55:15'),
	(125, 20, 2452452, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 7, 0, 0, '2017-05-31 10:49:41', '2017-05-31 10:49:41'),
	(126, 27, 52452, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-05-31 10:50:04', '2017-05-31 10:50:04'),
	(127, 44, 121212, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-06-04 10:41:34', '2017-06-04 10:41:34'),
	(128, 34, 787878, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-06-04 10:46:41', '2017-06-04 10:46:41'),
	(129, 33, 54545454, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-06-04 10:48:45', '2017-06-04 10:48:45'),
	(130, 27, 15942441, 'عبد السيد ابراهيم', 46262622400000, 25, 'اسيوط', NULL, NULL, NULL, 'عملية جراحية', '11:15 AM', 4, 5, 0, 0, '2017-06-04 11:21:50', '2017-06-04 11:21:50'),
	(131, 20, 1212124, 'عبد السيد ابراهيم', 46262622400000, 10, 'اسيوط', NULL, NULL, NULL, 'عملية جراحية', '11:15 AM', 4, 5, 0, 0, '2017-06-04 11:24:01', '2017-06-04 11:24:01'),
	(132, 24, 78718787, 'كيرلس عماد سيد', 26702022702222, 16, 'اسيوط', NULL, NULL, NULL, 'عملية جراحية', '11:30 AM', 4, 5, 0, 0, '2017-06-04 11:38:33', '2017-06-04 11:38:33'),
	(133, 25, 6172354, 'هشام سيد', 27702122700000, 2, 'اسيوط', NULL, NULL, NULL, 'عملية جراحية', '09:00 AM', 4, 7, 0, 0, '2017-06-05 09:09:42', '2017-06-05 09:09:42'),
	(134, 33, 37951, 'سيد علي محمد علي', 28802132700000, 6, 'اسيوط', NULL, NULL, NULL, 'عملية جراحية', '09:00 AM', 4, 5, 0, 0, '2017-06-05 09:10:43', '2017-06-05 09:10:43'),
	(135, 26, 996969, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-06-05 09:41:05', '2017-06-05 09:41:05'),
	(136, 22, 466568, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 7, 0, 0, '2017-06-05 09:41:43', '2017-06-05 09:41:43'),
	(137, 26, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 1, '2017-06-07 08:57:05', '2017-06-07 09:03:07'),
	(138, 26, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 1, '2017-06-07 09:03:33', '2017-06-07 09:03:44'),
	(139, 26, 98898, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-06-07 09:03:55', '2017-06-07 09:03:55'),
	(140, 44, 99799, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 0, 0, '2017-06-07 12:14:59', '2017-06-07 12:14:59'),
	(141, 44, 11212, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 1, 0, '2017-06-13 12:49:55', '2017-06-13 13:23:52'),
	(142, 44, 9854, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 5, 1, 0, '2017-06-21 11:54:32', '2017-06-21 11:54:56');
/*!40000 ALTER TABLE `visits` ENABLE KEYS */;

-- Dumping structure for table pdreg.visit_complaints
CREATE TABLE IF NOT EXISTS `visit_complaints` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `visit_id` int(10) unsigned NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `typist_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `visit_complaints_visit_id_index` (`visit_id`),
  CONSTRAINT `visit_complaints_visit_id_foreign` FOREIGN KEY (`visit_id`) REFERENCES `visits` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.visit_complaints: ~17 rows (approximately)
/*!40000 ALTER TABLE `visit_complaints` DISABLE KEYS */;
INSERT INTO `visit_complaints` (`id`, `visit_id`, `content`, `typist_id`, `created_at`, `updated_at`) VALUES
	(1, 36, 'ألم', 2, '2017-03-01 08:08:45', '2017-03-01 08:08:45'),
	(2, 39, 'ألم', 2, '2017-03-08 12:28:39', '2017-03-08 12:28:39'),
	(3, 57, 'ألم', 2, '2017-03-15 11:03:20', '2017-03-15 11:03:20'),
	(4, 66, 'ألم', 2, '2017-03-15 11:41:26', '2017-03-15 11:41:26'),
	(5, 78, 'ألم', 2, '2017-03-20 08:53:13', '2017-03-20 08:53:13'),
	(6, 80, 'ألم', 2, '2017-03-28 08:37:46', '2017-03-28 08:37:46'),
	(9, 81, 'ألم', 2, '2017-03-28 12:52:16', '2017-03-28 12:52:16'),
	(10, 81, 'سشسي', 2, '2017-03-28 13:43:18', '2017-03-28 13:43:18'),
	(11, 81, 'عيان', 2, '2017-03-28 13:49:02', '2017-03-28 13:49:02'),
	(12, 82, 'شكوي', 2, '2017-03-30 10:59:49', '2017-03-30 10:59:49'),
	(19, 84, 'عيان', 2, '2017-04-02 10:20:08', '2017-04-02 10:20:08'),
	(20, 85, 'ألم', 2, '2017-04-02 10:22:10', '2017-04-02 10:22:10'),
	(21, 85, 'عيان', 2, '2017-04-02 10:27:04', '2017-04-02 10:27:04'),
	(22, 85, 'سشسي', 2, '2017-04-02 10:28:15', '2017-04-02 10:28:15'),
	(23, 85, 'الم فى الظهر', 2, '2017-04-02 11:28:07', '2017-04-02 11:28:07'),
	(24, 86, 'شكوي', 2, '2017-04-02 12:31:56', '2017-04-02 12:31:56'),
	(25, 90, 'شكوي', 2, '2017-04-03 08:26:57', '2017-04-03 08:26:57'),
	(26, 141, 'شكوي', 2, '2017-06-13 13:23:45', '2017-06-13 13:23:45'),
	(27, 142, 'Casds', 2, '2017-06-21 11:54:52', '2017-06-21 11:54:52');
/*!40000 ALTER TABLE `visit_complaints` ENABLE KEYS */;

-- Dumping structure for table pdreg.visit_diagnoses
CREATE TABLE IF NOT EXISTS `visit_diagnoses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `visit_id` int(10) unsigned NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `typist_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `visit_diagnoses_visit_id_index` (`visit_id`),
  CONSTRAINT `visit_diagnoses_visit_id_foreign` FOREIGN KEY (`visit_id`) REFERENCES `visits` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.visit_diagnoses: ~11 rows (approximately)
/*!40000 ALTER TABLE `visit_diagnoses` DISABLE KEYS */;
INSERT INTO `visit_diagnoses` (`id`, `visit_id`, `content`, `typist_id`, `created_at`, `updated_at`) VALUES
	(1, 78, 'اشتباة فى كسر', 2, '2017-03-20 08:53:13', '2017-03-20 08:53:13'),
	(2, 80, 'اشتباة فى كسر', 2, '2017-03-28 08:40:26', '2017-03-28 08:40:26'),
	(3, 81, 'اشتباة فى كسر', 2, '2017-03-28 13:41:28', '2017-03-28 13:41:28'),
	(4, 81, 'اشتباة فى كسر', 2, '2017-03-28 13:42:56', '2017-03-28 13:42:56'),
	(5, 81, 'سشش', 2, '2017-03-28 13:44:02', '2017-03-28 13:44:02'),
	(6, 81, 'اورام', 2, '2017-03-28 13:52:36', '2017-03-28 13:52:36'),
	(7, 82, 'تشخيص', 2, '2017-03-30 11:58:30', '2017-03-30 11:58:30'),
	(12, 85, 'اشتباة فى كسر', 2, '2017-04-02 10:22:10', '2017-04-02 10:22:10'),
	(13, 85, 'تشخيص', 2, '2017-04-02 10:25:31', '2017-04-02 10:25:31'),
	(14, 85, 'اورام', 2, '2017-04-02 10:27:35', '2017-04-02 10:27:35'),
	(15, 90, 'اشتباة فى كسر', 2, '2017-04-03 09:10:32', '2017-04-03 09:10:32');
/*!40000 ALTER TABLE `visit_diagnoses` ENABLE KEYS */;

-- Dumping structure for table pdreg.visit_medicines
CREATE TABLE IF NOT EXISTS `visit_medicines` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `visit_id` int(10) unsigned NOT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `typist_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `visit_medicines_visit_id_index` (`visit_id`),
  CONSTRAINT `visit_medicines_visit_id_foreign` FOREIGN KEY (`visit_id`) REFERENCES `visits` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.visit_medicines: ~8 rows (approximately)
/*!40000 ALTER TABLE `visit_medicines` DISABLE KEYS */;
INSERT INTO `visit_medicines` (`id`, `visit_id`, `name`, `typist_id`, `created_at`, `updated_at`) VALUES
	(1, 40, 'Ketofan', 2, '2017-03-08 12:47:24', '2017-03-08 12:47:24'),
	(2, 78, 'Brofen', 2, '2017-03-20 08:53:29', '2017-03-20 08:53:29'),
	(3, 78, 'Cold flu', 2, '2017-03-20 08:54:26', '2017-03-20 08:54:26'),
	(4, 85, 'Cold flu', 2, '2017-04-02 11:27:40', '2017-04-02 11:27:40'),
	(5, 85, 'SeyMedicine', 2, '2017-04-02 11:37:33', '2017-04-02 11:37:33'),
	(6, 85, 'Medicine', 2, '2017-04-02 12:13:01', '2017-04-02 12:13:01'),
	(7, 86, 'Cold flu', 2, '2017-04-02 12:32:04', '2017-04-02 12:32:04'),
	(8, 90, 'Cold flu', 2, '2017-04-03 09:10:46', '2017-04-03 09:10:46');
/*!40000 ALTER TABLE `visit_medicines` ENABLE KEYS */;

-- Dumping structure for table pdreg.wsconfig
CREATE TABLE IF NOT EXISTS `wsconfig` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` text COLLATE utf8_unicode_ci NOT NULL,
  `sending_app` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `sending_fac` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `receiving_app` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `receiving_fac` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdreg.wsconfig: ~0 rows (approximately)
/*!40000 ALTER TABLE `wsconfig` DISABLE KEYS */;
/*!40000 ALTER TABLE `wsconfig` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
