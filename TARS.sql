-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 29, 2014 at 02:17 PM
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
  KEY `studentID` (`studentID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Course`
--

CREATE TABLE IF NOT EXISTS `Course` (
  `courseID` int(40) NOT NULL AUTO_INCREMENT,
  `courseName` varchar(40) NOT NULL,
  `courseTitle` varchar(40) NOT NULL,
  `website` varchar(40) NOT NULL,
  `startTime` varchar(40) NOT NULL,
  `endTime` varchar(40) NOT NULL,
  `placeID` int(30) NOT NULL,
  `term` varchar(30) NOT NULL,
  PRIMARY KEY (`courseID`),
  KEY `placeID` (`placeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Positions`
--

CREATE TABLE IF NOT EXISTS `Positions` (
  `positionID` int(30) NOT NULL AUTO_INCREMENT,
  `courseID` int(30) NOT NULL,
  `professorID` int(30) NOT NULL,
  `placeID` int(30) NOT NULL,
  `type` varchar(40) NOT NULL,
  PRIMARY KEY (`positionID`),
  KEY `courseID` (`courseID`,`professorID`,`placeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Professors`
--

CREATE TABLE IF NOT EXISTS `Professors` (
  `professorID` int(40) NOT NULL,
  `firstName` varchar(40) NOT NULL,
  `lastName` varchar(40) NOT NULL,
  `officePhone` int(30) NOT NULL,
  `mobilePhone` int(30) NOT NULL,
  `office` int(30) NOT NULL,
  PRIMARY KEY (`professorID`),
  KEY `professorID` (`professorID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Staff`
--

CREATE TABLE IF NOT EXISTS `Staff` (
  `staffID` int(40) NOT NULL,
  `firstName` int(40) NOT NULL,
  `lastName` int(40) NOT NULL,
  `officePhone` int(30) NOT NULL,
  `mobilePhone` int(30) NOT NULL,
  `office` int(30) NOT NULL,
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;

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
(50, 'emile.elmo.aron@throughthumb.com', 'Humble', 3);

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
  ADD CONSTRAINT `Course_ibfk_1` FOREIGN KEY (`placeID`) REFERENCES `Place` (`placeID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Positions`
--
ALTER TABLE `Positions`
  ADD CONSTRAINT `Positions_ibfk_1` FOREIGN KEY (`courseID`) REFERENCES `Course` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Professors`
--
ALTER TABLE `Professors`
  ADD CONSTRAINT `Professors_ibfk_1` FOREIGN KEY (`professorID`) REFERENCES `Users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

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
