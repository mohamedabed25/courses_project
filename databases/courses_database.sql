-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:4306
-- Generation Time: Nov 30, 2024 at 12:08 PM
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
-- Database: `users_courses_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `phone`, `password`) VALUES
(1, 'admin@admin.com', '01553967465', '123456');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `instructor_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `less_than_10` tinyint(1) DEFAULT 1,
  `is_free` tinyint(1) DEFAULT 1,
  `course_price` decimal(8,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `instructor_name`, `created_at`, `updated_at`, `less_than_10`, `is_free`, `course_price`) VALUES
(6, 'fsdfsdf', 'sdf', 'sdfsd', '2024-11-28 20:20:59', '2024-11-29 13:09:03', 0, 0, 80.00),
(7, 'werew', 'rre', 'rewr', '2024-11-28 20:28:28', '2024-11-29 13:09:13', 1, 0, 90.00),
(8, 'Rem amet irure nost', 'Harum deleniti venia', 'Jameson Christian', '2024-11-29 13:57:18', '2024-11-29 15:05:16', 0, 1, 0.00),
(9, 'Rem amet irure nost', 'Harum deleniti venia', 'Jameson Christian', '2024-11-29 13:58:10', '2024-11-29 13:58:10', 1, 1, 0.00),
(10, 'Est itaque eum itaq', 'Occaecat totam natus', 'Jin Torres', '2024-11-29 14:04:39', '2024-11-29 14:04:39', 1, 1, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `course_contents`
--

CREATE TABLE `course_contents` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content_type` enum('text','video','pdf','url','quiz') NOT NULL,
  `content_value` text DEFAULT NULL,
  `video_file_path` varchar(255) DEFAULT NULL,
  `pdf_file_path` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `quiz_id` int(11) DEFAULT NULL,
  `order` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_contents`
--

INSERT INTO `course_contents` (`id`, `course_id`, `title`, `content_type`, `content_value`, `video_file_path`, `pdf_file_path`, `url`, `quiz_id`, `order`, `created_at`, `updated_at`) VALUES
(10, 6, '2', 'pdf', NULL, NULL, 'uploads/pdfs/System Analysis And Design_Lec_3.pdf', NULL, NULL, 22, '2024-11-28 21:50:52', '2024-11-28 21:50:52'),
(11, 7, 'weqwe', 'video', NULL, 'uploads/videos/Screen_Recording_٢٠٢٤١١١١_١٥١٣٤٨.mp4', NULL, NULL, NULL, 2, '2024-11-28 22:06:32', '2024-11-28 22:06:32'),
(12, 6, 'rwer', 'text', 'ewrw', NULL, NULL, NULL, NULL, 3, '2024-11-28 23:06:21', '2024-11-28 23:06:21'),
(13, 6, 'بيسبثص', 'url', 'https://demo2.qanoony.pro/dashboard', NULL, NULL, NULL, NULL, 2, '2024-11-29 13:55:01', '2024-11-29 13:55:01'),
(14, 6, 'dasdsad', 'pdf', NULL, NULL, 'uploads/pdfs/Data structure _Lec4 _Dr.Nahla Bishri.pdf', NULL, NULL, 1, '2024-11-29 17:02:16', '2024-11-29 17:02:16'),
(15, 7, 'Culpa nemo quisquam ', 'pdf', NULL, NULL, 'uploads/pdfs/o5h3f0sd.mp4', NULL, NULL, 96, '2024-11-29 17:03:57', '2024-11-29 17:03:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `age` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `wallet` int(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `age`, `email`, `phone`, `country`, `wallet`, `photo`, `created_at`, `updated_at`) VALUES
(20, 'kojiterequ', '4234234', '3234324', 'qoxa@mailinator.com', '+1 (222) 489-8695', 'US', 808, 'name.jpg', '2024-11-08 21:40:50', '2024-11-29 12:29:08'),
(22, 'zihurehusu', '234234234', '38', 'zabypo4234@mailinator.com', '+1 777 964-5407', 'CA', 0, 'name.jpg', '2024-11-08 21:40:50', '2024-11-10 21:06:18'),
(23, 'zotifuzega', 'Pa$$w0rd!', '8', 'cydylysy@mailinator.com', '+1 (205) 924-3952', 'US', 0, 'name.jpg', '2024-11-08 21:40:50', '2024-11-08 21:40:50'),
(24, 'girubovyb', 'Pa$$w0rd!', '18', 'tyvumotu@mailinator.com', '+1 (107) 576-8869', 'CA', 0, 'name.jpg', '2024-11-08 21:40:50', '2024-11-08 21:40:50'),
(25, 'wyrakiricu', '123123123', '52', 'hyro@mailinator.com', '(524) 826-9126', 'CA', 0, '', '2024-11-08 21:40:50', '2024-11-10 21:06:37'),
(28, 'panucuj', 'Pa$$w0rd!', '48', '4234@mo.com', '+1 (451) 216-8252', 'CA', 0, '', '2024-11-08 21:40:50', '2024-11-10 21:19:44'),
(29, 'kavyrop', 'Pa$$w0rd!', '86', 'garu@mailinator.com', '+1 (143) 879-4621', 'US', 0, '', '2024-11-08 21:40:50', '2024-11-08 21:40:50'),
(30, 'tifilo', 'Pa$$w0rd!', '71', 'fdsfsdfsdfsdfsdfsdf@mailinator.com', '+1 (677) 523-4664', 'CA', 0, '', '2024-11-08 21:40:50', '2024-11-09 00:53:47'),
(31, 'narulemy', 'Pa$$w0rd!', '70', 'sixyra@mailinator.com', '+1 (448) 945-6838', 'CA', 0, '', '2024-11-08 21:40:50', '2024-11-09 00:59:28'),
(32, 'mohamed1', '123456', '5', 'mohamed@mohamed.com', '+1 (701) 486-2977', 'CA', 57, 'uploads67312016bc548-Three-logo.png', '2024-11-08 21:40:50', '2024-11-10 21:19:37'),
(34, 'xemynebeh', 'qerwerwer', '', 'zujuzarup@mailinator.com', '+1 (459) 223-3651', '', 0, '', '2024-11-09 00:25:07', '2024-11-09 00:25:07'),
(35, 'jusetac', 'Pa$$w0rd!', '', 'xuze@mailinator.com', '+1 (422) 999-5224', '', 0, '', '2024-11-09 00:26:57', '2024-11-09 00:26:57'),
(36, 'natapyz', 'Pa$$w0rd!', '', 'nafys@mailinator.com', '+1 (979) 261-5056', '', 0, '', '2024-11-09 00:27:23', '2024-11-09 00:27:23'),
(37, 'bunedady', 'Pa$$w0rd!', '', 'favuku@mailinator.com', '+1 (537) 955-5842', '', 0, '', '2024-11-09 00:27:50', '2024-11-09 00:27:50'),
(39, 'hesic', '$2y$10$UGxZ3u6U6fc.7etlLvuN5eohHBY50sP97b3YapncjHg', '', 'pygemu@mailinator.com', '+1 (839) 957-7496', '', 0, '', '2024-11-09 00:49:09', '2024-11-09 00:49:09'),
(40, 'zazajazu', '$2y$10$mRj6U/6l/Iwa9Ld81gNzbe2cng3tPioCj1u8CJfq/xg', '', 'tudizu@mailinator.com', '+1 (861) 416-6174', '', 0, '', '2024-11-09 00:49:47', '2024-11-09 00:49:47'),
(41, 'fyxyjujeq', '$2y$10$7I12ovrb7jCVO403WA4wp.bFCLKZKjyhcH8MsYpax4O', '', 'toki@mailinator.com', '+1 (385) 386-7902', '', 0, '', '2024-11-09 00:50:09', '2024-11-09 00:50:09'),
(46, 'rwer', '$2y$10$QQ9zCvmXXlHiqObDwIAkBu1PcMzI2w3XxfrM0h1iCpl', '', 'abed4021@gmail.com', '01553967465', '', 0, '', '2024-11-29 12:22:55', '2024-11-29 12:22:55');

-- --------------------------------------------------------

--
-- Table structure for table `visa_cards`
--

CREATE TABLE `visa_cards` (
  `card_id` int(11) NOT NULL,
  `cardholder_name` varchar(100) NOT NULL,
  `card_number` char(16) NOT NULL,
  `expiration_date` date NOT NULL,
  `cvv` char(3) NOT NULL,
  `billing_address` varchar(255) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visa_cards`
--

INSERT INTO `visa_cards` (`card_id`, `cardholder_name`, `card_number`, `expiration_date`, `cvv`, `billing_address`, `phone_number`, `email`, `created_at`) VALUES
(3, 'Hope Reese', '2020202020202020', '2019-04-14', '123', 'Sint iste vero aperi', '+1 (518) 104-31', 'wyguz@mailinator.com', '2024-11-02 20:09:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instructor_id` (`instructor_name`);

--
-- Indexes for table `course_contents`
--
ALTER TABLE `course_contents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visa_cards`
--
ALTER TABLE `visa_cards`
  ADD PRIMARY KEY (`card_id`),
  ADD UNIQUE KEY `card_number` (`card_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `course_contents`
--
ALTER TABLE `course_contents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `visa_cards`
--
ALTER TABLE `visa_cards`
  MODIFY `card_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
