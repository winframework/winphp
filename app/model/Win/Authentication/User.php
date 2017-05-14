<?php

namespace Win\Authentication;

use Win\Mvc\Application;
use Win\Authentication\UserDAO;
use Local\Person\Person;
use Local\Person\PersonDAO;
use Win\Helper\Url;
use Win\Mvc\Block;
use Win\Mailer\Email;
use Win\File\Image;
use Win\Calendar\Date;

/**
 * Usuários do sistema
 */
class User {

	private static $accessLevels = [0, 1, 2];

	const ACCESS_DENIED = 0;
	const ACCESS_ALLOWED = 1;
	const ACCESS_ADMIN = 2;

	/* Lock after many login fails */
	const LOCK_TRIES = 5;
	const LOCK_TIME_MINUTES = 10;

	private static $passwordSalt = 'E50H%gDui#';
	private $id;
	private $isEnabled;
	private $isLogged;
	private $accessLevel;
	private $name;
	private $email;
	private $confirmEmail;
	private $password;
	private $confirmPassword;
	private $passwordHash;
	private $recoreryHash;

	/** @var Date */
	private $loginDate;

	/** @var Date */
	private $loginLockDate;
	public $loginFailCount = 0;

	/** @var Image */
	private $image;

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

	public function isEnabled() {
		return $this->isEnabled;
	}

	public function isLogged() {
		return $this->isLogged;
	}

	public function getAccessLevel() {
		return $this->accessLevel;
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

	public function getGroupId() {
		return $this->groupId;
	}

	/** @return Person */
	public function getPerson() {
		if (is_null($this->person)) {
			$pDAO = new PersonDAO();
			$this->person = $pDAO->fetchById($this->id);
		}
		return $this->person;
	}

	public function getName() {
		return $this->name;
	}

	public function getEmail() {
		return $this->email;
	}

	public function getConfirmEmail() {
		return $this->confirmEmail;
	}

	public function getPassword() {
		return $this->password;
	}

	public function getConfirmPassword() {
		return $this->confirmPassword;
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

	public function getLoginDate() {
		return $this->loginDate;
	}

	public function getLoginLockDate() {
		return $this->loginLockDate;
	}

	/** @return Date retorna data que poderá logar novamente sem bloqueio */
	public function getLoginUnlockDate() {
		$date = clone $this->getLoginLockDate();
		$date->sumTime(static::LOCK_TIME_MINUTES, 'minutes');
		return $date;
	}

	public function getLockedMsg() {
		return 'Você foi bloqueado por realizar ' . static::LOCK_TRIES . ' tentativas de login.<br /> Você poderá tentar novamente ' . $this->getLoginUnlockDate()->toHumanFormat() . '.';
	}

	public function setId($id) {
		$this->id = (int) $id;
	}

	public function setEnabled($enabled) {
		$this->isEnabled = (boolean) $enabled;
	}

	public function setAccessLevel($accessLevel) {
		if (in_array($accessLevel, static::$accessLevels)) {
			$this->accessLevel = (int) $accessLevel;
		}
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
		$this->email = strClear($email);
	}

	public function setConfirmEmail($confirmEmail) {
		$this->confirmEmail = strClear($confirmEmail);
	}

	public function setPassword($password) {
		$this->password = $password;
		$this->passwordHash = static::encryptPassword($password);
	}

	public function setConfirmPassword($confirmPassword) {
		$this->confirmPassword = $confirmPassword;
	}

	public function setPasswordHash($passwordHash) {
		$this->passwordHash = $passwordHash;
	}

	public function setRecoreryHash($recoreryHash) {
		$this->recoreryHash = $recoreryHash;
	}

	public function setLoginDate(Date $loginDate) {
		$this->loginDate = $loginDate;
	}

	public function setImage(Image $image) {
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
		$this->setCurrentUser($user);

		if ($user->getId() > 0 && !$this->isLocked()) {
			$this->isLogged = true;
			$uDAO->clearRecoveryHash($user);
			$uDAO->updateLoginDate($user);
			$this->loginFailCount = 0;
		} else {
			$this->incrementLoginFail();
		}

		return $this->isLogged;
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
		$diff = Date::diffSeconds($this->getLoginUnlockDate(), new Date);
		return (boolean) ($diff <= 0 );
	}

	/** @return int total de tentativas restantes até ser bloqueado */
	public function getLoginTriesLeft() {
		return (static::LOCK_TRIES - $this->loginFailCount);
	}

	/** Objeto > Sessão */
	private function setCurrentUser(User $user) {
		$_SESSION['user'] = $this;
		$this->id = $user->getId();
		$this->accessLevel = $user->getAccessLevel();
		$this->name = $user->getName();
		$this->loginDate = $user->getLoginDate();
		$this->image = $user->getImage();
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
	public function requireAdmin() {
		$this->requireLogin();
		if ($this->getAccessLevel() != static::ACCESS_ADMIN) {
			Application::app()->errorPage(403);
		}
	}

	/**
	 * Envia link de recuperacao de senha via Email
	 * @return string | null
	 */
	public function sendRecoveryHash() {
		$filters = ['is_enabled = ?' => true, 'access_level > ?' => 0, 'email = ?' => $this->email];
		$uDAO = new UserDAO();
		$user = $uDAO->fetch($filters);

		if ($user->getId() > 0) {
			$uDAO->updateRecoveryHash($user);
			$content = new Block('email/content/recovery-password', ['user' => $user]);

			$mail = new Email();
			$mail->setFrom(EMAIL_FROM, Application::app()->getName());
			$mail->setSubject('Recuperação de Senha');
			$mail->addAddress($user->getEmail(), $user->getName());
			$mail->setContent($content);
			return $mail->send();
		} else {
			return 'Este E-mail não está cadastrado no sistema.';
		}
	}

	/** Define os atributos que são salvos na SESSAO */
	public function __sleep() {
		return ['id', 'isEnabled', 'isLogged', 'accessLevel', 'name', 'email', 'image', 'loginDate', 'groupId', 'loginFailCount', 'loginLockDate'];
	}

	/**
	 * Adiciona maior segura na senha/ utilizar esta função ao inves de um simples md5
	 * @param string $password
	 */
	public static function encryptPassword($password) {
		return md5($password . static::$passwordSalt);
	}

	/**
	 * Retorna uma senha aleatoria
	 * A senha tem sempre pelo menos: 1 caracter especial e 2 numeros;
	 * @param int $length
	 * @return string
	 */
	public static function generatePassword($length = 6) {
		$letters = str_shuffle('abcdefghijkmnopqrstwxyzABCDEFGHJKLMNPQRSTWXY');
		$numbers = str_shuffle('23456789');
		$specials = str_shuffle('@#&');

		$password = substr($letters, 0, $length - 3)
				. substr($numbers, 0, 2)
				. substr($specials, 0, 1);

		return str_shuffle($password);
	}

}
