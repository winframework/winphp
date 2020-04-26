<?php

namespace Win\Models;

use Win\Request\HttpException;

abstract class Model
{
	/** @var int|null */
	public $id;

	abstract public function validate();

	/**
	 * Retorna o model ou define página 404
	 * @return static
	 */
	public function or404()
	{
		if (!isset($this->id)) {
			throw new HttpException('Model not found', 404);
		}

		return $this;
	}
}
