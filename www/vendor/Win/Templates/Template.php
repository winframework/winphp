<?php

namespace Win\Templates;

use Error;
use Exception;
use Win\Application;

/**
 * Templates em .PHTML
 * Ver arquivos em: "templates/"
 */
class Template
{
	public static $dir = 'templates';
	public Application $app;

	/**
	 * Endereço completo do arquivo .phtml
	 * @var string
	 */
	protected string $file;

	protected $data = [];

	public $content = '';

	/**
	 * Cria um template com base no arquivo escolhido
	 * @param string $file Nome do arquivo
	 * @param mixed[] $data Array de variáveis
	 * @param string $layout
	 */
	public function __construct($file, $data = [], $content = '')
	{
		$this->app = Application::app();
		$this->file = BASE_PATH . '/' . static::$dir . "/$file.phtml";
		$this->data = $data;
		$this->content = $content;
	}

	/**
	 * Carrega e retorna o output
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->toHtml();
	}

	/**
	 * Retorna uma variável
	 * @param string $name
	 * @return mixed|null
	 */
	public function get($name)
	{
		return $this->data[$name] ?? null;
	}

	/**
	 * Retorna TRUE se o template existe
	 * @return bool
	 */
	public function exists()
	{
		return file_exists($this->file);
	}

	/**
	 * Carrega e retorna o output
	 * @return string
	 */
	public function toHtml()
	{
		ob_start();

		try {
			if (isset($this->file) && $this->exists()) {
				$content = $this->content;
				extract($this->data);
				include $this->file;
			}
		} catch (Error $e) {
			ob_get_clean();
			throw ($e);
		}
		return ob_get_clean();
	}
}
