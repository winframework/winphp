<?php

namespace Win\Database\Dao;

use PHPUnit_Framework_TestCase;
use Win\Database\Connection\Mysql;
use Win\Database\Dao\Page\Page;
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
		$page = PageDao::instance()->find(2)->result();
		$this->assertEquals($page->getId(), 2);
		$this->assertEquals($page->getTitle(), 'Second Page');
	}

	public function testFirst() {
		$page = PageDao::instance()->older()->result();
		$this->assertEquals($page->getId(), 1);
	}

	public function testLast() {
		$page = PageDao::instance()->newer()->result();
		$this->assertEquals($page->getId(), 3);
	}

	public function testAll() {
		$pages = PageDao::instance()->results();
		$this->assertTrue(count($pages) > 1);
		$this->assertEquals($pages[0]->getTitle(), 'First Page');
	}

	public function testLatest() {
		$pages = PageDao::instance()->newer()->results();
		$this->assertTrue(count($pages) > 1);
		$this->assertEquals($pages[0]->getTitle(), 'Third Page');
	}

	public function testOrderBy() {
		$pages = PageDao::instance()->orderBy('title ASC')->results();
		$this->assertTrue(count($pages) > 1);
		$this->assertEquals($pages[0]->getTitle(), 'First Page');
	}

	public function testFilter() {
		$page = PageDao::instance()->filter('id', '=', 2)->result();
		$this->assertEquals($page->getId(), 2);
		$this->assertEquals($page->getTitle(), 'Second Page');
	}

	public function testFilterLatest() {
		$pages = PageDao::instance()->filter('id', '>', 1)->filter('id', '<', 3)->newer()->results();
		$this->assertCount(1, $pages);
		$this->assertEquals($pages[0]->getTitle(), 'Second Page');
	}

	public function testLimit() {
		$pages = PageDao::instance()->limit(2)->results();
		$this->assertCount(2, $pages);
	}

	public function testFlush() {
		$pages = PageDao::instance()->filter('id', '>', 1)->results();
		$pages2 = PageDao::instance()->filter('id', '>', 0)->results();

		$this->assertCount(2, $pages);
		$this->assertCount(3, $pages2);
	}

}
