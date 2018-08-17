<?php

namespace Win\Alert;

use Win\Mvc\Block;

/**
 * Alertas
 * São mensagens armazenadas na sessão e exibidas ao usuário
 */
abstract class Alert {

	public $type;
	public $message;
	protected static $file = 'layout/html/alert';

	/**
	 * Cria um novo alerta
	 * @param string $type
	 * @param string $message
	 * @param boolean $saveOnSession [optional]
	 */
	public function __construct($type, $message, $saveOnSession = true) {
		$this->type = $type;
		$this->message = $message;
		if ($saveOnSession) {
			Session::addAlert($this);
		}
	}

	/** @return string */
	public function __toString() {
		return $this->message;
	}

	/**
	 * Exibe o conteúdo HTML do alerta
	 */
	public function load() {
		$block = new Block(static::$file, ['alert' => $this]);
		$block->load();
	}

	/**
	 * Cria um alerta de erro ou sucesso, dependendo dos parâmetros
	 * Possibilitando criar um "AlertError" ou "AlertSuccess" em um único método
	 * @param string|null $error
	 * @param string $success
	 * @return Alert
	 */
	public static function create($error, $success) {
		if (!is_null($error)) {
			return new AlertError($error);
		} else {
			return new AlertSuccess($success);
		}
	}

}
