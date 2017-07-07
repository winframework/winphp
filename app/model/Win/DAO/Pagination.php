<?php

namespace Win\DAO;

use Win\Mvc\Block;

/**
 * Paginação
 * Auxilia busca por paginação
 */
class Pagination {

	private $total = 0;
	private $totalPerPage = null;
	private $last = null;
	private $current;

	/** Construtor */
	public function __construct($totalPerPage = null, $current = null) {
		$this->totalPerPage = $totalPerPage;
		$this->setCurrent($current);
	}

	/** @return int */
	public function totalPerPage() {
		return $this->totalPerPage;
	}

	/** @return int */
	public function current() {
		if ($this->current > $this->last() && !is_null($this->last)) {
			$this->current = $this->last();
		}

		if ($this->current < $this->first()) {
			$this->current = $this->first();
		}
		return $this->current;
	}

	/** @return int */
	public function prev() {
		return ($this->current > $this->first()) ? ($this->current - 1) : $this->first();
	}

	/** @return int */
	public function next() {
		return ($this->current < $this->last) ? ($this->current + 1) : $this->last();
	}

	/** @return int */
	public function first() {
		return 1;
	}

	/** @return int */
	public function last() {
		return $this->last;
	}

	/** @param int $total */
	public function setTotal($total) {
		if ($this->totalPerPage > 0) {
			$this->total = $total;
			$this->last = ceil($total / $this->totalPerPage);
		}
	}

	/** @param int $current */
	public function setCurrent($current) {
		if (is_null($current) && isset($_GET['p'])) {
			$current = $_GET['p'];
		}
		$this->current = $current;
	}

	/** @return string */
	public function toSql() {
		if (!is_null($this->totalPerPage)) {
			$begin = $this->totalPerPage * ($this->current() - 1);
			return ' LIMIT ' . $begin . ',' . $this->totalPerPage;
		}
		return '';
	}

	/** @return string */
	public function toHtml() {
		return (string) new Block('layout/html/pagination', ['pagination' => $this]);
	}

}
