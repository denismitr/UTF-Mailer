<?php

namespace UTFMailer;

class UTFMailer {

	private $subject = '(No subject)';
	private $to = '';
	private $fromWho = '';
	private $fromWhoEmail = '';
	private $htmlMessage = '';

	/**
	 * UTFMailer constructor.
	 * @param string $to
	 * @param string $fromWho
	 * @param string $fromWhoEmail
	 * @param string $subject
	 * @param string $htmlMessage
	 */
	public function __construct($to = '', $fromWho = '', $fromWhoEmail = '',
	                            $subject = '(No subject)', $htmlMessage = '') {

		$this->to = $to;
		$this->fromWho = $fromWho;
		$this->fromWhoEmail = $fromWhoEmail;
		$this->subject = $subject;
		$this->htmlMessage = nl2br($htmlMessage);

	}

	/**
	 * @param $subject
	 * @return $this
	 */
	public function setSubject($subject) {
		$this->subject = $subject;
		return $this;
	}

	/**
	 * @param $htmlMessage
	 * @return $this
	 */
	public function setHtmlMessage($htmlMessage) {
		$this->htmlMessage = nl2br($htmlMessage);
		return $this;
	}

	/**
	 * @param $fromWhoEmail
	 * @return $this
	 */
	public function setFromWhoEmail($fromWhoEmail) {
		$this->fromWhoEmail = $fromWhoEmail;
		return $this;
	}

	/**
	 * @param $fromWho
	 * @return $this
	 */
	public function setFromWho($fromWho) {
		$this->fromWho = $fromWho;
		return $this;
	}

	/**
	 * @param $to
	 * @return $this
	 */
	public function setTo($to) {
		$this->to = $to;
		return $this;
	}


	/**
	 * @return bool
	 */
	public function send() {

		if (empty($this->fromWhoEmail) || empty($this->fromWho) || empty($this->htmlMessage) || empty($this->to)) {
			return false;
		}

		$this->fromWho = "=?UTF-8?B?".base64_encode($this->fromWho)."?=";
		$this->subject = "=?UTF-8?B?".base64_encode($this->subject)."?=";

		$headers = array();
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-type: text/html; charset=UTF-8";
		$headers[] = "Content-Transfer-Encoding: 7bit";
		$headers[] = "Date: " . date('r', $_SERVER['REQUEST_TIME']);
		$headers[] = "Message-ID: <" . $_SERVER['REQUEST_TIME'] . md5($_SERVER['REQUEST_TIME']) . "@" . $_SERVER['SERVER_NAME'] . ">";
		$headers[] = "From: {$this->fromWho} <{$this->fromWhoEmail}>";
		$headers[] = "Reply-To: {$this->fromWho} <{$this->fromWhoEmail}>";
		$headers[] = "Return-Path: {$this->fromWhoEmail}";
		$headers[] = "Subject: {$this->subject}";
		$headers[] = "X-Mailer: PHP v".phpversion();
		$headers[] = "X-Originating-IP: ".$_SERVER['SERVER_ADDR'];

		return mail($this->to, $this->subject, $this->htmlMessage, implode("\r\n", $headers));
	}

}