<?php

namespace Win\Message;

use Win\DesignPattern\SingletonTrait;
use Win\Mvc\Block;
use Win\Request\Session;

/**
 * Alertas
 */
class Alert {

	/** @var Session */
	private $session;

	use SingletonTrait {
		instance as instanceSingleton;
	}

	/**
	 * @return static
	 * @param string $group
	 */
	public static function instance($group = 'default') {
		$instance = static::instanceSingleton();
		$instance->session = Session::instance('alerts.' . $group);
		return $instance;
	}

	/**
	 * Adiciona um mensagem de alerta
	 * @param string $message
	 * @param string $type
	 */
	public function add($message, $type = 'default') {
		$messages = $this->session->get($type, []);
		array_push($messages, $message);
		$this->session->set($type, $messages);
	}

	/** @return mixed[] */
	public function all() {
		return $this->session->all(true);
	}

	/**
	 * Retorna os alertas por $type
	 * @return mixed[]
	 * @param string $type
	 */
	public function get($type) {
		return $this->session->get($type, null, true);
	}

	/** Limpa mensagens */
	public function clear() {
		$this->session->clear();
	}

	/**
	 * Exibe html dos alertas
	 * @return Block
	 */
	public function html() {
		return new Block('layout/html/alerts', ['alerts' => $this->session->all(true)]);
	}

	/** @param string $message */
	public static function alert($message) {
		static::instance()->add($message, 'default');
	}

	/** @param string $message */
	public static function success($message) {
		static::instance()->add($message, 'success');
	}

	/** @param string $message */
	public static function error($message) {
		static::instance()->add($message, 'danger');
	}

	/** @param string $message */
	public static function info($message) {
		static::instance()->add($message, 'info');
	}

	/** @param string $message */
	public static function warning($message) {
		static::instance()->add($message, 'warning');
	}

	/**
	 * Cria um alerta de erro ou sucesso em um único método
	 * @param string|null $error
	 * @param string $success
	 */
	public static function create($error, $success) {
		if (!is_null($error)) {
			static::error($error);
		} else {
			static::success($success);
		}
	}

}
