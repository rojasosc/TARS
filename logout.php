

<?php
 
 /*This file can be called from any page to securely end a session */
 
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
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session.
session_destroy();
 
        header("Location: index.php");

	 
?>
