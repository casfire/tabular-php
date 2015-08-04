<?php

require_once(dirname(__FILE__).'/../TabularFormat.php');

class TabularFormatCurrency extends TabularFormat {
	
	const DEF_NAME = '';
	const DEF_PRECISION = 2;
	
	private $name = self::DEF_NAME;
	private $precision = self::DEF_PRECISION;
	
	public function __construct($name = null, int $precision = null) {
		if ($name !== null) $this->name = $name;
		if ($precision !== null) $this->precision = $precision;
	}

	function html($data) {
		if (!is_numeric($data)) return false;
		$html = number_format($data, $this->precision);
		if ($html == 0) {
			$html = '0.'.str_repeat('0', $this->precision);
		}
		if (!empty($this->name)) {
			$html .= ' '.$this->name;
		}
		return htmlspecialchars($html);
	}
	
	public function serialize() {
		$data = array();
		if ($this->name      !== self::DEF_NAME)      $data[0] = $this->name;
		if ($this->precision !== self::DEF_PRECISION) $data[1] = $this->precision;
		return empty($data)?'':serialize($data);
	}
	
	public function unserialize($serialized) {
		$this->__construct();
		if (!($data = unserialize($serialized))) return;
		if (!empty($data[1])) $this->name =      $data[0];
		if (!empty($data[2])) $this->precision = $data[1];
	}

}
