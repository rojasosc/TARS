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
    const SERVER_EXCEPTION = 'SERVER_EXCEPTION';
    const SERVER_DBERROR = 'SERVER_DBERROR';
    const ERROR_ACTION = 'ERROR_ACTION';
    const SESSION_LOGIN = 'SESSION_LOGIN';
    const SESSION_LOGOUT = 'SESSION_LOGOUT';
    const SESSION_CONTINUE = 'SESSION_CONTINUE';
    const USER_REPORT_BUG = 'USER_REPORT_BUG';
    const USER_CREATE = 'USER_CREATE';
    const USER_RESET_PASS = 'USER_RESET_PASS';
    const USER_APPLY_TOKEN = 'USER_APPLY_TOKEN';
    const USER_IS_EMAIL_AVAIL = 'USER_IS_EMAIL_AVAIL';
    const USER_GET_OBJECT = 'USER_GET_OBJECT';
    const USER_GET_VIEW = 'USER_GET_VIEW';
    const USER_SET_PROFILE = 'USER_SET_PROFILE';
    const USER_SET_PASS = 'USER_SET_PASS';
    const STUDENT_APPLY = 'STUDENT_APPLY';
    const STUDENT_CANCEL = 'STUDENT_CANCEL';
    const STUDENT_WITHDRAW = 'STUDENT_WITHDRAW';
    const NONSTUDENT_SET_APP = 'NONSTUDENT_SET_APP';
    const NONSTUDENT_COMMENT = 'NONSTUDENT_COMMENT';
    const SU_CREATE_USER = 'SU_CREATE_USER';
    const SU_RESET_USER_PASS = 'SU_RESET_USER_PASS';
    const STAFF_TERM_IMPORT = 'STAFF_TERM_IMPORT';
    const ADMIN_CONFIGURE = 'ADMIN_CONFIGURE';

    private static $eventTypeRows = null;

    private static function cacheEventTypeRows() {
        if (Event::$eventTypeRows == null) {
            $sql = 'SELECT * FROM EventTypes';
            $rows = Database::executeGetAllRows($sql, array());
            Event::$eventTypeRows = $rows;
        }
    }

    public static function getEventTypeIDInDatabase($event_type) {
        Event::cacheEventTypeRows();
        foreach (Event::$eventTypeRows as $row) {
            if ($row['eventName'] === $event_type) {
                return $row['eventTypeID'];
            }
        }
        return null;
    }

    public static function getEventTypeNameInDatabase($event_type_id) {
        Event::cacheEventTypeRows();
        foreach (Event::$eventTypeRows as $row) {
            if (intval($row['eventTypeID']) === $event_type_id) {
                return $row['eventName'];
            }
        }
        return null;
    }

    public static function getErrorTextFromEventType($event_type) {
        switch ($event_type) {
        default:
        case Event::SERVER_EXCEPTION:
        case Event::SERVER_DBERROR:
        case Event::ERROR_ACTION: return 'An error occurred';
        case Event::SESSION_LOGIN: return 'Error logging in';
        case Event::SESSION_LOGOUT: return 'Error logging out';
        case Event::SESSION_CONTINUE: return 'Error accessing page';
        case Event::USER_REPORT_BUG: return 'Error reporting a bug';
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
                    $creator = LoginSession::getLoggedInUser();
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
            if ($creator !== null) {
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
            $data = array('class' => $eventType,
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

    public static function findEvents($userFilter, $eventSeverities, $pg) {
        $sql = 'SELECT eventID, eventName, severity, Events.eventTypeID,
                objectType, objectID, description, Events.creatorID, Events.createTime, Events.creatorIP
                FROM Events
                INNER JOIN EventTypes ON EventTypes.eventTypeID = Events.eventTypeID
                LEFT JOIN Users ON Users.userID = Events.creatorID
                WHERE ';
        $args = array();
        if (!empty($userFilter) && strlen($userFilter) > 0) {
            $sql .= '(INSTR(Users.email, :user) > 0 OR INSTR(CONCAT_WS(\' \', Users.firstName, Users.lastName), :user) > 0) AND ';
            $args[':user'] = $userFilter;
        }

        // severity filter:
        $sql .= '(';
        $sevCount = 0;
        foreach ($eventSeverities as $severity => $enabled) {
            if ($enabled) {
                $sql .= "EventTypes.severity = '$severity' OR ";
                $sevCount++;
            }
        }
        if ($sevCount === 0) {
            $sql .= '1) AND ';
        } else {
            $sql .= '0) AND ';
        }

        // end query
        $sql .= '1';
        return Database::executeGetPage($sql, $args, $pg, function($row) {return new Event($row);});
    }

    public function __construct($row) {
        $this->id = intval($row['eventID']);
        $this->eventType = $row['eventName'];
        $this->severity = $row['severity'];
        $this->objectType = $row['objectType'];
        $this->objectID = $row['objectID'] === null ? null : intval($row['objectID']);
        $this->objectValue = null;
        $this->descr = $row['description'];
        $this->creatorID = $row['creatorID'] === null ? null : intval($row['creatorID']);
        $this->creator = null;
        $this->createTime = strtotime($row['createTime']);
        $this->creatorIP = inet_ntop($row['creatorIP']);
    }

    public function getID() { return $this->id; }
    public function getEventType() { return $this->eventType; }
    public function getSeverity() { return $this->severity; }
    public function getObjectType() { return $this->objectType; }
    public function getDescription() { return $this->descr; }

    public function getObject() {
        if ($this->objectValue === null && $this->objectID !== null) {
            switch ($this->objectType) {
            case 'User': $this->objectValue = User::getUserByID($this->objectID); break;
            case 'EventType': $this->objectValue = Event::getEventTypeNameInDatabase($this->objectID); break;
            case 'Application': $this->objectValue = Application::getApplicationByID($this->objectID); break;
            case 'Term': $this->objectValue = Term::getTermByID($this->objectID); break;
            case 'Comment': $this->objectValue = Comment::getCommentByID($this->objectID); break;
            case 'Configuration': break; // TODO: NYI
            }
        }
        return $this->objectValue;
    }
    public function getCreator() {
        if ($this->creator === null && $this->creatorID !== null) {
            $this->creator = User::getUserByID($this->creatorID);
        }
        return $this->creator;
    }
    public function getCreateTime() { return $this->createTime; }
    public function getCreatorIP() { return $this->creatorIP; }

    public function toArray($showEvent = true) {
        $data = array(
            'id' => $this->id,
            'type' => $this->eventType,
            'severity' => $this->severity,
            'description' => $this->descr,
            'objectType' => $this->objectType);
        if ($showEvent) {
            $object = $this->getObject();
            if (is_object($object)) {
                $object = $object->toArray();
            }
            $creator = $this->getCreator();
            $data['object'] = $object === null ? null : $object;
            $data['creator'] = $creator === null ? null : $creator->toArray(false);
            $data['createTime'] = date('Y-m-d j:i:s', $this->createTime);
            $data['creatorIP'] = $this->creatorIP;
        }
        return $data;
    }

    private $id;
    private $eventType;
    private $severity;
    private $objectType;
    private $objectID;
    private $descr;
    private $creatorID;
    private $creator;
    private $createTime;
    private $creatorIP;
}

