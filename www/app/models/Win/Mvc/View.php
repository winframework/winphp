<?php

namespace Win\Mvc;

use Win\Response\ResponseException;

/**
 * View
 *
 * Responsável por criar o visual da página
 */
class View extends Template
{
	public static $dir = '/app/templates/views';

	/**
	 * Cria uma View com base no arquivo escolhido
	 * @param string $file arquivo da View
	 * @param mixed[] $data Variáveis
	 */
	public function __construct($file, $data = [])
	{
		parent::__construct($file, $data);
		$this->app->view = $this;
		if (!$this->exists()) {
			throw new ResponseException("View '{$file}' not found", 404);
		}
	}

	public function __toString()
	{
		$this->data += get_object_vars($this->app->controller);

		return (new Template($this->app->controller->template))->__toString();
	}

	/** @return string */
	public function getTitle()
	{
		return $this->getData('title');
	}
}
