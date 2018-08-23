<?php

namespace Win\Connection;

use Win\Request\Url;
use Win\Mvc\Application;

class MysqlTest extends \PHPUnit_Framework_TestCase {

	public function testNotValidWrongUser() {
		static::newApplication();
		$this->startWrongUser();
		$this->assertNull(Mysql::instance()->getPDO());
		$this->assertFalse(Mysql::instance()->getPDO() instanceof \PDO);
	}

	public function testNotValidWrongPassword() {
		static::newApplication();
		$this->startWrongPassword();
		$this->assertFalse(Mysql::instance()->isValid());
	}

	public function testNotValidWrongDatabase() {
		static::newApplication();
		$this->startWrongDatabase();
		$this->assertFalse(Mysql::instance()->isValid());
	}

	public function testValid() {
		static::startValidConnection();
		$this->assertTrue(Mysql::instance()->isValid());
	}

	public function testGetPdo() {
		static::startValidConnection();
		$this->assertTrue(Mysql::instance()->getPDO() instanceof \PDO);
	}

	public function testValidateDoesNothing() {
		static::newApplication();
		static::startValidConnection();
		Mysql::instance()->validate();
		$this->assertEquals('index', Application::app()->getPage());
	}

	public function testValidateRedirects() {
		static::newApplication();
		static::startWrongDatabase();
		Mysql::instance()->validate();
		$this->assertEquals(503, Application::app()->getPage());
	}

	public function testMultipleInstance() {
		static::newApplication();
		$this->startWrongDatabase();

		require 'app/config/database.php';
		$db['dbname'] = 'mysql';
		Mysql::instance('mysql2')->connect($db);

		require 'app/config/database.php';
		$db['dbname'] = 'wrong-database';
		Mysql::instance('mysql3')->connect($db);


		$this->assertFalse(Mysql::instance()->isValid());
		$this->assertTrue(Mysql::instance('mysql2')->isValid());
		$this->assertFalse(Mysql::instance('mysql4')->isValid());
	}

	/** @return Mysql */
	static function startWrongUser() {
		require 'app/config/database.php';
		$db['user'] = 'this-user-do-not-exist';
		Mysql::instance()->connect($db);
	}

	/** @return Mysql */
	static function startWrongPassword() {
		require 'app/config/database.php';
		$db['password'] = 'this-pass-is-wrong';
		Mysql::instance()->connect($db);
	}

	/** @return Mysql */
	static function startWrongDatabase() {
		require 'app/config/database.php';
		$db['dbname'] = 'invalid-database';
		Mysql::instance()->connect($db);
	}

	/** @return Mysql */
	static function startValidConnection() {
		require 'app/config/database.php';
		$db['dbname'] = 'mysql';
		Mysql::instance()->connect($db);
	}

	static function newApplication() {
		Url::instance()->setUrl('index');
		new Application();
	}

}
