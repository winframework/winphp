<?php

namespace Win\Repositories;

use PDO;
use PHPUnit\Framework\TestCase;

class MysqlTest extends TestCase
{
	public function testIsValid()
	{
		$pdo = DbConfig::valid();
		$this->assertInstanceOf(PDO::class, $pdo);
	}

	/** @expectedException Win\Repositories\DbException */
	public function testErrorConnection()
	{
		DbConfig::wrongDb();
	}
}
