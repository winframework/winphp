<?php

namespace Win\Authentication;

use Win\Connection\MySQLTest;

class UserDAOTest extends \PHPUnit_Framework_TestCase {

	/** @var \PDO */
	private static $pdo;

	/** @var User */
	private $user;

	/** @var UserDAO */
	private $dao;

	public static function setUpBeforeClass() {
		static::$pdo = MySQLTest::startMySQLConnection();
		static::$pdo->query('DROP TABLE ' . UserDAO::TABLE);
		static::$pdo->query('CREATE TABLE `user` (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`is_enabled` tinyint(1) NOT NULL,
					`access_level` smallint(6) NOT NULL,
					`group_id` int(11) NOT NULL,
					`name` varchar(45) NOT NULL,
					`email` varchar(45) NOT NULL,
					`password_hash` varchar(32) NOT NULL,
					`recovery_hash` varchar(32) DEFAULT NULL,
					`image` varchar(45) DEFAULT NULL,
					`last_login` datetime DEFAULT NULL,
					PRIMARY KEY(`id`)
				);
		');
	}

	public function setUp() {
		static::$pdo->query('TRUNCATE TABLE ' . UserDAO::TABLE);
	}

	private function insertExample() {
		$user = new User();
		$user->setEnabled(true);
		$user->setAccessLevel(User::ACCESS_ADMIN);
		$user->setName('My name');
		$user->setEmail('myemail@example.com');
		$user->setPassword('123456');
		$this->user = $user;

		$this->dao = new UserDAO();
		return $this->dao->save($user);
	}

	public function testUpdateRecoveryHash() {
		$this->insertExample();
		$oldHash = $this->user->getRecoreryHash();
		$this->user->setRecoreryHash('abc');
		$this->dao->updateRecoveryHash($this->user);
		$this->assertNotEquals($oldHash, $this->user->getRecoreryHash());
	}

	public function testUpdateLastLogin() {
		$this->insertExample();
		$lastLogin = $this->user->getLastLogin();
		$this->user->login();
		$this->assertEquals($lastLogin, $this->user->getLastLogin());

		$this->user->login();
		$this->assertNotEquals($lastLogin, $this->user->getLastLogin());
	}

	public function testUpdatePassword() {
		$this->insertExample();
		$oldPasswordHash = $this->user->getPasswordHash();
		$this->dao->updatePassword($this->user, 'my-newpass', 'my-newpass');
		$this->assertNotEquals($this->user->getPasswordHash(), $oldPasswordHash);

		$lastUser = $this->dao->fetchById($this->user->getId());
		$this->assertEquals($lastUser->getPasswordHash(), $this->user->getPasswordHash());
	}

}
