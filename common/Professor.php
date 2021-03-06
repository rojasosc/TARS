<?php

final class Professor extends User {
    public static function registerProfessor($email, $firstName, $lastName,
        $officeID, $officePhone) {

        $userID = parent::insertUser($email, null, $firstName, $lastName, PROFESSOR);

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
            $this->officeID = intval($professor_row['officeID']);
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

    public function updateProfile($firstName, $lastName, $officePhone, $officeID) {
        $sql = 'UPDATE Professors
                INNER JOIN Users ON Users.userID = Professors.userID
                SET firstName = :firstName, lastName = :lastName,
                    officeID = :officeID, officePhone = :officePhone
                WHERE Users.userID = :id';
        $args = array(':id'=>$this->id, ':firstName'=>$firstName, ':lastName'=>$lastName,
            ':officeID' => $officeID, ':officePhone' => $officePhone);
        Database::execute($sql, $args);
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
    public function getSectionsByCourseID($courseID){
        $sql = 'SELECT * FROM Sections
                INNER JOIN Teaches ON Teaches.sectionID = Sections.sectionID
                INNER JOIN Courses ON Courses.courseID = Sections.courseID
                WHERE Teaches.professorID = :prof_id
                AND Courses.courseID = :courseID
                ORDER BY Courses.department DESC, Courses.courseNumber ASC, Sections.crn ASC';
        $args = array(':prof_id' => $this->id, ':courseID' => $courseID);
        $rows = Database::executeGetAllRows($sql, $args);
        return array_map(function ($row) { return new Section($row); }, $rows);
    }

    public function getCourses($term){
        $sql = "SELECT DISTINCT Courses.courseID, Courses.department, Courses.courseNumber, Courses.courseTitle \n"
            . "FROM Courses,Sections,Teaches\n"
            . "WHERE Teaches.sectionID = Sections.sectionID \n"
            . "AND Courses.courseID = Sections.courseID\n"
            . "AND Teaches.professorID = :professorID\n"
            . "AND Courses.termID = :term";
        $args = array(':professorID' => $this->id, ':term' => $term->getID());
        $rows = Database::executeGetAllRows($sql,$args);
        return $rows;

    }


    public function getOffice() {
        if ($this->office == null) {
            $this->office = Place::getPlaceByID($this->officeID);
        }
        return $this->office;
    }

    public function getOfficeID() { return $this->officeID; }
    public function getOfficePhone() { return $this->officePhone; }
    public function getOfficePhoneDisplay() { return User::formatPhone($this->officePhone); }

    public function toArray($showEvent = false) {
        $office = $this->getOffice();
        $parent = parent::toArray($showEvent);
        $subclass = array(
            'officePhone' => $this->officePhone === null ? null : $this->getOfficePhoneDisplay(),
            'building' => $office === null ? null : $office->getBuilding(),
            'room' => $office === null ? null : $office->getRoom());
        return array_merge($parent, $subclass);
    }

    private $officeID;
    private $office;
    private $officePhone;
}

