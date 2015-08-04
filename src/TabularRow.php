<?php

require_once(dirname(__FILE__).'/TabularIndent.php');
require_once(dirname(__FILE__).'/TabularHTML.php');
require_once(dirname(__FILE__).'/TabularCell.php');

class TabularRow extends TabularHTML implements Serializable, Countable {
	
	const EXT_TAG_HEAD = 'thead';
	const EXT_TAG_BODY = 'tbody';
	const EXT_TAG_FOOT = 'tfoot';
	
	protected $extTag = TabularRow::EXT_TAG_BODY;
	protected $cells = array();
	
	/**
	 * Construct a new empty TabularRow
	 */
	function __construct() {}
	
	/**
	 * Return table html for this row
	 * @param TabularIndent $indent Indent to use
	 * @param int $level Level to indent to
	 * @return string Html friendly string
	 */
	public function html(TabularIndent $indent = null, $level = 0) {
		$html = $indent->line('<tr'.$this->htmlAttributes().'>', $level);
		foreach ($this->cells as $cell) {
			$html .= $indent->line($cell->html(), $level + 1);
		}
		$html .= $indent->line('</tr>', $level);
		return $html;
	}
	
	/**
	 * Set <b>external</b> html tag for this row
	 * Values can be
	 * - TabularRow::EXT_TAG_HEAD
	 * - TabularRow::EXT_TAG_BODY
	 * - TabularRow::EXT_TAG_FOOT
	 * @param string $extTag External tag to set
	 * @throws Exception If tag is invalid
	 * @return TabularRow This object
	 */
	public function setExtTag($extTag) {
		if ($extTag === TabularRow::EXT_TAG_HEAD) {
			$this->extTag = TabularRow::EXT_TAG_HEAD;
		} else if ($extTag === TabularRow::EXT_TAG_BODY) {
			$this->extTag = TabularRow::EXT_TAG_BODY;
		} else if ($extTag === TabularRow::EXT_TAG_FOOT) {
			$this->extTag = TabularRow::EXT_TAG_FOOT;
		} else {
			throw new Exception('Invalid tag');
		}
		return $this;
	}
	
	/**
	 * @return string External html tag of this row
	 */
	public function getExtTag() {
		return $this->extTag;
	}
	
	/**
	 * Push a new cell
	 * @param mixed $data Cell data
	 * @param TabularFormat $format TabularFormat for this cell
	 * @return TabularCell Pushed cell
	 */
	public function pushCell($data, TabularFormat $format = null) {
		if ($this->extTag === TabularRow::EXT_TAG_HEAD) {
			return $this->pushCellHead($data, $format);
		} else {
			return $this->pushCellData($data, $format);
		}
	}
	
	/**
	 * Push a new cell with TabularCell::TAG_TH
	 * @param mixed $data Cell data
	 * @param TabularFormat $format TabularFormat for this cell
	 * @return TabularCell Pushed cell
	 */
	public function pushCellHead($data, TabularFormat $format = null) {
		$cell = new TabularCell($data, $format);
		$cell->setTag(TabularCell::TAG_TH);
		$this->cells[] = $cell;
		return $cell;
	}
	
	/**
	 * Push a new cell with TabularCell::TAG_TD
	 * @param mixed $data Cell data
	 * @param TabularFormat $format TabularFormat for this cell
	 * @return TabularCell Pushed cell
	 */
	public function pushCellData($data, TabularFormat $format = null) {
		$cell = new TabularCell($data, $format);
		$cell->setTag(TabularCell::TAG_TD);
		$this->cells[] = $cell;
		return $cell;
	}
	
	/**
	 * Return last pushed cell and remove it
	 * Returns null if empty
	 * @return TabularCell Popped cell or null
	 */
	public function popCell() {
		return array_pop($this->cells);
	}
	
	/**
	 * Return cell array for this row
	 * @return array TabularCell array
	 */
	public function getCellArray() {
		return $this->cells;
	}
	
	/**
	 * Return last pushed cell without removing it
	 * Returns null if empty
	 * @return TabularCell Peeked cell or null
	 */
	public function peekCell() {
		$cell = end($this->cells);
		if ($cell === false) return null;
		reset($this->cells);
		return $cell;
	}
	
	/**
	 * Serialize this TabularRow
	 * @return string Serialized string
	 */
	public function serialize() {
		$data = array();
		$p = parent::serialize();
		$t = null;
		switch ($this->extTag) {
			case TabularRow::EXT_TAG_HEAD: $t = 1; break;
			case TabularRow::EXT_TAG_FOOT: $t = 2; break;
		}
		
		if (!empty($this->cells)) $data[0] = $this->cells;
		if (!empty($t))           $data[1] = $t;
		if (!empty($p))           $data[2] = $p;
		
		return empty($data)?'':serialize($data);
	}
	
	/**
	 * Unserialize into this TabularRow
	 * @param string $serialized Serialized string
	 */
	public function unserialize($serialized) {
		$this->__construct();
		if (!($data = unserialize($serialized))) return;
		$t = null;
		
		if (!empty($data[0])) $this->cells =      $data[0];
		if (!empty($data[1])) $t =                $data[1];
		if (!empty($data[2])) parent::unserialize($data[2]);
		
		switch ($t) {
			case 1: $this->extTag = TabularRow::EXT_TAG_HEAD; break;
			case 2: $this->extTag = TabularRow::EXT_TAG_FOOT; break;
		}
	}
	
	/**
	 * Count the number of cells
	 * @return int Number of cells
	 */
	public function count() {
		return count($this->cells);
	}
}
