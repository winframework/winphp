<?php

namespace Win\Database;

use PDO;
use PHPUnit\Framework\TestCase;
use Win\Database\Connection\Type\Mysql;
use Win\Database\DbConfig;
use Win\Mvc\Application;
use Win\Mvc\ApplicationTest;

class ConnectionTest extends TestCase {

	/** @var Mysql */
	private static $connection = null;

	/** @var Mysql */
	private static $connectionWrongUser = null;

	/** @var Mysql */
	private static $connectionWrongDb = null;

	/** @var Mysql */
	private static $connectionWrongPass = null;

	public static function setUpBeforeClass() {
		static::instance();
		static::connect();
		static::dropTable();
		static::createTable();
	}

	public static function tearDownAfterClass() {
		static::dropTable();
	}

	/** Instancia */
	public static function instance() {
		static::$connection = Mysql::instance();
		static::$connectionWrongUser = Mysql::instance('wrongUser');
		static::$connectionWrongDb = Mysql::instance('wrongDb');
		static::$connectionWrongPass = Mysql::instance('wrongPass');
	}

	/** Conexões */
	public static function connect() {
		static::$connection->connect(DbConfig::valid());
		static::$connectionWrongUser->connect(DbConfig::wrongUser());
		static::$connectionWrongDb->connect(DbConfig::wrongDb());
		static::$connectionWrongPass->connect(DbConfig::wrongPass());
	}

	public static function createTable() {
		$query = 'CREATE TABLE IF NOT EXISTS `child` (`age` int(11) NOT NULL)';
		static::$connection->query($query);
	}

	public static function dropTable() {
		$query = 'DROP TABLE `child`';
		static::$connection->query($query);
	}

	public function testIsValid() {
		$this->assertFalse(static::$connectionWrongUser->isValid());
		$this->assertFalse(static::$connectionWrongPass->isValid());
		$this->assertFalse(static::$connectionWrongDb->isValid());
		$this->assertTrue(static::$connection->isValid());
	}

	public function testGetPdo() {
		$this->assertTrue(static::$connection->getPdo() instanceof PDO);
		$this->assertNull(static::$connectionWrongUser->getPdo());
	}

	public function testValidate_DoesNothing() {
		ApplicationTest::newApp();
		static::$connection->validate();
		$this->assertEquals('index', Application::app()->getPage());
	}

	/** @expectedException \Win\Mvc\HttpException */
	public function testValidate_ThrowException() {
		ApplicationTest::newApp();
		static::$connectionWrongDb->validate();
	}

	public function testMultipleInstance() {
		$db = DbConfig::valid();
		$db['dbname'] = 'mysql';
		Mysql::instance('mysql2')->connect($db);

		$db['dbname'] = 'wrong-database';
		Mysql::instance('mysql3')->connect($db);

		$this->assertTrue(Mysql::instance()->isValid());
		$this->assertFalse(Mysql::instance('mysql3')->isValid());
		$this->assertTrue(Mysql::instance('mysql2')->isValid());
		$this->assertFalse(Mysql::instance('mysql4')->isValid());
	}

	public function testSintaxError() {
		$success = static::$connection->query('SELECT * FROM ASDF');
		$this->assertFalse($success);
	}

	public function testTruncate() {
		$query = 'TRUNCATE TABLE child';
		$success = static::$connection->query($query);
		$this->assertTrue($success);
	}

	public function testInsert() {
		$query = 'INSERT INTO child VALUES(?)';
		$success = static::$connection->query($query, [10]);
		$this->assertTrue($success);
	}

	public function testSelect() {
		$this->testInsert();
		$query = 'SELECT * FROM child WHERE age = ?';
		$rows = static::$connection->fetchAll($query, [10]);
		$this->assertCount(2, $rows);
		$this->assertEquals(10, $rows[0]['age']);
	}

	public function testUpdate() {
		$this->testInsert();
		$query = 'UPDATE child SET age = ? WHERE age = ? LIMIT 1';
		$success = static::$connection->query($query, [1, 10]);
		$rows = static::$connection->fetchAll('SELECT * FROM child WHERE age = ?', [1]);

		$this->assertTrue($success);
		$this->assertCount(1, $rows);
		$this->assertEquals(1, $rows[0]['age']);
	}

	public function testDelete() {
		$success = static::$connection->query('DELETE FROM child WHERE age = ? LIMIT 1', [10]);
		$rows = static::$connection->fetchAll('SELECT * FROM child');
		$this->assertTrue($success);
		$this->assertCount(2, $rows);
	}

	public function testNumRows() {
		$count1 = static::$connection->fetchCount('SELECT count(*) FROM child WHERE age = ?', [1]);
		$count2 = static::$connection->fetchCount('SELECT count(*) FROM child WHERE age >= ?', [1]);
		$this->assertEquals(1, $count1);
		$this->assertEquals(2, $count2);
	}

}
