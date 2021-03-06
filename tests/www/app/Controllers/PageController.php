<?php

namespace App\Controllers;

use App\Models\Page;
use App\Repositories\PageCategoryRepo;
use App\Repositories\PageRepo;
use PDO;
use Win\Application;
use Win\Utils\Input;
use Win\Controllers\Controller;
use Win\Repositories\Mysql;
use Win\Services\Filesystem;
use Win\Services\Alert;
use Win\Templates\View;

/**
 * pages => Pages@index
 * pages/(.*) => Page@listByCategory
 * page/(.*) => Page@detail
 */
class PageController extends Controller
{
	public PageRepo $repo;
	public PageCategoryRepo $categoryRepo;
	protected $pageSize = 2;

	public function __construct(PageRepo $repo, PageCategoryRepo $categoryRepo)
	{
		$this->createDatabase();
		$this->repo = $repo;
		$this->categoryRepo = $categoryRepo;
	}

	public function init()
	{
		$this->repo->sort('id DESC')->paginate($this->pageSize, Input::get('p'));
	}

	/**
	 * Exibe todos os items
	 */
	public function index()
	{
		$this->title = 'Pages';
		$this->categories = $this->getCategories();
		$this->pages = $this->repo->list();

		return new View('page/index');
	}

	/**
	 * Exibe os itens da categoria atual
	 */
	public function listByCategory($categoryId)
	{
		$category = $this->categoryRepo->findOr404($categoryId);

		$this->title = 'Pages - ' . $category;
		$this->categories = $this->getCategories();
		$this->pages = $this->repo
			->if('categoryId', $categoryId)
			->list();

		return new View('page/index');
	}

	/**
	 * Exibe detalhes do item
	 */
	public function detail($id)
	{
		$page = $this->repo->findOr404($id);

		$this->title = 'Page - ' . $page;
		$this->page = $page;

		return new View('page/detail');
	}

	/**
	 * Insere um teste
	 */
	public function save()
	{
		try {
			$pdo = $this->repo->pdo;
			$pdo->beginTransaction();

			$page = new Page();
			$page->title = 'Inserted';
			$this->repo->save($page);

			$this->categoryRepo
				->if('id', $page->categoryId)
				->update(['title' => 'Category updated']);

			$page = $this->repo->if('title', $page->title)->one();
			$page->title = 'Updated';
			$this->repo->save($page);

			Alert::success('Inseriu e atualizou: ' . $page->id);
			$pdo->commit();
		} catch (\Exception $e) {
			Alert::error($e);
			$pdo->rollBack();
		}

		return new View('page/form');
	}

	/**
	 * Atualiza um teste
	 */
	public function update()
	{
		$total = $this->repo
			->if('id > ?', 3)
			->update([
				'title' => 'Updated 01 - Title',
				'updatedAt' => date('Y-m-d H:i:s')
			]);
		Alert::success("Atualizou somente 2 atributos de $total entidade(s).");

		return new View('page/form');
	}

	protected function getCategories()
	{
		return $this->categoryRepo->if('enabled')->list();
	}

	private function createDatabase()
	{
		$fs = new Filesystem();
		$pdo = require 'config/database.php';
		Application::app()->pdo = $pdo;
		$query = $fs->read('../database/winphp_demo.sql');
		$pdo->exec($query);
	}
}
