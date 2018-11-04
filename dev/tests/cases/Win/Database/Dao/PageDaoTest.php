<?php

namespace Win\Database\Dao;

use PHPUnit_Framework_TestCase;
use Win\Database\Dao\Page\PageDao;

class PageDaoTest extends PHPUnit_Framework_TestCase {

	public static function setUpBeforeClass() {
		DaoTest::connect();
	}

	public function testAll() {
		$pages = PageDao::instance()->all();
		$this->assertTrue(count($pages) > 1);
		$this->assertEquals($pages[0]->getTitle(), 'about us');
	}

		public function testAll_OrderBy() {
		$pages = PageDao::instance()->orderBy('title DESC')->all();
		$this->assertTrue(count($pages) > 1);
		$this->assertEquals($pages[0]->getTitle(), 'Teste');
	}

	public function testFind() {
		$page = PageDao::instance()->find(2);
		$this->assertEquals($page->getId(), 2);
		$this->assertEquals($page->getTitle(), 'contact us');
	}

	public function testGet_NoFilter() {
		$pages = PageDao::instance()->get();
		$this->assertTrue(count($pages) > 1);
		$this->assertEquals($pages[0]->getTitle(), 'about us');
	}

	public function testGet_Filter() {
		//$page = PageDao::instance()->filter(['id = ?', 2])->get();
		//$this->assertEquals($page->getId(), 2);
		//$this->assertEquals($page->getTitle(), 'contact us');
	}

}
