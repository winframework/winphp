<?php

namespace Win\Common;

use PHPUnit\Framework\TestCase;

class PaginationTest extends TestCase
{
	public function testSetPage()
	{
		$pageSize = 10;
		$current = 5;

		$p = new Pagination($pageSize, $current);

		$this->assertEquals($pageSize, $p->pageSize);
		$this->assertEquals($current, $p->current);
	}

	public function testSetCount()
	{
		$pageSize = 10;
		$current = 5;
		$count = 10;
		$p = new Pagination($pageSize, $current);
		$p->setCount($count);

		$this->assertEquals($count, $p->count);
		$this->assertEquals(1, $p->current);
	}

	public function testOffset()
	{
		$pageSize = 10;
		$current = 5;
		$p = new Pagination($pageSize, $current);
		$p->setCount(400);

		$this->assertEquals(40, $p->offset);
	}

	public function testLast()
	{
		$pageSize = 10;
		$current = 5;
		$count = 100;
		$p = new Pagination($pageSize, $current);
		$p->setCount($count);
		$this->assertEquals(10, $p->last);
	}

	public function testPrev()
	{
		$pageSize = 10;
		$current = 5;
		$count = 100;
		$p = new Pagination($pageSize, $current);
		$p->setCount($count);
		$this->assertEquals(4, $p->prev);

		$p = new Pagination($pageSize, 1);
		$p->setCount($count);
		$this->assertEquals(1, $p->prev);
	}

	public function testNext()
	{
		$pageSize = 10;
		$current = 5;
		$count = 100;
		$p = new Pagination($pageSize, $current);
		$p->setCount($count);
		$this->assertEquals(6, $p->next);

		$p = new Pagination($pageSize, 100);
		$p->setCount($count);
		$this->assertEquals(10, $p->next);
	}
}
