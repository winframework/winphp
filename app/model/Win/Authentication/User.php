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

	const ACCESS_DISALLOWED = 0;
	const ACCESS_ALLOWED = 1;
	const ACCESS_ADMIN = 2;

	private $id;
	private $isActive;
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
		$this->isActive = true;
		$this->isLogged = false;
		$this->accessLevel = self::ACCESS_DISALLOWED;
		$this->group = null;
		$this->groupId = 0;
		$this->person = null;
		$this->name = '';
		$this->email = '';
		$this->password = '********';
		$this->passwordHash = '';
		$this->recoreryHash = null;
		$this->image = null;
		$this->lastLogin = null;

		if (isset($_SESSION['user'])) {
			$this->fromSession();
		}
	}

	public function getId() {
		return $this->id;
	}

	public function isActive() {
		return $this->isActive;
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

	public function setActive($active) {
		$this->isActive = (boolean) $active;
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
			'is_active = ?' => true,
			'access_level > ?' => 0,
			'email = ?' => $this->email,
			'password_hash = ?' => $this->passwordHash
		];
		$uDAO = new UserDAO();
		$user = $uDAO->fetch($filters);
		if ($user->getId() > 0) {
			$this->toSession($user);
			$this->fromSession();
			$uDAO->updateLastLogin($user);
		}
		return $this->isLogged;
	}

	/**
	 * Realiza logout
	 */
	public function logout() {
		unset($_SESSION['user']);
		$this->__construct();
	}

	/** Objeto > Sessão */
	private function toSession(User $user) {
		$user->isLogged = TRUE;
		$_SESSION['user'] = [
			'logged' => $user->isLogged(),
			'id' => $user->getId(),
			'access_level' => $user->getAccessLevel(),
			'group_id' => $user->getGroupId(),
			'name' => $user->getName(),
			'email' => $user->getEmail(),
			'image' => $user->getImage(),
			'last_login' => $user->getLastLogin()
		];
	}

	/** Objeto < Sessão */
	private function fromSession() {
		$session = $_SESSION['user'];
		$this->isLogged = true;
		$this->id = $session['id'];
		$this->accessLevel = $session['access_level'];
		$this->groupId = $session['group_id'];
		$this->name = $session['name'];
		$this->email = $session['email'];
		$this->image = $session['image'];
		$this->lastLogin = $session['last_login'];
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
		$filters = ['is_active = ?' => true, 'access_level > ?' => 0, 'email = ?' => $this->email];
		$uDAO = new UserDAO();
		$users = $uDAO->fetchAll($filters);
		
		if (count($users) > 0) {
			$success = true;
			$user = $users[0];
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

}
