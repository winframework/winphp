<?php

namespace Win\Database\Sql\Query;

use Win\Database\Sql\Query;

/**
 * INSERT INTO
 */
class Insert extends Query {

	/** @var mixed[] */
	protected $mapRow;

	public function __construct($dao) {
		parent::__construct($dao);
		$this->mapRow = $dao->mapRow($dao->getModel());
	}

	public function toString() {
		$keys = array_keys($this->mapRow);
		$params = str_split(str_repeat('?', count($keys)));
		return 'INSERT INTO ' .
				$this->table .
				' (' . implode(',', $keys) . ')' .
				' VALUES (' . implode(', ', $params) . ')';
	}

	public function getValues() {
		return array_values($this->mapRow);
	}

}
