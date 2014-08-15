-- TARS Upgrade Script (0.7.0 -> 0.8.0)
-- 
-- Last Modified Time: Aug 15, 8:52 AM ADT-3:00
-- Last Modified By: Nate Book
-- Host: localhost
-- Server version: 5.6.17
-- PHP Version: 5.4.28
-- 

ALTER TABLE `Sections` ADD `status` ENUM('ok','not-ok') NOT NULL DEFAULT 'not-ok' AFTER `type`;

