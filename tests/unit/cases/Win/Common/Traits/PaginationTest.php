<?php

namespace Win\Common;

use PHPUnit\Framework\TestCase;

class PaginationTest extends TestCase
{
	public function testSetPage()
	{
		$PAGE_SIZE = 10;
		$CURRENT = 5;

		$p = new Pagination();
		$p->setPage($PAGE_SIZE, $CURRENT);

		$this->assertEquals($PAGE_SIZE, $p->pageSize());
		$this->assertEquals($CURRENT, $p->current());
	}

	public function testSetCount()
	{
		$PAGE_SIZE = 10;
		$CURRENT = 5;
		$COUNT = 10;
		$p = new Pagination();
		$p->setPage($PAGE_SIZE, $CURRENT);
		$p->setCount($COUNT);

		$this->assertEquals($COUNT, $p->count());
		$this->assertEquals(1, $p->current());
	}

	public function testOffset()
	{
		$PAGE_SIZE = 10;
		$CURRENT = 5;
		$p = new Pagination();
		$p->setPage($PAGE_SIZE, $CURRENT);

		$this->assertEquals(40, $p->offset());
	}

	public function testFirst()
	{
		$p = new Pagination();
		$this->assertEquals(1, $p->first());
	}

	public function testLast()
	{
		$PAGE_SIZE = 10;
		$CURRENT = 5;
		$COUNT = 100;
		$p = new Pagination();
		$p->setPage($PAGE_SIZE, $CURRENT);
		$p->setCount($COUNT);
		$this->assertEquals(10, $p->last());
	}

	public function testPrev()
	{
		$PAGE_SIZE = 10;
		$CURRENT = 5;
		$COUNT = 100;
		$p = new Pagination();
		$p->setPage($PAGE_SIZE, $CURRENT);
		$p->setCount($COUNT);
		$this->assertEquals(4, $p->prev());

		$p->setPage($PAGE_SIZE, 1);
		$this->assertEquals(1, $p->prev());
	}

	public function testNext()
	{
		$PAGE_SIZE = 10;
		$CURRENT = 5;
		$COUNT = 100;
		$p = new Pagination();
		$p->setPage($PAGE_SIZE, $CURRENT);
		$p->setCount($COUNT);
		$this->assertEquals(6, $p->next());

		$p->setPage($PAGE_SIZE, 100);
		$this->assertEquals(10, $p->next());
	}
}
