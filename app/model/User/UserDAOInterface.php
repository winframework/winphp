<?php

namespace User;

interface UserDAOInterface extends \Win\DesignPattern\DAOInterface {

	/** @return User */
	public function fetchByField($fieldName, $fieldValue);

	/** @return User */
	public function fetchById($id);

	/** @return User */
	public function fetch($filter);

	/** @return User[] */
	public function fetchAll($filter);
}
