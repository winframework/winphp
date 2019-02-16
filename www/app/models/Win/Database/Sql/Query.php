<?php

namespace Win\Database\Sql;

use Win\Database\Connection;
use Win\Database\Orm\Orm;

/**
 * SELECT, UPDATE, DELETE, etc
 */
abstract class Query {

	/** @var Connection */
	protected $connection;

	/** @var Orm */
	protected $orm;

	/** @var string */
	protected $table;

	/**
	 * @param Orm $orm
	 */
	public function __construct(Orm $orm) {
		$this->orm = $orm;
		$this->table = $this->orm->getTable();
		$this->connection = $orm::getConnection();
	}

	/** @return string */
	abstract protected function toString();

	abstract public function execute();

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
