<?php

namespace Win\Database\Orm\Traits;

use Win\Database\Connection;

trait ConnectableTrait
{
	/** @var Connection */
	public static $defaultConnection;

	/** @var Connection */
	protected $conn;

	/** @param Connection $conn */
	public function setConnection(Connection $conn)
	{
		$this->conn = $conn;
	}

	/** @return Connection */
	public function getConnection()
	{
		return $this->conn;
	}
}
