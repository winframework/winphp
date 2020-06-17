<?php

namespace Win\Repositories;

use App\Models\Page;
use App\Repositories\PageRepo;
use PHPUnit\Framework\TestCase;
use Win\ApplicationTest;
use Win\Common\Pagination;
use Win\Repositories\Database\Connection;
use Win\Repositories\Database\DbConfig;
use Win\Repositories\Database\Mysql;

class PageRepoTest extends TestCase
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
		$repo = new PageRepo(new Pagination());
		$repo = $repo;
		$repo->conn = static::$conn;
		$success = $repo->execute('SELECT * FROM ' . $repo::TABLE
			. ' WHERE id BETWEEN ? AND ? ORDER BY id DESC', 2, 10);

		$this->assertTrue($success);
	}

	public function testCount()
	{
		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;
		$count = $repo->count();
		$this->assertEquals(3, $count);
	}

	public function testList()
	{
		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;
		$pages = $repo->list();
		$this->assertTrue(count($pages) > 1);
		$this->assertEquals('First Page', $pages[0]->title);
	}

	public function testFind()
	{
		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;
		$page = $repo->find(2);
		$this->assertEquals(2, $page->id);
		$this->assertEquals('Second Page', $page->title);
	}

	/** @expectedException Win\HttpException */
	public function testFindOr404()
	{
		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;
		$repo->findOr404(200);
	}

	public function testSort()
	{
		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;
		$page = $repo
			->sort('id ASC')
			->one();
		$this->assertEquals(1, $page->id);
	}

	public function testSortWithPriority()
	{
		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;
		$pages = $repo
			->sort('id DESC', 0)
			->sort('Title ASC', 1)
			->list();
		$this->assertTrue(count($pages) > 1);
		$this->assertEquals('Third Page', $pages[0]->title);
	}

	public function testFilterByEquals()
	{
		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;
		$page = $repo
			->filter('title = ?', 'Second Page')
			->one();
		$this->assertEquals(2, $page->id);
	}

	public function testFilterByNotNull()
	{
		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;
		$page = $repo
			->filterBy('title IS NOT NULL')
			->one();
		$this->assertEquals(1, $page->id);
	}

	public function testFilterByNull()
	{
		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;
		$count = $repo
			->filter('title IS NULL')
			->count();
		$this->assertEquals(0, $count);
	}

	public function testFilterByLike()
	{
		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;
		$page = $repo
			->filter('title LIKE ?', '%Second%')
			->one();
		$this->assertEquals(2, $page->id);
	}

	public function testFilter()
	{
		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;
		$page = $repo
			->filter('Title', 'Second Page')
			->one();
		$this->assertEquals($page->id, 2);
	}

	public function testFilterBy()
	{
		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;
		$count = $repo
			->filterBy(
				'title LIKE ? OR id > ?',
				['%Second%', 2]
			)->count();
		$this->assertEquals(2, $count);
	}

	public function testFilterByBindParams()
	{
		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;
		$count = $repo
			->filterBy(
				'title LIKE :title OR id > :id',
				[':title' => '%Second%', ':id' => 2]
			)->count();
		$this->assertEquals(2, $count);
	}

	public function testFilterAndSort()
	{
		$repo = new PageRepo(new Pagination());
		$repo = $repo;
		$repo->conn = static::$conn;
		$repo->filter('id > ?', 1);
		$repo->filter('id < ?', 3);
		$repo->sort('id');

		$pages = $repo->list();
		$this->assertCount(1, $pages);
		$this->assertEquals('Second Page', $pages[0]->title);
	}

	public function testOne()
	{
		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;
		$page = $repo->find(3);

		$this->assertEquals(3, $page->id);
		$this->assertEquals('Third Page', $page->title);
	}

	public function testSelect()
	{
		$title = 'Teste';
		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;
		$repo->select('*, "' . $title . '" as title');
		$page = $repo->find(3);

		$this->assertEquals(3, $page->id);
		$this->assertEquals($title, $page->title);
	}

	public function testOneOr404()
	{
		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;
		$repo = $repo->oneOr404();
		$this->assertEquals('First Page', $repo->title);
	}

	/** @expectedException Win\HttpException */
	public function testOneOr404Exception()
	{
		ApplicationTest::newApp();
		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;
		$repo->filter('id', 100)->oneOr404();
	}

	public function testPaginate()
	{
		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;
		$repo = $repo->paginate(1, 2);
		$this->assertEquals(1, count($repo->list()));
		$this->assertEquals(3, $repo->count());
		$this->assertEquals(2, $repo->pagination->current);
	}

	public function testPaginateInvalid()
	{
		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;
		$repo = $repo->paginate(2, 200);
		$this->assertEquals(1, count($repo->list()));
		$this->assertEquals(3, $repo->count());
		$this->assertEquals(2, $repo->pagination->current);

		$repo = $repo->paginate(0);
		$this->assertEquals(3, $repo->count());
		$this->assertEquals(1, $repo->pagination->current);

		$repo = $repo->paginate(10, 0);
		$this->assertEquals(3, $repo->count());
		$this->assertEquals(1, $repo->pagination->current);
	}

	public function testFlush()
	{
		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;
		$pages = $repo->filter('id > ?', 1)->list();

		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;
		$pages2 = $repo->list();

		$pages3 = $repo->list();

		$this->assertCount(2, $pages);
		$this->assertCount(3, $pages2);
		$this->assertCount(3, $pages3);
	}

	public function testDelete()
	{
		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;
		$pagesCount = count($repo->list());
		$repo->filter('id', 2)->delete();
		
		$this->assertCount($pagesCount - 1, $repo->list());
		$repo->conn = static::$conn;
	}

	public function testDestroy()
	{
		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;
		$pagesCount = count($repo->list());
		$repo->destroy(2);

		$newCount = count($repo->list());
		$this->assertNotEquals($pagesCount, $newCount);
	}

	public function testSave()
	{
		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;
		$pagesTotal = count($repo->list());

		$page = new Page();
		$page->title = 'Fourth Page';
		$page->description = 'Inserted by save method';
		$result = $repo->save($page);

		$this->assertInstanceOf(Page::class, $result);
		$this->assertGreaterThan(0, $page->id);
		$this->assertCount($pagesTotal + 1, $repo->list());
	}

	public function testSaveExisting()
	{
		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;
		$pagesTotal = count($repo->list());
		$description = 'Updated by save method';

		$page = $repo->sort('id DESC')->one();
		$page->title = 'New Title';
		$page->description = $description;
		$pageAfterSave = $repo->save($page);
		$pageUpdated = $repo->sort('id DESC')->one();

		$this->assertEquals($page->title, $pageUpdated->title);
		$this->assertEquals($page->title, $pageAfterSave->title);
		$this->assertEquals($description, $pageUpdated->description);
		$this->assertCount($pagesTotal, $repo->list());
		$repo->conn = static::$conn;
	}

	public function testUpdate()
	{
		$title = 'New title';
		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;

		// Act
		$total = $repo->filter('id >= ?', 2)->update(['title' => $title]);
		$pages = $repo->list();

		// Assert
		$this->assertEquals(2, $total);
		$this->assertNotEquals($title, $pages[0]->title);
		$this->assertEquals($title, $pages[1]->title);
		$this->assertEquals($title, $pages[2]->title);
	}

	public function testJoin()
	{
		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;
		$repo = $repo
			->join('pageCategories as pc ON pages.categoryId = pc.id');
		$pages = $repo->list();
		$this->assertCount(2, $pages);
		$this->assertEquals('Second Category', $pages[1]->title);
	}

	public function testLeftJoin()
	{
		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;
		$repo = $repo
			->leftJoin('pageCategories as pc ON pages.categoryId = pc.id');
		$pages = $repo->list();

		$this->assertCount(3, $pages);
		$this->assertEquals('First Category', $pages[0]->title);
		$this->assertEquals(null, $pages[2]->title);
	}

	public function testRightJoin()
	{
		$repo = new PageRepo(new Pagination());
		$repo->conn = static::$conn;
		$repo = $repo
			->rightJoin('pageCategories as pc ON pages.categoryId = pc.id');
		$pages = $repo->list();

		$this->assertCount(3, $pages);
		$this->assertEquals('First Category', $pages[0]->title);
		$this->assertEquals('Disabled Category', $pages[2]->title);
	}

	public function testDebug()
	{
		$repo = new PageRepo(new Pagination());
		$debug = $repo->filter('id > ?', 10)->debug();
		$repo->conn = static::$conn;
		$this->assertContains('SELECT * FROM pages WHERE (id > ?)', $debug[0]);
		$this->assertEquals([10], $debug[1]);

		$repo = new PageRepo(new Pagination());
		$debug = $repo->debug('insert');
		$repo->conn = static::$conn;
		$this->assertContains('INSERT INTO', $debug[0]);
		$this->assertEquals([], $debug[1]);
	}
}
