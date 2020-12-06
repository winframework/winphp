<?php

namespace Win\Templates;

use Win\Application;
use Win\HttpException;

/**
 * View - Template da Página
 * 
 * Exibe as variáveis públicas do controller
 */
class View extends Template
{
	/**
	 * Cria uma View com base no arquivo escolhido
	 * @param string $file arquivo da View
	 */
	public function __construct($file, $data = [])
	{
		$controller = Application::app()->controller;
		$data = array_merge(get_object_vars($controller), $data);
		parent::__construct($file, $data);

		if (!$this->exists()) {
			throw new HttpException("View '{$this->file}' não encontrada.", 404);
		}
	}
}
