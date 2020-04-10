<?php

namespace App\Models;

use Win\Models\Model;

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

	public function validate()
	{ }
}
