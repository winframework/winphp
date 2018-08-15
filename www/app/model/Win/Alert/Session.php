<?php

namespace Win\Alert;

/**
 * Armazena e exibe Alertas
 */
class Session {

	/**
	 * Adiciona o alerta na sessão
	 * @param Alert $alert
	 */
	public static function addAlert(Alert $alert) {
		$_SESSION['alerts'][] = $alert;
	}

	/**
	 * Mostra todos os alertas criados, removendo-os da sessão
	 */
	public static function showAlerts() {
		foreach (static::getAlerts() as $alert) {
			$alert->load();
		}
		unset($_SESSION['alerts']);
	}

	/**
	 * Retorna todos os alertas da sessão
	 * @return Alert[]
	 */
	public static function getAlerts() {
		if (!isset($_SESSION['alerts'])) {
			$_SESSION['alerts'] = [];
		}
		return array_unique($_SESSION['alerts']);
	}

	/**
	 * Remove todos os alertas da sessão
	 */
	public static function clearAlerts() {
		unset($_SESSION['alerts']);
	}

	/**
	 * Retorna TRUE se a sessão possui algum alerta
	 * @return boolean
	 */
	public static function hasAlert() {
		return (count(static::getAlerts()) > 0);
	}

}
