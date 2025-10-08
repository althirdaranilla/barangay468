-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 06, 2025 at 06:30 PM
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
-- Database: `barangay468_db`
--
CREATE DATABASE IF NOT EXISTS `barangay468_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `barangay468_db`;

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `position` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `first_name`, `last_name`, `email`, `position`, `password`, `created_at`, `status`) VALUES
(1, 'Edrian', 'Valdez', 'admin@barangay468.gov.ph', 'Barangay Captain', '$2y$10$m8ilDb.50cCnkJKfygQtv.wziFdYsz.wMZr3AqTTvMlGKguVKfIhG', '2025-09-03 20:29:55', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `certificate_requests`
--

CREATE TABLE `certificate_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `cellphone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `household_number` varchar(50) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `certificate_type` enum('Residency Certificate','Indigency Certificate','Employment Certificate') NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `date_requested` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certificate_requests`
--

INSERT INTO `certificate_requests` (`id`, `user_id`, `first_name`, `middle_name`, `last_name`, `email`, `cellphone`, `address`, `household_number`, `purpose`, `certificate_type`, `status`, `date_requested`) VALUES
(1, 2, 'Althird Cherson', 'Tranquilan', 'Aranilla', 'althirdcherson@gmail.com', '09660671237', 'P.Campa, Manila', '1234', 'Sample', 'Indigency Certificate', 'Pending', '2025-10-06 15:07:47');

-- --------------------------------------------------------

--
-- Table structure for table `clearance_requests`
--

CREATE TABLE `clearance_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `cellphone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `household_number` varchar(50) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `date_requested` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clearance_requests`
--

INSERT INTO `clearance_requests` (`id`, `user_id`, `first_name`, `middle_name`, `last_name`, `email`, `cellphone`, `address`, `household_number`, `purpose`, `status`, `date_requested`) VALUES
(4, 2, 'Sample', 'Data', 'Lang', 'lang@gmail.com', '2179637812', 'sample manila', '1000', 'SAMPLE', 'Pending', '2025-10-06 16:11:59');

-- --------------------------------------------------------

--
-- Table structure for table `permit_requests`
--

CREATE TABLE `permit_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `cellphone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `household_number` varchar(50) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `permit_type` enum('Business Permit','Building Permit','Event Permit') NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `date_requested` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permit_requests`
--

INSERT INTO `permit_requests` (`id`, `user_id`, `first_name`, `middle_name`, `last_name`, `email`, `cellphone`, `address`, `household_number`, `purpose`, `permit_type`, `status`, `date_requested`) VALUES
(1, 2, 'Althird Cherson', 'Tranquilan', 'Aranilla', 'althirdcherson@gmail.com', '09660671237', 'P.Campa, Manila', '1234', 'Sample', 'Business Permit', 'Pending', '2025-10-06 15:07:29'),
(2, 2, 'Edrian', 'Udtohan', 'Valdez', 'valdez@gmail.com', '12345678901', 'P.Campa, Manila', '1001', 'Sample', 'Event Permit', 'Pending', '2025-10-06 15:58:58');

-- --------------------------------------------------------

--
-- Table structure for table `residents`
--

CREATE TABLE `residents` (
  `resident_id` varchar(20) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `age` int(11) NOT NULL CHECK (`age` >= 0 and `age` <= 150),
  `civil_status` enum('Single','Married','Widowed','Divorced','Separated') NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `residents`
--

INSERT INTO `residents` (`resident_id`, `fullname`, `age`, `civil_status`, `gender`, `contact_number`, `email`, `created_at`, `updated_at`) VALUES
('748-204-0001', 'Kobe Tayco', 18, 'Single', 'Male', '0912-345-4343', 'tayco@gmail.com', '2025-09-04 15:20:45', '2025-09-04 15:20:45'),
('748-204-0002', 'Edrian Valdez', 20, 'Single', 'Male', '0912-345-4343', 'edrian@gmail.com', '2025-09-04 15:20:45', '2025-09-04 15:20:45'),
('748-204-0003', 'Athdr Aranilla', 60, 'Single', 'Male', '0912-345-4343', 'aranilla@gmail.com', '2025-09-04 15:20:45', '2025-09-04 15:20:45'),
('748-204-0004', 'Christian Somera', 80, 'Single', 'Male', '0912-345-4343', 'somera@gmail.com', '2025-09-04 15:20:45', '2025-09-04 15:20:45'),
('748-204-0005', 'Mark de Guzman', 60, 'Single', 'Male', '0912-345-4343', 'mark@gmail.com', '2025-09-04 15:20:45', '2025-09-04 15:20:45');

-- --------------------------------------------------------

--
-- Table structure for table `residents_users`
--

CREATE TABLE `residents_users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `residents_users`
--

INSERT INTO `residents_users` (`id`, `first_name`, `last_name`, `email`, `password`, `created_at`, `status`) VALUES
(2, 'Althird Cherson', 'Aranila', 'althirdcherson@gmail.com', '$2y$10$DE3CgxIFopMEvNZtHBAbpOFigwot2j494qIOqcyrz1.cRdPtKHULu', '2025-10-06 22:13:39', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `certificate_requests`
--
ALTER TABLE `certificate_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `clearance_requests`
--
ALTER TABLE `clearance_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `permit_requests`
--
ALTER TABLE `permit_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `residents`
--
ALTER TABLE `residents`
  ADD PRIMARY KEY (`resident_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_fullname` (`fullname`);

--
-- Indexes for table `residents_users`
--
ALTER TABLE `residents_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `certificate_requests`
--
ALTER TABLE `certificate_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `clearance_requests`
--
ALTER TABLE `clearance_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `permit_requests`
--
ALTER TABLE `permit_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `residents_users`
--
ALTER TABLE `residents_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `certificate_requests`
--
ALTER TABLE `certificate_requests`
  ADD CONSTRAINT `certificate_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `residents_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `clearance_requests`
--
ALTER TABLE `clearance_requests`
  ADD CONSTRAINT `clearance_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `residents_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `permit_requests`
--
ALTER TABLE `permit_requests`
  ADD CONSTRAINT `permit_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `residents_users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
