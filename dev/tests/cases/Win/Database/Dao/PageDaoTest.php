<?php

namespace Win\Database\Dao;

use PHPUnit_Framework_TestCase;
use Win\Database\Connection\Mysql;
use Win\Database\Dao\Page\PageDao;
use Win\Database\DbConfig;

class PageDaoTest extends PHPUnit_Framework_TestCase {

	public static function setUpBeforeClass() {
		DbConfig::connect();
		static::createTable();
		static::importTable();
	}

	public static function createTable() {
		Mysql::instance()->query("DROP TABLE `page` ");
		Mysql::instance()->query("CREATE TABLE `page` (
			`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			`title` varchar(75) NOT NULL,
			`description` text NOT NULL,
			`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;

"
		);
	}

	public static function importTable() {
		Mysql::instance()->query("INSERT INTO `page` (`id`, `title`, `description`, `created_at`) VALUES
			(1, 'First Page', 'About us', '2018-11-04 10:46:03'),
			(2, 'Second Page', 'Contact us', '2018-11-04 12:05:01'),
			(3, 'Third Page', 'Sample Page', '2018-11-04 12:05:20');"
		);
	}

	public function testFind() {
		$page = PageDao::instance()->find(2);
		$this->assertEquals($page->getId(), 2);
		$this->assertEquals($page->getTitle(), 'Second Page');
	}

	public function testFirst() {
		$page = PageDao::instance()->first();
		$this->assertEquals($page->getId(), 1);
	}

	public function testLast() {
		$page = PageDao::instance()->last();
		$this->assertEquals($page->getId(), 3);
	}

	public function testAll() {
		$pages = PageDao::instance()->all();
		$this->assertTrue(count($pages) > 1);
		$this->assertEquals($pages[0]->getTitle(), 'First Page');
	}

	public function testLatest() {
		$pages = PageDao::instance()->latest();
		$this->assertTrue(count($pages) > 1);
		$this->assertEquals($pages[0]->getTitle(), 'Third Page');
	}

	public function testAll_OrderBy() {
		$pages = PageDao::instance()->orderBy('title ASC')->all();
		$this->assertTrue(count($pages) > 1);
		$this->assertEquals($pages[0]->getTitle(), 'First Page');
	}

	public function testGet_Filter() {
		$page = PageDao::instance()->filter('id', '=', 2)->first();
		$this->assertEquals($page->getId(), 2);
		$this->assertEquals($page->getTitle(), 'Second Page');
	}

}
