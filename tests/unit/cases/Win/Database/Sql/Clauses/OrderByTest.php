<?php

namespace Win\Database\Sql;

use PHPUnit\Framework\TestCase;
use Win\Database\Sql\Clauses\OrderBy;

class OrderByTest extends TestCase
{
	public function testSet()
	{
		$order = new OrderBy();
		$order->set('Id DESC');
		$this->assertEquals('ORDER BY Id DESC', trim((string) $order));
	}

	public function testAdd()
	{
		$order = new OrderBy();
		$order->add('Name DESC');

		$this->assertEquals(
			'ORDER BY Name DESC',
			trim((string) $order)
		);
	}

	public function testAddWithPriority()
	{
		$order = new OrderBy();
		$order->add('Name DESC', 1);
		$order->add('Id ASC', 0);

		$this->assertEquals(
			'ORDER BY Id ASC, Name DESC',
			trim((string) $order)
		);
	}

	public function testReset()
	{
		$order = new OrderBy();
		$order->add('Name DESC', 1);
		$order->reset();

		$this->assertEquals('', trim((string) $order));
	}
}
