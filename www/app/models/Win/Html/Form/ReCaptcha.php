<?php

namespace Win\Html\Form;

use Win\Request\Input as RequestInput;

/**
 * ReCaptcha do Google
 * Validando se o usuário não é um robô
 */
class ReCaptcha
{
	public static $siteKey = '';
	public static $secretKey = '';

	/**
	 * Retorna TRUE se usuário marcou "Não sou um robô"
	 *
	 * @return bool
	 */
	public static function isValid()
	{
		if (static::$siteKey && static::$secretKey) {
			$response = json_decode(file_get_contents(static::getValidationUrl()), true);

			return (bool) $response['success'];
		} else {
			return true;
		}
	}

	/**
	 * Retorna a URL de validação
	 *
	 * @return string
	 */
	public static function getValidationUrl()
	{
		return 'https://www.google.com/recaptcha/api/siteverify'
			. '?secret=' . static::$secretKey
			. '&response=' . RequestInput::post('g-recaptcha-response')
			. '&remoteip=' . RequestInput::server('REMOTE_ADDR');
	}
}
