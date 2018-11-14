<?php

namespace Win\Database\Orm;

use PHPUnit_Framework_TestCase;
use Win\Database\Mysql;
use Win\Database\Orm\Page\Page;
use Win\Database\DbConfig;

class PageRepoTest extends PHPUnit_Framework_TestCase {

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
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
		);
	}

	public static function importTable() {
		Mysql::instance()->query("INSERT INTO `page` (`id`, `title`, `description`, `created_at`) VALUES
			(1, 'First Page', 'About us', '2018-11-04 10:46:03'),
			(2, 'Second Page', 'Contact us', '2018-11-04 12:05:01'),
			(3, 'Third Page', 'Sample Page', '2018-11-04 12:05:20');"
		);
	}

	public function testNumRows() {
		Page::repo()->debugOn();
		$count = Page::repo()->filter('id', '>', 1)->numRows();
		$this->assertEquals(2, $count);
	}

	public function testAll() {
		$pages = Page::repo()->results();
		$this->assertTrue(count($pages) > 1);
		$this->assertEquals('First Page', $pages[0]->getTitle());
	}

	public function testFind() {
		$page = Page::repo()->find(2)->result();
		$this->assertEquals(2, $page->getId());
		$this->assertEquals('Second Page', $page->getTitle());
	}

	public function testFirst() {
		$page = Page::repo()->older()->result();
		$this->assertEquals(1, $page->getId());
	}

	public function testLast() {
		$page = Page::repo()->newer()->result();
		$this->assertEquals(3, $page->getId());
	}

	public function testRecent() {
		$pages = Page::repo()->newer()->results();
		$this->assertTrue(count($pages) > 1);
		$this->assertEquals('Third Page', $pages[0]->getTitle());
	}

	public function testOrderBy() {
		$pages = Page::repo()->orderBy('title ASC')->results();
		$this->assertTrue(count($pages) > 1);
		$this->assertEquals('First Page', $pages[0]->getTitle());
	}

	public function testFilter() {
		$page = Page::repo()->filter('id', '=', 2)->result();
		$this->assertEquals($page->getId(), 2);
		$this->assertEquals('Second Page', $page->getTitle());
	}

	public function testFilterRecent() {
		$pages = Page::repo()->filter('id', '>', 1)->filter('id', '<', 3)->newer()->results();
		$this->assertCount(1, $pages);
		$this->assertEquals('Second Page', $pages[0]->getTitle());
	}

	public function testLimit() {
		$pages = Page::repo()->limit(2)->results();
		$this->assertCount(2, $pages);
	}

	public function testFlush() {
		$pages = Page::repo()->filter('id', '>', 1)->results();
		$pages2 = Page::repo()->filter('id', '>', 0)->results();

		$this->assertCount(2, $pages);
		$this->assertCount(3, $pages2);
	}

	public function testDelete() {
		$pagesTotal = count(Page::repo()->results());
		$page = Page::repo()->find(1)->result();
		$success = Page::repo()->delete($page);

		$this->assertTrue($success);
		$this->assertCount($pagesTotal - 1, Page::repo()->results());
	}

	public function testDebug() {
		Page::repo()->debugOn();
		ob_start();
		Page::repo()->results();
		$result = ob_get_clean();
		$this->assertContains('SELECT * FROM', $result);
	}

	public function testDebug_Off() {
		Page::repo()->debugOn();
		ob_start();
		Page::repo()->debugOff();
		Page::repo()->results();
		$empty = ob_get_clean();
		$this->assertEmpty($empty);
	}

	public function testInsert() {
		$pagesTotal = count(Page::repo()->results());

		$page = new Page();
		$page->setTitle('Fourth Page');
		$page->setDescription('Inserted by save method');
		$success = Page::repo()->save($page);

		$this->assertTrue($success);
		$this->assertGreaterThan(0, $page->getId());
		$this->assertCount($pagesTotal + 1, Page::repo()->results());
	}

	public function testUpdate() {
		Page::repo()->debugOn();
		$pagesTotal = count(Page::repo()->results());

		$page = Page::repo()->newer()->result();
		$page->setTitle('New Title');
		$page->setDescription('Updated by save method');
		$success = Page::repo()->save($page);
		$pageUpdated = Page::repo()->newer()->result();

		$this->assertTrue($success);
		$this->assertEquals('New Title', $pageUpdated->getTitle());
		$this->assertEquals('Updated by save method', $pageUpdated->getDescription());
		$this->assertCount($pagesTotal, Page::repo()->results());
	}

}
