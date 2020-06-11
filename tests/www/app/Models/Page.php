<?php

namespace App\Models;

use App\Repositories\PageCategoryOrm;
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
	public $createdAt;
	public $updatedAt;

	/** Construtor */
	public function __construct()
	{
		$this->title = '';
		$this->description = '';
		$this->createdAt = null;
		$this->updatedAt = null;
	}

	public function __toString()
	{
		return $this->title;
	}

	public function category()
	{
		return (new PageCategoryOrm())->find($this->categoryId);
	}

	public function validate()
	{
	}
}
