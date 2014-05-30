-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 30, 2014 at 06:59 PM
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
-- Table structure for table `Assistantship`
--

CREATE TABLE IF NOT EXISTS `Assistantship` (
  `positionID` int(30) NOT NULL,
  `studentID` int(30) NOT NULL,
  `compensation` varchar(30) NOT NULL,
  `status` int(1) NOT NULL,
  KEY `positionID` (`positionID`),
  KEY `studentID` (`studentID`),
  KEY `positionID_2` (`positionID`),
  KEY `studentID_2` (`studentID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Assistantship`
--

INSERT INTO `Assistantship` (`positionID`, `studentID`, `compensation`, `status`) VALUES
(1, 71, 'paid', 2),
(2, 71, 'credit', 2),
(3, 71, 'paid', 2),
(1, 71, 'paid', 2),
(2, 71, 'credit', 0),
(3, 71, 'paid', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Course`
--

CREATE TABLE IF NOT EXISTS `Course` (
  `courseID` int(40) NOT NULL AUTO_INCREMENT,
  `professorID` int(11) NOT NULL,
  `courseNumber` varchar(40) NOT NULL,
  `courseTitle` varchar(40) NOT NULL,
  `website` varchar(40) NOT NULL,
  `startTime` varchar(40) NOT NULL,
  `endTime` varchar(40) NOT NULL,
  `placeID` int(30) NOT NULL,
  `term` varchar(30) NOT NULL,
  PRIMARY KEY (`courseID`),
  KEY `placeID` (`placeID`),
  KEY `professorID` (`professorID`),
  KEY `professorID_2` (`professorID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `Course`
--

INSERT INTO `Course` (`courseID`, `professorID`, `courseNumber`, `courseTitle`, `website`, `startTime`, `endTime`, `placeID`, `term`) VALUES
(1, 70, 'CSC-172', 'Data Structures', 'sdlfkjlkj', '4:00', '5:00', 2, 'Fall-2014'),
(2, 70, 'CSC-173', 'Data Structures', 'sdlfkjlkj', '4:00', '5:00', 4, 'Fall-2014'),
(3, 70, 'CSC-171', 'Data Structures', 'sdlfkjlkj', '4:00', '5:00', 5, 'Fall-2014'),
(4, 70, 'CSC-210', 'Data Structures', 'sdlfkjlkj', '4:00', '5:00', 6, 'Fall-2014');

-- --------------------------------------------------------

--
-- Table structure for table `Place`
--

CREATE TABLE IF NOT EXISTS `Place` (
  `placeID` int(40) NOT NULL AUTO_INCREMENT,
  `building` varchar(30) NOT NULL,
  `room` varchar(30) NOT NULL,
  `type` varchar(30) NOT NULL,
  PRIMARY KEY (`placeID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=101 ;

--
-- Dumping data for table `Place`
--

INSERT INTO `Place` (`placeID`, `building`, `room`, `type`) VALUES
(1, 'CSB', '306', 'Lab'),
(2, 'LATT', '249', 'Lecture Hall'),
(3, 'Meliora', '281', 'Lecture Hall'),
(4, 'Meliora', '217', 'Lab'),
(5, 'Meliora', '248', 'Lab'),
(6, 'Meliora', '319', 'Lab'),
(7, 'CSB', '223', 'Lecture Hall'),
(8, 'Meliora', '211', 'Lecture Hall'),
(9, 'CSB', '255', 'Lecture Hall'),
(10, 'Meliora', '349', 'Lecture Hall'),
(11, 'LATT', '263', 'Lab'),
(12, 'CSB', '357', 'Lecture Hall'),
(13, 'LATT', '363', 'Lab'),
(14, 'CSB', '239', 'Lab'),
(15, 'LATT', '204', 'Lab'),
(16, 'LATT', '331', 'Lecture Hall'),
(17, 'LATT', '400', 'Lecture Hall'),
(18, 'LATT', '305', 'Lab'),
(19, 'LATT', '335', 'Lecture Hall'),
(20, 'CSB', '219', 'Lecture Hall'),
(21, 'Meliora', '241', 'Lecture Hall'),
(22, 'Meliora', '396', 'Lab'),
(23, 'LATT', '359', 'Lab'),
(24, 'LATT', '240', 'Lab'),
(25, 'CSB', '393', 'Lecture Hall'),
(26, 'Meliora', '378', 'Lab'),
(27, 'LATT', '364', 'Lecture Hall'),
(28, 'LATT', '373', 'Lab'),
(29, 'Meliora', '217', 'Lecture Hall'),
(30, 'CSB', '368', 'Lecture Hall'),
(31, 'CSB', '223', 'Lab'),
(32, 'LATT', '378', 'Lab'),
(33, 'LATT', '258', 'Lecture Hall'),
(34, 'Meliora', '269', 'Lab'),
(35, 'Meliora', '205', 'Lab'),
(36, 'Meliora', '247', 'Lecture Hall'),
(37, 'CSB', '248', 'Lab'),
(38, 'LATT', '208', 'Lab'),
(39, 'CSB', '242', 'Lab'),
(40, 'CSB', '265', 'Lecture Hall'),
(41, 'LATT', '254', 'Lecture Hall'),
(42, 'CSB', '233', 'Lecture Hall'),
(43, 'LATT', '251', 'Lab'),
(44, 'CSB', '284', 'Lecture Hall'),
(45, 'Meliora', '265', 'Lecture Hall'),
(46, 'LATT', '293', 'Lecture Hall'),
(47, 'LATT', '371', 'Lecture Hall'),
(48, 'LATT', '358', 'Lab'),
(49, 'LATT', '212', 'Lecture Hall'),
(50, 'CSB', '400', 'Lab'),
(51, 'LATT', '237', 'Lab'),
(52, 'LATT', '326', 'Lecture Hall'),
(53, 'LATT', '384', 'Lecture Hall'),
(54, 'CSB', '244', 'Lecture Hall'),
(55, 'LATT', '342', 'Lecture Hall'),
(56, 'Meliora', '273', 'Lecture Hall'),
(57, 'CSB', '304', 'Lecture Hall'),
(58, 'LATT', '344', 'Lecture Hall'),
(59, 'CSB', '233', 'Lab'),
(60, 'LATT', '323', 'Lab'),
(61, 'LATT', '390', 'Lab'),
(62, 'CSB', '367', 'Lab'),
(63, 'Meliora', '316', 'Lecture Hall'),
(64, 'Meliora', '268', 'Lab'),
(65, 'CSB', '382', 'Lab'),
(66, 'Meliora', '322', 'Lab'),
(67, 'Meliora', '264', 'Lecture Hall'),
(68, 'Meliora', '240', 'Lab'),
(69, 'CSB', '253', 'Lecture Hall'),
(70, 'LATT', '337', 'Lab'),
(71, 'Meliora', '377', 'Lecture Hall'),
(72, 'CSB', '381', 'Lecture Hall'),
(73, 'CSB', '224', 'Lab'),
(74, 'CSB', '243', 'Lab'),
(75, 'Meliora', '222', 'Lab'),
(76, 'Meliora', '382', 'Lab'),
(77, 'LATT', '222', 'Lecture Hall'),
(78, 'Meliora', '223', 'Lecture Hall'),
(79, 'Meliora', '270', 'Lecture Hall'),
(80, 'CSB', '338', 'Lab'),
(81, 'Meliora', '352', 'Lecture Hall'),
(82, 'Meliora', '366', 'Lab'),
(83, 'Meliora', '218', 'Lecture Hall'),
(84, 'LATT', '382', 'Lecture Hall'),
(85, 'CSB', '372', 'Lecture Hall'),
(86, 'Meliora', '286', 'Lab'),
(87, 'LATT', '247', 'Lecture Hall'),
(88, 'LATT', '377', 'Lecture Hall'),
(89, 'Meliora', '220', 'Lab'),
(90, 'CSB', '362', 'Lecture Hall'),
(91, 'LATT', '281', 'Lab'),
(92, 'LATT', '226', 'Lecture Hall'),
(93, 'LATT', '235', 'Lecture Hall'),
(94, 'LATT', '266', 'Lecture Hall'),
(95, 'CSB', '289', 'Lecture Hall'),
(96, 'Meliora', '296', 'Lecture Hall'),
(97, 'CSB', '317', 'Lab'),
(98, 'LATT', '356', 'Lab'),
(99, 'LATT', '215', 'Lecture Hall'),
(100, 'LATT', '255', 'Lab');

-- --------------------------------------------------------

--
-- Table structure for table `Positions`
--

CREATE TABLE IF NOT EXISTS `Positions` (
  `positionID` int(30) NOT NULL AUTO_INCREMENT,
  `courseID` int(30) NOT NULL,
  `professorID` int(30) NOT NULL,
  `type` varchar(40) NOT NULL,
  PRIMARY KEY (`positionID`),
  KEY `courseID` (`courseID`,`professorID`),
  KEY `professorID` (`professorID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `Positions`
--

INSERT INTO `Positions` (`positionID`, `courseID`, `professorID`, `type`) VALUES
(1, 1, 70, 'Grader'),
(2, 1, 70, 'Grader'),
(3, 1, 70, 'Grader'),
(4, 1, 70, 'Grader'),
(5, 1, 70, 'Grader'),
(6, 2, 70, 'Grader'),
(7, 2, 70, 'Grader'),
(8, 2, 70, 'Grader'),
(9, 1, 70, 'Lab TA'),
(10, 1, 70, 'Lab TA'),
(11, 1, 70, 'Lab TA'),
(12, 3, 70, 'Lab TA'),
(13, 3, 70, 'Lab TA'),
(14, 3, 70, 'Lab TA'),
(15, 3, 70, 'Lab TA'),
(16, 1, 70, 'Lab TA'),
(17, 4, 70, 'Lab TA'),
(18, 4, 70, 'Lab TA'),
(19, 4, 70, 'Lab TA'),
(20, 4, 70, 'Lab TA'),
(21, 3, 70, 'Lab TA'),
(22, 3, 70, 'Lab TA'),
(23, 3, 70, 'Lab TA');

-- --------------------------------------------------------

--
-- Table structure for table `Professors`
--

CREATE TABLE IF NOT EXISTS `Professors` (
  `professorID` int(40) NOT NULL,
  `officeID` int(30) NOT NULL,
  `firstName` varchar(40) NOT NULL,
  `lastName` varchar(40) NOT NULL,
  `officePhone` int(30) NOT NULL,
  `mobilePhone` int(30) NOT NULL,
  PRIMARY KEY (`professorID`),
  KEY `professorID` (`professorID`),
  KEY `officeID` (`officeID`),
  KEY `officeID_2` (`officeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Professors`
--

INSERT INTO `Professors` (`professorID`, `officeID`, `firstName`, `lastName`, `officePhone`, `mobilePhone`) VALUES
(70, 1, 'Ted', 'Pawlicki', 2147483647, 2147483647);

-- --------------------------------------------------------

--
-- Table structure for table `Staff`
--

CREATE TABLE IF NOT EXISTS `Staff` (
  `staffID` int(40) NOT NULL,
  `firstName` varchar(40) NOT NULL,
  `lastName` varchar(40) NOT NULL,
  `officePhone` int(30) NOT NULL,
  `mobilePhone` int(30) NOT NULL,
  PRIMARY KEY (`staffID`),
  KEY `staffID` (`staffID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `gpa` decimal(10,0) NOT NULL,
  `classYear` int(10) NOT NULL,
  `aboutMe` longtext NOT NULL,
  PRIMARY KEY (`studentID`),
  KEY `studentID` (`studentID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Students`
--

INSERT INTO `Students` (`studentID`, `firstName`, `lastName`, `homePhone`, `mobilePhone`, `major`, `gpa`, `classYear`, `aboutMe`) VALUES
(71, 'oscar', 'rojas', '4444444444', '4444444444', 'cs', '4', 2015, 'alfjsldkj');

-- --------------------------------------------------------

--
-- Table structure for table `Teaches`
--

CREATE TABLE IF NOT EXISTS `Teaches` (
  `courseID` int(30) NOT NULL,
  `professorID` int(30) NOT NULL,
  KEY `courseID` (`courseID`,`professorID`),
  KEY `courseID_2` (`courseID`,`professorID`),
  KEY `professorID` (`professorID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Teaches`
--

INSERT INTO `Teaches` (`courseID`, `professorID`) VALUES
(1, 70),
(2, 70),
(3, 70),
(4, 70);

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `userID` int(40) NOT NULL AUTO_INCREMENT,
  `email` varchar(40) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=72 ;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`userID`, `email`, `password`, `type`) VALUES
(1, 'noble@memorymetal.edu', 'Weimar', 3),
(2, 'jake@futuregarden.org', 'Wixon Valley', 3),
(3, 'ollie.jaime@memorymetal.com', 'Roma', 3),
(4, 'roberto@thethen.edu', 'Keene', 3),
(5, 'douglass@egg.com', 'Tomball', 3),
(6, 'alec@form.me', 'Lockney', 3),
(7, 'phoebe.millicent.katheryn@sharpsheep.inf', 'Brownsville', 3),
(8, 'kari.estelle@secret.info', 'Manor', 3),
(9, 'emile.elmo@officeoil.com', 'Rhome', 3),
(10, 'wade@forcefork.info', 'Buckholts', 3),
(11, 'maria.lenard.chauncey@shirt.edu', 'Liberty Hill', 3),
(12, 'jamie@voicewaiting.org', 'Santa Cruz', 3),
(13, 'jamie@pocketpoint.edu', 'Pineland', 3),
(14, 'jules.gil.eliseo@rollroof.me', 'San Marcos', 3),
(15, 'damion.chi@copycord.com', 'Sheldon', 3),
(16, 'aileen@operationopinion.org', 'Galveston', 3),
(17, 'lesley.jocelyn.paige@letterlevel.com', 'Blue-Iglesia Antigua', 3),
(18, 'mckinley@businessbut.edu', 'Flatonia', 3),
(19, 'alfonso@low.info', 'Midway', 3),
(20, 'alfonzo.lyman@pipeplace.me', 'Natalia', 3),
(21, 'bill.lloyd@amusement.info', 'Lakewood Village', 3),
(22, 'jackson.raphael@normal.info', 'Loraine', 3),
(23, 'sofia.jeanie@pullpump.com', 'Highlands', 3),
(24, 'young.christian.lessie@army.me', 'Quemado', 3),
(25, 'chandra@leaflearning.edu', 'Bruni', 3),
(26, 'edward.brian@differentdigestion.info', 'Waller', 3),
(27, 'regina.erica@why.com', 'Bruni', 3),
(28, 'susanna.reva.deidre@impulsein.com', 'Pharr', 3),
(29, 'rhea.marquita@noisenormal.me', 'Poteet', 3),
(30, 'joan.ashley@water.org', 'Melissa', 3),
(31, 'otha.miquel.lacy@all.org', 'Sunnyvale', 3),
(32, 'spencer@everevery.edu', 'Hickory Creek', 3),
(33, 'althea@blade.info', 'Lago', 3),
(34, 'darryl@water.info', 'West Columbia', 3),
(35, 'brad.gabriel@servant.com', 'Lost Creek', 3),
(36, 'norris@shelf.org', 'McLean', 3),
(37, 'chris.johnny@fictionfield.org', 'La Homa', 3),
(38, 'rocco.gonzalo@clothcloud.me', 'La Presa', 3),
(39, 'hannah.whitney@authority.org', 'Mount Enterprise', 3),
(40, 'sarah@needlenerve.com', 'Del Mar Heights', 3),
(41, 'migdalia.ivette@acrossact.com', 'Alton North', 3),
(42, 'haywood@comfortcommittee.info', 'Pecan Gap', 3),
(43, 'paula@wood.info', 'Liberty Hill', 3),
(44, 'murray.jamal.devon@blackblade.info', 'Hillsboro', 3),
(45, 'richard@complexcondition.org', 'Hurst', 3),
(46, 'eldon.rocky.pierre@landlanguage.com', 'Josephine', 3),
(47, 'lorene.elsa.josefina@machine.org', 'Redwood', 3),
(48, 'armando.felix.jimmie@sign.info', 'Paducah', 3),
(49, 'caren@horn.info', 'Jacksboro', 3),
(50, 'emile.elmo.aron@throughthumb.com', 'Humble', 3),
(70, 'pawlicki@cs.rochester.edu', '$2y$10$lRWCMEejbmjj02hIwfMjvuATC2q2yC7eiH6uySm1RYciA0JxuyTQS', 1),
(71, 'orojas@u.rochester.edu', '$2y$10$zbMUGaKAGfmvxWlt8HIsa.GzYTAC/khAy846xHsPSCecfaaNzkZ2K', 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Assistantship`
--
ALTER TABLE `Assistantship`
  ADD CONSTRAINT `Assistantship_ibfk_1` FOREIGN KEY (`positionID`) REFERENCES `Positions` (`positionID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Assistantship_ibfk_2` FOREIGN KEY (`studentID`) REFERENCES `Students` (`studentID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Course`
--
ALTER TABLE `Course`
  ADD CONSTRAINT `Course_ibfk_1` FOREIGN KEY (`placeID`) REFERENCES `Place` (`placeID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Course_ibfk_2` FOREIGN KEY (`professorID`) REFERENCES `Professors` (`professorID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Positions`
--
ALTER TABLE `Positions`
  ADD CONSTRAINT `Positions_ibfk_1` FOREIGN KEY (`courseID`) REFERENCES `Course` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Positions_ibfk_2` FOREIGN KEY (`professorID`) REFERENCES `Professors` (`professorID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Professors`
--
ALTER TABLE `Professors`
  ADD CONSTRAINT `Professors_ibfk_1` FOREIGN KEY (`professorID`) REFERENCES `Users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Professors_ibfk_2` FOREIGN KEY (`officeID`) REFERENCES `Place` (`placeID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Staff`
--
ALTER TABLE `Staff`
  ADD CONSTRAINT `Staff_ibfk_1` FOREIGN KEY (`staffID`) REFERENCES `Users` (`userID`);

--
-- Constraints for table `Students`
--
ALTER TABLE `Students`
  ADD CONSTRAINT `Students_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `Users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Teaches`
--
ALTER TABLE `Teaches`
  ADD CONSTRAINT `Teaches_ibfk_1` FOREIGN KEY (`courseID`) REFERENCES `Course` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Teaches_ibfk_2` FOREIGN KEY (`professorID`) REFERENCES `Professors` (`professorID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
