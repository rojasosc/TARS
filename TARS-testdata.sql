
SET FOREIGN_KEY_CHECKS=0;
TRUNCATE TABLE Teaches;
TRUNCATE TABLE Comments;
TRUNCATE TABLE Applications;
TRUNCATE TABLE PositionTypes;
TRUNCATE TABLE Positions;
TRUNCATE TABLE Sessions;
TRUNCATE TABLE Courses;
TRUNCATE TABLE Sections;
TRUNCATE TABLE Terms;
TRUNCATE TABLE TermSemesters;
TRUNCATE TABLE Staff;
TRUNCATE TABLE Professors;
TRUNCATE TABLE Students;
TRUNCATE TABLE Users;
TRUNCATE TABLE Places;
TRUNCATE TABLE Events;
TRUNCATE TABLE Notifications;
TRUNCATE TABLE EventTypes;
TRUNCATE TABLE Configurations;
SET FOREIGN_KEY_CHECKS=1;

-- DATA TO PUT IN FINAL INIT SCRIPT:
-- default term session types
INSERT INTO `TermSemesters` (`semesterName`, `semesterIndex`) VALUES
('spring', 10),
('fall', 20);

-- default position types
INSERT INTO `PositionTypes` (`positionName`, `positionTitle`, `responsibilities`, `times`, `compensation`) VALUES
('lab', 'Lab TA', 'lab ta respons.', 'lab ta times', 'lab ta comp.'),
('wsl', 'Workshop Leader', 'ws respons.', 'ws times', 'ws comp.'),
('wssl', 'Workshop Superleader', 'wss respons.', 'wss times', 'wss comp.'),
('grader', 'Grader', 'g respons.', 'g times', 'g comp.');

INSERT INTO `EventTypes` (`eventName`, `severity`, `objectType`) VALUES
('SERVER_EXCEPTION', 'crit', 'EventType'),
('SERVER_DBERROR', 'crit', 'EventType'),
('ERROR_LOGIN', 'error', 'EventType'),
('ERROR_PERMISSION', 'error', 'EventType'),
('ERROR_FORM_FIELD', 'error', 'EventType'),
('ERROR_FORM_UPLOAD', 'error', 'EventType'),
('SESSION_LOGIN', 'info', 'User'),
('SESSION_LOGOUT', 'info', 'User'),
('SESSION_CONTINUE', 'debug', NULL),
('USER_CREATE', 'info', 'User'),
('USER_RESET_PASS', 'info', 'User'),
('USER_APPLY_TOKEN', 'info', 'User'),
('USER_IS_EMAIL_AVAIL', 'debug', NULL),
('USER_GET_OBJECT', 'debug', NULL),
('USER_GET_VIEW', 'debug', NULL),
('USER_SET_PROFILE', 'info', 'User'),
('USER_SET_PASS', 'info', 'User'),
('STUDENT_APPLY', 'info', 'Application'),
('STUDENT_CANCEL', 'info', 'Application'),
('STUDENT_WITHDRAW', 'info', 'Application'),
('NONSTUDENT_SET_APP', 'info', 'Application'),
('NONSTUDENT_COMMENT', 'info', 'Comment'),
('SU_CREATE_USER', 'info', 'User'),
('SU_RESET_USER_PASS', 'info', 'User'),
('STAFF_TERM_IMPORT', 'info', 'Term'),
('ADMIN_CONFIGURE', 'info', 'Configurable');

-- default configuration state
INSERT INTO `Configurations` (`creatorID`, `createTime`, `logDebug`, `adminCreated`, `emailDomain`, `emailLinkBase`, `enableLogin`, `currentTerm`) VALUES
(NULL, '2014-01-01 00:00:00', 1, 1, 'cs.rochester.edu', 'http://www.cs.rochester.edu/TARS/', 1, NULL);

-- DATA FOR TESTING ONLY:
-- test place objects, for professor offices
-- test users!
INSERT INTO `Users` (`email`, `emailVerified`, `password`, `passwordReset`, `firstName`, `lastName`, `creatorID`, `createTime`, `type`) VALUES
('costello@cs.rochester.edu', 0, NULL, 1, 'Dave', 'Costello', 1, '2014-01-01 00:00:00', 3),
('marty@cs.rochester.edu', 1, '$2y$10$mracYEf6CHNZjOzCANSP0ehFI1pIo.o2CQAqmtHxgmMpuLlvrAZqK', 0, 'Marty', 'Guenther', 1, '2014-01-01 00:00:00', 2),
('pawlicki@cs.rochester.edu', 1, '$2y$10$lRWCMEejbmjj02hIwfMjvuATC2q2yC7eiH6uySm1RYciA0JxuyTQS', 0, 'Ted', 'Pawlicki', 2, '2014-01-01 00:00:00', 1),
('orojas@u.rochester.edu', 1, '$2y$10$zbMUGaKAGfmvxWlt8HIsa.GzYTAC/khAy846xHsPSCecfaaNzkZ2K', 0, 'Oscar', 'Rojas', 4, '2014-01-01 00:00:00', 0),
('jan2@u.rochester.edu', 1, '$2y$10$.VAc/y1rCFGbX3zTBJycpe9FP6EUj.bshNtBmBztx4EHDEtoUuz0G', 0, 'Jinze', 'An', 5, '2014-01-01 00:00:00', 0),
('student1@u.rochester.edu', 1, '$2y$10$3H.hdVdHGY2BiyrqkijuzOjtr4SPw0W8wrj0hDZYLuwOl.6vkOGTq', 0, 'Elena', 'Walker', 6, '2014-01-01 00:00:00', 0),
('student2@u.rochester.edu', 1, '$2y$10$3HUHQmAsgaIZ43BLZalI1unUhiLLKcKvwehZkpRi5GAkmm99uUhtO', 0, 'Karel', 'Aristides', 7, '2014-01-01 00:00:00', 0),
('student3@u.rochester.edu', 1, '$2y$10$M08w4WXTfECX8wkeuCIvnuYKsbHOUtmQwmDhs.tL43CUWAWfDzb1e', 0, 'Enlil', 'Amayas', 8, '2014-01-01 00:00:00', 0),
('student4@u.rochester.edu', 1, '$2y$10$JzcmmCVDtHIDVpDw/g7JuOqAx128LSwDJYS5dsKZx5HucDl6Fgndq', 0, 'Eli', 'Edmund', 9, '2014-01-01 00:00:00', 0),
('student5@u.rochester.edu', 1, '$2y$10$b.KOpfmt5iRo4ix5/sMm1.hJ/WWmmgk7CYMKjEhnq2OMDGiBTKK1m', 0, 'Thelonius', 'Afif', 10, '2014-01-01 00:00:00', 0),
('student6@u.rochester.edu', 1, '$2y$10$Xlrni1bV2MxbUb2mwfWbPujxB3/c6mXeAgrrrf2vvwlupIRy/DJz6', 0, 'Stig', 'Euaristos', 11, '2014-01-01 00:00:00', 0),
('student7@u.rochester.edu', 1, '$2y$10$Ca/fSp85GnQqxZ3x.5alVOdLJFoN.R8MaiIcKyW1OmytF6ow.PLhO', 0, 'Chidubern', 'Juho', 12, '2014-01-01 00:00:00', 0),
('brown@cs.rochester.edu', 1, '$2y$10$5h0ojOKkjACK9nCwiRp2/eW2obUaFGF5IviluR/x3f.wia0w9s9iK', 0, 'Christopher', 'Brown', 2, '2014-01-01 00:00:00', 1),
('martin@cs.rochester.edu', 1, '$2y$10$OgCgpj81LO5tP4RDTM5g9ONfBGu8EK9dcowOx4dgTehN.HNfKShCe', 0, 'Nathaniel', 'Martin', 2, '2014-01-01 00:00:00', 1),
('lane@cs.rochester.edu', 1, '$2y$10$db.fWC.VtOiYlVOe.OVVrOjUL1bpxVH9RzROE2trkqAaGCrZJNfme', 0, 'Lane', 'Hemaspaandra', 2, '2014-01-01 00:00:00', 1),
('scott@cs.rochester.edu', 1, '$2y$10$w1ZOnPXxdDtUUmxuDWewt.WSnkDOJqeLwLzlexzm0oGQFRwwVYcLO', 0, 'Michael', 'Scott', 2, '2014-01-01 00:00:00', 1),
('rraqueno@cs.rochester.edu', 0, NULL, 1, 'Rolando', 'Raqueñ', 2, '2014-01-01 00:00:00',1 ),
('cogliati@cs.rochester.edu', 0, NULL, 1, 'A', 'Cogliati', 2, '2014-01-01 00:00:00', 1),
('koomen@cs.rochester.edu', 0, NULL, 1, 'Hans', 'Koomen', 2, '2014-01-01 00:00:00', 1),
('pg@cs.rochester.edu', 0, NULL, 1, 'Phillip', 'Guo', 2, '2014-01-01 00:00:00', 1),
('mehoque@cs.rochester.edu', 0, NULL, 1, 'Ehson', 'Hoque', 2, '2014-01-01 00:00:00', 1),
('cding@cs.rochester.edu', 0, NULL, 1, 'Chen', 'Ding', 2, '2014-01-01 00:00:00', 1),
('stefanko@cs.rochester.edu', 0, NULL, 1, 'Daniel', 'Stefankovic', 2, '2014-01-01 00:00:00', 1);

INSERT INTO `Places` (`building`, `room`) VALUES
('CSB', '722'),
('CSB', '700'),
('CSB', '650'),
('CSB', '618'),
('CSB', '715'),
('CSB', '703'),
('CSB', '701'),
('CSB', '720'),
('CSB', '702'),
('CSB', '710'),
('CSB', '620');

INSERT INTO `Professors` (`userID`, `officeID`, `officePhone`) VALUES
(3, 1, 5852711111),
(13, 2, 5852711111),
(14, 3, 5852711111),
(15, 4, 5852711111),
(16, 5, 5852711111),
(17, 6, 5852711111),
(18, 7, 5852711111),
(19, 8, 5852711111),
(20, 9, 5852711111),
(21, 10, 5852711111),
(22, 10, 5852711111),
(23, 11, 5852711111);

INSERT INTO `Staff` (`userID`, `officePhone`) VALUES
(2, 5752711111);

INSERT INTO `Students` (`userID`, `mobilePhone`, `major`, `gpa`, `classYear`, `aboutMe`, `universityID`) VALUES
(4, '22222222', 'Physics', '4.00', 2015, 'asdfasfs', 99999991),
(5, '5857495590', 'Computer Science', '4.00', 2016, 'Took CSC 171 Fall 2012. Got an A', 99999992),
(6, '236777', 'Physics', '3.23', 2017, 'afsfsd', 99999993),
(7, '8557545870', 'Accounting', '1.24', 2016, 'Out interested acceptance our partiality affronting unpleasant why add. Esteem garden men yet shy course.', 99999994),
(8, '8330785816', 'Physics', '2.78', 2017, 'Consulted up my tolerably sometimes perpetual oh. Expression acceptance imprudence particular had eat unsatiable. ', 99999995),
(9, '8117746442', 'Mathematics', '2.03', 2018, 'In entirely be to at settling felicity. Fruit two match men you seven share.', 99999996),
(10, '8555274345', 'Economics', '2.55', 2017, 'Income joy nor can wisdom summer. Extremely depending he gentleman improving intention rapturous as.', 99999997),
(11, '8111256682', 'Physics', '2.94', 2016, 'At every tiled on ye defer do. No attention suspected oh difficult. ', 99999998),
(12, '8446680049', 'Mechanical Engineering', '3.12', 2015, 'Fond his say old meet cold find come whom. The sir park sake bred.', 99999999);

