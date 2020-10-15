<?php

namespace App\Controllers;

use Win\Controllers\Controller;
use Win\Services\Mailer;
use Win\Services\Alert;
use Win\Templates\Email;
use Win\Templates\View;

class EmailController extends Controller
{
	public function index()
	{
		$mailer = Mailer::instance();

		$data = ['name' => 'John', 'subject' => 'A', 'phone' => '0000-000', 'message' => 'My Message', 'email' => 'john@email.com'];

		$mailer->send(new Email('contact', $data));

		Alert::success('E-mail enviado!');

		return new View('basic/alerts');
	}
}
