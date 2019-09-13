<?php

namespace controllers;

use Win\Mvc\Controller;
use Win\Mvc\JsonView;

class JsonApiController extends Controller
{
	public $template = 'json';

	public function index()
	{
		$this->addData('success', true);
		$this->addData('array', [01, 02, 03]);

		$data = [
			'totalResults' => '3',
			'values' => [
				['name' => 'John', 'age' => 30],
				['name' => 'Mary', 'age' => 24],
				['name' => 'Petter', 'age' => 18],
			],
		];

		return new JsonView($data);
	}

	public function exemploDeAction()
	{
		$this->addData('success', true);
		$this->addData('array', [01, 02, 03]);

		$data = [
			'totalResults' => '3',
			'values' => [
				['name' => 'John', 'age' => 30],
				['name' => 'Mary', 'age' => 24],
				['name' => 'Petter', 'age' => 18],
			],
		];

		return new JsonView($data);
	}
}
