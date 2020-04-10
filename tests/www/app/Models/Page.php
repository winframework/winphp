<?php

namespace App\Models;

use Win\Models\DateTime;
use Win\Models\Model;

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

	public function validate()
	{ }
}
