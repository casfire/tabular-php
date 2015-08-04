<?php

require_once(dirname(__FILE__).'/../TabularFormat.php');

class TabularFormatString extends TabularFormat {
	
	function html($data) {
		return htmlspecialchars($data);
	}
}
