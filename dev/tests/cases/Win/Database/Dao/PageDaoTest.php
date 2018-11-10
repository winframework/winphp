<?php

namespace Win\Database\Dao;

use PHPUnit_Framework_TestCase;
use Win\Database\Connection\Mysql;
use Win\Database\Dao\Page\Page;
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
		$page = Page::dao()->find(2)->result();
		$this->assertEquals($page->getId(), 2);
		$this->assertEquals($page->getTitle(), 'Second Page');
	}

	public function testFirst() {
		$page = Page::dao()->older()->result();
		$this->assertEquals($page->getId(), 1);
	}

	public function testLast() {
		$page = Page::dao()->newer()->result();
		$this->assertEquals($page->getId(), 3);
	}

	public function testAll() {
		$pages = Page::dao()->results();
		$this->assertTrue(count($pages) > 1);
		$this->assertEquals($pages[0]->getTitle(), 'First Page');
	}

	public function testRecent() {
		$pages = Page::dao()->newer()->results();
		$this->assertTrue(count($pages) > 1);
		$this->assertEquals($pages[0]->getTitle(), 'Third Page');
	}

	public function testOrderBy() {
		$pages = Page::dao()->orderBy('title ASC')->results();
		$this->assertTrue(count($pages) > 1);
		$this->assertEquals($pages[0]->getTitle(), 'First Page');
	}

	public function testFilter() {
		$page = Page::dao()->filter('id', '=', 2)->result();
		$this->assertEquals($page->getId(), 2);
		$this->assertEquals($page->getTitle(), 'Second Page');
	}

	public function testFilterRecent() {
		$pages = Page::dao()->filter('id', '>', 1)->filter('id', '<', 3)->newer()->results();
		$this->assertCount(1, $pages);
		$this->assertEquals($pages[0]->getTitle(), 'Second Page');
	}

	public function testLimit() {
		$pages = Page::dao()->limit(2)->results();
		$this->assertCount(2, $pages);
	}

	public function testFlush() {
		$pages = Page::dao()->filter('id', '>', 1)->results();
		$pages2 = Page::dao()->filter('id', '>', 0)->results();

		$this->assertCount(2, $pages);
		$this->assertCount(3, $pages2);
	}

	public function testDelete() {
		$pagesTotal = count(Page::dao()->results());
		$page = Page::dao()->find(1)->result();
		$success = Page::dao()->delete($page);

		$this->assertTrue($success);
		$this->assertCount($pagesTotal - 1, Page::dao()->results());
	}

	public function testDebug() {
		Page::dao()->debug();
		ob_start();
		Page::dao()->results();
		$result = ob_get_clean();
		$this->assertContains('SELECT * FROM', $result);
	}

	public function testDebug_Off() {
		Page::dao()->debug();
		ob_start();
		Page::dao()->debug(false);
		Page::dao()->results();
		$empty = ob_get_clean();
		$this->assertEmpty($empty);
	}

	public function testInsert() {
		$pagesTotal = count(Page::dao()->results());

		$page = new Page();
		$page->setTitle('Fourth Page');
		$page->setDescription('Inserted by save method');
		$success = Page::dao()->save($page);

		$this->assertTrue($success);
		$this->assertGreaterThan(0, $page->getId());
		$this->assertCount($pagesTotal + 1, Page::dao()->results());
	}

	public function testUpdate() {
		Page::dao()->debug();
		$pagesTotal = count(Page::dao()->results());

		$page = Page::dao()->newer()->result();
		$page->setTitle('New Title');
		$page->setDescription('Updated by save method');
		$success = Page::dao()->save($page);
		$pageUpdated = Page::dao()->newer()->result();

		$this->assertTrue($success);
		$this->assertEquals('New Title', $pageUpdated->getTitle());
		$this->assertEquals('Updated by save method', $pageUpdated->getDescription());
		$this->assertCount($pagesTotal, Page::dao()->results());
	}

}
