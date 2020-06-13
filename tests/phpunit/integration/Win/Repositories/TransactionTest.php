<?php

namespace Win\Repositories;

use App\Models\Page;
use App\Repositories\PageOrm;
use PHPUnit\Framework\TestCase;
use Win\Repositories\Database\DbConfig;
use Win\Repositories\Database\Mysql;
use Win\Repositories\Database\Transaction;

class TransactionTest extends TestCase
{
	public static function setUpBeforeClass()
	{
		PageOrmTest::$conn = new Mysql(DbConfig::valid());
	}

	public function setUp()
	{
		PageOrmTest::createTable();
		PageOrmTest::importTable();
	}

	public function testTransactionCommit()
	{
		$orm = new PageOrm(PageOrmTest::$conn);
		$t = new Transaction(PageOrmTest::$conn);
		$count = $orm->count();
		$orm->save(new Page());

		$this->assertEquals($count + 1, $orm->count());
		$t->commit();
		$this->assertEquals($count + 1, $orm->count());
	}

	public function testTransactionRollback()
	{
		$orm = new PageOrm(PageOrmTest::$conn);
		$t = new Transaction(PageOrmTest::$conn);
		$count = $orm->count();
		$orm->save(new Page());

		$this->assertEquals($count + 1, $orm->count());
		$t->rollback();
		$this->assertEquals($count, $orm->count());
	}
}
