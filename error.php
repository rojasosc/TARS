<?php


/**
 * This class handles the capture and receiving of errors on the PHP-side.
 */
final class TarsException extends Exception {
	private static $ex = null;

	public static function setException($ex) {
		TarsException::$ex = $ex;
	}

	public static function getException() {
		return TarsException::$ex;
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

	public static function getMessageFromClass($error_class, $more_data) {
		switch ($error_class) {
		case Event::SERVER_EXCEPTION:
		case Event::SERVER_PDOERR:
			$message = 'An internal error has occurred';
			break;
		case Event::ERROR_LOGIN:
			$message = 'The email or password you entered is incorrect';
			break;
		case Event::ERROR_PERMISSION:
			$message = 'Permission was denied';
			break;
		case Event::ERROR_FORM_FIELD:
			$message = 'Fields have invalid input. Please fill in these fields and try again';
			break;
		}
		if (is_subclass_of($more_data, 'Exception')) {
			$message .= " ({$more_data->getMessage()})";
		}
		if (is_array($more_data)) {
			$parr = implode(', ', $more_data);
			$message .= " ($parr)";
		}
		return "$message.";
	}

	public static function getTitleFromAction($error_action) {
		return Event::getErrorTextFromEventType($error_action);
	}

	public static function getErrorClassName($class_code) {
		$class = new ReflectionClass(__CLASS__);
		$constants = array_flip($class->getConstants());

		return $constants[$class_code];
	}

	public function __construct($error_class, $error_action, $more_data = null) {
		$this->class = $error_class;
		$this->action = $error_action;
		$this->more_data = $more_data;
		$this->title = TarsException::getTitleFromAction($error_action);
		$this->message = TarsException::getMessageFromClass($error_class, $more_data);
		parent::__construct($this->message, $this->class,
			is_subclass_of($more_data, 'Exception') ? $more_data : null);

		// create an Event for this
		try {
			// log the event
			Event::createEvent($this->class, $this->title.' / '.
				$this->message, $this->action);
		} catch (PDOException $ex) {
			// we have an error condition on writing an error to the log.
			// this is very bad. print error to ./error.log

		}
	}

	public function toHTML() {
		return '<div class="error"><p><b>'.htmlentities($this->title).'</b></p><p>'.htmlentities($this->message).'</p></div>';
	}

	public function toArray() {
		return array('class' => TarsException::getErrorClassName($this->class), 'class_code' => $this->class,
			'action' => Event::getEventTypeName($this->action), 'action_code' => $this->action,
			'title' => $this->title, 'message' => $this->message);
	}

	// error classes: the things that represent the type of thing that went wrong
	// corresponds to Events.eventTypeID for the logged error Event object.
	// Event::SERVER_* and Event::ERROR_* values are appropriate in this field.
	protected $class;

	// action code: the things that represent the thing that was attempted
	// corresponds to Events.objectID for the logged error Event object.
	// Events.objectID for all error Events is of type EventType, so this is also an EventType
	// All event types are appropriate in this field.
	protected $action;

	// displayed parts of a TarsException.
	protected $title;
	protected $message;
	protected $more_data;
}

