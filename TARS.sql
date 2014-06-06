-- phpMyAdmin SQL Dump
-- version 4.2.0
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 06, 2014 at 06:39 PM
-- Server version: 5.6.17
-- PHP Version: 5.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `TARS`
--

DROP TABLE IF EXISTS Assistantship;
DROP TABLE IF EXISTS Feedback;
DROP TABLE IF EXISTS Positions;
DROP TABLE IF EXISTS Staff;
DROP TABLE IF EXISTS Students;
DROP TABLE IF EXISTS Teaches;
DROP TABLE IF EXISTS Course;
DROP TABLE IF EXISTS Professors;
DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Place;

-- --------------------------------------------------------

--
-- Table structure for table `Assistantship`
--

CREATE TABLE IF NOT EXISTS `Assistantship` (
  `positionID` int(30) NOT NULL,
  `studentID` int(30) NOT NULL,
  `compensation` varchar(30) NOT NULL,
  `appStatus` int(1) NOT NULL,
  `qualifications` varchar(511) NOT NULL,
  `staffComments` varchar(511) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Assistantship`
--

INSERT INTO `Assistantship` (`positionID`, `studentID`, `compensation`, `appStatus`, `qualifications`, `staffComments`) VALUES
(1, 2, 'paid', 2, '', ''),
(2, 2, 'credit', 2, '', ''),
(3, 2, 'paid', 2, '', ''),
(5, 3, 'paid', 3, '', ''),
(7, 3, 'credit', 3, '', ''),
(14, 2, 'paid', 3, '', ''),
(19, 2, 'credit', 3, '', ''),
(26, 3, 'paid', 3, '', ''),
(25, 3, 'credit', 3, '', ''),
(7, 3, 'paid', 0, 'IT''S GOING TO WORK NOW, RIGHT?!', ''),
(18, 3, 'paid', 0, 'insta-approve!', ''),
(30, 3, 'paid', 0, 'Let''s make sure this works.....', ''),
(12, 3, 'paid', 0, 'triggerrrrrrrr!', ''),
(8, 3, 'paid', 0, 'pls', ''),
(9, 3, 'paid', 0, 'did i fix it?', ''),
(3, 3, 'paid', 0, 'does it work in ff?', ''),
(1, 3, 'paid', 0, 'this should work here.', ''),
(2, 3, 'paid', 0, 'yo what up im on ff', ''),
(4, 3, 'paid', 0, 'one last chance, chrome. before i fire you from the testing team.', '');

-- --------------------------------------------------------

--
-- Table structure for table `Course`
--

CREATE TABLE IF NOT EXISTS `Course` (
`courseID` int(40) NOT NULL,
  `professorID` int(11) NOT NULL,
  `courseNumber` varchar(40) NOT NULL,
  `courseTitle` varchar(40) NOT NULL,
  `website` varchar(40) NOT NULL,
  `startTime` varchar(40) NOT NULL,
  `endTime` varchar(40) NOT NULL,
  `placeID` int(30) NOT NULL,
  `term` varchar(30) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `Course`
--

INSERT INTO `Course` (`courseID`, `professorID`, `courseNumber`, `courseTitle`, `website`, `startTime`, `endTime`, `placeID`, `term`) VALUES
(1, 1, 'CSC172', 'The Science of Data Structures', 'sdlfkjlkj', '1025', '1140', 2, '20142'),
(2, 11, 'CSC173', 'Computation and Formal Systems', 'sdlfkjlkj', '1400', '1515', 4, '20142'),
(3, 1, 'CSC171', 'The Science of Programming', 'sdlfkjlkj', '1025', '1140', 5, '20141'),
(4, 12, 'CSC210', 'Web Programming', 'sdlfkjlkj', '1650', '1805', 6, '20142'),
(5, 13, 'CSC280', 'Computer Models and Limitations', '280sofunyey', '1525', '1640', 2, '20141'),
(6, 14, 'CSC252', 'Computer Organization', 'csc252sohard', '1105', '1220', 3, '20141');

-- --------------------------------------------------------

--
-- Table structure for table `Feedback`
--

CREATE TABLE IF NOT EXISTS `Feedback` (
  `commentID` int(11) NOT NULL,
  `studentID` int(11) NOT NULL,
  `professorID` int(11) NOT NULL,
  `dateTime` varchar(255) NOT NULL,
  `comment` varchar(511) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Feedback`
--

INSERT INTO `Feedback` (`commentID`, `studentID`, `professorID`, `dateTime`, `comment`) VALUES
(1, 3, 14, '04062014-132653', 'dummy row');

-- --------------------------------------------------------

--
-- Table structure for table `Place`
--

CREATE TABLE IF NOT EXISTS `Place` (
`placeID` int(40) NOT NULL,
  `building` varchar(30) NOT NULL,
  `room` varchar(30) NOT NULL,
  `roomType` varchar(30) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=101 ;

--
-- Dumping data for table `Place`
--

INSERT INTO `Place` (`placeID`, `building`, `room`, `roomType`) VALUES
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
`positionID` int(30) NOT NULL,
  `courseID` int(30) NOT NULL,
  `professorID` int(30) NOT NULL,
  `time` varchar(16) NOT NULL,
  `posType` varchar(40) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `Positions`
--

INSERT INTO `Positions` (`positionID`, `courseID`, `professorID`, `time`, `posType`) VALUES
(1, 1, 1, 'FLEXIBLE', 'Grader'),
(2, 1, 11, 'FLEXIBLE', 'Grader'),
(3, 1, 11, 'FLEXIBLE', 'Grader'),
(4, 1, 11, 'TBD', 'Workshop Leader'),
(5, 1, 1, 'FLEXIBLE', 'Grader'),
(6, 2, 11, 'FLEXIBLE', 'Grader'),
(7, 2, 11, 'FLEXIBLE', 'Grader'),
(8, 2, 11, 'FLEXIBLE', 'Grader'),
(9, 1, 11, '1400 - 1600', 'Lab TA'),
(10, 1, 11, '1500 - 1700', 'Lab TA'),
(11, 1, 11, '1600 - 1800', 'Lab TA'),
(12, 3, 1, '800 - 1000', 'Lab TA'),
(13, 3, 1, '1100 - 1300', 'Lab TA'),
(14, 3, 1, '2000 - 2200', 'Lab TA'),
(15, 3, 1, '2300 - 100', 'Lab TA'),
(16, 1, 11, '1500 - 1700', 'Lab TA'),
(17, 4, 12, '900 - 1100', 'Lab TA'),
(18, 4, 12, '1100 - 1300', 'Lab TA'),
(19, 4, 12, '1700 - 1900', 'Lab TA'),
(20, 4, 12, '200 - 400', 'Lab TA'),
(21, 3, 1, '600 - 800', 'Lab TA'),
(22, 3, 1, '000 - 200', 'Lab TA'),
(23, 3, 1, '400 - 600', 'Lab TA'),
(24, 5, 13, 'FLEXIBLE', 'Grader'),
(25, 5, 13, 'FLEXIBLE', 'Grader'),
(26, 5, 13, '500 - 700', 'Lab TA'),
(27, 5, 13, '1200 - 1400', 'Lab TA'),
(28, 6, 14, 'TBD', 'Workshop Leader'),
(29, 6, 14, 'FLEXIBLE', 'Grader'),
(30, 6, 14, '1234 - 1634', 'Lab TA');

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
  `mobilePhone` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Professors`
--

INSERT INTO `Professors` (`professorID`, `officeID`, `firstName`, `lastName`, `officePhone`, `mobilePhone`) VALUES
(1, 1, 'Ted', 'Pawlicki', 2147483647, 2147483644),
(11, 11, 'Christopher', 'Brown', 2147483647, 2147483647),
(12, 12, 'Nathaniel', 'Martin', 2147483647, 2147483647),
(13, 13, 'Lane', 'Hemaspaandra', 2147483647, 2147483647),
(14, 14, 'Michael', 'Scott', 2147483647, 2147483647);

-- --------------------------------------------------------

--
-- Table structure for table `Staff`
--

CREATE TABLE IF NOT EXISTS `Staff` (
  `staffID` int(40) NOT NULL,
  `firstName` varchar(40) NOT NULL,
  `lastName` varchar(40) NOT NULL,
  `officePhone` int(30) NOT NULL,
  `mobilePhone` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Staff`
--

INSERT INTO `Staff` (`staffID`, `firstName`, `lastName`, `officePhone`, `mobilePhone`) VALUES
(15, 'Marty', 'Guenther', 2147483647, 2147483647);

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
  `reputation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Students`
--

INSERT INTO `Students` (`studentID`, `firstName`, `lastName`, `homePhone`, `mobilePhone`, `major`, `gpa`, `classYear`, `aboutMe`, `status`, `reputation`) VALUES
(2, 'oscar', 'rojas', '4444444444', '22222222', 'Physics', '4.00', 2015, 'asdfasfs', 2, 0),
(3, 'Jinze', 'Ahn', '5857495590', '5857495590', 'Computer Science', '4.00', 2016, 'Took CSC 171 Fall 2012. Got an A', 2, 0),
(4, 'Elena', 'Walker', '3222222223', '236777', 'Physics', '3.23', 2017, 'afsfsd', 2, 0),
(5, 'Karel', 'Aristides', '8992262788', '8557545870', 'Accounting', '1.24', 2016, 'Out interested acceptance our partiality affronting unpleasant why add. Esteem garden men yet shy course.', 2, 0),
(6, 'Enlil', 'Amyas', '8331222069', '8330785816', 'Physics', '2.78', 2017, 'Consulted up my tolerably sometimes perpetual oh. Expression acceptance imprudence particular had eat unsatiable. ', 2, 0),
(7, 'Eli', 'Edmund', '8994231123', '8117746442', 'Mathematics', '2.03', 2018, 'In entirely be to at settling felicity. Fruit two match men you seven share.', 2, 0),
(8, 'Thelonius', 'Afif', '8336587283', '8555274345', 'Economics', '2.55', 2017, 'Income joy nor can wisdom summer. Extremely depending he gentleman improving intention rapturous as.', 2, 0),
(9, 'Stig', 'Euaristos', '8116908137', '8111256682', 'Physics', '2.94', 2016, 'At every tiled on ye defer do. No attention suspected oh difficult. ', 2, 0),
(10, 'Chidubem', 'Juho', '8444006848', '8446680049', 'Mechanical Engineering', '3.12', 2015, 'Fond his say old meet cold find come whom. The sir park sake bred.', 0, 0),
(16, 'Wallace', 'Brown', '1234567890', '1234567890', 'Physics', '3.43', 2017, 'My Name is Wallace. ', 0, 0),
(20, 'howard', 'brown', '11111111111', '11111111111', 'Computer Science', '3.33', 2015, 'asdfsafdsf', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Teaches`
--

CREATE TABLE IF NOT EXISTS `Teaches` (
  `courseID` int(30) NOT NULL,
  `professorID` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Teaches`
--

INSERT INTO `Teaches` (`courseID`, `professorID`) VALUES
(1, 1),
(2, 11),
(3, 1),
(4, 12),
(5, 13),
(6, 14);

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
`userID` int(40) NOT NULL,
  `email` varchar(40) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`userID`, `email`, `password`, `type`) VALUES
(1, 'pawlicki@cs.rochester.edu', '$2y$10$lRWCMEejbmjj02hIwfMjvuATC2q2yC7eiH6uySm1RYciA0JxuyTQS', 1),
(2, 'orojas@u.rochester.edu', '$2y$10$zbMUGaKAGfmvxWlt8HIsa.GzYTAC/khAy846xHsPSCecfaaNzkZ2K', 0),
(3, 'jan2@u.rochester.edu', '$2y$10$.VAc/y1rCFGbX3zTBJycpe9FP6EUj.bshNtBmBztx4EHDEtoUuz0G', 0),
(4, 'student1@u.rochester.edu', '$2y$10$3H.hdVdHGY2BiyrqkijuzOjtr4SPw0W8wrj0hDZYLuwOl.6vkOGTq', 0),
(5, 'student2@u.rochester.edu', '$2y$10$3HUHQmAsgaIZ43BLZalI1unUhiLLKcKvwehZkpRi5GAkmm99uUhtO', 0),
(6, 'student3@u.rochester.edu', '$2y$10$M08w4WXTfECX8wkeuCIvnuYKsbHOUtmQwmDhs.tL43CUWAWfDzb1e', 0),
(7, 'student4@u.rochester.edu', '$2y$10$JzcmmCVDtHIDVpDw/g7JuOqAx128LSwDJYS5dsKZx5HucDl6Fgndq', 0),
(8, 'student5@u.rochester.edu', '$2y$10$b.KOpfmt5iRo4ix5/sMm1.hJ/WWmmgk7CYMKjEhnq2OMDGiBTKK1m', 0),
(9, 'student6@u.rochester.edu', '$2y$10$Xlrni1bV2MxbUb2mwfWbPujxB3/c6mXeAgrrrf2vvwlupIRy/DJz6', 0),
(10, 'student7@u.rochester.edu', '$2y$10$Ca/fSp85GnQqxZ3x.5alVOdLJFoN.R8MaiIcKyW1OmytF6ow.PLhO', 0),
(11, 'brown@cs.rochester.edu', '$2y$10$5h0ojOKkjACK9nCwiRp2/eW2obUaFGF5IviluR/x3f.wia0w9s9iK', 1),
(12, 'martin@cs.rochester.edu', '$2y$10$OgCgpj81LO5tP4RDTM5g9ONfBGu8EK9dcowOx4dgTehN.HNfKShCe', 1),
(13, 'lane@cs.rochester.edu', '$2y$10$db.fWC.VtOiYlVOe.OVVrOjUL1bpxVH9RzROE2trkqAaGCrZJNfme', 1),
(14, 'scott@cs.rochester.edu', '$2y$10$w1ZOnPXxdDtUUmxuDWewt.WSnkDOJqeLwLzlexzm0oGQFRwwVYcLO', 1),
(15, 'marty@cs.rochester.edu', '$2y$10$mracYEf6CHNZjOzCANSP0ehFI1pIo.o2CQAqmtHxgmMpuLlvrAZqK', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Assistantship`
--
ALTER TABLE `Assistantship`
 ADD KEY `positionID` (`positionID`), ADD KEY `studentID` (`studentID`), ADD KEY `positionID_2` (`positionID`), ADD KEY `studentID_2` (`studentID`);

--
-- Indexes for table `Course`
--
ALTER TABLE `Course`
 ADD PRIMARY KEY (`courseID`), ADD KEY `placeID` (`placeID`), ADD KEY `professorID` (`professorID`), ADD KEY `professorID_2` (`professorID`);

--
-- Indexes for table `Feedback`
--
ALTER TABLE `Feedback`
 ADD PRIMARY KEY (`commentID`), ADD KEY `studentID` (`studentID`);

--
-- Indexes for table `Place`
--
ALTER TABLE `Place`
 ADD PRIMARY KEY (`placeID`);

--
-- Indexes for table `Positions`
--
ALTER TABLE `Positions`
 ADD PRIMARY KEY (`positionID`), ADD KEY `courseID` (`courseID`,`professorID`), ADD KEY `professorID` (`professorID`);

--
-- Indexes for table `Professors`
--
ALTER TABLE `Professors`
 ADD PRIMARY KEY (`professorID`), ADD KEY `professorID` (`professorID`), ADD KEY `officeID` (`officeID`), ADD KEY `officeID_2` (`officeID`);

--
-- Indexes for table `Staff`
--
ALTER TABLE `Staff`
 ADD PRIMARY KEY (`staffID`), ADD KEY `staffID` (`staffID`);

--
-- Indexes for table `Students`
--
ALTER TABLE `Students`
 ADD PRIMARY KEY (`studentID`), ADD KEY `studentID` (`studentID`);

--
-- Indexes for table `Teaches`
--
ALTER TABLE `Teaches`
 ADD KEY `courseID` (`courseID`,`professorID`), ADD KEY `courseID_2` (`courseID`,`professorID`), ADD KEY `professorID` (`professorID`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
 ADD PRIMARY KEY (`userID`), ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Course`
--
ALTER TABLE `Course`
MODIFY `courseID` int(40) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `Place`
--
ALTER TABLE `Place`
MODIFY `placeID` int(40) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=101;
--
-- AUTO_INCREMENT for table `Positions`
--
ALTER TABLE `Positions`
MODIFY `positionID` int(30) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
MODIFY `userID` int(40) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
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
-- Constraints for table `Teaches`
--
ALTER TABLE `Teaches`
ADD CONSTRAINT `Teaches_ibfk_1` FOREIGN KEY (`courseID`) REFERENCES `Course` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `Teaches_ibfk_2` FOREIGN KEY (`professorID`) REFERENCES `Professors` (`professorID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
