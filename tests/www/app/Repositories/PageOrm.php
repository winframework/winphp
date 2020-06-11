<?php

namespace App\Repositories;

use App\Models\Page;
use Win\Repositories\Database\Orm;

/**
 * Página ORM
 *
 * 
 * @method Page[] list()
 * @method Page one()
 * @method Page oneOr404()
 * @method Page find($id)
 * @method Page findOr404($id)
 */
class PageOrm extends Orm
{
	const TABLE = 'pages';
	const TITLE = 'Página';

	/** @return Page */
	public static function mapModel($row)
	{
		$page = new Page();
		$page->id = $row['id'];
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
			'title' => $model->title,
			'description' => $model->description,
			'updatedAt' => $model->updatedAt,
		];
	}

	public function filterVisible()
	{
		return $this->filterBy('createdAt < NOW()');
	}
}
