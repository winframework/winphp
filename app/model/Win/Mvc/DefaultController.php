<?php

namespace Win\Mvc;

/**
 * Controller Padrão
 * 
 * Chamado quando não há um controller criado para a página
 */
final class DefaultController extends Controller {

	public function index() {
		
	}

	public function init() {

	}

	public function __call($name, $arguments) {
		return true;
	}

}
