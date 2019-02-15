<?php

namespace Win\Mvc;

/**
 * Bloco
 * Ver arquivos em: "app/block/"
 * Pequeno arquivo em .phtml que é chamado em views, emails, módulos, etc
 */
class Block {

	public static $dir = '/app/block';

	/**
	 * Ponteiro para Aplicação Principal
	 * @var Application
	 */
	public $app;

	/**
	 * Endereço completo do arquivo .phtml que contem o código HTML
	 * @var string
	 */
	protected $file;

	/**
	 * Variáveis para serem usadas no arquivo da View
	 * @var mixed[]
	 */
	protected $data = [];

	/**
	 * Cria uma View com base no arquivo escolhido
	 * @param string $file Nome do arquivo da View
	 * @param mixed[] $data Array de variáveis
	 */
	public function __construct($file, $data = []) {
		$this->app = Application::app();
		$this->setFile($file);
		$this->data = $data;
	}

	/**
	 * Adiciona uma variável para usar na View
	 * @param string $name
	 * @param mixed $value
	 */
	public function addData($name, $value) {
		$this->data[$name] = $value;
	}

	/**
	 * Retorna uma variável da View
	 * @param string $name
	 * @return mixed|null
	 */
	public function getData($name) {
		if (key_exists($name, $this->data)) {
			return $this->data[$name];
		}
		return null;
	}

	/**
	 * Define o arquivo da View
	 * @param string $file
	 */
	protected function setFile($file) {
		$filePath = static::$dir . DIRECTORY_SEPARATOR . $file;

		if (!is_null(Template::instance()->getTheme())) {
			$filePath = Template::instance()->getFilePath($file);
		}

		$this->file = BASE_PATH . $filePath . '.phtml';
	}

	/** @return string */
	public function getFile() {
		return $this->file;
	}

	/**
	 * Retorna TRUE se a View existe
	 * @return boolean
	 */
	public function exists() {
		return (file_exists($this->file));
	}

	/**
	 * Retorna o HTML da View
	 * @return string
	 */
	public function __toString() {
		return $this->toString();
	}

	/**
	 * Retorna o HTML da View
	 * @return string
	 */
	public function toString() {
		ob_start();
		$this->toHtml();
		return ob_get_clean();
	}

	/**
	 * Exibe o conteúdo HTML da View
	 */
	public function toHtml() {
		$this->load();
	}

	/**
	 * Exibe o conteúdo HTML da View
	 * @return string
	 */
	public function load() {
		if (isset($this->file) && $this->exists()) {
			extract($this->data);
			include $this->file;
		}
	}

}
