<?php

namespace Win\Database\Dao\Page;

use Win\Database\Dao\Dao;

/**
 *
 * @method Page[] all
 * @method Page find(int $id)
 */
class PageDao extends Dao {

	protected $name = 'Páginas';
	protected $table = 'page';

	/** @return Page */
	public function mapObject($row) {
		$page = new Page();
		$page->setId($row['id']);
		$page->setTitle($row['title']);
		$page->setDescription($row['description']);
		return $page;
	}

}
