<?php

namespace App\Controllers;

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

		$data = ['name' => 'John', 'subject' => 'A', 'phone' => '0000-000', 'message' => 'My Message', 'email' => 'john@email.com'];

		$email = new Email('contact', $data);
		$email->setFrom('no-reply@teste.com');
		$mailer->send($email);

		Alert::success('E-mail enviado!');

		return new View('basic/alerts');
	}
}
