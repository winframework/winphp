<?php

namespace App\Repositories;

use App\Models\PageCategory;
use Win\Repositories\Repository;

/**
 * Categoria Repositório
 *
 * @method PageCategory[] list()
 * @method PageCategory find($id)
 * @method PageCategory findOr404($id)
 * @method PageCategory one()
 * @method PageCategory oneOr404()
 */
class PageCategoryRepo extends Repository
{
	protected $table = 'pageCategories';
	protected $class = PageCategory::class;

	/** @return PageCategory */
	public static function mapModel($row)
	{
		$page = new PageCategory();
		$page->id = $row['id'];
		$page->title = $row['title'];
		$page->description = $row['description'];

		return $page;
	}

	/** @param PageCategory $model */
	public static function mapRow($model)
	{
		return [
			'id' => $model->id,
			'title' => $model->title,
			'description' => $model->description,
		];
	}
}
