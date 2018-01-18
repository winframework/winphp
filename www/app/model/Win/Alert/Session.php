<?php

namespace Win\Alert;

/**
 * Armazena e exibe Alertas
 */
class Session {

	/**
	 * Adicina o alerta no container
	 * @param Alert $alert
	 */
	public static function addAlert(Alert $alert) {
		$_SESSION['alerts'][] = $alert;
	}

	/**
	 * Mostra todos os alertas criados
	 * E remove os alertas da SESSAO
	 */
	public static function showAlerts() {
		foreach (static::getAlerts() as $alert) {
			$alert->load();
		}
		unset($_SESSION['alerts']);
	}

	/**
	 * Retorna array de alertas da Sessao
	 * @return Alert[]
	 */
	public static function getAlerts() {
		if (!isset($_SESSION['alerts'])) {
			$_SESSION['alerts'] = [];
		}
		return array_unique($_SESSION['alerts']);
	}

	/**
	 * Retorna TRUE se possui algum alert
	 * @return boolean
	 */
	public static function hasAlert() {
		return !count(static::getAlerts());
	}

	/**
	 * Cria um alerta de erro ou sucesso, depedendo dos parametros
	 * Usado para simplificar o uso de "AlertError" e "AlertSuccess"
	 * @param string $error
	 * @param string $success
	 */
	public static function alert($error, $success) {
		if (!is_null($error)) {
			new AlertError($error);
		} else {
			new AlertSuccess($success);
		}
	}

}
