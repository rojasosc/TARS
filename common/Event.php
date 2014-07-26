<?php

final class Event {

	/** EventTypes documentation:
	 * EVENT TYPES
	 * The following constants correspond to EventTypes.
	 * Each Event object has an EventType, elaborating on what it should appear as,
	 * and what type of object is referenced by objectID.
	 *
	 * --------------------
	 * NOTIFICAITON TEMPLATES
	 *
	 * There's a bunch of NotificationTemplates attached to some EventTypes
	 * representing that Events with that EventType will create Notifications (emails)
	 * to the specified user(s). When the notifyTarget is not "self", the target is in
	 * reference to the objectID. If the objectID is not a User, then emails are sent to
	 * relevent creators (i.e. "target"/creator of a Term object (a staff member), or
	 * "professors" of a Course object)
	 * All "staff" and "admin" targeted notifications go to all Users of those types.
	 *
	 * for example, a PDO exception can be represented by:
	 * name='SERVER_DBERROR', severity=crit, objectType=EventType
	 * with no templates (admin might add one to get notified about PDO errors by email).

	 * alternatively, a successful login can be represented by:
	 * name='SESSION_LOGIN', severity=info, objectType=User
	 * with no templates
	 *
	 * alternatively, a student applying for a course by:
	 name='STUDENT_APPLY', logSeverity=notice, objectType=Application
	 * with templates:
	 * - notifyTarget=self, notifyMode=both, subject="Student Application",
	 *   template="You have applied for APPLICATION OBJECT. The professor(s) of the course will be notified and you should hear back soon."
	 * - notifyTarget=professors, notifyMode=home, subhect="Student Application",
	 *   template="User CREATOR has applied for your course APPLICATION OBJECT."
	 * 
	 * notifyMode=email - Only an email is sent; the user won't see it when they log in.
	 * notifyMode=home - No email is sent; the user will see it when they log in.
	 * notifyMode=both - An email is sent, and it'll appear when they log in.
	 */
	const SERVER_EXCEPTION = 1;
	const SERVER_DBERROR = 2;
	const ERROR_LOGIN = 3;
	const ERROR_PERMISSION = 4;
	const ERROR_FORM_FIELD = 5;
	const ERROR_FORM_UPLOAD = 6;
	const SESSION_LOGIN = 7;
	const SESSION_LOGOUT = 8;
	const SESSION_CONTINUE = 9;
	const USER_CREATE = 10;
	const USER_RESET_PASS = 11;
	const USER_APPLY_TOKEN = 12;
	const USER_IS_EMAIL_AVAIL = 13;
	const USER_GET_OBJECT = 14;
	const USER_GET_VIEW = 15;
	const USER_SET_PROFILE = 16;
	const USER_SET_PASS = 17;
	const STUDENT_APPLY = 18;
	const STUDENT_CANCEL = 19;
	const STUDENT_WITHDRAW = 20;
	const NONSTUDENT_SET_APP = 21;
	const NONSTUDENT_COMMENT = 22;
	const SU_CREATE_USER = 23;
	const SU_RESET_USER_PASS = 24;
	const STAFF_TERM_IMPORT = 25;
	const ADMIN_CONFIGURE = 26;

	private static $eventTypeRows = null;

	public static function getEventTypeName($event_type) {
		$class = new ReflectionClass(__CLASS__);
		$constants = array_flip($class->getConstants());

		return $constants[$event_type];
	}

	private static function cacheEventTypeRows() {
		if (Event::$eventTypeRows == null) {
			$sql = 'SELECT * FROM EventTypes';
			$rows = Database::executeGetAllRows($sql, array());
			Event::$eventTypeRows = $rows;
		}
	}

	public static function getEventTypeIDInDatabase($event_type) {
		$name = Event::getEventTypeName($event_type);
		Event::cacheEventTypeRows();
		foreach (Event::$eventTypeRows as $row) {
			if ($row['eventName'] == $name) {
				return $row['eventTypeID'];
			}
		}
		return null;
	}

	public static function getErrorTextFromEventType($event_type) {
		switch ($event_type) {
		default:
		case Event::SERVER_EXCEPTION:
		case Event::SERVER_DBERROR:
		case Event::ERROR_LOGIN:
		case Event::ERROR_PERMISSION:
		case Event::ERROR_FORM_FIELD:
		case Event::ERROR_FORM_UPLOAD: return 'An error occurred';
		case Event::SESSION_LOGIN: return 'Error logging in';
		case Event::SESSION_LOGOUT: return 'Error logging out';
		case Event::SESSION_CONTINUE: return 'Error accessing page';
		case Event::USER_CREATE: return 'Error creating an account';
		case Event::USER_RESET_PASS: return 'Error resetting password';
		case Event::USER_APPLY_TOKEN: return 'Error applying a token';
		case Event::USER_IS_EMAIL_AVAIL: return 'Error checking email availability';
		case Event::USER_GET_OBJECT: return 'Error retrieving an object';
		case Event::USER_GET_VIEW: return 'Error retrieving a view';
		case Event::USER_SET_PROFILE: return 'Error setting profile data';
		case Event::USER_SET_PASS: return 'Error setting password';
		case Event::STUDENT_APPLY: return 'Error applying to position';
		case Event::STUDENT_CANCEL: return 'Error cancelling application';
		case Event::STUDENT_WITHDRAW: return 'Error withdrawing from position';
		case Event::NONSTUDENT_SET_APP: return 'Error setting application status';
		case Event::NONSTUDENT_COMMENT: return 'Error creating comment';
		case Event::SU_CREATE_USER: return 'Error creating user';
		case Event::SU_RESET_USER_PASS: return 'Error resetting user password';
		case Event::STAFF_TERM_IMPORT: return 'Error importing new term';
		case Event::ADMIN_CONFIGURE: return 'Error setting value';
		}
	}

	public static function insertEventInFile($eventType, $descr, $objectID = null,
		$createTime = null, $creatorIP = null, $creator = null) {
		return Event::insertEventGeneral($eventType, $descr, $objectID, $createTime,
			$creatorIP, $creator, false);
	}

	public static function insertEvent($eventType, $descr, $objectID = null,
		$createTime = null, $creatorIP = null, $creator = null) {
		return Event::insertEventGeneral($eventType, $descr, $objectID, $createTime,
			$creatorIP, $creator, true);
	}

	public static function insertEventGeneral($eventType, $descr, $objectID,
		$createTime, $creatorIP, $creator, $useDB) {
		// default createtime = now
		if ($createTime == null) {
			$createTime = time();
		}
		// default IP = REMOTE_ADDR
		if ($creatorIP == null) {
			$creatorIP = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
		}
		// default creator = currently logged in user (or leave null if session invalid)
		if ($creator == null) {
			if (Database::isConnected()) {
				try {
					$creator = Session::getLoggedInUser();
				} catch (PDOException $ex) {
					// throw away and leave $creator = null on database error
				}
			}
		}

		if ($useDB) {
			$args = array(':etype' => Event::getEventTypeIDInDatabase($eventType), ':descr' => $descr,
				':objectid' => $objectID, ':createtm' => date('Y-m-d H:i:s', $createTime),
				':createip' => inet_pton($creatorIP));

			$creatorID_field = ''; $creatorID_param = '';
			if ($creator != null) {
				$creatorID_field = ', creatorID';
				$creatorID_param = ', :createid';
				$args[':createid'] = $creator->getID();
			}

			$sql = "INSERT INTO Events (eventTypeID, description,
					objectID$creatorID_field, createTime, creatorIP) VALUES
					(:etype, :descr, :objectid$creatorID_param, :createtm, :createip)";

			return Database::executeInsert($sql, $args);
		} else {
			if ($creator == null) {
				$userID = null;
			} else {
				$userID = $creator->getID();
			}
			$data = array('class' => Event::getEventTypeName($eventType),
				'objectID' => $objectID, 'description' => $descr,
				'ip' => $creatorIP, 'userID' => $userID);
			$line = 'TARS EVENT (database access error): '.json_encode($data)."\n";
			$chars_put = error_log($line, 0);
			if ($chars_put === false) {
				return false;
			} else {
				// callers expect the database `eventID` column returned, since this
				// was a fallback, return a valid row value on "success"
				return 1;
			}
		}
	}
}

