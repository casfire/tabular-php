<?php

abstract class TabularFormat implements Serializable {
	
	/**
	 * Format and return a html friendly string
	 * Return false on error
	 * @param mixed $data Data to format
	 * @return Html friendly string or false on error
	 */
	public abstract function html($data);
	
	/**
	 * Called when TabularCell is being serialized
	 * @return string Serialized string
	 */
	public function serialize() {
		return '';
	}
	
	/**
	 * Called when TabularCell is being unserialized
	 * @param string $serialized Serialized string
	 */
	public function unserialize($serialized) {}
	
}
