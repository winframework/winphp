<?php

namespace Win\Database\Sql;

use Win\Database\Orm\Repository;

/**
 * SELECT, UPDATE, DELETE, etc
 */
abstract class Query {

	/** @var Repository */
	protected $orm;

	/** @var string */
	protected $table;

	/**
	 * @param Repository $orm
	 */
	public function __construct(Repository $orm) {
		$this->orm = $orm;
		$this->table = $this->orm->getTable();
	}

	/** @return string */
	abstract protected function toString();

	/** @return string */
	public function __toString() {
		if ($this->orm->getDebugMode()) {
			$this->debug();
		}
		return $this->toString();
	}

	/** Exibe informações de debug */
	public function debug() {
		print_r('<pre>');
		print_r((string) $this->toString());
		print_r('</pre>');
	}

}
