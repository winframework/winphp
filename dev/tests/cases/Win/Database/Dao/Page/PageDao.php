<?php

namespace Win\Database\Dao\Page;

use Win\Database\Dao\Dao;

/**
 * Página DAO
 *
 * @method Page result
 * @method Page[] results
 */
class PageDao extends Dao {

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
