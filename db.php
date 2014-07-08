<?php

require_once 'plugins/password_compat/password.php';
require_once 'error.php';
require_once 'session.php';

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
			$error = new TarsException(Event::SERVER_DBERROR, Event::SERVER_DBERROR, $ex);
			echo $error->toHTML();
			exit;
		}
	}

	/**
	 * Database::isConnected()
	 * Purpose: Tells us whether Database::connect() has succeeded
	 * Returns: true or false
	 */
	public static function isConnected() {
		return Database::$db_conn != null;
	}

	/**
	 * Database::executeStatement($sql, $args)
	 * Purpose: Prepares and executes a statement with the specified arguments.
	 * Returns: The statement object, for fetching.
	 * Throws:  A PDOException if prepare() or execute() fail (SQL syntax or database error).
	 */
	public static function execute($sql, $args) {
		//echo "<pre>EXECUTE: $sql\n";var_dump($args);echo '</pre>';

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
	 * Returns: The row requested, or NULL if no rows returned
	 * Throws:  A PDOException if prepare() or execute() fail (SQL syntax or database error).
	 */
	public static function executeGetRow($sql, $args) {
		/* create and execute the statement object */
		$stmt = Database::execute($sql, $args);

		/* fetch result: return first row, or null */
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($result === null) {
			/* empty result set */
			return null;
		}

		return $result;
	}

	/**
	 * Database::executeGetAllRows($sql, $args)
	 * Purpose: Runs a prepared statement with the given SQL statement and arguments.
	 *          Gets all selected rows of the database.
	 * Returns: The rows requested
	 * Throws:  A PDOException if prepare() or execute() fail (SQL syntax or database error).
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
	 * Returns: The scalar requested, or NULL if no rows returned
	 * Throws:  A PDOException if prepare() or execute() fail (SQL syntax or database error).
	 */
	public static function executeGetScalar($sql, $args) {
		/* create and execute the statement object */
		$stmt = Database::execute($sql, $args);

		/* fetch result: return first row, or null */
		$result = $stmt->fetch(PDO::FETCH_NUM);
		if ($result === null) {
			/* empty result set */
			return null;
		}

		return $result[0];
	}

	/**
	 * Database::executeInsert($sql, $args)
	 * Purpose: Runs a prepared statement with the given SQL statement and arguments.
	 *          Gets the inserted ID
	 * Returns: The last ID generated for an AUTO_INCREMENT column; the ID of the column inserted.
	 * Throws:  A PDOException if prepare() or execute() fail (SQL syntax or database error).
	 * Note:    Yes, you can run non-INSERT queries with this.
	 *          It's silly to though, as why else do you need the last ID inserted?
	 */
	public static function executeInsert($sql, $args) {
		/* create and execute the statement object */
		Database::execute($sql, $args);

		/* get the inserted ID */
		return Database::$db_conn->lastInsertId();
	}

	/**
	 * Database::beginTransaction()
	 * Purpose: Starts a PDO transaction
	 */
	public static function beginTransaction() {
		return Database::$db_conn->beginTransaction();
	}

	/**
	 * Database::rollbackTransaction()
	 * Purpose: Cancels a PDO transaction; no database changes will be made
	 */
	public static function rollbackTransaction() {
		return Database::$db_conn->rollback();
	}

	/**
	 * Database::commitTransaction()
	 * Purpose: Commits a PDO transaction; database changes will be made atomically
	 */
	public static function commitTransaction() {
		return Database::$db_conn->commit();
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
	
	public static function getAllBuildings(){
		$sql = 'SELECT DISTINCT building FROM Places';
		$args = array();
		$rows = Database::executeGetAllRows($sql, $args);
		return $rows;
	}

	public static function getOrCreatePlace($building, $room) {
		$args = array(':building' => $building, ':room' => $room);
		$sql = 'SELECT placeID FROM Places WHERE building = :building AND room = :room';
		$rowID = Database::executeGetScalar($sql, $args);
		if ($rowID === null) {
			$sql = 'INSERT INTO Places (building, room) VALUES (:building, :room)';
			return Database::executeInsert($sql, $args);
		} else {
			return $rowID;
		}
	}

	private function __construct($row) {
		$this->placeID = $row['placeID'];
		$this->building = $row['building'];
		$this->room = $row['room'];
	}

	public function getBuilding() { return $this->building; }
	public function getRoom() { return $this->room; }
	public function getPlaceID() { return $this->placeID; }

	private $building;
	private $room;
	private $placeID;
}

/*
 * User Object: Represents a User in the database.
 *
 * This is a Primary Object: it has a creator and createTime field
 */
abstract class User {
	/**
	 * User::getUserByID($id, [$type])
	 *
	 * Purpose: get a User subclass object (Student, Professor, Staff, Admin) by ID
	 * Returns: the wanted object, or NULL on no match
	 * Throws:  PDOException on SERVER_DBERROR
	 * Note:    $type, if set, restricts the returned object to be of that user type.
	 *          If the retrieved user is of the wrong type, it returns NULL.
	 */
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
			return null;
		}
	}

	/**
	 * User::getUserByEmail($email, [$type])
	 *
	 * Purpose: get a User subclass object (Student, Professor, Staff, Admin) by email
	 * Returns: the wanted object, or NULL on no match
	 * Throws:  PDOException on SERVER_DBERROR
	 * Note:    $type, if set, restricts the returned object to be of that user type.
	 *          If the retrieved user is of the wrong type, it returns NULL.
	 */
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
			return null;
		}
	}

	/**
	 * PRIVATE User::getUserSubclassObject($row)
	 *
	 * Purpose: get a User subclass object from the Users table row
	 * Returns: the wanted object, or NULL on usertype mismatch or missing data
	 * Throws:  PDOException on SERVER_DBERROR
	 */
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
		return null;
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

	public static function insertUserSelfCreated($email, $password, $firstName, $lastName, $type) {
		$sql = 'INSERT INTO Users
				(email, emailVerified, password, passwordReset,
				firstName, lastName, createTime, type) VALUES
				(:email, :eVer, :password, :pRes, :firstName, :lastName, :ctime, :type)';
		$args = array(':email' => $email, ':eVer' => 0, ':password' => $password,
				':pRes' => 0, ':firstName' => $firstName, ':lastName' => $lastName,
				':ctime' => date('Y-m-d H:i:s'), ':type' => $type);
		return Database::executeInsert($sql, $args);
	}

	public static function checkEmailAvailable($email) {
		if (empty($email)) {
			// empty email unavailable
			return false;
		} else {
			$count = Database::executeGetScalar('SELECT COUNT(*) FROM Users WHERE email = :email',
				array(':email' => $email));
			return $count == 0;
		}
	}

	protected function __construct($user_row) {
		$this->id = $user_row['userID'];
		$this->email = $user_row['email'];
		$this->password = $user_row['password'];
		$this->otype = $user_row['type'];
		$this->firstName = $user_row['firstName'];
		$this->lastName = $user_row['lastName'];
		$this->creatorID = $user_row['creatorID'];
		$this->creator = null;
		$this->createTime = strtotime($user_row['createTime']);
	}

	public function getID() { return $this->id; }
	public function getEmail() { return $this->email; }
	public function getPassword() { return $this->password; }
	public function getObjectType() { return $this->otype; }
	public function getFirstName() { return $this->firstName; }
	public function getLastName() { return $this->lastName; }
	public function geCreator() {
		if ($this->creator == null && $this->creatorID != null) {
			$this->creator = User::getUserByID($this->creatorID);
		}
		return $this->creator;
	}
	public function getCreateTime() { return $this->createTime; }

	/* "first-initial last" name */
	public function getFILName() {
		return $this->firstName[0].'. '.$this->lastName;
	}

	/* first name space last name */
	public function getName() {
		return $this->firstName.' '.$this->lastName;
	}

	protected $id;
	protected $email;
	protected $password;
	protected $otype;
	protected $firstName;
	protected $lastName;
	protected $creatorID;
	protected $creator;
	protected $createTime;
}

final class Student extends User {
	public static function registerStudent($email, $password, $firstName, $lastName,
		$mobilePhone, $classYear, $major, $gpa, $universityID, $aboutMe) {

		$password_hash = password_hash($password, PASSWORD_DEFAULT);

		$userID = parent::insertUserSelfCreated($email, $password_hash, $firstName, $lastName, STUDENT);

		$sql = 'INSERT INTO Students
				(userID, mobilePhone, classYear, major, gpa, universityID, aboutMe) VALUES
				(:id, :mobilePhone, :classYear, :major, :gpa, :universityID, :aboutMe)';
		$args = array(':id' => $userID, ':mobilePhone' => $mobilePhone, 
				':classYear' => $classYear, ':major' => $major, ':gpa' => $gpa,
				':universityID' => $universityID, ':aboutMe' => $aboutMe);
		Database::executeInsert($sql, $args);

		Event::insertEvent(Event::USER_CREATE, "$firstName $lastName created a STUDENT acocunt.",
			$userID);

		return $userID;
	}

	public static function getStudentsToReview(){
		$sql = 'SELECT userID FROM Students';
		$args = array();
		$rows = Database::executeGetAllRows($sql,$args);
		return array_map(function ($row) { return User::getUserByID($row['userID']);}, $rows);
	}
	
	public function __construct($user_row, $student_row) {
		parent::__construct($user_row);

		if ($student_row) {
			$this->mobilePhone = $student_row['mobilePhone'];
			$this->classYear = $student_row['classYear'];
			$this->major = $student_row['major'];
			$this->gpa = $student_row['gpa'];
			$this->universityID = $student_row['universityID'];
			$this->aboutMe = $student_row['aboutMe'];
		}
	}

	public function setStudentStatus($userID,$status){
		$sql = "UPDATE Students SET status = :status WHERE userID = :userID";
		$args = array(':status' => $status,':userID' => $userID);
		Database::execute($sql,$args);
	}
		
	public function getApplications($term, $status) {
		return Application::getApplications(null, null, $term, $status);
	}
	

	public function apply($positionID, $compensation, $qualifications) {
		$applicationID = Application::insertApplication($positionID, $compensation,
			$qualifications, PENDING, $this->id, time());
		Event::insertEvent(Event::STUDENT_APPLY, $this->getName().' applied to a position. '.
			'Application object created.', $applicationID);
	}
	
	public function withdraw($positionID){
		Application::setPositionStatus($this->id, $positionID, WITHDRAWN);
		Event::insertEvent(Event::STUDENT_WITHDRAW, $this->getName().' withdrew an application. '.
			'Application object updated.', null);
	}
	
	public function getAllComments(){
		return Comment::getAllComments($this->id);
	}

	public function saveComment($comment, $creator, $createTime = null) {
		if ($createTime == null) {
			$createTime = time();
		}

		$commentID = Comment::insertComment($comment, $this->id, $creator->getID(), $createTime);

		Event::insertEvent(Event::NONSTUDENT_COMMENT, $creator->getName().' commented on '.
			'student '.$this->getName().'. Comment object created.', $commentID);

		return $commentID;
	}

	public function updateProfile($firstName, $lastName, $mobilePhone,
		$classYear, $major, $gpa, $universityID, $aboutMe) {
		$sql = 'UPDATE Students
				INNER JOIN Users ON Users.userID = Students.userID
				SET firstName = :firstName, lastName = :lastName,
					mobilePhone = :mobilePhone, classYear = :classYear, major = :major,
					gpa = :gpa, universityID = :universityID, aboutMe = :aboutMe
				WHERE Users.userID = :id';
		$args = array(':id' => $this->id, ':firstName' => $firstName,
				':lastName' => $lastName, ':mobilePhone' => $mobilePhone, 
				':classYear' => $classYear, ':major' => $major, ':gpa' => $gpa,
				':universityID' => $universityID, ':aboutMe' => $aboutMe);
		Database::execute($sql, $args);

		Event::insertEvent(Event::USER_SET_PROFILE, "$firstName $lastName updated their ".
			'profile data.', $this->id);
	}

	public function getMobilePhone() { return $this->mobilePhone; }
	public function getMajor() { return $this->major; }
	public function getGPA() { return $this->gpa; }
	public function getClassYear() { return $this->classYear; }
	public function getAboutMe() { return $this->aboutMe; }
	public function getUniversityID() { return $this->universityID; }

	private $mobilePhone;
	private $major;
	private $gpa;
	private $classYear;
	private $aboutMe;
	private $universityID;
}

final class Professor extends User {
	public static function registerProfessor($email, $password, $firstName, $lastName,
		$officeID, $officePhone) {

		$password_hash = password_hash($password, PASSWORD_DEFAULT);

		$userID = parent::insertUserSelfCreated($email, $password_hash, $firstName, $lastName, PROFESSOR);

		$sql = 'INSERT INTO Professors
				(userID, officeID, officePhone) VALUES
				(:id, :officeID, :officePhone)';
		$args = array(':id' => $userID, ':officeID' => $officeID,
				':officePhone' => $officePhone);
		Database::executeInsert($sql, $args);

		return $userID;
	}

	public function __construct($user_row, $professor_row) {
		parent::__construct($user_row);

		if ($professor_row) {
			$this->officeID = $professor_row['officeID'];
			$this->office = null;
			$this->officePhone = $professor_row['officePhone'];
		}
	}

	public static function getAllProfessors(){
		$sql = "SELECT * FROM Users 
			INNER JOIN Professors on Professors.userID = Users.userID";
		$args = array();
		$rows = Database::executeGetAllRows($sql,$args);
		return array_map(function ($row) { return new Professor($row); }, $rows );
	}
		
	public function updateProfile($firstName, $lastName, $officeID, $officePhone) {
		$sql = 'UPDATE Professors
				INNER JOIN Users ON Users.userID = Professors.userID
				SET firstName = :firstName, lastName = :lastName,
					officeID = :officeID, officePhone = :officePhone
				WHERE Users.userID = :id';
		$args = array(':id'=>$this->id, ':firstName'=>$firstName, ':lastName'=>$lastName,
			':officeID' => $officeID, ':officePhone' => $officePhone);
		Database::execute($sql, $args);

		Event::insertEvent(Event::USER_SET_PROFILE, "$firstName $lastName updated their ".
			'profile data.', $this->id);
	}

	public function getSections() {
		$sql = 'SELECT * FROM Sections
				INNER JOIN Teaches ON Teaches.sectionID = Sections.sectionID
				INNER JOIN Courses ON Courses.courseID = Sections.courseID
				WHERE Teaches.professorID = :prof_id
				ORDER BY Courses.department DESC, Courses.courseNumber ASC, Sections.crn ASC';
		$args = array(':prof_id' => $this->id);
		$rows = Database::executeGetAllRows($sql, $args);
		return array_map(function ($row) { return new Section($row); }, $rows);
	}


	public function getOffice() {
		if ($this->office == null) {
			$this->office = Place::getPlaceByID($this->officeID);
		}
		return $this->office;
	}
	
	public function getOfficeID() { return $this->officeID; }
	public function getOfficePhone() { return $this->officePhone; }

	private $officeID;
	private $office;
	private $officePhone;
}

final class Staff extends User {
	public static function registerStaff($email, $password, $firstName, $lastName,
		$officePhone, $mobilePhone) {

		$password_hash = password_hash($password, PASSWORD_DEFAULT);

		$userID = parent::insertUserSelfCreated($email, $password_hash, $firstName, $lastName, STAFF);

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
		}
	}

	public function getOfficePhone() { return $this->officePhone; }

	private $officePhone;
}

final class Admin extends User {
	public function __construct($user_row, $admin_row) {
		parent::__construct($user_row);
	}
}

final class Position {
	public static function getPositionByID($id) {
		$args = array(':id' => $id);
		$sql = 'SELECT * FROM Positions
				INNER JOIN PositionTypes ON PositionTypes.positionTypeID = Positions.positionTypeID
				WHERE positionID = :id';
		$row = Database::executeGetRow($sql, $args);
		return new Position($row);
	}

	public static function getAllPositionTypes($useDisplayName = false) {
		$field = 'positionName';
		if ($useDisplayName) {
			$field = 'positionTitle';
		}
		$sql = "SELECT positionTypeID, $field FROM PositionTypes";
		$rows = Database::executeGetAllRows($sql, array());
		$results = array();
		foreach ($rows as $row) {
			$results[$row['positionTypeID']] = $row[$field];
		}
		return $results;
	}

	public static function getPositionTypeID($typeName) {
		$sql = 'SELECT positionTypeID FROM PositionTypes WHERE positionName = :type';
		$args = array(':type' => $typeName);
		return Database::executeGetScalar($sql, $args);
	}

	public static function insertPosition($sectionID, $type, $max, $creator, $createTime) {
		$sql = 'INSERT INTO Positions
				(sectionID, maximumAccepted, positionTypeID, creatorID, createTime) VALUES
				(:section, :max, :type, :creator, :createTime)';
		$args = array(':section' => $sectionID, ':max' => $max,
			':type' => Position::getPositionTypeID($type), ':creator' => $creator->getID(),
			':createTime' => date('Y-m-d H:i:s', $createTime));
		$positionID = Database::executeInsert($sql, $args);
		return $positionID;
	}

	public static function findPositions($search_field, $term = null, $position_type = null, $studentID) {
		$sql = 'SELECT 
					positionID, Positions.sectionID, maximumAccepted,
					Positions.positionTypeID, positionName, positionTitle,
					responsibilities, times, compensation,
					department, courseNumber, courseTitle, termID,
					Positions.creatorID, Positions.createTime,
					GROUP_CONCAT(DISTINCT Users.firstName SEPARATOR \' \') AS fnList,
					GROUP_CONCAT(DISTINCT Users.lastName SEPARATOR \' \') AS lnList
				FROM Positions
				INNER JOIN PositionTypes ON PositionTypes.positionTypeID = Positions.positionTypeID
				INNER JOIN Sections ON Positions.sectionID = Sections.sectionID
				INNER JOIN Courses ON Sections.courseID = Courses.courseID
				LEFT JOIN Teaches ON Teaches.sectionID = Sections.sectionID
				LEFT JOIN Users ON Users.userID = Teaches.professorID
				GROUP BY positionID HAVING ';
		$args = array();
		$sql_having = '';
		if (!empty($search_field)) {
			$i = 1;
			foreach (explode(' ', $search_field) as $word) {
				$sql .= "(department = :word$i OR
					courseNumber = :word$i OR
					INSTR(courseTitle, :word$i) OR
					CONCAT(department, courseNumber) = :word$i OR
					INSTR(fnList, :word$i) OR
					INSTR(lnList, :word$i)) AND ";
				$args[":word$i"] = $word;
				$i++;
			}
		}
		if ($term != null) {
			$sql .= 'termID = :term AND ';
			$args[':term'] = $term;
		}
		if ($position_type != null && $position_type > 0) {
			$sql .= 'Positions.positionTypeID = :posType AND ';
			$args[':posType'] = $position_type;
		}
		$sql .= "1
			ORDER BY Courses.department DESC, Courses.courseNumber ASC, Sections.crn ASC";
			
		//echo '<pre>';
		//print_r($args);
		//exit($sql);
		$rows = Database::executeGetAllRows($sql, $args);
		return array_map(function ($row) { return new Position($row); }, $rows);
	}
	

	private function __construct($row) {
		$this->id = $row['positionID'];
		$this->sectionID = $row['sectionID'];
		$this->section = null;
		$this->maximumAccepted = $row['maximumAccepted'];
		$this->type = $row['positionTypeID'];
		$this->typeName = $row['positionName'];
		$this->typeTitle = $row['positionTitle'];
		$this->typeResp = $row['responsibilities'];
		$this->typeTimes = $row['times'];
		$this->typeComp = $row['compensation'];
		$this->creatorID = $row['creatorID'];
		$this->creator = null;
		$this->createTime = strtotime($row['createTime']);
	}

	public function hasStudentApplied($student) {
		$sql = 'SELECT COUNT(*)	FROM Applications
				WHERE positionID = :position AND creatorID = :student';
		$args = array(':position' => $this->id, ':student' => $student->getID());
		return Database::executeGetScalar($sql, $args) != 0;
	}

	public function getID() { return $this->id; }
	public function getSection() {
		if ($this->section == null) {
			$this->section = Section::getSectionByID($this->sectionID);
		}
		return $this->section;
	}
	public function getType() { return $this->type; }
	public function getTypeName() { return $this->typeName; }
	public function getTypeTitle() { return $this->typeTitle; }
	public function getTypeResponsibilities() { return $this->typeResp; }
	public function getTypeTimes() { return $this->typeTimes; }
	public function getTypeCompensation() { return $this->typeComp; }
	public function getCreator() {
		if ($this->creator == null) {
			$this->creator = User::getUserByID($this->creatorID);
		}
		return $this->creator;
	}
	public function getCreateTime() { return $this->createTime; }

	private $id;
	private $sectionID;
	private $section;
	private $type;
	private $typeName;
	private $typeTitle;
	private $typeResp;
	private $typeTimes;
	private $typeComp;
	private $maximumAccepted;
	private $creatorID;
	private $creator;
	private $createTime;
}

final class Application {

	public static function getApplicationByID($id) {
		$sql = 'SELECT * FROM Applications WHERE appID = :id';
		$args = array(':id' => $id);
		$row = Database::executeGetRow($sql, $args);
		return new Application($row);
	}

	private static function generateGetApplicationsRequest($section, $professor, $term, $status, $compensation, $is_count) {
		$sql_where = '';
		$sql_ij_t = false;
		$sql_ij_teaches = '';
		$args = array();
		if ($section != null) {
			$sql_where .= 'Positions.sectionID = :section AND ';
			$args[':section'] = $section->getID();
		}
		if ($professor != null) {
			$sql_where .= 'Teaches.professorID = :professor AND ';
			$sql_ij_t = true;
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
			$sql_sel = 'appID, Applications.positionID, compensation, appStatus, qualifications, '.
			   'Applications.creatorID, Applications.createTime';
		}
		if ($sql_ij_t) {
			$sql_ij_teaches = 'INNER JOIN Teaches ON Teaches.sectionID = Sections.sectionID';
		}
		$sql = "SELECT $sql_sel FROM Applications
				INNER JOIN Positions ON Applications.positionID = Positions.positionID
				INNER JOIN Sections ON Positions.sectionID = Sections.sectionID
				INNER JOIN Courses ON Sections.courseID = Courses.courseID
				$sql_ij_teaches
				WHERE $sql_where 1
				ORDER BY Courses.department DESC, Courses.courseNumber ASC, Sections.crn ASC";
		//echo "<pre>";
		//print_r($args);
		//exit($sql);
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
	 * General purpose function to get application objects.
	 *
	 */
	public static function getApplications($course = null, $professor = null, $term = null, $status = -1, $compensation = null) {
		list($sql, $args) = Application::generateGetApplicationsRequest(
			$course, $professor, $term, $status, $compensation, false);
		$rows = Database::executeGetAllRows($sql, $args);
		return array_map(function ($row) { return new Application($row); }, $rows);
	}

	public static function insertApplication($positionID, $comp, $qual, $status, $creatorID, $createTime) {
		$sql = 'INSERT INTO Applications
				(positionID, compensation, appStatus, qualifications, creatorID, createTime) VALUES
				(:position, :comp, :status, :qual, :creator, :createTime)';
		$args = array(':position' => $positionID, ':comp' => $comp, ':status' => $status,
			':qual' => $qual, ':creator' => $creatorID,
			':createTime' => date('Y-m-d H:i:s', $createTime));
		return Database::executeInsert($sql, $args);
	}

	public static function setPositionStatus($studentID, $positionID, $status) {
		$sql = 'UPDATE Applications
				SET appStatus = :status
				WHERE creatorID = :student_id AND positionID = :position_id';
		$args = array(':status' => $status,	':student_id' => $studentID,
			':position_id' => $positionID);
		Database::execute($sql, $args);
	}

	public static function setApplicationStatus($student,$position,$status){
		$sql = 'UPDATE Applications
				SET appStatus = :status
				WHERE studentID = :student_id AND positionID = :position_id';
		$args = array(':status' => $status,	':student_id' => $student->getID(),
			':position_id' => $position->getID());
		Database::execute($sql, $args);
	}

	public function __construct($row) {
		$this->id = $row['appID'];
		$this->positionID = $row['positionID'];
		$this->position = null;
		$this->compensation = $row['compensation'];
		$this->appStatus = $row['appStatus'];
		$this->qualifications = $row['qualifications'];
		$this->creatorID = $row['creatorID'];
		$this->creator = null;
		$this->createTime = strtotime($row['createTime']);
	}

	public function getID() { return $this->id; }
	public function getPosition() {
		if ($this->position == null) {
			$this->position = Position::getPositionByID($this->positionID);
		}
		return $this->position;
	}
	public function getCompensation() { return $this->compensation; }
	public function getStatus() { return $this->appStatus; }
	public function getQualifications() { return $this->qualifications; }
	public function getCreator() {
		if ($this->creator == null) {
			$this->creator = User::getUserByID($this->creatorID);
		}
		return $this->creator;
	}
	public function getCreateTime() { return $this->createTime; }

	private $id;
	private $position;
	private $positionID;
	private $compensation;
	private $appStatus;
	private $qualifications;
	private $creatorID;
	private $creator;
	private $createTime;
}

final class Term {
	public static function getTermByID($id) {
		$sql = 'SELECT * FROM Terms
				INNER JOIN TermSemesters ON Terms.semesterID = TermSemesters.semesterID
				WHERE termID = :id';
		$args = array(':id' => $id);
		$row = Database::executeGetRow($sql, $args);
		return new Term($row);
	}

	public static function getAllTerms() {
		$sql = 'SELECT * FROM Terms
				INNER JOIN TermSemesters ON Terms.semesterID = TermSemesters.semesterID
				ORDER BY year ASC, semesterIndex ASC';
		$rows = Database::executeGetAllRows($sql, array());
		return array_map(function ($row) { return new Term($row); }, $rows);
	}

	public static function getAllTermSemesters() {
		$sql = 'SELECT semesterName FROM TermSemesters ORDER BY semesterIndex';
		$rows = Database::executeGetAllRows($sql, array());
		return array_map(function ($row) { return $row['semesterName']; }, $rows);
	}

	public static function getOrCreateTermSemester($semesterName) {
		$args = array(':semesterName' => $semesterName);
		$sql = "SELECT semesterID FROM TermSemesters
				WHERE semesterName = :semesterName";
		$rowID = Database::executeGetScalar($sql, $args);
		if ($rowID === null) {
			$args[':semesterIndex'] = 15; // TODO figure out what to default this to
			$sql = 'INSERT INTO TermSemesters
					(semesterName, semesterIndex) VALUES
					(:semesterName, :semesterIndex)';
			return Database::executeInsert($sql, $args);
		} else {
			return $rowID;
		}
	}
	
	public static function insertTerm($year, $semesterName, $creator, $createTime) {
		$sql = 'INSERT INTO Terms
				(year, semesterID, creatorID, createTime) VALUES
				(:year, :semester, :creator, :createTime)';
		$args = array(':year' => $year,
			':semester' => Term::getOrCreateTermSemester($semesterName),
			':creator' => $creator->getID(), ':createTime' => date('Y-m-d H:i:s', $createTime));
		$termID = Database::executeInsert($sql, $args);
		return $termID;
	}


	// expected structure:
	// {"courses":
	// [
	//	   {"department":"CSC",
	//	   "number":"172H",
	//	   "title":"The Science of Programming Honors",
	//     "sections":
	//     [
	//         {"crn":"30303",
	//         "instructors":["koomen@cs.rochester.edu","brown@cs.rochester.edu"],
	//         "type":"lab",
	//         "sessions":
	//         [
	//             {"days":"MW","startTime":"16:50","endTime":"18:05",
	//             "building":"Gavett","room":"224"},
	//             ...
	//         ],
	//         "positions":
	//         [
	//             {"positionType":"lab","comment":"lab 1","maxPositions":3}
	//         ]
	//         },
	//         {"crn":"30301",
	//         "instructors":["brown@cs.rochester.edu"],
	//         "type":"lecture",
	//         "sessions":
	//         [
	//             {"days":"TR","startTime":"16:50","endTime":"18:05",
	//             "building":"CSB","room":"601"},
	//             ...
	//         ],
	//         "positions":
	//         [
	//             {"positionType":"wsl","comment":"workshop ldrs","maxPositions":9},
	//             {"positionType":"wssl","comment":"workshop sl","maxPositions":1},
	//             {"positionType":"lect","comment":"lecture ta ex","maxPositions":2},
	//             {"positionType":"grader","comment":"grader ex","maxPositions":2}
	//         ]
	//         },
	//         ...
	//     ]
	//     },
	//     ...
	// ]
	// }
	public static function importTerm($termYear, $termSemester, $json_object) {
		try {
			$creator = Session::getLoggedInUser(STAFF);
			$createTime = time();

			Database::beginTransaction();

			$termID = Term::insertTerm($termYear, strtolower($termSemester), $creator, $createTime);
			foreach ($json_object as $course) {
				foreach ($course['sections'] as $section) {
					$sectionID = Section::insertSection($termID,
						strtoupper($course['department']), strtoupper($course['number']),
						$course['title'], $section['crn'], $section['type'], $creator, $createTime);
					foreach ($section['sessions'] as $session) {
						foreach (str_split($session['days']) as $day) {
							Section::insertSession($sectionID, $day,
								$session['startTime'], $session['endTime'],
								strtoupper($session['building']), strtoupper($session['room']));
						}
					}
					foreach ($section['positions'] as $position) {
						$positionID = Position::insertPosition($sectionID,
							$position['positionType'], $position['maxPositions'],
							$creator, $createTime);
					}
					foreach ($section['instructors'] as $instructor) {
						$professor = User::getUserByEmail($instructor, PROFESSOR);
						if ($professor) {
							Section::insertTeachesRelation($sectionID, $professor->getID());
						} else {
							// right now it ignores missing professor accounts
							// throw error or add?
						}
					}
				}
			}

			Configuration::set(Configuration::CURRENT_TERM, $termID,
				$creator, $createTime);
			Event::insertEvent(Event::STAFF_TERM_IMPORT, 'Imported a new '.
				'Term. Term object created.', $termID, $createTime, null, $creator);
		} catch (PDOException $ex) {
			Database::rollbackTransaction();
			throw $ex;
		}
		Database::commitTransaction();
		return $termID;
	}

	public static function importTermFromCSV($termYear, $termSemester, $lines, $uploadData) {
		$positionTypes = Position::getAllPositionTypes();
		$headerLine = true;
		$headers = array(); // the first line of CSV, used as column names
		$courses = array(); // output JSON-like array
		$courseObj = null; // element in $courses being updated
		foreach ($lines as $line) {
			$csv_line = str_getcsv($line);
			if ($headerLine) {
				$headers = $csv_line;
				$headerLine = false;
			} else {
				$courseLiveObj = array('department' => null, 'number' => null, 'title' => null);
				$sectionLiveObj = array('crn' => null, 'type' => null,
					'instructors' => array(), 'sessions' => array(), 'positions' => array());
				$i = 0;
				foreach ($csv_line as $cell) {
					if (isset($headers[$i]) && !empty($cell)) {
						$header = $headers[$i];
						$headerMult = 0;

						// support 0-9
						if (is_numeric(substr($header, -1))) {
							$headerMult = intval(substr($header, -1)) - 1;
							if ($headerMult == 0) $headerMult = 9;
							$header = substr($header, 0, -1);
						}
						
						if (substr($header, 7) == 'Session') {
							if (!isset($sectionLiveObj['sessions'][$headerMult])) {
								$sectionLiveObj['sessions'][$headerMult] = array(
									'days' => null, 'startTime' => null, 'endTime' => null,
									'building' => null, 'room' => null);
							}
						}

						switch ($header) {
						case 'CourseDepartment':
							$courseLiveObj['department'] = $cell;
							break;
						case 'CourseNumber':
							$courseLiveObj['number'] = $cell;
							break;
						case 'CourseTitle':
								$courseLiveObj['title'] = $cell;
							break;
						case 'SectionCRN':
							$sectionLiveObj['crn'] = $cell;
							break;
						case 'SectionType':
								$sectionLiveObj['type'] = ($cell == 'lab') ? 'lab' : 'lecture';
							break;
						case 'Instructor':
								$sectionLiveObj['instructors'][] = $cell;
							break;
						case 'SessionDays':
								$sectionLiveObj['sessions'][$headerMult]['days'] = $cell;
							break;
						case 'SessionTimeStart':
								$sectionLiveObj['sessions'][$headerMult]['startTime'] = $cell;
							break;
						case 'SessionTimeEnd':
								$sectionLiveObj['sessions'][$headerMult]['endTime'] = $cell;
							break;
						case 'SessionBuilding':
								$sectionLiveObj['sessions'][$headerMult]['building'] = $cell;
							break;
						case 'SessionRoom':
								$sectionLiveObj['sessions'][$headerMult]['room'] = $cell;
							break;
						default:
							if (strlen($header) > 0 && substr($header, 0, 1) == '#') {
								if (is_numeric($cell) && intval($cell) > 0 &&
									in_array(substr($header, 1), $positionTypes)) {
									$sectionLiveObj['positions'][] = array(
										'positionType' => strtolower(substr($header, 1)),
										'maxPositions' => intval($cell));
								}
							}
						}
					}


					$i++;
				}

				// re-index section.sessions so it produces an array instead of an object
				$sectionLiveObj['sessions'] = array_values($sectionLiveObj['sessions']);

				if ($courseLiveObj['department'] == null || $courseLiveObj['number'] == null ||
					$sectionLiveObj['crn'] == null) {
					// column required for CourseDepartment, CourseNumber or SectionCRN
					// ignore row
					// throw error?
				} else {
					$courseIndex = -1;
					for ($x = 0; $x < count($courses); $x++) {
						if ($courses[$x]['department'] == $courseLiveObj['department'] &&
							$courses[$x]['number'] == $courseLiveObj['number']) {
							$courseIndex = $x;
						}
					}
					if ($courseIndex < 0) {
						$courseIndex = count($courses);
						$courses[] = array('department' => $courseLiveObj['department'],
							'number' => $courseLiveObj['number'],
							'title' => $courseLiveObj['title'],
							'sections' => array($sectionLiveObj));
					} else {
						$courses[$courseIndex]['sections'][] = $sectionLiveObj;
					}
				}
			}
		}
		Term::importTerm($termYear, $termSemester, $courses);
	}

	public function __construct($row) {
		$this->id = $row['termID'];
		$this->year = $row['year']; // Term.year
		$this->semester = $row['semesterName'];
		$this->semesterID = $row['semesterID'];
		$this->semesterIndex = $row['semesterIndex'];
		$this->creatorID = $row['creatorID'];
		$this->creator = null;
		$this->createTime = strtotime($row['createTime']);
	}

	public function getID() { return $this->id; }
	public function getYear() { return $this->year; }
	public function getSemester() { return $this->semester; }
	public function getTermSemesterID() { return $this->semesterID; }
	public function getTermSemesterIndex() { return $this->semesterIndex; }
	public function getCreator() {
		if ($this->creator == null) {
			$this->creator = User::getUserByID($this->creatorID);
		}
		return $this->creator;
	}
	public function getCreateTime() { return $this->createTime; }
	public function getName() {
		return ucfirst($this->semester).' '.$this->year;
	}

	private $id;
	private $year;
	private $semester;
	private $semesterID;
	private $semesterIndex;
	private $creatorID;
	private $creator;
	private $createTime;
}

final class Comment {
	public static function getCommentByID($commentID){
		$sql = "SELECT * FROM Comments WHERE commentID = :commentID";
		$args = array('commentID' => $commentID);
		$row = Database::executeGetRow($sql, $args);
		return new Comment($row);
	}
	
	public static function getAllComments($studentID){
		$sql = 'SELECT * FROM Comments
   				WHERE studentID = :studentID';
		$args = array(':studentID' => $studentID);
   		$rows = Database::executeGetAllRows($sql, $args);
   		return array_map(function($row) { return new Comment($row); }, $rows);
	}
	
	public static function insertComment($comment, $studentID, $creatorID, $createTime) {
		$sql = "INSERT INTO Comments (commentText, studentID, creatorID, createTime) VALUES
				(:comment, :student, :creator, :createTime)";
		$args = array(':comment' => $comment, ':student' => $studentID,
				':creator' => $creatorID, ':createTime' => date('Y-m-d H:i:s', $createTime));
		return Database::executeInsert($sql, $args);
	}
	
	public function __construct($row){
		$this->id = $row['commentID'];
		$this->commentText = $row['commentText'];
		$this->studentID = $row['studentID'];
		$this->creatorID = $row['creatorID'];
		$this->creator = null;
		$this->createTime = $row['createTime'];
	}
	
	public function getID(){ return $this->id; }
	public function getComment(){ return $this->commentText; }
	public function getStudentID(){ return $this->studentID; }
	public function getCreator() {
		if ($this->creator == null) {
			$this->creator = User::getUserByID($this->creatorID);
		}
		return $this->creator;
	}
	public function getCreateTime() { return $this->createTime; }
	
	private $id;
	private $commentText;
	private $studentID;
	private $creatorID;
	private $creator;
	private $createTime;
}

final class SectionSession {
	public static function getSessionByID($id) {
		$sql = 'SELECT * FROM Sessions
				INNER JOIN Places ON Places.placeID = Sessions.placeID
				WHERE sessionID = :id';
		$args = array(':id' => $id);
		$row = Database::executeGetRow($sql, $args);
		return new SectionSession($row);
	}

	/**
	 * Takes a list of SectionSessions and combines the ones with the same everything
	 * except weekdays to be single SectionSessions with the weekdays field containing
	 * multiple letters (sorted by day-of-week order)
	 */
	public static function combineSessions($array) {
		$results = array();
		while (count($array) > 0) {
			$sess = array_shift($array);
			$i = 0;
			while ($i < count($array)) {
				// is the next element equiv. to this one except for the weekday?
				if ($sess->startTime == $array[$i]->startTime &&
					$sess->endTime == $array[$i]->endTime &&
					$sess->placeID == $array[$i]->placeID) {
					// put weekday on $sess
					$sess->weekdays .= $array[$i]->weekdays;
					// throw the element away
					array_splice($array, $i, 1);
				} else {
					$i++;
				}
			}
			$wkdays = str_split($sess->weekdays);
			array_unique($wkdays);
			usort($wkdays, function ($d1, $d2) {
				$map = 'MTWRFSU';
				$d1i = strpos($map, $d1);
				$d2i = strpos($map, $d2);
				return $d1i - $d2i;
			});
			$sess->weekdays = implode('', $wkdays);
			$results[] = $sess;
		}
		return $results;
	}

	public function __construct($row) {
		$this->id = $row['sessionID'];
		$this->sectionID = $row['sectionID'];
		$this->section = null;
		$this->weekdays = $row['weekday'];
		$this->startTime = substr($row['startTime'], 0, 5);
		$this->endTime = substr($row['endTime'], 0, 5);
		$this->placeID = $row['placeID'];
		$this->placeBuilding = $row['building'];
		$this->placeRoom = $row['room'];
	}

	public function getID() { return $this->id; }
	public function getSection() {
		if ($this->section == null) {
			$this->section = Section::getSectionByID($this->sectionID);
		}
		return $this->section;
	}
	public function getWeekdays() { return $this->weekdays; }
	public function getStartTime() { return $this->startTime; }
	public function getEndTime() { return $this->endTime; }
	public function getPlaceBuilding() { return $this->placeBuilding; }
	public function getPlaceRoom() { return $this->placeRoom; }
	public function getDayTimePlace() {
		return "{$this->weekdays} {$this->startTime} - {$this->endTime} in {$this->placeBuilding} {$this->placeRoom}";
	}

	private $id;
	private $sectionID;
	private $section;
	private $weekdays;
	private $startTime;
	private $endTime;
	private $placeBuilding;
	private $placeRoom;
}

final class Section {
	public static function getSectionByID($id) {
		$sql = 'SELECT * FROM Sections
				INNER JOIN Courses ON Courses.courseID = Sections.courseID
				WHERE sectionID = :id';
		$args = array(':id' => $id);
		$row = Database::executeGetRow($sql, $args);
		return new Section($row);
	}

	// TODO: outdated
	public static function getAllSections() {
		$sql = "Select * from Sections group by sectionTitle";
		$args = array();
		$rows = Database::executeGetAllRows($sql,$args);
		return $rows; 
	}

	public static function getOrCreateCourse($termID, $department, $courseNumber, $courseTitle) {
		$args = array(':term'=>$termID, ':department'=>$department, ':number'=>$courseNumber);
		$sql = 'SELECT courseID FROM Courses WHERE termID = :term AND
				department = :department AND courseNumber = :number';
		$rowID = Database::executeGetScalar($sql, $args);
		if ($rowID === null) {
			$args[':title'] = $courseTitle;
			$sql = 'INSERT INTO Courses
					(termID, department, courseNumber, courseTitle) VALUES
					(:term, :department, :number, :title)';
			return Database::executeInsert($sql, $args);
		} else {
			return $rowID;
		}
	}
	
	public static function insertSection($termID, $department, $courseNumber, $courseTitle,
			$crn, $type, $creator, $createTime) {
		$args = array(':course' =>
				Section::getOrCreateCourse($termID, $department, $courseNumber, $courseTitle),
				':crn' => $crn, ':type' => $type, ':creator' => $creator->getID(),
				':createTime' => date('Y-m-d H:i:s', $createTime));
		$sql = 'INSERT INTO Sections (courseID, crn, type, creatorID, createTime) VALUES
				(:course, :crn, :type, :creator, :createTime)';
		$sectionID = Database::executeInsert($sql, $args);
		return $sectionID;
	}
	
	public static function insertSession($sectionID, $day, $startTime, $endTime,
		$building, $room) {
			$args = array(':section' => $sectionID, ':day' => $day, ':startTime' => $startTime,
				':endTime' => $endTime, ':place' => Place::getOrCreatePlace($building, $room));
		$sql = 'INSERT INTO Sessions (sectionID, weekday, startTime, endTime, placeID) VALUES
				(:section, :day, :startTime, :endTime, :place)';
		$sessionID = Database::executeInsert($sql, $args);
		return $sessionID;
	}
	
	public static function insertTeachesRelation($sectionID, $professorID) {
		$args = array(':section' => $sectionID, ':professor' => $professorID);
		$sql = 'INSERT INTO Teaches (sectionID, professorID) VALUES
				(:section, :professor)';
		$teachesID = Database::executeInsert($sql, $args);
		return $teachesID;
	}

	// TODO: outdated
	public static function getSectionProfessors($sectionTitle){
		$sql = 'SELECT firstName,lastName 
			FROM Users,Professors,Teaches,Sections
			WHERE Users.userID = Professors.userID 
			AND Professors.userID = Teaches.professorID
			AND Sections.sectionID = Teaches.sectionID
			AND Sections.sectionTitle = :sectionTitle ';
		$args = array(':sectionTitle' => $sectionTitle);
		$rows = Database::executeGetAllRows($sql,$args);
		return $rows;

	}

	public function __construct($row) {
		$this->id = $row['sectionID'];
		$this->crn = $row['crn'];
		$this->sectionType = $row['type'];
		$this->courseID = $row['courseID'];
		$this->courseDepartment = $row['department'];
		$this->courseNumber = $row['courseNumber'];
		$this->courseTitle = $row['courseTitle'];
		$this->courseTermID = $row['termID'];
		$this->courseTerm = null;
		$this->creatorID = $row['creatorID'];
		$this->creator = null;
		$this->createTime = strtotime($row['createTime']);
	}

	// queries all professors for this section
	public function getAllProfessors() {
		$sql = 'SELECT * FROM Teaches
				INNER JOIN Users ON Users.userID = Teaches.professorID
				INNER JOIN Professors ON Professors.userID = Users.userID
				WHERE sectionID = :id';
		$args = array(':id' => $this->id);
		$rows = Database::executeGetAllRows($sql, $args);
		return array_map(function ($row) { return new Professor($row, $row); }, $rows);
	}

	// queries all sessions for this section
	public function getAllSessions() {
		$sql = 'SELECT * FROM Sessions
				INNER JOIN Places ON Places.placeID = Sessions.placeID
				WHERE sectionID = :id';
		$args = array(':id' => $this->id);
		$rows = Database::executeGetAllRows($sql, $args);
		return array_map(function ($row) { return new SectionSession($row); }, $rows);
	}

	public function getTotalPositions($prof = null) {
		if ($prof == null) {
			$sql = 'SELECT COUNT(*) FROM Positions
				WHERE sectionID = :section_id';
			$args = array(':section_id' => $this->id);
		} else {
			$sql = 'SELECT COUNT(*) FROM Positions
					INNER JOIN Sections ON Sections.sectionID = Positions.sectionID
					INNER JOIN Teaches ON Teaches.sectionID = Sections.sectionID
				WHERE professorID = :prof_id AND Positions.sectionID = :section_id';
			$args = array(':prof_id' => $prof->getID(), ':section_id' => $this->id);
		}
		return Database::executeGetScalar($sql, $args);
	}
	
	public function getTotalPositionsByType($professor, $type){
		$sql = 'SELECT COUNT(*) FROM Positions
					INNER JOIN Sections ON Sections.sectionID = Positions.sectionID
					INNER JOIN Teaches ON Teaches.sectionID = Sections.sectionID
				WHERE Positions.sectionID = :section_id AND professorID = :prof_id AND
				positionTypeID = :pos_type';
		$args = array(':section_id' => $this->id, ':prof_id' => $professor->getID(),':pos_type' => $type);
		return Database::executeGetScalar($sql, $args);
		
	}
	
	public function getCurrentPositionsByType($professor, $type){
		$sql = 'SELECT COUNT(*) FROM Positions
					INNER JOIN Sections ON Sections.sectionID = Positions.sectionID
					INNER JOIN Teaches ON Teaches.sectionID = Sections.sectionID
					INNER JOIN Applications ON Positions.positionID = Applications.positionID
				WHERE Positions.sectionID = :section_id AND professorID = :prof_id 
				AND Positions.positionTypeID = :pos_type AND Applications.appStatus = :approved';
		$args = array(':section_id' => $this->id, ':prof_id' => $professor->getID(),':pos_type' => $type, ':approved' => APPROVED);
		return Database::executeGetScalar($sql, $args);	
	}


	public function getID() { return $this->id; }
	public function getCRN() { return $this->crn; }
	public function getSectionType() { return $this->sectionType; }
	public function getCourseID() { return $this->courseID; }
	public function getCourseDepartment() { return $this->courseDepartment; }
	public function getCourseNumber() { return $this->courseNumber; }
	public function getCourseTitle() { return $this->courseTitle; }
	public function getCourseTerm() {
		if ($this->courseTerm == null) {
			$this->courseTerm = Term::getTermByID($this->courseTermID);
		}
		return $this->courseTerm;
	}
	public function getCourseName() {
		return $this->courseDepartment . $this->courseNumber;
	}
	public function getCourseFullName() {
		return $this->courseDepartment . $this->courseNumber . ' ' . $this->getCourseTerm()->getName();
	}

	public function getCreator() {
		if ($this->creator == null) {
			$this->creator = User::getUserByID($this->creatorID);
		}
		return $this->creator;
	}
	public function getCreateTime() { return $this->createTime; }

	private $id;
	private $crn;
	private $sectionType;
	private $courseID;
	private $courseDepartment;
	private $courseNumber;
	private $courseTitle;
	private $courseTermID;
	private $courseTerm;
	private $creatorID;
	private $creator;
	private $createTime;
}

final class Event {

	/** EventTypes documentation:
	 * EVENT TYPES
	 * The following constants correspond to EventTypes.
	 * Each Event object has an EventType, elaborating on what it should appear as,
	 * and what type of object is referenced by objectID.
	 *
	 * --------------------
	 * NOTIFICAITON TEMPLATES
	 *
	 * There's a bunch of NotificationTemplates attached to some EventTypes
	 * representing that Events with that EventType will create Notifications (emails)
	 * to the specified user(s). When the notifyTarget is not "self", the target is in
	 * reference to the objectID. If the objectID is not a User, then emails are sent to
	 * relevent creators (i.e. "target"/creator of a Term object (a staff member), or
	 * "professors" of a Course object)
	 * All "staff" and "admin" targeted notifications go to all Users of those types.
	 *
	 * for example, a PDO exception can be represented by:
	 * name='SERVER_DBERROR', severity=crit, objectType=EventType
	 * with no templates (admin might add one to get notified about PDO errors by email).

	 * alternatively, a successful login can be represented by:
	 * name='SESSION_LOGIN', severity=info, objectType=User
	 * with no templates
	 *
	 * alternatively, a student applying for a course by:
	 name='STUDENT_APPLY', logSeverity=notice, objectType=Application
	 * with templates:
	 * - notifyTarget=self, notifyMode=both, subject="Student Application",
	 *   template="You have applied for APPLICATION OBJECT. The professor(s) of the course will be notified and you should hear back soon."
	 * - notifyTarget=professors, notifyMode=home, subhect="Student Application",
	 *   template="User CREATOR has applied for your course APPLICATION OBJECT."
	 * 
	 * notifyMode=email - Only an email is sent; the user won't see it when they log in.
	 * notifyMode=home - No email is sent; the user will see it when they log in.
	 * notifyMode=both - An email is sent, and it'll appear when they log in.
	 */
	const SERVER_EXCEPTION = 1;
	const SERVER_DBERROR = 2;
	const ERROR_LOGIN = 3;
	const ERROR_PERMISSION = 4;
	const ERROR_NOT_FOUND = 5;
	const ERROR_FORM_FIELD = 6;
	const ERROR_FORM_UPLOAD = 7;
	const ERROR_CSV_PARSE = 8;
	const ERROR_JSON_PARSE = 9;
	const SESSION_LOGIN = 10;
	const SESSION_LOGOUT = 11;
	const SESSION_CONTINUE = 12;
	const USER_CREATE = 13;
	const USER_RESET = 14;
	const USER_CONFIRM = 15;
	const USER_CHECK_EMAIL = 16;
	const USER_GET_APPLICATIONS = 17;
	const USER_GET_POSITIONS = 18;
	const USER_GET_SECTIONS = 19;
	const USER_GET_STUDENTS = 20;
	const USER_GET_PROFESSORS = 21;
	const USER_GET_USERS = 22;
	const USER_GET_PROFILE = 23;
	const USER_SET_PROFILE = 24;
	const STUDENT_APPLY = 25;
	const STUDENT_WITHDRAW = 26;
	const STUDENT_SEARCH = 27;
	const NONSTUDENT_SET_APP = 28;
	const NONSTUDENT_COMMENT = 29;
	const SU_CREATE_USER = 30;
	const SU_RESET_USER = 31;
	const STAFF_TERM_IMPORT = 32;
	const STAFF_GET_PAYROLL = 33;
	const ADMIN_CONFIGURE = 34;

	public static function getEventTypeName($event_type) {
		$class = new ReflectionClass(__CLASS__);
		$constants = array_flip($class->getConstants());

		return $constants[$event_type];
	}

	public static function getErrorTextFromEventType($event_type) {
		switch ($event_type) {
		default:
		case Event::SERVER_EXCEPTION:
		case Event::SERVER_DBERROR:
		case Event::ERROR_LOGIN:
		case Event::ERROR_PERMISSION:
		case Event::ERROR_NOT_FOUND:
		case Event::ERROR_FORM_FIELD:
		case Event::ERROR_FORM_UPLOAD:
		case Event::ERROR_CSV_PARSE:
		case Event::ERROR_JSON_PARSE: return 'Error';
		case Event::SESSION_LOGIN: return 'Error logging in';
		case Event::SESSION_LOGOUT: return 'Error logging out';
		case Event::SESSION_CONTINUE: return 'Error accessing page';
		case Event::USER_CREATE: return 'Error creating an account';
		case Event::USER_RESET: return 'Error resetting password';
		case Event::USER_CONFIRM: return 'Error confirming email';
		case Event::USER_CHECK_EMAIL: return 'Error checking email availability';
		case Event::USER_GET_APPLICATIONS: return 'Error retrieving applications';
		case Event::USER_GET_POSITIONS: return 'Error retrieving positions';
		case Event::USER_GET_SECTIONS: return 'Error retrieving sections';
		case Event::USER_GET_STUDENTS: return 'Error retrieving students';
		case Event::USER_GET_PROFESSORS: return 'Error retrieving professors';
		case Event::USER_GET_USERS: return 'Error retrieving users';
		case Event::USER_GET_PROFILE: return 'Error retrieving profile data';
		case Event::USER_SET_PROFILE: return 'Error setting profile data';
		case Event::STUDENT_APPLY: return 'Error applying to position';
		case Event::STUDENT_WITHDRAW: return 'Error withdrawing application';
		case Event::STUDENT_SEARCH: return 'Error searching for positions';
		case Event::NONSTUDENT_SET_APP: return 'Error setting application status';
		case Event::NONSTUDENT_COMMENT: return 'Error creating comment';
		case Event::SU_CREATE_USER: return 'Error creating user';
		case Event::SU_RESET_USER: return 'Error resetting user\'s password';
		case Event::STAFF_TERM_IMPORT: return 'Error importing term';
		case Event::STAFF_GET_PAYROLL: return 'Error retrieving payroll data';
		case Event::ADMIN_CONFIGURE: return 'Error setting configuration';
		}
	}

	public static function insertEventInFile($eventType, $descr, $objectID = null,
		$createTime = null, $creatorIP = null, $creator = null) {
		return Event::insertEventGeneral($eventType, $descr, $objectID, $createTime,
			$creatorIP, $creator, false);
	}

	public static function insertEvent($eventType, $descr, $objectID = null,
		$createTime = null, $creatorIP = null, $creator = null) {
		return Event::insertEventGeneral($eventType, $descr, $objectID, $createTime,
			$creatorIP, $creator, true);
	}

	public static function insertEventGeneral($eventType, $descr, $objectID,
		$createTime, $creatorIP, $creator, $useDB) {
		// default createtime = now
		if ($createTime == null) {
			$createTime = time();
		}
		// default IP = REMOTE_ADDR
		if ($creatorIP == null) {
			$creatorIP = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
		}
		// default creator = currently logged in user (or leave null if session invalid)
		if ($creator == null) {
			if (Database::isConnected()) {
				try {
					$creator = Session::getLoggedInUser();
				} catch (PDOException $ex) {
					// throw away and leave $creator = null on database error
				}
			}
		}

		if ($useDB) {
			$args = array(':etype' => $eventType, ':descr' => $descr,
				':objectid' => $objectID, ':createtm' => date('Y-m-d H:i:s', $createTime),
				':createip' => inet_pton($creatorIP));

			$creatorID_field = ''; $creatorID_param = '';
			if ($creator != null) {
				$creatorID_field = ', creatorID';
				$creatorID_param = ', :createid';
				$args[':createid'] = $creator->getID();
			}

			$sql = "INSERT INTO Events (eventTypeID, description,
					objectID$creatorID_field, createTime, creatorIP) VALUES
					(:etype, :descr, :objectid$creatorID_param, :createtm, :createip)";

			return Database::executeInsert($sql, $args);
		} else {
			if ($creator == null) {
				$userID = null;
			} else {
				$userID = $creator->getID();
			}
			$data = array('class' => Event::getEventTypeName($eventType),
				'objectID' => $objectID, 'description' => $descr,
				'ip' => $creatorIP, 'userID' => $userID);
			$line = 'TARS EVENT (database access error): '.json_encode($data)."\n";
			$chars_put = error_log($line, 0);
			if ($chars_put === false) {
				return false;
			} else {
				// callers expect the database `eventID` column returned, since this
				// was a fallback, return a valid row value on "success"
				return 1;
			}
		}
	}
}

final class Configuration {
	const LOG_DEBUG = 'logDebug';
	const ADMIN_CREATED = 'adminCreated';
	const DOMAIN = 'domain';
	const ENABLE_LOGIN = 'enableLogin';
	const CURRENT_TERM = 'currentTerm';
	const CONFIG_LAST_ID = 'configID';
	const CONFIG_LAST_UPDATOR = 'creatorID';
	const CONFIG_LAST_UPDATETIME = 'createTime';

	// we can cache this row over the course of a PHP script run
	// it is unlikely to change more often
	private static $cachedConfig = null;

	public static function get($key) {
		if (Configuration::$cachedConfig == null) {
			$sql = 'SELECT * FROM Configurations
					ORDER BY createTime DESC
					LIMIT 1';
			Configuration::$cachedConfig = Database::executeGetRow($sql, array());
		}

		if (isset(Configuration::$cachedConfig[$key])) {
			return Configuration::$cachedConfig[$key];
		} else {
			return null;
		}
	}

	public static function set($key, $value, $user, $time) {
		// make sure cache is populated
		Configuration::get(null); 
		// set value if key is present
		if (array_key_exists($key, Configuration::$cachedConfig) &&
			$key != Configuration::CONFIG_LAST_ID &&
			$key != Configuration::CONFIG_LAST_UPDATOR &&
			$key != Configuration::CONFIG_LAST_UPDATETIME) {
			Configuration::$cachedConfig[$key] = $value;
		} else {
			return null;
		}
		$sql = 'INSERT INTO Configurations
			(logDebug, adminCreated, domain, enableLogin, currentTerm, creatorID, createTime) VALUES
			(:logDebug, :adminCreated, :domain, :enableLogin, :currentTerm, :creatorID, :createTime)';
		$args = array();
		foreach (Configuration::$cachedConfig as $curKey => $curVal) {
			$args[":$curKey"] = $curVal;
		}
		unset($args[':configID']);
		$args[':creatorID'] = $user->getID();
		$args[':createTime'] = date('Y-m-d H:i:s', $time);
		return Database::executeInsert($sql, $args);
	}
}


/** Connect to the database and create/use PDO object. */
Database::connect();

