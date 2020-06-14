<?php

namespace Win\Repositories;

use App\Models\Page;
use App\Repositories\PageRepo;
use PHPUnit\Framework\TestCase;
use Win\Repositories\Database\DbConfig;
use Win\Repositories\Database\Mysql;
use Win\Repositories\Database\Transaction;

class TransactionTest extends TestCase
{
	public static function setUpBeforeClass()
	{
		PageRepoTest::$conn = new Mysql(DbConfig::valid());
	}

	public function setUp()
	{
		PageRepoTest::createTable();
		PageRepoTest::importTable();
	}

	public function testTransactionCommit()
	{
		$orm = new PageRepo(PageRepoTest::$conn);
		$t = new Transaction(PageRepoTest::$conn);
		$count = $orm->count();
		$orm->save(new Page());

		$this->assertEquals($count + 1, $orm->count());
		$t->commit();
		$this->assertEquals($count + 1, $orm->count());
	}

	public function testTransactionRollback()
	{
		$orm = new PageRepo(PageRepoTest::$conn);
		$t = new Transaction(PageRepoTest::$conn);
		$count = $orm->count();
		$orm->save(new Page());

		$this->assertEquals($count + 1, $orm->count());
		$t->rollback();
		$this->assertEquals($count, $orm->count());
	}
}
