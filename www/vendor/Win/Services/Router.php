<?php

namespace Win\Services;

use Win\Common\Traits\InjectableTrait;
use Win\Common\Utils\Input;
use Win\HttpException;

/**
 * Rota de URL
 *
 * Define o Controller@action a ser executado baseado na URL
 * @see "config/routes.php"
 */
class Router
{
	use InjectableTrait;
	const HOME = ['index', 'index'];
	const SUFFIX = '/';

	/** @var string HTTPS/HTTP */
	public $protocol;

	/** @var string URL Base */
	public $baseUrl;

	/** @var string URL Relativa */
	public $relativeUrl;

	/** @var string URL Completa/Absoluta */
	public $url;

	/** @var string[] fragmentos da URL */
	public $segments;
	public $page;
	public $action;

	/** @var string[] */
	protected $routes = [];

	public function __construct()
	{
		$this->protocol = Input::protocol();
		$this->baseUrl = $this->getBaseUrl();
		$this->relativeUrl = $this->getRelativeUrl();
		$this->segments = $this->getSegments();
		$this->url = $this->baseUrl . $this->relativeUrl;
	}

	/**
	 * Adiciona as rotas
	 * @param string $namespace
	 * @param string[] $routes
	 */
	public function add($namespace, $routes)
	{
		foreach ($routes as $request => $destination) {
			$this->routes[$request] = $namespace . $destination;
		}
	}

	/**
	 * Percorre todas as rotas e retorna o destino final
	 * @return array Destino
	 * @example return [Controller, action, ...$args]
	 */
	public function getDestination()
	{
		$url = $this->format($this->relativeUrl);
		$args = [];

		foreach ($this->routes as $request => $destination) {
			$pattern = '@^' . $this->format($request) . '$@';
			$match = preg_match($pattern, $url, $args);
			if ($match) {
				return [...explode('@', $destination), ...array_splice($args, 1)];
			}
		}

		throw new HttpException('Route not found', 404);
	}


	/**
	 * Retorna no formato de URL
	 * @param string $url
	 * @return string
	 */
	public function format($url)
	{
		return rtrim($url, static::SUFFIX) . static::SUFFIX;
	}

	/**
	 * Redireciona para a URL escolhida
	 * @param string $url URL relativa ou absoluta
	 * @codeCoverageIgnore
	 */
	public function redirect($url = '')
	{
		if (false === strpos($url, '://')) {
			$url = $this->baseUrl . $url;
		}
		header('location:' . $url);
		die();
	}

	/**
	 * Volta para o método index da pagina atual
	 * @codeCoverageIgnore
	 */
	public function redirectToIndex()
	{
		$this->redirect($this->segments[0]);
	}

	/**
	 * Atualiza a mesma página
	 * @param string $url
	 * @codeCoverageIgnore
	 */
	public function refresh()
	{
		$this->redirect($this->url);
	}

	/**
	 * Retorna a URL base
	 * @return string
	 */
	private function getBaseUrl()
	{
		$host = Input::server('HTTP_HOST');
		if ($host) {
			$script = Input::server('SCRIPT_NAME');
			$baseUrl = preg_replace('@/+$@', '', dirname($script));
			return $this->protocol . '://' . $host . $baseUrl . '/';
		}
	}

	/**
	 * Define o final da URL
	 * @return string
	 */
	private function getRelativeUrl()
	{
		$host = Input::server('HTTP_HOST');
		if ($host) {
			$requestUri = explode('?', Input::server('REQUEST_URI'));
			$context = explode($host, $this->baseUrl);
			$uri = (explode(end($context), $requestUri[0], 2));
			return end($uri);
		}
	}

	/**
	 * Define os fragmentos da URL
	 * @return string[]
	 */
	private function getSegments()
	{
		return array_filter(explode('/', $this->relativeUrl)) + static::HOME;
	}
}
