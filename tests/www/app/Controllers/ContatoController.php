<?php

namespace App\Controllers;

use Exception;
use Win\Common\Email;
use Win\Controllers\Controller;
use Win\Services\Mailer;
use Win\Services\ReCaptcha;
use Win\Services\Alert;
use Win\Request\Input;
use Win\Views\View;

/**
 * Envia um formulário de contato via E-mail
 */
class ContatoController extends Controller
{
	const SEND_TO = 'destinatario@example.com';
	const SEND_FROM = 'no-reply@example.com';

	public string $name;
	public string $phone;
	public string $email;
	public string $subject;
	public string $message;

	/**
	 * Inicia variáveis
	 */
	public function init()
	{
		$this->title = 'Contato | ' . APP_NAME;

		$this->name = trim(Input::post('name'));
		$this->phone = trim(Input::post('phone'));
		$this->email = trim(Input::post('email'));
		$this->subject = trim(Input::post('subject'));
		$this->message = trim(Input::post('message'));
	}

	/**
	 * Exibe/Envia formulário
	 */
	public function index()
	{
		try {
			if (Input::post('submit')) {
				$this->validate();
				
				$mailer = new Mailer();
				$mailer->setSubject('Contato efetuado pelo site ' . APP_NAME);
				$mailer->addTo(static::SEND_TO);
				$mailer->setFrom(static::SEND_FROM, APP_NAME);
				$mailer->addReplyTo($this->email, $this->name);
				$email = new Email('contact', get_object_vars($this));
				$mailer->send($email);

				$this->name = '';
				$this->phone = '';
				$this->email = '';
				$this->subject = '';
				$this->message = '';

				Alert::success('Sua mensagem foi enviada com sucesso!');
			} else {
				Alert::info('Preencha os campos abaixo:');
			}
		} catch (Exception $e) {
			Alert::error($e->getMessage());
		}

		return new View('contato/index');
	}

	/**
	 * Valida os campos
	 */
	private function validate()
	{
		if (empty($this->name)) {
			throw new Exception('O campo Nome é obrigatório.');
		}
		if (empty($this->phone)) {
			throw new Exception('O campo Telefone é obrigatório.');
		}
		if (empty($this->email)) {
			throw new Exception('O campo E-mail é obrigatório.');
		}
		if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
			throw new Exception('O campo E-mail precisa ser um e-mail válido.');
		}
		if (!ReCaptcha::isValid()) {
			throw new Exception('Preencha o campo eu não sou um robô.');
		}
	}
}
