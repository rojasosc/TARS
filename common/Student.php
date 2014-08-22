<?php

final class Student extends User {
    public static function registerStudent($email, $password, $firstName, $lastName,
        $mobilePhone, $classYear, $major, $gpa, $universityID, $aboutMe) {

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $userID = parent::insertUser($email, $password_hash, $firstName, $lastName, STUDENT);

        $sql = 'INSERT INTO Students
                (userID, mobilePhone, classYear, major, gpa, universityID, aboutMe) VALUES
                (:id, :mobilePhone, :classYear, :major, :gpa, :universityID, :aboutMe)';
        $args = array(':id' => $userID, ':mobilePhone' => $mobilePhone,
                ':classYear' => $classYear, ':major' => $major, ':gpa' => $gpa,
                ':universityID' => $universityID, ':aboutMe' => $aboutMe);
        Database::executeInsert($sql, $args);

        return $userID;
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

    public function findApplications($term, $status) {
        return Application::findApplications($this, null, null, $term, $status);
    }

    public function getPendingApplicationCount($term = null) {
        $args = array(':id' => $this->id, ':cancelled' => CANCELLED);
        $sql = 'SELECT COUNT(*) FROM Applications
                INNER JOIN Positions ON Positions.positionID = Applications.positionID
                INNER JOIN Sections ON Sections.sectionID = Positions.sectionID
                INNER JOIN Courses ON Courses.courseID = Sections.courseID
                WHERE Applications.creatorID = :id AND appStatus != :cancelled';
        if ($term !== null) {
            $sql .= ' AND termID = :term';
            $args[':term'] = $term->getID();
        }
        return intval(Database::executeGetScalar($sql, $args));
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
    public function getMobilePhoneDisplay() { return User::formatPhone($this->mobilePhone);    }
    public function getMajor() { return $this->major; }
    public function getGPA() { return $this->gpa; }
    public function getClassYear() { return $this->classYear; }
    public function getAboutMe() { return $this->aboutMe; }
    public function getUniversityID() { return $this->universityID; }

    public function toArray($showEvent = false) {
        $parent = parent::toArray($showEvent);
        $subclass = array(
            'mobilePhone' => $this->getMobilePhoneDisplay(),
            'major' => $this->major,
            'gpa' => floatval($this->gpa),
            'classYear' => intval($this->classYear),
            'aboutMe' => $this->aboutMe,
            'universityID' => $this->universityID);
        return array_merge($parent, $subclass);
    }

    private $mobilePhone;
    private $major;
    private $gpa;
    private $classYear;
    private $aboutMe;
    private $universityID;
}

