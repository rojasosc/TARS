<?php

/*
 * User Object: Represents a User in the database.
 *
 * This is a Primary Object: it has a creator and createTime field
 */
abstract class User {
	/**
	 * User::getUserByID($id, [$type])
	 *
	 * Purpose: get a User subclass object (Student, Professor, Staff, Admin) by ID
	 * Returns: the wanted object, or NULL on no match
	 * Throws:  PDOException on SERVER_DBERROR
	 * Note:    $type, if set, restricts the returned object to be of that user type.
	 *          If the retrieved user is of the wrong type, it returns NULL.
	 */
	public static function getUserByID($id, $check_type = -1) {
		$sql = 'SELECT * FROM Users WHERE userID = :id';
		$args = array(':id' => $id);
		if ($check_type >= 0) {
			$sql .= ' AND type = :type';
			$args[':type'] = $check_type;
		}
		$user_row = Database::executeGetRow($sql, $args);
		if ($user_row == null) {
			return null;
		}
		return User::getUserSubclassObject($user_row);
	}

	/**
	 * User::getUserByEmail($email, [$type])
	 *
	 * Purpose: get a User subclass object (Student, Professor, Staff, Admin) by email
	 * Returns: the wanted object, or NULL on no match
	 * Throws:  PDOException on SERVER_DBERROR
	 * Note:    $type, if set, restricts the returned object to be of that user type.
	 *          If the retrieved user is of the wrong type, it returns NULL.
	 */
	public static function getUserByEmail($email, $check_type = -1) {
		$sql = 'SELECT * FROM Users WHERE email = :email';
		$args = array(':email' => $email);
		if ($check_type >= 0) {
			$sql .= ' AND type = :type';
			$args[':type'] = $check_type;
		}
		$user_row = Database::executeGetRow($sql, $args);

		if ($user_row) {
			return User::getUserSubclassObject($user_row);
		} else {
			return null;
		}
	}

	/**
	 * PRIVATE User::getUserSubclassObject($row)
	 *
	 * Purpose: get a User subclass object from the Users table row
	 * Returns: the wanted object, or NULL on usertype mismatch or missing data
	 * Throws:  PDOException on SERVER_DBERROR
	 */
	private static function getUserSubclassObject($user_row) {
		$args = array(':id' => $user_row['userID']);
		switch ($user_row['type']) {
		case STUDENT:
			$row = Database::executeGetRow('SELECT * FROM Students WHERE userID = :id', $args);
			return new Student($user_row, $row);
		case PROFESSOR:
			$row = Database::executeGetRow('SELECT * FROM Professors WHERE userID = :id', $args);
			return new Professor($user_row, $row);
		case STAFF:
			$row = Database::executeGetRow('SELECT * FROM Staff WHERE userID = :id', $args);
			return new Staff($user_row, $row);
		case ADMIN:
			// TODO add admin table?
			return new Admin($user_row, false);
		}
		return null;
	}

	public static function findUsers($email, $firstName, $lastName, $check_type = -1) {
		$sql = 'SELECT * FROM Users
				WHERE ';
		$args = array();
		if (!empty($email)) {
			$sql .= 'INSTR(email, :email) AND ';
			$args[':email'] = $email;
		}
		if (!empty($firstName)) {
			$sql .= 'INSTR(firstName, :firstName) AND ';
			$args[':firstName'] = $firstName;
		}
		if (!empty($lastName)) {
			$sql .= 'INSTR(lastName, :lastName) AND ';
			$args[':lastName'] = $lastName;
		}
		if ($check_type >= 0) {
			$sql .= 'type = :type AND ';
			$args[':type'] = $check_type;
		}
		$sql .= '1 ORDER BY lastName DESC, firstName DESC';
		$rows = Database::executeGetAllRows($sql, $args);
		return array_map(function ($row) { return User::getUserSubclassObject($row); }, $rows);
	}

	public static function insertUserSelfCreated($email, $password, $firstName, $lastName, $type) {
		$sql = 'INSERT INTO Users
				(email, emailVerified, password, passwordReset,
				firstName, lastName, createTime, type) VALUES
				(:email, :eVer, :password, :pRes, :firstName, :lastName, :ctime, :type)';
		$args = array(':email' => $email, ':eVer' => 0, ':password' => $password,
				':pRes' => 0, ':firstName' => $firstName, ':lastName' => $lastName,
				':ctime' => date('Y-m-d H:i:s'), ':type' => $type);
		return Database::executeInsert($sql, $args);
	}

	public static function checkEmailAvailable($email) {
		if (empty($email)) {
			// empty email unavailable
			return false;
		} else {
			$count = Database::executeGetScalar('SELECT COUNT(*) FROM Users WHERE email = :email',
				array(':email' => $email));
			return $count == 0;
		}
	}

	protected function __construct($user_row) {
		$this->id = $user_row['userID'];
		$this->email = $user_row['email'];
		$this->password = $user_row['password'];
		$this->otype = $user_row['type'];
		$this->firstName = $user_row['firstName'];
		$this->lastName = $user_row['lastName'];
		$this->creatorID = $user_row['creatorID'];
		$this->creator = null;
		$this->createTime = strtotime($user_row['createTime']);
	}

	public function changePassword($newPassword) {
		$password_hash = password_hash($newPassword, PASSWORD_DEFAULT);

		$sql = 'UPDATE Users
				SET password = :password
				WHERE userID = :userID';
		$args = array(':userID' => $this->id, ':password' => $password_hash);
		return Database::execute($sql, $args);
	}

	public function getID() { return $this->id; }
	public function getEmail() { return $this->email; }
	public function getPassword() { return $this->password; }
	public function getObjectType() { return $this->otype; }
	public function getFirstName() { return $this->firstName; }
	public function getLastName() { return $this->lastName; }
	public function geCreator() {
		if ($this->creator == null && $this->creatorID != null) {
			$this->creator = User::getUserByID($this->creatorID);
		}
		return $this->creator;
	}
	public function getCreateTime() { return $this->createTime; }

	/* "first-initial last" name */
	public function getFILName() {
		return $this->firstName[0].'. '.$this->lastName;
	}

	/* first name space last name */
	public function getName() {
		return $this->firstName.' '.$this->lastName;
	}

	protected $id;
	protected $email;
	protected $password;
	protected $otype;
	protected $firstName;
	protected $lastName;
	protected $creatorID;
	protected $creator;
	protected $createTime;
}

?>