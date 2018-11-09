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
		$pages = Page::dao()->results();
		var_dump($pages);
	}

}
