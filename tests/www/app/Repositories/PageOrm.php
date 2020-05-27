<?php

namespace App\Repositories;

use App\Models\Page;
use Win\Models\DateTime;
use Win\Repositories\Database\Orm;

/**
 * Página ORM
 *
 * @method Page one()
 * @method Page[] list()
 * @method Page find($id)
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
		$page->createdAt = new DateTime($row['createdAt']);

		return $page;
	}

	/** @param Page $model */
	public static function mapRow($model)
	{
		return [
			'id' => $model->id,
			'title' => $model->title,
			'description' => $model->description,
			'createdAt' => $model->createdAt->toSql(),
		];
	}

	public function filterVisible()
	{
		return $this->filterBy('createdAt < NOW()');
	}
}
