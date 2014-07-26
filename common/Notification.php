<?php

final class Notification {
	public static function getNotificationByID($id) {
		$sql = 'SELECT * FROM Notifications
				WHERE notifID = :id';
		$args = array(':id' => $id);
		$row = Database::executeGetRow($sql, $args);
		if ($row == null) {
			return null;
		}
		return new Notification($row);
	}

	public static function insertNotification($targetUserID, $sendEmail, $onHome, $subject, $homeText, $emailTemplate, $time) {
		$sql = 'INSERT INTO Notifications
				(showOnHome, sendToEmail, subject, notifText, emailTemplate, creatorID, createTime) VALUES
				(:home, :email, :subject, :homeText, :emailText, :creator, :createTime)';
		$args = array(':home' => $onHome, ':email' => $sendEmail, ':subject' => $subject,
			':homeText' => $homeText, ':emailText' => $emailTemplate,
			':creator' => $targetUserID, ':createTime' => date('Y-m-d H:i:s', $time));
		return Database::executeInsert($sql, $args);
	}

	public function __construct($row) {
		$this->id = $row['notifID'];
		$this->showOnHome = $row['showOnHome'];
		$this->sendToEmail = $row['sendToEmail'];
		$this->subject = $row['subject'];
		$this->notifText = $row['notifText'];
		$this->emailTemplate = $row['emailTemplate'];
		$this->creatorID = $row['creatorID'];
		$this->creator = null;
		$this->createTime = strtotime($row['createTime']);
	}

	public function sendEmail($extra_params, $action_event) {
		Email::send($this->getCreator(), $this->subject, $this->emailTemplate, $action_event, $extra_params);
	}

	public function getID() { return $this->id; }
	public function isShownOnHome() { return $this->showOnHome; }
	public function isSentToEmail() { return $this->sendToEmail; }
	public function getSubject() { return $this->subject; }
	public function getHomeText() { return $this->notifText; }
	public function getEmailTemplate() { return $this->emailTemplate; }
	public function getCreator() {
		if ($this->creator == null) {
			$this->creator = User::getUserByID($this->creatorID);
		}
		return $this->creator;
	}
	public function getCreateTime() { return $this->createTime; }

	private $id;
	private $showOnHome;
	private $sendToEmail;
	private $subject;
	private $notifText;
	private $emailTemplate;
	private $creatorID;
	private $creator;
	private $createTime;
}

