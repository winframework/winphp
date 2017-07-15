<?php

namespace Win\Widget;

use Win\Request\Input;

/**
 * Validador Captcha em PHP
 */
class Captcha {

	/**
	 * Retorna True se captcha está correto
	 * @return boolean
	 */
	public static function isValid() {
		if (isset($_SESSION['captcha'])) {
			$captcha = strtolower(Input::post('captcha'));
			$sessionCaptcha = strtolower(filter_var($_SESSION['captcha']));
			unset($_SESSION['captcha']);
			return ($captcha == $sessionCaptcha);
		}
		return false;
	}

}
