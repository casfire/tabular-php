<?php

class TabularHTML implements Serializable {
	
	private $e_classes = array();
	private $e_id = null;
	private $e_style = null;
	private $e_title = null;
	
	/**
	 * Set raw contents of html <b>id</b> attribute
	 * @param string $id Raw contents
	 * @return TabularElement This element
	 */
	public function htmlSetID($id) {
		if (empty($id)) {
			$this->e_id = null;
		} else {
			$this->e_id = $id;
		}
		return $this;
	}
	
	/**
	 * @return string Raw contents of html <b>id</b> attribute
	 */
	public function htmlGetID() {
		return $this->e_id;
	}
	
	/**
	 * Set raw contents of html <b>style</b> attribute
	 * @param string $style Raw contents
	 * @return TabularElement This element
	 */
	public function htmlSetStyle($style) {
		if (empty($style)) {
			$this->e_style = null;
		} else {
			$this->e_style = $style;
		}
		return $this;
	}
	
	/**
	 * @return string Raw contents of html <b>style</b> attribute
	 */
	public function htmlGetStyle() {
		return $this->e_style;
	}
	
	/**
	 * Set raw contents of html <b>title</b> attribute
	 * @param string $title Raw contents
	 * @return TabularElement This element
	 */
	public function htmlSetTitle($title) {
		if (empty($title)) {
			$this->e_title = null;
		} else {
			$this->e_title = $title;
		}
		return $this;
	}
	
	/**
	 * @return string Raw contents of html <b>title</b> attribute
	 */
	public function htmlGetTitle() {
		return $this->e_title;
	}
	
	/**
	 * Set raw contents of html <b>class</b> attribute
	 * @param string $class Class list separated by spaces
	 * @return TabularElement This element
	 */
	public function htmlSetClass($class) {
		if (empty($class)) {
			$this->e_classes = array();
		} else {
			$this->e_classes = explode(' ', $class);
		}
		return $this;
	}
	
	/**
	 * @return string Raw contents of html <b>class</b> attribute
	 */
	public function htmlGetClass() {
		return implode(' ', $this->e_classes);
	}
	
	/**
	 * Add a class
	 * @param string $class Class name to add
	 * @return TabularElement This element
	 */
	public function htmlAddClass($class) {
		if (!$this->htmlHasClass($class)) {
			$this->e_classes[] = $class;
		}
		return $this;
	}
	
	/**
	 * Remove a class if exists
	 * @param string $class Class name to remove
	 * @return boolean True if removed, False if not found
	 */
	public function htmlRemoveClass($class) {
		for ($i = 0; $i < count($this->e_classes); $i++) {
			if ($this->e_classes[$i] === $class) {
				$this->e_classes = array_splice($this->e_classes, $i, 1);
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Check if this element contains a class
	 * @param string $class Class name to check for
	 * @return boolean True if found, False if not
	 */
	public function htmlHasClass($class) {
		foreach ($this->e_classes as $c) {
			if ($class === $c) return true;
		}
		return false;
	}
	
	/**
	 * Returns tag attributes for this element
	 * First character is always space
	 * Returns empty string if no attributes set
	 * @return string Html attributes
	 */
	protected function htmlAttributes() {
		$html = '';
		if (!empty($this->e_id)) {
			$html .= ' id="'.htmlspecialchars($this->htmlGetID()).'"';
		}
		if (!empty($this->e_style)) {
			$html .= ' style="'.htmlspecialchars($this->htmlGetStyle()).'"';
		}
		if (!empty($this->e_title)) {
			$html .= ' title="'.htmlspecialchars($this->htmlGetTitle()).'"';
		}
		if (!empty($this->e_classes)) {
			$html .= ' class="'.htmlspecialchars($this->htmlGetClass()).'"';
		}
		return $html;
	}
	
	/**
	 * Serialize this TabularElement
	 * @return string Serialized string
	 */
	public function serialize() {
		$data = array();
		if (!empty($this->e_classes)) $data[0] = $this->htmlGetClass();
		if (!empty($this->e_id))      $data[1] = $this->htmlGetID();
		if (!empty($this->e_style))   $data[2] = $this->htmlGetStyle();
		if (!empty($this->e_title))   $data[3] = $this->htmlGetTitle();
		return empty($data)?'':serialize($data);
	}
	
	
	/**
	 * Unserialize into this TabularElement
	 * @param string $serialized Serialized string
	 */
	public function unserialize($serialized) {
		if (!($data = unserialize($serialized))) return;
		if (!empty($data[0])) $this->htmlSetClass($data[0]);
		if (!empty($data[1])) $this->htmlSetID($data[1]);
		if (!empty($data[2])) $this->htmlSetStyle($data[2]);
		if (!empty($data[3])) $this->htmlSetTitle($data[3]);
	}
	
}
