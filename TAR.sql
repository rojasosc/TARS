-- phpMyAdmin SQL Dump
-- version 4.1.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 23, 2014 at 09:47 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `TAR`
--

-- --------------------------------------------------------

--
-- Table structure for table `Course`
--

CREATE TABLE IF NOT EXISTS `Course` (
  `CID` int(11) NOT NULL AUTO_INCREMENT,
  `ClassName` varchar(100) DEFAULT NULL,
  `ClassTitle` varchar(100) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `Term` int(11) NOT NULL,
  `Year` int(11) NOT NULL,
  PRIMARY KEY (`CID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;

--
-- Dumping data for table `Course`
--

INSERT INTO `Course` (`CID`, `ClassName`, `ClassTitle`, `description`, `Term`, `Year`) VALUES
(44, 'CSC-210', 'Web Programming', 'Short description', 1, 2011),
(45, 'CSC-210B', 'Web Programming', 'Short description', 2, 2011),
(46, 'CSC-172', 'Science of Data Structures', 'Short description', 1, 2012),
(47, 'CSC-171', 'Science of Programming', 'Short description', 2, 2012),
(48, 'CSC-173', 'Computation and Formal Systems', 'Short description', 2, 2012);

-- --------------------------------------------------------

--
-- Table structure for table `Student`
--

CREATE TABLE IF NOT EXISTS `Student` (
  `UID` int(11) DEFAULT NULL,
  `major` varchar(100) DEFAULT NULL,
  `GPA` varchar(5) DEFAULT NULL,
  `classYear` int(11) DEFAULT NULL,
  `about` varchar(1000) DEFAULT NULL,
  KEY `UID` (`UID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Student`
--

INSERT INTO `Student` (`UID`, `major`, `GPA`, `classYear`, `about`) VALUES
(3, 'Computer Science', '3.32', 2015, 'About me Section'),
(4, 'Computer Science', '3.1', 2015, 'Student'),
(5, 'Computer Science', '3.56', 2015, 'Student'),
(6, 'Computer Science', '3.97', 2017, 'Student'),
(7, 'Computer Science', '3.32', 2014, 'This is Skylers About me Section'),
(8, 'Computer Science', '3.1', 2018, 'Student'),
(9, 'Computer Science', '3.32', 2018, 'Student'),
(23, 'Computer Science', '3.87', 2015, 'asfdsass'),
(24, 'Computer Science', '3.45', 2014, 'afjldsjafllfskjdfdadfadd');

-- --------------------------------------------------------

--
-- Table structure for table `TA`
--

CREATE TABLE IF NOT EXISTS `TA` (
  `TID` int(11) NOT NULL AUTO_INCREMENT,
  `UID` int(11) DEFAULT NULL,
  `CID` int(11) DEFAULT NULL,
  `requirement` varchar(150) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `startTime` varchar(10) DEFAULT NULL,
  `endTime` varchar(10) DEFAULT NULL,
  `classRoom` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`TID`),
  KEY `CID` (`CID`),
  KEY `UID` (`UID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `TA`
--

INSERT INTO `TA` (`TID`, `UID`, `CID`, `requirement`, `type`, `startTime`, `endTime`, `classRoom`) VALUES
(3, 5, 48, 'CSC-173', 'Lecture TA', '3:00', '4:15', 'CSB-210'),
(4, 1, 48, 'CSC-173', 'Lecture TA', '3:00', '4:15', 'CSB-212'),
(5, 1, 46, 'CSC-172', 'Lecture TA', '3:00', '4:15', 'CSB-214'),
(6, 1, 46, 'CSC-172', 'Lecture TA', '3:00', '4:15', 'CSB-676'),
(7, 1, 47, 'CSC-171', 'Lecture TA', '3:00', '4:15', 'CSB-217'),
(8, 1, 47, 'CSC-171', 'Lecture TA', '3:00', '4:15', 'CSB-199'),
(9, 5, 46, 'CSC-172', 'Lab TA', '3:00', '4:15', 'CSB-218'),
(10, 1, 46, 'CSC-172', 'Lab TA', '3:00', '4:15', 'CSB-671'),
(11, 1, 47, 'CSC-171', 'Lab TA', '3:00', '4:15', 'CSB-211'),
(12, 1, 47, 'CSC-171', 'Lab TA', '3:00', '4:15', 'CSB-212'),
(13, 1, 47, 'CSC-171', 'Lecture TA', '3:00', '4:15', 'CSB-110'),
(14, 1, 46, 'CSC-172', 'Workshop Leader', '3:00', '4:15', 'CSB-311'),
(15, 1, 46, 'CSC-172', 'Workshop Leader', '3:00', '4:15', 'CSB-210B'),
(16, 5, 47, 'CSC-171', 'Workshop Leader', '3:00', '4:15', 'CSB-210'),
(17, 1, 47, 'CSC-171', 'Workshop Leader', '3:00', '4:15', 'CSB-365B'),
(18, 1, 46, 'CSC-172', 'Workshop Leader', '3:00', '4:15', 'CSB-310B'),
(19, 1, 46, 'CSC-172', 'Workshop Leader', '3:00', '4:15', 'CSB-210C'),
(20, 1, 47, 'CSC-171', 'Workshop Leader', '3:00', '4:15', 'CSB-210D'),
(21, 1, 47, 'CSC-171', 'Workshop Leader', '3:00', '4:15', 'CSB-365');

-- --------------------------------------------------------

--
-- Table structure for table `TAship`
--

CREATE TABLE IF NOT EXISTS `TAship` (
  `TID` int(11) DEFAULT NULL,
  `UID` int(11) DEFAULT NULL,
  `compensation` varchar(40) NOT NULL,
  `status` int(11) DEFAULT NULL,
  UNIQUE KEY `TID_2` (`TID`),
  KEY `TID` (`TID`),
  KEY `UID` (`UID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `TAship`
--

INSERT INTO `TAship` (`TID`, `UID`, `compensation`, `status`) VALUES
(14, 6, 'Paid', 0),
(3, 8, 'Paid', 0),
(13, 7, 'Credit', 1),
(15, 6, 'Paid', 0),
(7, 7, 'Credit', 0),
(4, 8, 'Paid', 0),
(9, 7, 'Paid', 0),
(10, 6, 'Paid', 0),
(16, 9, 'Credit', 0),
(17, 4, 'Credit', 0),
(19, 4, 'Credit', 0),
(20, 9, 'Credit', 0);

-- --------------------------------------------------------

--
-- Table structure for table `teaches`
--

CREATE TABLE IF NOT EXISTS `teaches` (
  `CID` int(11) NOT NULL,
  `UID` int(11) NOT NULL,
  KEY `UID` (`UID`),
  KEY `CID` (`CID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `teaches`
--

INSERT INTO `teaches` (`CID`, `UID`) VALUES
(44, 11),
(45, 11),
(46, 1),
(47, 1),
(48, 1);

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE IF NOT EXISTS `User` (
  `UID` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `userType` int(11) DEFAULT NULL,
  `firstName` varchar(30) DEFAULT NULL,
  `lastName` varchar(30) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `homePhone` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`UID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`UID`, `email`, `password`, `userType`, `firstName`, `lastName`, `phone`, `homePhone`) VALUES
(1, 'pawlicki@cs.rochester.edu', '$2y$10$2A.hcsZuL2p9cmBDRxVf.OqeDrnQaJbM2mCHsCNF2yGg7XLodRdo.', 2, 'Ted', 'Pawlicki', '2147483647', '2147483647'),
(3, 'brodudo@u.rochester.edu', '$2y$10$MGGaRkcBLCg/ZkYla1IM5.nbyZVFbWhG0l.alcrdbGvDI4Qa2GcCG', 3, 'Will', 'Smith', '2147483647', '2147483647'),
(4, 'supdupa@u.rochester.edu', '$2y$10$L2PQmUjkcD.t6Cc1JAonVOFBn2nV5B8feEqiR6WOGvMzRz3yoCOEW', 3, 'John', 'Doe', '2147483647', '2147483647'),
(5, 'dropthehammer@u.rochester.edu', '$2y$10$AA6ucCUbCgA2A8B2MP8tfeSG9.4g3C2WCa1FfJW30XnmNzFoqYRoO', 3, 'Mark', 'Johnson', '2147483647', '2147483647'),
(6, 'student1@u.rochester.edu', '$2y$10$rLJwX/gpyRFHLFzy6Lhi9.4ar2KjHbRB5WQrrg2hMQaBz/htqZ7Xi', 3, 'Ron', 'Brown', '2147483647', '2147483647'),
(7, 'student2@u.rochester.edu', '$2y$10$bciL8rqWRVfYu0DiqnuHc.PIeU9EJLIuSvO1.KdMT78RKSgzOUGFq', 3, 'Skyler', 'Winn', '2147483647', '2147483647'),
(8, 'student3@u.rochester.edu', '$2y$10$afJsvf4Fu8bFWWsJTN1Tpud6pUVScnSMzBAnfAGNiMtbIbLFQhL3m', 3, 'John', 'doe2', '2147483647', '2147483647'),
(9, 'dropthehammer2@u.rochester.edu', '$2y$10$AJ6Z/NvorPutX1uk8MjRjeEeqa7p9DLv751t7WgrV4riJYXhhqh2y', 3, 'Marcus', 'Brown', '2147483647', '2147483647'),
(10, 'brown@cs.rochester.edu', '$2y$10$Oi6A.Uf5CAZzZVNp75lscexk8ptAj7YJNwGRfceAmSrMEn05SnSZW', 2, 'Chris', 'Brown', '2147483647', '2147483647'),
(11, 'martin@cs.rochester.edu', '$2y$10$RL4eww6fYD.O81oymh1w7eC4X4iD.Z6Wa1jKZokPpjMw8lDXQ6aMm', 2, 'Nathaniel', 'Martin', '2147483647', '2147483647'),
(23, 'studentReg@u.rochester.edu', '$2y$10$7tbpQOgO9YeUHCk7fq41De8jEO8aUILwsgFWceakp.CxdHuVoR.b2', 3, 'Walter', 'White', '1236786543', '1236786543'),
(24, 'newacc@u.rochester.edu', '$2y$10$9ILZOX.v6XANCothSKY4MOh9VA.4P8DgD8C0AYJIa6jo7O.YUhFTy', 3, 'new ', 'account', '1234567890', '1234567890'),
(25, 'admin@u.rochester.edu', '$2y$10$SlDVi85Wje8uxgDkPxwXjekQ88e/6QROAbfy7NRp..FE0qZrG/Fi.', 1, 'Ronald', 'McDonald', '6785674567', '');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Student`
--
ALTER TABLE `Student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `User` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `TA`
--
ALTER TABLE `TA`
  ADD CONSTRAINT `ta_ibfk_1` FOREIGN KEY (`CID`) REFERENCES `Course` (`CID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ta_ibfk_2` FOREIGN KEY (`UID`) REFERENCES `User` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `TAship`
--
ALTER TABLE `TAship`
  ADD CONSTRAINT `taship_ibfk_1` FOREIGN KEY (`TID`) REFERENCES `TA` (`TID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `taship_ibfk_2` FOREIGN KEY (`UID`) REFERENCES `User` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `teaches`
--
ALTER TABLE `teaches`
  ADD CONSTRAINT `teaches_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `User` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `teaches_ibfk_2` FOREIGN KEY (`CID`) REFERENCES `Course` (`CID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
