<?php

namespace controller;

use Win\Database\Mysql;
use Win\Database\Orm\Page\Page;
use Win\Mvc\Controller;
use Win\Mvc\View;

class DbController extends Controller {

	protected function init() {
		$db = [];
		require 'app/config/database.php';
		Mysql::instance()->connect($db);
	}

	public function index() {
		$this->setTitle('Database Tests');
		return new View('index');
	}

	public function results() {
		$pageOrm = Page::repo();
		$pageOrm->debugOn();
		$pageOrm->results();
	}

}
