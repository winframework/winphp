<?php

namespace Win\Alert;

/**
 * Alertas
 */
class Alert {

	public function __construct(Session $message, $type = 'default') {
		$this->message = $message;
	}

	public function get($type) {
		return [];
	}

	public static function all() {
		return [];
	}

	public static function success($message) {
		return new Alert($message, 'success');
	}

	public static function error($message) {
		return new Alert($message, 'danger');
	}

	public static function info($message) {
		return new Alert($message, 'info');
	}

	public static function toHtml() {
		echo new Block('layout/html/alert');
	}

}
