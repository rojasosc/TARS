
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
TRUNCATE TABLE NotificationTemplates;
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
('SERVER_EXCEPTION', 'crit', 'EventType'), -- 1
('SERVER_DBERROR', 'crit', 'EventType'), -- 2
('ERROR_LOGIN', 'error', 'EventType'), -- 3
('ERROR_PERMISSION', 'error', 'EventType'), -- 4
('ERROR_NOT_FOUND', 'error', 'EventType'), -- 5
('ERROR_FORM_FIELD', 'error', 'EventType'), -- 6
('ERROR_FORM_UPLOAD', 'error', 'EventType'), -- 7
('ERROR_CSV_PARSE', 'error', 'EventType'), -- 8
('ERROR_JSON_PARSE', 'error', 'EventType'), -- 9
('SESSION_LOGIN', 'info', 'User'), -- 10
('SESSION_LOGOUT', 'info', 'User'), -- 11
('USER_CREATE', 'info', 'User'), -- 12
('USER_RESET', 'info', 'User'), -- 13
('USER_CONFIRM', 'info', 'User'), -- 14
('USER_CHECK_EMAIL', 'debug', NULL), -- 15
('USER_GET_APPS', 'debug', NULL), -- 16
('USER_GET_POSITIONS', 'debug', NULL), -- 17
('USER_GET_SECTIONS', 'debug', NULL), -- 18
('USER_GET_STUDENTS', 'debug', NULL), -- 19
('USER_GET_PROFESSORS', 'debug', NULL), -- 20
('USER_GET_USERS', 'debug', NULL), -- 21
('USER_GET_PROFILE', 'debug', NULL), -- 22
('USER_SET_PROFILE', 'info', 'User'), -- 23
('STUDENT_APPLY', 'info', 'Application'), -- 24
('STUDENT_CANCEL', 'info', 'Application'), -- 25
('STUDENT_WITHDRAW', 'info', 'Application'), -- 26
('STUDENT_SEARCH', 'debug', NULL), -- 27
('PROFESSOR_ACCEPT', 'info', 'Application'), -- 28
('PROFESSOR_REJECT', 'info', 'Application'), -- 29
('PROFESSOR_COMMENT', 'info', 'Comment'), -- 30
('STAFF_CREATE_PROF', 'info', 'User'), -- 31
('STAFF_RESET_PROF', 'info', 'User'), -- 32
('STAFF_TERM_IMPORT', 'info', 'Term'), -- 33
('STAFF_GET_PAYROLL', 'debug', NULL), -- 34
('ADMIN_CONFIGURE', 'info', 'Configurable'); -- 35

INSERT INTO `NotificationTemplates`
(`eventTypeID`, `notifyTarget`, `notifyMode`, `subject`, `template`) VALUES
-- USER_CREATE:
-- email confirm token
(8, 'self', 'email', 'Account Signup', 'You have successfully created a student TARS account with this email address. Click the following link to confirm it belongs to you: %L'),
-- USER_RESET:
-- email reset token
(9, 'self', 'email', 'Account Password Reset', 'You have requested to reset your current password. Click the following link to continue: %L'),
-- STUDENT_APPLY:
-- a student creates Application object
(12, 'self', 'email', 'Student Application Create', 'You have applied to position %O. Please await decision.'),
(12, 'professors', 'home', 'Student Application Create', '%C has applied to position %O. You may choose to accept or reject their application here: %L'),
-- STUDENT_CANCEL:
-- a student cancels an Application object (before response)
(13, 'self', 'email', 'Student Application Cancel', 'You have cancelled your application to position %O.'),
(13, 'professors', 'home', 'Student Application Cancel', '%C has cancelled their application to position %O.'),
-- STUDENT_WITHDRAW:
-- a student withdraws an Application object (after accept)
(14, 'self', 'email', 'Student Application Withdraw', 'You have withdrawn your accepted application to position %O.'),
(14, 'professors', 'both', 'Student Application Withdraw', '%C has withdrawn their accepted application to position %O.'),
(14, 'staff', 'both', 'Student Application Withdraw', '%C has withdrawn their accepted application to position %O.'),
-- PROFESSOR_ACCEPT:
-- a professor accepts a student application
(15, 'target', 'both', 'Student Application Accepted', 'You have been accepted to the position of %O.'),
-- PROFESSOR_REJECT:
-- a professor rejects a student application
-- no notify
-- STAFF_CREATE_PROF:
-- email confirm token
(18, 'target', 'email', 'Account Signup', 'An administrator has created a TARS account for this email address. Click the following link to confirm it belongs to you and set a password: %L'),
-- STAFF_RESET_PROF:
-- email reset token
(9, 'target', 'email', 'Account Password Reset', 'An administrator has reset your account password. Click the following link to continue: %L');

-- default configuration state
INSERT INTO `Configurations` (`creatorID`, `createTime`, `logDebug`, `adminCreated`, `domain`, `enableLogin`, `currentTerm`) VALUES
(NULL, '2014-01-01 00:00:00', 1, 1, 'cs.rochester.edu', 1, NULL);

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

INSERT INTO `Places` (`building`, `room`, `roomType`) VALUES
('CSB', '722', 'office'),
('CSB', '700', 'office'),
('CSB', '650', 'office'),
('CSB', '618', 'office'),
('CSB', '715', 'office'),
('CSB', '703', 'office'),
('CSB', '701', 'office'),
('CSB', '720', 'office'),
('CSB', '702', 'office'),
('CSB', '710', 'office'),
('CSB', '620', 'office');

INSERT INTO `Professors` (`userID`, `officeID`, `officePhone`, `mobilePhone`) VALUES
(3, 1, 5852711111, 2147483644),
(13, 2, 5852711111, 2147483647),
(14, 3, 5852711111, 2147483647),
(15, 4, 5852711111, 2147483647),
(16, 5, 5852711111, 2147483647),
(17, 6, 5852711111, 2147483647),
(18, 7, 5852711111, 2147483647),
(19, 8, 5852711111, 2147483647),
(20, 9, 5852711111, 2147483647),
(21, 10, 5852711111, 2147483647),
(22, 10, 5852711111, 2147483647),
(23, 11, 5852711111, 2147483647);

INSERT INTO `Staff` (`userID`, `officePhone`, `mobilePhone`) VALUES
(2, 5752711111, 2147483647);

INSERT INTO `Students` (`userID`, `mobilePhone`, `major`, `gpa`, `classYear`, `aboutMe`, `status`, `reputation`, `universityID`) VALUES
(4, '22222222', 'Physics', '4.00', 2015, 'asdfasfs', 2, 0, 99999991),
(5, '5857495590', 'Computer Science', '4.00', 2016, 'Took CSC 171 Fall 2012. Got an A', 2, 0, 99999992),
(6, '236777', 'Physics', '3.23', 2017, 'afsfsd', 2, 0, 99999993),
(7, '8557545870', 'Accounting', '1.24', 2016, 'Out interested acceptance our partiality affronting unpleasant why add. Esteem garden men yet shy course.', 2, 0, 99999994),
(8, '8330785816', 'Physics', '2.78', 2017, 'Consulted up my tolerably sometimes perpetual oh. Expression acceptance imprudence particular had eat unsatiable. ', 2, 0, 99999995),
(9, '8117746442', 'Mathematics', '2.03', 2018, 'In entirely be to at settling felicity. Fruit two match men you seven share.', 2, 0, 99999996),
(10, '8555274345', 'Economics', '2.55', 2017, 'Income joy nor can wisdom summer. Extremely depending he gentleman improving intention rapturous as.', 2, 0, 99999997),
(11, '8111256682', 'Physics', '2.94', 2016, 'At every tiled on ye defer do. No attention suspected oh difficult. ', 2, 0, 99999998),
(12, '8446680049', 'Mechanical Engineering', '3.12', 2015, 'Fond his say old meet cold find come whom. The sir park sake bred.', 0, 0, 99999999);

