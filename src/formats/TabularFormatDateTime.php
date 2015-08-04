<?php

require_once(dirname(__FILE__).'/../TabularFormat.php');

class TabularFormatDateTime extends TabularFormat {
	
	const DEF_FORMAT = 'd.m.Y H:i:s';
	
	private $format = self::DEF_FORMAT;
	
	public function __construct($format = null) {
		if ($format !== null) $this->format = $format;
	}
	
	function html($data) {
		if (!($t = strtotime($data))) return false;
		return htmlspecialchars(date($this->format, $t));
	}
	
	public function serialize() {
		$data = array();
		if ($this->format !== self::DEF_FORMAT) $data = $this->format;
		return empty($data)?'':serialize($data);
	}
	
	public function unserialize($serialized) {
		$this->__construct();
		if (!($data = unserialize($serialized))) return;
		if (!empty($data)) $this->format = $data;
	}

}
