<?php

namespace Win\Common;

use Win\Application;

/**
 * Templates em .PHTML
 * Ver arquivos em: "app/templates/"
 */
class Template
{
	public static $dir = 'app/templates';
	/**
	 * Ponteiro para Aplicação Principal
	 * @var Application
	 */
	public $app;

	/**
	 * Endereço completo do arquivo .phtml
	 * @var string
	 */
	protected $file;

	/**
	 * Variáveis para serem usadas no corpo do arquivo
	 * @var mixed[]
	 */
	protected $data = [];

	/**
	 * Layout
	 * @var string
	 */
	private $layout = null;

	/**
	 * Cria um template com base no arquivo escolhido
	 * @param string $file Nome do arquivo
	 * @param mixed[] $data Array de variáveis
	 * @param string $layout Layout
	 */
	public function __construct($file, $data = [], $layout = null)
	{
		$this->app = Application::app();
		$this->file = BASE_PATH . '/' . static::$dir . "/$file.phtml";
		$this->data = $data;
		$this->layout = $layout;
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
			return (new Layout($this->layout, $this))->toHtml();
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
