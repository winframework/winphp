<?php

namespace Win\Mailer;

use Win\Mvc\Block;
use Win\Mvc\Application;

/**
 * Envios de E-mails
 *
 * Responsável por enviar emails, simplificando a forma de envio
 */
class Email {

	/** @var Block */
	private $layout;

	/** @var Block|string */
	private $body;

	/** @var object Classe responsável pelo envio real */
	private $mailer;

	/**
	 * Cria uma mensagem de E-mail
	 */
	public function __construct() {
		$this->setLayout('main');

		require_once 'lib/vendor/phpmailer/class.phpmailer.php';
		$this->mailer = new \PHPMailer();
		$this->mailer->CharSet = 'utf-8';
		$this->mailer->SetLanguage('br');
		$this->mailer->IsMail();
		$this->mailer->IsHTML(true);
	}

	/* Metodos de Acesso */

	/**
	 * Adiciona um Destinatário
	 * @param string $address E-mail destinatário
	 * @param string $name Nome destinatário
	 */
	public function addAddress($address, $name = '') {
		$this->mailer->AddAddress($address, $name);
	}

	/**
	 * Define o Remetente
	 * @param string $address E-mail remetente
	 * @param string $name Nome remetente
	 */
	public function setFrom($address, $name = '') {
		$this->mailer->SetFrom($address, $name);
	}

	/**
	 * Define qual será o arquivo de layout
	 *
	 * Arquivos de layout ficam em app/blocks/email
	 * @param string $layout Nome do arquivo de layout
	 */
	public function setLayout($layout) {
		$file = 'email/' . $layout;
		$this->layout = new Block($file, ['email' => $this]);
	}

	/**
	 * Define o conteudo do E-mail
	 * que pode ser uma string ou um bloco
	 * @param string|Block $body
	 */
	public function setBody($body) {
		$this->body = $body;
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
	 * @param string @lang
	 */
	public function setLanguage($lang) {
		$this->mailer->SetLanguage($lang);
	}

	/**
	 * Retorna o Destinatário
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

	/**
	 * Retorna o conteudo do E-mail
	 * @return string
	 */
	public function getBody() {
		return $this->body;
	}

	/**
	 * Retorna o Assunto
	 * @return string
	 */
	public function getSubject() {
		return $this->mailer->Subject;
	}

	/**
	 * Envia o email
	 *
	 * No localhost será mostrado o conteudo do E-mail
	 * @return null|string Retorna null ou string de erro
	 */
	public function send() {
		if (!Application::app()->isLocalHost()) {
			$this->mailer->Body = $this->layout->toString();
			$send = $this->mailer->Send();
			$this->mailer->ClearAllRecipients();
			$this->mailer->ClearAttachments();
			if (!$send) {
				return 'Houve um erro ao enviar o e-mail.<br /><span style="display:none">' . $this->mailer->ErrorInfo . '</span>';
			}
			return null;
		}
		$this->layout->toHtml();
		return null;
	}

}
