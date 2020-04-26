<?php

namespace Win\InfraServices;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Win\Common\Email;
use Win\Common\Server;
use Win\Common\Template;
use Win\Repositories\Filesystem;

/**
 * Envio de Emails
 *
 * Responsável por enviar Emails
 */
class Mailer
{
	const DIRECTORY = 'data/emails';

	/** @var PHPMailer */
	private $mailer;

	/** @var bool */
	public static $sendOnLocalHost = false;

	/**
	 * Instancia o serviço de E-mail
	 */
	public function __construct()
	{
		$this->mailer = new PHPMailer();
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
	}

	/**
	 * Define o assunto
	 * @param string $subject
	 */
	public function setSubject($subject)
	{
		$this->mailer->Subject = $subject;
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
	}

	/**
	 * Add destinatário
	 * @param string $address
	 * @param string $name
	 */
	public function addTo($address, $name = '')
	{
		$this->mailer->addAddress($address, $name);
	}

	/**
	 * Add cópia
	 * @param string $address
	 * @param string $name
	 */
	public function addCC($address, $name = '')
	{
		$this->mailer->addCC($address, $name);
	}

	/**
	 * Add cópia oculta
	 * @param string $address
	 * @param string $name
	 */
	public function addBCC($address, $name = '')
	{
		$this->mailer->addBCC($address, $name);
	}

	/**
	 * Add responder para
	 * @param string $address
	 * @param string $name
	 */
	public function addReplyTo($address, $name = '')
	{
		$this->mailer->addReplyTo($address, $name);
	}

	/**
	 * Envia o E-mail
	 * @param string|Email $body
	 */
	public function send($body)
	{
		$this->setBody($body);

		if (!Server::isLocalHost() || static::$sendOnLocalHost) {
			$send = $this->mailer->Send();
			$this->flush();

			if (!$send) {
				throw new Exception('Houve um erro ao enviar o e-mail.<br />'
					. '<span style="display:none">' . $this->mailer->ErrorInfo . '</span>');
			}
		} else {
			$this->saveOnDisk();
		}
	}

	/**
	 * Define o corpo do email
	 * @param string|Email $body
	 */
	protected function setBody($body)
	{
		if ($body instanceof Template) {
			$this->mailer->Body = $body->toHtml();
		} else {
			$this->mailer->Body = $body;
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
		$fs = new Filesystem();
		$name = date('Y.m.d-H.i.s-') . strtolower(md5(uniqid(time()))) . '.html';
		$body = $this->mailer->Body;

		return $fs->write(static::DIRECTORY . '/' . $name, $body);
	}
}
