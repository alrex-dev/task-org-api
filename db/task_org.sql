-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2023 at 06:46 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `task_org`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_notes`
--

CREATE TABLE `access_notes` (
  `id` int(11) NOT NULL,
  `note_label` varchar(100) DEFAULT NULL,
  `note_value` varchar(200) DEFAULT NULL,
  `group_id` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `access_notes`
--

INSERT INTO `access_notes` (`id`, `note_label`, `note_value`, `group_id`) VALUES
(20, 'link', 'https://www.wordpress.com', 5),
(21, 'user', 'admins', 5),
(22, 'pass:', 'SA#$##$DSDS', 5),
(23, 'db name', 'sea_soul', 6),
(24, 'user', 'root', 6),
(25, 'pass', 'LS)*(AS&JASAS', 6),
(26, 'link', 'https://www.ventraip.com.au', 7),
(37, 'user', 'admin', 16),
(38, 'link', 'https://www.watersolutionstas.com.au/wp-admin', 17),
(39, 'pass', 'SD*#8#@)@#!', 16);

-- --------------------------------------------------------

--
-- Table structure for table `access_note_groups`
--

CREATE TABLE `access_note_groups` (
  `id` int(11) NOT NULL,
  `group_name` varchar(100) DEFAULT NULL,
  `proj_id` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `access_note_groups`
--

INSERT INTO `access_note_groups` (`id`, `group_name`, `proj_id`) VALUES
(5, 'Admin', '98c328c8db7bc98255a7ea76c8db413c'),
(6, 'MySQL', '98c328c8db7bc98255a7ea76c8db413c'),
(7, 'Ventra IP', '98c328c8db7bc98255a7ea76c8db413c'),
(16, 'Admin', 'c13a6e201bde5730139bad4f3274ba2c'),
(17, 'Admin', '2e4414e78bd84b470881abfaa83ec37a');

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` int(11) NOT NULL,
  `act_date` date DEFAULT NULL,
  `act_ref_log` varchar(17) DEFAULT NULL,
  `act_desc` text DEFAULT NULL,
  `proj_id` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `act_date`, `act_ref_log`, `act_desc`, `proj_id`) VALUES
(1, '2023-02-10', '202302100901001', 'Changed menu item stylessssss', '98c328c8db7bc98255a7ea76c8db413c'),
(2, '2023-02-10', '202302100901001', 'Removed social icons', '98c328c8db7bc98255a7ea76c8db413c'),
(3, '2023-02-10', '202302101052001', 'Added phone number in the footer', '98c328c8db7bc98255a7ea76c8db413c'),
(4, '2023-02-11', '202302110700001', 'Backed up website files and database', '98c328c8db7bc98255a7ea76c8db413c'),
(5, '2023-02-11', '202302111401001', 'Updated wordpress from 6.0 to 6.1', '98c328c8db7bc98255a7ea76c8db413c'),
(6, '2023-02-11', '202302111540001', 'Updated the following plugins:\r\n- Akismith\r\n- Contact Form 7\r\n- Password Protected\r\n- Slider Revolution', '98c328c8db7bc98255a7ea76c8db413c'),
(7, '2023-02-12', '202302120905001', 'Checked client\'s email', '98c328c8db7bc98255a7ea76c8db413c'),
(8, '2023-02-12', '202302121330001', 'Configured FTP', '98c328c8db7bc98255a7ea76c8db413c'),
(9, '2023-02-12', NULL, 'Cropped photos', '98c328c8db7bc98255a7ea76c8db413c'),
(11, '2023-02-22', '', 'Something I did today', '98c328c8db7bc98255a7ea76c8db413c'),
(12, '2023-02-22', '2023022221155384', 'So another thing I did today', '98c328c8db7bc98255a7ea76c8db413c'),
(17, '2023-02-25', '2023022511320572', 'Setting up website', 'c13a6e201bde5730139bad4f3274ba2c'),
(18, '2023-02-25', '2023022511320572', 'Setting up theme', 'c13a6e201bde5730139bad4f3274ba2c'),
(19, '2023-02-25', '2023022513590761', 'Doing something for the website', '2e4414e78bd84b470881abfaa83ec37a'),
(20, '2023-02-25', '2023022513590761', 'And yeah another thing to do', '2e4414e78bd84b470881abfaa83ec37a'),
(21, '2023-02-25', '', 'Testing 3', 'c13a6e201bde5730139bad4f3274ba2c'),
(22, '2023-02-26', '', 'Another works today', 'c13a6e201bde5730139bad4f3274ba2c'),
(23, '2023-02-25', '', 'Testing 4', 'c13a6e201bde5730139bad4f3274ba2c'),
(24, '2023-02-26', '2023022601412344', 'Doing some work again', 'c13a6e201bde5730139bad4f3274ba2c');

-- --------------------------------------------------------

--
-- Table structure for table `current_session`
--

CREATE TABLE `current_session` (
  `id` int(11) NOT NULL,
  `sess_id` varchar(17) NOT NULL,
  `sess_date` date NOT NULL,
  `sess_time` varchar(5) NOT NULL,
  `proj_id` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `proj_id` varchar(32) NOT NULL,
  `proj_name` varchar(200) DEFAULT NULL,
  `proj_desc` text DEFAULT NULL,
  `proj_storage` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `proj_id`, `proj_name`, `proj_desc`, `proj_storage`) VALUES
(1, '98c328c8db7bc98255a7ea76c8db413c', 'Sea Soul Studios', 'This is just a test description about the Project. The quick brown fox jumps over the lazy dog. Lorem ipsum.', NULL),
(7, '2e4414e78bd84b470881abfaa83ec37a', 'Water Solutions Tas', 'Testing', NULL),
(8, 'c13a6e201bde5730139bad4f3274ba2c', 'Liquid Promotions', 'Test description', NULL),
(9, '40d5e67a17653e952907783794a83bb7', 'Tas Tow & Recovery', 'Another description', NULL),
(10, '43425f499d1384e237f815e79ba0a9ce', 'All About Time', 'Yeah almost done with this task organizer', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `timelogs`
--

CREATE TABLE `timelogs` (
  `id` int(11) NOT NULL,
  `log_id` varchar(17) DEFAULT NULL,
  `log_date` date DEFAULT NULL,
  `time_from` varchar(5) DEFAULT NULL,
  `time_to` varchar(5) DEFAULT NULL,
  `proj_id` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timelogs`
--

INSERT INTO `timelogs` (`id`, `log_id`, `log_date`, `time_from`, `time_to`, `proj_id`) VALUES
(1, '202302100901001', '2023-02-10', '09:01', '10:15', '98c328c8db7bc98255a7ea76c8db413c'),
(2, '202302101052001', '2023-02-10', '10:52', '11:42', '98c328c8db7bc98255a7ea76c8db413c'),
(3, '202302101301001', '2023-02-10', '13:01', '14:40', '98c328c8db7bc98255a7ea76c8db413c'),
(4, '202302102030001', '2023-02-10', '20:30', '00:00', '98c328c8db7bc98255a7ea76c8db413c'),
(5, '202302110700001', '2023-02-11', '07:00', '08:49', '98c328c8db7bc98255a7ea76c8db413c'),
(6, '202302111401001', '2023-02-11', '14:01', '15:35', '98c328c8db7bc98255a7ea76c8db413c'),
(7, '202302111540001', '2023-02-11', '15:40', '16:20', '98c328c8db7bc98255a7ea76c8db413c'),
(8, '202302120905001', '2023-02-12', '09:05', '11:45', '98c328c8db7bc98255a7ea76c8db413c'),
(9, '202302121330001', '2023-02-12', '13:30', '14:25', '98c328c8db7bc98255a7ea76c8db413c'),
(10, '2023022117064056', '2023-02-21', '17:06', '17:08', '98c328c8db7bc98255a7ea76c8db413c'),
(12, '2023022117144697', '2023-02-21', '17:14', '17:15', '98c328c8db7bc98255a7ea76c8db413c'),
(13, '2023022221155384', '2023-02-22', '21:15', '21:16', '98c328c8db7bc98255a7ea76c8db413c'),
(15, '2023022419140116', '2023-02-24', '19:14', '19:15', '98c328c8db7bc98255a7ea76c8db413c'),
(18, '2023022511320572', '2023-02-25', '11:32', '11:33', 'c13a6e201bde5730139bad4f3274ba2c'),
(19, '2023022513590761', '2023-02-25', '13:59', '15:33', '2e4414e78bd84b470881abfaa83ec37a'),
(20, '2023022515361685', '2023-02-25', '15:36', '15:37', '2e4414e78bd84b470881abfaa83ec37a'),
(21, '2023022601412344', '2023-02-26', '01:41', '01:43', 'c13a6e201bde5730139bad4f3274ba2c');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_notes`
--
ALTER TABLE `access_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `access_note_groups`
--
ALTER TABLE `access_note_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `current_session`
--
ALTER TABLE `current_session`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timelogs`
--
ALTER TABLE `timelogs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_notes`
--
ALTER TABLE `access_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `access_note_groups`
--
ALTER TABLE `access_note_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `current_session`
--
ALTER TABLE `current_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `timelogs`
--
ALTER TABLE `timelogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
