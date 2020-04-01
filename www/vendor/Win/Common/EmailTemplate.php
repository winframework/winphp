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

	public function __construct($file, $data, $layout, Email $email)
	{
		$this->email = $email;
		parent::__construct($file, $data, $layout);
	}
}
