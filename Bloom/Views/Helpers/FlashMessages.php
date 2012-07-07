<?php

namespace Bloom\Views\Helpers;

use Bloom\Views\Helper;

class FlashMessages extends Helper implements \Iterator
{
	const MESSAGE_TYPE_WARNING = 0;
	const MESSAGE_TYPE_ERROR = 1;
	const MESSAGE_TYPE_SUCCESS = 2;
	const MESSAGE_TYPE_INFORMATION = 3;

	protected $position = 0;

	protected $_messages = [];

	protected $_classes = [
		"block",
		"error",
		"success",
		"info",
	];

	protected $_path = "helpers/flash_messages";

	public function __construct() { 
		$this->position = 0;
	}

	public function setMessage($message, $type = self::MESSAGE_TYPE_INFORMATION)
	{
		$_SESSION['flashMessages'][] = ["message" => (string) $message, "type" => $type];
	}

	public function hasMessages()
	{
		return isset($_SESSION['flashMessages']) && count($_SESSION['flashMessages']);
	}

	public function getClass($type)
	{
		if(array_key_exists($type, $this->_classes)) {
			return $this->_classes[$type];
		}
	}

	protected function _loadMessages()
	{
		$this->_messages = $this->hasMessages() ? $_SESSION['flashMessages'] : [];
	}

	public function rewind() {
		// Load flash messages only when the first iteration occurs
		if($this->position === 0) {
			$this->_loadMessages();
		}
        $this->position = 0;
    }

    public function current() {
        return $this->_messages[$this->position];
    }

    public function key() {
        return $this->position;
    }

    public function next() {
		// Reset flash messages when iterated throught last one
		if($this->position === (count($this->_messages) -1)) {
			unset($_SESSION['flashMessages']);
		}
		++$this->position;
    }

    public function valid() {
        return isset($this->_messages[$this->position]);
    }
}