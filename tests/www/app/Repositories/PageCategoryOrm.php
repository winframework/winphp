<?php

namespace App\Repositories;

use App\Models\Page;
use App\Models\PageCategory;
use Win\Repositories\Database\Orm;

/**
 * Categoria ORM
 *
 * @method PageCategory one()
 * @method PageCategory[] list()
 * @method PageCategory find($id)
 */
class PageCategoryOrm extends Orm
{
	const TABLE = 'pageCategories';
	const TITLE = 'Categoria de Páginas';

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
