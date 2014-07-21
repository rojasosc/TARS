<?php

final class Staff extends User {
	public static function registerStaff($email, $password, $firstName, $lastName,
		$officePhone, $mobilePhone) {

		$password_hash = password_hash($password, PASSWORD_DEFAULT);

		$userID = parent::insertUserSelfCreated($email, $password_hash, $firstName, $lastName, STAFF);

		$sql = 'INSERT INTO Staff
				(userID, officePhone, mobilePhone) VALUES
				(:id, :officePhone, :mobilePhone)';
		$args = array(':id' => $userID,
				':officePhone' => $officePhone, ':mobilePhone' => $mobilePhone);
		Database::executeInsert($sql, $args);
		return $userID;
	}

	public function updateProfile($firstName, $lastName, $officePhone) {
		$sql = 'UPDATE Staff
				INNER JOIN Users ON Users.userID = Professors.userID
				SET firstName = :firstName, lastName = :lastName,
					officePhone = :officePhone
				WHERE Users.userID = :id';
		$args = array(':id'=>$this->id, ':firstName'=>$firstName, ':lastName'=>$lastName,
				':officePhone' => $officePhone);
		Database::execute($sql, $args);
	}

	public function __construct($user_row, $staff_row) {
		parent::__construct($user_row);

		if ($staff_row) {
			$this->officePhone = $staff_row['officePhone'];
		}
	}

	public function getOfficePhone() { return $this->officePhone; }

	public function toArray() {
		return array(
			'id' => $this->id,
			'type' => STAFF,
			'email' => $this->email,
			'firstName' => $this->firstName,
			'lastName' => $this->lastName);
	}

	private $officePhone;
}

?>