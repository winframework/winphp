<?php

namespace Win\Core\Repositories\Database\Sql;

use Win\Core\Repositories\Database\Orm;

/**
 * SELECT, UPDATE, DELETE, etc
 */
class Query
{
	/** @var Orm */
	protected $orm;

	/**
	 * Responsável por gerar a Query completa
	 * @var Builder
	 */
	protected $builder;

	/** @var string */
	public $table;

	/** @var string */
	public $raw = null;

	/** @var array */
	public $values = [];

	/** @var Where */
	public $where;

	/** @var OrderBy */
	public $orderBy;

	/** @var Limit */
	public $limit;

	/**
	 * Prepara a query
	 * @param Orm $orm
	 */
	public function __construct(Orm $orm)
	{
		$this->table = $orm::TABLE;
		$this->orm = $orm;

		$this->where = new Where();
		$this->orderBy = new OrderBy();
		$this->limit = new Limit();
	}

	/**
	 * Define o builder da Query
	 * @param string $statementType
	 * @example setStatement('SELECT'|'UPDATE'|'DELETE')
	 */
	public function setBuilder($statementType)
	{
		$this->builder = Builder::factory($statementType, $this);
	}

	/** @return mixed[] */
	public function getValues()
	{
		return array_values($this->builder->getValues());
	}

	/**
	 * Retorna o comando SQL
	 * @return string
	 */
	public function __toString()
	{
		if ($this->orm->debug) {
			print_r('<pre>' . $this->builder . '<br/>');
			print_r($this->getValues());
			print_r('</pre>');
		}

		return (string) $this->builder;
	}
}
