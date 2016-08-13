<?php

namespace User;

use Win\DesignPattern\DAO;

/**
 * User DAO
 */
class UserDAO extends DAO {

	const TABLE = 'user';
	const ALIAS = 'Usuário';

	/** @var User */
	protected $obj;

	/**
	 * @param array $row
	 * @return \User\User
	 */
	public function mapObject(array $row) {
		$obj = new \User\User();
		if ($row) {
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
	public function mapRow($obj) {
		$row['id'] = $obj->getId();
		$row['email'] = $obj->getEmail();
		$row['pass'] = $obj->getPass();
		return $row;
	}

}
