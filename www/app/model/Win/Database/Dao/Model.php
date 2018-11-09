<?php

namespace Win\Database\Dao;

interface Model {

	/** @return int */
	public function getId();

	/** @return Dao */
	public static function dao();
}
