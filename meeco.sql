-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 10, 2025 at 12:08 AM
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
-- Database: `meeco`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_actions`
--

CREATE TABLE `admin_actions` (
  `action_id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `action_type` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(1, '2024_11_22_161521_create_tables', 1),
(2, '2024_11_23_080413_create_sessions_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `multiple_choice`
--

CREATE TABLE `multiple_choice` (
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `answer` mediumint(9) NOT NULL,
  `A` mediumtext NOT NULL,
  `B` mediumtext NOT NULL,
  `C` mediumtext NOT NULL,
  `D` mediumtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `promos`
--

CREATE TABLE `promos` (
  `promo_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `perks` text NOT NULL,
  `duration` int(11) NOT NULL COMMENT 'Duration in days',
  `limitations` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `topic_id` bigint(20) UNSIGNED NOT NULL,
  `question_text` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `raw`
--

CREATE TABLE `raw` (
  `raw_id` bigint(20) UNSIGNED NOT NULL,
  `topic_id` bigint(20) UNSIGNED NOT NULL,
  `raw_text` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `raw`
--

INSERT INTO `raw` (`raw_id`, `topic_id`, `raw_text`, `created_at`, `updated_at`) VALUES
(56, 138, '=Image 1=\nFIRST BROWSER WAR (1995-2001)\nMOSAIC S\nSUMMAR\n①Netscape Navigators Dominande\n2\n>the first widely popular web browser\nafter its release in 1994\nSSW\n>it quickly gained a large share of the\nmarket, becoming the dominant browser\nin the early days of the www.\n>Mozzilla Crenamed)\nMicrosoft\'s Entry with Internet Explorer\n> entered the browser market by releasing\nIE in 1995 as part of its Windows 95 plus! pack\n> IE was initially based on code licensed\nfrom Spyglass mosaic, which itself was a\nlicensed ver of it.\n3 The Battle Intensipies!\n99\n99\n> MS began aggressively bundling IE\nwith it\'s Windows operating system,\nwhich was already installed on a majoriy\nof personal computers. This strategy\nx\ngave IE a significant distribution advantage\nleading to rapid growth in its market share.\n>Netscape initially charged for its browser,\nwhile MS offered It for free.\n000\n>Netscape began to declive as It\'s\npresense grew.\n④Antitrust Lawsuit Against Microsoft\nMS,\nthe aggresive tactics employed by mis,\nled to an antitrust law suit, by the us\nDept. of Justice in 1998. The case argued\nthat m\'s was using its windws monopoly to\nstiple competition in brow sau market.', '2025-02-07 00:00:39', '2025-02-07 00:00:57');

-- --------------------------------------------------------

--
-- Table structure for table `reviewer`
--

CREATE TABLE `reviewer` (
  `reviewer_id` bigint(20) UNSIGNED NOT NULL,
  `topic_id` bigint(20) UNSIGNED NOT NULL,
  `reviewer_text` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviewer`
--

INSERT INTO `reviewer` (`reviewer_id`, `topic_id`, `reviewer_text`, `created_at`, `updated_at`) VALUES
(21, 138, '---\n**Subject:** History of Web Browsers  \n**Card 1:** The First Browser War (1995-2001)  \nThe First Browser War took place between 1995 and 2001, marking a significant competition in the web browser market.\n\n**Card 2:** Mosaic and Netscape Navigator  \nMosaic was the first widely popular web browser, released in 1994. Netscape Navigator quickly gained popularity, becoming the dominant browser in the early days of the World Wide Web.\n\n**Card 3:** Microsoft\'s Entry with Internet Explorer  \nMicrosoft entered the browser market in 1995 with Internet Explorer (IE), included in the Windows 95 Plus! pack. IE was initially based on code licensed from Spyglass Mosaic.\n\n**Card 4:** Bundling Strategy and Market Share  \nMicrosoft aggressively bundled IE with its Windows operating system, giving it a significant distribution advantage. This strategy led to rapid growth in IE\'s market share.\n\n**Card 5:** Netscape\'s Decline  \nNetscape initially charged for its browser, while Microsoft offered IE for free. As a result, Netscape began to decline in market presence.\n\n**Card 6:** Antitrust Lawsuit Against Microsoft  \nThe aggressive tactics of Microsoft led to an antitrust lawsuit by the U.S. Department of Justice in 1998, arguing that Microsoft was using its Windows monopoly to stifle competition in the browser market.  \n---', '2025-02-07 00:13:06', '2025-02-07 00:13:06');

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
('z0Z5YaLje1H1YKtiv0hsdiExL1PTiQrZI0qPJ0lY', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOXpaSzhEYmR6UEZDYjJrQjZ5TEU3a2YxbDM3WWNJaVRSSDYzOWtuTCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3RvcGljcyI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1739126845);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `user_id`, `name`, `created_at`, `updated_at`) VALUES
(111, 11, 'New', '2025-02-07 00:00:20', '2025-02-07 00:00:20');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `subscription_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `promo_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `end_date` timestamp NULL DEFAULT NULL,
  `status` enum('active','cancelled','expired') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE `topics` (
  `topic_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `topics`
--

INSERT INTO `topics` (`topic_id`, `subject_id`, `name`, `created_at`, `updated_at`) VALUES
(138, 111, 'New topic', '2025-02-07 00:00:27', '2025-02-07 00:00:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `firstname`, `lastname`, `email`, `password`, `role`, `date_created`, `last_login`, `created_at`, `updated_at`, `remember_token`) VALUES
(8, 'jomark', 'abello', 'hehe@gmail.com', '$2y$12$sR.zGuoEA9G2g1wERl1EyOXQJfnjF2RRxoN3t/EPz7nYAg1dSAPXO', 'user', '2025-01-31 07:12:06', NULL, '2025-01-30 23:12:06', '2025-01-30 23:12:06', NULL),
(9, 'dean', 'nene', 'what@gmail.com', '$2y$12$RM4XZx2S/rhM9/EnYQdSwe8TdEVeDS66biBGdYs9H68CkUVNPXAUy', 'user', '2025-01-31 07:28:25', NULL, '2025-01-30 23:28:25', '2025-01-30 23:28:25', NULL),
(10, 'hue', 'sca', 'krisdasabbaani@gmail.com', '$2y$12$aYiRitOt0Ycg4EdI2Enl5OT9f2be9gGfKHjsoU2F1vCRNkVjcM1kK', 'user', '2025-01-31 07:38:53', NULL, '2025-01-30 23:38:53', '2025-01-30 23:38:53', NULL),
(11, 'Sharief kundo', 'Mudrikal', 'shariefkundo@gmail.com', '$2y$12$e0hZ3I7bYN.tw82heMyo1.wKuxXMFcwF.nwMMtv8CIn2Np93oBoe6', 'user', '2025-02-03 13:48:51', NULL, '2025-02-03 05:48:51', '2025-02-03 05:48:51', NULL),
(12, 'Sharief kundo', 'Mudrikal', 'shariefkundo19@gmail.com', '$2y$12$UXg0z1l/6jlGFYU1jlz1r.go22pIvM2drKIvTfDM/UjFTJxkE4G9O', 'user', '2025-02-03 13:53:29', NULL, '2025-02-03 05:53:29', '2025-02-03 05:53:29', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_actions`
--
ALTER TABLE `admin_actions`
  ADD PRIMARY KEY (`action_id`),
  ADD KEY `admin_actions_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `multiple_choice`
--
ALTER TABLE `multiple_choice`
  ADD UNIQUE KEY `question_id` (`question_id`),
  ADD KEY `question_id_2` (`question_id`);

--
-- Indexes for table `promos`
--
ALTER TABLE `promos`
  ADD PRIMARY KEY (`promo_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `questions_topic_id_foreign` (`topic_id`);

--
-- Indexes for table `raw`
--
ALTER TABLE `raw`
  ADD PRIMARY KEY (`raw_id`),
  ADD KEY `raw_topic_id_foreign` (`topic_id`);

--
-- Indexes for table `reviewer`
--
ALTER TABLE `reviewer`
  ADD PRIMARY KEY (`reviewer_id`),
  ADD KEY `reviewer_topic_id_foreign` (`topic_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`),
  ADD UNIQUE KEY `subjects_name_unique` (`name`),
  ADD KEY `subjects_user_id_foreign` (`user_id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`subscription_id`),
  ADD KEY `subscriptions_user_id_foreign` (`user_id`),
  ADD KEY `subscriptions_promo_id_foreign` (`promo_id`);

--
-- Indexes for table `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`topic_id`),
  ADD KEY `topics_subject_id_foreign` (`subject_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_actions`
--
ALTER TABLE `admin_actions`
  MODIFY `action_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `promos`
--
ALTER TABLE `promos`
  MODIFY `promo_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `raw`
--
ALTER TABLE `raw`
  MODIFY `raw_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `reviewer`
--
ALTER TABLE `reviewer`
  MODIFY `reviewer_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `subscription_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `topics`
--
ALTER TABLE `topics`
  MODIFY `topic_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_actions`
--
ALTER TABLE `admin_actions`
  ADD CONSTRAINT `admin_actions_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `multiple_choice`
--
ALTER TABLE `multiple_choice`
  ADD CONSTRAINT `question_id` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `raw`
--
ALTER TABLE `raw`
  ADD CONSTRAINT `raw_topic_id_foreign` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`topic_id`) ON DELETE CASCADE;

--
-- Constraints for table `reviewer`
--
ALTER TABLE `reviewer`
  ADD CONSTRAINT `reviewer_topic_id_foreign` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`topic_id`) ON DELETE CASCADE;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_promo_id_foreign` FOREIGN KEY (`promo_id`) REFERENCES `promos` (`promo_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `topics`
--
ALTER TABLE `topics`
  ADD CONSTRAINT `topics_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
