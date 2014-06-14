<?php


/**
 * This class handles the capture and receiving of errors on the PHP-side.
 */
class Error {
	private static $error = null;

	const EXCEPTION = 1;
	const FORM_SUBMISSION = 2;
	const CUSTOM_MESSAGE = 3;
	const PERMISSION_DENIED = 4;

	/**
	 * Call this function when an error occurs.
	 */
	public static function setError($type, $msg, $object = null) {
		Error::$error = new Error($type, $msg, $object);
	}

	public static function getError() {
		return Error::$error;
	}

	/**
	 * Call this function to print a <div class="error"> to the page where
	 * it is appropriate. If no error is saved, nothing will be printed, so
	 * you may leave a call to this above any form.
	 */
	public static function putError() {
		$e = Error::getError();
		if ($e !== null) {
			echo $e->toHTML();
		}
	}

	private function __construct($type, $msg, $object) {
		$this->type = $type;
		$this->msg = $msg;
		$this->object = $object;
	}

	public function toHTML() {
		$result = '<div class="error"><p><b>'.htmlentities($this->msg).'</b></p>';
		switch ($this->type) {
		case Error::EXCEPTION:
			$result .= '<p>aAn exception occurred!<br/>';
			$result .= htmlentities($this->object->getMessage()).'</p>';
			break;
		case Error::FORM_SUBMISSION:
			$result .= '<p>The following fields have invalid input:<ul>';
			foreach ($this->object as $element) {
				$result .= '<li>'.htmlentities($element).'</li>';
			}
			$result .= '</ul>Please fill in these fields and try again.</p>';
			break;
		case Error::CUSTOM_MESSAGE:
			$result .= '<p>'.htmlentities($this->object).'</p>';
			break;
		case Error::PERMISSION_DENIED:
			break;
		}
		$result .= '</div>';
		return $result;
	}

	public function toArray() {
		$result = array('type' => $this->getErrorName(), 'code' => $this->type,
			'title' => $this->msg);
		switch ($this->type) {
		case Error::EXCEPTION:
			$result['exception'] = $this->object->getMessage();
			$result['message'] = $this->object->getMessage();
			break;
		case Error::FORM_SUBMISSION:
			$result['invalid_field_values'] = $this->object;
			$result['message'] = 'Fields have invalid input. Please fill in these fields and try again.';
			break;
		case Error::CUSTOM_MESSAGE:
			$result['message'] = $this->object;
			break;
		case Error::PERMISSION_DENIED:
			$result['message'] = '';
			break;
		}
		return $result;
	}

	public function getErrorName() {
		$class = new ReflectionClass(__CLASS__);
		$constants = array_flip($class->getConstants());

		return $constants[$this->type];
	}

	private $type;
	private $msg;
	private $object;
}

