<?php

namespace Win\Common;

use Win\Application;

/**
 * Templates em .PHTML
 * Ver arquivos em: "/templates/"
 */
class Template
{
	public static $dir = '/templates';
	/**
	 * Retorno do arquivo
	 * @var string
	 */
	public $output;

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
	 * Cria um template com base no arquivo escolhido
	 * @param string $file Nome do arquivo
	 * @param mixed[] $data Array de variáveis
	 * @param string $layout Layout
	 */
	public function __construct($file, $data = [], $layout = null)
	{
		$this->app = Application::app();
		$this->data = $data;
		$this->setFile($file);
		$this->setOutput($layout);
	}

	/**
	 * Define o output
	 * @param string $layout
	 */
	protected function setOutput($layout)
	{
		if ($layout) {
			$layout = static::LAYOUT_PREFIX . '_' . $layout;
			$this->output = (string) new Layout($layout, [static::LAYOUT_PREFIX => $this]);
		} else {
			$this->output = $this->load();
		}
	}

	/**
	 * Define o arquivo do template
	 * @param string $file
	 */
	protected function setFile($file)
	{
		$this->file = BASE_PATH . static::$dir . '/' . $file . '.phtml';
	}

	/** @return string */
	public function getFile()
	{
		return $this->file;
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
	 * Retorna o conteúdo
	 * @return string
	 */
	public function __toString()
	{
		return $this->output;
	}

	/**
	 * Carrega e retorna o output
	 * @return string
	 */
	protected function load()
	{
		ob_start();

		if (isset($this->file) && $this->exists()) {
			extract($this->data);
			include $this->file;
		}

		return ob_get_clean();
	}
}
