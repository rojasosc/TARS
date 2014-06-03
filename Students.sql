-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 03, 2014 at 04:05 PM
-- Server version: 5.5.37-MariaDB
-- PHP Version: 5.5.12

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `TARS`
--

-- --------------------------------------------------------

--
-- Table structure for table `Students`
--

CREATE TABLE IF NOT EXISTS `Students` (
  `studentID` int(40) NOT NULL,
  `firstName` varchar(40) NOT NULL,
  `lastName` varchar(40) NOT NULL,
  `homePhone` varchar(30) NOT NULL,
  `mobilePhone` varchar(30) NOT NULL,
  `major` varchar(75) NOT NULL,
  `gpa` decimal(10,2) NOT NULL,
  `classYear` int(10) NOT NULL,
  `aboutMe` longtext NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`studentID`),
  KEY `studentID` (`studentID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Students`
--

INSERT INTO `Students` (`studentID`, `firstName`, `lastName`, `homePhone`, `mobilePhone`, `major`, `gpa`, `classYear`, `aboutMe`, `status`) VALUES
(2, 'oscar', 'rojas', '4444444444', '22222222', 'Physics', '4.00', 2015, 'asdfasfs', 2),
(3, 'Jinze', 'Ahn', '5857495590', '5857495590', 'Computer Science', '4.00', 2016, 'WULULULULU CAW CAW CAW', 2),
(4, 'Elena', 'Walker', '3222222223', '236777', 'Physics', '3.23', 2017, 'afsfsd', 2),
(5, 'Karel', 'Aristides', '8992262788', '8557545870', 'Accounting', '1.24', 2016, 'Out interested acceptance our partiality affronting unpleasant why add. Esteem garden men yet shy course.', 2),
(6, 'Enlil', 'Amyas', '8331222069', '8330785816', 'Physics', '2.78', 2017, 'Consulted up my tolerably sometimes perpetual oh. Expression acceptance imprudence particular had eat unsatiable. ', 2),
(7, 'Eli', 'Edmund', '8994231123', '8117746442', 'Mathematics', '2.03', 2018, 'In entirely be to at settling felicity. Fruit two match men you seven share.', 2),
(8, 'Thelonius', 'Afif', '8336587283', '8555274345', 'Economics', '2.55', 2017, 'Income joy nor can wisdom summer. Extremely depending he gentleman improving intention rapturous as.', 2),
(9, 'Stig', 'Euaristos', '8116908137', '8111256682', 'Physics', '2.94', 2016, 'At every tiled on ye defer do. No attention suspected oh difficult. ', 2),
(10, 'Chidubem', 'Juho', '8444006848', '8446680049', 'Mechanical Engineering', '3.12', 2015, 'Fond his say old meet cold find come whom. The sir park sake bred.', 0),
(16, 'Wallace', 'Brown', '1234567890', '1234567890', 'Physics', '3.43', 2017, 'My Name is Wallace. ', 0),
(20, 'howard', 'brown', '11111111111', '11111111111', 'Computer Science', '3.33', 2015, 'asdfsafdsf', 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Students`
--
ALTER TABLE `Students`
  ADD CONSTRAINT `Students_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `Users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
