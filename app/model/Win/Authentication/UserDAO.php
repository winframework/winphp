<?php

namespace Win\Authentication;

use Win\DAO\DAO;
use Win\Authentication\User;

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
		$obj->setId($row['id']);
		$obj->setActive($row['is_active']);
		$obj->setAccessLevel($row['access_level']);
		$obj->setGroupId($row['group_id']);
		$obj->setName($row['name']);
		$obj->setEmail($row['email']);
		$obj->setPasswordHash($row['password_hash']);
		$obj->setRecoreryHash($row['recovery_hash']);
		$obj->setImage($row['image']);
		$obj->setLastLogin($row['last_login']);
		return $obj;
	}

	/**
	 * @param User $obj
	 * @return mixed[]
	 */
	protected function mapRow($obj) {
		$row['id'] = $obj->getId();
		$row['is_active'] = $obj->isActive();
		$row['access_level'] = $obj->getAccessLevel();
		$row['group_id'] = $obj->getGroupId();
		$row['name'] = $obj->getName();
		$row['email'] = $obj->getEmail();
		$row['password_hash'] = $obj->getPasswordHash();
		$row['recovery_hash'] = $obj->getRecoreryHash();
		$row['image'] = $obj->getImage();
		$row['last_login'] = $obj->getLastLogin();
		return $row;
	}

	/**
	 * Insere o primeiro usuario
	 * @param string $email
	 * @param string $password
	 */
	public function insertFirst($email, $password) {
		$totalUser = $this->numRows();

		if ($totalUser == 0) {
			$user = new User();
			$user->setName('Administrador');
			$user->setEmail($email);
			$user->setPassword($password);
			$user->setActive(true);
			$user->setAccessLevel(User::ACCESS_ADMIN);
			$this->save($user);
		}
	}

}
