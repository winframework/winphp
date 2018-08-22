<?php

namespace Win\Alert;

/**
 * Armazena e exibe Alertas
 */
class Session {

	/** @var boolean */
	protected static $autoSave = true;

	/**
	 * Adiciona o alerta na sessão
	 * @param Alert $alert
	 */
	public static function addAlert(Alert $alert) {
		if (static::$autoSave) {
			$_SESSION['alerts'][] = $alert;
		}
	}

	/**
	 * Liga/Desliga o salvamento automático de alertas na sessão
	 * @param boolean $mode
	 */
	public static function setAutoSave($mode = true) {
		static::$autoSave = $mode;
	}

	/**
	 * Mostra os alertas criados, podendo filtrar por $type e/ou $group
	 * Removendo-os da sessão
	 * @param string $type [optional] Se não informado, retorna todos os tipos
	 * @param string $group [optional] Se não informado, retorna todos os grupos
	 */
	public static function showAlerts($type = '', $group = '') {
		foreach (static::getAlerts($type, $group) as $i => $alert) {
			$alert->load();
			unset($_SESSION['alerts'][$i]);
		}
	}

	/**
	 * Retorna alertas criados, podendo filtrar por $type e/ou $group
	 * @param string $type [optional] Se não informado, retorna todos os tipos
	 * @param string $group [optional] Se não informado, retorna todos os grupos
	 * @return Alert[]
	 */
	public static function getAlerts($type = '', $group = '') {
		$alerts = isset($_SESSION['alerts']) ? array_unique($_SESSION['alerts']) : [];
		foreach ($alerts as $i => $alert) {
			if (!$alert->isType($type) || !$alert->isGroup($group)) {
				unset($alerts[$i]);
			}
		}
		return $alerts;
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
