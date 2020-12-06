<?php

namespace Win\Repositories;

use PDO;
use PDOException;
use Win\InjectableTrait;
use Win\HttpException;
use Win\Services\Pagination;
use Win\Models\Model;

/**
 * Base Database Repository
 * 
 * @method Model[] list()
 * @method Model one()
 * @method Model oneOr404()
 * @method Model find($id)
 * @method Model findOr404($id)
 */
abstract class Repository
{
	use InjectableTrait;

	static PDO $globalPdo;

	public PDO $pdo;
	public Pagination $pagination;
	protected $table = '';
	protected $pk = 'id';
	protected Sql $sql;

	/**
	 * @param Model $model
	 * @return mixed[]
	 */
	abstract public static function mapRow($model);

	abstract public static function mapModel($row);

	public function __construct()
	{
		if (!isset(static::$globalPdo)) {
			static::$globalPdo = require BASE_PATH . '/config/database.php';
		}
		$this->pdo = static::$globalPdo;
		$this->sql = new Sql($this->table);
		$this->pagination = new Pagination();
	}

	/**
	 * Define a tabela manualmente
	 * @param string $table
	 */
	public function setTable($table)
	{
		$this->table = $table;
		return $this;
	}

	public function getTable()
	{
		return $this->table;
	}

	/**
	 * Define as colunas da busca
	 * @param string $columns
	 */
	public function select($columns)
	{
		$this->sql->columns[] = $columns;

		return $this;
	}

	/**
	 * Retorna o primeiro resultado da busca
	 * @return Model
	 */
	public function one()
	{
		try {
			$this->sql->setLimit(0, 1);
			$query = $this->sql->select();
			$values = $this->sql->values();
			$this->flush();

			$stmt = $this->pdo->prepare($query);
			$stmt->execute($values);
			$row = $stmt->fetch();

			if ($row !== false) {
				return $this->mapModel($row);
			}
		} catch (PDOException $e) {
			throw new DbException('Ocorreu um erro ao ler no banco de dados.', 500, $e);
		}
	}

	/**
	 * Retorna o primeiro resultado da busca ou redireciona para Página 404
	 */
	public function oneOr404()
	{
		$model = $this->one();
		if (is_null($model)) {
			throw new HttpException('O registro não foi encontrado no banco de dados.', 404);
		}

		return $model;
	}

	/**
	 * Retorna o registro pela PK
	 * @param int $id
	 */
	public function find($id)
	{
		return $this->if($this->pk, $id)->one();
	}

	/**
	 * Retorna o registro pela PK ou redireciona para Página 404
	 * @param int $id
	 */
	public function findOr404($id)
	{
		return $this->if($this->pk, $id)->oneOr404();
	}

	/**
	 * Retorna o todos os resultados da busca
	 */
	public function list()
	{
		try {
			$this->setListLimit();
			$query = $this->sql->select();
			$values = $this->sql->values();
			$this->flush();

			$stmt = $this->pdo->prepare($query);
			$stmt->execute($values);
			return array_map([$this, 'mapModel'], $stmt->fetchAll());
		} catch (PDOException $e) {
			throw new DbException('Ocorreu um erro ao ler no banco de dados.', 500, $e);
		}
	}

	protected function setListLimit()
	{
		$pagination = $this->pagination;

		if ($pagination->pageSize > 0) {
			$query = $this->sql->selectCount();
			$values = $this->sql->values();

			$stmt = $this->pdo->prepare($query);
			$stmt->execute($values);
			$pagination->count = $stmt->fetchColumn();
			$this->sql->setLimit($pagination->offset(), $pagination->pageSize);
		}
	}

	/**
	 * Retorna o total de resultados da busca
	 * @return int
	 */
	public function count()
	{
		try {
			$query = $this->sql->selectCount();
			$values = $this->sql->values();
			$this->flush();

			$stmt = $this->pdo->prepare($query);
			$stmt->execute($values);
			return (int) $stmt->fetchColumn();
		} catch (PDOException $e) {
			throw new DbException('Ocorreu um erro ao ler no banco de dados.', 500, $e);
		}
	}

	/**
	 * Define um limite das buscas com list()
	 * @param int $pageSize
	 * @param int $currentPage
	 */
	public function paginate($pageSize, $currentPage = 1)
	{
		$this->pagination->pageSize = $pageSize;
		$this->pagination->current = max($currentPage, 1);
		return $this;
	}

	/**
	 * Remove todos os registros da busca
	 */
	public function delete()
	{
		try {
			$query = $this->sql->delete();
			$values = $this->sql->values();
			$this->flush();

			$this->pdo->prepare($query)->execute($values);
		} catch (PDOException $e) {
			throw new DbException('Ocorreu um erro ao escrever no banco de dados.', 500, $e);
		}
	}

	/**
	 * Remove o registro pela PK
	 * @param int $id
	 */
	public function destroy($id)
	{
		$this->if($this->pk, $id)->delete();
	}

	/**
	 * Salva o registro no banco
	 * @param Model $model
	 */
	public function save(Model $model)
	{
		try {
			$this->sql->setValues($this->mapRow($model));
			$query = $this->querySave($model);
			$values = array_values($this->sql->values());
			$this->flush();

			$this->pdo->prepare($query)->execute($values);
			$model->id = $model->id ?? $this->pdo->lastInsertId();
			return $this;
		} catch (PDOException $e) {
			throw new DbException('Ocorreu um erro ao escrever no banco de dados.', 500, $e);
		}
	}

	/**
	 * @param Model $model
	 * @return string
	 */
	protected function querySave($model)
	{
		if ($this->exists($model->id)) {
			$this->if($this->pk, $model->id);
			return $this->sql->update();
		} else {
			return $this->sql->insert();
		}
	}

	/**
	 * Atualiza somente as colunas informadas
	 * @param mixed[] $columns
	 * @return int
	 */
	public function update($columns)
	{
		try {
			$this->sql->setValues($columns);
			$query = $this->sql->update();
			$values = array_values($this->sql->values());
			$this->flush();

			$stmt = $this->pdo->prepare($query);
			$stmt->execute($values);
			return $stmt ? $stmt->rowCount() : null;
		} catch (PDOException $e) {
			throw new DbException('Ocorreu um erro ao escrever no banco de dados.', 500, $e);
		}
	}

	/**
	 * Remove todos os filtros, ordenação, etc
	 */
	public function flush()
	{
		$this->sql->__construct($this->table);

		return $this;
	}

	/**
	 * Retorna TRUE se o model existir no banco
	 * @param int $id
	 * @return bool
	 */
	protected function exists($id)
	{
		$orm = new static();
		$orm->pdo = $this->pdo;
		$orm->table = $this->table;
		return $orm->if($this->pk, $id)->count() > 0;
	}

	/**
	 * Adiciona (inner) join
	 * @param string $query
	 * @param array $values
	 */
	public function join($query, ...$values)
	{
		$this->sql->addJoin('JOIN ' . $query, $values);

		return $this;
	}

	/**
	 * Adiciona left join
	 * @param string $query
	 * @param array $values
	 */
	public function leftJoin($query, ...$values)
	{
		$this->sql->addJoin('LEFT JOIN ' . $query, $values);

		return $this;
	}

	/**
	 * Adiciona right join
	 * @param string $query
	 * @param array $values
	 */
	public function rightJoin($query, ...$values)
	{
		$this->sql->addJoin('RIGHT JOIN ' . $query, $values);

		return $this;
	}

	/**
	 * Aplica filtros
	 * @param string $comparator
	 * @param mixed $values
	 * @example if('name', 'John')
	 * @example if('name = ? OR email = ?', 'John', 'email')
	 */
	public function if($comparator, ...$values)
	{
		$this->sql->addWhere($comparator, $values);

		return $this;
	}

	/**
	 * Aplica filtros (aceita bind params)
	 * @param string $query
	 * @param mixed[] $values
	 * @example filter('name = :name OR age > 0', [':name' => 'John'])
	 */
	public function filter($query, $values = [])
	{
		$this->sql->addWhere($query, $values);

		return $this;
	}

	/**
	 * Ordem por um campo
	 * @param string $rule
	 * @param int $priority
	 */
	public function sort($rule, $priority = 0)
	{
		$this->sql->addOrderBy($rule, $priority);

		return $this;
	}

	/**
	 * Exibe a query e os valores para debug
	 * @param string $method
	 * @return array<string,mixed>
	 */
	public function debug($method = 'select')
	{
		return [$this->sql->$method(), ...$this->sql->values()];
	}
}
