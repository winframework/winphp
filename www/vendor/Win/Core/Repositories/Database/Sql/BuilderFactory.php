<?php

namespace Win\Core\Repositories\Database\Sql;

use Exception;
use Win\Core\Repositories\Database\Sql\Builders\Delete;
use Win\Core\Repositories\Database\Sql\Builders\Insert;
use Win\Core\Repositories\Database\Sql\Builders\Raw;
use Win\Core\Repositories\Database\Sql\Builders\Select;
use Win\Core\Repositories\Database\Sql\Builders\SelectCount;
use Win\Core\Repositories\Database\Sql\Builders\Update;

abstract class BuilderFactory
{
	const BUILDERS = [
		'SELECT' => Select::class,
		'SELECT COUNT' => SelectCount::class,
		'INSERT' => Insert::class,
		'UPDATE' => Update::class,
		'DELETE' => Delete::class,
		'RAW' => Raw::class,
	];

	/**
	 * Cria um SQL Builder
	 * @param string $type
	 * @param Query $query
	 * @return Builder
	 */
	public static function create($type, $query)
	{
		if (key_exists($type, static::BUILDERS)) {
			$class = static::BUILDERS[$type];

			return new $class($query);
		}
		throw new Exception($type . ' is not a valid Statement Type.');
	}
}
