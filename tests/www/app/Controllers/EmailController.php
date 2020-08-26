<?php

namespace App\Controllers;

use Win\Templates\Email;
use Win\Controllers\Controller;
use Win\Services\Mailer;
use Win\Services\Alert;
use Win\Templates\View;

class EmailController extends Controller
{
	public function index()
	{
		$mailer = new Mailer();

		$data = ['name' => 'John', 'subject' => 'A', 'phone' => '0000-000', 'message' => 'My Message', 'email' => 'john@email.com'];

		$email = new Email('contact', $data);
		$mailer->send($email);

		Alert::success('E-mail enviado!');

		return new View('basic/alerts');
	}
}
