-- TARS Upgrade Script (0.8.0 -> 0.9.0)
-- 
-- Last Modified Time: Aug 18, 11:01 AM ADT-3:00
-- Last Modified By: Nate Book
-- Host: localhost
-- Server version: 5.6.17
-- PHP Version: 5.4.28

-- update configurations table
ALTER TABLE `Configurations`
MODIFY `creatorID` bigint(20) NOT NULL DEFAULT 1, -- for old schemas: default hopefully root
MODIFY `emailName` varchar(64) NOT NULL DEFAULT 'no-reply' AFTER `adminCreated`,
MODIFY `enableSendEmail` boolean NOT NULL DEFAULT 0 AFTER `enableLogin`, -- for old schemas: default off
MODIFY `bugReportUser` bigint(20) NOT NULL DEFAULT 1 AFTER `currentTerm`, -- for old schemas: default hopefully root
ADD FOREIGN KEY (`creatorID`) REFERENCES `Users` (`userID`),
ADD FOREIGN KEY (`bugReportUser`) REFERENCES `Users` (`userID`);

-- fix IPv4 storage
ALTER TABLE `Events` MODIFY `creatorIP` varbinary(16) NOT NULL;

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
INSERT INTO `PositionTypes` (`positionName`, `positionTitle`, `compensation`, `responsibilities`, `times`)
VALUES ('lecture', 'TA', '...', '...', '...');

-- update error types
SET ERROR_ACTION_ID = INSERT INTO `EventTypes` (`eventName`, `severity`, `objectType`)
VALUES ('ERROR_ACTION', 'error', 'EventType');

UPDATE `Events`
SET `eventTypeID` = ERROR_ACTION_ID
WHERE `eventTypeID` IN
	(SELECT `eventTypeID` FROM `EventTypes`
	WHERE `eventName` LIKE 'ERROR_%') AS SUBQ;

UPDATE `EventTypes`
SET `severity` = 'notice'
WHERE `eventName` LIKE 'SESSION_LOG%';

DELETE FROM `EventTypes`
WHERE `eventName` LIKE 'ERROR_%' AND NOT `eventName` = 'ERROR_ACTION';
