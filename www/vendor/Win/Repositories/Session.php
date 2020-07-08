<?php

namespace Win\Repositories;

use Win\Common\Traits\ArrayDotTrait;

/**
 * Variáveis de $_SESSION
 */
class Session
{
	use ArrayDotTrait;

	/**
	 * Cria referência da sessão
	 */
	public function __construct()
	{
		$this->data = &$_SESSION;
	}

	/**
	 * Retorna todas variáveis da sessão e limpa a sessão
	 * @return mixed[]
	 */
	public function popAll()
	{
		$values = $this->all();
		$this->clear();

		return $values;
	}

	/**
	 * Retorna a variável da sessão e a remove
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function pop($key, $default = '')
	{
		$value = $this->get($key, $default);
		$this->delete($key);

		return $value;
	}
}
