<?php

namespace Win\Mailer;

use PHPMailer;
use Win\File\File;
use Win\Mvc\Block;
use Win\Request\Server;

/**
 * Envios de E-mails
 *
 * Responsável por enviar emails, simplificando a forma de envio
 */
class Email {

	/** @var Block */
	private $layout;

	/** @var Block|string */
	private $content;

	/** @var object Classe responsável pelo envio real */
	private $mailer;

	/**
	 * Cria uma mensagem de E-mail
	 */
	public function __construct() {
		$this->setLayout('main');

		spl_autoload_register('\Win\Mailer\Email::autoload');

		$this->mailer = new PHPMailer();
		$this->mailer->CharSet = 'utf-8';
		$this->mailer->SetLanguage('br');
		$this->mailer->IsMail();
		$this->mailer->IsHTML(true);
	}

	/**
	 * Inclui bibliotecas necessarias
	 * @param string $className
	 */
	public static function autoload($className) {
		$file = BASE_PATH . '/lib/vendor/phpmailer/class.' . strtolower($className) . '.php';
		if (file_exists($file)):
			return require $file;
		endif;
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
	 * Define o conteudo do E-mail
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

	/**
	 * Retorna o conteudo do E-mail
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
	 * Envia o email
	 *
	 * No localhost será mostrado o conteudo do E-mail
	 * @return null|string Retorna null ou string de erro
	 */
	public function send() {
		if (!Server::isLocalHost()) {
			$this->mailer->Body = $this->layout->toString();
			$send = $this->mailer->Send();
			$this->mailer->ClearAllRecipients();
			$this->mailer->ClearAttachments();
			if (!$send) {
				return 'Houve um erro ao enviar o e-mail.<br /><span style="display:none">' . $this->mailer->ErrorInfo . '</span>';
			}
			return null;
		} else {
			$this->saveOnDisk();
		}
		return null;
	}

	/**
	 * Salva o email em um arquivo
	 */
	private function saveOnDisk() {
		$file = new File();
		$file->setDirectory('data/email');

		$fileName = date('Y.m.d-H.i.s-') . strtolower(md5(uniqid(time()))) . '.html';
		$file->setName($fileName);
		$file->write($this->layout->toString());
	}

}
