<?php

namespace Win\Database\Orm;

use Win\Database\Orm;
use Win\Mvc\Application;

abstract class Model
{
	public $id = null;

	/** @return Orm */
	abstract public static function orm();

	/**
	 * Retorna o model ou define página 404
	 * @return static
	 */
	public function orFail()
	{
		if (is_null($this->id)) {
			Application::app()->pageNotFound();
		}

		return $this;
	}
}
