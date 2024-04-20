-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 15, 2024 at 03:38 PM
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
-- Database: `mentorship`
--

-- --------------------------------------------------------

--
-- Table structure for table `enquiry`
--

CREATE TABLE `enquiry` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(30) NOT NULL,
  `message` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enquiry`
--

INSERT INTO `enquiry` (`id`, `name`, `email`, `message`, `date`) VALUES
(8, 'Keita Nara 2', 'keitanara11@gmail.com', 'Report and problem here or contact for more information here', '2024-01-10 13:13:05');

-- --------------------------------------------------------

--
-- Table structure for table `mentee`
--

CREATE TABLE `mentee` (
  `id` bigint(20) NOT NULL,
  `mentee_id` bigint(20) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(8) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `bio` text NOT NULL,
  `profile_pic` varchar(245) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `pronouns` varchar(50) NOT NULL,
  `age` bigint(20) NOT NULL,
  `college` text NOT NULL,
  `ed_field` text NOT NULL,
  `email` varchar(40) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `mentorship_field` text NOT NULL,
  `matched_mentor` bigint(20) NOT NULL,
  `matching_history` bigint(20) NOT NULL,
  `change_req` varchar(10) NOT NULL,
  `change_req_date` date DEFAULT NULL,
  `cancel_change_req_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mentee`
--

INSERT INTO `mentee` (`id`, `mentee_id`, `username`, `password`, `full_name`, `bio`, `profile_pic`, `gender`, `pronouns`, `age`, `college`, `ed_field`, `email`, `date`, `mentorship_field`, `matched_mentor`, `matching_history`, `change_req`, `change_req_date`, `cancel_change_req_date`) VALUES
(22, 38616, 'sairaj_07', 'Saira@07', 'sairaj shinde', 'An aspiring mechanical engineering student. I am interested in research and development activities. ', 'images/WhatsApp Image 2024-01-05 at 19.53.33_82fc5891.jpg', 'Non - Binary', 'They/Them', 20, 'Viva Institute of Technology', 'B.E Mechanical Engineering', '21105059sairaj@viva-technology.org', '2024-02-07 15:20:06', 'Mechanical Engineering', 0, 0, '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mentor`
--

CREATE TABLE `mentor` (
  `id` bigint(20) NOT NULL,
  `mentor_id` bigint(20) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(8) NOT NULL,
  `Date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `full_name` varchar(100) NOT NULL,
  `bio` text NOT NULL,
  `email` varchar(40) NOT NULL,
  `profile_pic` varchar(245) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `pronouns` varchar(50) NOT NULL,
  `age` bigint(20) NOT NULL,
  `mentor_org` text NOT NULL,
  `ed_field` text NOT NULL,
  `work_field` text NOT NULL,
  `matched_mentee` bigint(20) NOT NULL,
  `matching_history` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `enquiry`
--
ALTER TABLE `enquiry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mentee`
--
ALTER TABLE `mentee`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`),
  ADD KEY `date` (`date`),
  ADD KEY `mentee_id` (`mentee_id`);

--
-- Indexes for table `mentor`
--
ALTER TABLE `mentor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `mentor_id` (`mentor_id`),
  ADD KEY `username` (`username`),
  ADD KEY `Date` (`Date`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `enquiry`
--
ALTER TABLE `enquiry`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `mentee`
--
ALTER TABLE `mentee`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `mentor`
--
ALTER TABLE `mentor`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
