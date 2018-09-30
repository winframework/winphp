<?php

namespace Win\Message;

use Win\Mvc\Block;
use Win\Request\Session;

/**
 * Alertas
 */
class Alert {

	/** @var Session */
	private $session;

	/** @var static */
	static $instance = null;

	/** @param string $session */
	public function __construct($session = 'default') {
		$this->session = Session::instance($session);
	}

	/**
	 * @return static
	 * @param string $group
	 */
	public static function instance($group = 'default') {
		static::$instance = new Alert($group);
		return static::$instance;
	}

	/**
	 * @param string $message
	 * @param string $type
	 */
	public function alert($message, $type = 'default') {
		$messages = $this->session->get($type, []);
		array_push($messages, $message);
		$this->session->set($type, $messages);
	}

	/** @return mixed[] */
	public function all() {
		return $this->session->all(true);
	}

	/**
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
	public static function success($message) {
		static::instance()->alert($message, 'success');
	}

	/** @param string $message */
	public static function error($message) {
		static::instance()->alert($message, 'danger');
	}

	/** @param string $message */
	public static function info($message) {
		static::instance()->alert($message, 'info');
	}

	/** @param string $message */
	public static function warning($message) {
		static::instance()->alert($message, 'warning');
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
