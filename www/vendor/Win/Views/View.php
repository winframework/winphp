<?php

namespace Win\Views;

use Win\Application;
use Win\Common\Template;
use Win\Response\Response;
use Win\Response\ResponseException;

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
		parent::__construct($file, get_object_vars($controller), $controller->layout);
	}

	protected function setFile($file)
	{
		parent::setFile($file);
		if (!$this->exists()) {
			throw new ResponseException("View '{$this->file}' not found", 404);
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
