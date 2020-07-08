<?php

namespace Win\Common;

use PHPUnit\Framework\TestCase;

class PaginationTest extends TestCase
{

	public function testOffset()
	{
		$p = new Pagination();
		$p->pageSize = 10;
		$p->current = 5;
		$p->count = 400;

		$this->assertEquals(40, $p->offset());
	}

	public function testOffsetEmpty()
	{
		$p = new Pagination();
		$p->current = 5;
		$p->pageSize = 0;
		$p->count = 0;
		$this->assertEquals(0, $p->offset());
	}

	public function testLast()
	{
		$p = new Pagination();
		$p->pageSize = 10;
		$p->current = 5;
		$count = 100;
		$p->count = $count;
		$p->offset();
		$this->assertEquals(10, $p->last);
	}
}
