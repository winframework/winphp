<?php

namespace Win\Database\Orm;

use PHPUnit\Framework\TestCase;
use Win\Database\Mysql\MysqlConnection as Mysql;
use Win\Database\DbConfig;
use Win\Database\Orm\Page\Page;

class PageOrmTest extends TestCase
{
	public static function setUpBeforeClass()
	{
		DbConfig::connect();
		static::createTable();
		static::importTable();
	}

	public static function createTable()
	{
		Mysql::instance()->query('DROP TABLE `page` ');
		Mysql::instance()->query('CREATE TABLE `page` (
			`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			`title` varchar(75) NOT NULL,
			`description` text NOT NULL,
			`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
	}

	public static function importTable()
	{
		Mysql::instance()->query("INSERT INTO `page` (`id`, `title`, `description`, `created_at`) VALUES
			(1, 'First Page', 'About us', '2018-11-04 10:46:03'),
			(2, 'Second Page', 'Contact us', '2018-11-04 12:05:01'),
			(3, 'Third Page', 'Sample Page', '2018-11-04 12:05:20');");
	}

	public function testCount()
	{
		$count = Page::orm()->filter('id', '>', 1)->count();
		$this->assertEquals(2, $count);
	}

	public function testAll()
	{
		$pages = Page::orm()->all();
		$this->assertTrue(count($pages) > 1);
		$this->assertEquals('First Page', $pages[0]->getTitle());
	}

	public function testFind()
	{
		$page = Page::orm()->find(2);
		$this->assertEquals(2, $page->getId());
		$this->assertEquals('Second Page', $page->getTitle());
	}

	public function testOrderByOlder()
	{
		$page = Page::orm()->order('asc')->one();
		$this->assertEquals(1, $page->getId());
	}

	public function testOrderByNewer()
	{
		$page = Page::orm()->order('desc')->one();
		$this->assertEquals(3, $page->getId());
	}

	public function testRecents()
	{
		$pages = Page::orm()->order()->all();
		$this->assertTrue(count($pages) > 1);
		$this->assertEquals('Third Page', $pages[0]->getTitle());
	}

	public function testOrderBy()
	{
		$pages = Page::orm()->orderBy('title ASC')->all();
		$this->assertTrue(count($pages) > 1);
		$this->assertEquals('First Page', $pages[0]->getTitle());
	}

	public function testFilterBy()
	{
		$page = Page::orm()->filterBy('title', 'Second Page')->one();
		$this->assertEquals(2, $page->getId());
	}

	public function testFilter()
	{
		$page = Page::orm()->filter('id', '=', 2)->one();
		$this->assertEquals($page->getId(), 2);
		$this->assertEquals('Second Page', $page->getTitle());
	}

	public function testFilterRecent()
	{
		$repo = Page::orm();
		$repo->filter('id', '>', 1);
		$repo->filter('id', '<', 3);
		$repo->order('desc');

		$pages = $repo->all();
		$this->assertCount(1, $pages);
		$this->assertEquals('Second Page', $pages[0]->getTitle());
	}

	public function testLimit()
	{
		$pages = Page::orm()->limit(2)->all();
		$this->assertCount(2, $pages);
	}

	public function testAddCollumn()
	{
		$page = Page::orm()->addColumn('10 as id')->one();
		$this->assertEquals(10, $page->getId());

		Page::orm()->addColumn('20 as id');
		$page = Page::orm()->addColumn('"MyTitle" as title')->one();
		$this->assertEquals(20, $page->getId());
		$this->assertEquals('MyTitle', $page->getTitle());
	}

	public function testSetCollumns()
	{
		Page::orm()->addColumn('20 as id');
		$collumns = ['*', '"testDesc" as description', '"MyTitle" as title'];
		/** @var Page $page */
		$page = Page::orm()->setColumns($collumns)->one();
		$this->assertNotEquals(20, $page->getId());
		$this->assertEquals('testDesc', $page->getDescription());
		$this->assertEquals('MyTitle', $page->getTitle());
	}

	public function testFlush()
	{
		$pages = Page::orm()->filter('id', '>', 1)->all();
		$pages2 = Page::orm()->filter('id', '>', 0)->all();

		$this->assertCount(2, $pages);
		$this->assertCount(3, $pages2);
	}

	public function testDelete()
	{
		$pagesTotal = count(Page::orm()->all());
		$page = Page::orm()->find(1);
		$success = Page::orm()->delete($page);

		$this->assertTrue($success);
		$this->assertCount($pagesTotal - 1, Page::orm()->all());
	}

	public function testDebugOn()
	{
		Page::orm()->debugOn();
		ob_start();
		Page::orm()->all();
		$result = ob_get_clean();
		$this->assertContains('SELECT * FROM', $result);
	}

	public function testDebugOff()
	{
		Page::orm()->debugOn();
		ob_start();
		Page::orm()->debugOff();
		Page::orm()->all();
		$empty = ob_get_clean();
		$this->assertEmpty($empty);
	}

	public function testInsert()
	{
		$pagesTotal = count(Page::orm()->all());

		$page = new Page();
		$page->setTitle('Fourth Page');
		$page->setDescription('Inserted by save method');
		$success = Page::orm()->save($page);

		$this->assertTrue($success);
		$this->assertGreaterThan(0, $page->getId());
		$this->assertCount($pagesTotal + 1, Page::orm()->all());
	}

	public function testUpdate()
	{
		$pagesTotal = count(Page::orm()->all());
		$description = 'Updated by save method';

		$page = Page::orm()->order()->one();
		$page->setTitle('New Title');
		$page->setDescription($description);
		$success = Page::orm()->save($page);
		$pageUpdated = Page::orm()->order()->one();

		$this->assertTrue($success);
		$this->assertEquals('New Title', $pageUpdated->getTitle());
		$this->assertEquals($description, $pageUpdated->getDescription());
		$this->assertCount($pagesTotal, Page::orm()->all());
	}
}
