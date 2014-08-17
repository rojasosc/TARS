<?php

/********************
* SESSION FUNCTIONS *
********************/

final class LoginSession {
	// we can cache this object over the course of a PHP script run
	// it won't change more often
	private static $cached = false;
	private static $cachedUserObj = null;

	/* Function LoginSession::login($email, $password)
	*  Purpose: Logs a user in.  Verifies that user's input password field against
	*           a hashed password stored in the database.
	*  Returns: A User Object if login succeeds (password correct), or false on fail.
	*  Throws:  TarsException(SERVER_EXCEPTION) if session_start or session_regenerate_id fail.
	*  Throws:  PDOException if event log or getUserByEmail failed due to database error
	**/
	public static function login($email, $input_password) {
		if ($user_obj = User::getUserByEmail($email)) {
			if ($user_obj->getPassword() === null || password_verify($input_password, $user_obj->getPassword())) {
				return $user_obj;
			}
		}
		return null;
	}


	/* Function LoginSession::sessionCreate($user_object)
	*  Purpose:  Initializes a new session. Saves $_SESSION['userID'].
	*  Returns: Nothing.
	*  Throws: TarsException(SERVER_EXCEPTION) if session_start or session_regenerate_id fail.
	**/
	public static function sessionCreate($user_obj){
		LoginSession::start(true, Event::SESSION_LOGIN);
		$_SESSION['userID'] = $user_obj->getID();
	}

	/* Function LoginSession::start([$regenerate], [$eventTypeID])
	*  Purpose: Calls session_start() and checks for errors
	*  Throws: TarsException(SERVER_EXCEPTION) if session_start or session_regenerate_id fail.
	**/
	public static function start($regenerate = false, $eventTypeID = Event::SESSION_CONTINUE) {
		// begin the session
		if (!session_start()) {
			throw new TarsException(Event::SERVER_EXCEPTION, $eventTypeID, 'session_start() failed');
		}

		if ($regenerate) {
			// regenerate a new session id on each log in
			if (!session_regenerate_id(true)) {
				throw new TarsException(Event::SERVER_EXCEPTION, $eventTypeID, 'session_regenerate_id() failed');
			}
		}
	}

	/* Function LoginSession::sessionContinue($user_type)
	 * Purpose: Continue an existing session on this page.
	 *			If one does not exist, a TarsException is thrown.
	 * Returns: The User object who is currently logged in.
	 * Throws:  TarsException on failure. Catch this and show this sanely, please!
	 */
	public static function sessionContinue($user_type = -1) {
		$user = null;
		// throws TarsException
		LoginSession::start();

		//If the session managed to start...
		try {
			//Find out who logged in
			$user = LoginSession::getLoggedInUser($user_type);
		} catch (PDOException $ex) {
			//Catch any PDO exceptions and log them
			throw new TarsException(Event::SERVER_DBERROR, Event::SESSION_CONTINUE, $ex);
		}

		//If the logged user is not of the correct type, or no one is logged in:
		if ($user === null) {
			throw new TarsException(Event::ERROR_ACTION, Event::SESSION_CONTINUE, 'Permission denied');
		}

		return $user;
	}

	/* Function LoginSession::sessionDestroy()
	*  Purpose: Terminates an existing session.  
	**/
	public static function sessionDestroy(){
		 /**************************************
		 *NOTE: This code was obtained directly
		 *from the PHP5 Manual on ending sessions. 
		 **************************************/

		// Unset all of the session variables.
		$_SESSION = array();

		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000,
				$params["path"], $params["domain"],
				$params["secure"], $params["httponly"]);
		}

		// Finally, destroy the session.
		session_destroy();

		/**************************************/
	}


	/* Function LoginSession::getLoggedInUserID()
	*  Purpose: Gets the currently logged in user's object ID.
	*  Returns: Their object ID, or -1 if no session.
	**/
	public static function getLoggedInUserID() {
		return isset($_SESSION['userID']) ? $_SESSION['userID'] : -1;
	}

	/* Function LoginSession::getLoggedInUser(optional $expect_type)
	*  Purpose: Gets the currently logged in User object of their subtype.
	*  Returns: The user, or null if no session or session != $expect_type (if present)
	*  Throws: PDOException if getUserByID failed due to database error
	**/
	public static function getLoggedInUser($expect_type = -1) {
		if (LoginSession::$cached == false) {
			LoginSession::$cachedUserObj = isset($_SESSION['userID']) ?
				User::getUserByID($_SESSION['userID'], $expect_type) : null;
			LoginSession::$cached = true;
		}
		return LoginSession::$cachedUserObj;
	}


	/* Function LoginSession::saveDataForRedirect($data)
	 * Purpose: Stores $data in the $_SESSION variable so that we can redirect and keep it later.
	 *			DO NOT call this on any page with LoginSession::sessionCreate() or LoginSession::sessionContinue()
	 */
	public static function saveDataForRedirect($data) {
		try {
			LoginSession::start();
		} catch (TarsException $ex) {
			return;
		}

		$_SESSION['callbackResult'] = $data;
	}


	/* Function LoginSession::retrieveData()
	 * Purpose: Retrieves data stored in $_SESSION by LoginSession::saveDataForRedirect().
	 *			DO NOT call this on any page with LoginSession::sessionCreate() or LoginSession::sessionContinue()
	 */
	public static function retrieveSavedData() {
		try {
			LoginSession::start();
		} catch (TarsException $ex) {
			return null;
		}

		$output = null;
		if (isset($_SESSION['callbackResult'])) {
			$output = $_SESSION['callbackResult'];
		}
		LoginSession::sessionDestroy();
		return $output;
	}

	/********************
	* END LOGIN FUNCTIONS
	*********************/
}

