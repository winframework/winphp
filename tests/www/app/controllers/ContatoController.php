<?php

namespace controllers;

use Win\FlashMessage\Alert;
use Win\Html\Form\ReCaptcha;
use Win\Mail\Email;
use Win\Mvc\Block;
use Win\Mvc\View;
use Win\Request\Input;
use Win\Validation\Validator;

/**
 * Envia um formulário de contato via E-mail
 */
class ContatoController extends \Win\Mvc\Controller
{
	private $sendTo = 'destinatario@example.com';
	private $sendFrom = 'no-reply@example.com';

	private $validations = [
		'name' => ['Nome', 'required'],
		'email' => ['E-mail', 'required'],
		'recaptcha' => ['Não sou um robô', 'checked'],
	];

	/**
	 * Retorna os dados informados
	 * @return mixed[]
	 */
	protected function prepareData()
	{
		$data = [];
		$data['submit'] = Input::post('submit');
		$data['name'] = trim(Input::post('name'));
		$data['phone'] = trim(Input::post('phone'));
		$data['email'] = trim(Input::post('email'));
		$data['subject'] = trim(Input::post('subject'));
		$data['message'] = trim(Input::post('message'));
		$data['recaptcha'] = ReCaptcha::isValid();

		return $data;
	}

	/**
	 * Retorna os dados vazios
	 * @return mixed[]
	 */
	protected function clearData()
	{
		return [
			'name' => '',
			'phone' => '',
			'email' => '',
			'subject' => '',
			'message' => '',
		];
	}

	/**
	 * Retorna erro ao validar
	 * @param mixed[] $data
	 * @return string|null
	 */
	protected function getValidationError(&$data)
	{
		$validator = Validator::create($this->validations);
		$validator->validate($data);

		return $validator->getError();
	}

	/**
	 * Exibe formulário de contato
	 */
	public function index()
	{
		$this->setTitle('Contato | ' . $this->app->getName());
		$error = null;
		$data = $this->prepareData();

		// Se clicou em Enviar
		if (!empty($data['submit'])) {
			$error = $this->getValidationError($data);

			// Envia Email
			if (is_null($error)) {
				$mail = new Email();
				$mail->setSubject('Contato efetuado pelo site ' . $this->app->getName());
				$mail->addAddress($this->sendTo);
				$mail->setFrom($this->sendFrom, $this->app->getName());
				$mail->addReplyTo($data['email'], $data['name']);

				$content = new Block('email/contents/contact', $data);
				$mail->setContent($content);
				$mail->send();
				$error = $mail->getError();
				$data = $this->clearData();
			}

			Alert::create($error, 'Sua mensagem foi enviada com sucesso!');
		} else {
			Alert::alert('Preencha os campos baixo:');
		}

		return new View('contato', $data);
	}
}
