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
		$page->setId(7);
		$page->setTitle('seven!');

		var_dump(Page::orm()->count());
		return new View('demo');
	}
}
