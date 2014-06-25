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
DROP TABLE IF EXISTS PositionTypes;
DROP TABLE IF EXISTS PositionSessions;
DROP TABLE IF EXISTS Positions;
DROP TABLE IF EXISTS CourseSessions;
DROP TABLE IF EXISTS Sessions;
DROP TABLE IF EXISTS Courses;
DROP TABLE IF EXISTS Terms;
DROP TABLE IF EXISTS TermSemesters;
DROP TABLE IF EXISTS Staff;
DROP TABLE IF EXISTS Professors;
DROP TABLE IF EXISTS Students;
DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Place;
DROP TABLE IF EXISTS Places;
DROP TABLE IF EXISTS Events;
DROP TABLE IF EXISTS EventSubscriberTemplates;
DROP TABLE IF EXISTS EventTypes;
DROP TABLE IF EXISTS Configurations;
SET FOREIGN_KEY_CHECKS=1;

--
-- Table structure for table `Places`
--
-- Represents a place.
--
-- Secondary, created by changes to Professors or Sessions
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
-- Primary, created by self (Student signup), Staff (Professors create), Admin (Staff create)
--
CREATE TABLE IF NOT EXISTS `Users` (
  `userID` bigint(20) NOT NULL AUTO_INCREMENT,
  `email` varchar(254) NOT NULL,
  `emailVerified` boolean NOT NULL,
  `password` varchar(255) NULL,
  `passwordReset` boolean NOT NULL,
  `resetToken` bigint(20) NULL,
  `resetTokenTime` datetime NULL,
  `firstName` varchar(40) NOT NULL,
  `lastName` varchar(40) NOT NULL,
  `creatorID` bigint(20) NULL,
  `createTime` datetime NOT NULL,
  `type` int(11) NOT NULL, -- TODO: enumify

  PRIMARY KEY (`userID`),
  UNIQUE KEY (`email`),
  KEY (`firstName`),
  KEY (`lastName`),
  KEY (`resetToken`),
  FOREIGN KEY (`creatorID`) REFERENCES `Users` (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;

--
-- Table structure for table `Students`
--
-- Represents a Student User
--
-- Secondary, created with a User object
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
-- Secondary, created with a User object
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
-- Secondary, created with a User object
--
CREATE TABLE IF NOT EXISTS `Staff` (
  `userID` bigint(20) NOT NULL,
  `officePhone` bigint(20) NOT NULL,
  `mobilePhone` bigint(20) NOT NULL,

  PRIMARY KEY (`userID`),
  FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `TermSemesters`
--
-- Represents a term session enumerable (fall, spring, summer, ...)
--
-- Secondary, created with a Term object
--
CREATE TABLE IF NOT EXISTS `TermSemesters` (
  `termSessionID` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  
  PRIMARY KEY (`termSessionID`),
  KEY (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Table structure for table `Term`
-- 
-- Represents a term for a given course.
--
-- Primary, created by Staff (import)
--
CREATE TABLE IF NOT EXISTS `Terms` (
  `termID` bigint(20) NOT NULL AUTO_INCREMENT,
  `year` year NOT NULL,
  `sessionID` bigint(20) NOT NULL,
  `creatorID` bigint(20) NOT NULL,
  `createTime` datetime NOT NULL,

  PRIMARY KEY (`termID`),
  FOREIGN KEY (`sessionID`) REFERENCES `TermSemesters` (`termSessionID`),
  FOREIGN KEY (`creatorID`) REFERENCES `Users` (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Table structure for table `Course`
--
-- Represents a Course in the database.
--
-- Primary, created by Staff (import or new course)
--
CREATE TABLE IF NOT EXISTS `Courses` (
  `courseID` bigint(20) NOT NULL AUTO_INCREMENT,
  `crn` bigint(20) NOT NULL,
  `department` varchar(10) NOT NULL,
  `courseNumber` varchar(10) NOT NULL,
  `courseTitle` varchar(80) NOT NULL,
  `website` varchar(40) NOT NULL,
  `termID` bigint(20) NOT NULL,
  `creatorID` bigint(20) NOT NULL,
  `createTime` datetime NOT NULL,

  PRIMARY KEY (`courseID`),
  UNIQUE KEY (`crn`, `termID`),
  KEY (`department`),
  KEY (`courseNumber`),
  KEY (`courseTitle`),
  FOREIGN KEY (`termID`) REFERENCES `Terms` (`termID`),
  FOREIGN KEY (`creatorID`) REFERENCES `Users` (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Table structure for table `Session`
--
-- Represents a time that a course happens.
-- There may be multiple of these for a course.
--
-- Secondary, created with a Course or Position
--
CREATE TABLE IF NOT EXISTS `Sessions` (
  `sessionID` bigint(20) NOT NULL AUTO_INCREMENT,
  `weekday` enum('M','T','W','R','F','S','U') NOT NULL,
  `startTime` time NOT NULL,
  `endTime` time NOT NULL,
  `placeID` bigint(20) NOT NULL,

  PRIMARY KEY (`sessionID`),
  FOREIGN KEY (`placeID`) REFERENCES `Places` (`placeID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Table structure for table `CourseSessions`
--
-- Represents the list of Sessions for a Course
--
-- Secondary, created with a Session & Course
--
CREATE TABLE IF NOT EXISTS `CourseSessions` (
  `courseSessionID` bigint(20) NOT NULL AUTO_INCREMENT,
  `courseID` bigint(20) NOT NULL,
  `sessionID` bigint(20) NOT NULL,

  PRIMARY KEY (`courseSessionID`),
  FOREIGN KEY (`courseID`) REFERENCES `Courses` (`courseID`),
  FOREIGN KEY (`sessionID`) REFERENCES `Sessions` (`sessionID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Table structure for table `PositionTypes`
--
-- Represents a position type enumerable (lab, ws, grader, superws)
--
-- Secondary, enumeration for Position.types (should not change often)
--
CREATE TABLE IF NOT EXISTS `PositionTypes` (
  `positionTypeID` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `responsibilities` varchar(511) NOT NULL,
  `times` varchar(511) NOT NULL,
  `compensation` varchar(511) NOT NULL,
  
  PRIMARY KEY (`positionTypeID`),
  KEY (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Table structure for table `Positions`
--
-- Represents a position that a TA can fill.
--
-- Primary, created by Staff (import or new position)
--
CREATE TABLE IF NOT EXISTS `Positions` (
  `positionID` bigint(20) NOT NULL AUTO_INCREMENT,
  `courseID` bigint(20) NOT NULL,
  `description` varchar(40) NULL,
  `maximumAccepted` int(11) NOT NULL,
  `positionTypeID` bigint(20) NOT NULL,
  `creatorID` bigint(20) NOT NULL,
  `createTime` datetime NOT NULL,

  PRIMARY KEY (`positionID`),
  FOREIGN KEY (`courseID`) REFERENCES `Courses` (`courseID`),
  FOREIGN KEY (`positionTypeID`) REFERENCES `PositionTypes` (`positionTypeID`),
  FOREIGN KEY (`creatorID`) REFERENCES `Users` (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;

--
-- Table structure for table `PositionSessions`
--
-- Represents the list of Sessions for a Position
--
-- Secondary, created with a Session & Position
--
CREATE TABLE IF NOT EXISTS `PositionSessions` (
  `positionSessionID` bigint(20) NOT NULL AUTO_INCREMENT,
  `positionID` bigint(20) NOT NULL,
  `sessionID` bigint(20) NOT NULL,

  PRIMARY KEY (`positionSessionID`),
  FOREIGN KEY (`positionID`) REFERENCES `Positions` (`positionID`),
  FOREIGN KEY (`sessionID`) REFERENCES `Sessions` (`sessionID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Table structure for table `Applications`
--
-- Represents an application made by a student
--
-- Primary, created by Student (apply)
--
CREATE TABLE IF NOT EXISTS `Applications` (
  `appID` bigint(20) NOT NULL AUTO_INCREMENT,
  `positionID` bigint(20) NOT NULL,
  `studentID` bigint(20) NOT NULL,
  `compensation` enum('pay','credit') NOT NULL,
  `appStatus` int(11) NOT NULL, -- TODO: enumify
  `qualifications` varchar(511) NOT NULL,
  `creatorID` bigint(20) NOT NULL,
  `createTime` datetime NOT NULL,

  PRIMARY KEY (`appID`),
  UNIQUE KEY (`positionID`, `studentID`),
  FOREIGN KEY (`positionID`) REFERENCES `Positions` (`positionID`),
  FOREIGN KEY (`studentID`) REFERENCES `Students` (`userID`),
  FOREIGN KEY (`creatorID`) REFERENCES `Users` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `Feedback`
--
-- Represents feedback comments.
--
-- Primary, created by Professor or Staff
--
CREATE TABLE IF NOT EXISTS `Comments` (
  `commentID` bigint(20) NOT NULL AUTO_INCREMENT,
  `comment` varchar(511) NOT NULL,
  `studentID` bigint(20) NOT NULL,
  `creatorID` bigint(20) NOT NULL,
  `createTime` datetime NOT NULL,

  PRIMARY KEY (`commentID`),
  FOREIGN KEY (`studentID`) REFERENCES `Students` (`userID`),
  FOREIGN KEY (`creatorID`) REFERENCES `Users` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `Teaches`
--
-- Represents the relationship between Courses and Professors.
--
-- Secondary, created with Course & Professor
--
CREATE TABLE IF NOT EXISTS `Teaches` (
  `teachID` bigint(20) NOT NULL AUTO_INCREMENT,
  `courseID` bigint(20) NOT NULL,
  `professorID` bigint(20) NOT NULL,

  PRIMARY KEY (`teachID`),
  FOREIGN KEY (`courseID`) REFERENCES `Courses` (`courseID`),
  FOREIGN KEY (`professorID`) REFERENCES `Professors` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `EventTypes`
--
-- Represents the "type" of all events. Events of a given type may be disclosed
-- to the creator or other interested parties depending upon the
-- EventSubscriberTemplates table. See db.php
--
-- Secondary, enumeration for Events.type (should not change often)
--
CREATE TABLE IF NOT EXISTS `EventTypes` (
  `eventTypeID` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `severity` enum('debug','info','notice','warning','error','crit') NOT NULL,
  `objectType` enum('Term','Course','Position','Application','Comment','User','EventType') NULL,

  PRIMARY KEY (`eventTypeID`),
  KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `EventSubscriberTemplates`
--
-- Secondary, enumeration additional data (should not change often)
--
CREATE TABLE IF NOT EXISTS `EventSubscriberTemplates` (
  `eventSTID` bigint(20) NOT NULL AUTO_INCREMENT,
  `eventTypeID` bigint(20) NOT NULL,
  `notifyTarget` enum('self','target','professors','staff','admins') NOT NULL,
  `notifyMode` enum('email','home','both') NOT NULL,
  `subject` varchar(64) NOT NULL,
  `template` varchar(511) NOT NULL,

  PRIMARY KEY (`eventSTID`),
  FOREIGN KEY (`eventTypeID`) REFERENCES `EventTypes` (`eventTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
  

--
-- Table structure for table `Events`
--
-- Represents all logged events.
--
-- Primary, created on logged events and used to notify users (email & home page)
--
CREATE TABLE IF NOT EXISTS `Events` (
  `eventID` bigint(20) NOT NULL AUTO_INCREMENT,
  `eventTypeID` bigint(20) NOT NULL,
  `description` varchar(511) NOT NULL,
  `objectID` bigint(20) NULL,
  `creatorID` bigint(20) NULL,
  `createTime` datetime NOT NULL,
  `creatorIP` binary(16) NOT NULL, -- support ipv6, just in case

  PRIMARY KEY (`eventID`),
  FOREIGN KEY (`eventTypeID`) REFERENCES `EventTypes` (`eventTypeID`),
  FOREIGN KEY (`creatorID`) REFERENCES `Users` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Table structure for table `Configurations`
--
-- Represents the global configuration state of the application.
-- Each row has all global options, so query the latest with
-- SELECT optionName FROM Configurations ORDER BY configID ASC LIMIT 1
--
-- Primary, created by Admin
--
CREATE TABLE IF NOT EXISTS `Configurations` (
  `configID` bigint(20) NOT NULL AUTO_INCREMENT,
  `creatorID` bigint(20) NULL,
  `createTime` datetime NOT NULL,

  `logDebug` boolean NOT NULL,
  `adminCreated` boolean NOT NULL,
  `domain` varchar(64) NOT NULL,
  `enableLogin` boolean NOT NULL,
  `currentTerm` bigint(20) NULL,

  PRIMARY KEY (`configID`),
  FOREIGN KEY (`currentTerm`) REFERENCES `Terms` (`termID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

