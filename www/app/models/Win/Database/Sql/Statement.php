<?php

namespace Win\Database\Sql;

interface Statement
{
	public function __construct(Query $query);

	public function __toString();

	/** @return array */
	public function getValues();
}
