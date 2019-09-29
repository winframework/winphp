<?php

namespace Win\Email\Models;

use Win\Core\Common\Template;

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

	/** @var EmailTemplate|string */
	private $template;

	/** @var EmailTemplate|string */
	private $content;

	/**
	 * Cria uma mensagem de E-mail
	 * @param string $template
	 * @param string $content
	 * @param mixed[] $data
	 */
	public function __construct($template = 'main', $content = null, $data = [])
	{
		$this->template = new Template('email.' . $template, ['email' => $this]);
		$this->content = new Template('emails/' . $content, $data);
	}

	/** @return string */
	public function __toString()
	{
		return (string) $this->template ?: $this->content;
	}

	/**
	 * Define quem envia
	 * @param string $address
	 * @param string $name
	 */
	public function setFrom($address, $name = '')
	{
		$this->from = [$address => $name];

		return $this;
	}

	/**
	 * Define pra quem será respondido
	 * @param string $address
	 * @param string $name
	 */
	public function addReplyTo($address, $name = '')
	{
		$this->replyTo[$name] = $address;

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
		$this->bcc[$name] = $address;

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
	 * Define o conteúdo
	 * @param string $content
	 */
	public function setContent($content)
	{
		$this->content = $content;

		return $this;
	}

	/**
	 * Retorna o E-mail de quem envia
	 * @return string[]
	 */
	public function getFrom()
	{
		return $this->from;
	}

	/**
	 * Retorna o Nome de quem envia
	 * @return string[]
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
		return $this->replyTo ?? $this->from;
	}

	/**
	 * Retorna o CC
	 * @return string[]
	 */
	public function getCC()
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
	 * @return string[]
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
	 * Retorna o conteúdo do E-mail
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}
}
