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
	 * @return string|null
	 */
	protected function validate() {
		if (strlen($this->obj->getName()) < 2) {
			return 'O campo Nome deve possuir pelo menos 2 caracteres.';
		} elseif (strlen($this->obj->getEmail()) == 0) {
			return 'O campo E-mail deve ser preenchido.';
		} elseif (!filter_var($this->obj->getEmail(), FILTER_VALIDATE_EMAIL)) {
			return 'O campo E-mail deve ser um e-mail válido.';
		} elseif (strlen($this->obj->getPassword()) < 4) {
			return 'A senha deve possuir pelo menos 4 caracteres.';
		}
		return null;
	}

	/**
	 * @param array $row
	 * @return User
	 */
	protected function mapObject($row) {
		$obj = new User();
		$obj->setId($row['id']);
		$obj->setEnabled($row['is_enabled']);
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
		$row['is_enabled'] = $obj->isEnabled();
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
	 * @param User $user
	 * @return string|null
	 */
	public function insertFirst(User $user) {
		if ($this->totalUsers() === 0) {
			$user->setAccessLevel(User::ACCESS_ADMIN);
			$user->setName('Administrador');
			return $this->save($user);
		}
	}

	/**
	 * Retorna total usuarios
	 * @return int
	 */
	public function totalUsers() {
		return (int) $this->numRows();
	}

	/**
	 * Atualiza data ultimo login
	 * @param User $user
	 * @return string|null
	 */
	public function updateLastLogin(User $user) {
		$now = date('Y-m-d H:i:s');
		$user->setLastLogin($now);
		return $this->save($user);
	}

	/**
	 * Gera/Atualiza um novo recoveryHash
	 * @param User $user
	 * @return string|null
	 */
	public function updateRecoveryHash(User $user) {
		$hash = md5($user->getEmail() . date('Y-m-d'));
		$user->setRecoreryHash($hash);
		return $this->save($user);
	}

	/**
	 * Atualiza a senha
	 * @param User $user
	 * @param string $newPassword1
	 * @param string $newPassword2
	 * @return string erro
	 */
	public function updatePassword(User $user, $newPassword1, $newPassword2) {
		$error = null;
		if ($newPassword1 != $newPassword2) {
			$error = 'A senha deve ser informada duas vezes iguais.';
		}
		if (!$error) {
			$user->setPassword($newPassword1);
			$error = $this->save($user);
		}
		return $error;
	}

	public function fetchByRecoveryHash($recoveryHash) {
		return $this->fetch(['recovery_hash = ?' => $recoveryHash]);
	}

}
