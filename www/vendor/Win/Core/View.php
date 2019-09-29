<?php

namespace Win\Core;

use Win\Core\Application;
use Win\Core\Common\Template;
use Win\Core\Response\ResponseException;

/**
 * View
 *
 * Responsável por criar o visual da página
 */
class View extends Template
{
	public static $dir = '/templates/views';
	public static $layoutPrefix = 'view.';

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

		$this->data = $data;
		$output = $this->load();
		$layout = static::$layoutPrefix . $this->app->controller->template;
		$template = new Template($layout, ['view' => $output]);
		$this->output = $template->__toString();
	}

	public function setFile($file)
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

	public function load()
	{
		$this->data += get_object_vars($this->app->controller);
		$output = parent::load();
		$data = get_object_vars($this);
		$this->data = $data + $this->data;

		return $output;
	}
}
