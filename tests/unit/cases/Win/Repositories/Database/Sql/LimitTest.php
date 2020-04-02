<?php

namespace Win\Repositories\Database\Sql;

use PHPUnit\Framework\TestCase;

class LimitTest extends TestCase
{
	public function testSet()
	{
		$limit = new Limit();
		$limit->set('10', 1);
		$this->assertEquals('LIMIT 10,1', trim((string) $limit));
	}

	public function testReset()
	{
		$limit = new Limit();
		$limit->set('10', 1);
		$limit->reset();
		$this->assertEquals('', trim((string) $limit));
	}
}
