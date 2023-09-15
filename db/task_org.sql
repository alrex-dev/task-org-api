-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 15, 2023 at 04:45 PM
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

-- --------------------------------------------------------

--
-- Table structure for table `access_note_groups`
--

CREATE TABLE `access_note_groups` (
  `id` int(11) NOT NULL,
  `group_name` varchar(100) DEFAULT NULL,
  `proj_id` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `node_task_pool`
--

CREATE TABLE `node_task_pool` (
  `task_id` int(11) NOT NULL,
  `task` varchar(50) NOT NULL,
  `task_data` text NOT NULL
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
-- Indexes for table `node_task_pool`
--
ALTER TABLE `node_task_pool`
  ADD PRIMARY KEY (`task_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `access_note_groups`
--
ALTER TABLE `access_note_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `current_session`
--
ALTER TABLE `current_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `node_task_pool`
--
ALTER TABLE `node_task_pool`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timelogs`
--
ALTER TABLE `timelogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
