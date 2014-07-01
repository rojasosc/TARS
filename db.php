<?php
	include('plugins/password_compat/password.php');
/*******************************************
*TARS- Teacher Assistant Registration System
********************************************/

/******************
*Database Interface
*******************/
		

/* Database login credentials */
const DATABASE_PATH = 'localhost';
const DATABASE_USERNAME = 'root';
const DATABASE_PASSWORD = '1234';
const DATABASE_NAME = 'TARS';
const DATABASE_TYPE = 'mysql';

/*Account Types*/
const STUDENT = 0;
const PROFESSOR = 1;
const STAFF = 2;
const ADMIN = 3;

/*Application Statuses*/
const PENDING = 0;
const STAFF_VERIFIED = 1;
const REJECTED = 2;
const APPROVED = 3;
const WITHDRAWN = 4;

// TODO: use a configurable option or something
// 2 is internal ID of current Term row/object (fall 2014) in TARS-testdata.sql
const CURRENT_TERM = 1;

/******************
*DATABASE UTILITIES
*******************/	

/*
 * Databse Object: Utility class to connect to and query the database using PDO.
 */
final class Database {
	private static $db_conn = null;

	/**
	 * Database::connect()
	 * Purpose: Connects to the database using PDO and sets $db_conn.
	 *
	 * This terminates the script with an echo on failure, since throwing an
	 * exception is a security risk (the stacktrace will contain the database credentials).
	 *
	 * Call this ONLY ONCE, because we want to promote persistant database connections, which
	 * can be cached by PDO.
	 */
	public static function connect() {

		$db_dsn = DATABASE_TYPE.':host='.DATABASE_PATH.';dbname='.DATABASE_NAME;

		try {
			/** Obtain a persistant object representation of the database */
			Database::$db_conn = new PDO($db_dsn, DATABASE_USERNAME, DATABASE_PASSWORD);
			Database::$db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $ex) {
			echo "PDO connection error: " . $ex->getMessage() . "<br/>\n";
			exit;
		}

	}

	/**
	 * Database::executeStatement($sql, $args)
	 * Purpose: Prepares and executes a statement with the specified arguments.
	 * Returns: The statement object, for fetching.
	 * Throws: A PDOException if prepare() or execute() fail (SQL syntax or database error).
	 */
	public static function execute($sql, $args) {
		/* prepare statement: throws PDOException on error */
		$stmt = Database::$db_conn->prepare($sql);

		/* execute statement: throws PDOException on error */
		$stmt->execute($args);

		return $stmt;
	}

	/**
	 * Database::executeGetRow($sql, $args)
	 * Purpose: Runs a prepared statement with the given SQL statement and arguments.
	 *          Gets a row of the database.
	 * Returns: The row requested, or FALSE if no rows returned
	 * Throws: A PDOException if prepare() or execute() fail (SQL syntax or database error).
	 */
	public static function executeGetRow($sql, $args) {
		/* create and execute the statement object */
		$stmt = Database::execute($sql, $args);

		/* fetch result: return first row, or null */
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($result === null) {
			/* empty result set */
			return false;
		}

		return $result;
	}

	/**
	 * Database::executeGetAllRows($sql, $args)
	 * Purpose: Runs a prepared statement with the given SQL statement and arguments.
	 *          Gets all selected rows of the database.
	 * Returns: The rows requested
	 * Throws: A PDOException if prepare() or execute() fail (SQL syntax or database error).
	 */
	public static function executeGetAllRows($sql, $args) {
		/* create and execute the statement object */
		$stmt = Database::execute($sql, $args);

		/* fetch result: return array of all rows */
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Database::executeGetScalar($sql, $args)
	 * Purpose: Runs a prepared statement with the given SQL statement and arguments.
	 *          Gets a single cell/value (scalar) from the database.
	 * Returns: The scalar requested, or FALSE if no rows returned
	 * Throws: A PDOException if prepare() or execute() fail (SQL syntax or database error).
	 */
	public static function executeGetScalar($sql, $args) {
		/* create and execute the statement object */
		$stmt = Database::execute($sql, $args);

		/* fetch result: return first row, or null */
		$result = $stmt->fetch(PDO::FETCH_NUM);
		if ($result === null) {
			/* empty result set */
			return false;
		}

		return $result[0];
	}

	/**
	 * Database::executeInsert($sql, $args)
	 * Purpose: Runs a prepared statement with the given SQL statement and arguments.
	 *          Gets the inserted ID
	 * Returns: The last ID generated for an AUTO_INCREMENT column; the ID of the column inserted.
	 * Throws: A PDOException if prepare() or execute() fail (SQL syntax or database error).
	 * Note: Yes, you can run non-INSERT queries with this.
	 *       It's silly to though, as why else do you need the last ID inserted?
	 */
	public static function executeInsert($sql, $args) {
		/* create and execute the statement object */
		Database::execute($sql, $args);

		/* get the inserted ID */
		return Database::$db_conn->lastInsertId();
	}

}


final class Place {
	/*
	 * Place::getPlaceByID($id)
	 * Purpose: Fetch a place object using its unique object ID via executeGetRow()
	 * Returns: A place object corresponding to the ID provided
	 * Throws: exceptions from executeGetRow()
	 */
	public static function getPlaceByID($id) {
		$sql = 'SELECT * FROM Places WHERE placeID = :id';
		$args = array(':id' => $id);
		$row = Database::executeGetRow($sql, $args);
		return new Place($row);
	}
	/*
	 * Place::getPlaceByBuildingAndroom($building, $room)
	 * Purpose: Fetch a place object using its building name and room number
	 * Returns: A place object corresponding to the building and room number provided
	 * Throws: exceptions from executeGetRow()
	 */
	public static function getPlaceByBuildingAndRoom($building, $room){
		$sql = 'SELECT * FROM Places WHERE building = :building AND room = :room';
		$args = array(':building' => $building, ':room' => $room);
		$row = Database::executeGetRow($sql, $args);
		return new Place($row);
	}
	/*
	 * 
	 */
	public static function getPlacesByBuilding($building){
		$sql = 'SELECT * FROM Places WHERE building = :building';
		$args = array(':building' => $building);
		$rows = Database::executeGetAllRows($sql, $args);
		return array_map(function ($row) { return new Place($row); }, $rows);
	}
	
	public static function getBuildings(){
		$sql = 'SELECT DISTINCT building FROM Places';
		$args = array();
		$rows = Database::executeGetAllRows($sql, $args);
		return $rows;
	}
	private function __construct($row) {
		$this->placeID = $row['placeID'];
		$this->building = $row['building'];
		$this->room = $row['room'];
		$this->roomType = $row['roomType'];
	}

	public function getBuilding() { return $this->building; }
	public function getRoom() { return $this->room; }
	public function getRoomType() { return $this->roomType; }
	public function getPlaceID() { return $this->placeID; }

	private $building;
	private $room;
	private $roomType;
	private $placeID;
}

/*
 * User Object: Represents a User in the database.
 *
 * ::getUserByEmail($email) returns subclassed User object by given username
 * ::getUserByID($id) returns subclassed User object by given database row ID
 *
 * ->getID() return database row ID
 * ->getEmail() returns email string
 * ->getPassword() returns hashed password string
 * ->getObjectType() returns object type
 * ->getFirstName() return user first name
 * ->getLastName() return user last name
 */
abstract class User {
	public static function getUserByID($id, $check_type = -1) {
		$sql = 'SELECT * FROM Users WHERE userID = :id';
		$args = array(':id' => $id);
		if ($check_type >= 0) {
			$sql .= ' AND type = :type';
			$args[':type'] = $check_type;
		}
		$user_row = Database::executeGetRow($sql, $args);

		if ($user_row) {
			return User::getUserSubclassObject($user_row);
		} else {
			return false;
		}
	}

	public static function getUserByEmail($email, $check_type = -1) {
		$sql = 'SELECT * FROM Users WHERE email = :email';
		$args = array(':email' => $email);
		if ($check_type >= 0) {
			$sql .= ' AND type = :type';
			$args[':type'] = $check_type;
		}
		$user_row = Database::executeGetRow($sql, $args);

		if ($user_row) {
			return User::getUserSubclassObject($user_row);
		} else {
			return false;
		}
	}

	private static function getUserSubclassObject($user_row) {
		$args = array(':id' => $user_row['userID']);
		switch ($user_row['type']) {
		case STUDENT:
			$row = Database::executeGetRow('SELECT * FROM Students WHERE userID = :id', $args);
			return new Student($user_row, $row);
		case PROFESSOR:
			$row = Database::executeGetRow('SELECT * FROM Professors WHERE userID = :id', $args);
			return new Professor($user_row, $row);
		case STAFF:
			$row = Database::executeGetRow('SELECT * FROM Staff WHERE userID = :id', $args);
			return new Staff($user_row, $row);
		case ADMIN:
			// TODO add admin table?
			return new Admin($user_row, false);
		}
	}

	public static function findUsers($email, $firstName, $lastName, $check_type = -1) {
		$sql = 'SELECT * FROM Users
				WHERE ';
		$args = array();
		if (!empty($email)) {
			$sql .= 'INSTR(email, :email) AND ';
			$args[':email'] = $email;
		}
		if (!empty($firstName)) {
			$sql .= 'INSTR(firstName, :firstName) AND ';
			$args[':firstName'] = $firstName;
		}
		if (!empty($lastName)) {
			$sql .= 'INSTR(lastName, :lastName) AND ';
			$args[':lastName'] = $lastName;
		}
		if ($check_type >= 0) {
			$sql .= 'type = :type AND ';
			$args[':type'] = $check_type;
		}
		$sql .= '1 ORDER BY lastName DESC, firstName DESC';
		$rows = Database::executeGetAllRows($sql, $args);
		return array_map(function ($row) { return User::getUserSubclassObject($row); }, $rows);
	}


	public static function insertUser($email, $password, $firstName, $lastName, $type) {
		return Database::executeInsert('INSERT INTO Users
			(email, password, firstName, lastName, type) VALUES
			(:email, :password, :firstName, :lastName, :type)',
			array(':email' => $email, ':password' => $password,
				':firstName' => $firstName, ':lastName' => $lastName, ':type' => $type));
	}

	public static function checkEmailAvailable($email) {
		$count = Database::executeGetScalar('SELECT COUNT(*) FROM Users WHERE email = :email',
			array(':email' => $email));
		return $count == 0;		
	}

	protected function __construct($user_row) {
		$this->id = $user_row['userID'];
		$this->email = $user_row['email'];
		$this->password = $user_row['password'];
		$this->otype = $user_row['type'];
		$this->firstName = $user_row['firstName'];
		$this->lastName = $user_row['lastName'];
	}

	public function getID() { return $this->id; }
	public function getEmail() { return $this->email; }
	public function getPassword() { return $this->password; }
	public function getObjectType() { return $this->otype; }
	public function getFirstName() { return $this->firstName; }
	public function getLastName() { return $this->lastName; }

	/* "first-initial last" name */
	public function getFILName() {
		return "{$this->firstName[0]}. {$this->lastName}";
	}

	protected $id;
	protected $email;
	protected $password;
	protected $otype;
	protected $firstName;
	protected $lastName;
}

final class Student extends User {
	public static function registerStudent($email, $password, $firstName, $lastName,
		$mobilePhone, $major, $gpa, $classYear, $aboutMe, $universityID) {

		$password_hash = password_hash($password, PASSWORD_DEFAULT);

		$userID = parent::insertUser($email, $password_hash, $firstName, $lastName, STUDENT);

		$sql = 'INSERT INTO Students
				(userID, mobilePhone, major, gpa, classYear, aboutMe, universityID) VALUES
				(:id, :mobilePhone, :major, :gpa, :classYear, :aboutMe, :universityID)';
		$args = array(':id' => $userID, ':mobilePhone' => $mobilePhone, 
				  ':major' => $major, ':gpa' => $gpa, ':universityID' => $universityID,
				  ':classYear' => $classYear, ':aboutMe' => $aboutMe);
		Database::executeInsert($sql, $args);
		return $userID;
	}

	public function __construct($user_row, $student_row) {
		parent::__construct($user_row);

		if ($student_row) {
			$this->mobilePhone = $student_row['mobilePhone'];
			$this->major = $student_row['major'];
			$this->gpa = $student_row['gpa'];
			$this->classYear = $student_row['classYear'];
			$this->aboutMe = $student_row['aboutMe'];
			$this->status = $student_row['status'];
			$this->reputation = $student_row['reputation'];
			$this->universityID = $student_row['universityID'];
		}
	}

	public function getStudentsToReview(){
		$sql = "SELECT userID FROM Students WHERE status = ".PENDING;
		$args = array();
		$rows = Database::executeGetAllRows($sql,$args);
		return array_map(function ($row) { return User::getUserByID($row['userID']);}, $rows);
	}
	
	public function setStudentStatus($userID,$status){
		$sql = "UPDATE Students SET status = :status WHERE userID = :userID";
		$args = array(':status' => $status,':userID' => $userID);
		Database::execute($sql,$args);
	}
		
	public function getApplications($status) {
		$sql = 'SELECT * FROM Applications
				INNER JOIN Positions ON Positions.positionID = Applications.positionID
				INNER JOIN Courses ON Courses.courseID = Positions.courseID
				WHERE studentID = :student_id AND appStatus = :status
				ORDER BY department DESC, courseNumber ASC';
		$args = array(':student_id' => $this->id, ':status' => $status);
		$rows = Database::executeGetAllRows($sql, $args);
		return array_map(function ($row) { return new Application($row); }, $rows);
	}
	

	public function apply($position, $compensation, $qualifications) {
		$sql = 'INSERT INTO Applications
				(positionID, studentID, compensation, appStatus, qualifications) VALUES
				(:position, :student, :comp, :status, :qual)';
		$args = array(':position' => $position, ':student' => $this->id,
			':comp' => $compensation, ':status' => PENDING, ':qual' => $qualifications);
		return Database::executeInsert($sql, $args);
	}

	public function updateProfile($firstName, $lastName, $mobilePhone,
		$major, $classYear, $gpa, $aboutMe) {
		$sql = 'UPDATE Students
				INNER JOIN Users ON Users.userID = Students.userID
				SET firstName = :firstName, lastName = :lastName,
					mobilePhone = :mobilePhone, major = :major, classYear = :classYear,
					gpa = :gpa, aboutMe = :aboutMe
				WHERE Users.userID = :id';
		$args = array(':id'=>$this->id, ':firstName'=>$firstName, ':lastName'=>$lastName,
			':mobilePhone'=>$mobilePhone, ':major'=>$major,
			':classYear'=>$classYear, ':gpa'=>$gpa, ':aboutMe'=>$aboutMe);
		Database::execute($sql, $args);
	}
	
	public function withdraw($positionID){
		$sql = 'UPDATE Applications
				SET appStatus = :status
				WHERE positionID = :positionID AND studentID = :studentID';
		$args = array(':status' => WITHDRAWN, ':positionID' => $positionID, ':studentID' => $this->getID());
		Database::execute($sql, $args);
	}

	public function getMobilePhone() { return $this->mobilePhone; }
	public function getMajor() { return $this->major; }
	public function getGPA() { return $this->gpa; }
	public function getClassYear() { return $this->classYear; }
	public function getAboutMe() { return $this->aboutMe; }
	public function getStatus() { return $this->status; }
	public function getReputation() { return $this->reputation; }
	public function getUniversityID() { return $this->universityID; }

	private $mobilePhone;
	private $major;
	private $gpa;
	private $classYear;
	private $aboutMe;
	private $status;
	private $reputation;
	private $universityID;
}

final class Professor extends User {
	public static function registerProfessor($email, $password, $firstName, $lastName,
		$officeID, $officePhone, $mobilePhone) {

		$password_hash = password_hash($password, PASSWORD_DEFAULT);

		$userID = parent::insertUser($email, $password_hash, $firstName, $lastName, PROFESSOR);

		$sql = 'INSERT INTO Professors
				(userID, officeID, officePhone, mobilePhone) VALUES
				(:id, :officeID, :officePhone, :mobilePhone)';
		$args = array(':id' => $userID, ':officeID' => $officeID,
				':officePhone' => $officePhone, ':mobilePhone' => $mobilePhone);
		Database::executeInsert($sql, $args);
		return $userID;
	}

	public function __construct($user_row, $professor_row) {
		parent::__construct($user_row);

		if ($professor_row) {
			$this->officeID = $professor_row['officeID'];
			$this->office = null;
			$this->officePhone = $professor_row['officePhone'];
			$this->mobilePhone = $professor_row['mobilePhone'];
		}
	}

	public static function getAllProfessors(){
		$sql = "SELECT * FROM Users 
			INNER JOIN Professors on Professors.userID = Users.userID";
		$args = array();
		$rows = Database::executeGetAllRows($sql,$args);
		return array_map(function ($row) { return new Professor($row); }, $rows );
	}
		
	public function updateProfile($firstName, $lastName, $officeID, $officePhone, $mobilePhone) {
		$sql = 'UPDATE Professors
				INNER JOIN Users ON Users.userID = Professors.userID
				SET firstName = :firstName, lastName = :lastName,
					officeID = :officeID, officePhone = :officePhone,
					mobilePhone = :mobilePhone
				WHERE Users.userID = :id';
		$args = array(':id'=>$this->id, ':firstName'=>$firstName, ':lastName'=>$lastName,
			':officeID' => $officeID, ':officePhone' => $officePhone,
			':mobilePhone'=>$mobilePhone);
		Database::execute($sql, $args);
	}

	public function getCourses() {
		$sql = 'SELECT Courses.courseID, Courses.crn, Courses.department, Courses.courseNumber,
					Courses.courseTitle, Courses.website, Courses.termID
				FROM Courses, Teaches
				WHERE Courses.courseID = Teaches.courseID AND Teaches.professorID = :prof_id
				ORDER BY Courses.department DESC, Courses.courseNumber ASC';
		$args = array(':prof_id' => $this->id);
		$rows = Database::executeGetAllRows($sql, $args);
		return array_map(function ($row) { return new Course($row); }, $rows);
	}


	public function getOffice() {
		if ($this->office == null) {
			$this->office = Place::getPlaceByID($this->officeID);
		}
		return $this->office;
	}
	
	public function getOfficeID() { return $this->officeID; }
	public function getOfficePhone() { return $this->officePhone; }
	public function getMobilePhone() { return $this->mobilePhone; }

	private $officeID;
	private $office;
	private $officePhone;
	private $mobilePhone;
}

final class Staff extends User {
	public static function registerStaff($email, $password, $firstName, $lastName,
		$officePhone, $mobilePhone) {

		$password_hash = password_hash($password, PASSWORD_DEFAULT);

		$userID = parent::insertUser($email, $password_hash, $firstName, $lastName, STAFF);

		$sql = 'INSERT INTO Staff
				(userID, officePhone, mobilePhone) VALUES
				(:id, :officePhone, :mobilePhone)';
		$args = array(':id' => $userID,
				':officePhone' => $officePhone, ':mobilePhone' => $mobilePhone);
		Database::executeInsert($sql, $args);
		return $userID;
	}

	public function __construct($user_row, $staff_row) {
		parent::__construct($user_row);

		if ($staff_row) {
			$this->officePhone = $staff_row['officePhone'];
			$this->mobilePhone = $staff_row['mobilePhone'];
		}
	}

	public function getOfficePhone() { return $this->officePhone; }
	public function getMobilePhone() { return $this->mobilePhone; }

	private $officePhone;
	private $mobilePhone;
}

final class Admin extends User {
	public function __construct($user_row, $admin_row) {
		parent::__construct($user_row);
	}
}

final class Position {
	public static function getPositionByID($id) {
		$row = Database::executeGetRow('SELECT * FROM Positions WHERE positionID = :id',
			array(':id' => $id));
		return new Position($row);
	}
	public static function insertPosition($courseID, $professorID, $time, $posType) {
		$sql = 'INSERT INTO Positions (courseID, professorID, time, posType)
				VALUES (:courseID, :professorID, :time, :posType)';
		$args = array(':courseID' => $courseID, ':professorID' => $professorID, ':time' => $time, ':posType' => $posType);
		$posID = Database::executeInsert($sql, $args);
		return $posID;
	}
	//BUGGY AF
	public static function findPositions($search_field, $term = -1, $position_type = null, $studentID) {
		$sql = 'SELECT * FROM Positions
				INNER JOIN Courses ON Positions.courseID = Courses.courseID
				LEFT JOIN Applications ON Positions.positionID = Applications.positionID
				WHERE ';
		$args = array();
		if (!empty($search_field)) {
			$i = 1;
			foreach (explode(' ', $search_field) as $word) {
				$sql .= "(Courses.department = :word$i OR
					Courses.courseNumber = :word$i OR
					INSTR(Courses.courseTitle, :word$i) OR
					CONCAT(Courses.department, Courses.courseNumber) = :word$i) AND ";
				$args[":word$i"] = $word;
				$i++;
			}
		}
		if ($term >= 0) {
			$sql .= 'termID = :term ';
			$args[':term'] = $term;
		}
		$sql .="AND ((strcmp(studentID, :studentID) <> 0) OR studentID is null) ";
		$args['studentID'] = $studentID;
		// TODO: implement position_type
		//if ($position_type != null) {
		//	$sql .= 'posType = :posType AND ';
		//	$args[':posType'] = $position_type;
		//}
		$sql .= 'ORDER BY Courses.department DESC, Courses.courseNumber ASC';
		//echo '<pre>';
		//print_r($args);
		//exit($sql);
		$rows = Database::executeGetAllRows($sql, $args);
		return array_map(function ($row) { return new Position($row); }, $rows);
	}
	

	private function __construct($row) {
		$this->id = $row['positionID'];
		$this->courseID = $row['courseID'];
		$this->course = null;
		$this->professorID = $row['professorID'];
		$this->professor = null;
		$this->time = $row['time'];
		$this->posType = $row['posType'];
	}

	public function getID() { return $this->id; }
	public function getCourse() {
		if ($this->course == null) {
			$this->course = Course::getCourseByID($this->courseID);
		}
		return $this->course;
	}
	public function getProfessor() {
		if ($this->professor == null) {
			$this->professor = User::getUserByID($this->professorID, PROFESSOR);
		}
		return $this->professor;
	}
	public function getTime() { return $this->time; }
	public function getPositionType() { return $this->posType; }

	private $id;
	private $courseID;
	private $course;
	private $professorID;
	private $professor;
	private $time;
	private $posType;
}

final class Application {
	// TODO: this should be in relation to the Application database object instead of Position
	public static function setPositionStatus($student, $position, $status) {
		$sql = 'UPDATE Applications
				SET appStatus = :status
				WHERE studentID = :student_id AND positionID = :position_id';
		$args = array(':status' => $status,	':student_id' => $student->getID(),
			':position_id' => $position->getID());
		Database::execute($sql, $args);
	}

	public static function getApplicationByID($id) {
		$row = Database::executeGetRow('SELECT * FROM Applications WHERE appID = :id',
			array(':id' => $id));
		return new Application($row);
	}

	private static function generateGetApplicationsRequest($course, $professor, $term, $status, $compensation, $is_count) {
		$sql_where = '';
		$args = array();
		if ($course != null) {
			$sql_where .= 'Positions.courseID = :course AND ';
			$args[':course'] = $course->getID();
		}
		if ($professor != null) {
			$sql_where .= 'Positions.professorID = :professor AND ';
			$args[':professor'] = $professor->getID();
		}
		if ($term != null) {
			$sql_where .= 'Courses.termID = :term AND ';
			$args[':term'] = $term->getID();
		}
		if ($status >= 0) {
			$sql_where .= 'Applications.appStatus = :status AND ';
			$args[':status'] = $status;
		}
		if ($compensation != null) {
			$sql_where .= 'Applications.compensation = :compensation AND ';
			$args[':compensation'] = $compensation;
		}
		if ($is_count) {
			$sql_sel = 'COUNT(*)';
		} else {
			$sql_sel = '*';
		}
		$sql = "SELECT $sql_sel FROM Applications
				INNER JOIN Positions ON Applications.positionID = Positions.positionID
				INNER JOIN Courses ON Positions.courseID = Courses.courseID
				WHERE $sql_where 1
				ORDER BY Courses.department DESC, Courses.courseNumber ASC";
		return array($sql, $args);
	}

	/**
	 * General purpose function to get application object count.
	 *
	 */
	public static function getApplicationCount($course = null, $professor = null, $term = null, $status = -1, $compensation = null) {
		list($sql, $args) = Application::generateGetApplicationsRequest(
			$course, $professor, $term, $status, $compensation, true);
		return Database::executeGetScalar($sql, $args);
	}

	/**
	 * General purpose function to get application object count.
	 *
	 */
	public static function getApplications($course = null, $professor = null, $term = null, $status = -1, $compensation = null) {
		list($sql, $args) = Application::generateGetApplicationsRequest(
			$course, $professor, $term, $status, $compensation, false);
		$rows = Database::executeGetAllRows($sql, $args);
		return array_map(function ($row) { return new Application($row); }, $rows);
	}

	public function __construct($row) {
		$this->id = $row['appID'];
		$this->positionID = $row['positionID'];
		$this->position = null;
		$this->studentID = $row['studentID'];
		$this->student = null;
		$this->compensation = $row['compensation'];
		$this->appStatus = $row['appStatus'];
		$this->qualifications = $row['qualifications'];
	}

	public function getID() { return $this->id; }
	public function getPosition() {
		if ($this->position == null) {
			$this->position = Position::getPositionByID($this->positionID);
		}
		return $this->position;
	}
	public function getStudent() {
		if ($this->student == null) {
			$this->student = User::getUserByID($this->studentID);
		}
		return $this->student;
	}
	public function getCompensation() { return $this->compensation; }
	public function getStatus() { return $this->appStatus; }
	public function getQualifications() { return $this->qualifications; }

	private $id;
	private $position;
	private $positionID;
	private $student;
	private $studentID;
	private $compensation;
	private $appStatus;
	private $qualifications;
}

final class Term {
	public static function getTermByID($id) {
		$sql = 'SELECT * FROM Terms WHERE termID = :id';
		$args = array(':id' => $id);
		$row = Database::executeGetRow($sql, $args);
		return new Term($row);
	}

	public static function getAllTerms() {
		$sql = 'SELECT * FROM Terms ORDER BY year, session';
		$rows = Database::executeGetAllRows($sql, array());
		return array_map(function ($row) { return new Term($row); }, $rows);
	}
	
	public static function insertTerm($year, $session) {
		$sql = 'INSERT INTO Terms (year, session) VALUES (:year, :session)';
		$args = array(':year' => $year, ':session' => $session);
		$termID = Database::executeInsert($sql, $args);
		return $termID;
	}
	/*
	 * Not totally sure if this should be here, but it's here for now.
	 * Inserts a line into teaches to link the professor and the course
	 */
	public static function insertTeaches($courseID, $professorID) {
		$sql = 'INSERT INTO Teaches (courseID, professorID) VALUES (:courseID, :professorID)';
		$args = array(':courseID' => $courseID, ':professorID' => $professorID);
		$teachID = Database::executeInsert($sql, $args);
		return $teachID;
	}
	/*
	 * Takes a file path and processes the JSON content and inserts entries into the DB
	 */
	public static function getTermFromFile($path){
		$data = file_get_contents($path);
		$data = json_decode($data,true);
		//Insert Term into DB
		//var_dump($data);
		if(isset($data['termYear']) && isset($data['termSemester'])) {
			//$termID = insertTerm($data['termYear'], $data['termSemester']);
			$emailDomain = $data['defaultEmailDomain'];
		}
		if(isset($data['courses'])) {
			foreach($data['courses'] as $course) {
				//Insert Course into DB
				$crn = $course['crn'];
				$department = $course['dep'];
				$courseNumber = $course['number'];
				$courseTitle = $course['title'];
				$website = $course['website'];
				$courseID = Course::insertCourse($crn, $department, $courseNumber, $courseTitle, $website, CURRENT_TERM);
				//Assign instructors to the course
				if(isset($course['instructors'])) {
					foreach($course['instructors'] as $instructor) {
						$email = $instructor['email'].$emailDomain;
						$professorID = User::getUserByEmail($email, PROFESSOR)->getID();
						Term::insertTeaches($courseID, $professorID);
					}
				}
				//Insert Positions into DB
				if(isset($course['positions'])) {
					foreach($course['positions'] as $position) {
						$posType = $position['type'];
						if(isset($position['sessions'])) {
							$time = $position['sessions']['begin'].' - '.$position['sessions']['end'];
						} elseif($posType === "Super Leader" || $posType === "Workshop Leader") {
							$time = 'TBD';
						} else {
							$time = 'FLEXIBLE';
						}
						Position::insertPosition($courseID, $professorID, $time, $posType);
					}
				}
			}
		}
	}

	public function __construct($row) {
		$this->id = $row['termID'];
		$this->year = $row['year'];
		$this->session = $row['session'];
	}

	public function getID() { return $this->id; }
	public function getYear() { return $this->year; }
	public function getSession() { return $this->session; }
	public function toString() {
		return ucfirst($this->session).' '.$this->year;
	}

	private $id;
	private $year;
	private $session;
}

final class Feedback {
	public static function getCommentByID($feedbackID){
		$sql = "SELECT * FROM Feedback WHERE feedbackID = :feedbackID";
		$args = array('feedbackID' => $feedbackID);
		$row = Database::executeGetRow($sql,$args);
		return new Feedback($row);
	}
	
	public static function getCommentsFromStaff($studentID){
		$sql = "SELECT Users.firstName, Users.lastName, Feedback.comment, Feedback.dateTime
    			FROM Users,Feedback
   				WHERE Users.type = :staff AND  Feedback.studentID = :studentID";
   		$args = array(':studentID' => $studentID, ':staff' => STAFF);
   		$rows = Database:: executeGetAllRows($sql,$args);
   		return $rows;
	}
	
	public static function getCommentsFromProfessors($studentID){
		$sql = "SELECT Users.firstName, Users.lastName, Feedback.comment, Feedback.dateTime
    			FROM Users,Feedback
   				WHERE Users.type = :professor AND Users.userID = Feedback.commenterID AND Feedback.studentID = :studentID";
   		$args = array(':studentID' => $studentID, ':professor' => PROFESSOR);
   		$rows = Database:: executeGetAllRows($sql,$args);
   		return $rows;		
	}
	
	public static function newComment($studentID,$commenterID,$comment){
		$sql = "INSERT INTO Feedback (studentID,commenterID,comment) VALUES (:studentID,:commenterID,:comment)";
		$args = array(':studentID' => $studentID, ':commenterID' => $commenterID,':comment' => $comment);
		Database::executeInsert($sql,$args);
	}
	
	public function __construct($row){
		$this->feedbackID = $row['feedbackID'];
		$this->studentID = $row['studentID'];
		$this->commenterID = $row['commenterID'];
		$this->date_Time = $row['dateTime'];
		$this->comment = $row['comment'];
	}
	
	public function getCommentID(){ return $this->feedbackID; }
	public function getStudentID(){ return $this->studentID; }
	public function getCommentorID(){ return $this->commenterID; }
	public function getDateTime(){ return $this->date_Time; }
	public function getComment(){ return $this->comment; }
	
	private $feedbackID;
	private $studentID;
	private $commenterID;
	private $date_Time;
	private $comment;
}

final class Course {
	public static function getCourseByID($id) {
		$sql = 'SELECT * FROM Courses WHERE courseID = :id';
		$args = array(':id' => $id);
		$row = Database::executeGetRow($sql, $args);
		return new Course($row);
	}
	
	public static function getAllCourses() {
		$sql = "Select * from Courses group by courseTitle";
		$args = array();
		$rows = Database::executeGetAllRows($sql,$args);
		return $rows; 
	}
	
	public static function insertCourse($crn, $department, $courseNumber, $courseTitle, $website, $termID) {
		$sql = 'INSERT INTO Courses (crn, department, courseNumber, courseTitle, website, termID)
				VALUES (:crn, :department, :courseNumber, :courseTitle, :website, :termID)';
		$args = array(':crn' => $crn, ':department' => $department, ':courseNumber' => $courseNumber, ':courseTitle' => $courseTitle, ':website' => $website, ':termID' => $termID);
		$courseID = Database::executeInsert($sql, $args);
		return $courseID;
	}
	
	public static function getCourseProfessors($courseTitle){
		$sql = 'SELECT firstName,lastName 
			FROM Users,Professors,Teaches,Courses
			WHERE Users.userID = Professors.userID 
			AND Professors.userID = Teaches.professorID
			AND Courses.courseID = Teaches.courseID
			AND Courses.courseTitle = :courseTitle ';
		$args = array(':courseTitle' => $courseTitle);
		$rows = Database::executeGetAllRows($sql,$args);
		return $rows;

	}

	public function __construct($row) {
		$this->id = $row['courseID'];
		$this->crn = $row['crn'];
		$this->department = $row['department'];
		$this->courseNumber = $row['courseNumber'];
		$this->courseTitle = $row['courseTitle'];
		$this->website = $row['website'];
		$this->termID = $row['termID'];
		$this->term = null;
	}

	public function getTotalPositions($prof = null) {
		if ($prof == null) {
			$sql = 'SELECT COUNT(*) FROM Positions
				WHERE courseID = :course_id';
			$args = array(':course_id' => $this->id);
		} else {
			$sql = 'SELECT COUNT(*) FROM Positions
				WHERE professorID = :prof_id AND courseID = :course_id';
			$args = array(':prof_id' => $prof->getID(), ':course_id' => $this->id);
		}
		return Database::executeGetScalar($sql, $args);
	}
	
	public function getTotalPositionsByType($professor, $type){
		$sql = 'SELECT COUNT(*) FROM Positions
			WHERE courseID = :course_id AND professorID = :prof_id AND posType = :pos_type';
		$args = array(':course_id' => $this->id, ':prof_id' => $professor->getID(),':pos_type' => $type);
		return Database::executeGetScalar($sql, $args);
		
	}
	
	public function getCurrentPositionsByType($professor, $type){
		$sql = 'SELECT COUNT(*) FROM Positions
			INNER JOIN Applications ON Positions.positionID = Applications.positionID
			WHERE Positions.courseID = :course_id AND Positions.professorID = :prof_id 
			AND Positions.posType = :pos_type AND Applications.appStatus = 3';
		$args = array(':course_id' => $this->id, ':prof_id' => $professor->getID(),':pos_type' => $type);
		return Database::executeGetScalar($sql, $args);	
	}


	public function getID() { return $this->id; }
	public function getCRN() { return $this->crn; }
	public function getDepartment() { return $this->department; }
	public function getNumber() { return $this->courseNumber; }
	public function getTitle() { return $this->courseTitle; }
	public function getWebsite() { return $this->website; }
	public function getTerm() {
		if ($this->term == null) {
			$this->term = Term::getTermByID($this->termID);
		}
		return $this->term;
	}

	private $id;
	private $crn;
	private $department;
	private $courseNumber;
	private $title;
	private $website;
	private $term;
	private $termID;
}

/** Connect to the database and create/use PDO object. */
Database::connect();

/********************
* SESSION FUNCTIONS *
********************/

/* Function login
*  Purpose: Logs a user in.  Verifies that user's input password field against
*           a hashed password stored in the database.
*  Returns: nothing.
**/
function login($email, $input_password) {
	if ($user_obj = User::getUserByEmail($email)) {
		if (password_verify($input_password, $user_obj->getPassword())) {
			beginSession($email);
			return $user_obj;
		}
	}
	return false;
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


/********************
* END LOGIN FUNCTIONS
*********************/

