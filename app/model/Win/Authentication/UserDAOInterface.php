<?php

namespace Win\Authentication;

interface UserDAOInterface extends \Win\DAO\DAOInterface {

	/** @return User */
	public function fetchByField($fieldName, $fieldValue);

	/** @return User */
	public function fetchById($id);

	/** @return User */
	public function fetch($filter);

	/** @return User[] */
	public function fetchAll($filter);
}
