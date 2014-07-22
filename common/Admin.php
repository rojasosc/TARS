<?php
	
final class Admin extends User {
	public function __construct($user_row, $admin_row) {
		parent::__construct($user_row);
	}

	public function toArray() {
		return array(
			'id' => $this->id,
			'type' => ADMIN,
			'email' => $this->email,
			'firstName' => $this->firstName,
			'lastName' => $this->lastName);
	}
}

?>