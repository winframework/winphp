<?php

namespace Win\Database\Sql;

use Exception;
use Win\Database\Sql\Builders\Delete;
use Win\Database\Sql\Builders\Insert;
use Win\Database\Sql\Builders\Raw;
use Win\Database\Sql\Builders\Select;
use Win\Database\Sql\Builders\SelectCount;
use Win\Database\Sql\Builders\Update;

abstract class Builder
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
	 * Cria um SQL Builder
	 * @param string $statementType
	 * @param Query $query
	 * @return Builder
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
			case 'RAW':
				return new Raw($query);
			default:
				throw new Exception($statementType . ' is not a valid Statement Type ');
		}
	}
}
