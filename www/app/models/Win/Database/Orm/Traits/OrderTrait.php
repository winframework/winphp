<?php

namespace Win\Database\Orm\Traits;

trait OrderTrait
{
	/** @var Select */
	private $query;

	/**
	 * Ordena por um campo
	 * @param string $orderBy
	 * @return static
	 */
	public function orderBy($orderBy)
	{
		$this->query->orderBy->set($orderBy);

		return $this;
	}

	/**
	 * Ordena pelos mais novos
	 * @return static
	 */
	public function newer()
	{
		$this->orderBy('id DESC');

		return $this;
	}

	/**
	 * Ordena pelos mais antigos
	 * @return static
	 */
	public function older()
	{
		$this->orderBy('id ASC');

		return $this;
	}
}
