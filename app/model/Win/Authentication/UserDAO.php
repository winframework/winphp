<?php

namespace Win\Authentication;

use Win\Authentication\Password;
use Win\Authentication\User;
use Win\Calendar\Date;
use Win\DAO\DAO;

/**
 * User DAO
 * @method User fetch
 * @method User fetchAll
 */
class UserDAO extends DAO {

	const TABLE = 'person';
	const ALIAS = 'Usuário';

	/** @var User */
	protected $obj;

	/** @return string|null */
	protected function validate() {

		if (strlen($this->obj->name) < 2) {
			return 'O campo Nome deve possuir pelo menos 2 caracteres.';
		} elseif (!$this->obj->accessIsDenied() && strlen($this->obj->getEmail()) == 0) {
			return 'O campo E-mail deve ser preenchido.';
		} elseif (!$this->obj->accessIsDenied() && !filter_var($this->obj->getEmail(), FILTER_VALIDATE_EMAIL)) {
			return 'O campo E-mail deve ser um e-mail válido.';
		} elseif (!$this->obj->accessIsDenied() && $this->obj->confirmEmail()) {
			return 'O campo E-mail deve ser informado duas vezes iguais.';
		} elseif (strlen($this->obj->getEmail()) > 0 and $this->emailIsUsed()) {
			return 'Já existe um usuário com este e-mail.';
		} elseif (!$this->obj->accessIsDenied() && ($this->obj->getPassword() !== null || $this->obj->getId() === 0) && strlen($this->obj->getPassword()) < 4) {
			return 'A senha deve possuir pelo menos 4 caracteres.';
		} elseif (!$this->obj->confirmPassword()) {
			return 'O campo Senha deve ser informado duas vezes iguais.';
		}
		return null;
	}

	/**
	 * @param mixed[] $row
	 * @return User
	 */
	public static function mapObject($row) {
		$obj = new User();
		$obj->id = $row['person_id'];
		$obj->isEnabled = $row['is_enabled'];
		$obj->accessLevel = $row['access_level'];
		//$obj->setGroupId($row['group_id']);
		$obj->name = $row['name'];
		$obj->setEmail($row['email']);
		$obj->passwordHash = $row['password_hash'];
		$obj->recoreryHash = $row['recovery_hash'];
		$obj->image->setName($row['image']);
		$obj->loginDate = new Date($row['login_date']);
		return $obj;
	}

	/**
	 * @param User $obj
	 * @return mixed[]
	 */
	public static function mapRow($obj) {
		$row['person_id'] = $obj->id;
		$row['is_enabled'] = (int) $obj->isEnabled;
		$row['access_level'] = $obj->accessLevel;
		$row['name'] = strClear($obj->name);
		$row['email'] = strClear($obj->getEmail());
		//$row['image'] = $obj->image->getName();
		$row['login_date'] = $obj->loginDate->toSql();
		if (!is_null($obj->passwordHash)) {
			$row['password_hash'] = $obj->passwordHash;
		}
		if (!is_null($obj->recoreryHash)) {
			$row['recovery_hash'] = $obj->recoreryHash;
		}
		return $row;
	}

	/**
	 * Atualiza data ultimo login
	 * @param User $user
	 * @return string|null
	 */
	public function updateLoginDate(User $user) {
		$user->loginDate = new Date();
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
		if (!is_null($currentPassword) and $savedUser->passwordHash != Password::encrypt($currentPassword)) {
			return 'A senha atual não está correta.';
		} elseif (!is_null($recoveryHash) and $user->recoreryHash !== $recoveryHash) {
			return 'O link de recuperação é inválido.';
		}
		return $this->save($user);
	}

	/**
	 * Retorna true se já existe este email no sistema 
	 * @return boolean
	 */
	public function emailIsUsed() {
		return $this->numRows(['email = ?' => $this->obj->getEmail(), 'person_id <> ?' => $this->obj->id]);
	}

	public function fetchByRecoveryHash($recoveryHash) {
		return $this->fetch(['recovery_hash = ?' => $recoveryHash]);
	}

	public function onDelete() {
		$this->obj->image->remove();
	}

	/**
	 * Insere o primeiro admin
	 * @param User $user
	 * @return string|null
	 */
	public function insertFirst(User $user) {
		$user->name = 'Administrador';
		$user->accessLevel = User::ACCESS_ADMIN;

		if ($this->numRows() === 0) {
			return $this->save($user);
		}
	}

}
