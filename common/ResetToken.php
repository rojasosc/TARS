<?php

final class ResetToken {
	// generateToken():
	// Returns a new cryptographically random 64-bit token
	// PHP >= 5.3.0
	public static function generateToken($action, $creatorID, $createTime, $cbToken = null, $cbNotifID = null, $timeoutTime = null) {
		$token = ResetToken::getTokenByAction($action, $creatorID);
		if ($token != null) {
			return $token;
		}
		do {
			$token_str = openssl_random_pseudo_bytes(8); // 64-bit
			$token = ResetToken::str2token($token_str);
		} while (ResetToken::isTokenInUse($token));
		if ($timeoutTime != null) {
			$timeoutTime = date('Y-m-d H:i:s', $timeoutTime);
		}
		$sql = 'INSERT INTO ResetTokens
				(token, action, callbackToken, callbackNotif, timeoutTime, creatorID, createTime) VALUES
				(:token, :action, :cbToken, :cbNotif, :timeoutTime, :creator, :createTime)';
		$args = array(':token' => $token, ':action' => $action,
			':cbToken' => $cbToken, ':cbNotif' => $cbNotifID,
			':timeoutTime' => $timeoutTime, ':creator' => $creatorID,
			':createTime' => date('Y-m-d H:i:s', $createTime));
		Database::executeInsert($sql, $args);
		return $token;
	}

	// Return the associated ResetToken object and deletes the token from the database
	// Tokens are one-use
	public static function applyToken($enc_token) {
		$token = ResetToken::decodeToken($enc_token);
		$tokenAction = ResetToken::getActionByToken($token);
		if ($tokenAction != null) {
			$sql = 'DELETE FROM ResetTokens WHERE token = :token';
			$args = array(':token' => $token);
			Database::execute($sql, $args);
			return $tokenAction;
		} else {
			return null;
		}
	}

	public static function getTokenByAction($action, $creatorID) {
		$sql = 'SELECT token FROM ResetTokens
				WHERE action = :action AND creatorID = :creator';
		$args = array(':action' => $action, ':creator' => $creatorID);
		return Database::executeGetScalar($sql, $args);
	}

	public static function getActionByToken($token) {
		$sql = 'SELECT * FROM ResetTokens WHERE token = :token';
		$args = array(':token' => $token);
		$row = Database::executeGetRow($sql, $args);
		if ($row == null) {
			return null;
		}
		return new ResetToken($row);
	}

	public static function isTokenInUse($token) {
		$sql = 'SELECT COUNT(*) FROM ResetTokens WHERE token = :token';
		$args = array(':token' => $token);
		return Database::executeGetScalar($sql, $args) == 1;
	}

	// encodeToken(int64 $token):
	// Returns a URL-safe base64 encoded token, with the 1-character padding (=) removed.
	public static function encodeToken($token) {
		$token_str = '';
		for ($i = 0; $i < 64; $i += 8) {
			$token_str .= chr(($token >> $i) & 0xff);
		}
		$token64 = base64_encode($token_str);
		return str_replace(array('+','/','='),array('-','_',''),$token64);
	}

	// decodeToken(string token):
	// Returns the 64-bit token represented by this string.
	// If not a valid 11-character token for any reason, returns NULL instead.
	public static function decodeToken($enc_token) {
		if (strlen($enc_token) != 11) {
			return null;
		}
		$token64 = str_replace(array('-','_'),array('+','/'),$enc_token).'=';
		$token_str = base64_decode($token64);
		if ($token_str === false) {
			return null;
		}
		$token = ResetToken::str2token($token_str);
		if ($token === 0) {
			return null;
		}
		return $token;
	}

	// common function to convert an 8-byte binary string to a 64-bit number
	private static function str2token($token_str) {
		$result = 0;
		for ($i = 0; $i < 8; $i++) {
			$result += (ord($token_str[$i]) << ($i * 8));
		}
		return $result;
	}

	public function __construct($row) {
		$this->token = $row['token'];
		$this->action = $row['action'];
		$this->cbNotifID = $row['callbackNotif'];
		$this->cbToken = $row['callbackToken'];
		$this->creatorID = $row['creatorID'];
		$this->creator = null;
		$this->createTime = strtotime($row['createTime']);
		$this->timeoutTime = $row['timeoutTime'];
	}

	public function getAction() { return $this->action; }
	public function getCallbackToken() { return $this->cbToken; }
	public function getCallbackNotifID() { return $this->cbNotifID; }
	public function getCreator() {
		if ($this->creator == null) {
			$this->creator = User::getUserByID($this->creatorID);
		}
		return $this->creator;
	}
	public function getCreateTime() { return $this->createTime; }

	private $token;
	private $action;
	private $cbNotifID;
	private $cbToken;
	private $creatorID;
	private $creator;
	private $createTime;
}

