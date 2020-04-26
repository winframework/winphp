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

	public function testOr404()
	{
		$model = new MyModel();
		$model->id = 1;
		$model->or404();
	}

	/** @expectedException Win\Request\HttpException */
	public function testOr404Redirect()
	{
		$model = new MyModel();
		$model->or404();
	}
}
