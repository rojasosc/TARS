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


--
-- Database: `TARS`
--

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS Teaches;
DROP TABLE IF EXISTS Feedback;
DROP TABLE IF EXISTS Applications;
DROP TABLE IF EXISTS Assistantship;
DROP TABLE IF EXISTS Positions;
DROP TABLE IF EXISTS CourseSessions;
DROP TABLE IF EXISTS Sessions;
DROP TABLE IF EXISTS Courses;
DROP TABLE IF EXISTS Terms;
DROP TABLE IF EXISTS Staff;
DROP TABLE IF EXISTS Professors;
DROP TABLE IF EXISTS Students;
DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Place;
DROP TABLE IF EXISTS Places;
SET FOREIGN_KEY_CHECKS=1;

--
-- Table structure for table `Places`
--
-- Represents a place.
--
CREATE TABLE IF NOT EXISTS `Places` (
  `placeID` bigint(20) NOT NULL AUTO_INCREMENT,
  `building` varchar(30) NOT NULL,
  `room` varchar(30) NOT NULL,
  `roomType` varchar(30) NOT NULL,

  PRIMARY KEY (`placeID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;

--
-- Table structure for table `Users`
--
-- Represents any users in the system
--
CREATE TABLE IF NOT EXISTS `Users` (
  `userID` bigint(20) NOT NULL AUTO_INCREMENT,
  `email` varchar(254) NOT NULL,
  `password` varchar(255) NULL,
  `passwordToken` int(11) NULL,
  `passwordTokenExpire` datetime NULL,
  `accountState` enum('new','active','reset') NOT NULL,
  `firstName` varchar(40) NOT NULL,
  `lastName` varchar(40) NOT NULL,
  `type` int(11) NOT NULL,

  PRIMARY KEY (`userID`),
  UNIQUE KEY (`email`),
  KEY (`firstName`),
  KEY (`lastName`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;

--
-- Table structure for table `Students`
--
-- Represents a Student User
--
CREATE TABLE IF NOT EXISTS `Students` (
  `userID` bigint(20) NOT NULL,
  `mobilePhone` bigint(20) NOT NULL,
  `major` varchar(75) NOT NULL,
  `gpa` decimal(10,2) NOT NULL,
  `classYear` int(10) NOT NULL,
  `aboutMe` longtext NOT NULL,
  `status` int(11) NOT NULL,
  `reputation` int(11) NOT NULL,
  `universityID` bigint(20) NOT NULL,

  PRIMARY KEY (`userID`),
  FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `Professors`
--
-- Represents the Professor Users
--
CREATE TABLE IF NOT EXISTS `Professors` (
  `userID` bigint(20) NOT NULL,
  `officeID` bigint(20) NOT NULL,
  `officePhone` bigint(20) NOT NULL,
  `mobilePhone` bigint(20) NOT NULL,

  PRIMARY KEY (`userID`),
  FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`),
  FOREIGN KEY (`officeID`) REFERENCES `Places` (`placeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `Staff`
--
-- Represents a Staff User.
--
CREATE TABLE IF NOT EXISTS `Staff` (
  `userID` bigint(20) NOT NULL,
  `officePhone` bigint(20) NOT NULL,
  `mobilePhone` bigint(20) NOT NULL,

  PRIMARY KEY (`userID`),
  FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `Term`
-- 
-- Represents a term for a given course.
--
CREATE TABLE IF NOT EXISTS `Terms` (
  `termID` bigint(20) NOT NULL AUTO_INCREMENT,
  `year` year NOT NULL,
  `session` enum('spring','fall') NOT NULL,

  PRIMARY KEY (`termID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Table structure for table `Course`
--
-- Represents a Course in the database.
--
CREATE TABLE IF NOT EXISTS `Courses` (
  `courseID` bigint(20) NOT NULL AUTO_INCREMENT,
  `crn` bigint(20) NOT NULL,
  `department` varchar(10) NOT NULL,
  `courseNumber` varchar(10) NOT NULL,
  `courseTitle` varchar(80) NOT NULL,
  `website` varchar(40) NOT NULL,
  `termID` bigint(20) NOT NULL,

  PRIMARY KEY (`courseID`),
  UNIQUE KEY (`crn`, `termID`),
  KEY (`department`),
  KEY (`courseNumber`),
  KEY (`courseTitle`),
  FOREIGN KEY (`termID`) REFERENCES `Terms` (`termID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Table structure for table `Session`
--
-- Represents a time that a course happens.
-- There may be multiple of these for a course.
--
CREATE TABLE IF NOT EXISTS `Sessions` (
  `sessionID` bigint(20) NOT NULL AUTO_INCREMENT,
  `weekday` enum('M','T','W','R','F','S','U') NOT NULL,
  `startTime` time NOT NULL,
  `endTime` time NOT NULL,

  PRIMARY KEY (`sessionID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Table structure for table `CourseSessions`
--
-- Represents the list of Sessions for a Course
--
CREATE TABLE IF NOT EXISTS `CourseSessions` (
  `courseSessionID` bigint(20) NOT NULL AUTO_INCREMENT,
  `courseID` bigint(20) NOT NULL,
  `sessionID` bigint(20) NOT NULL,
  `placeID` bigint(20) NOT NULL,

  PRIMARY KEY (`courseSessionID`),
  FOREIGN KEY (`courseID`) REFERENCES `Courses` (`courseID`),
  FOREIGN KEY (`sessionID`) REFERENCES `Sessions` (`sessionID`),
  FOREIGN KEY (`placeID`) REFERENCES `Places` (`placeID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Table structure for table `Positions`
--
-- Represents a position that a TA can fill.
--
CREATE TABLE IF NOT EXISTS `Positions` (
  `positionID` bigint(20) NOT NULL AUTO_INCREMENT,
  `courseID` bigint(20) NOT NULL,
  `professorID` bigint(20) NOT NULL,
  `time` varchar(40) NOT NULL,
  `posType` varchar(40) NOT NULL,

  PRIMARY KEY (`positionID`),
  FOREIGN KEY (`courseID`) REFERENCES `Courses` (`courseID`),
  FOREIGN KEY (`professorID`) REFERENCES `Professors` (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;

--
-- Table structure for table `Assistantship`
--
-- Represents an application made by a student
--
CREATE TABLE IF NOT EXISTS `Applications` (
  `appID` bigint(20) NOT NULL AUTO_INCREMENT,
  `positionID` bigint(20) NOT NULL,
  `studentID` bigint(20) NOT NULL,
  `compensation` enum('pay','credit') NOT NULL,
  `appStatus` int(11) NOT NULL,
  `qualifications` varchar(511) NOT NULL,

  PRIMARY KEY (`appID`),
  UNIQUE KEY (`positionID`, `studentID`),
  FOREIGN KEY (`positionID`) REFERENCES `Positions` (`positionID`),
  FOREIGN KEY (`studentID`) REFERENCES `Students` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `Feedback`
--
-- Represents feedback comments.
--
CREATE TABLE IF NOT EXISTS `Feedback` (
  `feedbackID` bigint(20) NOT NULL AUTO_INCREMENT,
  `studentID` bigint(20) NOT NULL,
  `commenterID` bigint(20) NOT NULL,
  `dateTime` datetime NOT NULL,
  `comment` varchar(511) NOT NULL,

  PRIMARY KEY (`feedbackID`),
  FOREIGN KEY (`studentID`) REFERENCES `Students` (`userID`),
  FOREIGN KEY (`commenterID`) REFERENCES `Users` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `Teaches`
--
-- Represents the relationship between Courses and Professors.
--
CREATE TABLE IF NOT EXISTS `Teaches` (
  `teachID` bigint(20) NOT NULL AUTO_INCREMENT,
  `courseID` bigint(20) NOT NULL,
  `professorID` bigint(20) NOT NULL,

  PRIMARY KEY (`teachID`),
  FOREIGN KEY (`courseID`) REFERENCES `Courses` (`courseID`),
  FOREIGN KEY (`professorID`) REFERENCES `Professors` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

