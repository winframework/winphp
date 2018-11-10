<?php

namespace controller;

use Win\Database\Connection\Mysql;
use Win\Database\Dao\Page\Page;
use Win\Mvc\Controller;
use Win\Mvc\View;

class DaoController extends Controller {

	public function index() {
		$this->setTitle('Database Tests');
		return new View('index');
	}

	public function results() {
		$db = [];
		require 'app/config/database.php';
		Mysql::instance()->connect($db);

		Page::dao()->debug();
		$pagesTotal = count(Page::dao()->results());

		$page = new Page();
		$page->setTitle('Fourth Page');
		$page->setDescription('Inserted by save method');
		$success = Page::dao()->save($page);

		$this->assertTrue($success);
		$this->assertCount($pagesTotal + 1, Page::dao()->results());
	}

}
