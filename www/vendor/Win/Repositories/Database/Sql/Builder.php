<?php

namespace Win\Repositories\Database\Sql;

use Exception;
use Win\Repositories\Database\Sql\Builders\Delete;
use Win\Repositories\Database\Sql\Builders\Insert;
use Win\Repositories\Database\Sql\Builders\Raw;
use Win\Repositories\Database\Sql\Builders\Select;
use Win\Repositories\Database\Sql\Builders\SelectCount;
use Win\Repositories\Database\Sql\Builders\Update;

abstract class Builder
{
	const SELECT = Select::class;
	const SELECT_COUNT = SelectCount::class;
	const INSERT = Insert::class;
	const UPDATE = Update::class;
	const DELETE = Delete::class;
	const RAW = Raw::class;

	/** @return string */
	abstract public function __toString();

	/** @return array */
	abstract public function getValues();

	/** @var Query */
	protected $query;

	/**
	 * @param Query $query
	 */
	public function __construct(Query $query)
	{
		$this->query = $query;
	}

	/**
	 * Cria um SQL Builder
	 * @param string $builderClass
	 * @param Query $query
	 * @return Builder
	 */
	public static function factory($builderClass, $query)
	{
		if (class_exists($builderClass)) {
			return new $builderClass($query);
		}
		throw new Exception($builderClass . ' is not a valid Statement Type.');
	}
}
