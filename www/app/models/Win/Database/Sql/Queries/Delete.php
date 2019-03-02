<?php

namespace Win\Database\Sql\Queries;

use Win\Database\RepositoryInterface;
use Win\Database\Sql\Clauses\Limit;
use Win\Database\Sql\Clauses\Where;
use Win\Database\Sql\Query;

/**
 * DELETE FROM
 */
class Delete extends Query
{
	/** @var Where */
	protected $where;

	/** @var Limit */
	public $limit;

	public function __construct(RepositoryInterface $repository)
	{
		parent::__construct($repository);
		$this->where = new Where();
		$this->limit = new Limit();
	}

	/** @return string */
	public function toString()
	{
		return 'DELETE FROM '
				. $this->table
				. $this->where
				. $this->limit;
	}

	/** @return mixed[] */
	public function getValues()
	{
		return $this->where->values();
	}

	/** @return bool */
	public function execute()
	{
		return $this->conn->query($this, $this->getValues());
	}

	/** @return Where */
	public function where()
	{
		return $this->where;
	}
}
