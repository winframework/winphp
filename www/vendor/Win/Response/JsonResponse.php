<?php

namespace Win\Response;

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
	 * Envia a resposta Json
	 * @codeCoverageIgnore
	 * @return string
	 */
	public function respond()
	{
		header('Content-Type: application/json');

		return json_encode($this->data);
	}
}
