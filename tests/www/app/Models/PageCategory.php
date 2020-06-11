<?php

namespace App\Models;

use Win\Models\Model;

/**
 * Categoria
 */
class PageCategory extends Model
{
	public $enabled;
	public $title = '';
	public $description = '';
	public $createdAt;

	/** Construtor */
	public function __toString()
	{
		return $this->title;
	}

	public function validate()
	{
	}
}
