<?php

namespace Win\Templates;

use Win\Services\Mailer;

/**
 * Templates de Email
 * Ver arquivos em: "templates/emails"
 */
class Email extends Template
{
	public static $dir = 'templates/emails';
	public Mailer $mailer;
}
