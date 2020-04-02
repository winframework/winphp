<?php

namespace Win\Models;

use Win\Common\EmailTemplate;
use Win\Common\Template;

/**
 * Email
 */
class Email
{
	private $from = '';
	private $fromName = '';
	private $to = [];
	private $replyTo = [];
	private $cc = [];
	private $bcc = [];
	private $subject = '';
	private $language = 'br';
	private $body;

	/**
	 * Cria uma mensagem de E-mail
	 * @param string $subject
	 */
	public function __construct($subject = '')
	{
		$this->subject = $subject;
	}

	/**
	 * Define quem envia
	 * @param string $address
	 * @param string $name
	 */
	public function setFrom($address, $name = '')
	{
		$this->from = $address;
		$this->fromName = $name;

		return $this;
	}

	/**
	 * Define pra quem será respondido
	 * @param string $address
	 * @param string $name
	 */
	public function addReplyTo($address, $name = '')
	{
		$this->replyTo[$address] = $name;

		return $this;
	}

	/**
	 * Adiciona um Destinatário
	 * @param string $address
	 * @param string $name
	 */
	public function addTo($address, $name = '')
	{
		$this->to[$address] = $name;

		return $this;
	}

	/**
	 * Adiciona um BCC
	 * @param string $address
	 * @param string $name
	 */
	public function addBcc($address, $name = '')
	{
		$this->bcc[$address] = $name;

		return $this;
	}

	/**
	 * Adiciona um CC
	 * @param string $address
	 * @param string $name
	 */
	public function addCc($address, $name = '')
	{
		$this->cc[$address] = $name;

		return $this;
	}

	/**
	 * Define o Assunto
	 * @param string $subject
	 */
	public function setSubject($subject)
	{
		$this->subject = $subject;

		return $this;
	}

	/**
	 * Define o idioma
	 * @param string $language
	 */
	public function setLanguage($language)
	{
		$this->language = $language;

		return $this;
	}

	/**
	 * Define o corpo
	 * @param string $body
	 */
	public function setBody($body)
	{
		$this->body = $body;

		return $this;
	}

	/**
	 * Retorna quem envia
	 * @return string
	 */
	public function getFrom()
	{
		return $this->from;
	}

	/**
	 * Retorna o Nome de quem envia
	 * @return string
	 */
	public function getFromName()
	{
		return $this->fromName;
	}

	/**
	 * Retorna o destinatário
	 * @return string[]
	 */
	public function getTo()
	{
		return $this->to;
	}

	/**
	 * Retorna o remetente
	 * @return string[]
	 */
	public function getReplyTo()
	{
		return $this->replyTo ?? [$this->from];
	}

	/**
	 * Retorna o CC
	 * @return string[]
	 */
	public function getCc()
	{
		return $this->cc;
	}

	/**
	 * Retorna o Bcc
	 * @return string[]
	 */
	public function getBcc()
	{
		return $this->bcc;
	}

	/**
	 * Retorna o idioma
	 * @return string
	 */
	public function getLanguage()
	{
		return $this->language;
	}

	/**
	 * Retorna o Assunto
	 * @return string
	 */
	public function getSubject()
	{
		return $this->subject;
	}

	/**
	 * Retorna o corpo do E-mail
	 * @return string
	 */
	public function getBody()
	{
		return $this->body;
	}
}
