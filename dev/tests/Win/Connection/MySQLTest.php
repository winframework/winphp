<?php

namespace Win\Connection;

use Win\Helper\Url;
use Win\Mvc\Application;

class MySQLTest extends \PHPUnit_Framework_TestCase {

	public function testPdoIsNullOnErrorConnection() {
		$dbConfig = [
			'host' => 'localhost',
			'user' => 'no-name',
			'pass' => 'no-pass',
			'dbname' => 'database-doesnt-exist'
		];

		new Application();
		$db = new MySQL($dbConfig);
		$this->assertNull($db->getPDO());
	}

	public function testRedirectToPage500() {
		$dbConfig = [
			'host' => 'localhost',
			'user' => 'no-name',
			'pass' => 'no-pass',
			'dbname' => 'database-doesnt-exist'
		];

		Url::instance()->setUrl('my-page/test');
		$app = new Application();
		$this->assertEquals($app->getPage(), 'my-page');

		new MySQL($dbConfig);
		$this->assertEquals($app->getPage(), '503');
	}

	public function testConnectionWithSuccess() {
		static::startMySQLConnection();
		$this->assertTrue(MySQL::instance()->getPDO() instanceof \PDO);
	}

	/**
	 * @return \PDO
	 */
	static function startMySQLConnection() {
		require BASE_PATH . '/app/config/database.php';
		$db['dbname'] = 'win_test';
		$mysql = new MySQL($db);
		return $mysql->getPDO();
	}

}
