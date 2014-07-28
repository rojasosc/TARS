<?php

final class Configuration {
	const LOG_DEBUG = 'logDebug'; // TODO unused
	const ADMIN_CREATED = 'adminCreated'; // TODO unused
	const ENABLE_LOGIN = 'enableLogin'; // TODO unused
	const CURRENT_TERM = 'currentTerm';
	const EMAIL_DOMAIN = 'emailDomain';
	const EMAIL_LINK_BASE = 'emailLinkBase';
	const CONFIG_LAST_ID = 'configID';
	const CONFIG_LAST_UPDATOR = 'creatorID';
	const CONFIG_LAST_UPDATETIME = 'createTime';

	// we can cache this row over the course of a PHP script run
	// it is unlikely to change more often
	private static $cachedConfig = null;

	public static function get($key) {
		if (Configuration::$cachedConfig == null) {
			$sql = 'SELECT * FROM Configurations
					ORDER BY createTime DESC
					LIMIT 1';
			Configuration::$cachedConfig = Database::executeGetRow($sql, array());
		}

		if (isset(Configuration::$cachedConfig[$key])) {
			return Configuration::$cachedConfig[$key];
		} else {
			return null;
		}
	}

	public static function set($key, $value, $user, $time) {
		// make sure cache is populated
		Configuration::get(null); 
		// set value if key is present
		if (array_key_exists($key, Configuration::$cachedConfig) &&
			$key != Configuration::CONFIG_LAST_ID &&
			$key != Configuration::CONFIG_LAST_UPDATOR &&
			$key != Configuration::CONFIG_LAST_UPDATETIME) {
			Configuration::$cachedConfig[$key] = $value;
		} else {
			return null;
		}
		$sql = 'INSERT INTO Configurations
			(logDebug, adminCreated, domain, enableLogin, currentTerm, creatorID, createTime) VALUES
			(:logDebug, :adminCreated, :domain, :enableLogin, :currentTerm, :creatorID, :createTime)';
		$args = array();
		foreach (Configuration::$cachedConfig as $curKey => $curVal) {
			$args[":$curKey"] = $curVal;
		}
		unset($args[':configID']);
		$args[':creatorID'] = $user->getID();
		$args[':createTime'] = date('Y-m-d H:i:s', $time);
		return Database::executeInsert($sql, $args);
	}
}

