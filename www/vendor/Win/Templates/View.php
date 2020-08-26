<?php

namespace Win\Templates;

use Win\Application;
use Win\HttpException;

/**
 * View
 *
 * Responsável por criar o visual da página
 */
class View extends Template
{
	/**
	 * Cria uma View com base no arquivo escolhido
	 * @param string $file arquivo da View
	 */
	public function __construct($file, $data = [])
	{
		Application::app()->view = $this;
		$controller = Application::app()->controller;
		$data = array_merge(get_object_vars($controller), $data);
		parent::__construct($file, $data, $controller->layout);

		if (!$this->exists()) {
			throw new HttpException("View '{$this->file}' not found", 404);
		}
	}
}
