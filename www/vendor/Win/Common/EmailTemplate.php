<?php

namespace Win\Common;

use Win\Models\Email;

/**
 * Templates de Email
 * Ver arquivos em: "/templates/emails"
 */
class EmailTemplate extends Template
{
	public static $dir = '/templates/emails';
	const LAYOUT_PREFIX = 'email';

	/** @var Email */
	public $email;

	/**
	 * Cria um Template de E-mail
	 * @param Email $email
	 * @param string $file
	 * @param array $data
	 * @param string $layout
	 */
	public function __construct(Email $email, $file, $data = [], $layout = 'default')
	{
		$this->email = $email;
		parent::__construct($file, $data, $layout);
	}
}
