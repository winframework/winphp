<?php

namespace controllers;

use Win\Mvc\Controller;
use Win\Response\JsonResponse;

class JsonApiController extends Controller
{
	public $template = 'json';

	public function index()
	{
		$data = [
			'totalResults' => '3',
			'values' => [
				['name' => 'John', 'age' => 30],
				['name' => 'Mary', 'age' => 24],
				['name' => 'Petter', 'age' => 18],
			],
		];

		return new JsonResponse($data);
	}

	public function exemploDeAction()
	{
		$data = [
			'totalResults' => '3',
			'values' => [
				['name' => 'John', 'age' => 30],
				['name' => 'Mary', 'age' => 24],
				['name' => 'Petter', 'age' => 18],
			],
		];

		return new JsonResponse($data);
	}
}
