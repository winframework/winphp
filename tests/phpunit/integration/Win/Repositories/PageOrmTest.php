<?php

namespace Win\Repositories;

use App\Models\Page;
use App\Repositories\PageOrm;
use PHPUnit\Framework\TestCase;
use Win\ApplicationTest;
use Win\Repositories\Database\Connection;
use Win\Repositories\Database\DbConfig;
use Win\Repositories\Database\Mysql;

class PageOrmTest extends TestCase
{
	/** @var Connection */
	static $conn;

	public static function setUpBeforeClass()
	{
		static::$conn = new Mysql(DbConfig::valid());
	}

	public function setUp()
	{
		static::createTable();
		static::importTable();
	}

	public static function createTable()
	{
		static::$conn->execute('DROP TABLE IF EXISTS `pages` ');
		static::$conn->execute('CREATE TABLE `pages` (
			`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			`categoryId` int(11) NULL,
			`title` varchar(75) NOT NULL,
			`description` text NOT NULL,
			`createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			`updatedAt` datetime NULL DEFAULT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
	}

	public static function importTable()
	{
		static::$conn->execute("INSERT INTO `pages` (`id`, `categoryId`, `title`, `description`, `createdAt`) VALUES
			(1, NULL, 'First Page', 'About us', '2018-11-04 10:46:03'),
			(2, 1, 'Second Page', 'Contact us', '2018-11-04 12:05:01'),
			(3, 2, 'Third Page', 'Sample Page', '2018-11-04 12:05:20');");
	}

	public function testRunRawQuery()
	{
		$orm = (new PageOrm(static::$conn));
		$success = $orm->execute('SELECT * FROM ' . $orm::TABLE
			. ' WHERE id BETWEEN ? AND ? ORDER BY id DESC', 2, 10);

		$this->assertTrue($success);
	}

	public function testCount()
	{
		$count = (new PageOrm(static::$conn))->count();
		$this->assertEquals(3, $count);
	}

	public function testList()
	{
		$pages = (new PageOrm(static::$conn))->list();
		$this->assertTrue(count($pages) > 1);
		$this->assertEquals('First Page', $pages[0]->title);
	}

	public function testFind()
	{
		$page = (new PageOrm(static::$conn))->find(2);
		$this->assertEquals(2, $page->id);
		$this->assertEquals('Second Page', $page->title);
	}

	/** @expectedException Win\HttpException */
	public function testFindOr404()
	{
		(new PageOrm(static::$conn))->findOr404(200);
	}

	public function testSort()
	{
		$page = (new PageOrm(static::$conn))
			->sort('id ASC')
			->one();
		$this->assertEquals(1, $page->id);
	}

	public function testSortWithPriority()
	{
		$pages = (new PageOrm(static::$conn))
			->sort('id DESC', 0)
			->sort('Title ASC', 1)
			->list();
		$this->assertTrue(count($pages) > 1);
		$this->assertEquals('Third Page', $pages[0]->title);
	}

	public function testFilterByEquals()
	{
		$page = (new PageOrm(static::$conn))
			->filter('title = ?', 'Second Page')
			->one();
		$this->assertEquals(2, $page->id);
	}

	public function testFilterByNotNull()
	{
		$page = (new PageOrm(static::$conn))
			->filterBy('title IS NOT NULL')
			->one();
		$this->assertEquals(1, $page->id);
	}

	public function testFilterByNull()
	{
		$count = (new PageOrm(static::$conn))
			->filter('title IS NULL')
			->count();
		$this->assertEquals(0, $count);
	}

	public function testFilterByLike()
	{
		$page = (new PageOrm(static::$conn))
			->filter('title LIKE ?', '%Second%')
			->one();
		$this->assertEquals(2, $page->id);
	}

	public function testFilter()
	{
		$page = (new PageOrm(static::$conn))
			->filter('Title', 'Second Page')
			->one();
		$this->assertEquals($page->id, 2);
	}

	public function testFilterBy()
	{
		$count = (new PageOrm(static::$conn))
			->filterBy(
				'title LIKE ? OR id > ?',
				['%Second%', 2]
			)->count();
		$this->assertEquals(2, $count);
	}

	public function testFilterByBindParams()
	{
		$count = (new PageOrm(static::$conn))
			->filterBy(
				'title LIKE :title OR id > :id',
				[':title' => '%Second%', ':id' => 2]
			)->count();
		$this->assertEquals(2, $count);
	}

	public function testFilterAndSort()
	{
		$orm = (new PageOrm(static::$conn));
		$orm->filter('id > ?', 1);
		$orm->filter('id < ?', 3);
		$orm->sort('id');

		$pages = $orm->list();
		$this->assertCount(1, $pages);
		$this->assertEquals('Second Page', $pages[0]->title);
	}

	public function testOne()
	{
		$orm = new PageOrm(static::$conn);
		$page = $orm->find(3);

		$this->assertEquals(3, $page->id);
		$this->assertEquals('Third Page', $page->title);
	}

	public function testSelect()
	{
		$title = 'Teste';
		$orm = new PageOrm(static::$conn);
		$orm->select('*, "' . $title . '" as title');
		$page = $orm->find(3);

		$this->assertEquals(3, $page->id);
		$this->assertEquals($title, $page->title);
	}

	public function testOneOr404()
	{
		$orm = (new PageOrm(static::$conn))->oneOr404();
		$this->assertEquals('First Page', $orm->title);
	}

	/** @expectedException Win\HttpException */
	public function testOneOr404Exception()
	{
		ApplicationTest::newApp();
		(new PageOrm(static::$conn))->filter('id', 100)->oneOr404();
	}

	public function testPaginate()
	{
		$orm = (new PageOrm(static::$conn))->paginate(1, 2);
		$this->assertEquals(1, count($orm->list()));
		$this->assertEquals(3, $orm->count());
		$this->assertEquals(2, $orm->pagination->current);
	}

	public function testPaginateInvalid()
	{
		$orm = (new PageOrm(static::$conn))->paginate(2, 200);
		$this->assertEquals(1, count($orm->list()));
		$this->assertEquals(3, $orm->count());
		$this->assertEquals(2, $orm->pagination->current);
	}

	public function testFlush()
	{
		$orm = (new PageOrm(static::$conn));
		$pages = $orm->filter('id > ?', 1)->list();
		$pages2 = $orm->list();
		$pages3 = (new PageOrm(static::$conn))->list();

		$this->assertCount(2, $pages);
		$this->assertCount(3, $pages2);
		$this->assertCount(3, $pages3);
	}

	public function testDelete()
	{
		$pagesCount = count((new PageOrm(static::$conn))->list());
		(new PageOrm(static::$conn))->filter('id', 2)->delete();

		$this->assertCount($pagesCount - 1, (new PageOrm(static::$conn))->list());
	}

	public function testDestroy()
	{
		$pagesCount = count((new PageOrm(static::$conn))->list());
		(new PageOrm(static::$conn))->destroy(2);
		$newCount = count((new PageOrm(static::$conn))->list());

		$this->assertNotEquals($pagesCount, $newCount);
	}

	public function testSave()
	{
		$pagesTotal = count((new PageOrm(static::$conn))->list());

		$page = new Page();
		$page->title = 'Fourth Page';
		$page->description = 'Inserted by save method';
		$result = (new PageOrm(static::$conn))->save($page);

		$this->assertInstanceOf(Page::class, $result);
		$this->assertGreaterThan(0, $page->id);
		$this->assertCount($pagesTotal + 1, (new PageOrm(static::$conn))->list());
	}

	public function testSaveExisting()
	{
		$pagesTotal = count((new PageOrm(static::$conn))->list());
		$description = 'Updated by save method';

		$page = (new PageOrm(static::$conn))->sort('id DESC')->one();
		$page->title = 'New Title';
		$page->description = $description;
		$pageAfterSave = (new PageOrm(static::$conn))->save($page);
		$pageUpdated = (new PageOrm(static::$conn))->sort('id DESC')->one();

		$this->assertEquals($page->title, $pageUpdated->title);
		$this->assertEquals($page->title, $pageAfterSave->title);
		$this->assertEquals($description, $pageUpdated->description);
		$this->assertCount($pagesTotal, (new PageOrm(static::$conn))->list());
	}

	public function testUpdate()
	{
		$title = 'New title';
		$orm = (new PageOrm(static::$conn));

		// Act
		$total = $orm->filter('id >= ?', 2)->update(['title' => $title]);
		$pages = $orm->list();

		// Assert
		$this->assertEquals(2, $total);
		$this->assertNotEquals($title, $pages[0]->title);
		$this->assertEquals($title, $pages[1]->title);
		$this->assertEquals($title, $pages[2]->title);
	}

	public function testJoin()
	{
		$orm = (new PageOrm(static::$conn))
			->join('pageCategories as pc ON pages.categoryId = pc.id');
		$pages = $orm->list();
		$this->assertCount(2, $pages);
		$this->assertEquals('Second Category', $pages[1]->title);
	}

	public function testLeftJoin()
	{
		$orm = (new PageOrm(static::$conn))
			->leftJoin('pageCategories as pc ON pages.categoryId = pc.id');
		$pages = $orm->list();

		$this->assertCount(3, $pages);
		$this->assertEquals('First Category', $pages[0]->title);
		$this->assertEquals(null, $pages[2]->title);
	}

	public function testRightJoin()
	{
		$orm = (new PageOrm(static::$conn))
			->rightJoin('pageCategories as pc ON pages.categoryId = pc.id');
		$pages = $orm->list();

		$this->assertCount(3, $pages);
		$this->assertEquals('First Category', $pages[0]->title);
		$this->assertEquals('Disabled Category', $pages[2]->title);
	}

	public function testDebug()
	{
		$debug = (new PageOrm(static::$conn))->filter('id > ?', 10)->debug();
		$this->assertContains('SELECT * FROM pages WHERE (id > ?)', $debug[0]);
		$this->assertEquals([10], $debug[1]);

		$debug = (new PageOrm(static::$conn))->debug('insert');
		$this->assertContains('INSERT INTO', $debug[0]);
		$this->assertEquals([], $debug[1]);
	}
}
