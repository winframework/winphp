<?php

namespace Win\Common;

use Win\Services\Mailer;

/**
 * Templates de Email
 * Ver arquivos em: "templates/emails"
 */
class Email extends Template
{
	public static $dir = 'templates/emails';
	public Mailer $mailer;

	/**
	 * Cria um Template de E-mail
	 * @param string $file
	 * @param array $data
	 * @param string $layout
	 */
	public function __construct($file, $data = [], $layout = 'emails/layout')
	{
		parent::__construct($file, $data, $layout);
	}
}
