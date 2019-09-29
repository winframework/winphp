<?php

namespace Win\Core\Repositories;

use Win\Core\Common\Traits\ArrayDotTrait;

/**
 * Variáveis de $_SESSION
 */
class Session
{
	use ArrayDotTrait;

	private $instance;

	/**
	 * Retorna instância de Session
	 * @param string $alias
	 * @return static
	 */
	public static function instance($alias = 'default')
	{
		$instance = new Session();
		$instance->data = &$_SESSION[$alias];

		return $instance;
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
