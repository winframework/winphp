<?php

namespace Win\Database;

use PDO;
use PHPUnit\Framework\TestCase;
use Win\Database\Connections\MysqlConnection as Connection;
use Win\Mvc\Application;
use Win\Mvc\ApplicationTest;

class ConnectionTest extends TestCase
{
	/** @var connection */
	private static $connection = null;

	/** @var connection */
	private static $connectionWrongUser = null;

	/** @var connection */
	private static $connectionWrongDb = null;

	/** @var connection */
	private static $connectionWrongPass = null;

	public static function setUpBeforeClass()
	{
		static::instance();
		static::connect();
		static::dropTable();
		static::createTable();
	}

	public static function tearDownAfterClass()
	{
		static::dropTable();
	}

	/** Instancia */
	public static function instance()
	{
		static::$connection = connection::instance();
		static::$connectionWrongUser = connection::instance('wrongUser');
		static::$connectionWrongDb = connection::instance('wrongDb');
		static::$connectionWrongPass = connection::instance('wrongPass');
	}

	/** Conexões */
	public static function connect()
	{
		static::$connection->connect(DbConfig::valid());
		static::$connectionWrongUser->connect(DbConfig::wrongUser());
		static::$connectionWrongDb->connect(DbConfig::wrongDb());
		static::$connectionWrongPass->connect(DbConfig::wrongPass());
	}

	public static function createTable()
	{
		$query = 'CREATE TABLE IF NOT EXISTS `child` (`age` int(11) NOT NULL)';
		static::$connection->query($query);
	}

	public static function dropTable()
	{
		$query = 'DROP TABLE `child`';
		static::$connection->query($query);
	}

	public function testIsValid()
	{
		$this->assertFalse(static::$connectionWrongUser->isValid());
		$this->assertFalse(static::$connectionWrongPass->isValid());
		$this->assertFalse(static::$connectionWrongDb->isValid());
		$this->assertTrue(static::$connection->isValid());
	}

	public function testGetPdo()
	{
		$this->assertTrue(static::$connection->getPdo() instanceof PDO);
		$this->assertNull(static::$connectionWrongUser->getPdo());
	}

	public function testValidateIsValid()
	{
		ApplicationTest::newApp();
		static::$connection->validate();
		$this->assertEquals('index', Application::app()->getPage());
	}

	/** @expectedException \Win\Mvc\HttpException */
	public function testValidateThrowException()
	{
		ApplicationTest::newApp();
		static::$connectionWrongDb->validate();
	}

	public function testMultipleInstance()
	{
		$db = DbConfig::valid();
		$db['dbname'] = 'mysql';
		Connection::instance('connection2')->connect($db);

		$db['dbname'] = 'wrong-database';
		Connection::instance('connection3')->connect($db);

		$this->assertTrue(Connection::instance()->isValid());
		$this->assertFalse(Connection::instance('connection3')->isValid());
		$this->assertTrue(Connection::instance('connection2')->isValid());
		$this->assertFalse(Connection::instance('connection4')->isValid());
	}

	public function testSintaxError()
	{
		$success = static::$connection->query('SELECT * FROM ASDF');
		$this->assertFalse($success);
	}

	public function testTruncate()
	{
		$query = 'TRUNCATE TABLE child';
		$success = static::$connection->query($query);
		$this->assertTrue($success);
	}

	public function testInsert()
	{
		$query = 'INSERT INTO child VALUES(?)';
		$success = static::$connection->query($query, [10]);
		$this->assertTrue($success);
	}

	public function testSelect()
	{
		$this->testInsert();
		$query = 'SELECT * FROM child WHERE age = ?';
		$rows = static::$connection->fetchAll($query, [10]);
		$this->assertCount(2, $rows);
		$this->assertEquals(10, $rows[0]['age']);
	}

	public function testUpdate()
	{
		$this->testInsert();
		$query = 'UPDATE child SET age = ? WHERE age = ? LIMIT 1';
		$success = static::$connection->query($query, [1, 10]);
		$rows = static::$connection->fetchAll('SELECT * FROM child WHERE age = ?', [1]);

		$this->assertTrue($success);
		$this->assertCount(1, $rows);
		$this->assertEquals(1, $rows[0]['age']);
	}

	public function testDelete()
	{
		$success = static::$connection->query('DELETE FROM child WHERE age = ? LIMIT 1', [10]);
		$rows = static::$connection->fetchAll('SELECT * FROM child');
		$this->assertTrue($success);
		$this->assertCount(2, $rows);
	}

	public function testNumRows()
	{
		$count1 = static::$connection->fetchCount('SELECT count(*) FROM child WHERE age = ?', [1]);
		$count2 = static::$connection->fetchCount('SELECT count(*) FROM child WHERE age >= ?', [1]);
		$this->assertEquals(1, $count1);
		$this->assertEquals(2, $count2);
	}
}
