<?php

namespace Win\Database\Orm\Page;

use Win\Database\Orm\Model;

/**
 * Categoria
 */
class Category extends Model
{
	public $title;
	public $description;

	/** Construtor */
	public function __construct()
	{
		$this->title = '';
		$this->description = '';
	}

	/** @return CategoryOrm */
	public static function orm()
	{
		return new CategoryOrm();
	}
}
