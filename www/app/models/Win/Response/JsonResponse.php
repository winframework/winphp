<?php

namespace Win\Mvc;

/**
 * View em Json (apenas dados)
 *
 * Responsável por criar um retorno em Json
 */
class JsonView implements Response
{
	/**
	 * Cria uma Resposta com base no arquivo escolhido
	 * @param mixed[] $data
	 */
	public function __construct($data = [])
	{
		parent::__construct('', $data);
	}

	/**
	 * Exibe os dados em Json
	 * @return string
	 */
	public function __toString()
	{
		header('Content-Type: application/json');

		return json_encode($this->data);
	}
}
