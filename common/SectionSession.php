<?php

final class SectionSession {
	public static function getSessionByID($id) {
		$sql = 'SELECT * FROM Sessions
				INNER JOIN Places ON Places.placeID = Sessions.placeID
				WHERE sessionID = :id';
		$args = array(':id' => $id);
		$row = Database::executeGetRow($sql, $args);
		if ($row == null) {
			return null;
		}
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

