<?php

final class Email {
	private static $template_subject = 'TARS - :subject';
	private static $template_body = "Hello :filname,\r\n\r\n:text\r\n\r\nRegards,\r\nTA Reporting System\r\nUniversity of Rochester\r\n\r\nIf you did not request or expect this email be sent to you, you may safely ignore it.";
	private static $template_mailname = 'TARS';
	private static $template_mailfrom = 'no-reply';

	public static function send($targetUser, $subjectV, $bodyT, $eventType, $extraParams = array()) {
		if ($targetUser == null) {
			throw new TarsException(Event::SERVER_EXCEPTION, $eventType, new Exception('No email target'));
		}
		$args = array(
			':name' => $targetUser->getName(),
			':filname' => $targetUser->getFILName(),
			':email' => $targetUser->getEmail(),
			':text' => '%s'
		);
		$args = array_merge($args, $extraParams);
		$subject = Template::evaluate(Email::$template_subject, array(':subject' => $subjectV));
		$body_text = Template::evaluate($bodyT, $args);
		$body_wrap = Template::evaluate(Email::$template_body, $args);
		$body = sprintf($body_wrap, $body_text);
		$mailTo = Template::evaluate('":name" <:email>', $args);
		$mailSubject = $subject;
		$mailBody = $body;
		$mailHeaders = array(
			//'From' => $mailFrom
		);
		$sendmailArgs = array(
			':mname' => Email::$template_mailname,
			':mfrom' => Email::$template_mailfrom,
			':mdom' => Configuration::get(Configuration::EMAIL_DOMAIN));
		$sendmailCLI = Template::evaluate('-f :mfrom@:mdom -F ":mname"', $sendmailArgs);
		mail($mailTo, $mailSubject, $mailBody, '', $sendmailCLI);
	}

	// a 64-bit (8-byte) token produces an 11-character base64 string
	// with one padding character, =.
	// The below code generates the numeric token and allows you
	// to encode it in URL-safe base64 and then decode it
	//
	// getLink(string $act, int64 $token):
	// Takes the given 64-bit token and produces a callback link for our domain
	// and the given action.
	public static function getLink($token) {
		if ($token != null) {
			$enc_token = ResetToken::encodeToken($token);
		} else {
			$enc_token = '';
		}
		$link_base = Configuration::get(Configuration::EMAIL_LINK_BASE);
		return $link_base.'token.php?token='.$enc_token;
	}
}

final class Template {
	public static function evaluate($text, $args) {
		foreach ($args as $key => $value) {
			$text = preg_replace('/'.preg_quote($key).'\b/', $value, $text);
		}
		return $text;
	}
}

