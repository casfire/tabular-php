<?php

require_once(dirname(__FILE__).'/TabularIndent.php');
require_once(dirname(__FILE__).'/TabularHTML.php');
require_once(dirname(__FILE__).'/TabularCell.php');
require_once(dirname(__FILE__).'/TabularRow.php');

/**
 * 
 * Create and manage html tabular data
 * Date written: 2014-07-14
 *
 */
class Tabular extends TabularHTML implements Serializable, Countable {
	
	protected $caption = null;
	protected $rows = array();
	
	/**
	 * Construct a new empty Tabular object
	 * @param string $caption Name of the data
	 */
	public function __construct($caption = null) {
		$this->caption = $caption;
	}
	
	/**
	 * Return table html for this tabular data
	 * @param TabularIndent $indent Indent to use
	 * @param int $level Level to indent to
	 * @return string Html friendly string
	 */
	public function html(TabularIndent $indent = null, $level = 0) {
		if ($indent === null) $indent = new TabularIndent();
		if ($indent === '') {
			$indent = new TabularIndent();
			$indent->EOL = '';
			$indent->prefix = '';
			$indent->indent = '';
		}
		$html = $indent->line('<table'.$this->htmlAttributes().'>', $level);
		if (!empty($this->caption)) {
			$html .= $indent->line('<caption>'.htmlspecialchars($this->caption).'</caption>', $level + 1);
		}
		$currentExtTag = null;
		foreach ($this->rows as $row) {
			if ($row->getExtTag() !== $currentExtTag) {
				if ($currentExtTag !== null) {
					$html .= $indent->line('</'.$currentExtTag.'>', $level + 1);
				}
				$currentExtTag = $row->getExtTag();
				$html .= $indent->line('<'.$currentExtTag.'>', $level + 1);
			}
			$html .= $row->html($indent, $level + 2);
		}
		if ($currentExtTag !== null) {
			$html .= $indent->line('</'.$currentExtTag.'>', $level + 1);
		}
		$html .= $indent->line('</table>', $level);
		return $html;
	}
	
	/**
	 * Alias of pushRowBody
	 * @return TabularRow Pushed row
	 */
	public function pushRow() {
		return $this->pushRowBody();
	}
	
	/**
	 * Push a new row with TabularRow::EXT_TAG_HEAD
	 * @return TabularRow Pushed row
	 */
	public function pushRowHead() {
		$row = new TabularRow();
		$row->setExtTag(TabularRow::EXT_TAG_HEAD);
		$this->rows[] = $row;
		return $row;
	}
	
	/**
	 * Push a new row with TabularRow::EXT_TAG_BODY
	 * @return TabularRow Pushed row
	 */
	public function pushRowBody() {
		$row = new TabularRow();
		$row->setExtTag(TabularRow::EXT_TAG_BODY);
		$this->rows[] = $row;
		return $row;
	}
	
	/**
	 * Push a new row with TabularRow::EXT_TAG_FOOT
	 * @return TabularRow Pushed row
	 */
	public function pushRowFoot() {
		$row = new TabularRow();
		$row->setExtTag(TabularRow::EXT_TAG_FOOT);
		$this->rows[] = $row;
		return $row;
	}
	
	/**
	 * Return last pushed row and remove it
	 * Returns null if empty
	 * @return TabularRow Popped row or null
	 */
	public function popRow() {
		return array_pop($this->rows);
	}
	
	/**
	 * Return the row array for this Tabular
	 * @return array TabularRow array
	 */
	public function getRowArray() {
		return $this->rows;
	}
	
	/**
	 * Return last pushed row without removing it
	 * Returns null if empty
	 * @return TabularRow Peeked row or null
	 */
	public function peekRow() {
		$row = end($this->rows);
		if ($row === false) return null;
		reset($this->rows);
		return $row;
	}
	
	/**
	 * Push a new cell into last row
	 * @param mixed $data Cell data
	 * @param TabularFormat $format TabularFormat for this cell
	 * @return TabularCell Pushed cell
	 */
	function pushCell($data, TabularFormat $format = null) {
		$row = $this->peekRow();
		if ($row === null) $row = $this->pushRow();
		return $row->pushCell($data, $format);
	}
	
	/**
	 * Push a new cell with TabularCell::TAG_TH into last row
	 * @param mixed $data Cell data
	 * @param TabularFormat $format TabularFormat for this cell
	 * @return TabularCell Pushed cell
	 */
	function pushCellHead($data, TabularFormat $format = null) {
		$row = $this->peekRow();
		if ($row === null) $row = $this->pushRow();
		return $row->pushCellHead($data);
	}
	
	/**
	 * Push a new cell with TabularCell::TAG_TD into last row
	 * @param mixed $data Cell data
	 * @param TabularFormat $format TabularFormat for this cell
	 * @return TabularCell Pushed cell
	 */
	function pushCellData($data, TabularFormat $format = null) {
		$row = $this->peekRow();
		if ($row === null) $row = $this->pushRow();
		return $row->pushCellData($data);
	}
	
	/**
	 * Return last pushed cell and remove it
	 * Returns null if empty
	 * @return TabularCell Popped cell or null
	 */
	function popCell() {
		$row = $this->peekRow();
		if ($row === null) return null;
		return $row->popCell();
	}
	
	/**
	 * Return last pushed cell without removing it
	 * Returns null if empty
	 * @return TabularCell Peeked cell or null
	 */
	function peekCell() {
		$row = $this->peekRow();
		if ($row === null) return null;
		return $row->peekCell();
	}
	
	/**
	 * Serialize this tabular data
	 * @return string Serialized string
	 */
	public function serialize() {
		$data = array();
		$p = parent::serialize();
		
		if (!empty($this->rows))    $data[0] = $this->rows;
		if (!empty($this->caption)) $data[1] = $this->caption;
		if (!empty($p))             $data[2] = $p;
		
		return empty($data)?'':serialize($data);
	}
	
	/**
	 * Unserialize into this tabular data
	 * @param string $serialized Serialized string
	 */
	public function unserialize($serialized) {
		$this->__construct();
		if (!($data = unserialize($serialized))) return;
		
		if (!empty($data[0])) $this->rows =       $data[0];
		if (!empty($data[1])) $this->caption =    $data[1];
		if (!empty($data[2])) parent::unserialize($data[2]);
	}
	
	/**
	 * Count the number of rows
	 * @return int Number of rows
	 */
	public function count() {
		return count($this->rows);
	}
	
	/**
	 * Add default html classes
	 * @param string $prefix Prefix for the class names
	 * @param string $id ID of this table
	 * @return Tabular This object
	 */
	public function addStyle($prefix = 'tab_', $id = null) {
		$this->htmlAddClass($prefix.'table');
		if ($id !== null) $this->htmlSetID($id);
		foreach ($this->getRowArray() as $row) {
			$this->rowAddStyle($prefix, $row);
			foreach ($row->getCellArray() as $cell) {
				$this->cellAddStyle($prefix, $cell);
			}
		}
		return $this;
	}
	
	private function cellAddStyle($prefix, $cell) {
		$cell->htmlAddClass($prefix.'cell');
		switch (get_class($cell->getFormat())) {
			case 'TabularFormatCurrency':
				$cell->htmlAddClass($prefix.'cell_currency');
				break;
			case 'TabularFormatDate':
				$cell->htmlAddClass($prefix.'cell_date');
				break;
			case 'TabularFormatDateTime':
				$cell->htmlAddClass($prefix.'cell_datetime');
				break;
			case 'TabularFormatMissing':
				$cell->htmlAddClass($prefix.'cell_missing');
				break;
			case 'TabularFormatMonth':
				$cell->htmlAddClass($prefix.'cell_month');
				break;
			case 'TabularFormatQuantity':
				$cell->htmlAddClass($prefix.'cell_quantity');
				break;
			case 'TabularFormatString':
				$cell->htmlAddClass($prefix.'cell_string');
				break;
			case 'TabularFormatYear':
				$cell->htmlAddClass($prefix.'cell_year');
				break;
		}
	}
	
	private $style_count = 1;
	private $style_lastTag = null;
	
	private function rowAddStyle($prefix, $row) {
		$row->htmlAddClass($prefix.'row');
		switch ($row->getExtTag()) {
			case TabularRow::EXT_TAG_HEAD:
				$row->htmlAddClass($prefix.'row_head');
				break;
			case TabularRow::EXT_TAG_BODY:
				$row->htmlAddClass($prefix.'row_body');
				break;
			case TabularRow::EXT_TAG_HEAD:
				$row->htmlAddClass($prefix.'row_head');
				break;
		}
		if ($this->style_lastTag !== $row->getExtTag()) {
			$this->style_lastTag = $row->getExtTag();
			$this->style_count = 1;
		} else $this->style_count++;
		if ($this->style_count % 2 == 0) {
			$row->htmlAddClass($prefix.'row_even');
		} else {
			$row->htmlAddClass($prefix.'row_odd');
		}
	}
	
}
