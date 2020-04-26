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
	const TABLE = 'Pages';
	const TITLE = 'Página';

	/** @return Page */
	public static function mapModel($row)
	{
		$page = new Page();
		$page->id = $row['Id'];
		$page->title = $row['Title'];
		$page->description = $row['Description'];
		$page->createdAt = new DateTime($row['CreatedAt']);

		return $page;
	}

	/** @param Page $model */
	public static function mapRow($model)
	{
		return [
			'Id' => $model->id,
			'Title' => $model->title,
			'Description' => $model->description,
			'CreatedAt' => $model->createdAt->toSql(),
		];
	}

	public function filterVisible()
	{
		return $this->filterBy('CreatedAt < NOW()');
	}
}
