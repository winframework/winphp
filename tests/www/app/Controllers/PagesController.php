<?php

namespace App\Controllers;

use App\Models\Page;
use App\Repositories\PageCategoryRepo;
use App\Repositories\PageRepo;
use Win\Application;
use Win\Controllers\Controller;
use Win\Services\Alert;
use Win\Repositories\Database\Mysql;
use Win\Repositories\Database\Transaction;
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
	public PageRepo $repo;
	public PageCategoryRepo $categoryRepo;
	protected $pageSize = 2;

	public function __construct(PageRepo $repo, PageCategoryRepo $categoryRepo)
	{
		$conn = $this->connectDatabase();
		$this->orm = $repo;
		$this->categoryRepo = $categoryRepo;
		$this->orm->conn = $conn;
		$this->categoryRepo->conn = $conn;
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
		try {
			$conn = $this->orm->conn;
			$conn->beginTransaction();
			$page = new Page();
			$page->title = 'Inserted';
			$this->orm->save($page);
			$this->categoryRepo->save($page->category());


			Alert::success('Inseriu e atualizou: ' . $page->id);
			$conn->commit();
			$page = $this->orm->filter('title', $page->title)->one();
			$page->title = 'Updated';
			$this->orm->save($page);
		} catch (\Exception $e) {
			$conn->rollback();
			Alert::error($e);
		}
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
		$category = $this->categoryRepo->findOr404($categoryId);

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
		return $this->categoryRepo->filter('enabled')->list();
	}

	private function connectDatabase()
	{
		$fs = new Filesystem();
		$db = [];
		require 'config/database.php';
		$conn = new Mysql($db);
		$query = $fs->read('../database/winphp_demo.sql');
		$conn->execute($query);
		return $conn;
	}
}
