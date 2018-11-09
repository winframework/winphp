<?php

namespace Win\Database\Sql;

/**
 * SELECT, UPDATE, DELETE, etc
 */
abstract class Query {

	/** @var string */
	protected $table;

	/** @var Where */
	public $where;

	/** @var Limit */
	public $limit;

	public function __construct($table) {
		$this->table = $table;
		$this->where = new Where();
		$this->limit = new Limit();
	}

	abstract public function __toString();

	/** Exibe informações de debug */
	public function showDebug($debugMode = true) {
		if ($debugMode) {
			print_r('<pre>');
			print_r((string) $this);
			print_r('</pre>');
		}
	}

}
