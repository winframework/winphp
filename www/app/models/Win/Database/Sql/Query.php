<?php

namespace Win\Database\Sql;

use Win\Database\Connection;
use Win\Database\Orm\Repository;

/**
 * SELECT, UPDATE, DELETE, etc
 */
abstract class Query {

	/** @var Connection */
	protected $connection;

	/** @var Repository */
	protected $repo;

	/** @var string */
	protected $table;

	/**
	 * @param Repository $repo
	 */
	public function __construct(Repository $repo) {
		$this->repo = $repo;
		$this->table = $this->repo->getTable();
		$this->connection = $repo::getConnection();
	}

	/** @return string */
	abstract protected function toString();

	abstract public function execute();

	/** @return string */
	public function __toString() {
		if ($this->repo->getDebugMode()) {
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
