<?php

namespace controller;

use Win\Mvc\Controller;
use Win\Mvc\View;

/**
 * Este controller é usado para exemplo
 *
 */
class ExemploController extends Controller {

	/**
	 * Titulo AUTOMÁTICO e
	 * View AUTOMÁTICA
	 */
	public function index() {
	}

	/**
	 * Titulo MANUAL e
	 * View AUTOMÁTICA
	 */
	public function foo() {
		$this->app->setTitle('Exemplo Foo Automático');
	}

	/**
	 * Titulo MANUAL e
	 * View MANUAL
	 */
	public function bar() {
		$this->app->setTitle('Exemplo Bar Manual');
		return new View('exemplo/custom-bar');
	}

}
