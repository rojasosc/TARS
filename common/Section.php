<?php

final class Section {
    public static function getSectionByID($id) {
        $sql = 'SELECT * FROM Sections
                INNER JOIN Courses ON Courses.courseID = Sections.courseID
                WHERE sectionID = :id';
        $args = array(':id' => $id);
        $row = Database::executeGetRow($sql, $args);
        if ($row == null) {
            return null;
        }
        return new Section($row);
    }

    // TODO: outdated
    public static function getAllSections() {
        $sql = "Select * from Sections
                INNER JOIN Courses ON Courses.courseID = Sections.courseID";
        $args = array();
        $rows = Database::executeGetAllRows($sql,$args);
        return array_map(function($row) {return new Section($row);}, $rows);
    }

    public static function fetchSections($crn, $course, $type, $status, $pg) {
        $sql = "SELECT
                sectionID, Sections.courseID, crn, type, status, termID, department, courseNumber, courseTitle
                FROM Sections
                INNER JOIN Courses ON Courses.courseID = Sections.courseID
                WHERE ";
        $args = array();
        if(!empty($crn)) {
            $sql .= "INSTR(crn, :crn) AND ";
            $args[':crn'] = $crn;
        }
        if(!empty($course)) {
            $i = 1;
            foreach (explode(' ', $course) as $word) {
                $sql .= "(department = :word$i OR
                    courseNumber = :word$i OR
                    INSTR(courseTitle, :word$i) OR
                    CONCAT(department, courseNumber) = :word$i) AND ";
                $ars[":word$i"] = $word;
                $i++;
            }
        }
        if(!empty($type)) {
            $sql .= "type = :type AND ";
            $args[':type'] = $type;
        }
        if(!empty($status) && $status !== 'all') {
            $sql .= "status = :status AND ";
            $args[':status'] = $status;
        }
        $sql .= "1";

        return Database::executeGetPage($sql, $args, $pg, function ($row) {
            return new Section($row); });
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

    public function __construct($row) {
        $this->id = intval($row['sectionID']);
        $this->crn = intval($row['crn']);
        $this->sectionType = $row['type'];
        $this->courseID = intval($row['courseID']);
        $this->courseDepartment = $row['department'];
        $this->courseNumber = $row['courseNumber'];
        $this->courseTitle = $row['courseTitle'];
        $this->courseTermID = intval($row['termID']);
        $this->courseTerm = null;
        $this->status = $row['status'];
        $this->creatorID = intval($row['creatorID']);
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
        $sql = 'SELECT maximumAccepted FROM Positions
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

    public function isTaughtBy($professor) {
        $sql = 'SELECT COUNT(*) FROM Teaches
                WHERE sectionID = :section AND professorID = :professor';
        $args = array(':section' => $this->id, ':professor' => $professor->getID());
        $count = Database::executeGetScalar($sql, $args);
        return $count != 0;
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
    public function getStatus() {
        return $this->status;
    }
    public function getCreator() {
        if ($this->creator == null) {
            $this->creator = User::getUserByID($this->creatorID);
        }
        return $this->creator;
    }
    public function getCreateTime() { return $this->createTime; }

    public function toArray($showEvent = false) {
        $data = array(
            'id' => $this->id,
            'crn' => $this->crn,
            'type' => $this->sectionType,
            'course' => array(
                'id' => $this->courseID,
                'department' => $this->courseDepartment,
                'number' => $this->courseNumber,
                'title' => $this->courseTitle,
                'term' => $this->getCourseTerm()->toArray($showEvent)),
            'instructors' => array_map(function ($prof) {
                return $prof->toArray(false);
            }, $this->getAllProfessors()),
            'sessions' => array_map(function ($sess) {
                return $sess->toArray();
            }, SectionSession::combineSessions($this->getAllSessions())));
        if ($showEvent) {
            $data['creator'] = $this->getCreator()->toArray(false);
            $data['createTime'] = date('g:i:sa \o\n Y/m/d', $this->createTime);
        }
        return $data;
    }

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

