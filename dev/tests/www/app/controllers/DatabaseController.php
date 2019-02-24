<?php

namespace controllers;

use Win\Database\Connections\MysqlConnection;
use Win\Database\Orm\Page\Page;
use Win\Mvc\Controller;
use Win\Mvc\View;

class DatabaseController extends Controller {

	protected function init() {
		$db = [];
		require 'app/config/database.php';
		MysqlConnection::instance()->connect($db);
	}

	public function index() {
		$this->setTitle('Database Tests');
		$pageOrm = Page::orm();
		$pageOrm->debugOn();
		var_dump($pageOrm->all());
		return new View('demo');
	}

}
