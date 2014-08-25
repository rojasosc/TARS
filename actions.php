<?php
// This is the remote call (Action) handler
// it accepts parameters via POST request
// but should now handle errors properly
//
// Required parameters:
// action: The current action name. If it matches one in the $action_map,
// then that action will be attempted and may require additional parameters.
// This parameter can be provided by $_GET also (to support Bootstrap Validator)
//
// Output format: Always JSON with the following properties:
// "success" (always present): true or false
// "error" (present iff "success" is false): the TarsException object serialized
//    Always has "title" and "message" to be shown in a Bootstrap alert.
// "object" (present depending on action): The returned object
// "objects" (present depending on action): The returned array of objects
// "valid" (present depending on action): The returned boolean (for Bootstrap Validator)
require_once 'db.php';

/**
 * A function to tell us whether the given array is "associative" (has any string keys)
 * Source: http://stackoverflow.com/a/4254008/835995
 */
function is_assoc(array $array) {
    return (bool)count(array_filter(array_keys($array), 'is_string'));
}

final class ActionError extends Exception {
    public function __construct($msg, $extra_params = array()) {
        parent::__construct($msg);

        $this->extra_params = $extra_params;
    }

    public function getExtraParameters() { return $this->extra_params; }

    private $extra_params;
}



/**
 * Actions can be called remotely via requesting this script
 *
 * Error checking is provided by callAction($actionName, $params, $user)
 * It is NOT provided by the action functions themselves
 */
final class Action {
    // To create an action, add its function to this class, then add a corresponding
    // element to $action_map to describe how Action::callAction() has to verify parameters, error check, and event log it.
    //
    // Documentation for these functions is provided with the corresponding $action_map element
    // at the bottom of this class. It's sort of like an interface (i.e. Java)
    public static function login($params, $user, &$eventObjectID) {
        // app settings used
        $adminCreated = intval(Configuration::get(Configuration::ADMIN_CREATED));
        $loginEnabled = intval(Configuration::get(Configuration::ENABLE_LOGIN));

        // admin setup steps
        if (isset($params['cfg'])) {
            if ($adminCreated === 0) {
                // create User objects:
                // "Initial Account Email"
                $rootUserID = User::insertUser($params['email'], null, 'Root', 'User', ADMIN);
                $rootUser = User::getUserByID($rootUserID, ADMIN);
                $rootUser->confirmEmail();

                // "Bug Reporting Email"
                $bugReportUserID = User::insertUser($params['cfg-bug-user'], null, 'Bug', 'Reporting', ADMIN);
                $bugReportUser = User::getUserByID($bugReportUserID, ADMIN);
                $bugReportUser->disableAccount();

                // create ResetToken for rootUser
                ResetToken::generateToken('reset', $rootUserID, time());

                // create initial Configuration
                Configuration::setMultiple(array(
                    Configuration::LOG_DEBUG => true,
                    Configuration::ADMIN_CREATED => true,
                    Configuration::ENABLE_LOGIN => true,
                    Configuration::ENABLE_SEND_EMAIL => true,
                    Configuration::BUG_REPORT_USER => $rootUserID,
                    Configuration::CURRENT_TERM => null,
                    Configuration::EMAIL_NAME => $params['cfg-email-name'],
                    Configuration::EMAIL_DOMAIN => $params['cfg-email-domain'],
                    Configuration::EMAIL_LINK_BASE => $params['cfg-email-linkbase']),
                $rootUser, time());

                $adminCreated = 1;
                $loginEnabled = 1;
                // continue to login... will give the root user the ability to set their password

                // XXX silly hack: committing the Action transaction here in order to preserve the
                // setup configuration, in time for the login code below to ask you to set a password.
                Database::commitTransaction();
                Database::beginTransaction();
            }
        }

        // find an email and password that matches
        $sessionUser = LoginSession::login($params['email'], $params['password']);
        if ($sessionUser === null) {
            if ($loginEnabled === 0) {
                // alternate login failed message if logins are disabled
                throw new ActionError('Account logins are disabled');
            } else {
                throw new ActionError('The email or password you entered is incorrect');
            }
        }

        // if not admin and logins disabled, stop here
        if (($adminCreated === 0 || $loginEnabled === 0) && $sessionUser->getObjectType() !== ADMIN) {
            throw new ActionError('Account logins are disabled');
        }

        // handle users with unverified email
        if (!$sessionUser->isEmailConfirmed()) {
            // create a 'resend' token pointing to the 'signup' callback token
            $token = ResetToken::getTokenByAction('signup', $sessionUser->getID());
            $extra = array();
            $extra['token'] = $token;
            $extra['userID'] = $sessionUser->getID();
            if ($token !== null) {
                $tokenAction = ResetToken::getActionByToken($token);
                $extra['notifID'] = $tokenAction->getCallbackNotifID();
            } else {
                $extra['notifID'] = null;
            }
            throw new ActionError('Your email has not been confirmed yet', $extra);
        }

        // handle users with verified email but passwordReset = true (reset psasword requested)
        if ($sessionUser->isPasswordResetRequested()) {
            $token = ResetToken::getTokenByAction('reset', $sessionUser->getID());
            // password reset required
            if ($sessionUser->getPassword() === null) {
                // give the stored 'reset' token, if any
                $extra = array();
                $extra['token'] = $token;
                $extra['userID'] = $sessionUser->getID();
                throw new ActionError('Your password must be set', $extra);
            // cancel password reset (they logged in with their old password)
            } else {
                // throw away tokenAction, result of applyToken()
                ResetToken::applyToken($token);
                // User.passwordReset = 0
                $sessionUser->passwordSet();
            }
        }

        // handle users with verified email, passwordReset = false, password = null (reset required, but not allowed)
        // i.e. tarsbug@csug.rochester.edu: disabled accounts
        if ($sessionUser->getPassword() === null) {
            throw new ActionError('This account is disabled');
        }

        // actually create the login session
        LoginSession::sessionCreate($sessionUser);

        // save the returned user as the ObjectID of the SESSION_LOGIN event
        $eventObjectID = $sessionUser->getID();

        // return the logged in user's object
        return $sessionUser;
    }

    public static function logout($params, $user, &$eventObjectID) {
        $delayThrow = null;
        $sessionUser = null;
        try {
            LoginSession::start(false, Event::SESSION_LOGOUT);
            $sessionUser = LoginSession::getLoggedInUser();
        } catch (PDOException $ex) {
            $delayThrow = $ex;
        } catch (TarsException $ex) {
            $delayThrow = $ex;
        }

        LoginSession::sessionDestroy();

        if ($sessionUser !== null) {
            $eventObjectID = $sessionUser->getID();
        }

        if ($delayThrow !== null) {
            throw $delayThrow;
        }
        return null;
    }

    public static function emailAvailable($params, $user, &$eventObjectID) {
        $email = $params['email'];
        return User::checkEmailAvailable($email);
    }

    public static function sendBugReport($params, $user, &$eventObjectID) {
        $email = $params['bugrepemail'];
        $password = $params['bugreppass'];
        $report = $params['bugreport'];

        $user = LoginSession::login($email, $password);
        if ($user === null) {
            throw new ActionError('The email or password you entered is incorrect');
        }

        $time = time();
        $bugReportUserID = Configuration::get(Configuration::BUG_REPORT_USER);
        if ($bugReportUserID === null || User::getUserByID($bugReportUserID) === null) {
            throw new ActionError('Bug reporting is not configured');
        }
        // TODO: use constants or something for the below text
        $notifID = Notification::insertNotification($bugReportUserID,
            true, false, 'Bug Report', null,
            "A bug has been reported by :type :user (:email):\r\n\r\n:report",
            $time);
        $notif = Notification::getNotificationByID($notifID);
        switch ($user->getObjectType()) {
        default: $type ='UNKNOWNTYPE';
        case STUDENT: $type = 'STUDENT';
        case PROFESSOR: $type = 'PROFESSOR';
        case STAFF: $type = 'STAFF';
        case ADMIN: $type = 'ADMIN';
        }
        $notif->sendEmail(array(':user' => $user->getName(), ':type' => $type, ':email' => $user->getEmail(), ':report' => $report), Event::USER_CREATE);
    }

    public static function signup($params, $user, &$eventObjectID) {
        $email = $params['email']; $password = $params['password'];
        $firstName = $params['firstName']; $lastName = $params['lastName'];
        // filter non-digits
        $mobilePhone = preg_replace('/([^\d]+)/', '', $params['mobilePhone']);
        $classYear = $params['classYear'];
        $major = $params['major']; $gpa = $params['gpa'];
        $universityID = $params['universityID']; $aboutMe = $params['aboutMe'];

        $studentID = Student::registerStudent(
            $email, $password, $firstName, $lastName,
            $mobilePhone, $classYear, $major, $gpa, $universityID, $aboutMe);

        $time = time();
        // TODO: use constants or something for the below text
        $notifID = Notification::insertNotification($studentID, true, false,
            'Confirm Your Email', null,
            "You have successfully created a new student account on the TA Reporting System using this email address. Click the following link to confirm your email address:\r\n\r\n:link\r\n\r\nYou may then login using this account and search for positions to apply for. Thank you for using TARS.",
            $time);
        $signupToken = ResetToken::generateToken('signup', $studentID, $time, null, $notifID);

        $notif = Notification::getNotificationByID($notifID);
        $notif->sendEmail(array(':link' => Email::getLink($signupToken)), Event::USER_CREATE);

        $eventObjectID = $studentID;
        return null;
    }

    public static function applyToken($params, $user, &$eventObjectID) {
        $time = time();
        $tokenAction = ResetToken::applyToken($params['token']);
        if ($tokenAction === null) {
            throw new ActionError('Invalid token');
        }
        $user = $tokenAction->getCreator();
        if ($user !== null) {
            $eventObjectID = $user->getID();
            $result = array();
            switch ($tokenAction->getAction()) {
            case 'signup':
                // confirm the user's email address
                $user->confirmEmail();
                $result['alert'] = array('class' => 'success', 'title' => 'Success',
                    'message' => 'Your email address has been confirmed. Continue by logging in');
                break;

            case 'reset':
                // reset the user's password, ask for new password on index.php alternate form
                $user->confirmEmail();
                // create a token for index.php 'setpass' form:
                $newToken = ResetToken::generateToken('resetCallback', $user->getID(), $time);
                // create a token to allow users to reset later:
                ResetToken::generateToken('reset', $user->getID(), $time);
                // encode the resetCallback token
                $result['resetCallback'] = ResetToken::encodeToken($newToken);
                $result['userName'] = $user->getName();
                $result['alert'] = array('class' => 'warning', 'title' => 'Notice',
                    'message' => 'You must set a new password to continue');
                break;

            case 'resetCallback':
                // result of the created reset form must be handled with a token,
                // otherwise the server cannot verify that the reset was done by the same user
                // in the form created in applyToken "reset" above
                if (!isset($params['password']) || empty($params['password'])) {
                    throw new ActionError('Missing parameter (password)');
                }

                $user->changePassword($params['password']);
                $result['alert'] = array('class' => 'success', 'title' => 'Success',
                    'message' => 'Your password has been set. Continue by logging in');
                break;

            case 'resend':
                // call into the Notifications object to create a new email
                // identical to the last with a different :link token
                // store callback token
                $cbToken = $tokenAction->getCallbackToken();
                $cbNotifID = $tokenAction->getCallbackNotifID();
                $cbNotif = Notification::getNotificationByID($cbNotifID);
                if ($cbToken === null || $cbNotif === null) {
                    throw new ActionError('Malformed token data');
                }

                $newToken = ResetToken::generateToken('resendCallback', $user->getID(), $time, $cbToken, $cbNotifID);
                $cbNotif->sendEmail(array(':link' => Email::getLink($newToken)), Event::USER_APPLY_TOKEN);
                $result['alert'] = array('class' => 'warning', 'title' => 'Success',
                    'message' => 'The email has been resent to the associated address');
                break;

            case 'resendCallback':
                // when a succesful resent email callback happens, act on referenced token
                $cbToken = $tokenAction->getCallbackToken();
                if ($cbToken === null) {
                    throw new ActionError('Malformed token data');
                }

                $params['token'] = ResetToken::encodeToken($cbToken);
                $result2 = Action::applyToken($params, $user, $eventObjectID);
                $result['alert'] = $result2['alert'];
                break;
            }

            return $result;
        } else {
            throw new ActionError('User not found');
        }
    }

    public static function passRecov($params, $user, &$eventObjectID) {
        $email = $params['email'];
        $user = User::getUserByEmail($email);
        if ($user === null) {
            // it will appear in the event log as (email: $email) did this,
            // pointing to NULL, so we know what was typed in
            $eventObjectID = $email;
            return null;
        }

        $time = time();
        // TODO: use constants or something for the below text
        $notifID = Notification::insertNotification($user->getID(), true, false,
            'Set Your Password', null,
            "A password reset was requested for your TA management system account. Click the following link to set a password:\r\n\r\n:link\r\n\r\nLogging in with your old password will cancel this request.",
            $time);
        $resetToken = ResetToken::generateToken('reset', $user->getID(), $time, null, $notifID);

        $notif = Notification::getNotificationByID($notifID);
        $notif->sendEmail(array(':link' => Email::getLink($resetToken)), Event::USER_RESET_PASS);

        $eventObjectID = $user->getID();
        return null;
    }

    public static function findPositions($params, $student, &$eventObjectID) {
        $q = isset($params['q']) ? $params['q'] : '';
        $termID = $params['termID'];
        $typeID = $params['typeID'];
        $getTotal = isset($params['pgGetTotal']) && $params['pgGetTotal'] !== 'false' && !empty($params['pgGetTotal']);
        $pg = array(
            'index' => $params['pgIndex'],
            'length' => $params['pgLength'],
            'getTotal' => $getTotal);

        $positions_page = Position::findPositions($q, $termID, $typeID, $pg);
        // unlike other ExecuteGetPage() calls, we are retrieving the array version of 'objects' now
        // so that we can add the 'disableApplyText' key-value pair, specifically for student searching for positions
        $assoc_positions = array();
        foreach ($positions_page['objects'] as $position) {
            $application = $position->getLatestApplication($student);
            $appStatus = null;
            if ($application !== null) {
                $appStatus = $application->getStatus();
            }
            $pos = $position->toArray();
            switch ($appStatus) {
            case null:
            case CANCELLED:
                $pos['disableApplyText'] = '';
                break;
            case PENDING:
            case REJECTED:
                $pos['disableApplyText'] = 'Applied';
                break;
            case APPROVED:
            case WITHDRAWN:
                $pos['disableApplyText'] = 'Approved';
                break;
            }
            $assoc_positions[] = $pos;
        }
        $positions_page['objects'] = $assoc_positions;
        return $positions_page;
    }

    public static function apply($params, $student, &$eventObjectID) {
        $positionID = $params['positionID'];
        $comp = $params['compensation'];
        $qual = $params['qualifications'];
        $position = Position::getPositionByID($positionID);
        if ($position === null) {
            throw new ActionError('Position not found');
        }
        $posTerm = null;
        if (($section = $position->getSection()) !== null) {
            $posTerm = $section->getCourseTerm();
        }
        if ($student->getPendingApplicationCount($posTerm) >= 3) {
            throw new ActionError('You have already applied to three positions this term');
        }
        $application = $position->getLatestApplication($student);
        $appStatus = null;
        if ($application !== null) {
            $appStatus = $application->getStatus();
        }
        switch ($appStatus) {
        case null:
        case CANCELLED:
            // counts as not applied
            break;
        case PENDING:
        case REJECTED:
        case APPROVED:
        case WITHDRAWN:
            throw new ActionError('You have already applied for this position');
        }
        $appID = $position->apply($student, $comp, $qual);
        $eventObjectID = $appID;
        return null;
    }

    public static function withdraw($params, $student, &$eventObjectID) {
        $positionID = $params['positionID'];
        $position = Position::getPositionByID($positionID);
        if ($position === null) {
            throw new ActionError('Position not found');
        }
        $application = $position->getLatestApplication($student);
        $appStatus = null;
        if ($application !== null) {
            $appStatus = $application->getStatus();
        }
        switch ($appStatus) {
        case null:
        case CANCELLED:
            throw new ActionError('You have not applied for that position');
            break;
        case PENDING:
        case REJECTED:
            // change to CANCELLED
            $application->setApplicationStatus(CANCELLED);
            break;
        case APPROVED:
        case WITHDRAWN:
            // change to WITHDRAWN
            $application->setApplicationStatus(WITHDRAWN);
            break;
        }
        $eventObjectID = $application->getID();
        return null;
    }

    public static function updateProfile($params, $user, &$eventObjectID) {
        //Incase staff is updating a user's profile.
        if (isset($params['userID'])) {
            $userToUpdate = User::getUserByID($params['userID']);
        } else {
            $userToUpdate = $user;
        }
        if ($userToUpdate === null) {
            throw new ActionError('User not found');
        }
        if ($user->getObjectType() !== STAFF &&
            $user->getObjectType() !== ADMIN &&
            $user->getID() !== $userToUpdate->getID()) {
            throw new ActionError('Permission denied');
        }
        if(!isset($params['accStatus'])) {
            if($params['accStats'] == 'disable') {
                $userToUpdate->disableAccount();
            }
            if($params['accStatus'] == 'enable') {
                $userToUpdate->enableAccount();
            }
        }
        switch ($userToUpdate->getObjectType()) {
        case STUDENT:
            if (!isset($params['mobilePhone']) || !isset($params['classYear']) ||
                !isset($params['major']) || !isset($params['universityID']) || !isset($params['aboutMe'])) {
                throw new ActionError('Missing parameter');
            }
            // filter non-digits
            $mobilePhone = preg_replace('/([^\d]+)/', '', $params['mobilePhone']);
            $userToUpdate->updateProfile(
                $params['firstName'], $params['lastName'],
                $mobilePhone, $params['classYear'],
                $params['major'], $params['gpa'],
                $params['universityID'], $params['aboutMe']);
            break;
        case PROFESSOR:
        case STAFF:
            // filter non-digits
            if (empty($params['officePhone'])) {
                $officePhone = null;
            } else {
                $officePhone = preg_replace('/([^\d]+)/', '', $params['officePhone']);
            }
            if (empty($params['building']) || empty($params['room'])) {
                $officeBuilding = null;
                $officeRoom = null;
            } else {
                $officeBuilding = strtoupper($params['building']);
                $officeRoom = strtoupper($params['room']);
            }
            $placeID = Place::getOrCreatePlace($officeBuilding, $officeRoom);
            $userToUpdate->updateProfile(
                $params['firstName'], $params['lastName'],
                $officePhone, $placeID);
            break;
        case ADMIN:
            $userToUpdate->updateProfile(
                $params['firstName'], $params['lastName']);
            break;
        }
        $eventObjectID = $user->getID();
        // re-retrieve user object since ->updateProfile doesn't update the fields
        return User::getUserByID($userToUpdate->getID());
    }

    public static function changeUserPassword($params, $user, &$eventObjectID) {
        $oldPassword = $params['oldPassword'];
        $newPassword = $params['newPassword'];
        $confirmPassword = $params['confirmPassword'];
        if (!password_verify($oldPassword, $user->getPassword())) {
            throw new ActionError('Incorrect password');
        }
        $user->changePassword($newPassword);
        $eventObjectID = $user->getID();
        return null;
    }

    public static function fetchBuildings($params, $user, &$eventObjectID) {
        return Place::getAllBuildings();
    }

    public static function fetchRooms($params, $user, &$eventObjectID) {
        $places = Place::getPlacesByBuilding($params['building']);
        return array_map(function ($place) {
            return $place->getRoom();
        }, $places);
    }

    public static function fetchUser($params, $sessionUser, &$eventObjectID) {
        $user = User::getUserByID($params['userID']);
        if (($user === null || $user->getID() !== $sessionUser->getID()) &&
            $sessionUser->getObjectType() === STUDENT) {
            throw new ActionError('Permission denied');
        }
        if ($user === null) {
            throw new ActionError('User not found');
        }
        return $user;
    }

    public static function fetchApplication($params, $user, &$eventObjectID) {
        $application = Application::getApplicationByID($params['appID']);
        if ($application === null) {
            throw new ActionError('Application not found');
        }
        return $application;
    }

    public static function fetchComments($params, $user, &$eventObjectID) {
        $student = User::getUserByID($params['userID'], STUDENT);
        if ($student === null) {
            throw new ActionError('Student not found');
        }
        $comments = $student->getAllComments();
        return $comments;
    }

    public static function setAppStatus($params, $user, &$eventObjectID) {
        $decision = $params['decision'];
        $application = Application::getApplicationByID($params['appID']);
        if ($application === null) {
            throw new ActionError('Application not found');
        }
        if ($user->getObjectType() == PROFESSOR) {
            $position = $application->getPosition();
            $section = $position->getSection();
            if (!$section->isTaughtBy($user)) {
                throw new ActionError('Permission denied');
            }
        }
        $application->setApplicationStatus($params['decision']);
        $eventObjectID = $application->getID();
        return null;
    }

    public static function createComment($params, $user, &$eventObjectID) {
        $student = User::getUserByID($params['studentID'], STUDENT);
        if ($student === null) {
            throw new ActionError('Student not found');
        }
        $commentID = $student->saveComment($params['comment'], $user, time());
        $eventObjectID = $commentID;
        return null;
    }

    public static function createUser($params, $user, &$eventObjectID) {
        $userType = intval($params['userType']);
        if ($user->getObjectType() === STAFF && $userType !== PROFESSOR) {
            throw new ActionError('Permission denied');
        }
        $email = $params['email'];
        $firstName = $params['firstName']; $lastName = $params['lastName'];
        switch ($userType) {
        case STUDENT:
            if (!isset($params['mobilePhone']) || !isset($params['classYear']) ||
                !isset($params['major']) || !isset($params['universityID']) || !isset($params['aboutMe'])) {
                throw new ActionError('Missing parameter');
            }
            // filter non-digits
            $mobilePhone = preg_replace('/([^\d]+)/', '', $params['mobilePhone']);
            $classYear = $params['classYear'];
            $major = $params['major']; $gpa = $params['gpa'];
            $universityID = $params['universityID']; $aboutMe = $params['aboutMe'];

            $userID = Student::registerStudent(
                $email, null, $firstName, $lastName,
                $mobilePhone, $classYear, $major, $gpa, $universityID, $aboutMe);
            break;
        case PROFESSOR:
        case STAFF:
            // filter non-digits
            if (empty($params['officePhone'])) {
                $officePhone = null;
            } else {
                $officePhone = preg_replace('/([^\d]+)/', '', $params['officePhone']);
            }
            if (empty($params['building']) || empty($params['room'])) {
                $officeBuilding = null;
                $officeRoom = null;
            } else {
                $officeBuilding = strtoupper($params['building']);
                $officeRoom = strtoupper($params['room']);
            }
            $place = Place::getOrCreatePlace($officeBuilding, $officeRoom);
            if ($userType === PROFESSOR) {
                $userID = Professor::registerProfessor(
                    $email, $firstName, $lastName,
                    $place, $officePhone);
            } else {
                $userID = Staff::registerStaff(
                    $email, $firstName, $lastName,
                    $place, $officePhone);
            }
            break;
        default:
        case ADMIN:
            throw new ActionError('User create not supported for that user type');
        }

        $time = time();
        // TODO: use constants or something for the below text
        $notifID = Notification::insertNotification($userID, true, false,
            'Confirm Your Email', null,
            "An administrator has created a new account on the TA Registration System (TARS) using your email address. Click the following link to confirm your email address and set your password:\r\n\r\n:link\r\n\r\nYou may then login using this account to confirm TA applicants. Thank you for using TARS.",
            $time);
        $signupToken = ResetToken::generateToken('reset', $userID, $time, null, $notifID);

        $notif = Notification::getNotificationByID($notifID);
        $notif->sendEmail(array(':link' => Email::getLink($signupToken)), Event::USER_CREATE);

        $eventObjectID = $userID;
        return null;
    }

    public static function findUsers($params, $user, &$eventObjectID) {
        if (is_array($params)) {
            $userType = intval($params['userType']);
            //The array $params should contain: email, firstName, lastName, pgIndex, pgLength, pgGetTotal
            $getTotal = isset($params['pgGetTotal']) && $params['pgGetTotal'] !== 'false' && !empty($params['pgGetTotal']);
            $pg = array('index' => $params['pgIndex'],
                        'length' => $params['pgLength'],
                        'order' => array('lastName','firstName'),
                       'getTotal' => $getTotal);
            // do not allow STAFF to get userTypes other than STUDENT, PROFESSOR
            if ($user->getObjectType() === STAFF) {
                $userType &= (STUDENT | PROFESSOR);
            }
            $usersFound = User::findUsers($params['email'],$params['firstName'], $params['lastName'], $pg, $userType, $params['classYear']);
            return $usersFound;
        }
        return $params;
    }

    public static function findEvents($params, $user, &$eventObjectID) {
        if (is_array($params)) {
            //The array $params should contain: email, firstName, lastName, pgIndex, pgLength, pgGetTotal
            $getTotal = isset($params['pgGetTotal']) && $params['pgGetTotal'] !== 'false' && !empty($params['pgGetTotal']);
            $pg = array('index' => $params['pgIndex'],
                        'length' => $params['pgLength'],
                        'order' => array('createTime'),
                        'getTotal' => $getTotal);
            $eventTypes = array(
                'crit' => ($params['sevCrit'] !== 'false' && !empty($params['sevCrit'])),
                'error' => ($params['sevError'] !== 'false' && !empty($params['sevError'])),
                'notice' => ($params['sevNotice'] !== 'false' && !empty($params['sevNotice'])),
                'info' => ($params['sevInfo'] !== 'false' && !empty($params['sevInfo'])),
                'debug' => ($params['sevDebug'] !== 'false' && !empty($params['sevDebug'])));
            $eventsFound = Event::findEvents($params['userFilter'], $eventTypes, $pg);
            return $eventsFound;
        }
        return $params;
    }

    public static function fetchTermApplications($params, $user, &$eventObjectID){
        if(is_array($params)){
            $getTotal = isset($params['pgGetTotal']) && $params['pgGetTotal'] !== 'false' && !empty($params['pgGetTotal']);
            $pg = array('index' => $params['pgIndex'],
                       'length' => $params['pgLength'],
                       'getTotal' => $getTotal);
            $term = Term::getTermByID($params['termID']);
            $applications = Application::findApplications(null, null, null, $term, $params['appStatus'], null, $pg);
            return $applications;
        }
        return $params;
    }

    /*
     * Called for the editTerm page in the staff account. Fetches section information used to populate the page.
     */
    public static function fetchSections($params, $user, &$eventObjectID) {
        if(is_array($params)) {
            //The array $params should contain: crn, course, type, status, pgIndex, pgLength, pgGetTotal
            $getTotal = isset($params['pgGetTotal']) && $params['pgGetTotal'] !== 'false' && !empty($params['pgGetTotal']);
            $pg = array('index' => $params['pgIndex'],
                       'length' => $params['pgLength'],
                       'getTotal' => $getTotal);
            $sectionsFound = Section::fetchSections($params['crn'], $params['course'], $params['type'], $params['status'], $pg);
            //THIS DOESN'T WORK JUST YET TT_TT
            $countBuffer = array();
            foreach($sectionsFound as $section) {
                $profs = $section->getAllProfessors();
                $labTACount = $section->getTotalPositionsByType($profs[0], 1);
                if(!$labTACount) {
                    $labTACount = 0;
                }
                $wsTACount = $section->getTotalPositionsByType($profs[0], 2);
                if(!$wsTACount) {
                    $wsTACount = 0;
                }
                $wsslCount = $section->getTotalPositionsByType($profs[0], 3);
                if(!$wsslCount) {
                    $wsslCount = 0;
                }
                $lecTACount = $section->getTotalPositionsByType($profs[0], 5);
                if(!$lecTACount) {
                    $lecTACount = 0;
                }
                $graderCount = $section->getTotalPositionsByType($profs[0], 4);
                if(!$graderCount) {
                    $graderCount = 0;
                }
                $countPack = array('labTACount' => $labTACount, 'wsTACount' => $wsTACount, 'wsslCount' => $wsslCount, 'lecTACount' => $lecTACount, 'graderCount' => $graderCount);
                array_push($countBuffer, $countPack);
            }
            $TACounts = array('TACounts' => countBuffer);
            $sectionsFound[] = $TACounts;
            //Loop through each section and for each section get the 5 TA counts, placed in a very specific
            //order in an array, then put each array into a TACounts array in the order of the sections
            //the put both the sections information and the TA counts into a wrapper array and return that.
            return $sectionsFound;
        }
        return $params;
    }

    // only available via running this script; not by Action::callAction
    public static function uploadTerm($params, $user, &$eventObjectID) {
        define('CUSTOM_UPLOAD_ERR_CANT_READ', 1001);
        $upload_error_message = function ($code) {
            switch ($code) {
            case UPLOAD_ERR_OK: return 'success';
            case UPLOAD_ERR_INI_SIZE: return 'file size exceeds server limit';
            case UPLOAD_ERR_FORM_SIZE: return 'file size exceeds soft limit';
            case UPLOAD_ERR_PARTIAL: return 'received incomplete file';
            case UPLOAD_ERR_NO_FILE: return 'did not receive file';
            case UPLOAD_ERR_NO_TMP_DIR: return 'missing temporary directory';
            case UPLOAD_ERR_CANT_WRITE: return 'cannot write temporary file';
            case UPLOAD_ERR_EXTENSION: return 'extension stopped upload';
            case CUSTOM_UPLOAD_ERR_CANT_READ: return 'cannot read temporary file';
            default: return 'unknown error';
            }
        };

        $upload = $_FILES['termFile'];
        switch ($upload['error']) {
        case UPLOAD_ERR_OK:
            $lines = @file($upload['tmp_name'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if ($lines) {
                $termID = Term::importTermFromCSV($params['termYear'], $params['termSemester'], $lines, $upload);
                break;
            } else {
                $upload['error'] = CUSTOM_UPLOAD_ERR_CANT_READ;
                // intentional fall-through to DEFAULT
            }
        default:
            $upload_err = $upload_error_message($upload['error']);
            throw new ActionError("Upload of file failed ($upload_err)");
        }
        $eventObjectID = $termID;
        return null;
    }

    const VALIDATE_NOTEMPTY = 1;
    const VALIDATE_EMAIL = 2;
    const VALIDATE_OTHERFIELD = 3;
    const VALIDATE_NUMERIC = 4;
    const VALIDATE_NUMSTR = 5;
    const VALIDATE_UPLOAD = 11;

    /**
     * This function is not an Action.
     * Validates the parameters in $input according to the $definitions array.
     *
     * $input: The input, such as from $_POST, or constructed to pass to callAction.
     *
     * $definitions: This is 'params' in the $action_map
     */
    private static function validateParameters($input, $definitions) {
        if (!is_assoc($definitions)) {
            // if params was just a list of keys,
            // assign them all default settings
            $definitions = array_fill_keys($definitions,
                array('type' => Action::VALIDATE_NOTEMPTY));
        }
        $invalids = array();
        foreach ($definitions as $param_key => $def) {
            if (array_key_exists($param_key, $input) && ($input[$param_key] !== '')) {
                $value = $input[$param_key];
                if (is_int($def)) {
                    $def = array('type' => $def);
                } elseif (!isset($def['type'])) {
                    $def['type'] = Action::VALIDATE_NOTEMPTY;
                }

                $invalid = false;
                switch ($def['type']) {
                case Action::VALIDATE_NOTEMPTY:
                    $invalid = ($value === '');
                    break;
                case Action::VALIDATE_EMAIL:
                    $invalid = filter_var($value, FILTER_VALIDATE_EMAIL) === false;
                    break;
                case Action::VALIDATE_OTHERFIELD:
                    if (isset($def['field'])) {
                        $other_key = $def['field'];
                        if (isset($input[$other_key])) {
                            $invalid = ($input[$other_key] !== $value);
                            break;
                        }
                    }
                    $invalid = true;
                    break;
                case Action::VALIDATE_NUMERIC:
                    if (isset($def['reject_signed']) && ($value[0] == '-' || $value[0] == '+')) {
                        $value = '';
                    }
                    if (isset($def['reject_decimal']) && strpos($value, '.') !== false) {
                        $value = '';
                    }
                    $invalid = filter_var($value, FILTER_VALIDATE_FLOAT, $def) === false;
                    break;
                case Action::VALIDATE_NUMSTR:
                    $value = preg_replace('/([^\d]+)/', '', $value);
                    if (isset($def['min_length']) && strlen($value) < $def['min_length']) {
                        $value = '';
                    }
                    if (isset($def['max_length']) && strlen($value) > $def['max_length']) {
                        $value = '';
                    }
                    $invalid = ($value === '');
                    break;
                }
                if ($invalid) {
                    $invalids[] = $param_key;
                }
            } elseif ($def ==  Action::VALIDATE_UPLOAD ||
                    (isset($def['type']) && $def['type'] == Action::VALIDATE_UPLOAD)) {
                // this right here is why forms with VALIDATE_UPLOAD field
                // cannot be called by Action::callAction(): it accesses $_FILES
                if (!isset($_FILES[$param_key])) {
                    $invalids[] = $param_key;
                }
            } elseif (!isset($def['optional']) || !$def['optional']) {
                $invalids[] = $param_key;
            }
        }
        return $invalids;
    }

    // $action_map: this is a map of action keys to action definition structures
    // The keys of this array must have corresponding class functions defined with the same name:
    //    It takes three arguments:
    //        $params: The $_POST array
    //        $user: which is currently logged in (null if nobody is).
    //        &$eventObjectID: this can be set by the function to an object ID to put in the event log for this Action
    //
    //    Throws: PDOException when the underlying database call fails
    //            ActionError if a custom error is generated, is passed to create a TarsException in callAction
    //            TarsException if a structured error is generated
    //
    //    The return value is outputted in the format above by following these rules:
    //        The object always contains "success" and "error" (iff success=false)
    //        If the value is NULL, the object contains no other properties.
    //            EXAMPLE USE CASES: signup, apply
    //        If the value is a boolean, the object contains "valid": bool value
    //            EXAMPLE USE CASES: emailAvailable
    //        If the value is an object, the object contains "object": object->toArray()
    //            The object MUST have a toArray() function.
    //            EXAMPLE USE CASES: fetchUser
    //        If the value is an array, the object contains "objects": array
    //            Assumes the array only contains objects
    //            The objects MUST have a toArray() function.
    //            EXAMPLE USE CASES: findApplications, findPositions, findUsers, findEvents, fetchBuildings, fetchRooms
    //        If the value is a string, an ERROR_ACTION is produced with the given string
    //            USE CASE: the given positionID doesn't exist or was not provided
    //
    // 'event' => (REQUIRED) is the Event constant that will be used on failure
    // 'params' => is an array of the parameters.
    //    If one is not provided correctly, an ERROR_ACTION is thrown
    //    Format:
    //        1. array of $key => $definition pairs, giving rules for the $definition
    //            The $definition array has the following elements:
    //            'type' => the Action::VALIDATE_* constant that describes what the $value MUST be
    //            VALIDATE_NOTEMPTY: Just checks that the $value is not empty-string
    //            VALIDATE_EMAIL: Checks that the $value is a valid email address
    //            VALIDATE_OTHERFIELD: Checks that the $value is equal to the $value of $input with key $def['field']
    //            VALIDATE_NUMERIC: Checks that the $value is a number.
    //                It must be >= $def['min_range'] (optional)
    //                It must be <= $def['max_range'] (optional)
    //                It cannot start with a '+' or '-' if $def['reject_signed'] is set
    //                It cannot contain a '.' if $def['reject_decimal'] is set
    //            VALIDATE_NUMSTR: Checks that the $value is a string of digits (phone, uni ID)
    //                It must be shorter or equal to $def['min_length'] (optional)
    //                It must be longer or equal to $def['max_length'] (optional)
    //                It will validate while containing non-digits if $def['accept_nondigits'] is set (they will be removed before length checks)
    //            VALIDATE_UPLOAD: Checks that the $value is a file.
    //                This special validation checks $_FILES regardless of the contents of $input,
    //                making it not work when you call Action::callAction() unless done from an upload
    //            'optional' => if set, then either $value is not empty and validates using the above rules, or it is empty or not present in $input. If it is not empty and invalid, validation will fail.
    //        2. $definition may also be the 'type' constant alone, in which case it'll be interpreted as array('type' => $validate_constant).
    //        3. The entire array can just be an array of strings, representing all $input[$key]s as 'type' VALIDATE_NOTEMPTY with no options. The array CANNOT be associative in this case (string keys).
    // 'eventLog' => is either 'debug' (DEFAULT): the Event will not be logged on success, or 'always': the event will be logged on success
    // 'eventDescr' => The description to pass to the event log. One "%s" is allowed to represent the user who did the action.
    // 'eventDescrArg' => The location to get the user who did the action argument:
    //    'session' (DEFAULT): Uses the user who is logged in
    //    'refparam': Uses the value of $eventObjectID as the user, if it is a User object
    // 'noSession' => is a boolean. TRUE allows the action to be performed when not logged in
    // 'userType' => specifies the user type to pass to LoginSession::sessionContinue()
    //    (i.e. what user type the currently logged in user MUST be), don't specify this
    //    to accept any logged in user.
    //    (WARNING: if any logged in user is accepted, 'fn' will receive the logged in
    //    user's user object, so make sure 'fn' code doesn't request STUDENT-specific
    //    functions, for example; also 'fn' will receive null if noSession is true and
    //    nobody is logged in)
    private static $action_map = array(
        // Action:           login
        // Session required: none
        // Parameters:
        //     email: email field value
        //     password: password field value
        // Returns:
        //     object: Your user object on successful login
        //     success and error: Action status
        'login' => array('event' => Event::SESSION_LOGIN,
            'eventLog' => 'always', 'eventDescr' => '%s logged in.',
            'eventDescrArg' => 'refparam', 'noSession' => true,
            'params' => array(
                'email' => array('type' => Action::VALIDATE_EMAIL),
                'password' => array('type' => Action::VALIDATE_NOTEMPTY, 'optional' => true))),
        // Action:           logout
        // Session required: none (handled by code)
        // Parameters:       none
        // Returns:
        //     success and error: Action status
        // Note:             This Action does not log failure due to no session
        'logout' => array('event' => Event::SESSION_LOGOUT, 'noSession' => true,
            'eventLog' => 'always', 'eventDescr' => '%s logged out.',
            'eventDescrArg' => 'refparam'),
        // Action:           emailAvailable
        // Session required: none
        // Parameters:
        //     email: the email to check
        // Returns:
        //     valid: Whether the email can be used
        //     success and error: Action status
        'emailAvailable' => array('event' => Event::USER_IS_EMAIL_AVAIL,
            'eventDescr' => 'A user checked for the availability of an email address.',
            'noSession' => true, 'params' => array('email')),
        // Action:           sendBugReport
        // Session required: none
        // Parameters:
        //     bugrepemail:  User email
        //     bugreppass:   User password
        //     bugreport:    Report text
        // Returns:
        //     success and error: Action status
        'sendBugReport' => array('event' => Event::USER_REPORT_BUG,
            'eventLog' => 'always', 'eventDescr' => '%s sent a bug report.',
            'eventDescrArg' => 'refparam', 'noSession' => true,
            'params' => array(
                'bugrepemail' => array('type' => Action::VALIDATE_EMAIL),
                'bugreppass' => array('type' => Action::VALIDATE_NOTEMPTY),
                'bugreport' => array('type' => Action::VALIDATE_NOTEMPTY))),
        // Action:           signup
        // Session required: none
        // Parameters:       all user fields
        //     email, emailConfirm, password, passwordConfirm,
        //     firstName, lastName, mobilePhone, classYear, major,
        //     gpa, universityID, aboutMe
        // Returns:
        //     success and error: Action status
        'signup' => array('event' => Event::USER_CREATE, 'noSession' => true,
            'eventLog' => 'always', 'eventDescr' => '%s created a STUDENT account.',
            'eventDescrArg' => 'refparam',
            'params' => array(
                'email' => array('type' => Action::VALIDATE_EMAIL),
                'emailConfirm' => array('type' => Action::VALIDATE_OTHERFIELD,
                    'field' => 'email'),
                'password' => array('type' => Action::VALIDATE_NOTEMPTY),
                'passwordConfirm' => array('type' => Action::VALIDATE_OTHERFIELD,
                    'field' => 'password'),
                'firstName' => array('type' => Action::VALIDATE_NOTEMPTY),
                'lastName' => array('type' => Action::VALIDATE_NOTEMPTY),
                'mobilePhone' => array('type' => Action::VALIDATE_NUMSTR,
                    'min_length' => 10, 'max_length' => 10),
                'classYear' => array('type' => Action::VALIDATE_NUMSTR,
                    'min_length' => 4, 'max_length' => 4),
                'major' => array('type' => Action::VALIDATE_NOTEMPTY),
                'gpa' => array('type' => Action::VALIDATE_NUMERIC,
                    'min_range' => 0, 'max_range' => 4),
                'universityID' => array('type' => Action::VALIDATE_NUMSTR,
                    'min_length' => 8, 'max_length' => 8),
                'aboutMe' => array('type' => Action::VALIDATE_NOTEMPTY))),
        // Action:           applyToken
        // Session required: none
        // Parameters:
        //     token:        The token used to identify the request
        // Returns:
        //     object: An alert with a title, message, and class
        //     success and error: Action status
        'applyToken' => array('event' => Event::USER_APPLY_TOKEN,
            'noSession' => true, 'eventLog' => 'always',
            'eventDescr' => '%s applied a token to confirm email/reset password.',
            'eventDescrArg' => 'refparam', 'params' => array('token')),
        // Action:           passRecov
        // Session required: none
        // Parameters:
        //     email:        The email to send a reset token for
        // Returns:
        //     object: An alert with a title, message, and class (will succeed even if the user doesn't exist, just no email will be sent)
        //     success and error: Action status
        'passRecov' => array('event' => Event::USER_RESET_PASS,
            'noSession' => true, 'eventLog' => 'always',
            'eventDescr' => '%s requested a password reset.',
            'eventDescrArg' => 'refparam', 'params' => array('email')),
        // Action:           findPositions
        // Session required: STUDENT
        // Parameters:
        //     q: Plain text query string
        //     termID: Term ID of search
        //     typeID: Position Type ID
        //     pgIndex: Page number (if not specified or <= 0, assume 1)
        //     pgLength: Page length
        //     pgGetTotal: Boolean specifying whether to calculate the total number of pages for the query
        // Returns:
        //     objects: Returned positions
        //     pg.index: Actual page index
        //     pg.total: Total number of pages (if requested)
        //     success and error: Action status
        'findPositions' => array('event' => Event::USER_GET_VIEW, 'userType' => STUDENT,
            'eventDescr' => '%s searched for positions.',
            'params' => array(
                'q' => array('type'=>Action::VALIDATE_NOTEMPTY,'optional'=>true),
                'termID' => array('type'=>Action::VALIDATE_NUMERIC),
                'typeID' => array('type'=>Action::VALIDATE_NUMERIC),
                'pgIndex' => array('type'=>Action::VALIDATE_NUMERIC),
                'pgLength' => array('type'=>Action::VALIDATE_NUMERIC),
                'pgGetTotal' => array('type'=>Action::VALIDATE_NOTEMPTY,'optional'=>true))),
        // Action:           apply
        // Session required: STUDENT
        // Parameters:
        //     positionID:   Position ID
        //     compensation: pay|credit
        //     qualifications: text field
        // Returns:
        //     success and error: Action status
        'apply' => array('event' => Event::STUDENT_APPLY, 'userType' => STUDENT,
            'eventLog' => 'always', 'eventDescr' => '%s created an application.',
            'params' => array('positionID','compensation','qualifications')),
        // Action:           withdraw
        // Session required: STUDENT
        // Parameters:
        //     positionID:   Position ID
        //     TODO support withdraw type and reason
        //     TODO make withdraw parameter the Application ID
        // Returns:
        //     success and error: Action status
        'withdraw' => array('event' => Event::STUDENT_WITHDRAW, 'userType' => STUDENT,
            'eventLog' => 'always', 'eventDescr' => '%s withdrew an application.',
            'params' => array('positionID')),
        // Action:           updateProfile
        // Session required: required
        // Parameters:       all user fields (varies with session user type)
        //     userID:       If provided, we are updating this profile, not self
        //     firstName, lastName, mobilePhone, classYear, major,
        //     gpa, universityID, aboutMe, officePhone, officeBuilding, officeRoom
        // Returns:
        //     object:       The new user object
        //     success and error: Action status
        'updateProfile' => array('event' => Event::USER_SET_PROFILE,
            'eventLog' => 'always', 'eventDescr' => 'Profile of %s was updated.',
            'eventDescrArg' => 'refparam',
            'params' => array(
                'userID' => array('type' => Action::VALIDATE_NUMERIC,
                    'optional' => true),
                'firstName' => array('type' => Action::VALIDATE_NOTEMPTY),
                'lastName' => array('type' => Action::VALIDATE_NOTEMPTY),
                'mobilePhone' => array('type' => Action::VALIDATE_NUMSTR,
                    'optional' => true, 'min_length' => 10, 'max_length' => 10),
                'classYear' => array('type' => Action::VALIDATE_NUMSTR,
                    'optional' => true, 'min_length' => 4, 'max_length' => 4),
                'major' => array('type' => Action::VALIDATE_NOTEMPTY,
                    'optional' => true),
                'gpa' => array('type' => Action::VALIDATE_NUMERIC,
                    'optional' => true, 'min_range' => 0, 'max_range' => 4),
                'universityID' => array('type' => Action::VALIDATE_NUMSTR,
                    'optional' => true, 'min_length' => 8, 'max_length' => 8),
                'aboutMe' => array('type' => Action::VALIDATE_NOTEMPTY,
                    'optional' => true),
                'officePhone' => array('type' => Action::VALIDATE_NUMSTR,
                    'optional' => true, 'min_length' => 10, 'max_length' => 10),
                'building' => array('type' => Action::VALIDATE_NOTEMPTY,
                    'optional' => true),
                'room' => array('type' => Action::VALIDATE_NOTEMPTY,
                    'optional' => true),
                'accStatus' => array('type' => Action::VALIDATE_NOTEMPTY, 'optional' => true))),
        // Action:           changeUserPassword
        // Session required: required
        // Parameters:
        //     oldPassword, newPassword, confirmPassword
        // Returns:
        //     success and error: Action status
        'changeUserPassword' => array('event' => Event::USER_SET_PASS,
            'eventLog' => 'always', 'eventDescr' => '%s changed their password.',
            'params' => array(
                    'oldPassword' => array('type' => Action::VALIDATE_NOTEMPTY),
                    'newPassword' => array('type' => Action::VALIDATE_NOTEMPTY),
                    'confirmPassword' => array('type' => Action::VALIDATE_OTHERFIELD,
                        'field' => 'newPassword'))),
        // Action:           fetchBuildings
        // Session required: logged in
        // Parameters:       none
        // Returns:
        //     objects: array of building names
        //     success and error: Action status
        'fetchBuildings' => array('event' => Event::USER_GET_OBJECT,
            'eventDescr' => '%s retrieved buildings list.'),
        // Action:           fetchRooms
        // Session required: logged in
        // Parameters:
        //     building: name of building to look in
        // Returns:
        //     objects: array of room numbers (or names) in this building
        //     success and error: Action status
        'fetchRooms' => array('event' => Event::USER_GET_OBJECT,
            'eventDescr' => '%s retrieved building room list.',
            'params' => array('building')),
        // Action:           fetchUser
        // Session required: logged in
        // Parameters:
        //     userID: The user's ID
        //     userType: The expected user type (pass -1 for any)
        // Returns:
        //     object: The user's data
        //     success and error: Action status
        'fetchUser' => array('event' => Event::USER_GET_OBJECT,
            'eventDescr' => '%s retrieved user object.',
            'params' => array('userID', 'userType')),
        // Action:           fetchApplication
        // Session required: not STUDENT
        // Parameters:
        //     appID: The application ID
        // Returns:
        //     object: The application data
        //     success and error: Action status
        'fetchApplication' => array('event' => Event::USER_GET_OBJECT, 'userType' => USERMASK_NONSTUDENT,
            'eventDescr' => '%s retrieved application object.',
            'params' => array('appID')),
        // Action:           fetchComments
        // Session required: not STUDENT
        // Parameters:
        //     userID: The user ID
        // Returns:
        //     objects: The comments
        //     success and error: Action status
        'fetchComments' => array('event' => Event::USER_GET_VIEW, 'userType' => USERMASK_NONSTUDENT,
            'eventDescr' => '%s retrieved comments view.',
            'params' => array('userID')),
        // Action:           setAppStatus
        // Session required: not STUDENT
        // Parameters:
        //     appID: The app ID
        //     decision: The new app status
        // Returns:
        //     success and error: Action status
        'setAppStatus' => array('event' => Event::NONSTUDENT_SET_APP, 'userType' => USERMASK_NONSTUDENT,
            'eventLog' => 'always', 'eventDescr' => '%s updated the status of an application.',
            'params' => array('appID', 'decision')),
        // Action:           createComment
        // Session required: not STUDENT
        // Parameters:
        //     studentID: referred-to student
        //     comment: comment text
        // Returns:
        //     success and error: Action status
        'createComment' => array('event' => Event::NONSTUDENT_COMMENT, 'userType' => USERMASK_NONSTUDENT,
            'eventLog' => 'always', 'eventDescr' => '%s created a comment.',
            'params' => array('studentID', 'comment')),
        // Action:           createUser
        // Session required: not STUDENT
        // Parameters:
        //     type: the given user type is checked or permission denied is thrown
        //     firstName, lastName, email, emailConfirm, officePhone, building, room
        // Returns:
        //     success and error: Action status
        'createUser' => array('event' => Event::SU_CREATE_USER, 'userType' => USERMASK_STAFF,
            'eventLog' => 'always', 'eventDescr' => '%s created a new user.',
            'params' => array(
                'firstName' => array('type' => Action::VALIDATE_NOTEMPTY),
                'lastName' => array('type' => Action::VALIDATE_NOTEMPTY),
                'email' => array('type' => Action::VALIDATE_EMAIL),
                'userType' => array('type' => Action::VALIDATE_NUMERIC),
                'officePhone' => array('type' => Action::VALIDATE_NUMSTR,
                    'optional' => true, 'min_length' => 10, 'max_length' => 10),
                'building' => array('type' => Action::VALIDATE_NOTEMPTY,
                    'optional' => true),
                'room' => array('type' => Action::VALIDATE_NOTEMPTY,
                    'optional' => true))),
        // Action:           findUsers
        // Session required: STAFF, ADMIN
        // Parameters:
        //     email: email field
        //     firstName: first name field
        //     lastName: last name field
        //     userTypes: user type mask
        // Returns:
        //     objects: The users in this set
        //     success and error: Action status
        'findUsers' => array('event' => Event::USER_GET_VIEW, 'userType' => USERMASK_STAFF,
            'eventDescr' => '%s retrieved positions view.',
            'params' => array(
                'email' => array('optional' => true),
                'firstName' => array('optional' => true),
                'lastName' => array('optional' => true),
                'userType' => array('optional' => true),
                'classYear' => array('optional' => true),
                'pgIndex' => array('type' => Action::VALIDATE_NUMERIC),
                'pgLength' => array('type' => Action::VALIDATE_NUMERIC),
                'pgGetTotal' => array('type' => Action::VALIDATE_NOTEMPTY, 'optional' => true))),
        // Action:           findEvents
        // Session required: ADMIN
        // Parameters:
        //     userFilter: filter text
        // Returns:
        //     objects: The users in this set
        //     success and error: Action status
        'findEvents' => array('event' => Event::USER_GET_VIEW, 'userType' => ADMIN,
            'eventDescr' => '%s retrieved events view.',
            'params' => array(
                'userFilter' => array('optional' => true),
                'pgIndex' => array('type' => Action::VALIDATE_NUMERIC),
                'pgLength' => array('type' => Action::VALIDATE_NUMERIC),
                'pgGetTotal' => array('type' => Action::VALIDATE_NOTEMPTY, 'optional' => true))),
        // Action: fetchTermApplications
        // Session required: STAFF, ADMIN
        // Parameters:
        //        appStatus: application status
        //         termID: id of term
        // Returns:
        //         application objects
        'fetchTermApplications' => array('event' => Event::USER_GET_VIEW, 'userType' => USERMASK_STAFF,
            'eventDescr' => '%s retrieved an applications view.',
            'params' => array(
                'appStatus' => array('type' => Action::VALIDATE_NUMERIC, 'optional' => true),
                'termID' => array('type' => Action::VALIDATE_NUMERIC),
                'pgIndex' => array('type' => Action::VALIDATE_NUMERIC),
                'pgLength' => array('type' => Action::VALIDATE_NUMERIC),
                'pgGetTotal' => array('type' => Action::VALIDATE_NOTEMPTY, 'optional' => true))),
        // Action:           uploadTerm
        // Session required: STAFF, ADMIN
        // Parameters:
        //     termYear: year field
        //     termSemester: semester field
        //     termFile: the file data
        // Returns:
        //     success and error: Action status
        // Only available via calling this script; not by Action::callAction()
        'uploadTerm' => array('event' => Event::STAFF_TERM_IMPORT, 'userType' => USERMASK_STAFF,
            'eventLog' => 'always', 'eventDescr' => '%s uploaded a term.',
            'params' => array(
                'termYear' => array('type' => Action::VALIDATE_NUMSTR,
                    'min_length' => 4, 'max_length' => 4),
                'termSemester' => Action::VALIDATE_NOTEMPTY,
                'termFile' => Action::VALIDATE_UPLOAD)),
        // Action:            fetchSections
        // Session required: STAFF
        // Parameters:
        //        crn: CRN field
        //        course: course field
        //        type: type field
        //         status: status radio buttons
        // Returns:
        //        success and error: Action status
        'fetchSections' => array(
            'event' => Event::USER_GET_OBJECT,
            'userType' => USERMASK_STAFF,
            'eventDescr' => '%s retrieved sections view.',
            'params' => array(
                'crn' => array('optional' => true),
                'course' => array('optional' => true),
                'type' => array('optional' => true),
                'status' => array('optional' => true),
                'pgIndex' => array('type' => Action::VALIDATE_NUMERIC),
                'pgLength' => array('type' => Action::VALIDATE_NUMERIC),
                'pgGetTotal' => array('type' => Action::VALIDATE_NOTEMPTY, 'optional' => true)))
        );
    // end Action::$action_map

    public static function callAction($actionName, $input = array()) {
        // Check if action is known
        if (isset(Action::$action_map[$actionName])) {
            // get the definition structure for this action from the above structure
            $action_def = Action::$action_map[$actionName];
            // get the event (REQUIRED)
            $action_event = $action_def['event'];
            // get the session usertype
            $action_utype = isset($action_def['userType']) ? $action_def['userType'] : -1;
            // get the noSession option
            $action_nosess = isset($action_def['noSession']) ? $action_def['noSession'] : false;
            // get the parameters
            $action_params = isset($action_def['params']) ? $action_def['params'] : array();
            // get whether errors should be "Invalid form field" or "Invalid parameter"
            $action_evlog = isset($action_def['eventLog']) ? $action_def['eventLog'] : 'debug';
            $action_evdesc = isset($action_def['eventDescr']) ? $action_def['eventDescr'] : '%s did something.';
            $action_evdesc_arg = isset($action_def['eventDescrArg']) ? $action_def['eventDescrArg'] : 'session';

            // BEGIN TRANSACTION
            Database::beginTransaction();

            // SESSION
            $user = null;
            $error = null;
            if (!$action_nosess) {
                // if a session is required, start it
                $error = null;
                try {
                    $user = LoginSession::sessionContinue($action_utype);
                } catch (TarsException $ex) {
                    $error = $ex;
                }
            }

            // RUN ACTION
            try {
                // if nothing went wrong (session), run the function and get results
                if ($error === null) {

                    // VALIDATE PARAMETERS
                    $invalids = Action::validateParameters($input, $action_params);

                    if (count($invalids) > 0) {
                        $s = (count($invalids) == 1) ? '' : 's';
                        $i = implode(', ', $invalids);
                        throw new ActionError("Invalid input in field$s. Please fix these fields and try again ($i)");
                    } else {
                        // successful
                        $event_object = null;
                        $result_obj = Action::$actionName($input, $user, $event_object);
                    }

                    // GET RESULT
                    if ($result_obj !== null) {
                        if (is_bool($result_obj)) {
                            // boolean result
                            $output['valid'] = $result_obj;
                        } elseif (is_object($result_obj)) {
                            // object result
                            $output['object'] = $result_obj->toArray();
                        } elseif (is_array($result_obj)) {
                            // paginated result: move 'pg' and 'objects' up one level
                            if (isset($result_obj['pg'])) {
                                $output['pg'] = $result_obj['pg'];
                            }
                            if (isset($result_obj['objects'])) {
                                $result_obj = $result_obj['objects'];
                            }
                            // object-array result
                            if (is_assoc($result_obj)) {
                                // object result (as array)
                                $output['object'] = $result_obj;
                            } else {
                                // array of objects result
                                $output['objects'] =
                                    array_map(function ($obj) {
                                        if (is_object($obj)) {
                                            // as object
                                            return $obj->toArray();
                                        } else {
                                            return $obj;
                                        }
                                    }, $result_obj);
                            }
                        } else {
                            // int, float, or string
                            $output['value'] = $result_obj;
                        }
                    }
                }
            } catch (ActionError $ex) {
                // ERROR_ACTION with replacement message
                Database::rollbackTransaction();
                $error = new TarsException(Event::ERROR_ACTION, $action_event, $ex->getMessage());
                $extra = $ex->getExtraParameters();
                // extra parameters used to pass back a Reset Token (for resetting your password,
                // or resending a email verification)
                if (isset($extra['token'])) {
                    $userID = $extra['userID'];
                    $token = $extra['token'];
                    // if notifID is provided, this is a resend email verification request.
                    // create the token to resend it here.
                    if (isset($extra['notifID'])) {
                        $notifID = $extra['notifID'];

                        try {
                            $newToken = ResetToken::generateToken('resend',
                                $userID, time(), $token, $notifID);
                            $enc_token = ResetToken::encodeToken($newToken);
                            $output['token'] = $enc_token;
                            $output['tokenAction'] = 'resend the email';
                        } catch (PDOException $pdoex) {
                            // SERVER_DBERROR
                            $error = new TarsException(Event::SERVER_DBERROR, $action_event, $pdoex);
                        }
                    // otherwise, this is a password reset request.
                    // send back the token for resetting password
                    } else {
                        $enc_token = ResetToken::encodeToken($token);
                        $output['token'] = $enc_token;
                        $output['tokenAction'] = 'set a new password';
                    }
                }
            } catch (PDOException $ex) {
                // SERVER_DBERROR
                Database::rollbackTransaction();
                $error = new TarsException(Event::SERVER_DBERROR, $action_event, $ex);
            } catch (TarsException $ex) {
                // non-ERROR_ACTION thrown by Actions
                // Transaction rolled back by TarsException
                $error = $ex;
            }

            // LOG EVENT SUCCESS
            try {
                if ($error === null) {
                    $source_user = Action::getUserByType($action_evdesc_arg, $event_object, $user);
                    if ($source_user !== null && $source_user instanceof User) {
                        $descrarg = $source_user->getName();
                    } elseif (is_string($source_user)) {
                        $descrarg = "(email: $source_user)";
                        $source_user = null;
                    } else {
                        $descrarg = '(anonymous)';
                        $source_user = null; // do not give the database non-Users in creator column
                    }
                    $descr = sprintf($action_evdesc, $descrarg);
                    // insert event with this EventID, description, object,
                    // current time, current IP (default parameter), and current user object
                    Event::insertEvent($action_event, $descr, $event_object, time(), null, $source_user);
                }

            } catch (PDOException $ex) {
                Database::rollbackTransaction();
                $error = new TarsException(Event::SERVER_DBERROR, $action_event, $ex);
            } catch (TarsException $ex) {
                // Transaction rolled back by TarsException
                $error = $ex;
            }

            // COMMIT TRANSACTION
            if ($error === null) {
                Database::commitTransaction();
            }
        } else {
            // unknown action
            $error = new TarsException(Event::SERVER_EXCEPTION,
                Event::ERROR_ACTION, 'Unknown action');
        }

        // set the success and error properties here
        if ($error !== null) {
            $output['success'] = false;
            $output['error'] = $error->toArray();
        } else {
            $output['success'] = true;
        }
        return $output;
    }

    private static function getUserByType($type, $refparam, $session) {
        switch ($type) {
        case 'refparam':
            if (is_numeric($refparam)) {
                return User::getUserByID($refparam);
            } else {
                return $refparam;
            }
        case 'session':
            if ($session instanceof User) {
                return $session;
            }
            break;
        }
        return null;
    }
}

if (isset($_SERVER["SCRIPT_FILENAME"]) && basename($_SERVER["SCRIPT_FILENAME"]) == 'actions.php') {
    // check that action was in the request (POST or GET), but always have a value
    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

    $output = Action::callAction($action, $_POST, true);

    // output as JSON
    echo json_encode($output);
}

