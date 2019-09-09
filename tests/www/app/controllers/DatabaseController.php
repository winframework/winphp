<?php

namespace controllers;

use Win\Database\Mysql\MysqlConnection;
use Win\Database\Orm\Page\Page;
use Win\Mvc\Controller;
use Win\Mvc\View;

class DatabaseController extends Controller
{
	protected function init()
	{
		$db = [];
		require 'app/config/database.php';
		MysqlConnection::instance()->connect($db);
	}

	public function index()
	{
		$this->setTitle('Database Tests');

		$page = new Page();
		$page->id = 8;
		$page->title = 'chaves';

		$pageOrm = Page::orm();
		$pageOrm->debug = true;
		// $pageOrm->rawQuery('SELECT * FROM ' . $pageOrm::TABLE);

		// $pageOrm->rawQuery('SELECT * FROM ' . $pageOrm::TABLE . ' WHERE Id BETWEEN 1 AND 8');
		$x = $pageOrm->list();

		// $pageOrm->filterNotPublished();
		// $pageOrm->filterBy('UserId', '=', 10);
		// $pageOrm->filterBy('UserId', '=', 10);
		// $pageOrm->filterBy('Id', '<', 10);
		// $x = $pageOrm->one();
		var_dump($x);

		return new View('demo');
	}
}
