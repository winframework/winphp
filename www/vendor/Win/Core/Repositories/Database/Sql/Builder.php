<?php

namespace Win\Core\Repositories\Database\Sql;

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
}
