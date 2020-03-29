<?php

namespace App\Controllers;

use App\Models\Page;
use App\Repositories\PageOrm;
use Win\Controllers\Controller;
use Win\Repositories\Database\MysqlConnection;
use Win\Views\View;

class DatabaseController extends Controller
{
	/** @var PageOrm */
	public $pageOrm;

	public function __construct()
	{
		$db = [];
		require 'app/config/database.php';
		MysqlConnection::instance()->connect($db);

		$this->pageOrm = new PageOrm();
	}

	public function index()
	{
		$this->title = 'Database Tests';
		$pageOrm = $this->pageOrm;

		$page = new Page();
		$page->id = 8;
		$page->title = 'chaves';

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

		return new View('index');
	}
}
