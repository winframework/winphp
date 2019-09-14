<?php

namespace controllers;

use Win\Database\Mysql\MysqlConnection;
use Win\Database\Orm\Page\Category;
use Win\Database\Orm\Page\Page;
use Win\Database\Orm\Page\PageOrm;
use Win\Mvc\Controller;
use Win\Mvc\View;
use Win\Request\Input;

/**
 * ^pages => Pages/index
 * ^pages/(.*) => Pages/byCategory/$1
 * ^page/(.*) => 'Pages/detail/$1
 */
class PagesController extends Controller
{
	/** @var PageOrm */
	public $orm;
	protected $pageSize = 2;

	public function __construct()
	{
		$this->orm = Page::orm();
		$this->orm->filterVisible();
		$this->orm->paginate($this->pageSize, Input::get('p'));

		$db = [];
		require 'app/config/database.php';
		MysqlConnection::instance()->connect($db);
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
		$this->pagination = $orm->pagination;

		return new View('pages/index');
	}

	/**
	 * Exibe os itens da categoria atual
	 */
	public function byCategory()
	{
		$category = $this->getCategory();
		$orm = $this->orm
			->filter('CategoryId', $category->id);

		$this->setTitle('Pages - ' . $category->title);
		$this->addData('pages', $orm->list());
		$this->addData('categories', $this->getCategories());
		$this->addData('pagination', $orm->pagination);

		return new View('pages/index');
	}

	/**
	 * Exibe detalhes do item
	 */
	public function detail()
	{
		$page = $this->getPage();

		$this->setTitle('Page - ' . $page->title);
		$this->addData('page', $page);

		return new View('pages/detail');
	}

	protected function getCategory()
	{
		return Category::orm()
			->filter('Id', $this->app->getParam(2))
			->filterVisible()
			->one()
			->orFail();
	}

	protected function getCategories()
	{
		return Category::orm()
			->filterVisible()
			->paginate(2)
			->list();
	}

	protected function getPage()
	{
		return $this->orm
			->filter('Id', $this->app->getParam(2))
			->one()
			->orFail();
	}
}
