<?php

namespace Win\Alert;

use Win\Alert\Alert;
use Win\Mvc\Block;

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
		if (isset($_SESSION['alerts'])) {
			foreach (array_unique($_SESSION['alerts']) as $alert) {
				static::showAlert($alert);
			}
		}
		unset($_SESSION['alerts']);
	}

	private static function showAlert(Alert $alert) {
		$block = new Block('alert/alert', ['alert' => $alert]);
		$block->toHtml();
	}

}
