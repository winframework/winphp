<?php

namespace Win\Database\Orm;

use PHPUnit\Framework\TestCase;
use Win\Database\DbConfig;
use Win\Database\Mysql\MysqlConnection as Mysql;
use Win\Database\Orm\Page\Page;
use Win\Mvc\ApplicationTest;

class PageOrmTest extends TestCase
{
	public function setUp()
	{
		DbConfig::connect();
		static::createTable();
		static::importTable();
	}

	public static function createTable()
	{
		Mysql::instance()->query('DROP TABLE `Pages` ');
		Mysql::instance()->query('CREATE TABLE `Pages` (
			`Id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			`Title` varchar(75) NOT NULL,
			`Description` text NOT NULL,
			`CreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
	}

	public static function importTable()
	{
		Mysql::instance()->query("INSERT INTO `Pages` (`Id`, `Title`, `Description`, `CreatedAt`) VALUES
			(1, 'First Page', 'About us', '2018-11-04 10:46:03'),
			(2, 'Second Page', 'Contact us', '2018-11-04 12:05:01'),
			(3, 'Third Page', 'Sample Page', '2018-11-04 12:05:20');");
	}

	public function testRawQuery()
	{
		$orm = Page::orm();
		$orm->rawQuery('SELECT * FROM ' . $orm::TABLE
			. ' WHERE Id BETWEEN ? AND ? ORDER BY Id DESC', [2, 10]);

		$page = $orm->one();

		$this->assertEquals(3, $page->id);
	}

	public function testRunQuery()
	{
		$orm = Page::orm();
		$orm->rawQuery('SELECT * FROM ' . $orm::TABLE
			. ' WHERE Id BETWEEN ? AND ? ORDER BY Id DESC', [2, 10]);

		$success = $orm->runRaw();

		$this->assertTrue($success);
	}

	public function testRunInvalidQuery()
	{
		$orm = Page::orm()->rawQuery('INVALID QUERY');
		$success = $orm->runRaw();

		$this->assertFalse($success);
	}

	public function testRunWithoutRawQuery()
	{
		$success = Page::orm()->runRaw();
		$this->assertFalse($success);
	}

	public function testCount()
	{
		$count = Page::orm()->count();
		$this->assertEquals(3, $count);
	}

	public function testAll()
	{
		$pages = Page::orm()->list();
		$this->assertTrue(count($pages) > 1);
		$this->assertEquals('First Page', $pages[0]->title);
	}

	public function testFind()
	{
		$page = Page::orm()->find(2);
		$this->assertEquals(2, $page->id);
		$this->assertEquals('Second Page', $page->title);
	}

	public function testSortByOldest()
	{
		$page = Page::orm()
			->sortBy('Id', 'ASC')
			->one();
		$this->assertEquals(1, $page->id);
	}

	public function testSortByNewest()
	{
		$page = Page::orm()
			->sortBy('Id', 'DESC')
			->one();
		$this->assertEquals(3, $page->id);
	}

	public function testSortByWithPriority()
	{
		$pages = Page::orm()
			->sortBy('Id', 'DESC', 0)
			->sortBy('Title', 'ASC', 1)
			->list();
		$this->assertTrue(count($pages) > 1);
		$this->assertEquals('Third Page', $pages[0]->title);
	}

	public function testFilterBy()
	{
		$page = Page::orm()
			->filterBy('title', '=', 'Second Page')
			->one();
		$this->assertEquals(2, $page->id);
	}

	public function testFilterByNotNull()
	{
		$page = Page::orm()
			->filterBy('title', 'IS NOT NULL')
			->one();
		$this->assertEquals(1, $page->id);
	}

	public function testFilterByNull()
	{
		$count = Page::orm()
			->filterBy('title', 'IS NULL')
			->count();
		$this->assertEquals(0, $count);
	}

	public function testFilterByLike()
	{
		$page = Page::orm()
			->filterBy('title', 'LIKE', '%Second%')
			->one();
		$this->assertEquals(2, $page->id);
	}

	public function testFilter()
	{
		$page = Page::orm()
			->filter('Title', 'Second Page')
			->one();
		$this->assertEquals($page->id, 2);
	}

	public function testFilterAndSort()
	{
		$orm = Page::orm();
		$orm->filterBy('Id', '>', 1);
		$orm->filterBy('Id', '<', 3);
		$orm->sortBy('Id');

		$pages = $orm->list();
		$this->assertCount(1, $pages);
		$this->assertEquals('Second Page', $pages[0]->title);
	}

	public function testOrFailReturns()
	{
		$orm = Page::orm()->one()->orFail();
		$this->assertEquals('First Page', $orm->title);
	}

	/** @expectedException \Win\Mvc\ErrorResponse */
	public function testOrFailException()
	{
		ApplicationTest::newApp();
		$orm = Page::orm()->filter('Id', 100)->one()->orFail();
		$this->assertEquals('First Page', $orm->title);
	}

	public function testPaginate()
	{
		$orm = Page::orm()->paginate(1, 2);
		$this->assertEquals(1, count($orm->list()));
		$this->assertEquals(3, $orm->count());
		$this->assertEquals(2, $orm->pagination->current());
	}

	public function testPaginateInvalid()
	{
		$orm = Page::orm()->paginate(2, 200);
		$this->assertEquals(1, count($orm->list()));
		$this->assertEquals(3, $orm->count());
		$this->assertEquals(2, $orm->pagination->current());
	}

	// public function testAddCollumn()
	// {
	// 	$page = Page::orm()->addColumn('10 as id')->one();
	// 	$this->assertEquals(10, $page->id);

	// 	Page::orm()->addColumn('20 as id');
	// 	$page = Page::orm()->addColumn('"MyTitle" as title')->one();
	// 	$this->assertEquals(20, $page->id);
	// 	$this->assertEquals('MyTitle', $page->title);
	// }

	// public function testSetCollumns()
	// {
	// 	Page::orm()->addColumn('20 as id');
	// 	$collumns = ['*', '"testDesc" as description', '"MyTitle" as title'];
	// 	/** @var Page $page */
	// 	$page = Page::orm()->setColumns($collumns)->one();
	// 	$this->assertNotEquals(20, $page->id);
	// 	$this->assertEquals('testDesc', $page->getDescription());
	// 	$this->assertEquals('MyTitle', $page->title);
	// }

	public function testFlush()
	{
		$orm = Page::orm();
		$pages = $orm->filterBy('id', '>', 1)->list();
		$pages2 = $orm->list();
		$pages3 = Page::orm()->list();

		$this->assertCount(2, $pages);
		$this->assertCount(2, $pages2);
		$this->assertCount(3, $pages3);
	}

	public function testDelete()
	{
		$pagesCount = count(Page::orm()->list());
		$success = Page::orm()->filter('Id', 2)->delete();

		$this->assertTrue($success);
		$this->assertCount($pagesCount - 1, Page::orm()->list());
	}

	public function testDestroy()
	{
		$pagesCount = count(Page::orm()->list());
		$success = Page::orm()->destroy(2);
		$newCount = count(Page::orm()->list());

		$this->assertTrue($success);
		$this->assertNotEquals($pagesCount, $newCount);
	}

	public function testDebugOn()
	{
		$orm = Page::orm();
		ob_start();
		$orm->debug = true;
		$orm->list();
		$result = ob_get_clean();
		$this->assertContains('SELECT * FROM', $result);
	}

	public function testDebugOff()
	{
		$orm = Page::orm();
		ob_start();
		$orm->debug = false;
		$orm->list();
		$empty = ob_get_clean();
		$this->assertEmpty($empty);
	}

	public function testInsert()
	{
		$pagesTotal = count(Page::orm()->list());

		$page = new Page();
		$page->title = 'Fourth Page';
		$page->description = 'Inserted by save method';
		$success = Page::orm()->save($page);

		$this->assertTrue($success);
		$this->assertGreaterThan(0, $page->id);
		$this->assertCount($pagesTotal + 1, Page::orm()->list());
	}

	public function testUpdate()
	{
		$pagesTotal = count(Page::orm()->list());
		$description = 'Updated by save method';

		$page = Page::orm()->sortBy('Id', 'DESC')->one();
		$page->title = 'New Title';
		$page->description = $description;
		$success = Page::orm()->save($page);
		$pageUpdated = Page::orm()->sortBy('Id', 'DESC')->one();

		$this->assertTrue($success);
		$this->assertEquals('New Title', $pageUpdated->title);
		$this->assertEquals($description, $pageUpdated->description);
		$this->assertCount($pagesTotal, Page::orm()->list());
	}
}
