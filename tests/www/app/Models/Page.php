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
	public $categoryId;
	public $title = '';
	public $description = '';
	public $createdAt;
	public $updatedAt;

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
