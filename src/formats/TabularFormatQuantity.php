<?php

require_once(dirname(__FILE__).'/../TabularFormat.php');

class TabularFormatQuantity extends TabularFormat {
	
	const DEF_NAME = '';
	
	private $name = self::DEF_NAME;
	
	public function __construct($name = null) {
		if ($name !== null) $this->name = $name;
	}
	
	function html($data) {
		if (!ctype_digit((string) $data)) return false;
		$html = (string) $data;
		if (!empty($this->name)) $html .= ' '.$this->name;
		return htmlspecialchars($html);
	}
	
	public function serialize() {
		$data = array();
		if ($this->name !== self::DEF_NAME) $data = $this->name;
		return empty($data)?'':serialize($data);
	}
	
	public function unserialize($serialized) {
		$this->__construct();
		if (!($data = unserialize($serialized))) return;
		if (!empty($data)) $this->name = $data;
	}

}
