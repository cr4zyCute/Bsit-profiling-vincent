-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2024 at 06:01 AM
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
-- Database: `bsit_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `adminname` varchar(255) NOT NULL,
  `admin_email` varchar(255) NOT NULL,
  `admin_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `adminname`, `admin_email`, `admin_password`) VALUES
(1, 'admin', 'admin@gmail.com', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `approvals`
--

CREATE TABLE `approvals` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `picture` blob DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `approvals`
--

INSERT INTO `approvals` (`id`, `student_id`, `status`, `picture`, `created_at`) VALUES
(6, 1018, 'approved', NULL, '2024-11-23 21:21:49'),
(7, 1021, 'approved', NULL, '2024-11-23 21:36:09'),
(8, 1020, 'approved', NULL, '2024-11-23 21:37:50'),
(9, 1022, 'approved', 0x2e2e2f75706c6f6164732f617070726f76616c5f70696374757265732f38663336313861312d393261362d343161332d396262652d6635333637343864373963392e6a666966, '2024-11-23 21:59:49'),
(10, 1023, 'rejected', NULL, '2024-11-23 22:13:28'),
(11, 1023, 'approved', 0x2e2e2f75706c6f6164732f617070726f76616c5f70696374757265732f38663336313861312d393261362d343161332d396262652d6635333637343864373963392e6a666966, '2024-11-23 22:18:45'),
(12, 1024, 'approved', 0x2e2e2f75706c6f6164732f617070726f76616c5f70696374757265732f38663336313861312d393261362d343161332d396262652d663533363734386437396339202d20436f70792e6a666966, '2024-11-23 23:07:10');

-- --------------------------------------------------------

--
-- Table structure for table `logincredentials`
--

CREATE TABLE `logincredentials` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logincredentials`
--

INSERT INTO `logincredentials` (`id`, `student_id`, `email`, `password`) VALUES
(25, 1018, '7ebelen@gmail.com', '123'),
(27, 1020, 'crzycute4@gmail.com', '123'),
(28, 1021, 'sample@gmail.com', '123'),
(29, 1022, 'cyrill@gmail.com', '123'),
(30, 1023, 'jameswaren@gmail.com', '123'),
(31, 1024, 'sweet_diaacosta@gmail.com', '123');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` enum('Male','Female','Others') NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` text NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `firstname`, `middlename`, `lastname`, `age`, `gender`, `phone`, `address`, `image`) VALUES
(1018, 'belen', 'Magbannua', 'Acosta', 20, 'Female', '31896525123', 'Camanggahan Buanoy Balamban Cebu', '6fe16827-d53b-4f07-9b9b-bbb41d734d67.jfif'),
(1020, 'Nikki Sixx ', 'Rabanes', 'Acosta', 23, 'Female', '31896525123', 'Camanggahan Buanoy Balamban Cebu', '8deb1b36-da90-4877-8f9d-e5086e193eb2.jfif'),
(1021, 'sample', 'sample', 'sample', 23, 'Female', '31896525123', 'Camanggahan Buanoy Balamban Cebu', '7f36931b-1998-4e83-b38a-4b644edc495d.jfif'),
(1022, 'Cyrill', 'Rabanes', 'Acosta', 23, 'Female', '31896525123', 'Camanggahan Buanoy Balamban Cebu', '57b40218-ab54-49b7-a617-3f0371aef716.jfif'),
(1023, 'gwapo', 'Rabanes', 'sample', 23, 'Female', '31896525123', 'Camanggahan Buanoy Balamban Cebu', '57b40218-ab54-49b7-a617-3f0371aef716.jfif'),
(1024, 'first', 'sample', 'man', 23, 'Female', '123123', 'Camanggahan Buanoy Balamban Cebu', '0c5f8239-e9d7-4b92-a87e-9344b9c7020c.jfif');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `approvals`
--
ALTER TABLE `approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `logincredentials`
--
ALTER TABLE `logincredentials`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `approvals`
--
ALTER TABLE `approvals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `logincredentials`
--
ALTER TABLE `logincredentials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1025;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `approvals`
--
ALTER TABLE `approvals`
  ADD CONSTRAINT `approvals_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`);

--
-- Constraints for table `logincredentials`
--
ALTER TABLE `logincredentials`
  ADD CONSTRAINT `logincredentials_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
