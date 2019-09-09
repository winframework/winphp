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
	public $categoryId;

	/** @var DateTime */
	public $createdAt;

	/** Construtor */
	public function __construct()
	{
		$this->title = '';
		$this->description = '';
		$this->createdAt = new DateTime();
	}

	public function category()
	{
		return Category::orm()->find($this->categoryId);
	}

	public static function orm()
	{
		return new PageOrm();
	}
}
