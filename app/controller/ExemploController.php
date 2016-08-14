<?php

namespace controller;

class ExemploController extends \Win\Mvc\Controller {

	public function index() {
		$this->app->setTitle('Exemplo de DAO');

		$userDAO = new \User\UserDAO();
		$user = $userDAO->fetchLast();

		var_dump($user);
		
	}

}
