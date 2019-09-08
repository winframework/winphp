<?php

namespace Win\Database\Sql;

use Exception;
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
			case 'SELECT COUNT':
				return new SelectCount($query);
			case 'UPDATE':
				return new Update($query);
			case 'INSERT':
				return new Insert($query);
			case 'DELETE':
				return new Delete($query);
			default:
				throw new Exception($statementType . ' is not a valid Statement Type ');
		}
	}
}
