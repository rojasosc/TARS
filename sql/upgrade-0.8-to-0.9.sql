-- TARS Upgrade Script (0.8.0 -> 0.9.0)
-- 
-- Last Modified Time: Aug 18, 11:01 AM ADT-3:00
-- Last Modified By: Nate Book
-- Host: localhost
-- Server version: 5.6.17
-- PHP Version: 5.4.28
-- 

-- update configurations table
ALTER TABLE `Configurations`
MODIFY `creatorID` bigint(20) NOT NULL DEFAULT 1; -- for old schemas: default hopefully root
ALTER TABLE `Configurations`
ADD `emailName` varchar(64) NOT NULL DEFAULT 'no-reply' AFTER `adminCreated`;
ALTER TABLE `Configurations`
ADD `enableSendEmail` boolean NOT NULL DEFAULT 0 AFTER `enableLogin`; -- for old schemas: default off
ALTER TABLE `Configurations`
ADD `bugReportUser` bigint(20) NOT NULL DEFAULT 1 AFTER `currentTerm`, -- for old schemas: default hopefully root
ALTER TABLE `Configurations`
ADD FOREIGN KEY (`creatorID`) REFERENCES `Users` (`userID`),
ALTER TABLE `Configurations`
ADD FOREIGN KEY (`bugReportUser`) REFERENCES `Users` (`userID`);

-- update old Users.types
UPDATE `Users`
SET `type` = 8 WHERE `type` = 3;
UPDATE `Users`
SET `type` = 4 WHERE `type` = 2;
UPDATE `Users`
SET `type` = 2 WHERE `type` = 1;
UPDATE `Users`
SET `type` = 1 WHERE `type` = 0;

-- add lecture TA
INSERT INTO `PositionTypes` (`positionName`, `positionTitle`, `compensation`, `qualifications`, `times`)
VALUES ('lecture', 'TA', '...', '...', '...');

-- update error types
SET ERROR_ACITON_ID = INSERT INTO `EventTypes` (`eventName`, `severity`, `objectType`)
VALUES ('ERROR_ACITON', 'error', 'EventType');

UPDATE `Events`
SET `eventTypeID` = ERROR_ACTION_ID
WHERE `eventTypeID` IN
	(SELECT `eventTypeID` FROM `EventTypes`
	WHERE `eventName` LIKE 'ERROR_%') AS SUBQ;

DELETE FROM `EventTypes`
WHERE `eventName` LIKE 'ERROR_%' AND NOT `eventName` = 'ERROR_ACTION';

