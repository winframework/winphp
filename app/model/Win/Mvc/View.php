<?php

namespace Win\Mvc;

use Win\Helper\Template;

/**
 * Views
 *
 * São responsáveis por criar o visual da página
 */
class View {

	public static $dir = 'app/view/';

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
	 * Define o arquivo da view
	 * @param string $file Arquivo sem extensao
	 */
	private function setFile($file) {
		$filePath = static::$dir . $file;
		
		if (!is_null(Template::instance()->getTheme())):
			$filePath = Template::instance()->getFilePath(static::$dir, $file);
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
		if (!$this->exists()):
			$this->app->pageNotFound();
		endif;
	}

	/**
	 * Carrega o arquivo da view, imprimindo o resultado html
	 * @return string
	 */
	public function load() {
		$this->toHtml();
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
	 * Inclui o html do arquivo da view
	 */
	public function toHtml() {
		if (!is_null($this->file)):
			extract($this->data);
			include $this->file;
		endif;
	}

}
