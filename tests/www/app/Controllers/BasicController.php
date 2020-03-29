<?php

namespace App\Controllers;

use App\Views\ClassView;
use Win\Controllers\Controller;
use Win\Repositories\Alert;
use Win\Response\JsonResponse;
use Win\Views\View;

/**
 * Usado pelo PHPUnit
 */
class BasicController extends Controller
{
	public function __construct()
	{
		$this->ten = 10;
	}

	/**
	 * Exibe view com titulo personalizado
	 */
	public function index()
	{
		$this->title = 'My Index Action';

		return new View('basic/index');
	}

	/**
	 * Exibe View com variáveis criados na View
	 */
	public function data()
	{
		return new View('basic/data');
	}

	/**
	 * Exibe view de Classe
	 */
	public function classView()
	{
		return new ClassView(10);
	}

	/**
	 * View Invalida
	 */
	public function notFound1()
	{
		return new View('this-file-not-exist');
	}

	/**
	 * View Invalida
	 */
	public function notFound2()
	{
		return new View('contato/invalid');
	}

	/**
	 * Retorna variável, sem template
	 */
	public function returnFive()
	{
		return 5;
	}

	/**
	 * Retorna em JSON, sem template
	 */
	public function json()
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

	/**
	 * Método privado
	 */
	protected function methodPrivate()
	{
		echo 'Este método não deve estar disponível';

		return new View('basic/index');
	}

	/**
	 * Redireciona
	 */
	public function redirecting()
	{
		Alert::success('Você será redirecionado');
		$this->redirect('alerts/show');

		Alert::error('Este não pode aparecer');

		return new View('index');
	}

	/**
	 * Cria alerts
	 */
	public function createAlerts()
	{
		Alert::error('Ops! Um erro');
		Alert::error('Outro erro');
		Alert::success('Parabéns');

		$this->redirect('alerts/show');
	}

	/**
	 * Exibe os alerts na sessão e limpa ela
	 */
	public function showAlerts()
	{
		return new View('basic/alerts');
	}
}