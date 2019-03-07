<?php

namespace Win\Database;

use Win\Database\Orm\Model;

interface RepositoryInterface
{
	/** @return Connection */
	public function getConnection();

	/** @return string */
	public function getTable();

	/** @return mixed[] */
	public function getRowValues();

	/** @return Model */
	public function getModel();

	/** @return bool */
	public function getDebugMode();
}
