<?php

namespace controllers;

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

	public function init()
	{
		// ReCaptcha::$siteKey = '';
	}

	public function index()
	{
		$this->setTitle('Contato | ' . $this->app->getName());

		/* Pega campos via POST */
		$error = null;
		$data = [];
		$submit = Input::post('submit');
		$data['name'] = trim(Input::post('name'));
		$data['phone'] = trim(Input::post('phone'));
		$data['email'] = trim(Input::post('email'));
		$data['subject'] = trim(Input::post('subject'));
		$data['message'] = trim(Input::post('message'));
		$data['recaptcha'] = ReCaptcha::isValid();

		/* Se clicou em Enviar */
		if (!empty($submit)) {
			/* Valida os Campos */
			$validator = Validator::create($data);
			$data = $validator->validate($this->validations);
			$error = $validator->getError();

			/* Envia Email */
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

				/* Limpa dados */
				$data['name'] = '';
				$data['phone'] = '';
				$data['email'] = '';
				$data['subject'] = '';
				$data['message'] = '';
			}
		}

		/* Envia dados para View */
		$data['error'] = $error;
		$data['submit'] = $submit;

		return new View('contato', $data);
	}
}
