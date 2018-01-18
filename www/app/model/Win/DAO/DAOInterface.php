<?php

namespace Win\DAO;

/**
 * Interface básica para DAOs
 */
interface DAOInterface {

	public function fetchByField($fieldName, $fieldValue);

	public function fetchById($id);

	public function fetch($filter);

	public function fetchAll($filter);

	/**
	 * Retorna um objeto a partir da linha da tabela
	 * @param mixed[] $row
	 */
	public static function mapObject($row);

	/**
	 * Retorna a linha da tabela a partir de um objeto
	 * @param object $obj
	 */
	public static function mapRow($obj);
}
