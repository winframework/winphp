<?php

namespace App\Controllers;

use App\Repositories\PageCategoryOrm;
use App\Repositories\PageOrm;
use Exception;
use Win\Controllers\Controller;
use Win\Repositories\Database\DatabaseException;
use Win\Repositories\Database\MysqlConnection;
use Win\Repositories\Filesystem;
use Win\Request\Input;
use Win\Views\View;

/**
 * pages => Pages@index
 * pages/(.*) => Pages@listByCategory
 * page/(.*) => Pages@detailPage
 */
class PagesController extends Controller
{
	/** @var PageOrm */
	public $orm;

	/** @var PageCategoryOrm */
	public $categoryOrm;

	/** @var int */
	protected $pageSize = 2;

	public function __construct()
	{
		$this->orm = new PageOrm();
		$this->categoryOrm = new PageCategoryOrm();

		$this->prepareDatabase();
		$this->orm
			->sortBy('id', 'asc')
			->paginate($this->pageSize, Input::get('p'));
	}

	/**
	 * Exibe todos os items
	 */
	public function index()
	{
		$orm = $this->orm;

		$this->title = 'Pages';
		$this->pages = $orm->list();
		$this->categories = $this->getCategories();

		return new View('pages/index');
	}

	/**
	 * Exibe os itens da categoria atual
	 */
	public function listByCategory($categoryId)
	{
		$category = $this->categoryOrm->find($categoryId);
		$this->orm->filterBy('categoryId', $categoryId);

		$this->title = 'Pages - ' . $category->title;
		$this->pages = $this->orm->list();
		$this->categories = $this->getCategories();

		return new View('pages/index');
	}

	/**
	 * Exibe detalhes do item
	 */
	public function show($id)
	{
		$page = $this->getPage($id);

		$this->title = 'Page - ' . $page->title;
		$this->page = $page;

		return new View('pages/show');
	}

	protected function getCategories()
	{
		return $this->categoryOrm
			->paginate(2, 1)
			->list();
	}

	protected function getPage($id)
	{
		return $this->orm
			->filterBy('id', $id)
			->one()
			->or404();
	}

	private function prepareDatabase()
	{
		$fs = new Filesystem();
		$db = [];
		require 'app/config/database.php';
		MysqlConnection::instance()->connect($db);
		$query = $fs->read('../database/winphp_demo.sql');
		MysqlConnection::instance()->execute($query);
	}
}
