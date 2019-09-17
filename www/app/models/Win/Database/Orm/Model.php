<?php

namespace Win\Database\Orm;

use Win\Response\ResponseException;

abstract class Model
{
	public $id = null;

	abstract public static function orm();

	/**
	 * Retorna o model ou define página 404
	 * @return static
	 */
	public function or404()
	{
		if (is_null($this->id)) {
			throw new ResponseException('Model not found', 404);
		}

		return $this;
	}
}
