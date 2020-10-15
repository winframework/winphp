<?php

namespace Win\Services;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Win\Templates\Email;
use Win\Common\Server;
use Win\Common\Traits\InjectableTrait;
use Win\Services\Filesystem;

/**
 * Envio de Emails
 *
 * Responsável por enviar Emails
 */
class Mailer
{
	use InjectableTrait;
	const DIRECTORY = 'data/emails';

	/** @var PHPMailer */
	protected $mailer;

	protected Filesystem $fs;

	/** @var bool */
	public static $sendOnLocalHost = false;

	/**
	 * Instancia o serviço de E-mail
	 */
	public function __construct(Filesystem $fs, PHPMailer $mailer)
	{
		$this->fs = $fs;
		$this->mailer = $mailer;
		$this->mailer->CharSet = 'utf-8';
		$this->mailer->IsMail();
		$this->mailer->IsHTML(true);
	}

	/**
	 * Define o idioma
	 * @param string $language
	 */
	public function setLanguage($language)
	{
		$this->mailer->SetLanguage($language);
		return $this;
	}

	/**
	 * Define o assunto
	 * @param string $subject
	 */
	public function setSubject($subject)
	{
		$this->mailer->Subject = $subject;
		return $this;
	}

	/**
	 * Retorna o assunto
	 */
	public function getSubject()
	{
		return $this->mailer->Subject;
	}

	/**
	 * Define o remetente
	 * @param string $address
	 * @param string $name
	 */
	public function setFrom($address, $name = '')
	{
		$this->mailer->From = $address;
		$this->mailer->FromName = $name;
		return $this;
	}

	/**
	 * Add destinatário
	 * @param string $address
	 * @param string $name
	 */
	public function addTo($address, $name = '')
	{
		$this->mailer->addAddress($address, $name);
		return $this;
	}

	/**
	 * Add cópia
	 * @param string $address
	 * @param string $name
	 */
	public function addCC($address, $name = '')
	{
		$this->mailer->addCC($address, $name);
		return $this;
	}

	/**
	 * Add cópia oculta
	 * @param string $address
	 * @param string $name
	 */
	public function addBCC($address, $name = '')
	{
		$this->mailer->addBCC($address, $name);
		return $this;
	}

	/**
	 * Add responder para
	 * @param string $address
	 * @param string $name
	 */
	public function addReplyTo($address, $name = '')
	{
		$this->mailer->addReplyTo($address, $name);
		return $this;
	}

	/**
	 * Envia o E-mail
	 * @param string|Email $body
	 * @param string $layout
	 */
	public function send($body, $layout = 'layout')
	{
		if ($body instanceof Email) {
			$body->mailer = $this;
		}
		if ($layout) {
			$layout = new Email($layout, ['content' => $body]);
			$layout->mailer = $this;
			$body = $layout;
		}
		$this->mailer->Body = (string) $body;

		if (!Server::isLocalHost() || static::$sendOnLocalHost) {
			$send = $this->mailer->Send();
			$this->flush();

			if (!$send) {
				throw new Exception('Houve um erro ao enviar o e-mail.');
			}
		} else {
			$this->flush();
			$this->saveOnDisk();
		}
	}

	/**
	 * Limpa dados
	 */
	private function flush()
	{
		$this->mailer->ClearAllRecipients();
		$this->mailer->ClearAttachments();
	}

	/**
	 * Salva o corpo do E-mail em um arquivo
	 * @return bool
	 */
	private function saveOnDisk()
	{
		$name = date('Y.m.d-H.i.s-') . strtolower(md5(uniqid(time()))) . '.html';
		$body = $this->mailer->Body;

		return $this->fs->write(static::DIRECTORY . "/$name", $body);
	}
}
