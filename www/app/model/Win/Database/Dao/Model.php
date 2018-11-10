<?php

namespace Win\Database\Dao;

interface Model {

	/** @return int */
	public function getId();

	/** @param int $id */
	public function setId($id);

	/** @return Dao */
	public static function dao();
}
