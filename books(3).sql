-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2015 at 06:43 PM
-- Server version: 5.6.11
-- PHP Version: 5.5.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `books`
--
CREATE DATABASE IF NOT EXISTS `books` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `books`;

-- --------------------------------------------------------

--
-- Table structure for table `confirmation`
--

CREATE TABLE IF NOT EXISTS `confirmation` (
  `OTP` varchar(32) NOT NULL,
  `Email` varchar(40) DEFAULT NULL,
  `Password` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`OTP`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE IF NOT EXISTS `login` (
  `Username` varchar(30) DEFAULT NULL,
  `Password` varchar(32) NOT NULL,
  UNIQUE KEY `Username` (`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`Username`, `Password`) VALUES
('sahib12', 'e90c6647830e603b4e761311d05238db'),
('harman', 'e90c6647830e603b4e761311d05238db'),
('singh213', 'e90c6647830e603b4e761311d05238db');

-- --------------------------------------------------------

--
-- Table structure for table `master`
--

CREATE TABLE IF NOT EXISTS `master` (
  `Username` varchar(30) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `ContactNo` varchar(12) DEFAULT NULL,
  `Qualification` varchar(50) DEFAULT NULL,
  `Profession` varchar(30) DEFAULT NULL,
  `Link_Photo` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`Username`),
  UNIQUE KEY `u_EMail` (`Email`),
  UNIQUE KEY `u_ContactNo` (`ContactNo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `master`
--

INSERT INTO `master` (`Username`, `Name`, `Email`, `ContactNo`, `Qualification`, `Profession`, `Link_Photo`) VALUES
('harman', 'Harman', 'harmandeepsinghkalsi9@gmial.com', '7894562130', NULL, NULL, 0),
('sahib12', 'Sahibpreet Singh', 'sahibpreetsingh94@gmail.com', '9888518432', 'pursuing BTech', 'Student', 0),
('singh213', 'Assddf', 'singh213@gmail.com', '7845632100', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `ID` int(10) NOT NULL,
  `Title` varchar(75) DEFAULT NULL,
  `Subject` varchar(30) DEFAULT NULL,
  `Author` varchar(50) DEFAULT NULL,
  `Edition` varchar(3) DEFAULT NULL,
  `Original_Price` int(4) DEFAULT NULL,
  `Selling_Price` int(4) DEFAULT NULL,
  `Photo` tinyint(1) DEFAULT NULL,
  `Username` varchar(30) DEFAULT NULL,
  `dateofpost` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sold` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `fk_Usename` (`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`ID`, `Title`, `Subject`, `Author`, `Edition`, `Original_Price`, `Selling_Price`, `Photo`, `Username`, `dateofpost`, `sold`) VALUES
(0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2015-01-05 13:02:57', 0),
(3, 'Book Test', 'Test', 'Kalsi Saab', '2', 512, 250, 1, 'harman', '2015-01-11 22:58:09', 1),
(4, 'Book Test', 'Test', 'Kalsi Saab', '2', 512, 250, 1, 'harman', '2015-01-11 22:57:55', 1),
(5, 'Computer Architecture', 'Computer', 'Morris Mano', '2', 512, 250, 1, 'sahib12', '2015-01-13 11:14:17', 0),
(6, 'Computer Architecture', 'Computer', 'Morris Mano', '4', 512, 250, 1, 'sahib12', '2015-01-13 18:52:20', 1);

-- --------------------------------------------------------

--
-- Table structure for table `temp`
--

CREATE TABLE IF NOT EXISTS `temp` (
  `Username` varchar(30) DEFAULT NULL,
  `Name` varchar(30) NOT NULL,
  `Email` varchar(40) NOT NULL,
  `ContactNo` varchar(12) DEFAULT NULL,
  `OTP` varchar(32) DEFAULT NULL,
  KEY `Email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `fk_Username` FOREIGN KEY (`Username`) REFERENCES `master` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_Usename` FOREIGN KEY (`Username`) REFERENCES `master` (`Username`);

--
-- Constraints for table `temp`
--
ALTER TABLE `temp`
  ADD CONSTRAINT `fk_Email` FOREIGN KEY (`Email`) REFERENCES `confirmation` (`Email`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
