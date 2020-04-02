<?php

namespace Win\Views;

use Win\Application;
use Win\Common\Template;
use Win\Response\ResponseException;

/**
 * View
 *
 * Responsável por criar o visual da página
 */
class View extends Template
{
	public static $dir = '/templates/views';
	const LAYOUT_PREFIX = 'view';

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
			throw new ResponseException("View '{$this->getFile()}' not found", 404);
		}
	}

	/** @return string */
	public function getTitle()
	{
		return $this->getData('title');
	}

	/**
	 * Carrega e retorna o output da view
	 * @return string
	 */
	public function load()
	{
		$output = parent::load();
		$data = get_object_vars($this);
		$this->data = $data + $this->data;

		return $output;
	}
}
