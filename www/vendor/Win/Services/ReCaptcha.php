<?php

namespace Win\Services;

use Win\InjectableTrait;
use Win\Utils\Input;

/**
 * ReCaptcha do Google
 * Validando se o usuário não é um robô
 */
class ReCaptcha
{
	use InjectableTrait;

	/**
	 * Retorna TRUE se usuário marcou "Não sou um robô"
	 * @return bool
	 */
	public function isValid()
	{
		if (!defined('RECAPTCHA_SITE_KEY') || !defined('RECAPTCHA_SECRET_KEY')) {
			return true;
		}

		$response = json_decode(file_get_contents($this->getValidationUrl()), true);
		return (bool) $response['success'];
	}

	/**
	 * Retorna a URL de validação
	 * @return string
	 */
	private function getValidationUrl()
	{
		return 'https://www.google.com/recaptcha/api/siteverify'
			. '?secret=' . RECAPTCHA_SECRET_KEY
			. '&response=' . Input::post('g-recaptcha-response')
			. '&remoteip=' . Input::server('REMOTE_ADDR');
	}
}
