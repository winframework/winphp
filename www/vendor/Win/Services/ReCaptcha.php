<?php

namespace Win\Services;

use Win\Common\InjectableTrait;
use Win\Common\Utils\Input;

/**
 * ReCaptcha do Google
 * Validando se o usuário não é um robô
 */
class ReCaptcha
{
	use InjectableTrait;
	public $siteKey = '';
	public $secretKey = '';

	/**
	 * Retorna TRUE se usuário marcou "Não sou um robô"
	 * @return bool
	 */
	public function isValid()
	{
		if ($this->siteKey && $this->secretKey) {
			$response = json_decode(file_get_contents($this->getValidationUrl()), true);

			return (bool) $response['success'];
		} else {
			return true;
		}
	}

	/**
	 * Retorna a URL de validação
	 * @return string
	 */
	private function getValidationUrl()
	{
		return 'https://www.google.com/recaptcha/api/siteverify'
			. '?secret=' . $this->secretKey
			. '&response=' . Input::post('g-recaptcha-response')
			. '&remoteip=' . Input::server('REMOTE_ADDR');
	}
}
