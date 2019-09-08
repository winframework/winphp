<?php

namespace Win\Database\Sql;

use Win\Database\Sql\Statements\Delete;
use Win\Database\Sql\Statements\Insert;
use Win\Database\Sql\Statements\Select;
use Win\Database\Sql\Statements\SelectCount;
use Win\Database\Sql\Statements\Update;

abstract class Statement
{
	/** @var Query */
	protected $query;

	/** @return string */
	abstract public function __toString();

	/** @return array */
	abstract public function getValues();

	/**
	 * @param Query $query
	 */
	public function __construct(Query $query)
	{
		$this->query = $query;
	}

	/**
	 * Cria um  Statement
	 * @param string $statementType
	 * @param Query $query
	 */
	public static function factory($statementType, $query)
	{
		switch ($statementType) {
			case 'SELECT':
				return new Select($query);
			break;
			case 'SELECT COUNT':
				return new SelectCount($query);
			break;
			case 'UPDATE':
				return new Update($query);
			break;
			case 'INSERT':
				return new Insert($query);
			break;
			case 'DELETE':
				return new Delete($query);
			break;
		}
	}
}
