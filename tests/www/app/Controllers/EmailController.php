<?php

namespace App\Controllers;

use Win\Common\Template;
use Win\Controllers\Controller;
use Win\InfraServices\Mailer;
use Win\Models\Email;
use Win\Repositories\Alert;
use Win\Views\View;

class EmailController extends Controller
{
	public function index()
	{
		$mailer = new Mailer();

		$email = new Email();
		$email->setContent('Meu conteúdo');
		$mailer->send($email);

		$email = new Email();
		$email->setContent(new Template('emails/contact'));
		$mailer->send($email);

		Alert::success('E-mail enviado!');

		return new View('basic/alerts');
	}
}
