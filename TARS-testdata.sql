
SET FOREIGN_KEY_CHECKS=0;
TRUNCATE TABLE Teaches;
TRUNCATE TABLE Feedback;
TRUNCATE TABLE Assistantship;
TRUNCATE TABLE Positions;
TRUNCATE TABLE CourseSessions;
TRUNCATE TABLE Sessions;
TRUNCATE TABLE Courses;
TRUNCATE TABLE Terms;
TRUNCATE TABLE Staff;
TRUNCATE TABLE Professors;
TRUNCATE TABLE Students;
TRUNCATE TABLE Users;
TRUNCATE TABLE Places;
SET FOREIGN_KEY_CHECKS=1;

INSERT INTO `Terms` (`year`, `session`) VALUES
(2014, 'spring'),
(2014, 'fall'),
(2015, 'spring'),
(2015, 'fall');

INSERT INTO `Places` (`building`, `room`, `roomType`) VALUES
('HOYT', 'AUD', 'Lecture Hall'),
('GAVET', '202', 'Lecture Hall'),
('LATT', '201', 'Lecture Hall'),
('B&L', '106', 'Lecture Hall'),
('CSB', '722', 'Office'),
('CSB', '609', 'Office'),
('CSB', '608', 'Office'),
('CSB', '618', 'Office'),
('CSB', '715', 'Office');

INSERT INTO `Sessions` (`weekday`, `startTime`, `endTime`) VALUES
('W', '10:25', '11:40'),
('F', '10:25', '11:40'),
('T', '14:00', '15:15'),
('R', '14:00', '15:15'),
('T', '16:50', '18:05'),
('R', '16:50', '18:05');

INSERT INTO `Courses` (`crn`, `department`, `courseNumber`, `courseTitle`, `website`, `termID`) VALUES
(30105, 'CSC', '171', 'The Science of Programming', 'sdlfkjlkj', 2),
(30279, 'CSC', '172', 'The Science of Data Structures', 'website', 2),
(30302, 'CSC', '173', 'Computation and Formal Systems', 'http://cs.rochester.edu/u/brown/173/', 2),
(76183, 'CSC', '210', 'Web Programming', 'website', 2),
(30365, 'CSC', '254', 'Programming Language Design & Implementation', 'website', 2),
(29364, 'CSC', '172', 'The Science of Data Structures', 'website', 1);

INSERT INTO `CourseSessions` (`courseID`, `sessionID`, `placeID`) VALUES
(1, 1, 1),
(1, 2, 1),
(2, 1, 2),
(2, 2, 2),
(3, 3, 3),
(3, 4, 3),
(4, 5, 4),
(4, 6, 4);

INSERT INTO `Users` (`email`, `password`, `firstName`, `lastName`, `type`) VALUES
('pawlicki@cs.rochester.edu', '$2y$10$lRWCMEejbmjj02hIwfMjvuATC2q2yC7eiH6uySm1RYciA0JxuyTQS', 'Ted', 'Pawlicki', 1),
('orojas@u.rochester.edu', '$2y$10$zbMUGaKAGfmvxWlt8HIsa.GzYTAC/khAy846xHsPSCecfaaNzkZ2K', 'Oscar', 'Rojas', 0),
('jan2@u.rochester.edu', '$2y$10$.VAc/y1rCFGbX3zTBJycpe9FP6EUj.bshNtBmBztx4EHDEtoUuz0G', 'Jinze', 'An', 0),
('student1@u.rochester.edu', '$2y$10$3H.hdVdHGY2BiyrqkijuzOjtr4SPw0W8wrj0hDZYLuwOl.6vkOGTq', 'Elena', 'Walker', 0),
('student2@u.rochester.edu', '$2y$10$3HUHQmAsgaIZ43BLZalI1unUhiLLKcKvwehZkpRi5GAkmm99uUhtO', 'Karel', 'Aristides', 0),
('student3@u.rochester.edu', '$2y$10$M08w4WXTfECX8wkeuCIvnuYKsbHOUtmQwmDhs.tL43CUWAWfDzb1e', 'Enlil', 'Amayas', 0),
('student4@u.rochester.edu', '$2y$10$JzcmmCVDtHIDVpDw/g7JuOqAx128LSwDJYS5dsKZx5HucDl6Fgndq', 'Eli', 'Edmund', 0),
('student5@u.rochester.edu', '$2y$10$b.KOpfmt5iRo4ix5/sMm1.hJ/WWmmgk7CYMKjEhnq2OMDGiBTKK1m', 'Thelonius', 'Afif', 0),
('student6@u.rochester.edu', '$2y$10$Xlrni1bV2MxbUb2mwfWbPujxB3/c6mXeAgrrrf2vvwlupIRy/DJz6', 'Stig', 'Euaristos', 0),
('student7@u.rochester.edu', '$2y$10$Ca/fSp85GnQqxZ3x.5alVOdLJFoN.R8MaiIcKyW1OmytF6ow.PLhO', 'Chidubern', 'Juho', 0),
('brown@cs.rochester.edu', '$2y$10$5h0ojOKkjACK9nCwiRp2/eW2obUaFGF5IviluR/x3f.wia0w9s9iK', 'Christopher', 'Brown', 1),
('martin@cs.rochester.edu', '$2y$10$OgCgpj81LO5tP4RDTM5g9ONfBGu8EK9dcowOx4dgTehN.HNfKShCe', 'Nathaniel', 'Martin', 1),
('lane@cs.rochester.edu', '$2y$10$db.fWC.VtOiYlVOe.OVVrOjUL1bpxVH9RzROE2trkqAaGCrZJNfme', 'Lane', 'Hemaspaandra', 1),
('scott@cs.rochester.edu', '$2y$10$w1ZOnPXxdDtUUmxuDWewt.WSnkDOJqeLwLzlexzm0oGQFRwwVYcLO', 'Michael', 'Scott', 1),
('marty@cs.rochester.edu', '$2y$10$mracYEf6CHNZjOzCANSP0ehFI1pIo.o2CQAqmtHxgmMpuLlvrAZqK', 'Marty', 'Guenther', 2);

INSERT INTO `Professors` (`userID`, `officeID`, `officePhone`, `mobilePhone`) VALUES
(1, 5, 2147483647, 2147483644),
(11, 6, 2147483647, 2147483647),
(12, 7, 2147483647, 2147483647),
(13, 8, 2147483647, 2147483647),
(14, 9, 2147483647, 2147483647);

INSERT INTO `Staff` (`userID`, `officePhone`, `mobilePhone`) VALUES
(15, 2147483647, 2147483647);

INSERT INTO `Students` (`userID`, `mobilePhone`, `major`, `gpa`, `classYear`, `aboutMe`, `status`, `reputation`) VALUES
(2, '22222222', 'Physics', '4.00', 2015, 'asdfasfs', 2, 0),
(3, '5857495590', 'Computer Science', '4.00', 2016, 'Took CSC 171 Fall 2012. Got an A', 2, 0),
(4, '236777', 'Physics', '3.23', 2017, 'afsfsd', 2, 0),
(5, '8557545870', 'Accounting', '1.24', 2016, 'Out interested acceptance our partiality affronting unpleasant why add. Esteem garden men yet shy course.', 2, 0),
(6, '8330785816', 'Physics', '2.78', 2017, 'Consulted up my tolerably sometimes perpetual oh. Expression acceptance imprudence particular had eat unsatiable. ', 2, 0),
(7, '8117746442', 'Mathematics', '2.03', 2018, 'In entirely be to at settling felicity. Fruit two match men you seven share.', 2, 0),
(8, '8555274345', 'Economics', '2.55', 2017, 'Income joy nor can wisdom summer. Extremely depending he gentleman improving intention rapturous as.', 2, 0),
(9, '8111256682', 'Physics', '2.94', 2016, 'At every tiled on ye defer do. No attention suspected oh difficult. ', 2, 0),
(10, '8446680049', 'Mechanical Engineering', '3.12', 2015, 'Fond his say old meet cold find come whom. The sir park sake bred.', 0, 0);

INSERT INTO `Feedback` (`studentID`, `commenterID`, `dateTime`, `comment`) VALUES
(3, 14, '2014-04-06 13:26:53', 'test post, please ignore');

INSERT INTO `Positions` (`courseID`, `professorID`, `time`, `posType`) VALUES
(1, 1, 'FLEXIBLE', 'Grader'),
(1, 11, 'FLEXIBLE', 'Grader'),
(1, 11, 'FLEXIBLE', 'Grader'),
(1, 11, 'TBD', 'Workshop Leader'),
(1, 1, 'FLEXIBLE', 'Grader'),
(2, 11, 'FLEXIBLE', 'Grader'),
(2, 11, 'FLEXIBLE', 'Grader'),
(2, 11, 'FLEXIBLE', 'Grader'),
(1, 11, '1400 - 1600', 'Lab TA'),
(1, 11, '1500 - 1700', 'Lab TA'),
(1, 11, '1600 - 1800', 'Lab TA'),
(3, 1, '800 - 1000', 'Lab TA'),
(3, 1, '1100 - 1300', 'Lab TA'),
(3, 1, '2000 - 2200', 'Lab TA'),
(3, 1, '2300 - 100', 'Lab TA'),
(1, 11, '1500 - 1700', 'Lab TA'),
(4, 12, '900 - 1100', 'Lab TA'),
(4, 12, '1100 - 1300', 'Lab TA'),
(4, 12, '1700 - 1900', 'Lab TA'),
(4, 12, '200 - 400', 'Lab TA'),
(3, 1, '600 - 800', 'Lab TA'),
(3, 1, '000 - 200', 'Lab TA'),
(3, 1, '400 - 600', 'Lab TA'),
(5, 13, 'FLEXIBLE', 'Grader'),
(5, 13, 'FLEXIBLE', 'Grader'),
(5, 13, '500 - 700', 'Lab TA'),
(5, 13, '1200 - 1400', 'Lab TA'),
(6, 14, 'TBD', 'Workshop Leader'),
(6, 14, 'FLEXIBLE', 'Grader'),
(6, 14, '1234 - 1634', 'Lab TA');

INSERT INTO `Teaches` (`courseID`, `professorID`) VALUES
(1, 1),
(2, 11),
(3, 1),
(4, 12),
(5, 13),
(6, 14);

INSERT INTO `Applications` (`positionID`, `studentID`, `compensation`, `appStatus`, `qualifications`) VALUES
(1, 2, 'pay', 2, ''),
(2, 2, 'credit', 2, ''),
(3, 2, 'pay', 2, ''),
(5, 3, 'pay', 3, ''),
(7, 3, 'credit', 3, ''),
(14, 2, 'pay', 3, ''),
(19, 2, 'credit', 3, ''),
(26, 3, 'pay', 3, ''),
(25, 3, 'credit', 3, ''),
(18, 3, 'pay', 0, 'insta-approve!'),
(30, 3, 'pay', 0, 'Let''s make sure this works.....'),
(12, 3, 'pay', 0, 'triggerrrrrrrr!'),
(8, 3, 'pay', 0, 'pls'),
(9, 3, 'pay', 0, 'did i fix it?'),
(3, 3, 'pay', 0, 'does it work in ff?'),
(1, 3, 'pay', 0, 'this should work here.'),
(2, 3, 'pay', 0, 'yo what up im on ff'),
(4, 3, 'pay', 0, 'one last chance, chrome. before i fire you from the testing team.');

