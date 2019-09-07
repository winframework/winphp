<?php

namespace Win\Mvc;

use PHPUnit\Framework\TestCase;

function header($header)
{
	JsonViewTest::$header = $header;
}

class JsonViewTest extends TestCase
{
	public static $header = [];

	private $data = [
		'totalResults' => '3',
		'values' => [
			['name' => 'John', 'age' => 30],
			['name' => 'Mary', 'age' => 24],
			['name' => 'Petter', 'age' => 18],
		],
	];

	public function testConstructor()
	{
		$view = new JsonView($this->data);
		$values = $view->getData('values');

		$this->assertEquals($this->data['values'][1]['name'], $values[1]['name']);
		$this->assertEquals($this->data['totalResults'], $view->getData('totalResults'));
		$this->assertEquals(
			'Content-Type: application/json',
			static::$header
		);
	}

	public function testToJson()
	{
		$view = new JsonView($this->data);
		$decoded = json_decode($view->toJson());
		$data = $view->getData('values');

		$this->assertEquals($view->getData('totalResults'), $decoded->totalResults);
		$this->assertEquals($data[1]['name'], $decoded->values[1]->name);
	}

	public function testExists()
	{
		$view = new JsonView($this->data);

		$this->assertTrue($view->exists());
	}
}
