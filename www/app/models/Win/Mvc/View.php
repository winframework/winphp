<?php

namespace Win\Mvc;

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
	}

	/**
	 * Executa o ErrorPage caso a view não exista
	 * @throws HttpException
	 */
	public function validate()
	{
		if (!$this->exists()) {
			throw new HttpException(404);
		}
	}

	/**
	 * Adiciona um array de variáveis para usar na View
	 * @param mixed[] $data
	 */
	public function mergeData(array $data)
	{
		$this->data = array_merge($this->data, $data);
	}

	/** @return string */
	public function getTitle()
	{
		return $this->getData('title');
	}
}
