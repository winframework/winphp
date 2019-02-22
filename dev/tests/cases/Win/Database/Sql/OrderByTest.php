<?php

namespace Win\Database;

use PHPUnit\Framework\TestCase;
use Win\Database\Sql\Clause\OrderBy;

class OrderByTest extends TestCase
{
	public function testConstructor()
	{
		$order = new OrderBy();
		$this->assertEquals(' ORDER BY id ASC', (string) $order);
	}

	public function testSet()
	{
		$order = new OrderBy();
		$order->set('name DESC');
		$this->assertEquals(' ORDER BY name DESC', (string) $order);

		$order->set(null);
		$this->assertEquals('', (string) $order);
	}
}
