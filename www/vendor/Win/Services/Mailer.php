<?php

namespace Win\Services;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Win\Common\Server;
use Win\Models\Email;
use Win\Filesystem\Models\File;

/**
 * Envio de Emails
 *
 * Responsável por enviar Emails
 */
class Mailer
{
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
		if (!Server::isLocalHost() || static::$sendOnLocalHost) {
			$this->prepare($email);
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
	 * Prepara o email
	 * @param Email $email
	 */
	private function prepare(Email $email)
	{
		// Details
		$phpMailer = $this->mailer;
		$phpMailer->SetLanguage($email->getLanguage());
		$phpMailer->Subject = $email->getSubject();
		$phpMailer->Body = $email->__toString();

		// From
		$phpMailer->From = $email->getFrom();
		$phpMailer->FromName = $email->getFromName();

		// Addresses
		foreach ($email->getTo() as $address => $name) {
			$phpMailer->addAddress($address, $name);
		}
		foreach ($email->getCC() as $address => $name) {
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
	 * Limpa dados
	 */
	private function flush()
	{
		$this->mailer->ClearAllRecipients();
		$this->mailer->ClearAttachments();
	}

	/**
	 * Salva o E-mail em um arquivo
	 * @return bool
	 */
	private function saveOnDisk()
	{
		// $name = date('Y.m.d-H.i.s-') . strtolower(md5(uniqid(time()))) . '.html';
		// $file = new File('data/emails/' . $name);
		// $file->getDirectory()->create(0777);

		// return $file->write($this->__toString());
	}
}
