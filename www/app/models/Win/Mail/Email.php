<?php

namespace Win\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use Win\Filesystem\File;
use Win\Mvc\Block;
use Win\Request\Server;

/**
 * Envio de Emails
 *
 * Responsável por enviar Emails
 */
class Email {

	/** @var Block */
	private $layout;

	/** @var Block|string */
	private $content;

	/** @var object Classe responsável pelo envio real */
	private $mailer;
	public static $sendOnLocalHost = false;

	/** @var string|null */
	private $error = null;

	/**
	 * Cria uma mensagem de E-mail
	 */
	public function __construct() {
		$this->setLayout('main');

		$this->mailer = new PHPMailer();
		$this->mailer->CharSet = 'utf-8';
		$this->mailer->SetLanguage('br');
		$this->mailer->IsMail();
		$this->mailer->IsHTML(true);
	}

	/** @return string */
	public function __toString() {
		return $this->layout->toString();
	}

	/**
	 * Adiciona um Destinatário
	 * @param string $address E-mail destinatário
	 * @param string $name Nome destinatário
	 */
	public function addAddress($address, $name = '') {
		$this->mailer->AddAddress($address, $name);
	}

	/**
	 * Define pra quem será respondido
	 * @param string $address
	 * @param string $name
	 */
	public function addReplyTo($address, $name = '') {
		$this->mailer->AddReplyTo($address, $name);
	}

	/**
	 * Define o Remetente
	 * @param string $address E-mail remetente
	 * @param string $name Nome remetente
	 */
	public function setFrom($address, $name = '') {
		$this->mailer->SetFrom($address, $name);
		$this->mailer->ClearReplyTos();
	}

	/**
	 * Define qual será o arquivo de layout
	 *
	 * @param string $layout Nome do arquivo de layout
	 */
	public function setLayout($layout) {
		$file = 'email/' . $layout;
		$this->layout = new Block($file, ['email' => $this]);
	}

	/**
	 * Define o conteúdo do E-mail
	 * que pode ser uma string ou um bloco
	 * @param string|Block $content
	 */
	public function setContent($content) {
		$this->content = $content;
	}

	/**
	 * Define o Assunto
	 * @param string $subject
	 */
	public function setSubject($subject) {
		$this->mailer->Subject = $subject;
	}

	/**
	 * Define o idioma
	 * @param string $lang
	 */
	public function setLanguage($lang) {
		$this->mailer->SetLanguage($lang);
	}

	/**
	 * Retorna o E-mail do Destinatário
	 * @return string
	 */
	public function getFrom() {
		return $this->mailer->From;
	}

	/**
	 * Retorna o Nome do destinatário
	 * @return string
	 */
	public function getFromName() {
		return $this->mailer->FromName;
	}

	/** @return string[] */
	public function getAddresses() {
		return $this->mailer->getAllRecipientAddresses();
	}

	/** @return string[] */
	public function getReplyToAddresses() {
		return $this->mailer->getReplyToAddresses();
	}

	/**
	 * Retorna o conteúdo do E-mail
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * Retorna o Assunto
	 * @return string
	 */
	public function getSubject() {
		return $this->mailer->Subject;
	}

	/**
	 * Retorna o erro caso 'send()' tenha retornado FALSE
	 * @return string|null
	 */
	public function getError() {
		return $this->error;
	}

	/**
	 * Envia o E-mail
	 *
	 * @return boolean
	 */
	public function send() {
		$send = false;
		if (!Server::isLocalHost() || static::$sendOnLocalHost) {
			$this->mailer->Body = $this->layout->toString();
			$send = $this->mailer->Send();
			$this->mailer->ClearAllRecipients();
			$this->mailer->ClearAttachments();
			if (!$send) {
				$this->error = 'Houve um erro ao enviar o e-mail.<br /><span style="display:none">' . $this->mailer->ErrorInfo . '</span>';
			}
		} else {
			$send = $this->saveOnDisk();
		}
		return $send;
	}

	/**
	 * Salva o E-mail em um arquivo
	 * @return boolean
	 */
	private function saveOnDisk() {
		$fileName = date('Y.m.d-H.i.s-') . strtolower(md5(uniqid(time()))) . '.html';
		$file = new File('data/emails/' . $fileName);
		$file->getDirectory()->create(0777);
		return $file->write($this->layout->toString());
	}

}