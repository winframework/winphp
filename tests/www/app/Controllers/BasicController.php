<?php

namespace App\Controllers;

use App\Templates\ClassView;
use Win\Controllers\Controller;
use Win\Services\Alert;
use Win\Services\Router;
use Win\Templates\View;

/**
 * Usado pelo PHPUnit
 */
class BasicController extends Controller
{
	public int $ten;
	private Router $router;

	public function __construct(Router $router)
	{
		$this->router = $router;
	}

	public function init()
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
	 * Exibe view com layout alternativo
	 */
	public function alternativeLayout()
	{
		$this->layout = 'layout-alternative';

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

		return $data;
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
	 * Erro
	 */
	protected function erroView()
	{
		echo 'Esta mensagem não deve aparecer';
		return new View('basic/erro-view');
	}

	/**
	 * Redireciona
	 */
	public function redirecting()
	{
		Alert::success('Você foi redirecionado.');
		$this->router->redirect('alerts/show');

		Alert::error('Este não pode aparecer.');

		return new View('index');
	}

	/**
	 * Cria alerts
	 */
	public function createAlerts()
	{
		Alert::error('Ops! Um erro.');
		Alert::error('Outro erro.');
		Alert::success('Parabéns.');

		$this->router->redirect('alerts/show');
	}

	/**
	 * Exibe os alerts na sessão e limpa ela
	 */
	public function showAlerts()
	{
		return new View('basic/alerts');
	}
}
