<?php

namespace Win\Models;

use Win\Request\HttpException;

abstract class Model
{
	/** @var int|null */
	public $id;

	abstract public function validate();
}
