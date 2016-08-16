<?php

namespace Win\DAO;

use Win\Mvc\Application;
use Win\Connection\MySQL;

/**
 * Data Access Object
 */
abstract class DAO implements DAOInterface {

	/** @var \PDO */
	protected $pdo;

	/** @var string[] */
	protected $selectCollumns = ['*'];

	/**
	 * Retorna um objeto a partir da linha da tabela
	 * @param array[] $row
	 */
	abstract protected function mapObject($row);

	/**
	 * Retorna a linha da tabela a partir de um objeto
	 * @param object $obj
	 */
	abstract protected function mapRow($obj);

	/**
	 * Valida os campos retornando string de Erro ou Null
	 * @return string|null
	 */
	abstract protected function validate();

	/** Inicia o DAO */
	public function __construct() {
		$this->pdo = MySQL::instance()->getPDO();
	}

	/**
	 * Define uma conexão manualmente
	 * @param \PDO $pdo
	 */
	public function setPDO($pdo) {
		$this->pdo = $pdo;
	}

	/**
	 * Define quais colunas serão consultadas no SELECT
	 * @param string[] $collumns
	 */
	public function setSelectCollumns(array $collumns) {
		$this->selectCollumns = $collumns;
	}

	/**
	 * Adiciona nova coluna no SELECT
	 * @param string $collumn
	 */
	public function addSelectCollumn($collumn) {
		if (!in_array($collumn, $this->selectCollumns)) {
			$this->selectCollumns[] = $collumn;
		}
	}

	/**
	 * Salva o registro
	 * @param object $obj
	 * @return string|null
	 */
	public function save($obj) {
		$this->obj = $obj;
		$error = $this->validate();
		if (is_null($error) and $this->pdo !== false) {
			if (!$this->objExists()) {
				$this->insert();
				$this->obj->setId($this->pdo->lastInsertId());
			} else {
				$this->update();
			}
		}
		return $error;
	}

	/** Insere o registro */
	protected function insert() {
		$mapRow = $this->mapRow($this->obj);
		$keys = array_keys($mapRow);
		$values = array_values($mapRow);
		$params = str_split(str_repeat('?', count($keys)));

		$sql = 'INSERT INTO ' . static::TABLE . ' (' . implode(',', $keys) . ') VALUES (' . implode(', ', $params) . ') ';
		if ($this->pdo) {
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute($values);
		}
	}

	/** Atualiza o registro */
	protected function update() {
		$mapRow = $this->mapRow($this->obj);
		$keys = array_keys($mapRow);
		$values = array_values($mapRow);
		$params = [];
		foreach ($keys as $key):
			$params[] = $key . ' = ?';
		endforeach;
		$values[] = $this->obj->getId();

		$sql = 'UPDATE ' . static::TABLE . ' SET ' . implode(', ', $params) . ' WHERE id = ? ';
		if ($this->pdo) {
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute($values);
		}
	}

	/**
	 * Exclui o registro
	 * @param object $obj
	 */
	public function delete($obj) {
		$this->deleteById($obj->getId());
	}

	/**
	 * Exclui o registro por id
	 * @param int $id
	 */
	public function deleteById($id) {
		$sql = 'DELETE FROM ' . static::TABLE . ' WHERE id = :id';
		if ($this->pdo) {
			$stmt = $this->pdo->prepare($sql);
			$stmt->bindValue(':id', $id);
			$stmt->execute();
		}
	}

	/**
	 * Busca o objeto pelo id
	 * @param int $id
	 */
	public function fetchById($id) {
		return $this->fetchByField('id', $id);
	}

	/**
	 * Busca o objeto por um campo/atributo específico
	 * @param string $name Nome do atributo
	 * @param mixed $value Valor do atributo
	 */
	public function fetchByField($name, $value) {
		return $this->fetch([$name . ' = ?' => $value]);
	}

	/**
	 * Busca o objeto
	 * @param string[] $filters Array de filtros
	 * @param string $option [Order by, Limit, etc]
	 */
	public function fetch($filters, $option = '') {
		if (!is_array($filters)):
			throw new \Exception("Filter: '{$filters}' must be a array");
		endif;
		$sql = $sql = $this->selectSQL() . ' ' . $this->whereSQL($filters) . ' ' . $option;
		$result = [];
		if ($this->pdo) {
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute(array_values($filters));

			$result = $stmt->fetch();
		}
		return $this->mapObject($result);
	}

	/**
	 * Retorna todos os registros
	 *
	 * <code>
	 * $dao->fetchAll( ['id = ?' => 10]);
	 * </code>
	 * @param string[] $filters Array de filtros
	 * @param string $option [Order by, Limit, etc]
	 */
	public function fetchAll($filters = [], $option = '') {
		$array = [];
		if (!is_array($filters)):
			throw new \Exception("Filter: '{$filters}' must be a array");
		endif;

		$sql = $this->selectSQL($this->selectCollumns) . ' ' . $this->whereSQL($filters) . ' ' . $option;
		if ($this->pdo) {
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute(array_values($filters));

			$results = $stmt->fetchAll();
			foreach ($results as $result):
				$array[] = $this->mapObject($result);
			endforeach;
		}
		return $array;
	}

	/**
	 * Retorna comando SELECT
	 * @param string $selectCollumns
	 * @return string
	 * @example "SELECT * FROM user"
	 */
	protected function selectSQL($selectCollumns = ['*']) {
		return 'SELECT ' . implode(', ', $selectCollumns) . ' FROM ' . static::TABLE;
	}

	/**
	 * Retorna comando WHERE
	 * @param string[] $filters
	 * @return string
	 */
	private function whereSQL(&$filters) {
		$keys = array_keys($filters);
		return ($keys) ? 'WHERE ' . implode(' AND ', $keys) : '';
	}

	/**
	 * Retorna o total de registros
	 * @param string[] $filters Array de filtros
	 * @return int
	 */
	public function numRows($filters = []) {
		$total = 0;
		if (!is_array($filters)):
			throw new \Exception("Filter: '{$filters}' must be a array");
		endif;

		$sql = 'SELECT count(*) as total FROM ' . static::TABLE . ' ' . $this->whereSQL($filters);
		if ($this->pdo) {
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute(array_values($filters));

			$result = $stmt->fetch();
			$total = $result['total'];
		}
		return $total;
	}

	/**
	 * Retorna True se objeto existir
	 * @return boolean
	 */
	protected function objExists() {
		return ($this->obj->getId() > 0);
	}

	/** Define como Página 404 se o objeto não existir */
	public function validateObject() {
		if (!$this->objExists()) {
			Application::app()->pageNotFound();
		}
	}

}
