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
		} elseif (!$this->obj->accessIsDenied() && strlen($this->obj->getEmail()) == 0) {
			return 'O campo E-mail deve ser preenchido.';
		} elseif (!$this->obj->accessIsDenied() && !filter_var($this->obj->getEmail(), FILTER_VALIDATE_EMAIL)) {
			return 'O campo E-mail deve ser um e-mail válido.';
		} elseif (!$this->obj->accessIsDenied() && $this->obj->getConfirmEmail() !== null && $this->obj->getConfirmEmail() != $this->obj->getEmail()) {
			return 'O campo E-mail deve ser informado duas vezes iguais.';
		} elseif (strlen($this->obj->getEmail()) > 0 and $this->emailIsUsed()) {
			return 'Já existe um usuário com este e-mail.';
		} elseif (!$this->obj->accessIsDenied() && $this->obj->getPassword() !== null && strlen($this->obj->getPassword()) < 4) {
			return 'A senha deve possuir pelo menos 4 caracteres.';
		} elseif ($this->obj->getConfirmPassword() != $this->obj->getPassword()) {
			return 'O campo Senha deve ser informado duas vezes iguais.';
		}
		return null;
	}

	/**
	 * @param array $row
	 * @return User
	 */
	public static function mapObject($row) {
		$obj = new User();
		$obj->setId($row['person_id']);
		$obj->setEnabled($row['is_enabled']);
		$obj->setAccessLevel($row['access_level']);
		//$obj->setGroupId($row['group_id']);
		$obj->setName($row['name']);
		$obj->setEmail($row['email']);
		$obj->setConfirmEmail($row['email']);
		$obj->setPasswordHash($row['password_hash']);
		$obj->setRecoreryHash($row['recovery_hash']);
		$obj->getImage()->setName($row['image']);
		$obj->setLoginDate(new Date($row['login_date']));
		return $obj;
	}

	/**
	 * @param User $obj
	 * @return mixed[]
	 */
	public static function mapRow($obj) {
		$row['person_id'] = $obj->getId();
		$row['is_enabled'] = (int) $obj->isEnabled();
		$row['access_level'] = $obj->getAccessLevel();
		$row['name'] = strClear($obj->getName());
		$row['email'] = strClear($obj->getEmail());
		$row['image'] = $obj->getImage()->getName();
		$row['login_date'] = $obj->getLoginDate()->toSql();
		if (!is_null($obj->getPasswordHash())) {
			$row['password_hash'] = $obj->getPasswordHash();
		}
		if (!is_null($obj->getRecoreryHash())) {
			$row['recovery_hash'] = $obj->getRecoreryHash();
		}
		return $row;
	}

	/**
	 * Atualiza data ultimo login
	 * @param User $user
	 * @return string|null
	 */
	public function updateLoginDate(User $user) {
		$now = new Date();
		$user->setLoginDate($now);
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
	 * Limpa o recoveryHash
	 * @param User $user
	 * @return string|null
	 */
	public function clearRecoveryHash(User $user) {
		$user->setRecoreryHash('');
		return $this->save($user);
	}

	/**
	 * Atualiza a senha | É necessário informar a senha atual, ou então o recoveryHash
	 * @param User $user
	 * @param string $currentPassword
	 * @param string $recoveryHash
	 * @return string|null
	 */
	public function updatePassword($user, $currentPassword = null, $recoveryHash = null) {
		$savedUser = $this->fetchById($user->getId());
		if (!is_null($currentPassword) and $savedUser->getPasswordHash() != User::encryptPassword($currentPassword)) {
			return 'A senha atual não está correta.';
		} elseif (!is_null($recoveryHash) and $user->getRecoreryHash() !== $recoveryHash) {
			return 'O link de recuperação é inválido.';
		}
		return $this->save($user);
	}

	/**
	 * Retorna true se já existe este email no sistema 
	 * @return boolean
	 */
	public function emailIsUsed() {
		return $this->numRows(['email = ?' => $this->obj->getEmail(), 'person_id <> ?' => $this->obj->getId()]);
	}

	public function fetchByRecoveryHash($recoveryHash) {
		return $this->fetch(['recovery_hash = ?' => $recoveryHash]);
	}

	public function onDelete() {
		$this->obj->getImage()->remove();
	}

	/**
	 * Insere o primeiro admin
	 * @param User $user
	 * @return string|null
	 */
	public function insertFirst(User $user) {
		$user->setName('Administrador');
		$user->setAccessLevel(User::ACCESS_ADMIN);
		$user->setConfirmEmail($user->getEmail());
		$user->setConfirmPassword($user->getPassword());

		if ($this->numRows() === 0) {
			return $this->save($user);
		}
	}

}
