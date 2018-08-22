<?php

namespace Win\Connection;

use Win\Request\Url;
use Win\Mvc\Application;

class MysqlTest extends \PHPUnit_Framework_TestCase {

	public function testWrongUser() {
		new Application();
		$db = $this->startWrongUser();
		$this->assertNull($db->getPDO());
	}

	public function testWrongPassword() {
		new Application();
		$db = $this->startWrongPassword();
		$this->assertNull($db->getPDO());
	}

	public function testWrongDatabase() {
		new Application();
		$db = $this->startWrongDatabase();
		$this->assertNull($db->getPDO());
	}

	public function testRedirectToPage500() {
		Url::instance()->setUrl('my-page/test');
		$app = new Application();
		$this->assertEquals($app->getPage(), 'my-page');

		$this->startWrongDatabase();
		$this->assertEquals($app->getPage(), '503');
	}

	public function testConnectionWithSuccess() {
		static::startValidConnection();
		$this->assertTrue(Mysql::instance()->getPDO() instanceof \PDO);
	}

	/** @return Mysql */
	static function startWrongUser() {
		require 'app/config/database.php';
		$db['user'] = 'this-user-do-not-exist';
		return new Mysql($db);
	}

	/** @return Mysql */
	static function startWrongPassword() {
		require 'app/config/database.php';
		$db['password'] = 'this-pass-is-wrong';
		return new Mysql($db);
	}

	/** @return Mysql */
	static function startWrongDatabase() {
		require 'app/config/database.php';
		$db['dbname'] = 'invalid-database';
		return new Mysql($db);
	}

	/** @return Mysql */
	static function startValidConnection() {
		require 'app/config/database.php';
		$db['dbname'] = 'mysql';
		return new Mysql($db);
	}

}
