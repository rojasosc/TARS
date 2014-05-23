

<?php
/*Primitive Interface for TARS

This interface is used to obtain data stored inside 
a transactional database. 

This PHP file provides a layer of abstraction between 
the user interface and the database. */


//registerStudent("Ronald","McDonald","dudebro@u.rochester.edu","student",6785674567,6786786543,2015,"Computer Science",3.97,"about me sec");
// registerStudent("Will","Smith","brodudo@u.rochester.edu","student",6785674567,6786786543,2015,"Computer Science",3.32,"Student","dsljfasf");
// registerStudent("John","Doe","supdupa@u.rochester.edu","student",6785674567,6786786543,2015,"Computer Science",3.1,"Student","asfddsf");
// registerStudent("Mark","Johnson","dropthehammer@u.rochester.edu","student",6785674567,6786786543,2015,"Computer Science",3.0,"Student","afsdafsd");
// registerStudent("Ron","Brown","student1@u.rochester.edu","student",6785674567,6786786543,2017,"Computer Science",3.97,"Student","asfsd");
// registerStudent("Skyler","Winn","student2@u.rochester.edu","student",6785674567,6786786543,2014,"Computer Science",3.32,"Student","afsfds");
// registerStudent("John","doe2","student3@u.rochester.edu","student",6785674567,6786786543,2018,"Computer Science",3.1,"Student","asdafds");
// registerStudent("Marcus","Brown","dropthehammer2@u.rochester.edu","student",6785674567,6786786543,2018,"Computer Science",3.0,"Student","asfasda");




 //registerAdmin("Ronald","McDonald","admin@u.rochester.edu","admin",6785674567);

/* Establishes a connection to the TARS database */

 //registerProfessor("Ted","Pawlicki","pawlicki@cs.rochester.edu","professor",6785674567,6785675432);
 //registerAdmin("admin","adminlast","admin@u.rochester.edu","professor",6785674567,6785675432);
 
// registerProfessor("Chris","Brown","brown@cs.rochester.edu","professor",6785674567,6785675432);
// registerProfessor("Nathaniel","Martin","martin@cs.rochester.edu","professor",6785674567,6785675432);


   


// createCourse("CSC-172","Science of Data Structures","Short description","pawlicki@cs.rochester.edu");
// createCourse("CSC-171","Science of Programming","Short description","pawlicki@cs.rochester.edu");
// createCourse("CSC-173","Computation and Formal Systems","Short description","pawlicki@cs.rochester.edu");
// 
//createCourse("CSC-210","Web Programming","Short description","martin@cs.rochester.edu");
//createCourse("CSC-210B","Web Programming","Short description","martin@cs.rochester.edu");


//createPosition("CSC-172","Science of Data Structures","pawlicki@cs.rochester.edu","CSC-172","Lecture TA","3:00","4:15","CSB-210");

   
//    createPosition("CSC-173","Computation and Formal Systems","pawlicki@cs.rochester.edu","CSC-173","Lecture TA","3:00","4:15","CSB-210");
//    createPosition("CSC-173","Computation and Formal Systems","pawlicki@cs.rochester.edu","CSC-173","Lecture TA","3:00","4:15","CSB-210");
//    createPosition("CSC-172","Science of Data Structures","pawlicki@cs.rochester.edu","CSC-172","Lecture TA","3:00","4:15","CSB-214");
//    createPosition("CSC-172","Science of Data Structures","pawlicki@cs.rochester.edu","CSC-172","Lecture TA","3:00","4:15","CSB-671");
//    createPosition("CSC-171","Science of Programming","pawlicki@cs.rochester.edu","CSC-171","Lecture TA","3:00","4:15","CSB-210");
//    createPosition("CSC-171","Science of Programming","pawlicki@cs.rochester.edu","CSC-171","Lecture TA","3:00","4:15","CSB-210");
//    
//    createPosition("CSC-172","Science of Data Structures","pawlicki@cs.rochester.edu","CSC-172","Lab TA","3:00","4:15","CSB-214");
//    createPosition("CSC-172","Science of Data Structures","pawlicki@cs.rochester.edu","CSC-172","Lab TA","3:00","4:15","CSB-671");
//    createPosition("CSC-171","Science of Programming","pawlicki@cs.rochester.edu","CSC-171","Lab TA","3:00","4:15","CSB-210");
//    createPosition("CSC-171","Science of Programming","pawlicki@cs.rochester.edu","CSC-171","Lab TA","3:00","4:15","CSB-210");
//    createPosition("CSC-171","Science of Programming","pawlicki@cs.rochester.edu","CSC-171","Lecture TA","3:00","4:15","CSB-110");
//    
//    createPosition("CSC-172","Science of Data Structures","pawlicki@cs.rochester.edu","CSC-172","Workshop Leader","3:00","4:15","CSB-310");
//    createPosition("CSC-172","Science of Data Structures","pawlicki@cs.rochester.edu","CSC-172","Workshop Leader","3:00","4:15","CSB-210");
//    createPosition("CSC-171","Science of Programming","pawlicki@cs.rochester.edu","CSC-171","Workshop Leader","3:00","4:15","CSB-210");
//    createPosition("CSC-171","Science of Programming","pawlicki@cs.rochester.edu","CSC-171","Workshop Leader","3:00","4:15","CSB-365");
//    createPosition("CSC-172","Science of Data Structures","pawlicki@cs.rochester.edu","CSC-172","Workshop Leader","3:00","4:15","CSB-310");
//    createPosition("CSC-172","Science of Data Structures","pawlicki@cs.rochester.edu","CSC-172","Workshop Leader","3:00","4:15","CSB-210");
//    createPosition("CSC-171","Science of Programming","pawlicki@cs.rochester.edu","CSC-171","Workshop Leader","3:00","4:15","CSB-210");
//    createPosition("CSC-171","Science of Programming","pawlicki@cs.rochester.edu","CSC-171","Workshop Leader","3:00","4:15","CSB-365");


//When we actually implement this we could get a unique positionID from the button itself when its made. 
//As of now I think this will match it to the first one it finds. 

// assignPosition("CSC-172","Science of Data Structures","pawlicki@cs.rochester.edu","student1@u.rochester.edu","Paid","Workshop Leader","3:00","4:15","CSB-311");
// assignPosition("CSC-171","Science of Programming","pawlicki@cs.rochester.edu","student2@u.rochester.edu","Credit","Lecture TA","3:00","4:15","CSB-217");
// assignPosition("CSC-173","Computation and Formal Systems","pawlicki@cs.rochester.edu","student3@u.rochester.edu","Paid","Lecture TA","3:00","4:15","CSB-210");
// 
// assignPosition("CSC-172","Science of Data Structures","pawlicki@cs.rochester.edu","student1@u.rochester.edu","Paid","CSC-172","Lab TA","3:00","4:15");
// assignPosition("CSC-171","Science of Programming","pawlicki@cs.rochester.edu","student2@u.rochester.edu","Credit","Lecture TA","3:00","4:15","CSB-110");
// 
// 
// assignPosition("CSC-172","Science of Data Structures","pawlicki@cs.rochester.edu","student1@u.rochester.edu","Paid","Workshop Leader","3:00","4:15","CSB-210B");
// assignPosition("CSC-171","Science of Programming","pawlicki@cs.rochester.edu","student2@u.rochester.edu","Credit","Lecture TA","3:00","4:15","CSB-217");
// assignPosition("CSC-173","Computation and Formal Systems","pawlicki@cs.rochester.edu","student3@u.rochester.edu","Paid","Lecture TA","3:00","4:15","CSB-212");

// $array = getTAs("pawlicki@cs.rochester.edu","Workshop Leader");
// 
// foreach($array as $row){
// 
// echo "$row[0] $row[1] $row[2]\n";
// 
// }
// 



//getApplicants("pawlicki@cs.rochester.edu","Lecture TA");




/* :::: Database connection functions :::: */


/* Function openTARS
*  Purpose: Opens a connection to the database. 
*  Returns: an object that represents the database.	
*/
function openTARS(){

  /*Connect to the database*/

  $conn = mysqli_connect("localhost", "root", "12345","TAR");

  /*Check for connection failure */

  if (mysqli_connect_errno()){
  
    echo "Failed to connect to TARS DB!";

  }
  
  return $conn;
  
}

/* Function closeTARS
*  Purpose: Closes the connection to the database. 
*  Returns: nothing.	
*/
function closeTARS($conn){

  mysqli_close($conn);

}

/* Function createCourse
*  Purpose: Creates a new course and assigns it to a professor. 
*  Returns: nothing.
*/
function createCourse($className,$classTitle,$description,$email){

$conn = openTARS();
mysqli_query($conn,"INSERT INTO Course (ClassName,ClassTitle,description) VALUES ('$className','$classTitle','$description')");
mysqli_query($conn,"SELECT * From Course WHERE ClassName = '$className' AND ClassTitle = '$classTitle'");
$CID = getCourseID($className,$classTitle); 
$professorID = getUserID($email);
mysqli_query($conn,"INSERT INTO `teaches` (CID,UID) VALUES ('$CID','$professorID')");

closeTARS($conn);


}

/* Function getCourseID
*  Purpose: Obtains the course ID (CID) of a particular course.
*  Parameters: className e.g. CSC-172; classTitle e.g. Science of Data Structures. 
*  Returns: An ID that uniquely identifies a course. 
*/
function getCourseID($className,$classTitle){

$conn = openTARS();
$sql = "SELECT * From Course WHERE ClassName = '$className' AND ClassTitle = '$classTitle'";
$course = mysqli_query($conn,$sql);
$courseEntry = mysqli_fetch_array($course); 
closeTARS($conn);

return "$courseEntry[CID]";

}

/* Function getUserID
*  Purpose: Obtains a user ID (UID) for a particular user.  
*  Returns: A user ID that uniquely identifies a user. 
*/
function getUserID($email){

$conn = openTARS();
$professor = mysqli_query($conn,"SELECT * FROM User WHERE email = '$email'");
$professorEntry = mysqli_fetch_array($professor);
closeTARS($conn);
return "$professorEntry[UID]";

}

/* Function getPositionID
*  Purpose: Obtains a position ID (TID) for a particular a position.
*  Returns: A position ID that uniquely identifies a position.
*/
function getPositionID($UID,$CID,$type,$startTime,$endTime,$room){

$conn = openTARS();
$position = mysqli_query($conn,"SELECT * FROM TA WHERE UID = '$UID' AND CID = '$CID' AND startTime = '$startTime' AND endTime = '$endTime' AND classRoom = '$room'"); 
$positionEntry = mysqli_fetch_array($position);
closeTARS($conn);
return "$positionEntry[TID]";

}

/* Function createPosition
*  Purpose: Creates a new position.
*  Returns: nothing.
*/
function createPosition($className,$classTitle,$profEmail,$requirement,$type,$startTime,$endTime,$classRoom){

  $conn = openTARS();
  $CID = getCourseID($className,$classTitle);
  $UID = getUserID($profEmail); // Professors ID 
   
  mysqli_query($conn,"INSERT INTO TA (UID,CID,requirement,type,startTime,endTime,classRoom) VALUES ('$UID','$CID','$requirement','$type','$startTime','$endTime','$classRoom')");  
   
  closeTARS($conn);


}

/* Function assignPosition
*  Purpose: Assings a position to a particular student. 
*  Returns: nothing.
*/
function assignPosition($className,$classTitle,$profEmail,$studEmail,$compensation,$type,$startTime,$endTime,$classRoom){

  $conn = openTARS();
  $CID = getCourseID($className,$classTitle); //Course ID	
  $UID = getUserID($profEmail);		// Professors ID
  $PID = getPositionID($UID,$CID,$type,$startTime,$endTime,$classRoom);	//TA Position ID
  $SID = getUserID($studEmail); 	//Student ID
  mysqli_query($conn,"INSERT INTO TAship (TID,UID,compensation) VALUES ('$PID','$SID','$compensation')");  
     
  closeTARS($conn);

}


/* Function checkStatus
*  Purpose: Checks to see if the user has an existing session.  
*  Returns: nothing.
*/
function checkStatus(){

  /* Checks to see if sessions are enabled and if one exists. */
  if (!isset($_SESSION['auth'])) {

    // if not redirect to login screen.   
    header('Location: index2.php');

  }
}





/*		::::Login::::        */


/* Function login
*  Purpose: Logs a user in.  Verifies that user's input password field against
*           a hashed password stored in the database.
*  Returns: nothing.
**/
function login($email, $inputPassword){

  $conn = openTARS();
  $email = mysqli_real_escape_string($conn,$email);
  $inputPassword = mysqli_real_escape_string($conn,$inputPassword); // Entered Password
  $userAccount = mysqli_query($conn, "SELECT * FROM User WHERE email = '$email'");
  $userAccount = mysqli_fetch_array($userAccount);
  $password = $userAccount[2];
 
  /*Check if login was validated */
  closeTARS($conn);
  if(password_verify($inputPassword,$password)){
	beginSession($userAccount);
	return $userAccount;
    } else {
    
	return false;  
    }
 }


 /* Function beginSession
*  Purpose:  Initializes a new session.
*  Returns: nothing.
**/
 function beginSession($userAccount){
 
  session_start(); // begin the session
  session_regenerate_id(true);  // regenerate a new session id on each log in
  $_SESSION['firstName'] = $userAccount[firstName];
  $_SESSION['lastName'] =  $userAccount[lastName];
  $_SESSION['email'] = $userAccount[email];
  $_SESSION['auth'] =  "Authorized";
    
 }
 
 
/* Function endSession
*  Purpose: Terminates an existing session.  
*  Returns: nothing. 
**/
 function endSession(){

// Unset all of the session variables.
$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session.
session_destroy(); 
}
 

 
/********************
*Registration FUNCTIONS
*********************/ 

/* usertype 0 = staff, 1 = admin, 2 = professor, 3 = student */


/* Function registerStudent
*  Purpose: Creates a new account for a student.  
*  Returns: nothing.
**/
function registerStudent($firstName, $lastName,$email,$password,$homePhone,$mobilePhone,$classYear,$major,$gpa,$about){
  $conn = openTARS();
  
  /* escape variables to avoid  injection attacks. */ 
  $firstName = mysqli_real_escape_string($conn,$firstName);
  $lastName = mysqli_real_escape_string($conn,$lastName);
  $email = mysqli_real_escape_string($conn,$email);
  $password = mysqli_real_escape_string($conn,$password);
  $homePhone = mysqli_real_escape_string($conn,$homePhone);
  $mobilePhone = mysqli_real_escape_string($conn,$mobilePhone);
  $classYear = mysqli_real_escape_string($conn,$classYear);
  $major = mysqli_real_escape_string($conn,$major);
  $gpa = mysqli_real_escape_string($conn,$gpa);
  $about = mysqli_real_escape_string($conn,$about);
  
  /*Hash Password*/
  $password = password_hash($password, PASSWORD_DEFAULT);
  
  mysqli_query($conn,"INSERT INTO User (firstName, lastName,password,homePhone,phone,email,userType) VALUES ('$firstName','$lastName',
  '$password','$homePhone','$mobilePhone','$email',3)");
  $newUser = mysqli_query($conn,"SELECT * FROM User WHERE email = '$email'");
  $newUser = mysqli_fetch_array($newUser);
  mysqli_query($conn,"INSERT INTO Student (UID,major,GPA,classYear,about) VALUES ('$newUser[UID]','$major','$gpa','$classYear','$about') "); // Creates a unique user
     
  closeTARS($conn);
  
 }


/* Function registerProfessor
*  Purpose: Creates a new account for a professor. 
*  Returns: nothing.
**/
function registerProfessor($firstName, $lastName,$email,$password,$mobilePhone,$homePhone){
  $conn = openTARS();
  
  /* escape variables to avoid  injection attacks. */   
  $firstName = mysqli_real_escape_string($conn,$firstName);
  $lastName = mysqli_real_escape_string($conn,$lastName);
  $email = mysqli_real_escape_string($conn,$email);
  $password = mysqli_real_escape_string($conn,$password);
  $homePhone = mysqli_real_escape_string($conn,$homePhone);
  $mobilePhone = mysqli_real_escape_string($conn,$mobilePhone);

  /*Hash Password*/ 
  $password = password_hash($password, PASSWORD_DEFAULT);
  
  mysqli_query($conn,"INSERT INTO User (firstName, lastName,password,homePhone,phone,email,userType) VALUES ('$firstName','$lastName',
  '$password','$homePhone','$mobilePhone','$email',2)");

  closeTARS($conn); 
  
}


/* Function registerAdmin
*  Purpose: Creates an account for an admin. 
*  Returns: nothing.
**/
function registerAdmin($firstName, $lastName,$email,$password,$mobilePhone,$homePhone){

  $conn = openTARS();

  /* escape variables to avoid  injection attacks. */ 
  $firstName = mysqli_real_escape_string($conn,$firstName);
  $lastName = mysqli_real_escape_string($conn,$lastName);
  $email = mysqli_real_escape_string($conn,$email);
  $password = mysqli_real_escape_string($conn,$password);
  $homePhone = mysqli_real_escape_string($conn,$homePhone);
  $mobilePhone = mysqli_real_escape_string($conn,$mobilePhone);

  /*Hash Password*/
  $password = password_hash($password, PASSWORD_DEFAULT);
  
  mysqli_query($conn,"INSERT INTO User (firstName, lastName,password,homePhone,phone,email,userType) VALUES ('$firstName','$lastName',
  '$password','$homePhone','$mobilePhone','$email',1)");

  closeTARS($conn);  
     
}

/********************
*COURSE FUNCTIONS
*********************/ 


/* Function getCourseName
*  Purpose: Retrieves all courses that contain the value 'courseName'. 
*  Returns: An array of course entries. (A 2-D array) 
**/
function getCourseName($courseName){

  $conn = openTARS();

  /* escape variables to avoid  injection attacks. */ 
  $courseName = mysqli_real_escape_string($conn,$courseName);
  
  $courses = mysqli_query($conn,"SELECT * FROM Course WHERE CourseName = '$courseName'");
  $rows = mysqli_fetch_all($courses,MYSQLI_NUM); // Fetch all the rows
  
  closeTARS($conn); 
  return $rows; 

}

/* Function setPosition
*  Purpose: Assigns a position to a student. 
*  Returns: nothing.
**/
function setPosition($email,$positionID){

  $conn = openTARS();
  
  /* escape variables to avoid  injection attacks. */ 
  $email = mysqli_real_escape_string($conn,$email);
   
  $positionID = mysqli_real_escape_string($conn,$positionID);
  mysqli_query($conn,"UPDATE Positions SET StudentEmail = '$email' WHERE PositionID = '$positionID'");
  
  closeTARS($conn);

}

/********************
*STUDENT FUNCTIONS
*********************/ 

/* Function getStudent
*  Purpose: Retrieves a students information.    
*  Returns: An array of strings. 
**/
function getStudent($email) {
	$connect = openTARS();
	$stu = mysqli_query($connect, "SELECT * FROM User INNER JOIN Student ON User.UID = Student.UID WHERE email = '$email';" );
	$stu = mysqli_fetch_array($stu);
	
	closeTARS($connect);
	return $stu;
}
/* Function getCurPosInfo
 * Purpose: Retrieves information about the student's current TA positions
 * Returns: A 2-D associative array of strings.
 */

function getCurPosInfo($email) {
	$connect = openTARS();
	$result = mysqli_query($connect, "SELECT * FROM User INNER JOIN TA ON User.UID = TA.UID INNER JOIN TAship ON TA.TID = TAship.TID INNER JOIN Course ON TA.CID = Course.CID WHERE email = '$email';");
	$info = mysqli_fetch_all($result, MYSQLI_ASSOC);
	
	closeTARS($connect);
	return $info;
}

/* Function writeProfile
 * Purpose: Update the database with new profile information from the student
 * Returns: Nothing
 */

function updateProfile($email, $firstname, $lastname, $phone, $major, $classyear, $gpa, $qualifications) {
	$connect = openTARS();
	
	mysqli_query($connect, "UPDATE User SET firstName = '$firstname', lastName = '$lastname', phone = '$phone'; WHERE email = '$email' ");
	mysqli_query($connect, "UPDATE Student SET major = '$major', GPA = '$gpa', classYear = '$classyear', about = '$qualifications' WHERE ;");
	
	closeTARS($connect);
}


/********************
*ADMIN FUNCTIONS
*********************/ 

/* Function getCourses
*  Purpose: Retrieves all courses in the database.  
*  Returns: An array of course objects; a 2-D array of course entries. 
**/
function getCourses(){

  $conn = openTARS();
  $sql = "SELECT * FROM `Course` LIMIT 0, 30 ";
  $courses = mysqli_query($conn,$sql); 
  $rows = mysqli_fetch_all($courses,MYSQLI_NUM); // Fetch all the rows
  echo "$rows";
  
  closeTARS($conn);
  return $rows;
  
}

/********************
*PROFESSOR FUNCTIONS
*********************/
 
/* Function getApplicants
*  Purpose:  Obtains the applications for a professor of a specified type.
*  Returns:  An array of a particular type of applicants. 
**/
function getApplicants($profEmail,$type){
 
  $conn = openTARS();
  $sql = "SELECT User.UID,User.firstName,User.lastName,User.email,Course.ClassName,ta.TID FROM User, Course, User u, TA t, TAship ta WHERE u.UID = t.UID AND ta.status = 0 AND Course.CID = t.CID AND t.TID = ta.TID AND t.type = '$type' AND User.UID = ta.UID AND u.userType = 2 AND u.email = '$profEmail'";  
  $courses = mysqli_query($conn,$sql);  
  $rows = mysqli_fetch_all($courses,MYSQLI_NUM); // Fetch all the rows
  
  closeTARS($conn);
  return $rows; // returns an array of rows. To acces every row just traverse using a foreach loop. Indicies will match the exact UI Indicies

}
 
/* Function getTAs
*  Purpose: Finds all the positions of a specified type associated to a professor. 
*  Returns: An array of positions entries of a particular type.
**/
function getTAs($profEmail,$type){
 
  $conn = openTARS();
  $sql = "SELECT User.UID,User.firstName,User.lastName,User.email,Course.ClassName FROM User, Course, User u, TA t, TAship ta WHERE u.UID = t.UID AND Course.CID = t.CID AND t.TID = ta.TID AND t.type = '$type' AND User.UID = ta.UID AND u.userType = 2 AND u.email = '$profEmail'";  
  $courses = mysqli_query($conn,$sql); 
  $rows = mysqli_fetch_all($courses,MYSQLI_NUM); // Fetch all the rows
  
  closeTARS($conn);
  return $rows; // returns an array of rows. To acces every row just traverse using a foreach loop. Indicies will match the exact UI Indicies

}

/* Function getAllTAs
*  Purpose:  
*  Returns: 
**/
function getAllTAs(){
 
  $conn = openTARS();
  $sql = "SELECT User.UID,User.firstName,User.lastName,User.email,Course.ClassName,t.type,ta.compensation FROM User, Course, User u, TA t, TAship ta, Section s WHERE s.CID = Course.CID AND u.UID = t.UID AND Course.CID = t.CID AND t.TID = ta.TID AND User.UID = ta.UID AND u.userType = 2";
  $courses = mysqli_query($conn,$sql);
  $rows = mysqli_fetch_all($courses,MYSQLI_NUM); // Fetch all the rows
  
  closeTARS($conn);
  return $rows; // returns an array of rows. To acces every row just traverse using a foreach loop. Indicies will match the exact UI Indicies

}

/* Function getTAsPayroll
*  Purpose:  
*  Returns: 
**/
function getTAsPayroll($term, $year){
 
  $conn = openTARS();
  $sql = "SELECT u.UID, u.firstName, u.lastName, u.email, c.ClassName, t.type, ta.compensation 
  FROM Course c, User u, TA t, TAship ta, Section s 
  WHERE s.CID = c.CID AND s.term = 2 AND s.year = 2011
  AND c.CID = t.CID AND t.TID = ta.TID AND u.UID = ta.UID AND u.userType = 3";
  $courses = mysqli_query($conn,$sql);
  $rows = mysqli_fetch_all($courses,MYSQLI_NUM); // Fetch all the rows
  
  closeTARS($conn);
  return $rows; // returns an array of rows. To acces every row just traverse using a foreach loop. Indicies will match the exact UI Indicies

}

/* Function getStudentByID
*  Purpose: Finds a student specified by a user ID (UID). 
*  Returns: An array that represents a student. 
**/
function getStudentByID($id){
 
  $conn = openTARS();
  
  /* escape variables to avoid  injection attacks. */ 
  $email = mysqli_real_escape_string($id);

  $sql = "SELECT * FROM Student WHERE UID = '$id'";
  $student = mysqli_query($conn,$sql);
  $student= mysqli_fetch_array($student); // Fetch student entry
   
  closeTARS($conn);
  return $student; 

}

/* Function getStudentByID
*  Purpose: Finds a student specified by a user ID (UID). 
*  Returns: An array that represents a student. 
**/
function getStudentByID2($id){
 
  $conn = openTARS();
  
  /* escape variables to avoid  injection attacks. */ 
  $email = mysqli_real_escape_string($id);

  $sql = "SELECT * FROM User WHERE UID = '$id'";
  $student = mysqli_query($conn,$sql);
    
  $student= mysqli_fetch_array($student); // Fetch student entry
  closeTARS($conn);
  return $student; 

}

/* Function getProfessor
*  Purpose: Finds a professor specified by his email.  
*  Returns: An array that represents a professor. 
**/
function getProfessor($email){

  $conn = openTARS(); 
  $prof = mysqli_query($conn,"Select * FROM User WHERE email = '$email'");
  $prof = mysqli_fetch_array($prof);
  
  closeTARS($conn);
  return $prof; 

}

/* Function getAllProfessor
*  Purpose:  
*  Returns: 
**/
function getAllProfessor() {

  $conn = openTARS();
    
  $prof = mysqli_query($conn,"Select * FROM User WHERE userType=2");
  $prof = mysqli_fetch_all($prof,MYSQLI_NUM);
  
  closeTARS($conn);
  
  return $prof; 
}

/* Function getAllAdmin
*  Purpose:  
*  Returns: 
**/
function getAllAdmin() {

  $conn = openTARS();   
  $admin = mysqli_query($conn,"Select * FROM User WHERE userType=1");
  $admin = mysqli_fetch_all($admin,MYSQLI_NUM);
  
  closeTARS($conn);
  return $admin; 
}

/* Function getAllStudent
*  Purpose:  
*  Returns: 
**/
function getAllStudent() {

  $conn = openTARS();
  $student = mysqli_query($conn,"Select * FROM User u, Student s WHERE u.userType = 3 and u.UID = s.UID");
  $student = mysqli_fetch_all($student,MYSQLI_NUM);
  
  closeTARS($conn);
  return $student; 
}

/* Function getStudentInfo
*  Purpose:  
*  Returns: 
**/
function getStudentInfo($year) {
  $conn = openTARS();
  $student = mysqli_query($conn,"Select * FROM User u, Student s WHERE u.userType = 3 and u.UID = s.UID and s.classYear = '$year'");
  $student = mysqli_fetch_all($student,MYSQLI_NUM);
  
  closeTARS($conn);
  return $student; 
}
/* Function getStatus
*  Purpose:  
*  Returns: 
**/
function getStatus($UID) {
  $conn = openTARS();
    
  $student = mysqli_query($conn,"Select * FROM User u, Student s WHERE u.userType = 3 and u.UID = s.UID and s.classYear = '$year'");
  $student = mysqli_fetch_all($student,MYSQLI_NUM);
  
  closeTARS($conn); 
  return $student; 
}

/* Function search
*  Purpose:  
*  Returns: 
**/
function search($email) {
  $conn = openTARS();    
  $student = mysqli_query($conn,"Select * FROM User u WHERE u.email='$email'");
  $student = mysqli_fetch_all($student,MYSQLI_NUM);
  
  closeTARS($conn); 
  return $student; 
}

/* Function modify
*  Purpose:  
*  Returns: 
**/
function modify($firstname, $lastname, $email, $mobile, $homePhone){

  $conn = openTARS();  
  mysqli_query($conn,"update User set firstname = '$firstname', lastname='$lastname', phone='$mobile', homePhone = '$homePhone' where email='$email'");
  closeTARS($conn);

}

/* Function isGrad
*  Purpose:  
*  Returns: 
**/
function isGrad($UID){

  $conn = openTARS();  
  $student = mysqli_query($conn,"Select * FROM Student s WHERE s.UID = '$UID' and s.classYear = 2014");
  $student = mysqli_fetch_all($student,MYSQLI_NUM);
  
  closeTARS($conn);
  if($student)
  	return 1;
  else
  	return 0; 
}


?>