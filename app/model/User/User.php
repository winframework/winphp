<?php

namespace User;

/**
 * Usuários do sistema
 */
class User {

	private $id;
	private $email;
	private $pass;

	public function __construct() {
		$this->id = 0;
		$this->email = '';
		$this->pass = '';
	}

	public function getId() {
		return $this->id;
	}

	public function getEmail() {
		return $this->email;
	}

	public function getPass() {
		return $this->pass;
	}

	public function setId($id) {
		$this->id = (int) $id;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function setPass($pass) {
		$this->pass = $pass;
	}

}
