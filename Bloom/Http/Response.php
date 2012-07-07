<?php

namespace Bloom\Http;

class Response
{
	protected $_body = "";

	protected $_statusCodes = array(
		100 => 'Continue',
		101 => 'Switching Protocols',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Time-out',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Large',
		415 => 'Unsupported Media Type',
		416 => 'Requested range not satisfiable',
		417 => 'Expectation Failed',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Time-out'
	);
	
	protected $_protocol = 'HTTP/1.1';
	protected $_status = 200;
	protected $_contentType = "text/html";
	protected $_charset = "UTF-8";
	protected $_headers = [];

	public function __construct()
	{

	}

	public function setStatus($status)
	{
		if(in_array($status, $this->_statusCodes)) {
			$this->_status = $status;
		}
	}

	public function setHeader($name, $value)
	{
		$this->_headers[$name] = $value;
	}

	public function appendBody($content)
	{
		$this->_body .= $content;
	}

	public function send()
	{
		$codeMessage = $this->_statusCodes[$this->_status];
		header("{$this->_protocol} {$this->_status} {$codeMessage}");
		header("Content-Type: {$this->_contentType}, charset={$this->_charset}");
		foreach($this->_headers as $name => $value) {
			header("{$name}: $value");
		}
		echo (string) $this;
	}

	public function __toString()
	{
		return $this->_body;
	}
}