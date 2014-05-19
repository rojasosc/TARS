

<?php
/**Primitive Interface for TARS

This interface is used to obtain data stored inside 
a transactional database. **/


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

/**WORKS**/
/*creates a new course and assigns it to a professor via email*/

function createCourse($className,$classTitle,$description,$email){

$conn = openTARS();



mysqli_query($conn,"INSERT INTO Course (ClassName,ClassTitle,description) VALUES ('$className','$classTitle','$description')");

mysqli_query($conn,"SELECT * From Course WHERE ClassName = '$className' AND ClassTitle = '$classTitle'");
$CID = getCourseID($className,$classTitle); 
$professorID = getUserID($email);


mysqli_query($conn,"INSERT INTO `teaches` (CID,UID) VALUES ('$CID','$professorID')");

closeTARS($conn);


}

/*Returns the courseID (CID) given the className e.g. CSC-172 and the classTitle */
/*WORKS*/
function getCourseID($className,$classTitle){

$conn = openTARS();


$sql = "SELECT * From Course WHERE ClassName = '$className' AND ClassTitle = '$classTitle'";

$course = mysqli_query($conn,$sql);

$courseEntry = mysqli_fetch_array($course); 



closeTARS($conn);

return "$courseEntry[CID]";

}

/*Returns the userID (UID) given the user's email*/
/*WORKS*/

function getUserID($email){

$conn = openTARS();

$professor = mysqli_query($conn,"SELECT * FROM User WHERE email = '$email'");
  
$professorEntry = mysqli_fetch_array($professor);


closeTARS($conn);

return "$professorEntry[UID]";

}

/*Returns a TA Position ID (TID inside the TA Table)
*WORKS
*/
function getPositionID($UID,$CID,$type,$startTime,$endTime,$room){

$conn = openTARS();

$position = mysqli_query($conn,"SELECT * FROM TA WHERE UID = '$UID' AND CID = '$CID' AND startTime = '$startTime' AND endTime = '$endTime' AND classRoom = '$room'");
  
$positionEntry = mysqli_fetch_array($position);


closeTARS($conn);

return "$positionEntry[TID]";

}





/**Creates a new Position 
*WORKS
**/

function createPosition($className,$classTitle,$profEmail,$requirement,$type,$startTime,$endTime,$classRoom){

  $conn = openTARS();

  $CID = getCourseID($className,$classTitle);
  $UID = getUserID($profEmail); // Professors ID 
   
  mysqli_query($conn,"INSERT INTO TA (UID,CID,requirement,type,startTime,endTime,classRoom) VALUES ('$UID','$CID','$requirement','$type','$startTime','$endTime','$classRoom')");  
   
    
  closeTARS($conn);


}

/*Assigns a student to a TA Position
*WORKS This way is sloppy, we might need to seperate the type,times,and location into another table. 
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



function checkStatus(){


  /*Checks to see if sessions are enabled and if one exists.*/
  if (!isset($_SESSION['auth'])) {

    // if not redirect to login screen. 
    
    header('Location: index.php');


  }
}


function openTARS(){


  /*Connect to the database*/

  $conn = mysqli_connect("localhost", "root", "12345","TAR");


  /*Check for connection failure */

  if (mysqli_connect_errno()){
    
    echo "Failed to connect to TARS DB!";

  }
  
  return $conn;
  
}

/*Closes the connection to the TARS database */

function closeTARS($conn){

  mysqli_close($conn);

}


/*		::::Login::::

Uses secure PHP5 hashing functions to encrypt password.
We can decide a work cost constant later on.. */

function login($email, $inputPassword){

  $conn = openTARS();

  $email = mysqli_real_escape_string($conn,$email);

  $inputPassword = mysqli_real_escape_string($conn,$inputPassword); // Entered Password
 
  
  
  $userAccount = mysqli_query($conn, "SELECT * FROM User WHERE email = '$email'");
  
  $userAccount = mysqli_fetch_array($userAccount);
  
  $password = $userAccount[2];
 


  /*Check if login was validated */

  closeTARS($conn);
  if (password_verify($inputPassword,$password)) {
	beginSession($userAccount);
	return $userAccount;
    } else {
    
	return false;
    
    }
 
 }
 
 function logout(){
 
    endSession();
 
 }
 
 /*Starts a new session*/
 function beginSession($userAccount){
 
  session_start(); // begin the session
  
  session_regenerate_id(true);  // regenerate a new session id on each log in
  
  $_SESSION['firstName'] = $userAccount[firstName];
  $_SESSION['lastName'] =  $userAccount[lastName];
  $_SESSION['email'] = $userAccount[email];
  $_SESSION['auth'] =  "Authorized";
  
   
 }
 
 
 /*Ends the current session*/
 function endSession(){
 
 
 /**************************************
 *NOTE: This code was obtained directly
 *from the PHP5 Manual on ending sessions. 
 **************************************/
 
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

//usertype 0 = staff, 1 = admin, 2 = professor, 3 = student,


/*Student*/

/**WORKS **/

/*Registers a student for TARS*/
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


/*Professor Registration*/

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


/*Professor Registration*/

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

/*Course Access*/

/*Returns an array of all course entries <=> objects by Course Title e.g. CSC-172
/*CourseTitle will be the equivalent of CDCS course title i.e. the full name. 

/**WORKS **/

function getCourseName($courseName){

  $conn = openTARS();
  
  
  /* escape variables to avoid  injection attacks. */ 
  $courseName = mysqli_real_escape_string($conn,$courseName);
  
  $courses = mysqli_query($conn,"SELECT * FROM Course WHERE CourseName = '$courseName'");
  
  $rows = mysqli_fetch_all($courses,MYSQLI_NUM); // Fetch all the rows
  
  closeTARS($conn);
  
  return $rows; // returns an array of course objects (entries). 

}



/*Positions Access*/

/*Maps a student to an open position
$email is the students email
$positionID is the unique position identifier*/
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
//Returns an array of all the information on the student profile page
function getStudent($email) {
	$connect = openTARS();
	
	$stu = mysqli_query($connect, "SELECT * FROM User INNER JOIN Student ON User.UID = Student.UID WHERE email = '$email'" );
	$stu = mysqli_fetch_array($stu);
	closeTARS($connect);
	
	return $stu;
}


/********************
*ADMIN FUNCTIONS
*********************/ 


/*Returns all the courses*/

function getCourses(){

 
  
  $conn = openTARS();
  
  $sql = "SELECT * FROM `Course` LIMIT 0, 30 ";
  
  $courses = mysqli_query($conn,$sql);
    
  $rows = mysqli_fetch_all($courses,MYSQLI_NUM); // Fetch all the rows
  
  echo "$rows";
  
  closeTARS($conn);
  
  return $rows; // This is an array of rows. Look at the DB to decide what fields you need. 
  
  

}






// get term


/********************
*PROFESSOR FUNCTIONS
*********************/



/*Returns an array of all applicants associated to a professor
 by the email attribute.
 Takes the professor's email and the type of TA as parameters */
function getApplicants($profEmail,$type){
 
  $conn = openTARS();

  
  $sql = "SELECT User.UID,User.firstName,User.lastName,User.email,Course.ClassName,ta.TID FROM User, Course, User u, TA t, TAship ta WHERE u.UID = t.UID AND ta.status = 0 AND Course.CID = t.CID AND t.TID = ta.TID AND t.type = '$type' AND User.UID = ta.UID AND u.userType = 2 AND u.email = '$profEmail'";  
  $courses = mysqli_query($conn,$sql);
    
  $rows = mysqli_fetch_all($courses,MYSQLI_NUM); // Fetch all the rows
  
  
  closeTARS($conn);
  
  //echo $rows[2];
  
  return $rows; // returns an array of rows. To acces every row just traverse using a foreach loop. Indicies will match the exact UI Indicies

}


/*Returns an array of all positions that are associated to a professor
 by the email attribute.
 Takes the professor's email and the type of TA as parameters */
function getTAs($profEmail,$type){
 
  $conn = openTARS();

  
  $sql = "SELECT User.UID,User.firstName,User.lastName,User.email,Course.ClassName FROM User, Course, User u, TA t, TAship ta WHERE u.UID = t.UID AND Course.CID = t.CID AND t.TID = ta.TID AND t.type = '$type' AND User.UID = ta.UID AND u.userType = 2 AND u.email = '$profEmail'";  
  $courses = mysqli_query($conn,$sql);
    
  $rows = mysqli_fetch_all($courses,MYSQLI_NUM); // Fetch all the rows
  
  
  closeTARS($conn);
  
  return $rows; // returns an array of rows. To acces every row just traverse using a foreach loop. Indicies will match the exact UI Indicies

}



function getAllTAs(){
 
  $conn = openTARS();

  $sql = "SELECT User.UID,User.firstName,User.lastName,User.email,Course.ClassName,t.type,ta.compensation FROM User, Course, User u, TA t, TAship ta, Section s WHERE s.CID = Course.CID AND u.UID = t.UID AND Course.CID = t.CID AND t.TID = ta.TID AND User.UID = ta.UID AND u.userType = 2";
  $courses = mysqli_query($conn,$sql);
    
  $rows = mysqli_fetch_all($courses,MYSQLI_NUM); // Fetch all the rows
  
  
  closeTARS($conn);
  
  return $rows; // returns an array of rows. To acces every row just traverse using a foreach loop. Indicies will match the exact UI Indicies

}

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






/*Returns entry from the Student Table by UID
*WORKS
*/

function getStudentByID($id){
 
  $conn = openTARS();
  
  /* escape variables to avoid  injection attacks. */ 
  $email = @mysqli_real_escape_string($id);
  /*
  $sql = "SELECT Students.FirstName,Students.LastName,Students.Email,Positions.ClassTitle \n"
    . "FROM Positions,Students\n"
    . "WHERE Positions.ProfessorEmail = ".$profEmail."\n"
    . "AND Positions.StudentEmail = Students.Email\n"
    . "AND Positions.PositionTitle = ".$type;
  */
  
  $sql = "SELECT * FROM Student WHERE UID = '$id'";
  $student = mysqli_query($conn,$sql);
    
  $student= mysqli_fetch_array($student); // Fetch student entry
  
  
  closeTARS($conn);
    
  return $student; 

}


function getStudentByID2($id){
 
  $conn = openTARS();
  
  /* escape variables to avoid  injection attacks. */ 
  $email = @mysqli_real_escape_string($id);
  /*
  $sql = "SELECT Students.FirstName,Students.LastName,Students.Email,Positions.ClassTitle \n"
    . "FROM Positions,Students\n"
    . "WHERE Positions.ProfessorEmail = ".$profEmail."\n"
    . "AND Positions.StudentEmail = Students.Email\n"
    . "AND Positions.PositionTitle = ".$type;
  */
  
  $sql = "SELECT * FROM User WHERE UID = '$id'";
  $student = mysqli_query($conn,$sql);
    
  $student= mysqli_fetch_array($student); // Fetch student entry
  
  
  closeTARS($conn);
    
  return $student; 

}




/**WORKS **/
/*Returns a professor entry (row) given his/her email address*/
function getProfessor($email){

  $conn = openTARS();
    
  $prof = mysqli_query($conn,"Select * FROM User WHERE email = '$email'");
  $prof = mysqli_fetch_array($prof);
  
  closeTARS($conn);
  
  return $prof; 


}

/*Reture all professor infor*/
function getAllProfessor() {

  $conn = openTARS();
    
  $prof = mysqli_query($conn,"Select * FROM User WHERE userType=2");
  $prof = mysqli_fetch_all($prof,MYSQLI_NUM);
  
  closeTARS($conn);
  
  return $prof; 
}

/*Reture all admin infor*/
function getAllAdmin() {

  $conn = openTARS();
    
  $admin = mysqli_query($conn,"Select * FROM User WHERE userType=1");
  $admin = mysqli_fetch_all($admin,MYSQLI_NUM);
  
  closeTARS($conn);
  
  return $admin; 
}

/*Reture all student infor*/
function getAllStudent() {

  $conn = openTARS();
    
  $student = mysqli_query($conn,"Select * FROM User u, Student s WHERE u.userType = 3 and u.UID = s.UID");
  $student = mysqli_fetch_all($student,MYSQLI_NUM);
  
  closeTARS($conn);
  
  return $student; 
}

function getStudentInfo($year) {
  $conn = openTARS();
    
  $student = mysqli_query($conn,"Select * FROM User u, Student s WHERE u.userType = 3 and u.UID = s.UID and s.classYear = '$year'");
  $student = mysqli_fetch_all($student,MYSQLI_NUM);
  
  closeTARS($conn);
  
  return $student; 
}

function getStatus($UID) {
  $conn = openTARS();
    
  $student = mysqli_query($conn,"Select * FROM User u, Student s WHERE u.userType = 3 and u.UID = s.UID and s.classYear = '$year'");
  $student = mysqli_fetch_all($student,MYSQLI_NUM);
  
  closeTARS($conn);
  
  return $student; 
}

function search($email) {
	$conn = openTARS();
    
  $student = mysqli_query($conn,"Select * FROM User u WHERE u.email='$email'");
  $student = mysqli_fetch_all($student,MYSQLI_NUM);
  
  closeTARS($conn);
  
  return $student; 
}

function modify($firstname, $lastname, $email, $mobile, $homePhone){
$conn = openTARS();
    
  mysqli_query($conn,"update User set firstname = '$firstname', lastname='$lastname', phone='$mobile', homePhone = '$homePhone' where email='$email'");
  
  
  closeTARS($conn);

}

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