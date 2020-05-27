<?php

namespace Win\Models;

use Win\Request\HttpException;

abstract class Model
{
	/** @var int|null */
	public $id;

	abstract public function validate();

	/** @return boolean */
	public function exists()
	{
		return isset($this->id);
	}

	/**
	 * Retorna o model ou define página 404
	 * @return static
	 */
	public function or404()
	{
		if (!$this->exists()) {
			throw new HttpException('Model not found', 404);
		}

		return $this;
	}
}
