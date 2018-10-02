<?php

namespace Win\Mvc;

/**
 * Controller Padrão
 * 
 * Chamado quando não há um Controller criado para a página
 */
final class DefaultController extends Controller {

	public function index() {
		
	}

	protected function init() {

	}

	public function __construct() {
		parent::__construct('index');
	}

	public function __call($name, $arguments) {
		return true;
	}

}
