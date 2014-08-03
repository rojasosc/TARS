<?php

final class Staff extends User {
	public static function registerStaff($email, $firstName, $lastName,
		$officeID, $officePhone) {

		$userID = parent::insertUser($email, null, $firstName, $lastName, STAFF);

		$sql = 'INSERT INTO Staff
				(userID, officeID, officePhone) VALUES
				(:id, :officeID, :officePhone)';
		$args = array(':id' => $userID, ':officeID' => $officeID,
				':officePhone' => $officePhone);
		Database::executeInsert($sql, $args);

		return $userID;
	}

	public function __construct($user_row, $staff_row) {
		parent::__construct($user_row);

		if ($staff_row) {
			$this->officeID = $staff_row['officeID'];
			$this->office = null;
			$this->officePhone = $staff_row['officePhone'];
		}
	}

	public function updateProfile($firstName, $lastName, $officePhone, $building, $room) {
		$building = strtoupper($building);
		$room = strtoupper($room);

		$sql = 'UPDATE Staff
				INNER JOIN Users ON Users.userID = Staff.userID
				SET firstName = :firstName, lastName = :lastName,
					officeID = :officeID, officePhone = :officePhone
				WHERE Users.userID = :id';
		$args = array(':id'=>$this->id, ':firstName'=>$firstName, ':lastName'=>$lastName,
			':officeID' => Place::getOrCreatePlace($building, $room),
			':officePhone' => $officePhone);
		Database::execute($sql, $args);
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
			'officePhone' => $this->officePhone == null ? null : $this->getOfficePhoneDisplay(),
			'building' => $office == null ? null : $office->getBuilding(),
			'room' => $office == null ? null : $office->getRoom());
		return array_merge($parent, $subclass);
	}

	private $officeID;
	private $office;
	private $officePhone;
}

