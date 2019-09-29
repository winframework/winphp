<?php

namespace Win\Core\Response;

/**
 * Resposta Json (apenas dados)
 */
class JsonResponse implements Response
{
	private $data = [];

	/**
	 * Cria uma Resposta em json
	 * @param mixed[] $data
	 */
	public function __construct($data)
	{
		$this->data = $data;
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
