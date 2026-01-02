-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 30, 2025 at 02:42 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `codleo_trust`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','admin') NOT NULL DEFAULT 'admin',
  `is_2fa_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `role`, `is_2fa_enabled`, `created_at`, `updated_at`) VALUES
(1, 'Gyanender', 'gyanender@codleo.com', '$2y$12$CSD38k0tbPIR/qltYLbZP.K./BCSamgdFaZK2vw2TxkYA9Vl/XXMC', 'super_admin', 1, '2025-12-15 08:30:40', '2025-12-15 08:30:40'),
(2, 'vikram', 'vikram@codleo.org', '$2y$12$TmcUhowIKdiYHLAU2TZ6B..kT6W8zgRECmhvAxvIj.esMt9TkpXdW', 'admin', 0, '2025-12-22 08:11:26', '2025-12-22 08:11:26');

-- --------------------------------------------------------

--
-- Table structure for table `assign_policies_to_user`
--

CREATE TABLE `assign_policies_to_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `policy_id` bigint(20) UNSIGNED NOT NULL,
  `client_user_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL COMMENT 'admin_id',
  `updated_by` bigint(20) UNSIGNED NOT NULL COMMENT 'admin_id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assign_policies_to_user`
--

INSERT INTO `assign_policies_to_user` (`id`, `policy_id`, `client_user_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(9, 3, 2, 1, 1, '2025-12-30 01:20:39', '2025-12-30 01:20:39');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_users`
--

CREATE TABLE `client_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `is_2fa_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` tinyint(4) NOT NULL COMMENT 'admin_id',
  `updated_by` tinyint(4) NOT NULL COMMENT 'admin_id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `client_users`
--

INSERT INTO `client_users` (`id`, `name`, `email`, `password`, `company_name`, `is_2fa_enabled`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(2, 'Gyanender', 'gyanender@codleo.com', '$2y$12$2jksfydJMzS4RfRuilLabO3Te1oTLArJyQAfXbRIFaTVOXSE2oWp.', 'Codleo Consulting', 1, 1, 1, '2025-12-15 09:03:31', '2025-12-15 09:03:31');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_12_05_134855_create_two_factor_codes_table', 1),
(5, '2025_12_05_144525_create_admins_table', 1),
(6, '2025_12_09_111243_create_policies_category_table', 1),
(7, '2025_12_09_132155_create_policies_table', 1),
(8, '2025_12_15_131745_create_client_users_table', 1),
(9, '2025_12_15_132855_create_policy_views_table', 1),
(10, '2025_12_15_144114_update_client_users_created_by_columns', 2),
(11, '2025_12_24_073758_create_assign_policies_to_user_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `policies`
--

CREATE TABLE `policies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL COMMENT 'admin_id',
  `updated_by` int(11) NOT NULL COMMENT 'admin_id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `policies`
--

INSERT INTO `policies` (`id`, `category_id`, `title`, `description`, `file_path`, `is_published`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(2, 2, 'ISO Test', 'ISO Testing policy', 'policies/4DT3h0D74whYdVsaZNO5cw9EEMJhGBZ7lJfJywfM.pdf', 1, 1, 1, '2025-12-22 05:59:28', '2025-12-30 01:11:11'),
(3, 2, 'IT Security', 'IT Security Testing policy', 'policies/J6NZaeLCFAQcI32GWgyBxW5IzcFQn6KpjSFW5hCy.pdf', 1, 2, 2, '2025-12-22 08:14:51', '2025-12-22 08:14:51'),
(4, 4, 'HR Policy', 'Testing HR Policy', 'policies/ycka6caiPMT2USA3CgQNR6xxjhGBp9RsElyjhhQO.pdf', 1, 1, 1, '2025-12-30 01:09:08', '2025-12-30 01:11:19');

-- --------------------------------------------------------

--
-- Table structure for table `policies_category`
--

CREATE TABLE `policies_category` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL COMMENT 'admin_id',
  `updated_by` bigint(20) UNSIGNED NOT NULL COMMENT 'admin_id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `policies_category`
--

INSERT INTO `policies_category` (`id`, `name`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(2, 'IT', 1, 1, '2025-12-22 05:58:34', '2025-12-22 05:58:34'),
(4, 'HR', 1, 1, '2025-12-30 01:07:58', '2025-12-30 01:07:58');

-- --------------------------------------------------------

--
-- Table structure for table `policy_views`
--

CREATE TABLE `policy_views` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `policy_id` bigint(20) UNSIGNED NOT NULL,
  `client_user_id` bigint(20) UNSIGNED NOT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `policy_views`
--

INSERT INTO `policy_views` (`id`, `policy_id`, `client_user_id`, `ip`, `user_agent`, `viewed_at`, `created_at`, `updated_at`) VALUES
(1, 2, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-22 06:00:58', '2025-12-22 06:00:58', '2025-12-22 06:00:58'),
(2, 2, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-22 06:02:13', '2025-12-22 06:02:13', '2025-12-22 06:02:13'),
(3, 2, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-22 06:02:28', '2025-12-22 06:02:28', '2025-12-22 06:02:28'),
(4, 2, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-24 04:40:47', '2025-12-24 04:40:47', '2025-12-24 04:40:47'),
(5, 3, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-24 04:42:36', '2025-12-24 04:42:36', '2025-12-24 04:42:36'),
(6, 2, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-26 02:44:11', '2025-12-26 02:44:11', '2025-12-26 02:44:11'),
(7, 3, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-26 02:45:00', '2025-12-26 02:45:00', '2025-12-26 02:45:00'),
(8, 2, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-26 04:59:42', '2025-12-26 04:59:42', '2025-12-26 04:59:42'),
(9, 2, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-26 05:17:02', '2025-12-26 05:17:02', '2025-12-26 05:17:02'),
(10, 2, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-26 05:35:51', '2025-12-26 05:35:51', '2025-12-26 05:35:51'),
(11, 2, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-26 05:58:02', '2025-12-26 05:58:02', '2025-12-26 05:58:02'),
(12, 3, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-26 08:36:54', '2025-12-26 08:36:54', '2025-12-26 08:36:54'),
(13, 3, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-26 08:36:54', '2025-12-26 08:36:54', '2025-12-26 08:36:54'),
(14, 3, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-26 08:54:00', '2025-12-26 08:54:00', '2025-12-26 08:54:00'),
(15, 3, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-30 05:44:27', '2025-12-30 05:44:27', '2025-12-30 05:44:27');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('CXUeXgzuPi5V1NoDydkYCaG48j67hKZvEtiYJjn4', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'ZXlKcGRpSTZJa2x5VVVFM1RFWjJaSEprWXpSdGJtNHJkV1pzWm1jOVBTSXNJblpoYkhWbElqb2ljbkZaUzBoWGRpc3lkSGhLV0ZnemIxSTRkM0JJY1dKeWNIQkthbmhWV1c4dk0wOVNNRE5DVlc1dFVubENRV1ZFUmtSdlZYcHJNbEJLUlhOSk1FSlRiRlJIUVRWM1RraHdTMVpyVW5aTVduZFhUbUZSTlRKc1JGTnNPV1JHTWtoRlZpOHZhMk5EY1d4M1NGbHNZbHBrVlU5eFZHMXhOMGxRVEdScU1ETjFWbEl4YVZaR1prTkxiMlJKYVRKbFRWRmlSMmhaYVdaTWMzTXphVzVqV2tORGVHdFVZMHBOVEZaVlpGcEpWMjVZYWpsdFNHRk5UR2cxVkhJeVlXaHVSbXhxWW1wMVIybGhPRnBoVmpoa1ltcHBSRWhxUXpKNmNUSkxUbkozY1RkWGIyUllTWFptUkVoME9VSTViaTlyZHpnME9FNTFUMDUyTWxKakwxSjJkREphSzFCaWVtcDJNVmtyUkZselJFOUpUek5JYTBnM2EyVnpabVpTVWxwNVJ6a3hWVGhNYTJkNmFqazVTRE5MSzJOM2VGZFFSbWx1YVhFMGNqWjZVM000UTBSdE1GaExORFEwZWtWUmNtTXZXVEF4VERSNFRuZHJjakpGV1haSlpsUkRZbVZqTUhaeFdFcEdSM1p2UWxKNk5YSlpVMjFKWVV4alR5dEVTR013TTNGWklpd2liV0ZqSWpvaU5HVmlNRGd4TW1ZNFlUa3laalZqTWpRNFl6YzROemc0TkdRNVpqWTFOV1JpWVdGak5XTmlNalZpTURFNU9UQTBaRFF3TldJM1pXTTNZVGRrTW1Zd01pSXNJblJoWnlJNklpSjk=', 1767091629),
('pRciz4OcT3Lf9VFqMY5S1ZAvrUIwyNSNqfPd9R2F', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'ZXlKcGRpSTZJbUZxWkdKclIzVkZOWE5uY2pObVltRk5ObEpOWWtFOVBTSXNJblpoYkhWbElqb2lRVTl5TDJsb2RFcFZVMk5LTnpOdWRsaDZZa3N6YzNOWVlXcFhhalI1VHpFeVoyOVVWa2xZWlhWbU0wbEdiMVJUWTIwck9GRXlZVFJsZFhGdU5tUjRaemRqVkZsM1dYRklSak00YlRkTVdqRk5RbEJKUVRsa2FWbHVVbUZyTWpGWFEyRlhiRUYzU1dJMGQwUlFSbXAzUWt3M016TlRUbWhFZW5ORlIxVkRVR1JhZG5SNlJWVm5WRVU0TTBSbmNqaFFTRkV5VFRkb1pXbzNRMloyY1ZvNVpVaDJRekI2WkNzM1p6ZE1ZbHBhYkd4RlZWYzRLemxPTm5wT2JWbE9Rbk5CUTJvNVZuZFdkVEp1ZVZKTkwxTnZha1Y2VTBGWGNFcHVMelJJY2pGNU9HcFBkRTlXTDJWUlNsUXpSMU4wYURKTFRIaGFNM0pEYkhGbGMwdDViMWhDZEVGWFJYTm1VMXB1Y1VkUGFqUmpXVGMyYlRWWFFpdHJRVzF0V1ZrNU9ISXdZMkZaZUhsc1dFSXZTREpYYlhSNE4zZGpkakEzWW1Rd1IwaGFSV2xDVGxWSmJuVkJUa3RFYlhncmIyUmtkVm93ZW5oTmEwdG5QVDBpTENKdFlXTWlPaUk1Tkdaak56WmpaVFpoWm1ZME9ETTJNMkUyTnpZM1pqVXlZbVk0WW1Vek9XUTNZakUwTnpZME9ERXpabVkzTW1NNE9EZGtZVGhtT1ROak16YzVNRGN5SWl3aWRHRm5Jam9pSW4wPQ==', 1767091578),
('tblJSyWqjaHLkanC0rP5s03vjOQ8EO0mAhfKvosb', NULL, '127.0.0.1', 'Slackbot-LinkExpanding 1.0 (+https://api.slack.com/robots)', 'ZXlKcGRpSTZJbkJOT0dsaE1sb3dRM0psV0U1dlRFeExkbWN5T1djOVBTSXNJblpoYkhWbElqb2lNR2R3UlZkellYbFhMMXB1V1dSUmNETmhjamgwUzIxck5EQlFTQzlOY2xKck4xWTFNWFJyYVZCd1kzWklPWGRUZEhkUE5FOVZMelJyWmpNNVkwZGtNSEF2WmpsVldDOXlkRVJzSzFoM2RsaEZaRTlMVjNBMVJESnVSRXRIZW5WemEySk1PRTlrUVZWb1lTOXZhRVkzVkhCVlVWWk9SbU0yT1RnMlVHUkdZMmgxVDNOb1NUUXJPV28yWVVGaE1FdEpOaTlFYzFNNFlTdEROMlJ1UmxVNWF6TlBkRXMyYURkRGNra3piMjh3WlRsdVRWazBURVJFWW5sSlRHTm1UM2xwYW5VeUt6UXljWGh6U1hGTE5GVkhabGNyYjBOdVUwYzVURmxPWm5oT1ZUbFBWVXBoVUhaWU1VbHRVMWMwZDJacWFuRjNlR3Q0Wm1sSWN6VTRhVnB5V1dReFkxVTFaSGxSYjI4M2VEZzBSVWRCUlZkT2QzcE9NbVp3UXpsSk5WVkZVbFpCTTBaNksxUjNUbFF3YkhoSFpqRmtUazloYjI1TlFtbG1NemxhZFVJNVMwOW5SMnhOWkdobmFYRkpjRzlVZVVsTVZ6SkJQVDBpTENKdFlXTWlPaUptWkRkbU5qVmtObVU0T0dRMU9EWmlPR1k1TmpNNE5ERXpOelUyT0RZM1pUSTROekppWVdFeE5EUTBORGN4TlRFek1HSXpPVE5pWlRGaU5tTmlORFprSWl3aWRHRm5Jam9pSW4wPQ==', 1767091565),
('xi07TKt8YYergyYcKctXvSupOIfSByVRCN1jcRO0', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'ZXlKcGRpSTZJa1Z3UmtzMWJsRnRMMHBSU3pOckwwZGxXbUZUWmtFOVBTSXNJblpoYkhWbElqb2lZamxuVWxkeFMyMTFOR3MwUTFwWVlVaDFOblJOTjFaRVIydHdRamxHTjJ3clVtVXJXV3RKVjIxdGExVjFRMmhOYldkemVIWlFOaklyT1c5alIzUkhhbVJrU2pGcWVUaDVWalpGVVZWVVVXRlNMMjFpVTFFMmRHWjJSbTlVTmxWNGMyUjRURGMzYXpZMVMyd3haRWxLYkVNM2QwRmtSR3RKTDJSNFYycGphaTl3YUM5UVUzVkxRblJ3U1M5aFVqRm5VazlvY0ROaU9HbDJTemRYVlVWRVl6SlVOVUZwY2xJdlIwOW9lbm9yYjFkdWR5OVNOVEZLUTNsaVZEWklSMUk1TkVoRmEzazNMMDRyVldoS05EZ3pXVXB5YkV0a1kwSkljbVY1VG01M1ZubzRjakZUWmpnMGNVdzVkVVJIZEVsT2VtTlpTekZqUkhKR2VuVmFaME5yTUhSb1Frd3ZaVEJyVWpRcmVsWkJVM1I0Ym5WNVpVYzJZbFUyVDNsYVFsUnJNVk5xVVVWV1dqVmhWR3B5TUhKWmVtSmxNR1J0UWsxVVprcG1OVm96ZDBKNFR6ZHNOVGMzYzNONGMyTTNaR0UyVVVGQlVtSkRPV3BtVUVSYVRWSTFOWEF4ZURkUGQxTTJOVUZXT1haU01EUktOWGt6YlVaMFRFZEdMMVpqTTFCNU9FaHVkMFJUVWxCT2VHaDZaVzB3Y2xsMmJGQTBZMGRVZVdOUWIwSmxjalp2WjJocGIwdGxiMDlSTUQwaUxDSnRZV01pT2lJM09EWXdPV0V3WTJJNE9EWTRPV1l6TWpneE1USXhOVEF6TldOak56QXhPVFE1TkRobFkyTTJPRFF6TlRFeFpEQTNNVE15TkRFMk56VmpPRFJrTUdWaElpd2lkR0ZuSWpvaUluMD0=', 1767094781),
('xKfzCq7g3VqWdsFCab2jICjoUTr1OsSKFA0DRyQH', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'ZXlKcGRpSTZJamRVTDNGM2RsWXZkblJpWlZoWlpYQjBVVUZKWmxFOVBTSXNJblpoYkhWbElqb2lNMUU0T1RVdlZVUTFNRFJxVkdoUFZIaHhRbWhTYm1GRU56WjZObGxGWkdSR1NURm9jRXRTWjB3MmJFZzBialJ3WkZSeVkyMDFTMDlzVjBWVmRqSlRaa3QwVFhBcmFXdzBNV05EWjJ4dE0yOXRUME5WWlc5WmEwbEJLMUpaVkZCa1kzSmhXbVpCV1RCcmIyOUZWVzFvVFV0bFRVRnRWV1l5UlZoQk1HbG5SVWxKYTJ4NVdWaDVWVWh6UjFjM2JWUmliMXA0UlRjdldsaGtjSEkxYjJ4VE1YWlVNR3A1UWs5U2IzWTVkakF2TVc5blRYaEhPWEpaZDNaeE4wOVdjRUZtY205cFkwaHdWRUZoTlVvd1JqSXdXSEZYVjB4dlZDOTVWa00yWmpFMWVEQmtkR05tYzFKcVIzRXJOVlppYTNrM1JEbFZTRGc0YzBsNU5IZHdWWGhWVnpBeVdtUXhaRVZMY1VvNWNVeFRUVWNyYzFZck0xcHdVVGxKTUZWd1RHeDFNWHBSWkdaV2NDOHpTM1ZhVmsxbFQwa3hVMGhZVVRkQk9FcEVTbGhQVW5oa2NVeFdPV3cyWVVOR1RTOXRORkZFYW5VNFJHdElkSFJhZFd0NmNEVlJRekVyVlc1bWFsRnZMMWQwTW5SaVRWTmxSR0pxVW5wRFQxVkZWMk5qVVZGMFMxTk5LMVJhZUU4eE9GUkNSMVJqYWxGUGFuTkZRMkpKVW14aVZFVnNOeXN5VlRBMlYyNXliM056WkVwdGVYZExUR05qTlZnM1JVdE1ZV1JoUTBWaVJYb3pZVnBDU1ZOemJrYzFjWHAzTmpCbVp6ZHZhVVl2WnpWTldWTXlaRlJrZEUxMVlqaE5NazVWY1RKU1VqaFdRelJGY1dObldXSjVURkU1VW14T1FXUWlMQ0p0WVdNaU9pSmpNelEyWmpVMk9UaGtOR1V6TnpVd1lURmtNekJtWVRkaE5qSTRZbUkyTW1WbVpEY3daRFptWWpNd09HWmxNakV5WXpkaE1qUTFNelkxWkdRMllqZzFJaXdpZEdGbklqb2lJbjA9', 1767082330),
('YcukKgGGR1MJlmpo1jnFWL5wldVoJsoo88Ko0YpG', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'ZXlKcGRpSTZJbU56UW1aT2RtSjBlVTlyYzFOMmVETnRSRFZoTVVFOVBTSXNJblpoYkhWbElqb2lOVEUyUzBKUVZ6TmpUR0oxYVdWSlJuRXdXVGgwU0N0b2J5c3pkbmhpWjBOcWNISkNTa2xrUkdwVVptaEtjMWRvUlVocGFVMVVjbWh6VW1RdlJGSm9XWHBVYlhKT1ZrVk5SemxwYTBKRVdtOVZjVGRWTmxONVJ6UktXR3QxVDJ0d0wwZHBNazFNYUdoWVdreGhNWGx1WjFCUFVERm5TVE5WVUZCR1ZuaE9TeXRUWkZoRGNDc3JkMU40WW1SRGNXUkhOU3REYjJGT2RHaFVlSGxPY1VkdU1VMW9ZV04xSzJwTFJrUkNZelZoWWxKYVVUQjVRWE50YzJGaGJGRklNM2cwY1c5SVoxQnpjR2hrWjA1WlpEUjVUVk40UzJ0dVFqWjZTMmd6YVdSeU5EbDFPWGx1UzIxc1YzZEVaRlJGVTJ4U2VHZDROMnBYV1hOU05FMVFNa1F3ZVZNeVdrczRkMkpDVDJVdlIxRk1Va0kwVVdwVGIwMWxXbXRKVEdOSFdITTFUbVF5UzNjM2JuRlFOR3M5SWl3aWJXRmpJam9pTVRnME9HVXdOR0k0WmpNd1pUVTRaR00wT0dVd05ETmxZMlkzT0RNNVlUVXdNV0k1WmpkbE5qVmxaV0l3WlRjek1tWTBZbVkyTkdGbU5XUXdOV1F3WmlJc0luUmhaeUk2SWlKOQ==', 1767091422);

-- --------------------------------------------------------

--
-- Table structure for table `two_factor_codes`
--

CREATE TABLE `two_factor_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `user_type` enum('admin','client') NOT NULL,
  `otp` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `assign_policies_to_user`
--
ALTER TABLE `assign_policies_to_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assign_policies_to_user_policy_id_foreign` (`policy_id`),
  ADD KEY `assign_policies_to_user_client_user_id_foreign` (`client_user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `client_users`
--
ALTER TABLE `client_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `client_users_email_unique` (`email`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `policies`
--
ALTER TABLE `policies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `policies_category_id_foreign` (`category_id`);

--
-- Indexes for table `policies_category`
--
ALTER TABLE `policies_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `policy_views`
--
ALTER TABLE `policy_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `policy_views_policy_id_foreign` (`policy_id`),
  ADD KEY `policy_views_client_user_id_foreign` (`client_user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `two_factor_codes`
--
ALTER TABLE `two_factor_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `two_factor_codes_user_id_user_type_index` (`user_id`,`user_type`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `assign_policies_to_user`
--
ALTER TABLE `assign_policies_to_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `client_users`
--
ALTER TABLE `client_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `policies`
--
ALTER TABLE `policies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `policies_category`
--
ALTER TABLE `policies_category`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `policy_views`
--
ALTER TABLE `policy_views`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `two_factor_codes`
--
ALTER TABLE `two_factor_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assign_policies_to_user`
--
ALTER TABLE `assign_policies_to_user`
  ADD CONSTRAINT `assign_policies_to_user_client_user_id_foreign` FOREIGN KEY (`client_user_id`) REFERENCES `client_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assign_policies_to_user_policy_id_foreign` FOREIGN KEY (`policy_id`) REFERENCES `policies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `policies`
--
ALTER TABLE `policies`
  ADD CONSTRAINT `policies_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `policies_category` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `policy_views`
--
ALTER TABLE `policy_views`
  ADD CONSTRAINT `policy_views_client_user_id_foreign` FOREIGN KEY (`client_user_id`) REFERENCES `client_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `policy_views_policy_id_foreign` FOREIGN KEY (`policy_id`) REFERENCES `policies` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
