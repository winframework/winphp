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
		$this->setFile($file);
		$this->app = Application::app();
		$this->app->view = $this;

		$this->data = $data + get_object_vars($this->app->controller);
		$output = $this->load();
		$template = new Template($this->app->controller->template, ['view' => $output]);
		$this->output = $template->__toString();
	}

	public function setFile($file)
	{
		parent::setFile($file);
		if (!$this->exists()) {
			throw new ResponseException("View '{$file}' not found", 404);
		}
	}

	/** @return string */
	public function getTitle()
	{
		return $this->getData('title');
	}
}
