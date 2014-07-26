<?php

final class Position {
	public static function getPositionByID($id) {
		$args = array(':id' => $id);
		$sql = 'SELECT * FROM Positions
				INNER JOIN PositionTypes ON PositionTypes.positionTypeID = Positions.positionTypeID
				WHERE positionID = :id';
		$row = Database::executeGetRow($sql, $args);
		if ($row == null) {
			return null;
		}
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

