<?php

namespace Denismitr\UTFMail;

class UTFMail {

	private $subject = '(No subject)';
	private $to = '';
	private $fromName = '';
	private $fromEmail = '';
	private $htmlBody = '';


	public function __construct($to)
	{
		$this->to = $to;
	}


	/**
	 * @param $to
	 * @return static
	 */
	public static function to($to)
	{
	    return new static($to);
	}


	/**
	 * @param $subject
	 * @return $this
	 */
	public function subject($subject)
	{
		$this->subject = $subject;

		return $this;
	}


	/**
	 * @param $htmlBody
	 * @return $this
	 */
	public function body($htmlBody)
	{
		$this->htmlBody = $htmlBody;

		return $this;
	}


	/**
	 * @param $email
	 * @param $name
	 * @return $this
	 */
	public function from($email, $name)
	{
		$this->fromName = $name;
		$this->fromEmail = $email;

		return $this;
	}


	/**
	 * @throws UTFMailException
	 */
	public function send() {

		if (empty($this->fromEmail) || empty($this->fromName) || empty($this->htmlBody) || empty($this->to)) {
			throw new UTFMailException("Wrong arguments passed into UTFMailer");
		}

		$this->fromName = "=?UTF-8?B?".base64_encode($this->fromName)."?=";
		$this->subject = "=?UTF-8?B?".base64_encode($this->subject)."?=";

		$headers = array();
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-type: text/html; charset=UTF-8";
		$headers[] = "Content-Transfer-Encoding: 7bit";
		$headers[] = "Date: " . date('r', $_SERVER['REQUEST_TIME']);
		$headers[] = "Message-ID: <" . $_SERVER['REQUEST_TIME'] . md5($_SERVER['REQUEST_TIME']) . "@" . $_SERVER['SERVER_NAME'] . ">";
		$headers[] = "From: {$this->fromName} <{$this->fromEmail}>";
		$headers[] = "Reply-To: {$this->fromName} <{$this->fromEmail}>";
		$headers[] = "Return-Path: {$this->fromEmail}";
		$headers[] = "Subject: {$this->subject}";
		$headers[] = "X-Mailer: PHP v".phpversion();
		$headers[] = "X-Originating-IP: ".$_SERVER['SERVER_ADDR'];

		if ( ! mail($this->to, $this->subject, $this->htmlBody, implode("\r\n", $headers))) {
			throw new UTFMailException("PHP Mail function failed");
		}
	}

}