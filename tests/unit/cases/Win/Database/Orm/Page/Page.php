<?php

namespace Win\Database\Orm\Page;

use Win\Calendar\DateTime;
use Win\Database\Orm\Model;

/**
 * Página
 */
class Page implements Model
{
	public $id;
	public $title;
	public $description;

	/** @var DateTime */
	public $createdAt;

	/** Construtor */
	public function __construct()
	{
		$this->id = null;
		$this->title = '';
		$this->description = '';
		$this->createdAt = null;
	}

	/** @return int */
	public function getId()
	{
		return $this->id;
	}

	/** @return string */
	public function getTitle()
	{
		return $this->title;
	}

	/** @return string */
	public function getDescription()
	{
		return $this->description;
	}

	/** @return DateTime */
	public function getCreatedAt()
	{
		return $this->createdAt;
	}

	/** @param int $id */
	public function setId($id)
	{
		$this->id = $id;
	}

	/** @param string $title */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/** @param string $description */
	public function setDescription($description)
	{
		$this->description = $description;
	}

	/** @param DateTime $createdAt */
	public function setCreatedAt(DateTime $createdAt)
	{
		$this->createdAt = $createdAt;
	}

	/** @return PageOrm */
	public static function orm()
	{
		return new PageOrm();
	}
}
