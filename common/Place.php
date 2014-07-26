<?php

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
		if ($row == null) {
			return null;
		}
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
		if ($row == null) {
			return null;
		}
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
		return array_map(function ($row) { return $row['building']; }, $rows);
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
		$this->id = $row['placeID'];
		$this->building = $row['building'];
		$this->room = $row['room'];
	}

	public function getBuilding() { return $this->building; }
	public function getRoom() { return $this->room; }
	public function getPlaceID() { return $this->id; }

	public function toArray() {
		return array(
			'id' => $this->id,
			'building' => $this->building,
			'room' => $this->room);
	}

	private $id;
	private $building;
	private $room;
}

