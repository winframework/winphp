<?php

namespace Win\Core\Models;

use Win\Core\Response\ResponseException;

abstract class Model
{
	/** @var int */
	public $id = null;

	abstract public function validate();

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
