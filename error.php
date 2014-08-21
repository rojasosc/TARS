<?php

/**
 * This class handles the capture, logging, and output of errors/exceptions.
 * Create a new one whenever a problem occurs and an Event row will be added.
 *
 * A TarsException is sort of an informal subclass of Event that handles the following Events:
 * - SERVER_EXCEPTION (fatal internal error)
 * - SERVER_DBERROR (SQL/database error)
 * - ERROR_ACTION (all external errors, "Action" refers to the Actions API, actions.php)
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
		case Event::ERROR_ACTION:
			$message = 'Invalid input in fields. Please fix these fields and try again';
			break;
		}
		// exceptions in parens
		if (is_subclass_of($more_data, 'Exception')) {
			$message .= " ({$more_data->getMessage()})";
		}
		// arrays show in parens (USE CASE: list of fields not filled in)
		if (is_array($more_data)) {
			$parr = implode(', ', $more_data);
			$message .= " ($parr)";
		}
		// string replaces message (USE CASE: ERROR_ACTION with better reason field)
		if (is_string($more_data)) {
			$message = $more_data;
		}
		return "$message.";
	}

	public static function getTitleFromAction($error_action) {
		return Event::getErrorTextFromEventType($error_action);
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
		$ex_msg = $this->message;
		$ex_code = 1000;
		$ex_ex = is_subclass_of($more_data, 'Exception') ? $more_data : null;
		parent::__construct($ex_msg, $ex_code, $ex_ex);

		// create an Event for this
		try {
			$useDB = Database::isConnected();
			// cancel pending transaction
			if ($useDB && Database::inTransaction()) {
				Database::rollbackTransaction();
			}
			// log the event
			Event::insertEventGeneral($this->class, $this->title.': '.
				$this->message, Event::getEventTypeIDInDatabase($this->action),
				null, null, null, $useDB);
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
	 *
	 * HTML-entity-encodes the title and message
	 */
	public function toHTML() {
		return TarsException::makeAlert(htmlentities($this->title),
			htmlentities($this->message), 'danger');
	}

	/*
	 * Makes a general alert to be printed (Bootstrap component)
	 *
	 * Does not encode HTML entities.
	 */
	public static function makeAlert($title, $message, $alert_level) {

		return '<div class="alert alert-'.$alert_level.'" role="alert"><strong>'.$title.'!</strong> '.$message.'</div';
	}

	/*
	 * Returns the error as an array to be dropped into JSON data.
	 */
	public function toArray() {
		return array('class' => $this->class, 'action' => $this->action,
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

