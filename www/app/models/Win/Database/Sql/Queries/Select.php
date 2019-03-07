<?php

namespace Win\Database\Sql\Queries;

use Win\Database\RepositoryInterface;
use Win\Database\Sql\Clauses\Limit;
use Win\Database\Sql\Clauses\OrderBy;
use Win\Database\Sql\Clauses\Where;
use Win\Database\Sql\Query;

/**
 * SELECT * FROM
 */
class Select extends Query
{
	/** @var string[] */
	public $columns;

	/** @var Where */
	protected $where;

	/** @var Limit */
	protected $limit;

	/** @var OrderBy */
	protected $orderBy;

	/**
	 * Prepara o select
	 * @param RepositoryInterface $repository
	 */
	public function __construct(RepositoryInterface $repository)
	{
		parent::__construct($repository);
		$this->init();
	}

	/**
	 * Inicia os atributos padrão
	 */
	protected function init()
	{
		$this->columns = ['*'];
		$this->where = new Where();
		$this->limit = new Limit();
		$this->orderBy = new OrderBy();
	}

	/** @return string */
	public function toString()
	{
		return 'SELECT ' . implode(',', $this->columns) . ' FROM '
				. $this->table
				. $this->where
				. $this->orderBy
				. $this->limit;
	}

	/** @return mixed[] */
	public function getValues()
	{
		return $this->where->values();
	}

	/**
	 * Retorna o total de resultados da busca
	 * @return int
	 */
	public function count()
	{
		$this->columns = ['count(*)'];
		$stmt = $this->conn->stmt($this, $this->getValues());
		$this->init();

		return $stmt->fetchColumn();
	}

	/** @return mixed[] */
	public function execute()
	{
		$all = $this->conn->fetchAll($this, $this->getValues());
		$this->init();

		return $all;
	}

	/** @param string $orderBy */
	public function orderBy($orderBy)
	{
		$this->orderBy->set($orderBy);
	}

	/** @return Where */
	public function where()
	{
		return $this->where;
	}

	/** @param int Limit */
	public function limit($limit)
	{
		$this->limit->set($limit);
	}
}
