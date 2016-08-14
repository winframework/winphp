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
}
