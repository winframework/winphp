<?php

namespace Win\Database\Orm\Traits;

trait ReadTrait
{
	/** @var Select */
	private $query;

	/**
	 * Retorna o primeiro resultado da consulta
	 */
	public function one()
	{
		$rows = $this->query->execute();

		return $this->mapModel($rows[0]);
	}

	/**
	 * Retorna todos os resultado da consulta
	 */
	public function all()
	{
		$rows = $this->query->execute();
		$all = [];
		foreach ($rows as $row) {
			$all[] = $this->mapModel($row);
		}

		return $all;
	}

	/** @return int */
	public function numRows()
	{
		return $this->query->count();
	}

	/**
	 * Define as colunas do resultado
	 * @param string[] $columns
	 * @return static
	 */
	public function setColumns($columns)
	{
		$this->query->columns = $columns;

		return $this;
	}

	/**
	 * Adiciona uma coluna do resultado
	 * @param string $column
	 * @return static
	 */
	public function addColumn($column)
	{
		$this->query->columns[] = $column;

		return $this;
	}

	/**
	 * Filtra pelo id
	 * @param int $id
	 * @return static
	 */
	public function find($id)
	{
		$this->filterBy('id', $id);

		return $this;
	}

	/**
	 * Filtra pelo campo
	 * @param string $column
	 * @param mixed $value
	 * @return static
	 */
	public function filterBy($column, $value)
	{
		$this->filter($column, '=', $value);

		return $this;
	}

	/**
	 * Adiciona filtros para busca
	 * @return static
	 */
	public function filter($column, $operator, $value)
	{
		$this->query->where->add($column, $operator, $value);

		return $this;
	}

	/**
	 * Limita os resultados
	 * @param int $limit
	 * @return static
	 */
	public function limit($limit)
	{
		$this->query->limit->set($limit);

		return $this;
	}
}
