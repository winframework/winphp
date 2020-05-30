<?php

namespace Win\Repositories\Database\Orm;

use App\Models\Page;
use App\Repositories\PageOrm;
use PHPUnit\Framework\TestCase;
use Win\ApplicationTest;
use Win\Repositories\Database\DbConfig;
use Win\Repositories\Database\MysqlConnection as Mysql;
use Win\Repositories\Database\Transaction;

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
		Mysql::instance()->execute('DROP TABLE `pages` ');
		Mysql::instance()->execute('CREATE TABLE `pages` (
			`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			`categoryId` int(11) NULL,
			`title` varchar(75) NOT NULL,
			`description` text NOT NULL,
			`createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
	}

	public static function importTable()
	{
		Mysql::instance()->execute("INSERT INTO `pages` (`id`, `title`, `description`, `createdAt`) VALUES
			(1, 'First Page', 'About us', '2018-11-04 10:46:03'),
			(2, 'Second Page', 'Contact us', '2018-11-04 12:05:01'),
			(3, 'Third Page', 'Sample Page', '2018-11-04 12:05:20');");
	}

	public function testRawQuery()
	{
		$orm = (new PageOrm());
		$orm->rawQuery('SELECT * FROM ' . $orm::TABLE
			. ' WHERE id BETWEEN ? AND ? ORDER BY id DESC', [2, 10]);

		$page = $orm->one();

		$this->assertEquals(3, $page->id);
	}

	public function testRunRawQuery()
	{
		$orm = (new PageOrm());
		$orm->rawQuery('SELECT * FROM ' . $orm::TABLE
			. ' WHERE id BETWEEN ? AND ? ORDER BY id DESC', [2, 10]);

		$success = $orm->run();

		$this->assertTrue($success);
	}

	/**
	 * @expectedException \Win\Repositories\Database\DatabaseException
	 */
	public function testRunInvalidQuery()
	{
		$orm = (new PageOrm())->rawQuery('INVALID QUERY');
		$success = $orm->run();

		$this->assertFalse($success);
	}

	/**
	 * @expectedException \Win\Repositories\Database\DatabaseException
	 */
	public function testRunWithoutRawQuery()
	{
		$success = (new PageOrm())->run();
		$this->assertFalse($success);
	}

	public function testCount()
	{
		$count = (new PageOrm())->count();
		$this->assertEquals(3, $count);
	}

	public function testAll()
	{
		$pages = (new PageOrm())->list();
		$this->assertTrue(count($pages) > 1);
		$this->assertEquals('First Page', $pages[0]->title);
	}

	public function testFind()
	{
		$page = (new PageOrm())->find(2);
		$this->assertEquals(2, $page->id);
		$this->assertEquals('Second Page', $page->title);
	}

	public function testSortBy()
	{
		$page = (new PageOrm())
			->sortBy('id', 'ASC')
			->one();
		$this->assertEquals(1, $page->id);
	}

	public function testSortNewest()
	{
		$page = (new PageOrm())
			->sortNewest()
			->one();
		$this->assertEquals(3, $page->id);
	}

	public function testSortOldest()
	{
		$page = (new PageOrm())
			->sortOldest()
			->one();
		$this->assertEquals(1, $page->id);
	}

	public function testSortRand()
	{
		// TODO: How to test RAND() ?
		$orm = new PageOrm();
		$count = $orm
			->sortRand()
			->count();
		$this->assertEquals(3, $count);
	}

	public function testSortByWithPriority()
	{
		$pages = (new PageOrm())
			->sortBy('id', 'DESC', 0)
			->sortBy('Title', 'ASC', 1)
			->list();
		$this->assertTrue(count($pages) > 1);
		$this->assertEquals('Third Page', $pages[0]->title);
	}

	public function testFilterByEquals()
	{
		$page = (new PageOrm())
			->filterBy('title = ?', 'Second Page')
			->one();
		$this->assertEquals(2, $page->id);
	}

	public function testFilterByNotNull()
	{
		$page = (new PageOrm())
			->filterBy('title IS NOT NULL')
			->one();
		$this->assertEquals(1, $page->id);
	}

	public function testFilterByNull()
	{
		$count = (new PageOrm())
			->filterBy('title IS NULL')
			->count();
		$this->assertEquals(0, $count);
	}

	public function testFilterByLike()
	{
		$page = (new PageOrm())
			->filterBy('title LIKE ?', '%Second%')
			->one();
		$this->assertEquals(2, $page->id);
	}

	public function testFilterBy()
	{
		$page = (new PageOrm())
			->filterBy('Title', 'Second Page')
			->one();
		$this->assertEquals($page->id, 2);
	}

	public function testFilterAndSort()
	{
		$orm = (new PageOrm());
		$orm->filterBy('id > ?', 1);
		$orm->filterBy('id < ?', 3);
		$orm->sortBy('id');

		$pages = $orm->list();
		$this->assertCount(1, $pages);
		$this->assertEquals('Second Page', $pages[0]->title);
	}

	public function testOrFailReturns()
	{
		$orm = (new PageOrm())->one()->or404();
		$this->assertEquals('First Page', $orm->title);
	}

	/** @expectedException Win\Request\HttpException */
	public function testOrFailException()
	{
		ApplicationTest::newApp();
		$orm = (new PageOrm())->filterBy('id', 100)->one()->or404();
		$this->assertEquals('First Page', $orm->title);
	}

	public function testPaginate()
	{
		$orm = (new PageOrm())->paginate(1, 2);
		$this->assertEquals(1, count($orm->list()));
		$this->assertEquals(3, $orm->count());
		$this->assertEquals(2, $orm->pagination->current());
	}

	public function testPaginateInvalid()
	{
		$orm = (new PageOrm())->paginate(2, 200);
		$this->assertEquals(1, count($orm->list()));
		$this->assertEquals(3, $orm->count());
		$this->assertEquals(2, $orm->pagination->current());
	}

	public function testFlush()
	{
		$orm = (new PageOrm());
		$pages = $orm->filterBy('id > ?', 1)->list();
		$pages2 = $orm->list();
		$pages3 = (new PageOrm())->list();

		$this->assertCount(2, $pages);
		$this->assertCount(3, $pages2);
		$this->assertCount(3, $pages3);
	}

	public function testDelete()
	{
		$pagesCount = count((new PageOrm())->list());
		(new PageOrm())->filterBy('id', 2)->delete();

		$this->assertCount($pagesCount - 1, (new PageOrm())->list());
	}

	public function testDestroy()
	{
		$pagesCount = count((new PageOrm())->list());
		(new PageOrm())->destroy(2);
		$newCount = count((new PageOrm())->list());

		$this->assertNotEquals($pagesCount, $newCount);
	}

	public function testInsert()
	{
		$pagesTotal = count((new PageOrm())->list());

		$page = new Page();
		$page->title = 'Fourth Page';
		$page->description = 'Inserted by save method';
		$result = (new PageOrm())->save($page);

		$this->assertInstanceOf(Page::class, $result);
		$this->assertGreaterThan(0, $page->id);
		$this->assertCount($pagesTotal + 1, (new PageOrm())->list());
	}

	public function testUpdate()
	{
		$pagesTotal = count((new PageOrm())->list());
		$description = 'Updated by save method';

		$page = (new PageOrm())->sortBy('id', 'DESC')->one();
		$page->title = 'New Title';
		$page->description = $description;
		$pageAfterSave = (new PageOrm())->save($page);
		$pageUpdated = (new PageOrm())->sortBy('id', 'DESC')->one();

		$this->assertEquals($page->title, $pageUpdated->title);
		$this->assertEquals($page->title, $pageAfterSave->title);
		$this->assertEquals($description, $pageUpdated->description);
		$this->assertCount($pagesTotal, (new PageOrm())->list());
	}

	public function testTransactionCommit()
	{
		$orm = new PageOrm();
		$t = new Transaction($orm);
		$count = $orm->count();
		$orm->save(new Page());

		$this->assertEquals($count + 1, $orm->count());
		$t->commit();
		$this->assertEquals($count + 1, $orm->count());
	}

	public function testTransactionRollback()
	{
		$orm = new PageOrm();
		$t = new Transaction($orm);
		$count = $orm->count();
		$orm->save(new Page());

		$this->assertEquals($count + 1, $orm->count());
		$t->rollback();
		$this->assertEquals($count, $orm->count());
	}
}
