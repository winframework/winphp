<?php

namespace Win\Core\Common;

use Win\Core\Application;

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
	 */
	public function __construct($file, $data = [])
	{
		$this->app = Application::app();
		$this->setFile($file);
		$this->data = $data;
		$this->output = $this->load();
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
	 * Define o arquivo do template
	 * @param string $file
	 */
	protected function setFile($file)
	{
		$filePath = static::$dir . DIRECTORY_SEPARATOR . $file;

		if (!is_null(Theme::instance()->get())) {
			$filePath = Theme::instance()->getFilePath($file);
		}

		$this->file = BASE_PATH . $filePath . '.phtml';
	}

	/** @return string */
	public function getFile()
	{
		return $this->file;
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
	 * Carrega e Retorna o output
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
