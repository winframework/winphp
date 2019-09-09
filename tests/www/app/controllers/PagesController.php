<?php

namespace controllers;

use Win\Database\Mysql\MysqlConnection;
use Win\Database\Orm\Page\Category;
use Win\Database\Orm\Page\Page;
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
	protected $orm;
	protected $pageSize = 2;

	protected function init()
	{
		$this->orm = Page::orm()
		->filterVisible()
		->paginate($this->pageSize, Input::get('p'));

		$db = [];
		require 'app/config/database.php';
		MysqlConnection::instance()->connect($db);
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

	/**
	 * Exibe todos os items
	 */
	public function index()
	{
		$orm = $this->orm;
		$this->setTitle('Pages');
		$this->addData('pages', $orm->list());
		$this->addData('categories', $this->getCategories());
		$this->addData('pagination', $orm->pagination);

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
		$this->addData('pageCount', $orm->count());

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
}
