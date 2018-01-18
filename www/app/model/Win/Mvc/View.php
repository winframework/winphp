<?php

namespace Win\Mvc;

use Win\Helper\Template;
use Win\Html\Seo\Title;

/**
 * Views
 *
 * São responsáveis por criar o visual da página
 */
class View {

	public static $dir = '/app/view/';

	/**
	 * Ponteiro para Aplicação Principal
	 * @var Application
	 */
	public $app;

	/**
	 * Endereço completo do arquivo .phtml que contem o código html
	 * @var string
	 */
	private $file = null;

	/**
	 * Variáveis para serem usadas no arquivo da view
	 * @var mixed[]
	 */
	private $data;

	/**
	 * Cria uma View com base no arquivo escolhido
	 * @param string $file Nome do arquivo da view
	 * @param mixed[] $data Array de variaveis
	 */
	public function __construct($file, $data = []) {
		$this->app = Application::app();
		$this->setFile($file);
		$this->data = $data;
	}

	/**
	 * Adiciona uma variavel para usar na view
	 * @param string $name
	 * @param mixed $value
	 */
	public function addData($name, $value) {
		$this->data[$name] = $value;
	}

	/**
	 * Adiciona um array de variaveis para usar na view
	 * @param mixed[] $data
	 */
	public function mergeData(array $data) {
		$this->data = array_merge($this->data, $data);
	}

	/**
	 * Retorna uma variavel da view
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
		if (empty($this->getData('title'))){
			$this->addData('title', Title::otimize(ucfirst(str_replace('-', ' ', $this->app->getPage()))));
		}
		return $this->getData('title');
	}

	/**
	 * Define o arquivo da view
	 * @param string $file
	 */
	private function setFile($file) {
		$filePath = BASE_PATH . static::$dir . $file;

		if (!is_null(Template::instance()->getTheme())):
			$filePath = Template::instance()->getFilePath(BASE_PATH . static::$dir, $file);
		endif;

		if (file_exists($filePath . '.phtml')):
			$this->file = $filePath . '.phtml';
		endif;
	}

	/**
	 * Retorna true se a view existe
	 * @return boolean
	 */
	public function exists() {
		return (file_exists($this->file));
	}

	/**
	 * Se arquivo nao existe, define como 404
	 */
	public function validate() {
		if (!$this->exists() && $this->app->getPage() !== '404'):
			$this->app->pageNotFound();
		endif;
	}

	/**
	 * Retorna o html da view
	 * @return string
	 */
	public function __toString() {
		return $this->toString();
	}

	/**
	 * Retorna o html da view
	 * @return string
	 */
	public function toString() {
		ob_start();
		$this->toHtml();
		return ob_get_clean();
	}

	/**
	 * Carrega o arquivo da view, imprimindo o resultado html
	 * @return string
	 */
	public function load() {
		$this->toHtml();
	}

	/**
	 * Carrega o arquivo da view, imprimindo o resultado html
	 */
	public function toHtml() {
		if (!is_null($this->file)):
			extract($this->data);
			include $this->file;
		endif;
	}

}
