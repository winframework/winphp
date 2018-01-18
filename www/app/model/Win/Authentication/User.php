<?php

namespace Win\Authentication;

use Local\Person\Person;
use Local\Person\PersonDAO;
use Win\Alert\AlertError;
use Win\Authentication\UserDAO;
use Win\Calendar\Date;
use Win\File\Image;
use Win\Helper\Url;
use Win\Mvc\Application;

/**
 * Usuários do sistema
 */
class User {

	const ACCESS_DENIED = 0;
	const ACCESS_ALLOWED = 1;
	const ACCESS_ADMIN = 2;

	/* Lock after many login fails */
	const LOCK_TRIES = 5;
	const LOCK_TIME_MINUTES = 10;

	public $id;
	public $isEnabled;
	private $isLogged;
	public $accessLevel;
	public $name;
	private $email;
	private $confirmEmail;
	private $password;
	private $confirmPassword;
	public $passwordHash;
	public $recoreryHash;

	/** @var Date */
	public $loginDate;

	/** @var Date */
	public $loginLockDate;
	public $loginFailCount = 0;

	/** @var Image */
	public $image;

	/** @var Group */
	private $group;
	public $groupId;

	/** @var Person */
	private $person;

	public function __construct() {
		$this->id = 0;
		$this->isEnabled = true;
		$this->isLogged = false;
		$this->accessLevel = self::ACCESS_DENIED;
		$this->name = '';
		$this->email = '';
		$this->confirmEmail = '';
		$this->password = null;
		$this->confirmPassword = null;
		$this->passwordHash = null;
		$this->recoreryHash = null;
		$this->image = new Image();
		$this->image->setDirectory('data/upload/user');
		$this->loginDate = new Date('00/00/0000');
		$this->loginLockDate = new Date('00/00/0000');
		$this->group = null;
		$this->groupId = 0;
		$this->person = null;
	}

	public function getId() {
		return $this->id;
	}

	public function isLogged() {
		return $this->isLogged;
	}

	public function accessIsDenied() {
		return ($this->accessLevel == self::ACCESS_DENIED);
	}

	/** @return boolean */
	public function isAdmin() {
		return ($this->accessLevel == self::ACCESS_ADMIN);
	}

	public function getGroup() {
		if (is_null($this->group)) {
			// groupDAO
		}
		return $this->group;
	}

	public function getPassword() {
		return $this->password;
	}

	public function getEmail() {
		return $this->email;
	}

	/** @return Person */
	public function getPerson() {
		if (is_null($this->person)) {
			$pDAO = new PersonDAO();
			$this->person = $pDAO->fetchById($this->id);
		}
		return $this->person;
	}

	/** @return Date retorna data que poderá logar novamente sem bloqueio */
	public function getLoginUnlockDate() {
		$date = clone $this->loginLockDate;
		$date->sumTime(static::LOCK_TIME_MINUTES, 'minutes');
		return $date;
	}

	public function getLockedMsg() {
		return 'Você foi bloqueado por realizar ' . static::LOCK_TRIES . ' tentativas de login.<br /> Você poderá tentar novamente ' . $this->getLoginUnlockDate()->toTimeAgo() . '.';
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setPerson(Person $person) {
		$this->person = $person;
	}

	public function setEmail($email, $confirmEmail = null) {
		$this->email = $email;
		$this->confirmEmail = $confirmEmail;
	}

	public function setPassword($password, $confirmPassword = null) {
		$this->password = $password;
		$this->confirmPassword = $confirmPassword;
		$this->passwordHash = Password::encrypt($password);
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
		$this->setCurrentUser($user);

		if ($user->id > 0 && !$this->isLocked()) {
			$this->isLogged = true;
			RecoveryPassword::clearHash($user);
			$uDAO->updateLoginDate($user);
			$this->loginFailCount = 0;
		} else {
			$this->incrementLoginFail();
		}

		return $this->isLogged;
	}

	/** @return boolean TRUE se preencheu os emails iguais */
	public function confirmEmail() {
		return $this->confirmEmail !== null || $this->confirmEmail == $this->email;
	}

	/** @return boolean TRUE se preencheu as senhas iguais */
	public function confirmPassword() {
		return $this->confirmPassword == null || $this->confirmPassword == $this->password;
	}

	/** Realiza logout */
	public function logout() {
		unset($_SESSION['user']);
	}

	private function incrementLoginFail() {
		$this->loginFailCount++;
		if ($this->loginFailCount >= static::LOCK_TRIES && !$this->isLocked()) {
			$this->loginLockDate = new Date();
			$this->loginFailCount = 0;
		}
	}

	/** @return boolean retorna TRUE se está bloqueado por tentativas de login */
	public function isLocked() {
		$diff = $this->getLoginUnlockDate()->diff(new Date());
		return (boolean) ($diff > 0 );
	}

	/** @return int total de tentativas restantes até ser bloqueado */
	public function getLoginTriesLeft() {
		return (static::LOCK_TRIES - $this->loginFailCount);
	}

	/** Objeto > Sessão */
	private function setCurrentUser(User $user) {
		$_SESSION['user'] = $this;
		$this->id = $user->id;
		$this->accessLevel = $user->accessLevel;
		$this->name = $user->name;
		$this->loginDate = $user->loginDate;
		$this->image = $user->image;
	}

	/** Objeto < Sessão */
	public static function getCurrentUser() {
		/* @var $user User */
		$user = (isset($_SESSION['user'])) ? $_SESSION['user'] : new User();
		static::preventDeletedAndLogged($user);
		return $user;
	}

	/**
	 * Evita que um usuário seja removido e continue logado
	 * @param User $user
	 */
	private static function preventDeletedAndLogged(User $user) {
		if ($user->isLogged) {
			$dao = new UserDAO();
			if (!$dao->objExists($user)) {
				$user->logout();
				new AlertError('Sua sessão foi finalizada, tente realizar o login novamente.');
				Application::app()->refresh();
			}
		}
	}

	/** Obriga o usuário a se logar */
	public function requireLogin() {
		if (!$this->isLogged) {
			Url::instance()->redirect('login');
		}
	}

	/** Obriga o usuário a logar como ADMIN */
	public function requireAdmin() {
		$this->requireLogin();
		if ($this->getAccessLevel() != static::ACCESS_ADMIN) {
			Application::app()->errorPage(403);
		}
	}

	/** Define os atributos que são salvos na SESSAO */
	public function __sleep() {
		return ['id', 'isEnabled', 'isLogged', 'accessLevel', 'name', 'email', 'image', 'loginDate', 'groupId', 'loginFailCount', 'loginLockDate'];
	}

}
