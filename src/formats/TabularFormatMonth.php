<?php

require_once(dirname(__FILE__).'/../TabularFormat.php');

class TabularFormatMonth extends TabularFormat {
	
	const DEF_MONTH_1  = 'Januar';
	const DEF_MONTH_2  = 'Februar';
	const DEF_MONTH_3  = 'Marec';
	const DEF_MONTH_4  = 'April';
	const DEF_MONTH_5  = 'Maj';
	const DEF_MONTH_6  = 'Junij';
	const DEF_MONTH_7  = 'Julij';
	const DEF_MONTH_8  = 'Avgust';
	const DEF_MONTH_9  = 'September';
	const DEF_MONTH_10 = 'Oktober';
	const DEF_MONTH_11 = 'November';
	const DEF_MONTH_12 = 'December';
	
	private $months = array(
			self::DEF_MONTH_1,  self::DEF_MONTH_2,  self::DEF_MONTH_3,
			self::DEF_MONTH_4,  self::DEF_MONTH_5,  self::DEF_MONTH_6,
			self::DEF_MONTH_7,  self::DEF_MONTH_8,  self::DEF_MONTH_9,
			self::DEF_MONTH_10, self::DEF_MONTH_11, self::DEF_MONTH_12);
	
	public function __construct($months = null) {
		if (($months !== null) && (count($months) === 12)) $this->months = $months;
	}
	
	function html($data) {
		$m = $this->toMonth($data);
		if (!$m) return false;
		return htmlspecialchars($this->months[$m - 1]);
	}
	
	private function toMonth($data) {
		if (ctype_digit((string) $data)) {
			if ((int) $data >= 1 || (int) $data <= 12) return (int) $data;
		} else {
			$t = strtotime($data);
			if (!$t) {
				for ($t = 0; $t < 12; $t++) if ($this->months[$t] == $data) return $t + 1;
			} else {
				return date('j', $t);
			}
		}
		return false;
	}
	
	public function serialize() {
		$data = array();
		if ($this->months[0]  !== self::DEF_MONTH_1)  $data[0]  = $this->months[0];
		if ($this->months[1]  !== self::DEF_MONTH_2)  $data[1]  = $this->months[1];
		if ($this->months[2]  !== self::DEF_MONTH_3)  $data[2]  = $this->months[2];
		if ($this->months[3]  !== self::DEF_MONTH_4)  $data[3]  = $this->months[3];
		if ($this->months[4]  !== self::DEF_MONTH_5)  $data[4]  = $this->months[4];
		if ($this->months[5]  !== self::DEF_MONTH_6)  $data[5]  = $this->months[5];
		if ($this->months[6]  !== self::DEF_MONTH_7)  $data[6]  = $this->months[6];
		if ($this->months[7]  !== self::DEF_MONTH_8)  $data[7]  = $this->months[7];
		if ($this->months[8]  !== self::DEF_MONTH_9)  $data[8]  = $this->months[8];
		if ($this->months[9]  !== self::DEF_MONTH_10) $data[9]  = $this->months[9];
		if ($this->months[10] !== self::DEF_MONTH_11) $data[10] = $this->months[10];
		if ($this->months[11] !== self::DEF_MONTH_12) $data[11] = $this->months[11];
		return empty($data)?'':serialize($data);
	}
	
	public function unserialize($serialized) {
		$this->__construct();
		if (!($data = unserialize($serialized))) return;
		if (!empty($data[0]))  $this->months[0]  = $data[0];
		if (!empty($data[1]))  $this->months[1]  = $data[1];
		if (!empty($data[2]))  $this->months[2]  = $data[2];
		if (!empty($data[3]))  $this->months[3]  = $data[3];
		if (!empty($data[4]))  $this->months[4]  = $data[4];
		if (!empty($data[5]))  $this->months[5]  = $data[5];
		if (!empty($data[6]))  $this->months[6]  = $data[6];
		if (!empty($data[7]))  $this->months[7]  = $data[7];
		if (!empty($data[8]))  $this->months[8]  = $data[8];
		if (!empty($data[9]))  $this->months[9]  = $data[9];
		if (!empty($data[10])) $this->months[10] = $data[10];
		if (!empty($data[11])) $this->months[11] = $data[11];
	}

}
