<?php

namespace Win\Alert;

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

}
