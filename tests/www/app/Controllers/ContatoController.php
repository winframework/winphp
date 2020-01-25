<?php

namespace App\Controllers;

use Exception;
use Win\Controllers\Controller;
use Win\InfraServices\Mailer;
use Win\InfraServices\ReCaptcha;
use Win\Models\Email;
use Win\Repositories\Alert;
use Win\Request\Input;
use Win\Views\View;

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

		if (Alert::instance()->isEmpty()) {
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
			$mailer = new Mailer();

			$email = new Email('contact', get_object_vars($this));
			$email->setSubject('Contato efetuado pelo site ' . APP_NAME);
			$email->addTo(static::SEND_TO);
			$email->setFrom(static::SEND_FROM, APP_NAME);
			$email->addReplyTo($this->email, $this->name);
			$mailer->send($email);

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
