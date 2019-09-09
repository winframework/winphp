<?php

namespace Win\Database\Sql;

use Exception;
use PHPUnit\Framework\TestCase;
use Win\Database\Orm\Page\PageOrm;
use Win\Database\Sql\Builders\Delete;

class BuilderTest extends TestCase
{
	public function testFactory()
	{
		$orm = new PageOrm();
		$delete = Builder::factory('DELETE', new Query($orm));
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
