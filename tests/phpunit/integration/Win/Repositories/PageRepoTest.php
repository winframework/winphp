<?php

namespace Win\Repositories;

use PDO;
use App\Models\Page;
use App\Repositories\PageRepo;
use PHPUnit\Framework\TestCase;
use Win\ApplicationTest;
use Win\Services\Pagination;
use Win\Repositories\DbConfig;
use Win\Services\Filesystem;

class PageRepoTest extends TestCase
{
	/** @var PDO */
	static $pdo;
	static $query;

	public static function setUpBeforeClass(): void
	{
		static::$pdo = DbConfig::valid();
		$fs = new Filesystem();
		static::$query = $fs->read('../database/winphp_demo.sql');
		static::$pdo->exec(static::$query);
	}

	public function setUp()
	{
		static::$pdo->exec("TRUNCATE TABLE `pages`");
		static::$pdo->exec("INSERT INTO `pages` (`id`, `categoryId`, `title`, `description`, `createdAt`) VALUES
		(1, NULL, 'First Page', 'About us', '2018-11-04 10:46:03'),
		(2, 1, 'Second Page', 'Contact us', '2018-11-04 12:05:01'),
		(3, 2, 'Third Page', 'Sample Page', '2018-11-04 12:05:20');");
	}

	public function testPdoExecute()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$success = $repo->pdo
			->prepare('SELECT * FROM `pages` WHERE id BETWEEN ? AND ? ORDER BY id DESC')
			->execute([2, 10]);

		$this->assertTrue($success);
	}

	public function testCount()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$count = $repo->count();
		$this->assertEquals(3, $count);
	}

	/** @expectedException Win\Repositories\DbException */
	public function testCountError()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$repo->if('INEXISTENT = 2');
		$repo->count();
	}

	public function testList()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$pages = $repo->list();
		$this->assertTrue(count($pages) > 1);
		$this->assertEquals('First Page', $pages[0]->title);
	}

	/** @expectedException Win\Repositories\DbException */
	public function testListError()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$repo->if('DOTEXISTEND = 1');
		$repo->list();
	}

	public function testFind()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$page = $repo->find(2);
		$this->assertEquals(2, $page->id);
		$this->assertEquals('Second Page', $page->title);
	}

	public function testSetTable()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$page = $repo->setTable('pages')->find(2);
		$this->assertEquals('Second Page', $page->title);
		$this->assertEquals('pages', $repo->getTable());
	}

	/** @expectedException Win\HttpException */
	public function testFindOr404()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$repo->findOr404(200);
	}

	public function testSort()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$page = $repo
			->sort('id ASC')
			->one();
		$this->assertEquals(1, $page->id);
	}

	public function testSortWithPriority()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$pages = $repo
			->sort('id DESC', 0)
			->sort('Title ASC', 1)
			->list();
		$this->assertTrue(count($pages) > 1);
		$this->assertEquals('Third Page', $pages[0]->title);
	}

	public function testIfEquals()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$page = $repo
			->if('title = ?', 'Second Page')
			->one();
		$this->assertEquals(2, $page->id);
	}

	public function testIfNotNull()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$page = $repo
			->filter('title IS NOT NULL')
			->one();
		$this->assertEquals(1, $page->id);
	}

	public function testIfNull()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$count = $repo
			->if('title IS NULL')
			->count();
		$this->assertEquals(0, $count);
	}

	public function testIfLike()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$page = $repo
			->if('title LIKE ?', '%Second%')
			->one();
		$this->assertEquals(2, $page->id);
	}

	public function testIf()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$page = $repo
			->if('Title', 'Second Page')
			->one();
		$this->assertEquals($page->id, 2);
	}

	public function testFilter()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$count = $repo
			->filter(
				'title LIKE ? OR id > ?',
				['%Second%', 2]
			)->count();
		$this->assertEquals(2, $count);
	}

	public function testFilterBindParams()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$count = $repo
			->filter(
				'title LIKE :title OR id > :id',
				[':title' => '%Second%', ':id' => 2]
			)->count();
		$this->assertEquals(2, $count);
	}

	public function testIfAndSort()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$repo->if('id > ?', 1);
		$repo->if('id < ?', 3);
		$repo->sort('id');

		$pages = $repo->list();
		$this->assertCount(1, $pages);
		$this->assertEquals('Second Page', $pages[0]->title);
	}

	public function testOne()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$page = $repo->if('id = ?', 3)->one();

		$this->assertEquals(3, $page->id);
		$this->assertEquals('Third Page', $page->title);
	}

	/** @expectedException Win\Repositories\DbException */
	public function testOneError()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$page = $repo->if('INEXISTENT = ?', 3)->one();
	}

	public function testSelect()
	{
		$title = 'Teste';
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$repo->select("*, '{$title}' as title");
		$page = $repo->find(3);

		$this->assertEquals(3, $page->id);
		$this->assertEquals($title, $page->title);
	}

	public function testOneOr404()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$repo = $repo->oneOr404();
		$this->assertEquals('First Page', $repo->title);
	}

	/** @expectedException Win\HttpException */
	public function testOneOr404Exception()
	{
		ApplicationTest::newApp();
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$repo->if('id', 100)->oneOr404();
	}

	public function testPaginate()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$repo = $repo->paginate(1, 2);
		$this->assertEquals(1, count($repo->list()));
		$this->assertEquals(3, $repo->count());
		$this->assertEquals(2, $repo->pagination->current);
	}

	public function testPaginateInvalid()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
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
		$repo->pdo = static::$pdo;
		$pages = $repo->if('id > ?', 1)->list();

		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$pages2 = $repo->list();

		$pages3 = $repo->list();

		$this->assertCount(2, $pages);
		$this->assertCount(3, $pages2);
		$this->assertCount(3, $pages3);
	}

	public function testDelete()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$pagesCount = count($repo->list());
		$repo->if('id', 2)->delete();

		$this->assertCount($pagesCount - 1, $repo->list());
		$repo->pdo = static::$pdo;
	}

	/** @expectedException Win\Repositories\DbException */
	public function testDeleteError()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$repo->if('id > ?')->delete();
	}

	public function testDestroy()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$pagesCount = count($repo->list());
		$repo->destroy(2);

		$newCount = count($repo->list());
		$this->assertNotEquals($pagesCount, $newCount);
	}

	public function testSave()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$pagesTotal = count($repo->list());

		$page = new Page();
		$page->title = 'Fourth Page';
		$page->description = 'Inserted by save method';
		$repo->save($page);

		$this->assertEquals($pagesTotal + 1, $page->id);
		$this->assertCount($pagesTotal + 1, $repo->list());
	}

	/** @expectedException Win\Repositories\DbException */
	public function testSaveError()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$page = new Page();
		$page->title = null; //title is required
		$repo->save($page);
	}

	public function testSaveExisting()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$pagesTotal = count($repo->list());
		$description = 'Updated by save method';

		$page = $repo->sort('id DESC')->one();
		$page->title = 'New Title';
		$page->description = $description;
		$repo->save($page);
		$pageUpdated = $repo->sort('id DESC')->one();

		$this->assertEquals($page->title, $pageUpdated->title);
		$this->assertEquals($description, $pageUpdated->description);
		$this->assertCount($pagesTotal, $repo->list());
		$repo->pdo = static::$pdo;
	}

	public function testUpdate()
	{
		$title = 'New title';
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;

		// Act
		$total = $repo->if('id >= ?', 2)->update(['title' => $title]);
		$pages = $repo->list();

		// Assert
		$this->assertEquals(2, $total);
		$this->assertNotEquals($title, $pages[0]->title);
		$this->assertEquals($title, $pages[1]->title);
		$this->assertEquals($title, $pages[2]->title);
	}

	/** @expectedException Win\Repositories\DbException */
	public function testUpdateError()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$repo->if('id >= ?', 2)->update(['NOT-EXISTENT-FIELD' => 'Value']);
	}

	public function testJoin()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$repo = $repo
			->join('pageCategories as pc ON pages.categoryId = pc.id');
		$pages = $repo->list();
		$this->assertCount(2, $pages);
		$this->assertEquals('Second Category', $pages[1]->title);
	}

	public function testLeftJoin()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
		$repo = $repo
			->leftJoin('pageCategories as pc ON pages.categoryId = pc.id');
		$pages = $repo->list();

		$this->assertCount(3, $pages);
		$this->assertEquals(null, $pages[0]->title);
		$this->assertEquals('Second Category', $pages[2]->title);
	}

	public function testRightJoin()
	{
		$repo = new PageRepo(new Pagination());
		$repo->pdo = static::$pdo;
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
		$debug = $repo->if('id > ?', 10)->debug();
		$repo->pdo = static::$pdo;
		$this->assertContains('SELECT * FROM pages WHERE (id > ?)', $debug[0]);
		$this->assertEquals(10, $debug[1]);

		$repo = new PageRepo(new Pagination());
		$debug = $repo->debug('insert');
		$repo->pdo = static::$pdo;
		$this->assertContains('INSERT INTO', $debug[0]);
	}
}
