<?php

namespace Win\Database\Dao\Page;

use Win\Database\Dao\Dao;

/**
 * Página DAO
 *
 * @method Page[] all
 * @method Page[] latest
 * @method Page find(int $id)
 * @method Page first
 * @method Page last
 */
class PageDao extends Dao {

	protected $name = 'Páginas';
	protected $table = 'page';

	public function mapObject($row) {
		$page = new Page();
		$page->setId($row['id']);
		$page->setTitle($row['title']);
		$page->setDescription($row['description']);
		return $page;
	}

}
