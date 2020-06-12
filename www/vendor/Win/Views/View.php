<?php

namespace Win\Views;

use Win\Application;
use Win\Common\Template;
use Win\Response\Response;
use Win\HttpException;

/**
 * View
 *
 * Responsável por criar o visual da página
 */
class View extends Template implements Response
{
	/**
	 * Cria uma View com base no arquivo escolhido
	 * @param string $file arquivo da View
	 */
	public function __construct($file)
	{
		Application::app()->view = $this;
		$controller = Application::app()->controller;
		$data = get_object_vars($controller);
		parent::__construct($file, $data, 'shared/' . $controller->layout);
		$this->validateFile();
	}

	private function validateFile()
	{
		if (!$this->exists()) {
			throw new HttpException("View '{$this->file}' not found", 404);
		}
	}

	/** @return string */
	public function getTitle()
	{
		return $this->getData('title');
	}

	/**
	 * Envia a resposta Html
	 * @return string
	 */
	public function respond()
	{
		return $this->toHtml();
	}
}
