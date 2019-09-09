<?php

namespace Win\Database\Orm\Page;

use Win\Calendar\DateTime;
use Win\Database\Orm;

/**
 * Categoria ORM
 *
 * @method Category one()
 * @method Category[] list()
 * @method Category find($id)
 */
class CategoryOrm extends Orm
{
	const TABLE = 'PageCategories';
	const TITLE = 'Categoria de Páginas';

	/** @return Category */
	public static function mapModel($row)
	{
		$page = new Page();
		$page->id = $row['Id'];
		$page->title = $row['Title'];
		$page->description = $row['Description'];

		return $page;
	}

	/** @param Category $model */
	public static function mapRow($model)
	{
		return [
			'Id' => $model->id,
			'Title' => $model->title,
			'Description' => $model->description,
		];
	}

	public function filterVisible()
	{
		return $this
			->filter('Enabled', true);
		// ->filterBy('PublishedAt', '<=', (new DateTime())->toSql());
	}
}