<?php

namespace Win\Mvc;

/**
 * View em Json (apenas dados)
 *
 * Responsável por criar um retorno em Json
 */
class JsonView extends View
{
	/**
	 * Cria uma View com base no arquivo escolhido
	 * @param mixed[] $data
	 */
	public function __construct($data = [])
	{
		header('Content-Type: application/json');
		parent::__construct('', $data);
	}

	public function exists()
	{
		return true;
	}

	/**
	 * Exibe os dados em Json
	 * @return mixed[]
	 */
	public function toJson()
	{
		return json_encode($this->data);
	}
}
