<?php

namespace Win\Alert;

use Win\Mvc\Block;

/**
 * Alertas
 * São mensagens armazenadas na sessão e exibidas ao usuárioTYPE
 */
abstract class Alert {

	public $type;
	public $group;
	public $message;
	protected static $file = 'layout/html/alert';

	const TYPE = '';

	/**
	 * Cria um novo alerta
	 * @param string $message
	 */
	public function __construct($message, $group = null) {
		$this->message = $message;
		$this->type = static::TYPE;
		$this->group = $group;
		Session::addAlert($this);
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
	public static function create($error, $success, $group = null) {
		if (!is_null($error)) {
			return new AlertError($error, $group);
		} else {
			return new AlertSuccess($success, $group);
		}
	}

	/**
	 * @param string $group
	 * @return boolean
	 */
	public function isGroup($group) {
		return ($group == '' || $group == $this->group);
	}

	/**
	 * @param string $type
	 * @return boolean
	 */
	public function isType($type = '') {
		return ($type == '' || $type == $this->type);
	}

}
