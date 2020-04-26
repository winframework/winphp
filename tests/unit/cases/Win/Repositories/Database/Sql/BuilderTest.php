<?php

namespace Win\Repositories\Database\Sql;

use Exception;
use App\Repositories\PageOrm;
use PHPUnit\Framework\TestCase;
use Win\Repositories\Database\Sql\Builders\Delete;

class BuilderTest extends TestCase
{
	public function testFactory()
	{
		$orm = new PageOrm();
		$delete = Builder::factory(Builder::DELETE, new Query($orm));
		$this->assertInstanceOf(Delete::class, $delete);
	}

	/** @expectedException Exception */
	public function testFactoryInvalid()
	{
		$orm = new PageOrm();
		$delete = Builder::factory('INVALID', new Query($orm));
		$this->assertNull($delete);
	}
}
