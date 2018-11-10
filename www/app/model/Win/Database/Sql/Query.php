<?php

namespace Win\Database\Sql;

use Win\Database\Dao\Dao;

/**
 * SELECT, UPDATE, DELETE, etc
 */
abstract class Query {

	/** @var Dao */
	protected $dao;

	/** @var string */
	protected $table;

	/**
	 * @param Dao $dao
	 */
	public function __construct(Dao $dao) {
		$this->dao = $dao;
		$this->table = $this->dao->getTable();
	}

	/** @return string */
	abstract protected function toString();

	/** @return string */
	public function __toString() {
		if ($this->dao->getDebugMode()) {
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
