<?php

namespace controllers;

use Exception;
use Win\FlashMessage\Alert;
use Win\Html\Form\ReCaptcha;
use Win\Mail\Email;
use Win\Mvc\Controller;
use Win\Mvc\View;
use Win\Request\Input;

/**
 * Envia um formulário de contato via E-mail
 */
class ContatoController extends Controller
{
	const SEND_TO = 'destinatario@example.com';
	const SEND_FROM = 'no-reply@example.com';

	public $name;
	public $phone;
	public $email;
	public $subject;
	public $message;

	/**
	 * Inicia variáveis
	 */
	public function __construct()
	{
		$this->name = trim(Input::post('name'));
		$this->phone = trim(Input::post('phone'));
		$this->email = trim(Input::post('email'));
		$this->subject = trim(Input::post('subject'));
		$this->message = trim(Input::post('message'));
	}

	/**
	 * Exibe formulário
	 */
	public function index()
	{
		$this->title = 'Contato | ' . APP_NAME;

		if (!Alert::instance()->has()) {
			Alert::info('Preencha os campos abaixo:');
		}

		return new View('contato');
	}

	/**
	 * Envia o email
	 */
	public function send()
	{
		try {
			$this->validate();

			$mail = new Email('main', 'html/contact', get_object_vars($this));
			$mail->setSubject('Contato efetuado pelo site ' . APP_NAME);
			$mail->addTo(static::SEND_TO);
			$mail->setFrom(static::SEND_FROM, APP_NAME);
			$mail->addReplyTo($this->email, $this->name);
			$mail->send();

			Alert::success('Sua mensagem foi enviada com sucesso!');
			$this->backToIndex();
		} catch (Exception $e) {
			Alert::error($e->getMessage());

			return $this->index();
		}
	}

	/**
	 * Valida os campos
	 */
	protected function validate()
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
