<?php

namespace Win\Common\Traits;

/**
 * Armazena Dados
 */
trait ArrayDotTrait
{
	/**
	 * Dados que estão armazenados
	 * @var mixed[]
	 */
	protected $data = [];

	/**
	 * Retorna todos os valores
	 * @return mixed[]
	 */
	public function all()
	{
		return $this->data;
	}

	/** Exclui todos os dados */
	public function clear()
	{
		$this->data = [];
	}

	/**
	 * Define um valor
	 * @param string $key
	 * @param mixed $value
	 */
	public function set($key, $value)
	{
		$p = &$this->data;
		$keys = explode('.', $key);
		foreach ($keys as $k) {
			$p = &$p[$k];
		}
		if ($value) {
			$p = $value;
		}
	}

	/**
	 * Adiciona um valor ao array
	 * @param string $key
	 * @param mixed $value
	 */
	public function add($key, $value)
	{
		$values = $this->getArray($key);
		array_push($values, $value);
		$this->set($key, $values);
	}

	/**
	 * Retorna um valor
	 * @param string $key
	 * @param mixed $default Valor default, caso a $key não exista
	 */
	public function get($key, $default = null)
	{
		$data = $this->data;
		$keys = explode('.', $key);
		foreach ($keys as $k) {
			if (is_array($data) && array_key_exists($k, $data)) {
				$data = $data[$k];
			} else {
				return $default;
			}
		}

		return $data;
	}

	/**
	 * Retorna sempre um array
	 * @param string $key
	 * @return mixed
	 */
	private function getArray($key)
	{
		$values = $this->get($key, []);
		if (!is_array($values) && $this->has($key)) {
			$values = [$this->get($key)];
		}

		return $values;
	}

	/**
	 * Remove o valor
	 * @param string $key
	 */
	public function delete($key)
	{
		unset($this->data[$key]);
	}

	/**
	 * Retorna TRUE se $key existe
	 * @param string $key
	 * @return bool
	 */
	public function has($key)
	{
		return !is_null($this->get($key, null));
	}

	/**
	 * Retorna TRUE se estiver vazio
	 * @return bool
	 */
	public function isEmpty()
	{
		return empty($this->data);
	}
}
