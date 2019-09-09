<?php

namespace Win\Database\Orm\Page;

use Win\Calendar\DateTime;
use Win\Database\Orm\Model;

/**
 * Página
 */
class Page extends Model
{
	public $title;
	public $description;

	/** @var DateTime */
	public $createdAt;

	public $categoryId;

	/** Construtor */
	public function __construct()
	{
		$this->title = '';
		$this->description = '';
		$this->createdAt = null;
	}

	public function category()
	{
		return Category::orm()->find($this->categoryId);
	}

	/** @return PageOrm */
	public static function orm()
	{
		return new PageOrm();
	}
}
