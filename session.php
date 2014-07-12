<?php

require_once 'db.php';

/********************
* SESSION FUNCTIONS *
********************/

final class Session {
	// we can cache this object over the course of a PHP script run
	// it won't change more often
	private static $cached = false;
	private static $cachedUserObj = null;

	/* Function Session::login($email, $password)
	*  Purpose: Logs a user in.  Verifies that user's input password field against
	*           a hashed password stored in the database.
	*  Returns: A User Object if login succeeds (password correct), or false on fail.
	*  Throws:  TarsException(SERVER_ERROR) if session_start or session_regenerate_id fail.
	*  Throws:  PDOException if event log or getUserByEmail failed due to database error
	**/
	public static function login($email, $input_password) {
		if ($user_obj = User::getUserByEmail($email)) {
			if (password_verify($input_password, $user_obj->getPassword())) {
				Session::create($user_obj);
				return $user_obj;
			}
		}
		return false;
	}


	/* Function Session::create($user_object)
	*  Purpose:  Initializes a new session. Saves $_SESSION['userID'].
	*  Returns: Nothing.
	*  Throws: TarsException(SERVER_ERROR) if session_start or session_regenerate_id fail.
	*  Throws: PDOException if event log failed due to database error
	**/
	public static function create($user_obj){

		$success = session_start(); // begin the session
		$success = $success && session_regenerate_id(true);  // regenerate a new session id on each log in
		if ($success) {
			Event::insertEvent(Event::SESSION_LOGIN,
				$user_obj->getName().' logged in', $user_obj->getID());
			$_SESSION['userID'] = $user_obj->getID();
		} else {
			throw new TarsException(Event::SERVER_ERROR, Event::SESSION_LOGIN,
				new Exception('Session create failed'));
		}
	}

	/* Function Session::continue($user_type)
	 * Purpose: Continue an existing session on this page.
	 *			If one does not exist, a TarsException is thrown.
	 * Returns: The User object who is currently logged in.
	 * Throws:  TarsException on failure. Catch this and show this sanely, please!
	 */
	public static function start($user_type = -1) {
		$success = session_start();
		$user = null;
		if (!$success) { //If the session failed to start, throw an exception and log the error
			throw new TarsException(Event::SERVER_EXCEPTION, Event::SESSION_CONTINUE,
				new Exception('Session continue failed'));
		} else {
			try { //If the session managed to start...
				$user = Session::getLoggedInUser($user_type); //Find out who logged in
			} catch (PDOException $ex) { //Catch any PDO exceptions and log them
				throw new TarsException(Event::SERVER_DBERROR, Event::SESSION_CONTINUE, $ex);
			}
		}
		if ($user == null) { //If the logged user is not a student
			throw new TarsException(Event::ERROR_PERMISSION, Event::SESSION_CONTINUE,
				array('user not logged in'));
		}
		return $user;
	}

	/* Function Session::getLoggedInUserID()
	*  Purpose: Gets the currently logged in user's object ID.
	*  Returns: Their object ID, or -1 if no session.
	**/
	public static function getLoggedInUserID() {
		return isset($_SESSION['userID']) ? $_SESSION['userID'] : -1;
	}

	/* Function Session::getLoggedInUser(optional $expect_type)
	*  Purpose: Gets the currently logged in User object of their subtype.
	*  Returns: The user, or null if no session or session != $expect_type (if present)
	*  Throws: PDOException if getUserByID failed due to database error
	**/
	public static function getLoggedInUser($expect_type = -1) {
		if (Session::$cached == false) {
			Session::$cachedUserObj = isset($_SESSION['userID']) ?
				User::getUserByID($_SESSION['userID'], $expect_type) : null;
			Session::$cached = true;
		}
		return Session::$cachedUserObj;
	}

	/* Function destroy()
	*  Purpose: Terminates an existing session.  
	*  Returns: nothing. 
	*  Throws: PDOException if event log failed due to database error
	**/
	public static function destroy(){
		// If the getLoggedInUser code throws a database error,
		// continue destroying session and throw the exception later
		$delay_throw = null;
		$user_obj = null;
		if (session_start()) {
			try {
				$user_obj = Session::getLoggedInUser();
			} catch (PDOException $ex) {
				$delay_throw = $ex;
			}
		}

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

		if ($user_obj != null) {
			Event::insertEvent(Event::SESSION_LOGOUT,
				$user_obj->getName().' logged out', $user_obj->getID());
		}

		if ($delay_throw != null) {
			throw $delay_throw;
		}
	}


	/********************
	* END LOGIN FUNCTIONS
	*********************/
}

