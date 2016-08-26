<?php

namespace Win\Authentication;

use Win\Authentication\UserDAO;
use Win\Helper\Url;
use Win\Mvc\Block;
use Win\Mailer\Email;

/**
 * Usuários do sistema
 */
class User {

	const ACCESS_DENIED = 0;
	const ACCESS_ALLOWED = 1;
	const ACCESS_ADMIN = 2;

	private $id;
	private $isEnabled;
	private $isLogged;
	private $accessLevel;
	private $name;
	private $email;
	private $password;
	private $passwordHash;
	private $recoreryHash;
	private $image;
	private $lastLogin;

	/** @var Group */
	private $group;
	private $groupId;

	/** @var Person */
	private $person;

	public function __construct() {
		$this->id = 0;
		$this->isEnabled = true;
		$this->isLogged = false;
		$this->accessLevel = self::ACCESS_DENIED;
		$this->name = '';
		$this->email = '';
		$this->password = '********';
		$this->passwordHash = '';
		$this->recoreryHash = null;
		$this->image = null;
		$this->lastLogin = null;
		$this->group = null;
		$this->groupId = 0;
		$this->person = null;
	}

	public function getId() {
		return $this->id;
	}

	public function isEnabled() {
		return $this->isEnabled;
	}

	public function isLogged() {
		return $this->isLogged;
	}

	public function getAccessLevel() {
		return $this->accessLevel;
	}

	/** @return boolean */
	public function isMaster() {
		return ($this->accessLevel == self::ACCESS_ADMIN);
	}

	public function getGroup() {
		if (is_null($this->group)) {
			// groupDAO
		}
		return $this->group;
	}

	public function getGroupId() {
		return $this->groupId;
	}

	public function getPerson() {
		if (is_null($this->person)) {
			// personDAO
		}
		return $this->person;
	}

	public function getName() {
		return $this->name;
	}

	public function getEmail() {
		return $this->email;
	}

	public function getPassword() {
		return $this->password;
	}

	public function getPasswordHash() {
		return $this->passwordHash;
	}

	public function getRecoreryHash() {
		return $this->recoreryHash;
	}

	public function getImage() {
		return $this->image;
	}

	public function getLastLogin() {
		return $this->lastLogin;
	}

	public function setId($id) {
		$this->id = (int) $id;
	}

	public function setEnabled($enabled) {
		$this->isEnabled = (boolean) $enabled;
	}

	public function setAccessLevel($accessLevel) {
		$this->accessLevel = (int) $accessLevel;
	}

	public function setGroup(Group $group) {
		$this->group = $group;
	}

	public function setGroupId($groupId) {
		$this->groupId = (int) $groupId;
	}

	public function setPerson(Person $person) {
		$this->person = $person;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function setPassword($password) {
		$this->password = $password;
		$this->passwordHash = md5($password);
	}

	public function setPasswordHash($passwordHash) {
		$this->passwordHash = $passwordHash;
	}

	public function setRecoreryHash($recoreryHash) {
		$this->recoreryHash = $recoreryHash;
	}

	public function setLastLogin($lastLogin) {
		$this->lastLogin = $lastLogin;
	}

	public function setImage($image) {
		$this->image = $image;
	}

	/**
	 * Tenta realizar login
	 * @return boolean
	 */
	public function login() {
		$filters = [
			'is_enabled = ?' => true,
			'access_level > ?' => 0,
			'email = ?' => $this->email,
			'password_hash = ?' => $this->passwordHash
		];
		$uDAO = new UserDAO();
		$user = $uDAO->fetch($filters);

		if ($user->getId() > 0) {
			$this->setCurrentUser($user);
			$uDAO->updateLastLogin($user);
		}
		return $user->isLogged;
	}

	/** Realiza logout */
	public function logout() {
		unset($_SESSION['user']);
	}

	/** Objeto > Sessão */
	private function setCurrentUser(User $user) {
		$_SESSION['user'] = $user;
		$this->isLogged = true;
		$this->id = $user->getId();
		$this->accessLevel = $user->getAccessLevel();
		$this->name = $user->getName();
		$this->lastLogin = $user->getLastLogin();
	}

	/** Objeto < Sessão */
	public static function getCurrentUser() {
		return (isset($_SESSION['user'])) ? $_SESSION['user'] : new User();
	}

	/** Obriga o usuário a se logar */
	public function requireLogin() {
		if (!$this->isLogged) {
			Url::instance()->redirect('login');
		}
	}

	/** Obriga o usuário a logar como ADMIN */
	public function requireMaster() {
		if (!$this->isLogged || $this->getAccessLevel() != static::ACCESS_ADMIN) {
			Url::instance()->redirect('login');
		}
	}

	/**
	 * Envia link de recuperacao de senha via Email
	 * @return boolean
	 */
	public function sendRecoveryHash() {
		$success = false;
		$filters = ['is_enabled = ?' => true, 'access_level > ?' => 0, 'email = ?' => $this->email];
		$uDAO = new UserDAO();
		$user = $uDAO->fetch($filters);

		if ($user->getId() > 0) {
			$success = true;
			$uDAO->updateRecoveryHash($user);
			$body = new Block('email/html/recovery-password', ['user' => $user]);

			$mail = new Email();
			$mail->setSubject('Recuperação de Senha');
			$mail->addAddress($user->getEmail(), $user->getName());
			$mail->setBody($body);
			$mail->send();
		}
		return $success;
	}

	/** Define os atributos que são salvos na SESSAO */
	public function __sleep() {
		return ['id', 'isEnabled', 'isLogged', 'accessLevel', 'name', 'email', 'image', 'last_login', 'group_id'];
	}

}
