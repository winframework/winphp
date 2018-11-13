<?php

namespace Win\Database\Sql;

use Win\Database\Orm\Repository;

/**
 * INSERT INTO
 */
class Insert extends Query {

	/** @var mixed[] */
	protected $values;

	public function __construct(Repository $repo) {
		parent::__construct($repo);
		$this->values = $repo->getRowValues();
	}

	/** @return string */
	public function toString() {
		$collumns = array_keys($this->values);
		$params = $this->getParams();
		return 'INSERT INTO ' .
				$this->table .
				' (' . implode(',', $collumns) . ')' .
				' VALUES (' . implode(', ', $params) . ')';
	}

	/**
	 * @return string[]
	 * @example return ['?','?','?']
	 */
	protected function getParams() {
		return str_split(str_repeat('?', count($this->values)));
	}

	/** @return mixed[] */
	public function getValues() {
		return array_values($this->values);
	}

	/** @return boolean */
	public function execute() {
		return $this->connection->query($this, $this->getValues());
	}

}
