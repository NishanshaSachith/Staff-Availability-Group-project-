-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2024 at 02:57 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `staff_availability_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `access_level` enum('Super Admin','Editor') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `user_id`, `access_level`) VALUES
(2, 2, 'Super Admin');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `appointment_date` date NOT NULL,
  `status` enum('Pending','Confirmed','Canceled') DEFAULT 'Pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `appointment_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `staff_id`, `student_id`, `appointment_date`, `status`, `created_at`, `appointment_time`) VALUES
(45, 14, 112, '2024-10-23', 'Confirmed', '2024-10-28 21:54:09', '21:57:00'),
(46, 14, 112, '2024-10-28', 'Pending', '2024-10-28 21:55:29', '21:57:00'),
(54, 14, 112, '2024-10-29', 'Pending', '2024-10-28 23:36:23', '17:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `appointment_history`
--

CREATE TABLE `appointment_history` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) DEFAULT NULL,
  `status` enum('Pending','Confirmed','Canceled') DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news_updates`
--

CREATE TABLE `news_updates` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news_updates`
--

INSERT INTO `news_updates` (`id`, `title`, `content`, `created_at`) VALUES
(1, 'Welcome to the Staff Availability System', 'This system allows students to book appointments with staff and manage their availability.', '2024-10-17 12:59:20'),
(2, 'Office Hours for Fall 2024', 'All staff members are available for consultations on weekdays from 9 AM to 5 PM.', '2024-10-17 12:59:20'),
(8, 'dfdf', 'sdhgsrgrgrse', '2024-10-28 23:25:37');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `is_read`, `created_at`) VALUES
(1, NULL, 'Swrga boys', 0, '2024-10-17 12:59:20');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(100) NOT NULL,
  `office_location` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `office_hours` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `name`, `position`, `office_location`, `email`, `phone`, `office_hours`, `created_at`) VALUES
(14, 'Dr. (Mrs). B. Mayurathan', 'Lecturer', 'Room 1', 'barathym@univ.jfn.ac.lk', '', 'Mon-fri: 8 AM - 4 PM', '2024-10-18 16:01:39'),
(15, 'Prof. A. Ramanan', 'Lecturer', 'Room 2', 'a.ramanan@univ.jfn.ac.lk', '', 'Mon-fri: 8 AM - 4 PM', '2024-10-18 16:02:16'),
(16, 'Prof. M. Siyamalan', 'Lecturer', 'room 3', 'siyam@univ.jfn.ac.lk', '', 'Mon-fri: 8 AM - 4 PM', '2024-10-18 16:02:48'),
(17, 'Dr. E. Y. A. Charles', 'Lecturer', 'Room 4', 'charles.ey@univ.jfn.ac.lk', '', 'Mon-fri: 8 AM - 4 PM', '2024-10-18 16:03:48'),
(18, 'Dr. K. Thabotharan', 'Lecturer', 'Room 5', 'thabo@univ.jfn.ac.lk', '', 'Mon-fri: 8 AM - 4 PM', '2024-10-18 16:04:37'),
(19, 'Mr. S. Suthakar', 'Lecturer', 'Room 6', 'sosuthakar@univ.jfn.ac.lk', '', 'Mon-fri: 8 AM - 4 PM', '2024-10-18 16:05:05'),
(20, ' Dr. K. Sarveswaran', 'Lecturer', 'Room 7', 'sarves@univ.jfn.ac.lk', '', 'Mon-fri: 8 AM - 4 PM', '2024-10-18 16:05:38'),
(21, 'Dr. S. Shriparen', 'Lecturer', 'Room 8', 'shriparens@univ.jfn.ac.lk', '', 'Mon-fri: 8 AM - 4 PM', '2024-10-18 16:06:11'),
(22, 'Dr. T. Kokul', 'Lecturer', 'Room 9', 'kokul@univ.jfn.ac.lk', '', 'Mon-fri: 8 AM - 4 PM', '2024-10-18 16:06:48'),
(23, 'Dr. (Ms.) J. Samantha Tharani', 'Lecturer', 'Room 10', 'samantha@univ.jfn.ac.lk', '', 'Mon-fri: 8 AM - 4 PM', '2024-10-18 16:07:32'),
(24, 'Dr. (Ms.) R. Nirthika', 'Lecturer', 'Room 11', 'nirthika@univ.jfn.ac.lk', '', 'Mon-fri: 8 AM - 4 PM', '2024-10-18 16:07:57'),
(25, 'Dr. S. Mahesan', 'Lecturer', 'Room 12', 'mahesans@univ.jfn.ac.lk', '', 'Mon-fri: 8 AM - 4 PM', '2024-10-18 16:08:35'),
(26, 'Mr. V. Visithan', 'Academic Support Staff', 'Room 13', 'visithan@univ.jfn.ac.lk', '', 'Mon-fri: 9 AM - 3 PM', '2024-10-18 16:10:09'),
(27, 'Mr. T. Sugirthan', 'Academic Support Staff', 'Room 13', 'tsugirthan@univ.jfn.ac.lk', '', 'Mon-fri: 9 AM - 3 PM', '2024-10-18 16:10:39'),
(28, 'Ms. G. Thayani', 'Academic Support Staff', 'Room 14', 'thayani0521@gmail.com', '', 'Mon-fri: 8 AM - 4 PM', '2024-10-18 16:11:37'),
(29, 'Mrs. B. Arani', 'Academic Support Staff', 'Room 14', 'aranivithi@gmail.com', '', 'Mon-fri: 8 AM - 4 PM', '2024-10-18 16:11:58'),
(30, 'Ms. K. Kamshika', 'Academic Support Staff', 'Room 14', 'kamshikak@gmail.com', '', 'Mon-fri: 8 AM - 4 PM', '2024-10-18 16:12:20'),
(31, 'Ms. V. Niruththana', 'Academic Support Staff', 'Room 14', 'niruththana00@gmail.com', '', 'Mon-fri: 8 AM - 4 PM', '2024-10-18 16:12:51'),
(32, 'Mr. Y. Hajanthan', 'Administrative Staff', 'Room 15', 'yhajanthan@univ.jfn.ac.lk', '', 'Mon-fri: 8 AM - 4 PM', '2024-10-18 16:13:28'),
(33, 'Mr. N. Thileepan', 'Administrative Staff', 'Room 15', 'nthileepan@univ.jfn.ac.lk', '', 'Mon-fri: 8 AM - 4 PM', '2024-10-18 16:13:47'),
(94, 'sfatt', 'Lecturer', NULL, 'staff@gmail.com', NULL, NULL, '2024-10-29 13:01:57'),
(95, 'staff2', 'Academic Support Staff', NULL, 'staff2@gmail.com', NULL, NULL, '2024-10-29 13:14:26');

-- --------------------------------------------------------

--
-- Table structure for table `staff_availability`
--

CREATE TABLE `staff_availability` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `day_of_week` varchar(20) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `available` tinyint(1) DEFAULT 1,
  `status` varchar(50) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_availability`
--

INSERT INTO `staff_availability` (`id`, `staff_id`, `day_of_week`, `start_time`, `end_time`, `available`, `status`) VALUES
(30, 14, '', '22:07:00', '23:07:00', 1, 'pending'),
(31, 14, '', '22:08:00', '12:08:00', 1, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `staff_categories`
--

CREATE TABLE `staff_categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_categories`
--

INSERT INTO `staff_categories` (`id`, `category_name`) VALUES
(3, 'Administrative Staff'),
(4, 'Lecturers'),
(2, 'Teaching Assistants');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `student_number` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `email`, `user_id`, `student_number`) VALUES
(1, 'student', 'student@gmail.com', 112, ''),
(2, 'student2', 'student2@gmail.com', 114, ''),
(3, 'student3', 'student3@gmail.com', 116, '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Staff','Student','Admin') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `role`, `created_at`) VALUES
(112, 'student', 'student@gmail.com', '$2y$10$JRf.sLH0oYz7.MVMOAmxb.588r77ShoJxT2tByM/txY6HWvzCEwOi', 'Student', '2024-10-29 13:00:49'),
(113, 'sfatt', 'staff@gmail.com', '$2y$10$wehJG7B3pUgxrPPPu1amvOwie.kwM8C9iG92SBvorjwF61imSfyw.', 'Staff', '2024-10-29 13:01:57'),
(114, 'student2', 'student2@gmail.com', '$2y$10$o.3Bw6TLYIsGzbwxlqgkdeFrJXKVIMykObb2tpSZ5GjkXBe9vQFNW', 'Student', '2024-10-29 13:10:03'),
(115, 'staff2', 'staff2@gmail.com', '$2y$10$xkSY3jRzbBdUcSBHh6nxveuVNOffqEOQVPLg5sm1utTxY4EWGriDC', 'Staff', '2024-10-29 13:14:26'),
(116, 'student3', 'student3@gmail.com', '$2y$10$5fiHNiBW94deFmkw/nzvcuauOIW71mP6JnC0hTFi9yt3W4g/1j58O', 'Student', '2024-10-29 13:16:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointment_history`
--
ALTER TABLE `appointment_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news_updates`
--
ALTER TABLE `news_updates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `staff_availability`
--
ALTER TABLE `staff_availability`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_staff_id` (`staff_id`);

--
-- Indexes for table `staff_categories`
--
ALTER TABLE `staff_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `appointment_history`
--
ALTER TABLE `appointment_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `news_updates`
--
ALTER TABLE `news_updates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `staff_availability`
--
ALTER TABLE `staff_availability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `staff_categories`
--
ALTER TABLE `staff_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `staff_availability`
--
ALTER TABLE `staff_availability`
  ADD CONSTRAINT `fk_staff_id` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
