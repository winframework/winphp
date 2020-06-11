<?php

namespace App\Models;

use Win\Models\Model;

/**
 * Categoria
 */
class PageCategory extends Model
{
	public $title;
	public $description;

	/** Construtor */
	public function __construct()
	{
		$this->title = '';
		$this->description = '';
	}

	public function __toString()
	{
		return $this->title;
	}

	public function validate()
	{
	}
}
