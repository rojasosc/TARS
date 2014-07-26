<?php

final class Term {
	public static function getTermByID($id) {
		$sql = 'SELECT * FROM Terms
				INNER JOIN TermSemesters ON Terms.semesterID = TermSemesters.semesterID
				WHERE termID = :id';
		$args = array(':id' => $id);
		$row = Database::executeGetRow($sql, $args);
		if ($row == null) {
			return null;
		}
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
		} catch (PDOException $ex) {
			throw $ex;
		}
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
		return Term::importTerm($termYear, $termSemester, $courses);
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

