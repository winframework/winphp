<?php

namespace App\Models;

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
}
