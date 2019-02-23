<?php

namespace Win\Database\Orm\Page;

use Win\Database\Orm;

/**
 * Página ORM
 *
 * @method Page one
 * @method Page[] all
 */
class PageOrm extends Orm
{
	protected $model = 'Páginas';
	protected $table = 'page';

	/** @return Page */
	public function mapModel($row)
	{
		$page = new Page();
		$page->setId($row['id']);
		$page->setTitle($row['title']);
		$page->setDescription($row['description']);

		return $page;
	}

	/** @param Page $model */
	public function mapRow($model)
	{
		$row = [];
		$row['id'] = $model->getId();
		$row['title'] = $model->getTitle();
		$row['description'] = $model->getDescription();

		return $row;
	}
}
