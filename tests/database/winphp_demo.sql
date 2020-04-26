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
-- Table structure for table `PageCategories`
--

CREATE TABLE `PageCategories` (
  `Id` int(11) NOT NULL,
  `Enabled` int(11) NOT NULL,
  `Title` varchar(75) NOT NULL,
  `Description` text NOT NULL,
  `CreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `PageCategories`
--

INSERT INTO `PageCategories` (`Id`, `Enabled`, `Title`, `Description`, `CreatedAt`) VALUES
(1, 1, 'First Category', '1111111111111', '2018-11-04 10:46:03'),
(2, 1, 'Second Category', '22222222', '2018-11-04 12:05:01'),
(3, 0, 'Disabled Category', '333333333', '2018-11-04 12:05:20');

-- --------------------------------------------------------

--
-- Table structure for table `Pages`
--

CREATE TABLE `Pages` (
  `Id` int(11) NOT NULL,
  `CategoryId` int(11) DEFAULT NULL,
  `Title` varchar(75) NOT NULL,
  `Description` text NOT NULL,
  `CreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Pages`
--

INSERT INTO `Pages` (`Id`, `CategoryId`, `Title`, `Description`, `CreatedAt`) VALUES
(1, NULL, 'First Page', 'About us', '2018-11-04 10:46:03'),
(2, NULL, 'Second Page', 'Contact us', '2018-11-04 12:05:01'),
(3, NULL, 'Third Page', 'Sample Page', '2018-11-04 12:05:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `PageCategories`
--
ALTER TABLE `PageCategories`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Pages`
--
ALTER TABLE `Pages`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `PageCategories`
--
ALTER TABLE `PageCategories`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `Pages`
--
ALTER TABLE `Pages`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
