<?php

require_once(dirname(__FILE__).'/../TabularFormat.php');

class TabularFormatMissing extends TabularFormat {
	
	function html($data) {
		return htmlspecialchars($data);
	}
	
	function add(TabularCell $cell) {
		$cell->htmlAddClass($this->class);
		return true;
	}
	
	function remove(TabularCell $cell) {
		$cell->htmlRemoveClass($this->class);
	}
	
}
