-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 21, 2016 at 12:05 PM
-- Server version: 5.6.15-log
-- PHP Version: 5.5.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `personal_details`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
-- Creation: Jun 20, 2016 at 03:59 PM
-- Last update: Jun 20, 2016 at 04:54 PM
--

CREATE TABLE IF NOT EXISTS `users` (
  `firstname` varchar(20) NOT NULL,
  `surname` varchar(40) NOT NULL,
  `email` varchar(60) NOT NULL,
  `password` varchar(200) NOT NULL,
  `age` int(3) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`firstname`, `surname`, `email`, `password`, `age`, `gender`, `ID`) VALUES
('Edward', 'Stansfield', 'ETstansfield@hotmail.com', 'EdA5NuSo77rxk', 21, 'Male', 1),
('Dean', 'Venture', 'DeanyV@venturehomenews.com', 'DeAGMxYPTdjUA', 18, 'Male', 2),
('Katy', 'Smith', 'KS@google.com', 'Ka6lCp7pRH9j2', 35, 'Female', 3),
('Streetlamp', 'LeMoose', 'Sl@google.com', 'StlKPsk3Uoci6', 50, 'Male', 4),
('Francine', 'Smith', 'fm@example.net', 'Fr4X0tTlgtSBg', 42, 'Female', 8),
('Mara', 'Jade', 'MJ@empire.holonet', 'Mam/FKKJ9cU2w', 32, 'Female', 9),
('Eric', 'Cartman', 'ec@southp.org', 'ErqHtklN0wNDY', 19, 'Male', 10);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
