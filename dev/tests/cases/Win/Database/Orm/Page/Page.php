<?php

namespace Win\Database\Orm\Page;

use Win\Calendar\DateTime;
use Win\Database\Orm\Model;

/**
 * Página
 *
 */
class Page implements Model {

	public $id;
	public $title;
	public $description;

	/** @var DateTime */
	public $createdAt;

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

	/** @return PageRepo */
	public static function repo() {
		return PageRepo::instance();
	}

}