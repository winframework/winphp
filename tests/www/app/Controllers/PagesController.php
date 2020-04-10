<?php

namespace App\Controllers;

use App\Models\Page;
use App\Repositories\CategoryOrm;
use App\Repositories\PageOrm;
use Win\Controllers\Controller;
use Win\Repositories\Database\MysqlConnection;
use Win\Request\Input;
use Win\Views\View;

/**
 * pages => Pages@index
 * pages/(.*) => Pages@byCategory
 * page/(.*) => Pages@detail
 */
class PagesController extends Controller
{
	/** @var PageOrm */
	public $orm;

	/** @var CategoryOrm */
	public $categoryOrm;

	/** @var int */
	protected $pageSize = 2;

	public function __construct()
	{
		$this->orm = new PageOrm();
		$this->categoryOrm = new CategoryOrm();

		$db = [];
		require 'app/config/database.php';
		MysqlConnection::instance()->connect($db);

		$this->orm
			->sortBy('id', 'desc')
			// ->sortRand()
			->paginate($this->pageSize, Input::get('p'))
			->filterVisible()
			->filterBy('id <> ?', 19)
			->filterBy(
				'(title LIKE ? OR title LIKE ?) OR (description IS NOT NULL AND id >= ? )',
				'Nothing%',
				'%age',
				3
			);
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
	public function byCategory($categoryId)
	{
		$category = $this->categoryOrm
			->filterBy('Id', $categoryId)
			->filterVisible()
			->one()
			->or404();

		$orm = $this->orm;
		$orm->filterBy('CategoryId', $category->id);

		$this->title = 'Pages - ' . $category->title;
		$this->pages = $orm->list();
		$this->categories = $this->getCategories();

		return new View('pages/index');
	}

	/**
	 * Exibe detalhes do item
	 */
	public function detail($id)
	{
		$page = $this->getPage($id);

		$this->title = 'Page - ' . $page->title;
		$this->page = $page;

		return new View('pages/detail');
	}

	protected function getCategories()
	{
		return $this->categoryOrm
			->filterVisible()
			->paginate(2, 1)
			->list();
	}

	protected function getPage($id)
	{
		return $this->orm
			->filterBy('Id', $id)
			->one()
			->or404();
	}
}
