<?php

namespace Win\Alert;

use Win\Mvc\Block;

/**
 * Alertas
 * São mensagens armazenadas na sessão e exibidas ao usuárioTYPE
 */
abstract class AbstractAlert {

	public $type;
	public $group;
	public $message;
	protected static $file = 'layout/html/alert';

	const TYPE = '';

	/**
	 * Cria um novo alerta
	 * @param string $message
	 * @param string $group
	 */
	public function __construct($message, $group = '') {
		$this->message = $message;
		$this->type = static::TYPE;
		$this->group = $group;
		Session::addAlert($this);
	}

	/** @return string */
	public function __toString() {
		return $this->getBlock()->toString();
	}

	/** Exibe o conteúdo HTML do alerta */
	public function load() {
		return $this->getBlock()->load();
	}

	/** @return Block */
	protected function getBlock() {
		return new Block(static::$file, ['alert' => $this]);
	}

	/**
	 * Cria um alerta de erro ou sucesso, dependendo dos parâmetros
	 * Possibilitando criar um "AlertError" ou "AlertSuccess" em um único método
	 * @param string|null $error
	 * @param string $success
	 * @param string $group
	 * @return Alert
	 */
	public static function create($error, $success, $group = '') {
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
	public function isType($type) {
		return ($type == '' || $type == $this->type);
	}

}
