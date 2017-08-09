<?php

namespace controller;

use Win\Mvc\View;
use Win\Mvc\Block;
use Win\Request\Input;
use Win\Mailer\Email;
use Win\Widget\ReCaptcha;

/**
 * Envia um formulario de contato via E-mail
 */
class ContatoController extends \Win\Mvc\Controller {

	private $sendTo = 'destinatario@example.com';
	private $sendFrom = 'no-reply@example.com';

	public function index() {
		$this->setTitle('Contato | ' . $this->app->getName());


		/* Pega campos via POST */
		$submit = Input::post('submit');
		$name = trim(strip_tags(Input::post('name')));
		$phone = trim(strip_tags(Input::post('phone')));
		$email = trim(strip_tags(Input::post('email')));
		$subject = trim(strip_tags(Input::post('subject')));
		$message = trim(strip_tags(Input::post('message')));

		/* Prepara dados para view */
		$error = null;
		$data = [];
		$data['name'] = $name;
		$data['phone'] = $phone;
		$data['email'] = $email;
		$data['subject'] = $subject;
		$data['message'] = $message;

		/* Se clicou em Enviar */
		if (!empty($submit)) {

			/* Valida os Campos */
			if (empty($name)) {
				$error = 'Preencha o campo Nome.';
			} elseif (empty($phone)) {
				$error = 'Preencha o campo Telefone.';
			} elseif (empty($email)) {
				$error = 'Preencha o campo E-mail.';
			} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$error = 'Preencha um E-mail válido.';
			} elseif (empty($subject)) {
				$error = 'Preencha o campo Assunto.';
			} elseif (empty($message)) {
				$error = 'Preencha o campo Mensagem.';
			} elseif (!ReCaptcha::isValid()) {
				$error = 'Marque a opção "Não sou um robô".';
			}

			/* Envia Email */
			if (is_null($error)) {
				$mail = new Email();
				$mail->setSubject('Contato efetuado pelo site ' . $this->app->getName());
				$mail->addAddress($this->sendTo);
				$mail->setFrom($this->sendFrom, $this->app->getName());
				$mail->addReplyTo($email, $name);

				$content = new Block('email/content/contact', $data);
				$mail->setContent($content);
				$error = $mail->send();

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
