<?php

namespace Win\Database\Orm;

use Win\Database\Orm;
use Win\Mvc\Application;

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
			Application::app()->page404();
		}

		return $this;
	}
}
