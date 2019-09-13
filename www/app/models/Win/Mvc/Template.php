<?php

namespace Win\Mvc;

/**
 * Templates em .PHTML
 * Ver arquivos em: "app/templates/"
 */
class Template
{
	public static $dir = '/app/templates';

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
		$this->setFile($file);
		$this->data = $data;
	}

	/**
	 * Adiciona uma variável
	 * @param string $name
	 * @param mixed $value
	 */
	public function addData($name, $value)
	{
		$this->data[$name] = $value;
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
		ob_start();
		$this->load();

		return ob_get_clean();
	}

	/**
	 * Carrega e Exibe o conteúdo
	 * @return string
	 */
	public function load()
	{
		$this->app = Application::app();
		
		if (isset($this->file) && $this->exists()) {
			extract($this->data);
			include $this->file;
		}
	}
}
