<?php

namespace Win\Database\Dao;

use PHPUnit_Framework_TestCase;
use Win\Database\Connection\Mysql;
use const BASE_PATH;

class DaoTest extends PHPUnit_Framework_TestCase {

	/** @var Mysql */
	private static $mysql = null;

	public static function setUpBeforeClass() {
		static::connect();
		PageDaoTest::createTable();
		PageDaoTest::importTable();
	}

	public static function connect() {
		/** @var string[] $db */
		$db = [];
		include BASE_PATH . '/app/config/database.php';

		static::$mysql = Mysql::instance();
		static::$mysql->connect($db);
	}

	public function testGetPdo() {
		$this->assertTrue(static::$mysql->isValid());
		$this->assertEquals(static::$mysql->getPdo(), Dao::getPdo());
	}

	public function testSelect() {
		$query = 'select * from page';
		$pages = Dao::select($query);
		$this->assertTrue(count($pages) > 1);
	}

	public function testInsert() {
		$query = 'insert into page (title,description) values("inserted","beleza")';
		$success = Dao::insert($query);
		$this->assertTrue($success);
	}

	public function testUpdate() {
		$query = 'update page set title = "updated" where description like "beleza"';
		$success = Dao::update($query);
		$this->assertTrue($success);
	}

	public function testDelete() {
		$query = 'delete from page where description like "beleza"';
		$success = Dao::delete($query);
		$this->assertTrue($success);
	}

	public function testSintaxError() {
		$query = 'insert into page invalidsintax';
		$success = Dao::insert($query);
		$this->assertFalse($success);
	}

}
