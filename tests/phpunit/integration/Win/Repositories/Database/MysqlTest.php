<?php

namespace Win\Repositories\Database;

use PDO;
use PHPUnit\Framework\TestCase;

class MysqlTest extends TestCase
{
	/** @var Mysql */
	private static $conn;

	public static function setUpBeforeClass()
	{
		static::$conn = new Mysql(DbConfig::valid());
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
	public static function createTable()
	{
		$query = 'CREATE TABLE IF NOT EXISTS `child` (`age` int(11) NOT NULL,' .
			' `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT)';
		static::$conn->execute($query);
		$query = 'INSERT INTO child VALUES(2,null); INSERT INTO child VALUES(3,null)';
		static::$conn->execute($query);
	}

	public static function dropTable()
	{
		$query = 'DROP TABLE IF EXISTS `child`';
		static::$conn->execute($query);
	}

	// TESTES

	public function testIsValid()
	{
		$this->assertTrue(static::$conn->isValid());
	}

	/** @expectedException Win\HttpException */
	public function testErrorConnection()
	{
		new Mysql(DbConfig::wrongDb());
	}

	public function testGetPdo()
	{
		$this->assertTrue(static::$conn->getPdo() instanceof PDO);
	}

	/** @expectedException Win\Repositories\Database\DatabaseException */
	public function testSintaxeError()
	{
		$success = static::$conn->execute('SELECT * FROM ASDF');
		$this->assertFalse($success);
	}

	/** @expectedException Win\Repositories\Database\DatabaseException */
	public function testStmtError()
	{
		$success = static::$conn->fetch('SELECT * FROM ASDF', []);
		$this->assertFalse($success);
	}

	public function testTruncate()
	{
		$query = 'TRUNCATE TABLE child';
		$success = static::$conn->execute($query);
		$this->assertTrue($success);
	}

	public function testInsert()
	{
		$query = 'INSERT INTO child VALUES(?,null)';
		$success = static::$conn->execute($query, [10]);
		$this->assertTrue($success);
		$this->assertGreaterThan(0, static::$conn->lastInsertId());
	}

	public function testFetch()
	{
		$this->testInsert();
		$query = 'SELECT * FROM child WHERE age = ?';
		$rows = static::$conn->fetch($query, [10]);
		$this->assertEquals(10, $rows['age']);
	}

	public function testFetchAll()
	{
		$this->testInsert();
		$query = 'SELECT * FROM child WHERE age = ?';
		$rows = static::$conn->fetchAll($query, [10]);
		$this->assertCount(1, $rows);
		$this->assertEquals(10, $rows[0]['age']);
	}

	public function testUpdate()
	{
		$this->testInsert();
		$query = 'UPDATE child SET age = ? WHERE age = ? LIMIT 1';
		$success = static::$conn->execute($query, [1, 10]);
		$rows = static::$conn->fetchAll('SELECT * FROM child WHERE age = ?', [1]);

		$this->assertTrue($success);
		$this->assertCount(1, $rows);
		$this->assertEquals(1, $rows[0]['age']);
	}

	public function testDelete()
	{
		$this->testInsert();
		$success = static::$conn->execute('DELETE FROM child WHERE age = ? LIMIT 1', [10]);
		$rows = static::$conn->fetchAll('SELECT * FROM child');
		$this->assertTrue($success);
		$this->assertCount(2, $rows);
	}

	public function testNumRows()
	{
		$count1 = static::$conn->fetchCount('SELECT count(*) FROM child WHERE age = ?', [3]);
		$count2 = static::$conn->fetchCount('SELECT count(*) FROM child WHERE age >= ?', [1]);
		$this->assertEquals(1, $count1);
		$this->assertEquals(2, $count2);
	}
}
