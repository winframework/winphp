<?php

namespace Win\Database\Orm\Page;

use Win\Database\Orm;

/**
 * Página ORM
 *
 * @method Page one
 * @method Page[] list
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
		$page->setId($row['Id']);
		$page->setTitle($row['Title']);
		$page->setDescription($row['Description']);

		return $page;
	}

	/** @param Page $model */
	public static function mapRow($model)
	{
		return [
			'Id' => $model->getId(),
			'Title' => $model->getTitle(),
			'Description' => $model->getDescription(),
		];
	}
}
