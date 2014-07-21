<?php

require_once 'plugins/password_compat/password.php';
require_once 'error.php';
require_once 'session.php';
require_once 'common/Place.php';
require_once 'common/User.php';
require_once 'common/Student.php';
require_once 'common/Professor.php';
require_once 'common/Staff.php';
require_once 'common/Admin.php';
require_once 'common/Position.php';
require_once 'common/Application.php';
require_once 'common/Term.php';
require_once 'common/Comment.php';
require_once 'common/SectionSession.php';
require_once 'common/Section.php';
require_once 'common/Event.php';
require_once 'common/Configuration.php';

/*******************************************
*TARS- Teacher Assistant Registration System
********************************************/

/******************
*Database Interface
*******************/
		

/* Database login credentials */
const DATABASE_PATH = 'localhost';
const DATABASE_USERNAME = 'root';
const DATABASE_PASSWORD = '1234';
const DATABASE_NAME = 'TARS';
const DATABASE_TYPE = 'mysql';

/*Account Types*/
const STUDENT = 0;
const PROFESSOR = 1;
const STAFF = 2;
const ADMIN = 3;

/*Application Statuses*/
const PENDING = 0;
const STAFF_VERIFIED = 1;
const REJECTED = 2;
const APPROVED = 3;
const WITHDRAWN = 4;

/******************
*DATABASE UTILITIES
*******************/	

/*
 * Databse Object: Utility class to connect to and query the database using PDO.
 */
final class Database {
	private static $db_conn = null;

	/**
	 * Database::connect()
	 * Purpose: Connects to the database using PDO and sets $db_conn.
	 *
	 * This terminates the script with an echo on failure, since throwing an
	 * exception is a security risk (the stacktrace will contain the database credentials).
	 *
	 * Call this ONLY ONCE, because we want to promote persistant database connections, which
	 * can be cached by PDO.
	 */
	public static function connect() {
		$db_dsn = DATABASE_TYPE.':host='.DATABASE_PATH.';dbname='.DATABASE_NAME;

		try {
			/** Obtain a persistant object representation of the database */
			Database::$db_conn = new PDO($db_dsn, DATABASE_USERNAME, DATABASE_PASSWORD);
			Database::$db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $ex) {
			$error = new TarsException(Event::SERVER_DBERROR, Event::SERVER_DBERROR, $ex);
			echo $error->toHTML();
			exit;
		}
	}

	/**
	 * Database::isConnected()
	 * Purpose: Tells us whether Database::connect() has succeeded
	 * Returns: true or false
	 */
	public static function isConnected() {
		return Database::$db_conn != null;
	}

	/**
	 * Database::executeStatement($sql, $args)
	 * Purpose: Prepares and executes a statement with the specified arguments.
	 * Returns: The statement object, for fetching.
	 * Throws:  A PDOException if prepare() or execute() fail (SQL syntax or database error).
	 */
	public static function execute($sql, $args) {
		//echo "<pre>EXECUTE: $sql\n";var_dump($args);echo '</pre>';

		/* prepare statement: throws PDOException on error */
		$stmt = Database::$db_conn->prepare($sql);

		/* execute statement: throws PDOException on error */
		$stmt->execute($args);

		return $stmt;
	}

	/**
	 * Database::executeGetRow($sql, $args)
	 * Purpose: Runs a prepared statement with the given SQL statement and arguments.
	 *          Gets a row of the database.
	 * Returns: The row requested, or NULL if no rows returned
	 * Throws:  A PDOException if prepare() or execute() fail (SQL syntax or database error).
	 */
	public static function executeGetRow($sql, $args) {
		/* create and execute the statement object */
		$stmt = Database::execute($sql, $args);

		/* fetch result: return first row, or null */
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($result === null) {
			/* empty result set */
			return null;
		}

		return $result;
	}

	/**
	 * Database::executeGetAllRows($sql, $args)
	 * Purpose: Runs a prepared statement with the given SQL statement and arguments.
	 *          Gets all selected rows of the database.
	 * Returns: The rows requested
	 * Throws:  A PDOException if prepare() or execute() fail (SQL syntax or database error).
	 */
	public static function executeGetAllRows($sql, $args) {
		/* create and execute the statement object */
		$stmt = Database::execute($sql, $args);

		/* fetch result: return array of all rows */
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Database::executeGetScalar($sql, $args)
	 * Purpose: Runs a prepared statement with the given SQL statement and arguments.
	 *          Gets a single cell/value (scalar) from the database.
	 * Returns: The scalar requested, or NULL if no rows returned
	 * Throws:  A PDOException if prepare() or execute() fail (SQL syntax or database error).
	 */
	public static function executeGetScalar($sql, $args) {
		/* create and execute the statement object */
		$stmt = Database::execute($sql, $args);

		/* fetch result: return first row, or null */
		$result = $stmt->fetch(PDO::FETCH_NUM);
		if ($result === null) {
			/* empty result set */
			return null;
		}

		return $result[0];
	}

	/**
	 * Database::executeInsert($sql, $args)
	 * Purpose: Runs a prepared statement with the given SQL statement and arguments.
	 *          Gets the inserted ID
	 * Returns: The last ID generated for an AUTO_INCREMENT column; the ID of the column inserted.
	 * Throws:  A PDOException if prepare() or execute() fail (SQL syntax or database error).
	 * Note:    Yes, you can run non-INSERT queries with this.
	 *          It's silly to though, as why else do you need the last ID inserted?
	 */
	public static function executeInsert($sql, $args) {
		/* create and execute the statement object */
		Database::execute($sql, $args);

		/* get the inserted ID */
		return Database::$db_conn->lastInsertId();
	}

	/**
	 * Database::beginTransaction()
	 * Purpose: Starts a PDO transaction
	 */
	public static function beginTransaction() {
		return Database::$db_conn->beginTransaction();
	}

	/**
	 * Database::rollbackTransaction()
	 * Purpose: Cancels a PDO transaction; no database changes will be made
	 */
	public static function rollbackTransaction() {
		return Database::$db_conn->rollback();
	}

	/**
	 * Database::commitTransaction()
	 * Purpose: Commits a PDO transaction; database changes will be made atomically
	 */
	public static function commitTransaction() {
		return Database::$db_conn->commit();
	}
}

/** Connect to the database and create/use PDO object. */
Database::connect();

