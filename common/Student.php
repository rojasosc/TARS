<?php

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
		$id = parent::getID();
		return Application::getApplications($id, null, null, $term, $status);
	}
	
	// TODO move this to Position object
	// TODO create notification
	public function apply($position, $compensation, $qualifications) {
		$applicationID = Application::insertApplication($position, $compensation,
			$qualifications, PENDING, $this->id, time());
	}

	// TODO move this to Position object
	// TODO create notification
	public function withdraw($position){
		Application::setPositionStatus($this->id, $position, WITHDRAWN);
	}
	
	public function getAllComments(){
		$sql = 'SELECT * FROM Comments
   				WHERE studentID = :studentID';
		$args = array(':studentID' => $this->id);
   		$rows = Database::executeGetAllRows($sql, $args);
   		return array_map(function($row) { return new Comment($row); }, $rows);
	}

	public function saveComment($comment, $creator, $createTime = null) {
		if ($createTime == null) {
			$createTime = time();
		}

		$commentID = Comment::insertComment($comment, $this->id, $creator->getID(), $createTime);

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
	}

	public function getMobilePhone() { return $this->mobilePhone; }
	public function getMajor() { return $this->major; }
	public function getGPA() { return $this->gpa; }
	public function getClassYear() { return $this->classYear; }
	public function getAboutMe() { return $this->aboutMe; }
	public function getUniversityID() { return $this->universityID; }

	public function toArray() {
		return array(
			'id' => $this->id,
			'type' => STUDENT,
			'email' => $this->email,
			'firstName' => $this->firstName,
			'lastName' => $this->lastName,
			'mobilePhone' => $this->mobilePhone,
			'major' => $this->major,
			'gpa' => $this->gpa,
			'classYear' => $this->classYear,
			'aboutMe' => $this->aboutMe,
			'universityID' => $this->universityID);
	}

	private $mobilePhone;
	private $major;
	private $gpa;
	private $classYear;
	private $aboutMe;
	private $universityID;
}

?>