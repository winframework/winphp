<?php

namespace Win\Database\Sql;

use Win\Database\Connection;
use Win\Database\RepositoryInterface;

/**
 * SELECT, UPDATE, DELETE, etc
 */
abstract class Query
{
	/** @var Connection */
	protected $conn;

	/** @var RepositoryInterface */
	protected $repository;

	/** @var string */
	protected $table;

	/**
	 * @param RepositoryInterface $repository
	 */
	public function __construct(RepositoryInterface $repository)
	{
		$this->repository = $repository;
		$this->table = $this->repository->getTable();
		$this->connection = $repository::getConnection();
	}

	/** @return string */
	abstract protected function toString();

	abstract public function execute();

	/** @return string */
	public function __toString()
	{
		if ($this->repository->getDebugMode()) {
			$this->debug();
		}

		return $this->toString();
	}

	/** Exibe informações de debug */
	public function debug()
	{
		print_r('<pre>');
		print_r((string) $this->toString());
		print_r('</pre>');
	}
}
