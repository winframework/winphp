<?php

namespace Win\Repositories\Database;

use PDO;
use PHPUnit\Framework\TestCase;

class MysqlConnectionTest extends TestCase
{
	/** @var MysqlConnection */
	private static $connection = null;

	/** @var MysqlConnection */
	private static $connectionWrongDb = null;

	public static function setUpBeforeClass()
	{
		static::instance();
		static::connect();
	}

	public function setUp()
	{
		static::dropTable();
		static::createTable();
	}

	public static function tearDownAfterClass()
	{
		static::dropTable();
	}

	/** MÉTODOS ESTÁTICOS */
	public static function instance()
	{
		static::$connection = MysqlConnection::instance();
		static::$connectionWrongDb = MysqlConnection::instance('wrongDb');
	}

	public static function connect()
	{
		static::$connection->connect(DbConfig::valid());
	}

	public static function createTable()
	{
		$query = 'CREATE TABLE IF NOT EXISTS `child` (`age` int(11) NOT NULL,' .
		' `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT)';
		static::$connection->query($query);
		$query = 'INSERT INTO child VALUES(2,null); INSERT INTO child VALUES(3,null)';
		static::$connection->query($query);
	}

	public static function dropTable()
	{
		$query = 'DROP TABLE IF EXISTS `child`';
		static::$connection->query($query);
	}

	// TESTES

	public function testIsValid()
	{
		$this->assertTrue(static::$connection->isValid());
	}

	/** @expectedException Win\Response\ResponseException */
	public function testErrorConnection()
	{
		static::$connectionWrongDb->connect(DbConfig::wrongDb());
	}

	public function testGetPdo()
	{
		$this->assertTrue(static::$connection->getPdo() instanceof PDO);
		$this->assertNull(static::$connectionWrongDb->getPdo());
	}

	public function testMultipleInstance()
	{
		$db = DbConfig::valid();
		$db['dbname'] = 'mysql';
		MysqlConnection::instance('connection2')->connect($db);

		$this->assertTrue(MysqlConnection::instance()->isValid());
		$this->assertTrue(MysqlConnection::instance('connection2')->isValid());
		$this->assertFalse(MysqlConnection::instance('connection4')->isValid());
	}

	/** @expectedException Win\Repositories\Database\DatabaseException */
	public function testSintaxeError()
	{
		$success = static::$connection->query('SELECT * FROM ASDF');
		$this->assertFalse($success);
	}

	/** @expectedException Win\Repositories\Database\DatabaseException */
	public function testStmtError()
	{
		$success = static::$connection->fetch('SELECT * FROM ASDF', []);
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
		$query = 'INSERT INTO child VALUES(?,null)';
		$success = static::$connection->query($query, [10]);
		$this->assertTrue($success);
		$this->assertGreaterThan(0, static::$connection->getLastInsertId());
	}

	public function testFetch()
	{
		$this->testInsert();
		$query = 'SELECT * FROM child WHERE age = ?';
		$rows = static::$connection->fetch($query, [10]);
		$this->assertEquals(10, $rows['age']);
	}

	public function testFetchAll()
	{
		$this->testInsert();
		$query = 'SELECT * FROM child WHERE age = ?';
		$rows = static::$connection->fetchAll($query, [10]);
		$this->assertCount(1, $rows);
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
		$this->testInsert();
		$success = static::$connection->query('DELETE FROM child WHERE age = ? LIMIT 1', [10]);
		$rows = static::$connection->fetchAll('SELECT * FROM child');
		$this->assertTrue($success);
		$this->assertCount(2, $rows);
	}

	public function testNumRows()
	{
		$count1 = static::$connection->fetchCount('SELECT count(*) FROM child WHERE age = ?', [3]);
		$count2 = static::$connection->fetchCount('SELECT count(*) FROM child WHERE age >= ?', [1]);
		$this->assertEquals(1, $count1);
		$this->assertEquals(2, $count2);
	}
}
