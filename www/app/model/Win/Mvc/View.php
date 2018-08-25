<?php

namespace Win\Mvc;

use Win\Html\Seo\Title;

/**
 * Views
 *
 * São responsáveis por criar o visual da página
 */
class View {

	public static $dir = '/app/view';

	/**
	 * Ponteiro para Aplicação Principal
	 * @var Application
	 */
	public $app;

	/**
	 * Endereço completo do arquivo .phtml que contem o código HTML
	 * @var string
	 */
	private $file;

	/**
	 * Variáveis para serem usadas no arquivo da View
	 * @var mixed[]
	 */
	private $data;

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
	 * Adiciona um array de variáveis para usar na View
	 * @param mixed[] $data
	 */
	public function mergeData(array $data) {
		$this->data = array_merge($this->data, $data);
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

	public function getTitle() {
		if (empty($this->getData('title'))) {
			$this->addData('title', Title::otimize(ucwords(str_replace('-', ' ', $this->app->getPage()))));
		}
		return $this->getData('title');
	}

	/**
	 * Define o arquivo da View
	 * @param string $file
	 */
	private function setFile($file) {
		$filePath = static::$dir .DIRECTORY_SEPARATOR. $file;

		if (!is_null(Template::instance()->getTheme())):
			$filePath = Template::instance()->getFilePath($file);
		endif;

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
	 * Se arquivo não existe, define como 404
	 */
	public function validate() {
		if (!$this->exists() && $this->app->getPage() !== '404'):
			$this->app->pageNotFound();
		endif;
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
	 * @return string
	 */
	public function load() {
		$this->toHtml();
	}

	/**
	 * Exibe o conteúdo HTML da View
	 */
	public function toHtml() {
		if (isset($this->file) && $this->exists()):
			extract($this->data);
			include $this->file;
		endif;
	}

}
