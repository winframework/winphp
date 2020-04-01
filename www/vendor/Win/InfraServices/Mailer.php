<?php

namespace Win\InfraServices;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Win\Common\Server;
use Win\Models\Email;
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
	 * Envia o E-mail
	 */
	public function send(Email $email)
	{
		$this->prepareHeader($email);
		$this->prepareBody($email);

		if (!Server::isLocalHost() || static::$sendOnLocalHost) {
			$send = $this->mailer->Send();
			$this->flush();

			if (!$send) {
				throw new Exception('Houve um erro ao enviar o e-mail.<br />'
				. '<span style="display:none">' . $this->mailer->ErrorInfo . '</span>');
			}
		} else {
			$this->saveOnDisk($email);
		}
	}

	/**
	 * Prepara os dados do email
	 * @param Email $email
	 */
	private function prepareHeader(Email $email)
	{
		// Details
		$phpMailer = $this->mailer;
		$phpMailer->SetLanguage($email->getLanguage());
		$phpMailer->Subject = $email->getSubject();

		// From
		$phpMailer->From = $email->getFrom();
		$phpMailer->FromName = $email->getFromName();

		// Addresses
		foreach ($email->getTo() as $address => $name) {
			$phpMailer->addAddress($address, $name);
		}
		foreach ($email->getCc() as $address => $name) {
			$phpMailer->addCC($address, $name);
		}
		foreach ($email->getBcc() as $address => $name) {
			$phpMailer->addBCC($address, $name);
		}
		foreach ($email->getReplyTo() as $address => $name) {
			$phpMailer->addReplyTo($address, $name);
		}
	}

	/**
	 * Prepara o corpo do email
	 * @param Email $email
	 */
	private function prepareBody(Email $email)
	{
		$this->mailer->Body = $email->getBody();
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
	 * Salva o E-mail em um arquivo
	 * @param Email $email
	 * @return bool
	 */
	private function saveOnDisk(Email $email)
	{
		$fs = new Filesystem();
		$name = date('Y.m.d-H.i.s-') . strtolower(md5(uniqid(time()))) . '.html';

		return $fs->write(static::DIRECTORY . '/' . $name, $email->getBody());
	}
}
