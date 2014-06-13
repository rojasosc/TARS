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

const STUDENT = 0;
const PROFESSOR = 1;
const STAFF = 2;
const ADMIN = 3;

const PENDING = 0;
const STAFF_VERIFIED = 1;
const REJECTED = 2;
const APPROVED = 3;
const WITHDRAWN = 4;

// TODO: use a configurable option or something
// 2 is internal ID of current Term row/object (fall 2014) in TARS-testdata.sql
const CURRENT_TERM = 2;

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
	public static function getPlaceByID($id) {
		$sql = 'SELECT * FROM Places WHERE placeID = :id';
		$args = array(':id' => $id);
		$row = Database::executeGetRow($sql, $args);
		return new Place($row);
	}

	private function __construct($row) {
		$this->building = $row['building'];
		$this->room = $row['room'];
		$this->roomType = $row['roomType'];
	}

	public function getBuilding() { return $this->building; }
	public function getRoom() { return $this->room; }
	public function getRoomType() { return $this->roomType; }

	private $building;
	private $room;
	private $roomType;
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

	protected $id;
	protected $email;
	protected $password;
	protected $otype;
	protected $firstName;
	protected $lastName;
}

final class Student extends User {
	public static function insertStudent($email, $password_hash, $firstName, $lastName,
		$mobilePhone, $major, $gpa, $classYear, $aboutMe, $universityID) {

		$userID = parent::insertUser($email, $password_hash, $firstName, $lastName, STUDENT);

		$sql = 'INSERT INTO Students
				(userID, mobilePhone, major, gpa, classYear, aboutMe, universityID) VALUES
				(:id, :mobilePhone, :major, :gpa, :classYear, :aboutMe, :universityID)';
		$args = array(':id' => $userID, ':mobilePhone' => $mobilePhone, 
				  ':major' => $major, ':gpa' => $gpa, ':universityID' => $universityID,
				  ':classYear' => $classYear, ':aboutMe' => $aboutMe);
		return Database::executeInsert($sql, $args);
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

	public function getApplications($status) {
		$sql = 'SELECT * FROM Applications
				INNER JOIN Positions ON Positions.positionID = Applications.positionID
				INNER JOIN Courses ON Courses.courseID = Positions.courseID
				WHERE studentID = :student_id AND appStatus = :status
				ORDER BY department DESC, courseNumber ASC';
		$args = array(':student_id' => $this->id, ':status' => $status);
		$rows = Database::executeGetAllRows($sql, $args);
		return array_map(function ($row) { return new Applicant($row); }, $rows);
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
	public static function insertProfessor($email, $password_hash, $firstName, $lastName,
		$officeID, $officePhone, $mobilePhone) {

		$userID = parent::insertUser($email, $password_hash, $firstName, $lastName, PROFESSOR);

		return Database::executeInsert('INSERT INTO Professors
			(userID, officeID, officePhone, mobilePhone) VALUES
			(:id, :id, :officeID, :officePhone, :mobilePhone)',
			array(':id' => $userID, ':officeID' => $officeID,
				':officePhone' => $officePhone, ':mobilePhone' => $mobilePhone));
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

	public function updateProfile($firstName, $lastName, $place, $officePhone, $mobilePhone) {
		$sql = 'UPDATE Professors
				INNER JOIN Users ON Users.userID = Professors.userID
				SET firstName = :firstName, lastName = :lastName,
					office = :office, officePhone = :officePhone,
					mobilePhone = :mobilePhone
				WHERE Users.userID = :id';
		$args = array(':id'=>$this->id, ':firstName'=>$firstName, ':lastName'=>$lastName,
			':office' => $place->getID(), ':officePhone' => $officePhone,
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
		if ($this->office = null) {
			$this->office = Place::getPlaceByID($this->officeID);
		}
		return $this->office;
	}
	public function getOfficePhone() { return $this->officePhone; }
	public function getMobilePhone() { return $this->mobilePhone; }

	private $officeID;
	private $office;
	private $officePhone;
	private $mobilePhone;
}

final class Staff extends User {
	public static function insertStaff($email, $password_hash, $firstName, $lastName,
		$officePhone, $mobilePhone) {

		$userID = parent::insertUser($email, $password_hash, $firstName, $lastName, STAFF);

		return Database::executeInsert('INSERT INTO Staff
			(userID, officePhone, mobilePhone) VALUES
			(:id, :officePhone, :mobilePhone)',
			array(':id' => $userID,
				':officePhone' => $officePhone, ':mobilePhone' => $mobilePhone));
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

	public static function findPositions($search_field, $term = -1, $position_type = null) {
		$sql = 'SELECT * FROM Positions
				INNER JOIN Courses ON Positions.courseID = Courses.courseID
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
			$sql .= 'termID = :term AND ';
			$args[':term'] = $term;
		}
		// TODO: implement position_type
		//if ($position_type != null) {
		//	$sql .= 'posType = :posType AND ';
		//	$args[':posType'] = $position_type;
		//}
		$sql .= '1 ORDER BY Courses.department DESC, Courses.courseNumber ASC';
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

// TODO: a better name to describe this than Applicant and Applications is "Application"
final class Applicant {
	// TODO: this should be in relation to the Applicant database object instead of Position
	public static function setPositionStatus($student, $position, $status) {
		$sql = 'UPDATE Applications
				SET appStatus = :status
				WHERE studentID = :student_id AND positionID = :position_id';
		$args = array(':status' => $status,	':student_id' => $student->getID(),
			':position_id' => $position->getID());
		Database::execute($sql, $args);
	}

	public static function getApplicantByID($id) {
		$row = Database::executeGetRow('SELECT * FROM Applications WHERE appID = :id',
			array(':id' => $id));
		return new Applicant($row);
	}

	// TODO pagination?
	public static function getApplicantsByProfessor($prof_obj, $app_status) {
		$sql = 'SELECT Applications.appID, Applications.positionID, Applications.studentID,
					Applications.compensation, Applications.appStatus,
					Applications.qualifications
				FROM Applications, Users, Courses, Positions, Students, Teaches
				WHERE Applications.studentID = Users.userID AND
					Applications.studentID = Students.userID AND
					Applications.appStatus = :status AND
					Applications.positionID = Positions.positionID AND
					Positions.courseID = Courses.courseID AND
					Teaches.courseID = Courses.courseID AND
					Teaches.professorID = :prof_id
				ORDER BY Courses.department DESC, Courses.courseNumber ASC';
		$args = array(':prof_id' => $prof_obj->getID(), ':status' => $app_status);
		$rows = Database::executeGetAllRows($sql, $args);
		return array_map(function ($row) { return new Applicant($row); }, $rows);
	}

	public static function getApplicantsByProfessorAndCourse($prof_obj, $course_obj, $app_status) {
		$sql = 'SELECT Applications.appID, Applications.positionID, Applications.studentID,
					Applications.compensation, Applications.appStatus,
					Applications.qualifications
				FROM Applications, Users, Courses, Positions, Students, Teaches
				WHERE Applications.studentID = Users.userID AND
					Applications.studentID = Students.userID AND
					Applications.appStatus = :status AND
					Applications.positionID = Positions.positionID AND
					Positions.courseID = :course_id AND
					Teaches.courseID = Courses.courseID AND
					Courses.courseID = :course_id
				ORDER BY Courses.department DESC, Courses.courseNumber ASC';
		$args = array(':prof_id' => $prof_obj->getID(), ':status' => $app_status,
			':course_id' => $course_obj->getID());
		$rows = Database::executeGetAllRows($sql, $args);
		return array_map(function ($row) { return new Applicant($row); }, $rows);
	}

	public static function getApplicantsByTerm($term, $app_status, $compensation) {
		$sql = 'SELECT Applications.appID, Applications.positionID, Applications.studentID,
					Applications.compensation, Applications.appStatus,
					Applications.qualifications
				FROM Applications, Positions, Courses, Students, Users
				WHERE Applications.studentID = Users.userID AND
					Applications.studentID = Students.userID AND
					Courses.courseID = Positions.courseID AND
					Positions.positionID = Applications.studentID AND
					Applications.appStatus = :status AND
					Applications.compensation = :compensation AND
					Courses.termID = :termID
				ORDER BY Courses.department DESC, Courses.courseNumber ASC';
		$args = array(':termID' => $term->getID(), ':status' => $app_status,
			':compensation' => $compensation);
		$rows = Database::executeGetAllRows($sql, $args);
		return array_map(function ($row) { return new Applicant($row); }, $rows);
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

final class Course {
	public static function getCourseByID($id) {
		$sql = 'SELECT * FROM Courses WHERE courseID = :id';
		$args = array(':id' => $id);
		$row = Database::executeGetRow($sql, $args);
		return new Course($row);
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

Database::connect();

/* Function getUserID
*  Purpose: Obtains an existing users ID via their email address.
*  Returns: integer userID
**/
function getUserID($email) {
	if ($user_obj = User::getUserByEmail($email)) {
		return $user_obj->getID();
	}
	return false;
}

/* Function 
*  Purpose: 
*  Returns: 
**/	
function getStudent($email) {
	return User::getUserByEmail($email, STUDENT);
}

/* Function 
*  Purpose: 
*  Returns: 
**/	
function getProfessor($email) {
	return User::getUserByEmail($email, PROFESSOR);
}	

/* Function 
*  Purpose: 
*  Returns: 
**/	
function getStaff($email) {
	return User::getUserByEmail($email, STAFF);
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

/* Function emailExists
*  Purpose: Checks if an email is in use.
*  Returns: True if in use and false otherwise.
**/	
function emailAvailable($email){
	return User::checkEmailAvailable($email);
} 

/********************
* END LOGIN FUNCTIONS
*********************/	


/*******************
* USER REGISTRATION
********************/


function registerStudent($firstName, $lastName,$email,$password,$mobilePhone,$classYear,$major,$gpa,$aboutMe,$universityID){

	/* Note: $password does not require database escaping; it is not being put in the database.
	 *       Only the result of password_hash() is, and that is escaped by being a parameter
	 *       in the prepared statement.
	 */
	$password_hash = password_hash($password, PASSWORD_DEFAULT);

	Student::insertStudent($email, $password_hash, $firstName, $lastName,
		$mobilePhone, $major, $gpa, $classYear, $aboutMe, $universityID);
}

/* Function registerProfessor
*  Purpose: Creates a new account for a professor. 
*  Returns: nothing.
**/
function registerProfessor($officeID,$firstName, $lastName, $email, $password,$officePhone, $mobilePhone) {

	/* Note: $password does not require database escaping; it is not being put in the database.
	 *       Only the result of password_hash() is, and that is escaped by being a parameter
	 *       in the prepared statement.
	 */
	$password_hash = password_hash($password, PASSWORD_DEFAULT);

	Professor::insertProfessor($email, $password_hash, $firstName, $lastName,
		$officeID, $officePhone, $mobilePhone);
}	

/* Function registerAdmin
*  Purpose: Creates an account for an admin. 
*  Returns: nothing.
**/
function registerStaff($firstName, $lastName,$email,$password,$officePhone,$mobilePhone) {

	/* Note: $password does not require database escaping; it is not being put in the database.
	 *       Only the result of password_hash() is, and that is escaped by being a parameter
	 *       in the prepared statement.
	 */
	$password_hash = password_hash($password, PASSWORD_DEFAULT);

	Staff::insertStaff($email, $password_hash, $firstName, $lastName,
		$officePhone, $mobilePhone);
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
function getApplicants($email) {

	$professor_obj = User::getUserByEmail($email, PROFESSOR);

	if ($professor_obj) {
		// TODO: support both PENDING and STAFF_VERIFIED in DAL
		return Applicant::getApplicantsByProfessor($professor_obj, PENDING);
	} else {
		return array();
	}
}	

/* Function getApplicants
*  Purpose:  Obtain a table representation of a particular professor's applicants.
*  Returns:  A 2-D array of type 0 and type 1 applicantions
**/
function getAssistants($email) {

	$professor_obj = User::getUserByEmail($email, PROFESSOR);

	if ($professor_obj) {
		return Applicant::getApplicantsByProfessor($professor_obj, APPROVED);
	} else {
		return array();
	}
}

function pendingApplicants($email) {

	$count = 0; 
	$count += count(getApplicants($email));

	return $count;

}

/* Function getCourseName
*  Purpose: Retrieves all courses that contain the value 'courseName'. 
*  Returns: An array of course entries. (A 2-D array) 
**/
function getCourses($email) {

	$professor_obj = User::getUserByEmail($email, PROFESSOR);

	if ($professor_obj) {
		return $professor_obj->getCourses();
	} else {
		return array();
	}
}

function getApplicationsByCourse($email,$course_obj){

	$professor_obj = User::getUserByEmail($email, PROFESSOR);

	if ($professor_obj && $course_obj) {
		// TODO: support both PENDING and STAFF_VERIFIED in DAL
		return Applicant::getApplicantsByProfessorAndCourse($professor_obj, $course_obj, PENDING);
	} else {
		return array();
	}
}


function getFilledPositionsForCourse($email,$course_obj){


	$professor_obj = User::getUserByEmail($email, PROFESSOR);

	if ($professor_obj && $course_obj) {
		return Applicant::getApplicantsByProfessorAndCourse($professor_obj, $course_obj, APPROVED);
	} else {
		return array();
	}
}

function countTotalPositions($email,$course_obj){

	$professor_obj = User::getUserByEmail($email, PROFESSOR);

	if ($professor_obj && $course_obj) {
		$course_obj->getTotalPositions($professor_obj);
	} else {
		return 0;
	}
}

/* Function setPosition
*  Purpose: Assigns a position to a student. 
*  Returns: nothing.
**/
function setPositionStatus($studentID,$positionID,$status){
	$student_obj = User::getUserByEmail($email, STUDENT);
	$position_obj = Position::getPositionByID($positionID);

	if ($student_obj && $position_obj) {
		Applicant::setPositionStatus($student_obj, $position_obj, $status);
	}
}


/* UNUSED: not mapped
function getCourseIDS($email){

	$conn = open_database();
	
	$professorID = getUserID($email);
	
	$sql = "SELECT Course.courseID\n"
		. "FROM Course\n"
		. "WHERE professorID = '$professorID'";

	$result = mysqli_query($conn,$sql);
	
	
	/* 2-D array of courseIDS *
	$result = mysqli_fetch_all($result); 
	
	/*Make just one array *
	$courseIDS = array();
	
	foreach($result as $course){
		
		$courseIDS[] = $course[0];
	}
	
	close_database($conn);
	
	return $courseIDS;
}*/

/************************
* END PROFESSOR FUNCTIONS
*************************/

/*******************
* STUDENT FUNCTIONS
********************/

/* Function studentPositions
*  Purpose: Fetch all of the student's currently held TA-ing positions from the database
*  Returns: An array of associative arrays with all the student's TA position information
**/

function studentPositions($email){

	$student = User::getUserByEmail($email, STUDENT);

	if ($student) {
		return $student->getApplications(APPROVED);
	} else {
		return array();
	}
}

/* Function updateProfile
*  Purpose: Edit the database entries that correspond to the student's information with newly submitted ones from the student
*  Returns: absolutely nothing
**/
function updateProfile($email, $firstName, $lastName, $mobilePhone, $major, $classYear, $gpa, $aboutMe){
	$student = User::getUserByEmail($email, STUDENT);

	if ($student) {
		$student->updateProfile($firstName, $lastName, $mobilePhone, $major, $classYear, $gpa, $aboutMe);
		return true;
	} else {
		return false;
	}
}

/* Function search
*  Purpose: Search the database for TA positions with the specified attributes
*  Returns: An array of associative arrays that represent individual positions
**/

function search($search, $term, $type) {

	return Position::findPositions($search, $term, $type);
}

/* Function apply
*  Purpose: Register a student's application in the database
*  Returns: Absolutely nothing
**/

function apply($positionID, $studentID, $compensation, $qualifications) {
	$student = User::getUserByID($studentID, STUDENT);
	$position = Position::getPositionByID($positionID);

	if ($student && $position) {
		$student->apply($position, $compensation, $qualifications);
	}
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
function getPayrollByTerm($termID){

	$term = Term::getTermByID($termID);

	if ($term) {
		return Applicant::getApplicantsByTerm($term, APPROVED, 'pay');
	} else {
		return array();
	}
}

function getOffice($building,$room){

	return new Place(array('id'=>-1,'building'=>$building,'room'=>$room,'roomType'=>'Office'));

	$conn = open_database();
	
	$sql = "SELECT * FROM Place WHERE building = '$building' AND room = '$room' LIMIT 1";
	$result = mysqli_query($conn,$sql);
	
	$office = mysqli_fetch_array($result,MYSQLI_BOTH);
	
	close_database($conn);

	return $office;
}

function getUnverifiedStudents(){

	return Applicant::getApplicantsByTerm(Term::getTermByID(CURRENT_TERM), PENDING, 'pay');

	$conn = open_database();
	
	$sql = "SELECT Students.userID, Students.firstName, Students.lastName, Users.email, Students.gpa\n"
	. "FROM Students,Users\n"
	. "WHERE Students.userID = Users.userID AND Students.status = 0";
	
	$result = mysqli_query($conn,$sql);
	
	$students = @mysqli_fetch_all($result,MYSQLI_BOTH);

	close_database($conn);
	
	return $students;
}

function totalAssistantCount(){

	return 0;

	//return Applicant::get

	$conn = open_database();

	$sql = "SELECT COUNT(*) FROM Students AS numberofStudents";
	$count = mysqli_query($conn,$sql);
	$count = mysqli_fetch_array($count);
	close_database($conn);
	$count = $count[0];
	return $count;		

}


function setStatus($studentID,$status){

	return;

	$conn = open_database();
	
	$sql = "UPDATE Students SET status = '$status' WHERE Students.userID = '$studentID'";
	
	mysqli_query($conn,$sql);
	
	close_database($conn);

}

function updateProfessor($firstName, $lastName, $email,$officePhone, $mobilePhone){

	return;

	$conn = open_database();
	
	/* escape variables to avoid  injection attacks. */ 
	$firstName = mysqli_real_escape_string($conn,$firstName);
	$lastName = mysqli_real_escape_string($conn,$lastName);
	$email = mysqli_real_escape_string($conn,$email);
	$password = mysqli_real_escape_string($conn,$password);
	$officePhone = mysqli_real_escape_string($conn,$officePhone);
	$mobilePhone = mysqli_real_escape_string($conn,$mobilePhone);
	
	$professorID = getUserID($email);
	$sql = "UPDATE Professors SET Professors.firstName = '$firstName', Professors.lastName = '$lastName', Professors.officePhone = '$officePhone', Professors.mobilePhone = '$mobilePhone' WHERE Professors.professorID = '$professorID'";		
	mysqli_query($conn,$sql);
	
	close_database($conn);	
}

function updateStudent($firstName, $lastName,$email,$homePhone,$mobilePhone,$classYear,$major,$gpa,$aboutMe){

	return;

	$conn = open_database();
			
	/* escape variables to avoid  injection attacks. */ 
	$firstName = mysqli_real_escape_string($conn,$firstName);
	$lastName = mysqli_real_escape_string($conn,$lastName);
	$email = mysqli_real_escape_string($conn,$email);
	$homePhone = mysqli_real_escape_string($conn,$homePhone);
	$mobilePhone = mysqli_real_escape_string($conn,$mobilePhone);
	$classYear = mysqli_real_escape_string($conn,$classYear);
	$major = mysqli_real_escape_string($conn,$major);
	$gpa = mysqli_real_escape_string($conn,$gpa);
	$aboutMe = mysqli_real_escape_string($conn,$aboutMe);
	
	$studentID = getUserID($email);
	$sql = "UPDATE Students SET Students.firstName = '$firstName', Students.lastName = '$lastName', Students.homePhone = '$homePhone', Students.mobilePhone = '$mobilePhone', Students.classYear = '$classYear', Students.major = '$major', Students.gpa = '$gpa', Students.aboutMe = '$aboutMe' WHERE Students.userID = '$studentID'";		
	mysqli_query($conn,$sql);
	
	close_database($conn);	
}

/********************
* END STAFF FUNCTIONS
*********************/

