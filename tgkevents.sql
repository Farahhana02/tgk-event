-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 31, 2025 at 05:54 AM
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
-- Database: `tgkevents`
--

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
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fundraiser_id` bigint(20) UNSIGNED NOT NULL,
  `donor_name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `amount_pledge` decimal(12,2) NOT NULL,
  `notes` longtext DEFAULT NULL,
  `receipt_file` varchar(255) DEFAULT NULL,
  `submitted_form_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved') NOT NULL DEFAULT 'pending',
  `donate_time` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `fundraisers`
--

CREATE TABLE `fundraisers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `programme_name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `target_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `progress` decimal(5,2) NOT NULL DEFAULT 0.00,
  `image_path` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `form_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2025_11_25_073839_create_sessions_table', 1),
(4, '2025_11_25_143200_create_users_table', 1),
(5, '2025_11_25_144000_create_sponsorship_files_table', 1),
(6, '2025_11_25_144100_create_fundraisers_table', 1),
(7, '2025_11_25_144200_create_donations_table', 1),
(8, '2025_12_03_013009_create_settings_table', 1),
(9, '2025_12_07_065643_create_programs_table', 1),
(10, '2025_12_08_023501_drop_award_programs_tables', 1),
(11, '2025_12_08_034220_add_visible_sections_to_programs_table', 1),
(12, '2025_12_09_070348_update_program_columns_to_json', 1),
(13, '2025_12_10_023331_create_programme_items_table', 1),
(14, '2025_12_13_030001_create_packages_table', 1),
(15, '2025_12_13_030003_create_programme_packages_table', 1),
(16, '2025_12_14_030001_create_participation_programmes_table', 1),
(17, '2025_12_14_030001_create_payment_methods_table', 1),
(18, '2025_12_14_030002_create_programme_payment_methods_table', 1),
(19, '2025_12_14_030003_create_participation_submissions_table', 1),
(20, '2025_12_14_030004_create_participation_participants_table', 1),
(21, '2025_12_15_012123_add_participation_additional_files_to_programs_table', 1),
(22, '2025_12_15_071147_fix_public_token_nullable', 1),
(23, '2025_12_16_081428_create_photo_items_table', 1),
(26, '2025_12_18_001616_participation_programme_packages', 2),
(29, '2025_12_18_001712_update_participation_submissions_foreign_key', 3),
(30, '2025_12_18_014600_cleanup_duplicate_programme_package_links', 4),
(31, '2025_12_18_014500_cleanup_duplicate_packages', 5),
(32, '2025_12_18_020000_fix_constraint_dependencies', 6),
(33, '2025_12_18_013656_add_unique_constraints_to_master_tables', 7),
(34, '2025_12_18_032000_add_override_columns_fixed', 8),
(35, '2025_12_18_013827_fix_foreign_key_naming', 9),
(36, '2025_12_18_013939_make_public_token_nullable', 9),
(37, '2025_12_18_014020_add_performance_indexes', 9),
(38, '2025_12_18_034206_add_description_to_participation_programme_packages_table', 10),
(39, '2025_12_18_040253_modify_sort_order_in_participation_programme_packages', 11),
(40, '2025_12_22_071320_add_table_number_to_participation_submissions_table', 12),
(41, '2025_12_22_072359_add_table_number_to_participation_participants_table', 13),
(42, '2025_12_22_080913_add_programme_id_to_participation_programmes_table', 14),
(43, '2025_12_22_162120_make_package_payment_nullable_in_participation_submissions_table', 14),
(44, '2025_12_23_005954_add_participation_programme_id_to_programs_table', 15),
(45, '2025_12_23_043119_add_form_path_to_fundraisers_table', 16),
(46, '2025_12_23_044030_add_submitted_form_path_to_donations_table', 17),
(47, '2025_12_23_072238_add_upload_form_path_to_participation_programmes', 18),
(48, '2025_12_23_140944_add_supporting_document_to_participation_submissions', 19);

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `package_type` enum('one_person','multi_person') NOT NULL,
  `default_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `people_per_package` smallint(5) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `participation_participants`
--

CREATE TABLE `participation_participants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `submission_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `table_number` varchar(20) DEFAULT NULL,
  `sort_order` smallint(5) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `participation_programmes`
--

CREATE TABLE `participation_programmes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `programme_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `venue` varchar(255) DEFAULT NULL,
  `public_token` varchar(64) DEFAULT NULL,
  `qr_path` varchar(255) DEFAULT NULL,
  `receipt_max_mb` smallint(5) UNSIGNED NOT NULL DEFAULT 20,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `upload_form_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `participation_programme_packages`
--

CREATE TABLE `participation_programme_packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `programme_id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `people_per_package` smallint(5) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_locked` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` smallint(5) UNSIGNED NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `participation_submissions`
--

CREATE TABLE `participation_submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `programme_id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `officer_name` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `participation_programme_package_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `expected_participants` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `total_price` decimal(10,2) NOT NULL,
  `programme_payment_method_id` bigint(20) UNSIGNED DEFAULT NULL,
  `receipt_path` varchar(255) DEFAULT NULL,
  `receipt_original_name` varchar(255) DEFAULT NULL,
  `receipt_size` bigint(20) UNSIGNED DEFAULT NULL,
  `receipt_mime` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `admin_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `supporting_document_path` varchar(255) DEFAULT NULL,
  `supporting_document_original` varchar(255) DEFAULT NULL,
  `supporting_document_size` int(11) DEFAULT NULL,
  `supporting_document_mime` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bank` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `photo_items`
--

CREATE TABLE `photo_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `program_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT 'Photo',
  `image` varchar(255) NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `programme_items`
--

CREATE TABLE `programme_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `program_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `programme_payment_methods`
--

CREATE TABLE `programme_payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `programme_id` bigint(20) UNSIGNED NOT NULL,
  `payment_method_id` bigint(20) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `participation_programme_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `introduction` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`introduction`)),
  `background` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`background`)),
  `objectives` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`objectives`)),
  `event_date` date DEFAULT NULL,
  `event_time` time DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `theme` varchar(255) DEFAULT NULL,
  `schedules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`schedules`)),
  `vip_list` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`vip_list`)),
  `participation_description` text DEFAULT NULL,
  `participation_prices` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`participation_prices`)),
  `participation_additional_files` varchar(255) DEFAULT NULL,
  `participation_form_type` enum('file','link') NOT NULL DEFAULT 'file',
  `participation_form` varchar(255) DEFAULT NULL,
  `sponsorship_description` text DEFAULT NULL,
  `sponsorship_packages` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`sponsorship_packages`)),
  `sponsorship_additional_files` varchar(255) DEFAULT NULL,
  `sponsorship_form_type` enum('file','link') NOT NULL DEFAULT 'file',
  `sponsorship_form` varchar(255) DEFAULT NULL,
  `programme_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`programme_images`)),
  `programme_name` varchar(255) DEFAULT NULL,
  `programme_description` text DEFAULT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `visible_sections` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`visible_sections`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kia_enabled` tinyint(1) NOT NULL DEFAULT 0,
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
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@kedahforward.com', '$2y$12$JFugDCXivE0JwVDSerAjG.PiEPpN/bhaoBqaPfjmBN1ZkTt1nmHfG', '2025-12-17 06:00:27', '2025-12-17 06:00:27');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donations_fundraiser_id_foreign` (`fundraiser_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fundraisers`
--
ALTER TABLE `fundraisers`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_packages_name_type` (`name`,`package_type`),
  ADD KEY `idx_packages_active` (`is_active`),
  ADD KEY `idx_packages_name_type` (`name`,`package_type`);

--
-- Indexes for table `participation_participants`
--
ALTER TABLE `participation_participants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `participation_participants_submission_id_index` (`submission_id`);

--
-- Indexes for table `participation_programmes`
--
ALTER TABLE `participation_programmes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `participation_programmes_public_token_unique` (`public_token`),
  ADD KEY `participation_programmes_programme_id_foreign` (`programme_id`);

--
-- Indexes for table `participation_programme_packages`
--
ALTER TABLE `participation_programme_packages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_participation_programme_package` (`programme_id`,`package_id`),
  ADD KEY `participation_programme_packages_package_id_foreign` (`package_id`),
  ADD KEY `idx_part_prog_packages_prog_active` (`programme_id`,`is_active`),
  ADD KEY `idx_part_prog_packages_locked` (`is_locked`),
  ADD KEY `idx_part_prog_packages_order` (`sort_order`);

--
-- Indexes for table `participation_submissions`
--
ALTER TABLE `participation_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `participation_submissions_programme_id_status_index` (`programme_id`,`status`),
  ADD KEY `fk_part_sub_prog_pkg` (`participation_programme_package_id`),
  ADD KEY `fk_part_sub_prog_pay_method` (`programme_payment_method_id`),
  ADD KEY `idx_part_submissions_prog_status_date` (`programme_id`,`status`,`created_at`),
  ADD KEY `idx_part_submissions_company` (`company_name`),
  ADD KEY `idx_part_submissions_created` (`created_at`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_payment_account_number` (`account_number`),
  ADD KEY `idx_payment_methods_active` (`is_active`),
  ADD KEY `idx_payment_methods_bank` (`bank`);

--
-- Indexes for table `photo_items`
--
ALTER TABLE `photo_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `photo_items_program_id_index` (`program_id`),
  ADD KEY `photo_items_order_index` (`order`);

--
-- Indexes for table `programme_items`
--
ALTER TABLE `programme_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `programme_items_program_id_foreign` (`program_id`);

--
-- Indexes for table `programme_payment_methods`
--
ALTER TABLE `programme_payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_programme_payment` (`programme_id`,`payment_method_id`),
  ADD KEY `programme_payment_methods_payment_method_id_foreign` (`payment_method_id`),
  ADD KEY `idx_prog_payment_methods_prog_active` (`programme_id`,`is_active`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `programs_participation_programme_id_foreign` (`participation_programme_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fundraisers`
--
ALTER TABLE `fundraisers`
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `participation_participants`
--
ALTER TABLE `participation_participants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `participation_programmes`
--
ALTER TABLE `participation_programmes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `participation_programme_packages`
--
ALTER TABLE `participation_programme_packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `participation_submissions`
--
ALTER TABLE `participation_submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `photo_items`
--
ALTER TABLE `photo_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `programme_items`
--
ALTER TABLE `programme_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `programme_payment_methods`
--
ALTER TABLE `programme_payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_fundraiser_id_foreign` FOREIGN KEY (`fundraiser_id`) REFERENCES `fundraisers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `participation_participants`
--
ALTER TABLE `participation_participants`
  ADD CONSTRAINT `fk_part_part_submission` FOREIGN KEY (`submission_id`) REFERENCES `participation_submissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `participation_programmes`
--
ALTER TABLE `participation_programmes`
  ADD CONSTRAINT `participation_programmes_programme_id_foreign` FOREIGN KEY (`programme_id`) REFERENCES `programs` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `participation_programme_packages`
--
ALTER TABLE `participation_programme_packages`
  ADD CONSTRAINT `participation_programme_packages_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`),
  ADD CONSTRAINT `participation_programme_packages_programme_id_foreign` FOREIGN KEY (`programme_id`) REFERENCES `participation_programmes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `participation_submissions`
--
ALTER TABLE `participation_submissions`
  ADD CONSTRAINT `fk_part_sub_pay_method` FOREIGN KEY (`programme_payment_method_id`) REFERENCES `programme_payment_methods` (`id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `fk_part_sub_prog_pay_method` FOREIGN KEY (`programme_payment_method_id`) REFERENCES `programme_payment_methods` (`id`),
  ADD CONSTRAINT `fk_part_sub_prog_pkg` FOREIGN KEY (`participation_programme_package_id`) REFERENCES `participation_programme_packages` (`id`),
  ADD CONSTRAINT `participation_submissions_programme_id_foreign` FOREIGN KEY (`programme_id`) REFERENCES `participation_programmes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `photo_items`
--
ALTER TABLE `photo_items`
  ADD CONSTRAINT `photo_items_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `programme_items`
--
ALTER TABLE `programme_items`
  ADD CONSTRAINT `programme_items_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `programme_payment_methods`
--
ALTER TABLE `programme_payment_methods`
  ADD CONSTRAINT `programme_payment_methods_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`),
  ADD CONSTRAINT `programme_payment_methods_programme_id_foreign` FOREIGN KEY (`programme_id`) REFERENCES `participation_programmes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `programs`
--
ALTER TABLE `programs`
  ADD CONSTRAINT `programs_participation_programme_id_foreign` FOREIGN KEY (`participation_programme_id`) REFERENCES `participation_programmes` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
