<?php

namespace Win\Common;

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

	private ?string $layout = null;

	/**
	 * Cria um template com base no arquivo escolhido
	 * @param string $file Nome do arquivo
	 * @param mixed[] $data Array de variáveis
	 * @param string $layout
	 */
	public function __construct($file, $data = [], $layout = null)
	{
		$this->app = Application::app();
		$this->file = BASE_PATH . '/' . static::$dir . "/$file.phtml";
		$this->data = $data;
		$this->layout = $layout;
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
	public function getData($name)
	{
		if (key_exists($name, $this->data)) {
			return $this->data[$name];
		}

		return null;
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
		if ($this->layout) {
			return new Template($this->layout, ['content' => $this]);
		}

		ob_start();
		$this->load();
		return ob_get_clean();
	}

	/**
	 * Carrega e exibe o conteúdo do template
	 */
	public function load()
	{
		if (isset($this->file) && $this->exists()) {
			extract($this->data);
			include $this->file;
		}
	}
}
