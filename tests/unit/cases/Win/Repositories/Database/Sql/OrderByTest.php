<?php

namespace Win\Repositories\Database\Sql;

use PHPUnit\Framework\TestCase;

class OrderByTest extends TestCase
{
	public function testSet()
	{
		$order = new OrderBy();
		$order->set('id DESC');
		$this->assertEquals('ORDER BY id DESC', trim((string) $order));
	}

	public function testAdd()
	{
		$order = new OrderBy();
		$order->add('name DESC');

		$this->assertEquals(
			'ORDER BY name DESC',
			trim((string) $order)
		);
	}

	public function testToString()
	{
		$order = new OrderBy();
		$this->assertEquals('', trim((string) $order));
	}

	public function testAddWithPriority()
	{
		$order = new OrderBy();
		$order->add('name DESC', 1);
		$order->add('id ASC', 0);

		$this->assertEquals(
			'ORDER BY id ASC, name DESC',
			trim((string) $order)
		);
	}

	public function testReset()
	{
		$order = new OrderBy();
		$order->add('name DESC', 1);
		$order->reset();

		$this->assertEquals('', trim((string) $order));
	}
}
