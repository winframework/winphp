<?php

namespace Win\Database\Connection;

use PDO;
use PHPUnit_Framework_TestCase;
use Win\Mvc\Application;
use Win\Request\Url;
use const BASE_PATH;

class MysqlTest extends PHPUnit_Framework_TestCase {

	protected static $dbFile = BASE_PATH . '/app/config/database.php';

	public function testNotValid_WrongUser() {
		static::newApplication();
		$this->connectNotValid_WrongUser();
		$this->assertNull(Mysql::instance()->getPdo());
		$this->assertFalse(Mysql::instance()->getPdo() instanceof PDO);
	}

	public function testNotValid_WrongPassword() {
		static::newApplication();
		$this->connectNotValid_WrongPassword();
		$this->assertFalse(Mysql::instance()->isValid());
	}

	public function testNotValid_WrongDatabase() {
		static::newApplication();
		$this->connectNotValid_WrongDatabase();
		$this->assertFalse(Mysql::instance()->isValid());
	}

	public function testValid() {
		static::connectValid();
		$this->assertTrue(Mysql::instance()->isValid());
	}

	public function testGetPdo() {
		static::connectValid();
		$this->assertTrue(Mysql::instance()->getPdo() instanceof PDO);
	}

	public function testValidate_DoesNothing() {
		static::newApplication();
		static::connectValid();
		Mysql::instance()->validate();
		$this->assertEquals('index', Application::app()->getPage());
	}

	/**
	 * @expectedException \Win\Mvc\HttpException
	 */
	public function testValidate_ThrowException() {
		static::newApplication();
		static::connectNotValid_WrongDatabase();
		Mysql::instance()->validate();
	}

	public function testMultipleInstance() {
		static::newApplication();
		$this->connectNotValid_WrongDatabase();

		require static::$dbFile;
		$db['dbname'] = 'mysql';
		Mysql::instance('mysql2')->connect($db);

		require static::$dbFile;
		$db['dbname'] = 'wrong-database';
		Mysql::instance('mysql3')->connect($db);


		$this->assertFalse(Mysql::instance()->isValid());
		$this->assertTrue(Mysql::instance('mysql2')->isValid());
		$this->assertFalse(Mysql::instance('mysql4')->isValid());
	}

	/** @return Mysql */
	private static function connectNotValid_WrongUser() {
		require static::$dbFile;
		$db['user'] = 'this-user-do-not-exist';
		Mysql::instance()->connect($db);
	}

	/** @return Mysql */
	private static function connectNotValid_WrongPassword() {
		require static::$dbFile;
		$db['password'] = 'this-pass-is-wrong';
		Mysql::instance()->connect($db);
	}

	/** @return Mysql */
	private static function connectNotValid_WrongDatabase() {
		require static::$dbFile;
		$db['dbname'] = 'invalid-database';
		Mysql::instance()->connect($db);
	}

	/** @return Mysql */
	public static function connectValid() {
		require static::$dbFile;
		$db['dbname'] = 'mysql';
		Mysql::instance()->connect($db);
	}

	private static function newApplication() {
		Url::instance()->setUrl('index');
		new Application();
	}

}
