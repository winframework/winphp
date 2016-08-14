<?php

namespace User;

use Win\DesignPattern\DAO;

/**
 * User DAO
 */
class UserDAO extends DAO implements UserDAOInterface {

	const TABLE = 'user';
	const ALIAS = 'Usuário';

	/** @var User */
	protected $obj;

	/**
	 * @param array $row
	 * @return User
	 */
	protected function mapObject($row) {
		$obj = new User();
		if (!empty($row)) {
			$obj->setId($row['id']);
			$obj->setEmail($row['email']);
			$obj->setPass($row['pass']);
		}
		return $obj;
	}

	/**
	 * @param User $obj
	 * @return mixed[]
	 */
	protected function mapRow($obj) {
		$row['id'] = $obj->getId();
		$row['email'] = $obj->getEmail();
		$row['pass'] = $obj->getPass();
		return $row;
	}

	public function fetchLast() {
		return $this->fetch([], 'ORDER BY id DESC');
	}

}
