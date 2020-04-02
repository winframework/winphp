<?php

namespace Win\Models;

use Win\Common\EmailTemplate;

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
	private $data = [];
	private $layout = '';
	private $template = '';

	/**
	 * Cria uma mensagem de E-mail
	 * @param string $template
	 * @param mixed[] $data
	 * @param string $layout
	 */
	public function __construct($template = null, $data = [], $layout = 'default')
	{
		$this->template = $template;
		$this->data = $data;
		$this->layout = $layout;
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
		if (!$this->body) {
			$this->body = new EmailTemplate($this->template, $this->data, $this->layout, $this);
		}

		return (string) $this->body;
	}
}
