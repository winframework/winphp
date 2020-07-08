<?php

namespace Win\Common\Traits;

class ArrClass
{
	use ArrayDotTrait;

	public function __construct($values)
	{
		$this->data = $values;
	}
}
