<?php

namespace Win\Database\Orm;

interface Model {

	/** @return int */
	public function getId();

	/** @param int $id */
	public function setId($id);

	/** @return Repository */
	public static function repo();
}
