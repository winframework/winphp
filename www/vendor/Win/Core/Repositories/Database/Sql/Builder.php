<?php

namespace Win\Core\Repositories\Database\Sql;

use Exception;
use Win\Core\Repositories\Database\Sql\Builders\Delete;
use Win\Core\Repositories\Database\Sql\Builders\Insert;
use Win\Core\Repositories\Database\Sql\Builders\Raw;
use Win\Core\Repositories\Database\Sql\Builders\Select;
use Win\Core\Repositories\Database\Sql\Builders\SelectCount;
use Win\Core\Repositories\Database\Sql\Builders\Update;

abstract class Builder
{
	/** @var Query */
	protected $query;

	const BUILDERS = [
		'SELECT' => Select::class,
		'SELECT COUNT' => SelectCount::class,
		'INSERT' => Insert::class,
		'UPDATE' => Update::class,
		'DELETE' => Delete::class,
		'RAW' => Raw::class,
	];

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
	 * @param string $type
	 * @param Query $query
	 * @return Builder
	 */
	public static function factory($type, $query)
	{
		if (key_exists($type, static::BUILDERS)) {
			$class = static::BUILDERS[$type];

			return new $class($query);
		}
		throw new Exception($type . ' is not a valid Statement Type.');
	}
}
