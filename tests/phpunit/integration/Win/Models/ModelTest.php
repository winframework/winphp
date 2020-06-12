<?php

namespace Win\Models;

use PHPUnit\Framework\TestCase;

class ModelTest extends TestCase
{
	public function testDefaultId()
	{
		$model = new MyModel();

		$this->assertNull($model->id);
	}
}
