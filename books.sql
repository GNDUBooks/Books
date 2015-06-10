-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2015 at 09:50 AM
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
-- Table structure for table `buyrequest`
--

CREATE TABLE IF NOT EXISTS `buyrequest` (
  `BookId` int(10) NOT NULL DEFAULT '0',
  `BuyerUser` varchar(30) NOT NULL DEFAULT '',
  `OfferedPrice` int(4) DEFAULT NULL,
  `DateOfOffer` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`BookId`,`BuyerUser`),
  KEY `BuyerUser` (`BuyerUser`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `confirmation`
--

CREATE TABLE IF NOT EXISTS `confirmation` (
  `OTP` varchar(32) NOT NULL,
  `Email` varchar(40) DEFAULT NULL,
  `Username` varchar(30) NOT NULL,
  PRIMARY KEY (`OTP`),
  UNIQUE KEY `Username` (`Username`)
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
('harman', 'e90c6647830e603b4e761311d05238db');

-- --------------------------------------------------------

--
-- Table structure for table `master`
--

CREATE TABLE IF NOT EXISTS `master` (
  `Username` varchar(30) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `ContactNo` bigint(12) DEFAULT NULL,
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
('harman', 'Harmandeep', 'jsbhalla52@gmail.com', 9888518454, 'BTech123', 'engineer', 1),
('sahib12', 'Sahibpreet Singh', 'sahibpreetsingh94@gmail.com', 9888518432, 'BTech123', 'Engineer', 1);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `ID` int(10) NOT NULL,
  `Title` varchar(75) DEFAULT NULL,
  `Subject` varchar(40) DEFAULT NULL,
  `Author` varchar(50) DEFAULT NULL,
  `Edition` varchar(3) DEFAULT NULL,
  `Original_Price` int(4) DEFAULT NULL,
  `Selling_Price` int(4) DEFAULT NULL,
  `Username` varchar(30) DEFAULT NULL,
  `dateofpost` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sold` tinyint(1) NOT NULL DEFAULT '0',
  `NoReport` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `fk_Usename` (`Username`),
  KEY `fk_Subject` (`Subject`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`ID`, `Title`, `Subject`, `Author`, `Edition`, `Original_Price`, `Selling_Price`, `Username`, `dateofpost`, `sold`, `NoReport`) VALUES
(0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2015-01-05 07:32:57', 0, 0),
(1, 'Computer Architecture', 'Computer Science', 'Morris Mano', '4', 360, 120, 'sahib12', '2015-05-15 15:54:49', 0, 0),
(2, 'Computer Architecture', 'Computer Science', 'Morris Mano', '6', 90, 30, 'sahib12', '2015-05-15 15:55:46', 0, 0),
(3, 'Computer Architecture', 'Computer Science', 'Morris Mano', '6', 110, 30, 'sahib12', '2015-05-17 04:22:30', 0, 1),
(4, 'koi v', 'Computer Science', 'asd', '5', 310, 100, 'sahib12', '2015-05-15 15:56:25', 0, 0),
(6, 'cskacd', 'Computer Science', 'sad', '5', 500, 200, 'sahib12', '2015-05-15 15:57:55', 0, 0),
(7, 'cskacd', 'Computer Science', 'sad', '5', 500, 200, 'sahib12', '2015-05-15 15:58:24', 0, 0),
(9, 'cskacd', 'Computer Science', 'sad', '5', 500, 200, 'sahib12', '2015-05-15 15:58:24', 0, 0),
(10, 'cskacd', 'Computer Science', 'sad', '5', 500, 200, 'sahib12', '2015-05-15 15:58:24', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE IF NOT EXISTS `report` (
  `BookId` int(10) NOT NULL DEFAULT '0',
  `Username` varchar(30) NOT NULL DEFAULT '',
  `DateOfReport` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`BookId`,`Username`),
  KEY `Username` (`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`BookId`, `Username`, `DateOfReport`) VALUES
(3, 'harman', '2015-05-17 04:22:30');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE IF NOT EXISTS `subject` (
  `SubjectName` varchar(40) NOT NULL,
  UNIQUE KEY `SubjectName` (`SubjectName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`SubjectName`) VALUES
('Agriculture'),
('Architecture'),
('Arts'),
('Chemistry'),
('Commerce'),
('Computer Science'),
('Economics'),
('Engineering'),
('History'),
('Language'),
('Law'),
('Library Science'),
('Life Sciences'),
('Literature'),
('Management'),
('Mathematics'),
('Medicine and Health'),
('Other'),
('Philosophy and Psychology'),
('Physics'),
('Political Science'),
('Religion'),
('Science'),
('Social Sciences and Sociology');

-- --------------------------------------------------------

--
-- Table structure for table `temp`
--

CREATE TABLE IF NOT EXISTS `temp` (
  `Password` varchar(32) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `ContactNo` bigint(12) DEFAULT NULL,
  `OTP` varchar(32) DEFAULT NULL,
  UNIQUE KEY `ContactNo` (`ContactNo`),
  UNIQUE KEY `OTP` (`OTP`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `buyrequest`
--
ALTER TABLE `buyrequest`
  ADD CONSTRAINT `buyrequest_ibfk_1` FOREIGN KEY (`BookId`) REFERENCES `posts` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `buyrequest_ibfk_2` FOREIGN KEY (`BuyerUser`) REFERENCES `master` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `fk_Username` FOREIGN KEY (`Username`) REFERENCES `master` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_Subject` FOREIGN KEY (`Subject`) REFERENCES `subject` (`SubjectName`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Usename` FOREIGN KEY (`Username`) REFERENCES `master` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`BookId`) REFERENCES `posts` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `report_ibfk_2` FOREIGN KEY (`Username`) REFERENCES `master` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
