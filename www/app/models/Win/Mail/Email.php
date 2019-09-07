<?php

namespace Win\Mail;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Win\Filesystem\File;
use Win\Mvc\Block;
use Win\Request\Server;

/**
 * Envio de Emails
 *
 * Responsável por enviar Emails
 */
class Email
{
	/** @var Block */
	private $layout;

	/** @var Block|string */
	private $content;

	/** @var PHPMailer */
	private $mailer;

	/** @var boolean */
	public static $sendOnLocalHost = false;

	/**
	 * Cria uma mensagem de E-mail
	 * @param string $layout
	 */
	public function __construct($layout = 'main')
	{
		$this->setLayout($layout);

		$this->mailer = new PHPMailer();
		$this->mailer->CharSet = 'utf-8';
		$this->mailer->SetLanguage('br');
		$this->mailer->IsMail();
		$this->mailer->IsHTML(true);
	}

	/** @return string */
	public function __toString()
	{
		return $this->layout->toString();
	}

	/**
	 * Adiciona um Destinatário
	 * @param string $address E-mail destinatário
	 * @param string $name Nome destinatário
	 */
	public function addTo($address, $name = '')
	{
		$this->mailer->AddAddress($address, $name);
	}

	/**
	 * Define pra quem será respondido
	 * @param string $address
	 * @param string $name
	 */
	public function addReplyTo($address, $name = '')
	{
		$this->mailer->AddReplyTo($address, $name);
	}

	/**
	 * Define o Remetente
	 * @param string $address E-mail remetente
	 * @param string $name Nome remetente
	 */
	public function setFrom($address, $name = '')
	{
		$this->mailer->SetFrom($address, $name);
		$this->mailer->ClearReplyTos();
	}

	/**
	 * Define qual será o arquivo de layout
	 *
	 * @param string $layout Nome do arquivo de layout
	 */
	public function setLayout($layout)
	{
		$this->layout = new EmailLayout($layout, ['email' => $this]);
	}

	/**
	 * Define o conteúdo do E-mail
	 * que pode ser uma string ou um bloco
	 * @param string|Block $content
	 */
	public function setContent($content)
	{
		$this->content = $content;
	}

	/**
	 * Define o Assunto
	 * @param string $subject
	 */
	public function setSubject($subject)
	{
		$this->mailer->Subject = $subject;
	}

	/**
	 * Define o idioma
	 * @param string $lang
	 */
	public function setLanguage($lang)
	{
		$this->mailer->SetLanguage($lang);
	}

	/**
	 * Retorna o E-mail do Destinatário
	 * @return string
	 */
	public function getFrom()
	{
		return $this->mailer->From;
	}

	/**
	 * Retorna o Nome do destinatário
	 * @return string
	 */
	public function getFromName()
	{
		return $this->mailer->FromName;
	}

	/** @return string[] */
	public function getTo()
	{
		return $this->mailer->getAllRecipientAddresses();
	}

	/** @return string[] */
	public function getReplyTo()
	{
		return $this->mailer->getReplyToAddresses();
	}

	/**
	 * Retorna o conteúdo do E-mail
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * Retorna o Assunto
	 * @return string
	 */
	public function getSubject()
	{
		return $this->mailer->Subject;
	}

	/**
	 * Envia o E-mail
	 */
	public function send()
	{
		if (!Server::isLocalHost() || static::$sendOnLocalHost) {
			$this->mailer->Body = $this->layout->toString();
			$send = $this->mailer->Send();
			$this->mailer->ClearAllRecipients();
			$this->mailer->ClearAttachments();

			if (!$send) {
				throw new Exception('Houve um erro ao enviar o e-mail.<br />'
				. '<span style="display:none">' . $this->mailer->ErrorInfo . '</span>');
			}
		} else {
			$this->saveOnDisk();
		}
	}

	/**
	 * Salva o E-mail em um arquivo
	 * @return bool
	 */
	private function saveOnDisk()
	{
		$name = date('Y.m.d-H.i.s-') . strtolower(md5(uniqid(time()))) . '.html';
		$file = new File('data/emails/' . $name);
		$file->getDirectory()->create(0777);

		return $file->write($this->layout->toString());
	}
}