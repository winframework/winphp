<?php

namespace Win\Alert;

use Win\Mvc\Block;

/**
 * Alertas
 * São mensagens exibidas na tela
 */
abstract class Alert {

	public $type;
	public $message;

	/**
	 * Cria uma nova mensagem
	 * @param string $type
	 * @param string $message
	 */
	public function __construct($type, $message) {
		$this->type = $type;
		$this->message = $message;
		Session::addAlert($this);
	}

	public function __toString() {
		return $this->message;
	}

	/**
	 * Carrega o html do alerta
	 * @param Alert $alert
	 */
	public function load() {
		$block = new Block('layout/html/alert', ['alert' => $this]);
		$block->load();
	}

}
