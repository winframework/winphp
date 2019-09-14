<?php

namespace Win\Database\Orm;

use Win\Database\Orm;
use Win\Response\ErrorResponse;

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
			throw new ErrorResponse(404, 'Model not found');
		}

		return $this;
	}
}
