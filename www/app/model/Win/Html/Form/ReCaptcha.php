<?php

namespace Win\Html\Form;

use Win\Request\Input;

/**
 * Utiliza o ReCaptcha do Google
 * Validando se o usuário não é um robô
 */
class ReCaptcha {

	public static $siteKey = '';
	public static $secretKey = '';

	/**
	 * Retorna TRUE se usuário marcou "Não sou um robô"
	 * @return boolean
	 */
	public static function isValid() {
		if (static::$siteKey && static::$secretKey) {
			$captcha = Input::post('g-recaptcha-response');
			$response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . static::$secretKey . "&response=" . $captcha . "&remoteip=" . Input::server('REMOTE_ADDR')), true);
			return (boolean) $response['success'];
		} else {
			return true;
		}
	}

}
