<?php

namespace Win\Authentication;

use Win\DAO\DAO;
use Win\Authentication\User;
use Win\Calendar\Date;

/**
 * User DAO
 */
class UserDAO extends DAO implements UserDAOInterface {

	const TABLE = 'person';
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
		} elseif (strlen($this->obj->getEmail()) > 0 and $this->obj->emailIsDuplicated()) {
			return 'Já existe um usuário com este e-mail.';
		} elseif ($this->obj->getPassword() != null and strlen($this->obj->getPassword()) < 4) {
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
		$obj->setId($row['person_id']);
		$obj->setEnabled($row['is_enabled']);
		$obj->setAccessLevel($row['access_level']);
		$obj->setGroupId($row['group_id']);
		$obj->setName($row['name']);
		$obj->setEmail($row['email']);
		$obj->setPasswordHash($row['password_hash']);
		$obj->setRecoreryHash($row['recovery_hash']);
		$obj->getImage()->setName($row['image']);
		if (!is_null($row['login_date'])) {
			$obj->setLoginDate(new Date($row['login_date']));
		}
		return $obj;
	}

	/**
	 * @param User $obj
	 * @return mixed[]
	 */
	protected function mapRow($obj) {
		$row['person_id'] = $obj->getId();
		$row['is_enabled'] = $obj->isEnabled();
		$row['access_level'] = $obj->getAccessLevel();
		$row['group_id'] = $obj->getGroupId();
		$row['name'] = $obj->getName();
		$row['email'] = $obj->getEmail();
		if ($obj->getPassword() != null) {
			$row['password_hash'] = $obj->getPasswordHash();
		}
		if ($obj->getRecoreryHash() != null) {
			$row['recovery_hash'] = $obj->getRecoreryHash();
		}
		$row['image'] = $obj->getImage()->getName();
		$row['login_date'] = $obj->getLoginDate()->toSql();
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
	public function updateLoginDate(User $user) {
		$now = new Date();
		$userClone = clone $user;
		$userClone->setLoginDate($now);
		return $this->save($userClone);
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
	 * Atualiza a senha | É necessário informar a senha atual, ou então o recoveryHash
	 * @param int $userId
	 * @param string $newPassword1
	 * @param string $newPassword2
	 * @param string $currentPassword
	 * @param string $recoveryHash
	 * @return string erro
	 */
	public function updatePassword($userId, $newPassword1, $newPassword2, $currentPassword = null, $recoveryHash = null) {
		$user = $this->fetchById($userId);
		$error = $this->validateNewPassword($user, $newPassword1, $newPassword2, $currentPassword, $recoveryHash);

		if (!$error) {
			$user->setPassword($newPassword1);
			$error = $this->save($user);
		}
		return $error;
	}

	/**
	 * Valida se está apto a alterar a senha
	 * @param User $user
	 * @param string $newPassword1
	 * @param string $newPassword2
	 * @param string $currentPassword
	 * @param string $recoveryHash
	 * @return string|null
	 */
	private function validateNewPassword($user, $newPassword1, $newPassword2, $currentPassword, $recoveryHash) {
		if (!is_null($currentPassword) and $user->getPasswordHash() != User::encryptPassword($currentPassword)) {
			return 'A senha atual não está correta.';
		} elseif (!is_null($recoveryHash) and $user->getRecoreryHash() !== $recoveryHash) {
			return 'O link de recuperação é inválido.';
		} elseif (!is_null($user->getPassword()) and strlen($newPassword1) < 4) {
			return 'A nova senha deve possuir pelo menos 4 caracteres.';
		} elseif ($newPassword1 != $newPassword2) {
			return 'A nova senha deve ser informada duas vezes iguais.';
		}
		return null;
	}

	public function fetchByRecoveryHash($recoveryHash) {
		return $this->fetch(['recovery_hash = ?' => $recoveryHash]);
	}

	/** @param User $obj */
	public function delete($obj) {
		$obj->getImage()->remove();
		parent::delete($obj);
	}

}
