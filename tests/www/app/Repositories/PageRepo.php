<?php

namespace App\Repositories;

use App\Models\Page;
use CorpTotal\Models\CmsPage;
use Win\Models\Model;
use Win\Repositories\Repository;

/**
 * Página Repositório
 *
 * @method Page[] list()
 * @method Page one()
 * @method Page oneOr404()
 * @method Page find($id)
 * @method Page findOr404($id)
 */
class PageRepo extends Repository
{
	protected $table = 'pages';
	protected $class = Page::class;

	/** @return Page */
	public static function mapModel($row)
	{
		$page = new Page();
		$page->id = $row['id'];
		$page->categoryId = $row['categoryId'];
		$page->title = $row['title'];
		$page->description = $row['description'];
		$page->createdAt = $row['createdAt'];
		$page->updatedAt = $row['updatedAt'];

		return $page;
	}

	/** @param Page $model */
	public static function mapRow($model)
	{
		return [
			'id' => $model->id,
			'categoryId' => $model->categoryId,
			'title' => $model->title,
			'description' => $model->description,
			'updatedAt' => $model->updatedAt,
		];
	}

	public function ifVisible()
	{
		return $this->if('createdAt < NOW()');
	}
}
