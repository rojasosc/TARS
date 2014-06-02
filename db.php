<?php

/*******************************************
*TARS- Teacher Assistant Registration System
********************************************/

/******************
*Database Interface
*******************/
		

	/* Database login credentials */
	const DATABASE_PATH = "localhost";
	const DATABASE_USERNAME = "root";
	const DATABASE_PASSWORD = "12345";
	const DATABASE_NAME = "TARS";
	
	const STUDENT = 0;
	const PROFESSOR = 1;
	const STAFF = 2;
	const ADMIN = 3;
	
	const PENDING = 0;
	const STAFF_VERIFIED = 1;
	const REJECTED = 2;
	const APPROVED = 3;

	
	/******************
	*DATABASE UTILITIES
	*******************/	
	
	
	
	/**
	* Function: open_database
	* Purpose: Obtains an object representation of the database.
	* Parameters: None.
	* Returns: nothing.
	*/
	function open_database(){

		/** Obtain an object representation of the database */
		$conn = mysqli_connect(DATABASE_PATH,DATABASE_USERNAME,DATABASE_PASSWORD,DATABASE_NAME) or die("Error " . mysqli_error($conn));
		
		return $conn; 
	}
	
	
	/**
	* Function: close_database();
	* Purpose: Closes the existing connection to the database.
	* Parameters: None.
	* Returns: Nothing.
	*/
	function close_database($conn){
	
		/** Close the existing connection to the database */
		
		mysqli_close($conn);

	}	
	
	/* Function getUserID
	*  Purpose: Obtains an existing users ID via their email address.
	*  Returns: integer userID
	**/
	function getUserID($email){
	
		$conn = open_database();
		
		$sql = "SELECT * FROM Users WHERE email='$email'";
		$user = mysqli_query($conn,$sql);
		
		/* Fetch user as an array representation */
		$user = mysqli_fetch_array($user);
		
		/*Obtain user ID*/
		$userID = $user['userID'];
		
		close_database($conn);

		return $userID;
	}
	
	/* Function 
	*  Purpose: 
	*  Returns: 
	**/	
	function getStudent($email){
		
		$studentID = getUserID($email);
		
		$conn = open_database();
		$sql = "SELECT * FROM Students WHERE studentID = '$studentID'";
		
		$student = mysqli_query($conn,$sql);
		$student = mysqli_fetch_array($student);
		
		
		close_database($conn);
		return $student;
	
	}
	
	/* Function 
	*  Purpose: 
	*  Returns: 
	**/	
	function getProfessor($email){
		
		$professorID = getUserID($email);
		
		$conn = open_database();
		$sql = "SELECT * FROM Professors WHERE professorID = '$professorID'";
		
		$professor = mysqli_query($conn,$sql);
		$professor = mysqli_fetch_array($professor);
		
		close_database($conn);

		return $professor;
	
	}	

	/* Function 
	*  Purpose: 
	*  Returns: 
	**/	
	function getStaff($email){
		
		$staffID = getUserID($email);
		
		$conn = open_database();
		$sql = "SELECT * FROM Staff WHERE staffID = '$staffID'";
		
		$staff = mysqli_query($conn,$sql);
		$staff = mysqli_fetch_array($staff);
		
		
		close_database($conn);
		return $staff;
	
	}	
	
	/***********************
	* END DATABASE UTILITIES
	************************/	
	
	/****************
	* LOGIN FUNCTIONS
	*****************/	

	/* Function login
	*  Purpose: Logs a user in.  Verifies that user's input password field against
	*           a hashed password stored in the database.
	*  Returns: nothing.
	**/
	function login($email, $inputPassword){

		$conn = open_database();
		$email = mysqli_real_escape_string($conn, $email);
		$inputPassword = mysqli_real_escape_string($conn, $inputPassword); // Entered Password
		$user = mysqli_query($conn, "SELECT * FROM Users WHERE email = '$email'");

		$user = mysqli_fetch_array($user);
		$password = $user['password'];

		/* Determine the type of user and verify password */
		
		if(password_verify($inputPassword,$password)){
			beginSession($email);
			return $user;
			
		}else{
			
			return false;
		}

	}


	/* Function beginSession
	*  Purpose:  Initializes a new session.
	*  Returns: nothing.
	**/
	function beginSession($email){
	
		session_start(); // begin the session
		session_regenerate_id(true);  // regenerate a new session id on each log in
		$_SESSION['auth'] =  "Authorized";
		$_SESSION['email'] = $email;
		
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
			$params["secure"], $params["httponly"]);
		}

		// Finally, destroy the session.
		session_destroy(); 
	}

	/* Function emailExists
	*  Purpose: Checks if an email is in use.
	*  Returns: True if in use and false otherwise.
	**/	
	function emailExists($email){
	
		$conn = open_database();
		
		$sql = "SELECT * FROM Users WHERE email ='$email'";
		
		$user = mysqli_query($conn,$sql);
		$user = mysqli_fetch_all($user);
		
		close_database();
		
		/* Check if the email is already in use */
		
		if(count($user) > 0){
			return true; 
		}else{
			return false;
		}

	} 
	
	/********************
	* END LOGIN FUNCTIONS
	*********************/	
	
	
	/*******************
	* USER REGISTRATION
	********************/
	
	/* Function newUser
	*  Purpose: Creates a new user in the Users table.
	*  Returns: The user ID of the new user.
	**/	
	function newUser($email,$password,$type){
		
		/*
		*	Types
		* 	[0 => student], [1 => professor], [2 => staff], [3 => admin]
		*/
		
		$conn = open_database();
		
		/*Insert entry into the Users table*/
		$sql = "INSERT INTO Users (email,password,type) VALUES('$email','$password','$type')";	
		mysqli_query($conn,$sql);
		$user = mysqli_query($conn,"SELECT * FROM Users WHERE email ='$email'");
		$user = mysqli_fetch_array($user);
		
		/*Obtain user ID*/
		$userID = $user['userID'];
		
		close_database($conn);
		
 		return $userID;
			
	}
	
	
	function registerStudent($firstName, $lastName,$email,$password,$homePhone,$mobilePhone,$classYear,$major,$gpa,$aboutMe){
		$conn = open_database();
		
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
		$aboutMe = mysqli_real_escape_string($conn,$aboutMe);
		
		/*Hash Password*/
		$password = password_hash($password, PASSWORD_DEFAULT);
		
		/*Obtain student ID*/
		$studentID = newUser($email,$password,STUDENT);

		/*Insert student record*/
		$sql = "INSERT INTO Students VALUES('$studentID','$firstName','$lastName','$homePhone','$mobilePhone','$major','$gpa','$classYear','$aboutMe')";
		mysqli_query($conn,$sql);
		close_database($conn);
	
	}
	
	/* Function registerProfessor
	*  Purpose: Creates a new account for a professor. 
	*  Returns: nothing.
	**/
	function registerProfessor($firstName, $lastName, $email, $password, $officeID, $officePhone, $mobilePhone){
		$conn = open_database();

		/* escape variables to avoid  injection attacks. */   
		$firstName = mysqli_real_escape_string($conn,$firstName);
		$lastName = mysqli_real_escape_string($conn,$lastName);
		$email = mysqli_real_escape_string($conn,$email);
		$password = mysqli_real_escape_string($conn,$password);
		$officePhone = mysqli_real_escape_string($conn,$officePhone);
		$mobilePhone = mysqli_real_escape_string($conn,$mobilePhone);

		/*Hash Password*/ 
		$password = password_hash($password, PASSWORD_DEFAULT);
	
		/*Obtain professor ID*/
		$professorID = newUser($email,$password,PROFESSOR);
		
		/*Insert student record*/
		$sql = "INSERT INTO Professors VALUES('$professorID', '$officeID', '$firstName','$lastName','$officePhone','$mobilePhone')";
		mysqli_query($conn,$sql);
		
		close_database($conn); 
	
	}	
	
	/* Function registerAdmin
	*  Purpose: Creates an account for an admin. 
	*  Returns: nothing.
	**/
	function registerStaff($firstName, $lastName,$email,$password,$officePhone,$mobilePhone){

		$conn = open_database();

		/* escape variables to avoid  injection attacks. */ 
		$firstName = mysqli_real_escape_string($conn,$firstName);
		$lastName = mysqli_real_escape_string($conn,$lastName);
		$email = mysqli_real_escape_string($conn,$email);
		$password = mysqli_real_escape_string($conn,$password);
		$officePhone = mysqli_real_escape_string($conn,$officePhone);
		$mobilePhone = mysqli_real_escape_string($conn,$mobilePhone);

		/*Hash Password*/
		$password = password_hash($password, PASSWORD_DEFAULT);
		
		/*Obtain staff ID*/
		$staffID = newUser($email,$password,STAFF);

		/*Insert staff record*/
		$sql = "INSERT INTO Staff VALUES('$staffID','$firstName','$lastName','$officePhone','$mobilePhone')";
		mysqli_query($conn,$sql);		
		
		close_database($conn);  
	
	}	
	
	/***********************
	* END USER REGISTRATION
	************************/	
	
	/*******************
	* PROFESSOR FUNCTIONS
	********************/
	
	/* Function getApplicants
	*  Purpose:  Obtain a table representation of a particular professor's applicants.
	*  Returns:  A 2-D array of type 0 and type 1 applicantions
	**/
	function getApplicants($email){
	
		$conn = open_database();
		
		$professorID = getUserID($email);
		
		
		$sql = "SELECT Users.userID,Students.firstName,Students.lastName,Users.email,Course.courseNumber, Positions.positionID, Students.gpa\n"
		. "FROM Assistantship,Users,Course,Positions,Students,Teaches\n"
		. "WHERE Users.userID = Assistantship.studentID AND Assistantship.studentID = Students.studentID AND Assistantship.status <= ". STAFF_VERIFIED. " AND Assistantship.positionID = Positions.positionID AND Positions.courseID = Course.courseID AND Teaches.professorID = '$professorID' AND Teaches.courseID = Course.courseID AND Users.type = ".STUDENT." ORDER BY `Course`.`courseNumber` ASC";		
		
		$apps = mysqli_query($conn,$sql);
		/* Fetch every application */
		$apps = mysqli_fetch_all($apps,MYSQLI_BOTH); 

		close_database($conn);
	
		return $apps; 
	}	
	
	/* Function getApplicants
	*  Purpose:  Obtain a table representation of a particular professor's applicants.
	*  Returns:  A 2-D array of type 0 and type 1 applicantions
	**/
	function getAssistants($email){
	
		$conn = open_database();
		
		$professorID = getUserID($email);
			
		$sql = "SELECT Users.userID,Students.firstName,Students.lastName,Users.email,Course.courseNumber, Positions.positionID, Students.gpa\n"
		. "FROM Assistantship,Users,Course,Positions,Students,Teaches\n"
		. "WHERE Users.userID = Assistantship.studentID AND Assistantship.studentID = Students.studentID AND Assistantship.status = " .APPROVED. " AND Assistantship.positionID = Positions.positionID AND Positions.courseID = Course.courseID AND Teaches.professorID = '$professorID' AND Teaches.courseID = Course.courseID AND Users.type = ".STUDENT." ORDER BY `Course`.`courseNumber` ORDER BY `Course`.`courseNumber` ASC";		
		$apps = mysqli_query($conn,$sql); 
		
		/* Fetch every assistant */
		$apps = mysqli_fetch_all($apps,MYSQLI_BOTH); 
		
		close_database($conn);
	
		return $apps; 
	}

	function pendingApplicants($email){

		$count = 0; 
		$count += count(getApplicants($email));

		return $count;
	
	}
	
	/* Function getCourseName
	*  Purpose: Retrieves all courses that contain the value 'courseName'. 
	*  Returns: An array of course entries. (A 2-D array) 
	**/
	function getCourses($email){

		$conn = open_database();
		$professorID = getUserID($email);
		
		$sql = "SELECT Course.courseID, Course.courseNumber,Course.courseTitle FROM Course WHERE professorID = '$professorID'";
		
		$result = mysqli_query($conn,$sql);
		
		/* Fetch every course */
		$courses = mysqli_fetch_all($result,MYSQLI_BOTH);
		
		close_database($conn);
		
		return $courses;
	}
	
	function getApplicationsByCourseID($email,$courseID){
	
		$conn = open_database();
		$professorID = getUserID($email);
		
		$sql = "SELECT Users.userID,Students.firstName,Students.lastName,Users.email,Course.courseNumber, Positions.type,Positions.positionID, Students.gpa\n"
		. "FROM Assistantship,Users,Course,Positions,Students,Teaches\n"
		. "WHERE Users.userID = Assistantship.studentID AND Assistantship.studentID = Students.studentID AND Assistantship.status <= ". STAFF_VERIFIED. " AND Assistantship.positionID = Positions.positionID AND Positions.courseID = '$courseID' AND Teaches.professorID = '$professorID' AND Teaches.courseID = '$courseID' AND Users.type = ".STUDENT." AND Course.courseID = '$courseID'";		
		
		$result = mysqli_query($conn,$sql);	
		$applications = mysqli_fetch_all($result, MYSQLI_BOTH);
		
		close_database($conn);
		
		return $applications;
	
	}

	
	function getFilledPositionsForCourse($email,$courseID){
	
		$conn = open_database();
		$professorID = getUserID($email);
		
		$sql = "SELECT Users.userID,Students.firstName,Students.lastName,Users.email,Course.courseNumber, Positions.type, Students.gpa\n"
		. "FROM Assistantship,Users,Course,Positions,Students,Teaches\n"
		. "WHERE Users.userID = Assistantship.studentID AND Assistantship.studentID = Students.studentID AND Assistantship.status = ".APPROVED. " AND Assistantship.positionID = Positions.positionID AND Positions.courseID = '$courseID' AND Teaches.professorID = '$professorID' AND Teaches.courseID = '$courseID' AND Users.type = ".STUDENT." AND Course.courseID = '$courseID'";		
		
		
		$result = mysqli_query($conn,$sql);
		$assistants = mysqli_fetch_all($result, MYSQLI_BOTH);
		
		close_database($conn);
		
		/* return the actual filled positions to use in the My Assistants page */
	
		return $assistants;

	}
	
	function countTotalPositions($email,$courseID){
	
		$conn = open_database();
		$professorID = getUserID($email);
	
		$sql = "SELECT COUNT(*) FROM Positions AS numberofPositions WHERE professorID = '$professorID' AND courseID = '$courseID'";
		$count = mysqli_query($conn,$sql);
		$count = mysqli_fetch_array($count);
		close_database($conn);
		$count = $count[0];
		return $count;
		
	}

	/* Function setPosition
	*  Purpose: Assigns a position to a student. 
	*  Returns: nothing.
	**/
	function setPositionStatus($studentID,$positionID,$status){
		$conn = open_database();
		
		$sql = "UPDATE Assistantship SET status = '$status' WHERE studentID = '$studentID' AND positionID = '$positionID'";
		
		mysqli_query($conn,$sql);
		
		close_database($conn);
	}
	
	
	
	function getCourseIDS($email){
	
		$conn = open_database();
		
		$professorID = getUserID($email);
		
		$sql = "SELECT Course.courseID\n"
			. "FROM Course\n"
			. "WHERE professorID = '$professorID'";

		$result = mysqli_query($conn,$sql);
		
		
		/* 2-D array of courseIDS */ 
		$result = mysqli_fetch_all($result); 
		
		/*Make just one array */
		$courseIDS = array();
		
		foreach($result as $course){
			
			$courseIDS[] = $course[0];
		}
		
		close_database($conn);
		
		return $courseIDS;
	}
	
	/************************
	* END PROFESSOR FUNCTIONS
	*************************/
	
	/*******************
	* STUDENT FUNCTIONS
	********************/
	
	/* Function studentPositions
	*  Purpose: Fetch all of the student's currently held TA-ing positions fromt he database
	*  Returns: An array of associative arrays with all the student's TA position information
	**/
	
	function studentPositions($email){
	
		$conn = open_database();
		
		$studentID = getUserID($email);
		
		$sql = "SELECT Users.userID, Course.courseNumber, Course.courseTitle, Positions.type, Place.building, Place.room, Course.startTime, Course.endTime, Assistantship.compensation\n"
			. "FROM Users,Course,Positions,Place,Assistantship\n"
			. "WHERE Users.userID = '$studentID' AND Positions.courseID = Course.courseID AND Assistantship.positionID = Positions.positionID AND Assistantship.status = " .APPROVED. " AND Assistantship.studentID = Users.userID AND Course.placeID = Place.placeID ORDER BY `Course`.`courseNumber` ASC";
		
		
		$result = mysqli_query($conn, $sql);
		$positions = mysqli_fetch_all($result, MYSQLI_BOTH);
		
		close_database($conn);
		
		return $positions;
	
	}

	/* Function updateProfile
	*  Purpose: Edit the database entries that correspond to the student's information with newly submitted ones from the student
	*  Returns: absolutely nothing
	**/
	function updateProfile($email, $firstName, $lastName, $mobilePhone, $major, $classYear, $gpa, $aboutMe) {
		$connect = open_database();
		
		$firstName = mysqli_real_escape_string($connect, $firstName);
		$lastName = mysqli_real_escape_string($connect, $lastName);
		$mobilePhone = mysqli_real_escape_string($connect, $mobilePhone);
		$major = mysqli_real_escape_string($connect, $major);
		$classYear = mysqli_real_escape_string($connect, $classYear);
		$gpa = mysqli_real_escape_string($connect, $gpa);
		$aboutMe = mysqli_real_escape_string($connect, $aboutMe);
		
		$sql = "UPDATE Students\n"
			."INNER JOIN Users ON Users.userID = Students.studentID\n"
			."SET firstName = '$firstName', lastName = '$lastName', mobilePhone = '$mobilePhone', major = '$major', classYear = '$classYear', gpa = '$gpa', aboutMe = '$aboutMe'\n"
			."WHERE email = '$email';";
		mysqli_query($connect, $sql);
		
		close_database($connect);
	}

	/* Function search
	*  Purpose: Search the database for TA positions with the specified attributes
	*  Returns: An array of associative arrays that represent individual positions
	**/
	
	function search($search, $term, $days, $startTime, $endtime) {
		$connect = open_database();
		$sql = "SELECT *\n"
			."FROM Positions\n"
			."INNER JOIN Course ON Positions.courseID = Course.courseID\n"
			."INNER JOIN Professors ON Positions.professorID = Professors.professorID\n";
		if($search != NULL) {
			$search = strtoupper($search);
			$search = trim($search);
			$sql .= "WHERE courseNumber = '$search'\n";
		}
		if($term != NULL) {
			$sql .= "AND term = '$term'\n";
		}
		$sql .="ORDER BY positionID";
		$results = mysqli_query($connect, $sql);
		$results = mysqli_fetch_all($results, MYSQLI_BOTH);
		close_database($connect);
		
		return $results;
	}
	
	/***********************
	* END STUDENT FUNCTIONS
	************************/
	
	/****************
	* STAFF FUNCTIONS
	*****************/
	
	
	/* Function 
	*  Purpose: 
	*  Returns: 
	**/
	function getPayrollByTerm($term){
		
		$conn = open_database();
		
	
		$sql = "SELECT Students.studentID, Students.firstName, Students.lastName, Users.email, Students.classYear, Course.courseNumber, Positions.type, Assistantship.compensation\n"
		. "FROM Users,Students,Course,Positions,Assistantship\n"
		. "WHERE Students.studentID = Users.userID AND Students.studentID = Assistantship.studentID AND Course.courseID = Positions.courseID AND Positions.positionID = Assistantship.positionID AND Assistantship.status = ".APPROVED;
		
		$result = mysqli_query($conn,$sql);
		$payroll = mysqli_fetch_all($result,MYSQLI_BOTH);

		close_database($conn);
		
		return $payroll;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/********************
	* END STAFF FUNCTIONS
	*********************/

?>