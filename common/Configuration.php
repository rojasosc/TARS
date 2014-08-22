<?php

final class Configuration {
	const LOG_DEBUG = 'logDebug'; // TODO unused
	const ADMIN_CREATED = 'adminCreated';
	const ENABLE_LOGIN = 'enableLogin';
	const ENABLE_SEND_EMAIL = 'enableSendEmail';
	const BUG_REPORT_USER = 'bugReportUser';
	const CURRENT_TERM = 'currentTerm';
	const EMAIL_NAME = 'emailName';
	const EMAIL_DOMAIN = 'emailDomain';
	const EMAIL_LINK_BASE = 'emailLinkBase';
	const CONFIG_LAST_ID = 'configID';
	const CONFIG_LAST_UPDATOR = 'creatorID';
	const CONFIG_LAST_UPDATETIME = 'createTime';

	// we can cache this row over the course of a PHP script run
	// it is unlikely to change more often
	private static $cachedConfig = null;

	private static function populateCache() {
		$sql = 'SELECT * FROM Configurations
				ORDER BY createTime DESC
				LIMIT 1';
		Configuration::$cachedConfig = Database::executeGetRow($sql, array());
		if (Configuration::$cachedConfig === null) {
			$class = new ReflectionClass(__CLASS__);
			$cols = $class->getConstants();
			Configuration::$cachedConfig = array();
			foreach ($cols as $const => $column) {
				Configuration::$cachedConfig[$column] = null;
			}
		}
	}

	public static function get($key) {
		// make sure cache is populated
		if (Configuration::$cachedConfig === null) {
			Configuration::populateCache();
		}

		if (isset(Configuration::$cachedConfig[$key])) {
			return Configuration::$cachedConfig[$key];
		} else {
			return null;
		}
	}

	public static function set($key, $value, $user, $time) {
		return Configuration::setMultiple(array($key => $value), $user, $time);
	}

	public static function setMultiple($configSet, $user, $time) {
		// make sure cache is populated
		if (Configuration::$cachedConfig === null) {
			Configuration::populateCache();
		}

		$count = 0;
		foreach ($configSet as $key => $value) {
			// set value if key is present
			if (array_key_exists($key, Configuration::$cachedConfig) &&
				$key !== Configuration::CONFIG_LAST_ID &&
				$key !== Configuration::CONFIG_LAST_UPDATOR &&
				$key !== Configuration::CONFIG_LAST_UPDATETIME) {
				Configuration::$cachedConfig[$key] = $value;
				$count++;
			}
		}
		if ($count === 0) {
			// no changes
			return null;
		}

		unset(Configuration::$cachedConfig[Configuration::CONFIG_LAST_ID]);
		$args = array();
		foreach (Configuration::$cachedConfig as $curKey => $curVal) {
			$args[":$curKey"] = $curVal;
		}

		$sql = 'INSERT INTO Configurations (';
		$sql .= implode(', ', array_keys(Configuration::$cachedConfig));
		$sql .= ') VALUES (';
		$sql .= implode(', ', array_keys($args));
		$sql .= ')';

		$args[':creatorID'] = $user->getID();
		$args[':createTime'] = date('Y-m-d H:i:s', $time);
		return Database::executeInsert($sql, $args);
	}
}

