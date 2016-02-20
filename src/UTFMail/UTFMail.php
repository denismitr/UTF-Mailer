<?php

namespace Acme\UTFMail;

class UTFMail {

	private $title = '(No title)';
	private $to = '';
	private $fromName = '';
	private $fromEmail = '';
	private $htmlBody = '';
	private $titlePrefix = '';
	private $bodyPrefix = '';


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
	 * @param $prefix
	 * @return $this
	 */
	public function prefixTitle($prefix)
	{
		if (!empty($prefix))
		{
			$this->titlePrefix = $prefix;
		}

		return $this;
	}


	/**
	 * @param $prefix
	 * @return $this
	 */
	public function prefixBody($prefix)
	{
	    if (!empty($prefix))
		{
			$this->bodyPrefix = '<h3>' . $prefix . '</h3>';
		}

		return $this;
	}


	/**
	 * @param $title
	 * @param string $prefix
	 * @return $this
	 */
	public function title($title, $prefix = '')
	{
		$this->title = $title;

		return $this->prefixTitle($prefix);
	}


	/**
	 * @param $htmlBody
	 * @param string $prefix
	 * @return UTFMail
	 */
	public function body($htmlBody, $prefix = '')
	{
		$this->htmlBody = nl2br($htmlBody);

		return $this->prefixBody($prefix);
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
	 * @return bool
	 */
	public function send() {

		if (empty($this->fromEmail) || empty($this->fromName) || empty($this->htmlBody) || empty($this->to)) {
			return false;
		}

		$this->fromName = "=?UTF-8?B?".base64_encode($this->fromName)."?=";
		$this->title = "=?UTF-8?B?".base64_encode($this->titlePrefix . ' ' . $this->title)."?=";
		$this->htmlBody = $this->bodyPrefix . '<br>' . $this->htmlBody;

		$headers = array();
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-type: text/html; charset=UTF-8";
		$headers[] = "Content-Transfer-Encoding: 7bit";
		$headers[] = "Date: " . date('r', $_SERVER['REQUEST_TIME']);
		$headers[] = "Message-ID: <" . $_SERVER['REQUEST_TIME'] . md5($_SERVER['REQUEST_TIME']) . "@" . $_SERVER['SERVER_NAME'] . ">";
		$headers[] = "From: {$this->fromName} <{$this->fromEmail}>";
		$headers[] = "Reply-To: {$this->fromName} <{$this->fromEmail}>";
		$headers[] = "Return-Path: {$this->fromEmail}";
		$headers[] = "Subject: {$this->title}";
		$headers[] = "X-Mailer: PHP v".phpversion();
		$headers[] = "X-Originating-IP: ".$_SERVER['SERVER_ADDR'];

		return mail($this->to, $this->title, $this->htmlBody, implode("\r\n", $headers));
	}

}