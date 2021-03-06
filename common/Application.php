<?php

final class Application {

    public static function getApplicationByID($id) {
        $sql = 'SELECT * FROM Applications WHERE appID = :id';
        $args = array(':id' => $id);
        $row = Database::executeGetRow($sql, $args);
        if ($row == null) {
            return null;
        }
        return new Application($row);
    }

    public static function getApplicationToPositionByStudent($position, $student) {
        $sql = 'SELECT * FROM Applications
                WHERE positionID = :position AND creatorID = :student
                ORDER BY createTime DESC
                LIMIT 1';
        $args = array(':position' => $position->getID(), ':student' => $student->getID());
        $row = Database::executeGetRow($sql, $args);
        if ($row === null) {
            return null;
        }
        return new Application($row);
    }

    private static function findApplicationsGeneral($student, $section, $professor, $term, $status, $compensation, $is_count) {
        $sql_where = '';
        $sql_ij_t = false;
        $sql_ij_teaches = '';
        $args = array();
        if ($section !== null) {
            $sql_where .= 'Positions.sectionID = :section AND ';
            $args[':section'] = $section->getID();
        }
        if ($student !== null) {
            $sql_where .= 'Applications.creatorID = :student AND ';
            $args[':student'] = $student->getID();
        }
        if ($professor !== null) {
            $sql_where .= 'Teaches.professorID = :professor AND ';
            $sql_ij_t = true;
            $args[':professor'] = $professor->getID();
        }
        if ($term !== null) {
            $sql_where .= 'Courses.termID = :term AND ';
            $args[':term'] = $term->getID();
        }
        if ($status >= 0) {
            $sql_where .= 'Applications.appStatus = :status AND ';
            $args[':status'] = $status;
        }
        if ($compensation !== null) {
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
                WHERE $sql_where 1";
        // echo "<pre>";
        // print_r($args);
        // exit($sql);
        return array($sql, $args);
    }

    public static function getApplicationsByCourseID($courseID, $professor){
        $sql = "Select Applications.appID\n"
    . "From Applications, Sections, Teaches, Positions\n"
    . "WHERE Applications.appStatus = 0 \n"
    . "AND Applications.positionID = Positions.positionID\n"
    . "AND Positions.sectionID = Sections.sectionID\n"
    . "AND Sections.courseID = :courseID\n"
    . "AND Teaches.sectionID = Sections.sectionID\n"
    . "AND Teaches.professorID = :professorID";
        $args = array('courseID' => $courseID, 'professorID' => $professor->getID());
        $rows = Database::executeGetAllRows($sql, $args);
        return array_map(function ($row) { return Application::getApplicationByID($row['appID']); }, $rows);
    }

    public static function getAssistantsByCourseID($courseID, $professor){
        $sql = "Select Applications.appID\n"
    . "From Applications, Sections, Teaches, Positions\n"
    . "WHERE Applications.appStatus = 3 \n"
    . "AND Applications.positionID = Positions.positionID\n"
    . "AND Positions.sectionID = Sections.sectionID\n"
    . "AND Sections.courseID = :courseID\n"
    . "AND Teaches.sectionID = Sections.sectionID\n"
    . "AND Teaches.professorID = :professorID";
        $args = array('courseID' => $courseID, 'professorID' => $professor->getID());
        $rows = Database::executeGetAllRows($sql, $args);
        return array_map(function ($row) { return Application::getApplicationByID($row['appID']); }, $rows);
    }

    /**
     * General purpose function to get application object count.
     *
     */
    public static function findApplicationCount($student = null, $section = null, $professor = null, $term = null, $status = -1, $compensation = null) {
        list($sql, $args) = Application::findApplicationsGeneral(
            $student, $section, $professor, $term, $status, $compensation, true);
        return Database::executeGetScalar($sql, $args);
    }

    /**
     * General purpose function to get application objects.
     *
     */
    public static function findApplications($student = null, $section = null, $professor = null, $term = null, $status = -1, $compensation = null, $pg = null) {
        list($sql, $args) = Application::findApplicationsGeneral(
            $student, $section, $professor, $term, $status, $compensation, false);
        if($pg !== null){
            return Database::executeGetPage($sql, $args, $pg, function($row) { return new Application($row); });
        }else{
            $rows = Database::executeGetAllRows($sql, $args);
            return array_map(function ($row) { return new Application($row); }, $rows);
        }
    }

    public static function insertApplication($position, $comp, $qual, $status, $creatorID, $createTime) {
        $sql = 'INSERT INTO Applications
                (positionID, compensation, appStatus, qualifications, creatorID, createTime) VALUES
                (:position, :comp, :status, :qual, :creator, :createTime)';
        $args = array(':position' => $position->getID(), ':comp' => $comp, ':status' => $status,
            ':qual' => $qual, ':creator' => $creatorID,
            ':createTime' => date('Y-m-d H:i:s', $createTime));
        return Database::executeInsert($sql, $args);
    }

    public function __construct($row) {
        $this->id = intval($row['appID']);
        $this->positionID = intval($row['positionID']);
        $this->position = null;
        $this->compensation = $row['compensation'];
        $this->appStatus = intval($row['appStatus']);
        $this->qualifications = $row['qualifications'];
        $this->creatorID = intval($row['creatorID']);
        $this->creator = null;
        $this->createTime = strtotime($row['createTime']);
    }

    public function getID() { return $this->id; }
    public function getPosition() {
        if ($this->position === null) {
            $this->position = Position::getPositionByID($this->positionID);
        }
        return $this->position;
    }
    public function getCompensation() { return $this->compensation; }
    public function getStatus() { return $this->appStatus; }
    public function getQualifications() { return $this->qualifications; }
    public function getCreator() {
        if ($this->creator === null) {
            $this->creator = User::getUserByID($this->creatorID);
        }
        return $this->creator;
    }
    public function getCreateTime() { return $this->createTime; }

    public function setApplicationStatus($decision){
        $sql = "UPDATE Applications SET appStatus = :decision
                WHERE appID = :appID";
        $args = array(':appID' => $this->id, ':decision' => $decision);
        return Database::execute($sql, $args);
    }

    public function toArray($showEvent = true) {
        $data = array(
            'id' => $this->id,
            'status' => $this->appStatus,
            'compensation' => $this->compensation,
            'qualifications' => $this->qualifications,
            'position' => $this->getPosition()->toArray(false));
        if ($showEvent) {
            $data['creator'] = $this->getCreator()->toArray(false);
            $data['createTime'] = date('g:i:sa \o\n Y/m/d', $this->createTime);
        }
        return $data;
    }

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

