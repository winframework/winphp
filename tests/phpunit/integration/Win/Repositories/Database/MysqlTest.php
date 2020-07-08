<?php

namespace Win\Repositories\Database;

use PDO;
use PHPUnit\Framework\TestCase;

class MysqlTest extends TestCase
{
	public function testIsValid()
	{
		$pdo = Mysql::connect(DbConfig::valid());
		$this->assertInstanceOf(PDO::class, $pdo);
	}

	/** @expectedException Win\Repositories\Database\DbException */
	public function testErrorConnection()
	{
		Mysql::connect(DbConfig::wrongDb());
	}
}
