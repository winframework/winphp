<?php

namespace Win\DAO;

use Win\Connection\MySQLTest;
use Win\Authentication\User;
use Win\Authentication\UserDAO;

/**
 * Este teste usa UserDAO como exemplo
 * Testa os metodos já existentes em abstract DAO
 */
class DAOTest extends \PHPUnit_Framework_TestCase {

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

	public function testInsert() {
		$error = $this->insertExample();
		$this->assertNull($error);

		$lastUser = $this->dao->fetchById($this->user->getId());
		$this->assertEquals($this->user->getName(), $lastUser->getName());
	}

	public function testUpdate() {
		$this->insertExample();

		$this->user->setName('Name Updated');
		$error = $this->dao->save($this->user);
		$this->assertNull($error);

		$lastUser = $this->dao->fetchById($this->user->getId());
		$this->assertEquals($this->user->getId(), $lastUser->getId());
	}

	public function testDelete() {
		$this->insertExample();
		$this->dao->delete($this->user);

		$lastUser = $this->dao->fetchById($this->user->getId());
		$this->assertEquals($lastUser->getId(), 0);
	}

	public function testDeleteById() {
		$this->insertExample();
		$this->dao->deleteById($this->user->getId());

		$lastUser = $this->dao->fetchById($this->user->getId());
		$this->assertEquals($lastUser->getId(), 0);
	}

	public function testValidate() {
		$this->insertExample();
		$this->user->setEmail('invalid');
		$this->dao->save($this->user);

		$lastUser = $this->dao->fetchById($this->user->getId());
		$this->assertEquals($lastUser->getEmail(), 'myemail@example.com');
	}

	public function testFetch() {
		$this->insertExample();
		$lastUser = $this->dao->fetch(['name = ?' => 'My name']);
		$this->assertEquals($lastUser->getId(), $this->user->getId());
	}

	public function testFetchAll() {
		$this->dao = new UserDAO();
		$userList0 = $this->dao->fetchAll();
		$this->assertEquals(count($userList0), 0);

		/* insert 2 examples */
		$this->insertExample();
		$this->insertExample();
		$userList2 = $this->dao->fetchAll();
		$this->assertEquals(count($userList2), 2);
	}

	public function testNumRows() {
		$this->dao = new UserDAO();
		$numRows0 = $this->dao->numRows();
		$this->assertEquals($numRows0, 0);

		$this->insertExample();
		$numRows1 = $this->dao->numRows();
		$this->assertEquals($numRows1, 1);
	}

	public function testFoundWithFilter() {
		$this->insertExample();
		$filters = [
			'name = ?' => 'My name',
			'email = ?' => 'myemail@example.com',
			'access_level > ?' => 0
		];
		$user = $this->dao->fetch($filters);
		$this->assertNotEquals($user->getId(), 0);
	}

	public function testNotFoundWithFilter() {
		$this->insertExample();
		$filters = [
			'name = ?' => 'Name doesnt exist',
			'email = ?' => 'myemail@example.com',
			'access_level > ?' => 0
		];
		$user = $this->dao->fetch($filters);
		$this->assertEquals($user->getId(), 0);
	}

}
