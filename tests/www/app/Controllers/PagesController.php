<?php

namespace App\Controllers;

use App\Repositories\PageCategoryOrm;
use App\Repositories\PageOrm;
use ArrayObject;
use Exception;
use Win\Common\Pagination;
use Win\Controllers\Controller;
use Win\Repositories\Database\DatabaseException;
use Win\Repositories\Database\MysqlConnection;
use Win\Repositories\Filesystem;
use Win\Request\Input;
use Win\Views\View;

/**
 * pages => Pages@index
 * pages/(.*) => Pages@listByCategory
 * page/(.*) => Pages@detail
 */
class PagesController extends Controller
{
	/** @var PageOrm */
	public $orm;

	/** @var PageCategoryOrm */
	public $categoryOrm;

	/** @var int */
	protected $pageSize = 1;

	public function __construct()
	{
		$this->orm = new PageOrm();
		$this->categoryOrm = new PageCategoryOrm();

		$this->prepareDatabase();
		$this->orm->sortOldest()->paginate($this->pageSize, Input::get('p'));
	}

	/**
	 * Exibe todos os items
	 */
	public function index()
	{
		$this->title = 'Pages';
		$this->categories = $this->getCategories();
		$this->pages = $this->orm->list();

		return new View('pages/index');
	}

	/**
	 * Exibe os itens da categoria atual
	 */
	public function listByCategory($categoryId)
	{
		$category = $this->categoryOrm->find($categoryId);

		$this->title = 'Pages - ' . $category;
		$this->categories = $this->getCategories();
		$this->pages = $this->orm
			->filterBy('categoryId', $categoryId)
			->list();

		return new View('pages/index');
	}

	/**
	 * Exibe detalhes do item
	 */
	public function detail($id)
	{
		$page = $this->orm->findOr404($id);

		$this->title = 'Page - ' . $page;
		$this->page = $page;

		return new View('pages/detail');
	}

	protected function getCategories()
	{
		return $this->categoryOrm->list();
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
