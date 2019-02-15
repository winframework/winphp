<?php

namespace Win\Mvc;

use Win\Html\Seo\Title;

/**
 * View
 *
 * Responsável por criar o visual da página
 */
class View extends Block {

	public static $dir = '/app/view';

	/**
	 * Cria uma View com base no arquivo escolhido
	 * @param string $file arquivo da View
	 * @param mixed[] $data Variáveis
	 */
	public function __construct($file, $data = []) {
		parent::__construct($file, $data);
	}

	/**
	 * Executa o ErrorPage caso a view não exista
	 * @throws HttpException
	 */
	public function validate() {
		if (!$this->exists()) {
			throw new HttpException(404);
		}
	}

	/**
	 * Adiciona um array de variáveis para usar na View
	 * @param mixed[] $data
	 */
	public function mergeData(array $data) {
		$this->data = array_merge($this->data, $data);
	}

	/** @return string */
	public function getTitle() {
		if (empty($this->getData('title'))) {
			$this->addData('title', $this->getDinamicTitle());
		}
		return $this->getData('title');
	}

	/**
	 * @return string
	 */
	private function getDinamicTitle() {
		return Title::otimize(ucwords(str_replace('-', ' ', $this->app->getPage())));
	}

}
