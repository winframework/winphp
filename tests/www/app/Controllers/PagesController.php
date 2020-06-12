<?php

namespace App\Controllers;

use App\Models\Page;
use App\Repositories\PageCategoryOrm;
use App\Repositories\PageOrm;
use Win\Application;
use Win\Controllers\Controller;
use Win\Repositories\Alert;
use Win\Repositories\Database\Mysql;
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
	protected $pageSize = 2;

	public function __construct()
	{
		$this->prepareDatabase();
		$this->orm = new PageOrm();
		$this->categoryOrm = new PageCategoryOrm();
	}

	public function init()
	{
		$this->orm->sort('id DESC')->paginate($this->pageSize, Input::get('p'));
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
	 * Insere um teste
	 */
	public function save()
	{
		$page = new Page();
		$page->title = 'Inserted';
		$this->orm->save($page);
		Alert::success('Inseriu e atualizou: ' . $page->id);

		$page = $this->orm->filter('title', $page->title)->one();
		$page->title = 'Updated';
		$this->orm->save($page);
		$this->redirect('alerts/show');
	}

	/**
	 * Atualiza um teste
	 */
	public function update()
	{
		$total = $this->orm
			->filter('id > ?', 3)
			->update([
				'title' => 'Updated 01 - Title',
				'updatedAt' => date('Y-m-d H:i:s')
			]);
		Alert::success("Atualizou somente 2 atributos de $total entidade(s).");

		$this->redirect('alerts/show');
	}

	/**
	 * Exibe os itens da categoria atual
	 */
	public function listByCategory($categoryId)
	{
		$category = $this->categoryOrm->findOr404($categoryId);

		$this->title = 'Pages - ' . $category;
		$this->categories = $this->getCategories();
		$this->pages = $this->orm
			->filter('categoryId', $categoryId)
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
		return $this->categoryOrm->filter('enabled')->list();
	}

	private function prepareDatabase()
	{
		$fs = new Filesystem();
		$db = [];
		require 'app/config/database.php';
		$conn = new Mysql($db);
		Application::app()->conn = $conn;
		$query = $fs->read('../database/winphp_demo.sql');
		$conn->execute($query);
	}
}
