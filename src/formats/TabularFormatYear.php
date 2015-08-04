<?php

require_once(dirname(__FILE__).'/../TabularFormat.php');

class TabularFormatYear extends TabularFormat {
	
	function html($data) {
		if (!ctype_digit((string) $data)) return false;
		return htmlspecialchars($data);
	}

}
