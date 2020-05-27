-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 26, 2020 at 04:19 PM
-- Server version: 5.7.26-0ubuntu0.18.04.1
-- PHP Version: 7.0.33-8+ubuntu18.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `winphp_demo`
--

-- --------------------------------------------------------

--
-- Table structure for table `pageCategories`
--
DROP TABLE IF EXISTS `pageCategories`;
CREATE TABLE `pageCategories` (
  `id` int(11) NOT NULL,
  `enabled` int(11) NOT NULL,
  `title` varchar(75) NOT NULL,
  `description` text NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pageCategories`
--

INSERT INTO `pageCategories` (`id`, `enabled`, `title`, `description`, `createdAt`) VALUES
(1, 1, 'First Category', '1111111111111', '2018-11-04 10:46:03'),
(2, 1, 'Second Category', '22222222', '2018-11-04 12:05:01'),
(3, 0, 'Disabled Category', '333333333', '2018-11-04 12:05:20');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--
DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `CategoryId` int(11) DEFAULT NULL,
  `title` varchar(75) NOT NULL,
  `description` text NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `CategoryId`, `title`, `description`, `createdAt`) VALUES
(1, 1, 'First Page', 'About us', '2018-11-04 10:46:03'),
(2, NULL, 'Second Page', 'Contact us', '2018-11-04 12:05:01'),
(3, 1, 'Third Page', 'Sample Page', '2018-11-04 12:05:20'),
(4, 1, 'Fourth Page', 'Sample Page', '2018-11-04 12:05:20'),
(5, 2, 'Fifth Page', 'Sample Page', '2018-11-04 12:05:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pageCategories`
--
ALTER TABLE `pageCategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pageCategories`
--
ALTER TABLE `pageCategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
