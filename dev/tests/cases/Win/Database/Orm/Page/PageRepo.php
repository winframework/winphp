<?php

namespace Win\Database\Orm\Page;

use Win\Database\Orm\Repository;

/**
 * Página ORM
 *
 * @method Page result
 * @method Page[] results
 */
class PageRepo extends Repository {

	protected $model = 'Páginas';
	protected $table = 'page';

	/** @return Page */
	public function mapModel($row) {
		$page = new Page();
		$page->setId($row['id']);
		$page->setTitle($row['title']);
		$page->setDescription($row['description']);
		return $page;
	}

	/** @param Page $model */
	public function mapRow($model) {
		$row = [];
		$row['id'] = $model->getId();
		$row['title'] = $model->getTitle();
		$row['description'] = $model->getDescription();
		return $row;
	}

}
