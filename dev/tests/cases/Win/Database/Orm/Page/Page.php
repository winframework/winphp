<?php

namespace Win\Database\ActiveRecord\Page;

use Win\Calendar\DateTime;

/**
 * Página
 *
 */
class Page extends \Win\Database\ActiveRecord\Model {

	private $id;
	private $title;
	private $description;

	/** @var DateTime */
	private $createdAt;

	/** Construtor */
	public function __construct() {
		$this->id = null;
		$this->title = '';
		$this->description = '';
		$this->createdAt = null;
	}

	public function getId() {
		return $this->id;
	}

	public function getTitle() {
		return $this->title;
	}

	public function getDescription() {
		return $this->description;
	}

	public function getCreatedAt() {
		return $this->createdAt;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function setDescription($description) {
		$this->description = $description;
	}

	public function setCreatedAt(DateTime $createdAt) {
		$this->createdAt = $createdAt;
	}

	protected static $model = 'Páginas';
	protected static $table = 'page';

	public function mapObject($row) {
		$page = new Page();
		$page->setId($row['id']);
		$page->setTitle($row['title']);
		$page->setDescription($row['description']);
		return $page;
	}

}
