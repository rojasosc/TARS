<?php

/**
 * This class handles the capture, logging, and output of errors/exceptions.
 * Create a new one whenever a problem occurs and an Event row will be added.
 *
 * A TarsException is sort of an informal subclass of Event that handles the following Events:
 * - SERVER_EXCEPTION (fatal internal error)
 * - SERVER_DBERROR (SQL/database error)
 * - ERROR_LOGIN ("The email or password you entered is incorrect")
 * - ERROR_PERMISSION ("Permission was denied")
 * - ERROR_FORM_FIELD ("Fields have invalid input")
 *
 * Constructor syntax: new TarsException($class, $action, $more_data)
 *
 * The first parameter ($class) is one of the above that describes what went wrong, and
 * The second parameter ($action) is the Event:: constant that describes what was attempted.
 * - The "object" of these events is the EventType that failed when this error occured.
 * The third parameter ($more_data) depends on the Error type and elaborates what went wrong.
 *
 * With this exception object, you can convert it to an array (->toArray()) for JSON, or to
 * an error <div> (->toHTML()).
 */
final class TarsException extends Exception {
	public static function getMessageFromClass($error_class, $more_data) {
		switch ($error_class) {
		case Event::SERVER_EXCEPTION:
		case Event::SERVER_DBERROR:
			$message = 'An internal error has occurred';
			break;
		case Event::ERROR_LOGIN:
			$message = 'The email or password you entered is incorrect';
			break;
		case Event::ERROR_PERMISSION:
			$message = 'Permission was denied';
			break;
		case Event::ERROR_NOT_FOUND:
			$message = 'Object was not found';
			break;
		case Event::ERROR_FORM_FIELD:
			$message = 'Fields have invalid input. Please fill in these fields and try again';
			break;
		case Event::ERROR_FORM_UPLOAD:
			$message = 'Upload of file failed';
			break;
		case Event::ERROR_CSV_PARSE:
			$message = 'Parsing of CSV failed';
			break;
		case Event::ERROR_JSON_PARSE:
			$message = 'Parsing of JSON failed';
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
		return Event::getEventTypeName($class_code);
	}

	/*
	 * Creates a new exception.
	 *
	 * WARNING: calls to this WILL create an Event and log it, so watch what calls it is
	 * - actually an error
	 * - not already logged previously
	 */
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
			$useDB = Database::isConnected();
			// log the event
			Event::insertEventGeneral($this->class, $this->title.': '.
				$this->message, $this->action, null, null, null, $useDB);
		} catch (PDOException $ex) {
			// we have an error condition on writing an error to the database.
			// this means our database connection probably failed...
			// send the error to php's error_log().
			Event::insertEventInFile($this->class, $this->title.': '.$this->message,
				$this->action);
		}
	}

	/*
	 * Returns the error as a Bootstrap component to be dropped into the page.
	 */
	public function toHTML() {

		return '<div class="alert alert-danger" role="alert"><strong>'.htmlentities($this->title).'!</strong> '.htmlentities($this->message).'</div';
	}

	/*
	 * Returns the error as an array to be dropped into JSON data.
	 */
	public function toArray() {
		return array('class' => TarsException::getErrorClassName($this->class), 'class_code' => $this->class,
			'action' => Event::getEventTypeName($this->action), 'action_code' => $this->action,
			'title' => $this->title, 'message' => $this->message);
	}

	public function getAction() { return $this->action; }

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

