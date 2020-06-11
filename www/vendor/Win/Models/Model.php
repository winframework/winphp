<?php

namespace Win\Models;

abstract class Model
{
	/** @var int|null */
	public $id;

	abstract public function validate();
}
