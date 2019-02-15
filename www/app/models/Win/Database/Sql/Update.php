<?php

namespace Win\Database\Sql;

use Win\Database\Orm\Repository;

/**
 * UPDATE SET
 */
class Update extends Query {

	/** @var mixed[] */
	protected $values;

	public function __construct(Repository $repo) {
		parent::__construct($repo);
		$this->values = $repo->getRowValues();
	}

	/** @return string */
	public function toString() {
		$params = $this->getParams();
		return 'UPDATE ' . $this->table
				. ' SET ' . implode(', ', $params)
				. ' WHERE id = ? ';
	}

	/** @return string[] */
	protected function getParams() {
		$columns = array_keys($this->values);
		$params = [];
		foreach ($columns as $column) {
			$params[] = $column . ' = ?';
		}
		return $params;
	}

	/** @return mixed[] */
	public function getValues() {
		$values = array_values($this->values);
		$values[] = $this->repo->getModel()->getId();
		return $values;
	}

	/** @return boolean */
	public function execute() {
		return $this->connection->query($this, $this->getValues());
	}

}
