<?php

namespace Win\Common;

use Win\InfraServices\Mailer;

/**
 * Templates de Email
 * Ver arquivos em: "/app/templates/emails"
 */
class Email extends Template
{
	public static $dir = '/app/templates/emails';

	/** @var Mailer */
	public $mailer;

	/**
	 * Cria um Template de E-mail
	 * @param string $file
	 * @param array $data
	 * @param string $layout
	 */
	public function __construct($file, $data = [], $layout = 'default')
	{
		parent::__construct($file, $data, $layout);
	}
}
