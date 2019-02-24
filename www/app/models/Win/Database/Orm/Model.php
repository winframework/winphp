<?php

namespace Win\Database\Orm;

use Win\Database\Orm;

interface Model
{
	/** @return int */
	public function getId();

	/** @param int $id */
	public function setId($id);

	/** @return Orm */
	public static function orm();
}