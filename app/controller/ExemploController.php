<?php

namespace controller;

class ExemploController extends \Win\Mvc\Controller {

	public function index() {
		$this->app->setTitle('Exemplo de DAO');


		$user = $this->app->getUser();
		$user->setName('Wanderson');
		$user->setEmail('wanderson@gmail.com');
		$user->setPassword('123');
		$user->login();


		var_dump($user);
		$user->logout();

	}

}
