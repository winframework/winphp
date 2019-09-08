<?php

namespace Win\Database\Sql;

use Exception;
use PHPUnit\Framework\TestCase;
use Win\Database\Orm\Page\PageOrm;
use Win\Database\Sql\Statements\Delete;

class StatementTest extends TestCase
{
	public function testFactory()
	{
		$orm = new PageOrm();
		$delete = Statement::factory('DELETE', new Query($orm));
		$this->assertInstanceOf(Delete::class, $delete);
	}

	/** @expectedException Exception */
	public function testFactoryInvalid()
	{
		$orm = new PageOrm();
		$delete = Statement::factory('INVALID', new Query($orm));
		$this->assertNull($delete);
	}
}
