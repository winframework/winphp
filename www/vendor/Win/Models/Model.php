<?php

namespace Win\Models;

abstract class Model
{
	public ?int $id = null;

	abstract public function validate();
}
