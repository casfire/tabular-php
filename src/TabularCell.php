<?php

require_once(dirname(__FILE__).'/TabularFormat.php');
require_once(dirname(__FILE__).'/TabularHTML.php');

class TabularCell extends TabularHTML implements Serializable {
	
	const TAG_TD = 'td';
	const TAG_TH = 'th';
	
	protected $tag = TabularCell::TAG_TD;
	protected $colspan = 1;
	protected $rowspan = 1;
	protected $data = null;
	protected $format = null;
	
	/**
	 * Construct a new TabularCell
	 * @param mixed $data Data for this cell
	 * @param TabularFormat $format TabularFormat for this cell
	 */
	public function __construct($data = null, TabularFormat $format = null) {
		$this->data = $data;
		$this->setFormat($format);
	}
	
	/**
	 * Return table html for this cell
	 * @return string Html friendly string
	 */
	public function html() {
		$html = false;
		if ($this->format !== null) $html = $this->format->html($this->data);
		if (!$html) $html = htmlspecialchars($this->data);
		return '<'.$this->tag.$this->htmlAttributes().'>'.$html.'</'.$this->tag.'>';
	}
	
	/**
	 * Set html tag of this cell
	 * Values can be:
	 * - TabularCell::TAG_TD
	 * - TabularCell::TAG_TH
	 * @param string $tag Tag to set
	 * @throws Exception If tag is invalid
	 * @return TabularCell This object
	 */
	public function setTag($tag) {
		if ($tag === TabularCell::TAG_TD) {
			$this->tag = TabularCell::TAG_TD;
		} else if ($tag === TabularCell::TAG_TH) {
			$this->tag = TabularCell::TAG_TH;
		} else {
			throw new Exception('Invalid tag');
		}
		return $this;
	}
	
	/**
	 * @return string html tag of this cell
	 */
	public function getTag() {
		return $this->tag;
	}
	
	/**
	 * Set colspan for this cell
	 * @param int $colspan Positive colspan value
	 * @throws Exception If colspan is less then 1
	 * @return TabularCell This object
	 */
	public function setColspan(int $colspan) {
		if ($colspan < 1) throw new Exception('Colspan must be positive');
		$this->colspan = $colspan;
		return $this;
	}
	
	/**
	 * @return int colspan
	 */
	public function getColspan() {
		return $this->colspan;
	}
	
	/**
	 * Set rowspan for this cell
	 * @param int $rowspan Positive rowspan value
	 * @throws Exception If rowspan is less then 1
	 * @return TabularCell This object
	 */
	public function setRowspan(int $rowspan) {
		if ($rowspan < 1) throw new Exception('Rowspan must be positive');
		$this->rowspan = $rowspan;
		return $this;
	}
	
	/**
	 * @return int rowspan
	 */
	public function getRowspan() {
		return $this->rowspan;
	}
	
	/**
	 * Set data for this cell
	 * @param mixed $data Data for this cell
	 * @return TabularCell This object
	 */
	public function setData($data) {
		$this->data = $data;
		return $this;
	}
	
	/**
	 * @return mixed data
	 */
	public function getData() {
		return $this->data;
	}
	
	/**
	 * Serialize this TabularCell
	 * @return string Serialized string
	 */
	public function serialize() {
		$data = array();
		$p = parent::serialize();
		$t = null;
		if ($this->tag === TabularCell::TAG_TH) $t = 1;
		
		if (!empty($t))            $data[0] = $t;
		if ($this->colspan !== 1)  $data[1] = $this->colspan;
		if ($this->rowspan !== 1)  $data[2] = $this->rowspan;
		if ($this->data !== null)  $data[3] = $this->data;
		if (!empty($this->format)) $data[6] = $this->format;
		if (!empty($p))            $data[5] = $p;
		return empty($data)?'':serialize($data);
	}
	
	/**
	 * Unserialize into this TabularCell
	 * @param string $serialized Serialized string
	 */
	public function unserialize($serialized) {
		$this->__construct();
		if (!($data = unserialize($serialized))) return;
		$t = null;
		
		if (!empty($data[0])) $t =                $data[0];
		if (!empty($data[1])) $this->colspan =    $data[1];
		if (!empty($data[2])) $this->rowspan =    $data[2];
		if (!empty($data[3])) $this->data =       $data[3];
		if (!empty($data[6])) $this->format =     $data[6];
		if (!empty($data[5])) parent::unserialize($data[5]);
		
		if ($t === 1) $this->tag = TabularCell::TAG_TH;
	}
	
	/**
	 * @return TabularFormat Current TabularFormat or null
	 */
	public function getFormat() {
		return $this->format;
	}
	
	/**
	 * Set the TabularFormat for this cell
	 * @param TabularFormat $format TabularFormat for this cell
	 * @return TabularCell This object
	 */
	public function setFormat(TabularFormat $format = null) {
		$this->format = $format;
		return $this;
	}
	
	public function setFormatCurrency($name = null, int $precision = null) {
		require_once(dirname(__FILE__).'/formats/TabularFormatCurrency.php');
		return $this->setFormat(new TabularFormatCurrency($name, $precision));
	}
	
	public function setFormatDate($format = null) {
		require_once(dirname(__FILE__).'/formats/TabularFormatDate.php');
		return $this->setFormat(new TabularFormatDate($format));
	}
	
	public function setFormatDateTime($format = null) {
		require_once(dirname(__FILE__).'/formats/TabularFormatDateTime.php');
		return $this->setFormat(new TabularFormatDateTime($format));
	}
	
	public function setFormatMissing() {
		require_once(dirname(__FILE__).'/formats/TabularFormatMissing.php');
		return $this->setFormat(new TabularFormatMissing());
	}
	
	public function setFormatMonth($months = null) {
		require_once(dirname(__FILE__).'/formats/TabularFormatMonth.php');
		return $this->setFormat(new TabularFormatMonth($months));
	}
	
	public function setFormatQuantity($name = null) {
		require_once(dirname(__FILE__).'/formats/TabularFormatQuantity.php');
		return $this->setFormat(new TabularFormatQuantity($name));
	}
	
	public function setFormatString() {
		require_once(dirname(__FILE__).'/formats/TabularFormatString.php');
		return $this->setFormat(new TabularFormatString());
	}
	
	public function setFormatYear() {
		require_once(dirname(__FILE__).'/formats/TabularFormatYear.php');
		return $this->setFormat(new TabularFormatYear());
	}
	
}
