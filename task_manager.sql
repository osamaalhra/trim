-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 01 ديسمبر 2025 الساعة 05:35
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `task_manager`
--

-- --------------------------------------------------------

--
-- بنية الجدول `daily_tasks`
--

CREATE TABLE `daily_tasks` (
  `id` int(11) NOT NULL,
  `task_id` int(11) DEFAULT NULL,
  `assigned_date` date NOT NULL,
  `completed` tinyint(1) DEFAULT 0,
  `completed_at` timestamp NULL DEFAULT NULL,
  `actual_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `daily_tasks`
--

INSERT INTO `daily_tasks` (`id`, `task_id`, `assigned_date`, `completed`, `completed_at`, `actual_time`) VALUES
(1, 2, '2025-12-01', 0, NULL, NULL),
(2, 3, '2025-12-01', 0, NULL, NULL),
(3, 1, '2025-12-01', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- بنية الجدول `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `duration` int(11) NOT NULL,
  `difficulty` enum('سهلة','متوسطة','صعبة') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estimated_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `description`, `duration`, `difficulty`, `created_at`, `updated_at`, `estimated_time`) VALUES
(1, 'سوفتوير ', 'سوفتوير A20', 30, 'متوسطة', '2025-12-01 04:28:28', '2025-12-01 04:28:28', 0),
(2, 'صيانة', 'صيانة معالج', 120, 'سهلة', '2025-12-01 04:29:54', '2025-12-01 04:29:54', 0),
(3, 'برمجة', 'سهلة ', 120, 'سهلة', '2025-12-01 04:30:20', '2025-12-01 04:30:20', 0);

-- --------------------------------------------------------

--
-- بنية الجدول `time_tracking`
--

CREATE TABLE `time_tracking` (
  `id` int(11) NOT NULL,
  `daily_task_id` int(11) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$jRYoYphg2pUni6Sqt8jFM.0PvfUhx5PdhRK8ruQxFztYS7i1z7gZe', 'admin', '2025-12-01 04:26:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `daily_tasks`
--
ALTER TABLE `daily_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `time_tracking`
--
ALTER TABLE `time_tracking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `daily_task_id` (`daily_task_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `daily_tasks`
--
ALTER TABLE `daily_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `time_tracking`
--
ALTER TABLE `time_tracking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- قيود الجداول المُلقاة.
--

--
-- قيود الجداول `daily_tasks`
--
ALTER TABLE `daily_tasks`
  ADD CONSTRAINT `daily_tasks_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`);

--
-- قيود الجداول `time_tracking`
--
ALTER TABLE `time_tracking`
  ADD CONSTRAINT `time_tracking_ibfk_1` FOREIGN KEY (`daily_task_id`) REFERENCES `daily_tasks` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
