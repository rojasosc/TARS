<?php


/**
 * This class handles the capture and receiving of errors on the PHP-side.
 */
class Error {
	private static $error = null;

	const EXCEPTION = 1;
	const FORM_SUBMISSION = 2;

	/**
	 * Call this function when an error occurs.
	 */
	public static function setError($type, $msg, $object) {
		Error::$error = new Error($type, $msg, $object);
	}

	/**
	 * Call this function to print a <div class="error"> to the page where
	 * it is appropriate. If no error is saved, nothing will be printed, so
	 * you may leave a call to this above any form.
	 */
	public static function putError() {
		$e = Error::$error;
		if ($e == null) {
			return;
		}

		switch ($e->type) {
		case Error::EXCEPTION:
			echo '<div class="error"><p><b>'.htmlentities($e->msg).
				'</b></p><p>An exception occurred!'.
				'<br/>'.htmlentities($e->object->getMessage()).'</p></div>';
			break;
		case Error::FORM_SUBMISSION:
			echo '<div class="error"><p><b>'.htmlentities($e->msg).
				'</b></p><p>The following fields are not valid:<ul>';
			foreach ($e->object as $element) {
				echo '<li>'.htmlentities($element).'</li>';
			}
			echo '</ul>Please fill in these fields to continue.</p></div>';
			break;

		}

		Error::$error = null;
	}

	private function __construct($type, $msg, $object) {
		$this->type = $type;
		$this->msg = $msg;
		$this->object = $object;
	}

	private $type;
	private $msg;
	private $object;
}

