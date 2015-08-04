<?php

class TabularIndent {

	/**
	 * @var string EOL character
	 */
	public $EOL = "\n";
	
	/**
	 * @var string Prefix for lines
	 */
	public $prefix = '';
	
	/**
	 * @var string Indent character
	 */
	public $indent = "\t";
	
	/**
	 * Construct a new TabularIndent
	 * @param string $indent Indent character
	 * @param string $EOL EOL character
	 * @param string $prefix Prefix for lines
	 */
	public function __construct($indent = null, $EOL = null, $prefix = null) {
		if ($indent !== null) $this->indent = $indent;
		if ($EOL !== null) $this->EOL = $EOL;
		if ($prefix !== null) $this->prefix = $prefix;
	}
	
	/**
	 * Indent a single line
	 * @param string $line Line to indent
	 * @param string $level Level to indent to
	 * @return string Indented line
	 */
	public function line($line, $level = 0) {
		return $this->prefix.str_repeat($this->indent, $level).$line.$this->EOL;
	}
	
}
