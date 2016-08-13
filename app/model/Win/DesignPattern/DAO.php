<?php

namespace Win\DesignPattern;

/**
 * Data Access Object
 */
abstract class DAO {

	/** @var \PDO */
	protected $pdo;

	/** Inicia o DAO */
	public function __construct() {
		$this->pdo = \Win\Connection\MySQL::instance()->getPDO();
	}

	/**
	 * Define uma conexão manualmente
	 * @param \PDO $pdo
	 */
	public function setPDO($pdo) {
		$this->pdo = $pdo;
	}

	/**
	 * Retorna um objeto a partir da linha da tabela
	 * @param mixed[] $row
	 */
	abstract public function mapObject(array $row);

	/** Retorna a linha da tabela a partir de um objeto
	 * @param object $obj
	 */
	abstract public function mapRow($obj);

	/**
	 * Salva o registro
	 * @param object $obj
	 */
	public function save($obj) {
		$this->obj = $obj;
		if (!$this->objExists()) {
			$this->insert();
			$this->obj->setId($this->pdo->lastInsertId());
		} else {
			$this->update();
		}
	}

	/** Insere registro no banco de dados */
	protected function insert() {
		$mapRow = $this->mapRow($this->obj);
		$keys = array_keys($mapRow);
		$values = array_values($mapRow);
		$params = str_split(str_repeat('?', count($keys)));

		$sql = 'INSERT INTO ' . static::TABLE . ' (' . implode(',', $keys) . ') VALUES (' . implode(', ', $params) . ') ';
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($values);
	}

	/** Atualiza registro no banco de dados */
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
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($values);
	}

	/**
	 * Exclui o registro
	 * @param mixed $obj
	 */
	public function delete($obj) {
		$this->deleteById($obj->getId());
	}

	/**
	 * Exclui o registro por Id
	 * @param int $id
	 */
	public function deleteById($id) {
		$sql = 'DELETE FROM ' . static::TABLE . ' WHERE id = :id';
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindValue(':id', $id);
		$stmt->execute();
	}

	/**
	 * Busca o objeto pelo id
	 * @param int $id
	 * @return mixed
	 */
	public function fetchById($id) {
		return $this->fetchByField('id', $id);
	}

	/**
	 * Busca o objeto por um campo específico
	 * @param string $fieldName
	 * @param mixed $fieldValue
	 * @return mixed
	 */
	public function fetchByField($fieldName, $fieldValue) {
		return $this->fetch([$fieldName . ' = ?' => $fieldValue]);
	}

	/**
	 * Busca o objeto pelo filtro
	 * @param string $fieldName
	 * @param mixed $fieldValue
	 * @return mixed
	 */
	public function fetch($filter) {
		if (!is_array($filter)):
			throw new \Exception("Filter: '{$filter}' must be a array");
		endif;
		$sql = $this->selectSQL($filter);
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(array_values($filter));

		$result = $stmt->fetch();
		return $this->mapObject($result);
	}

	/**
	 * Retorna todos os registros
	 *
	 * É possivel utilizar filtragem
	 * @example fetchAll(['id = ?' => 10, 'data > ?' => '2010-01-01'])
	 * 
	 * @param string[] $filter Array com filtros
	 * @return object[] Array de objetos
	 */
	public function fetchAll($filter = []) {
		if (!is_array($filter)):
			throw new \Exception("Filter: '{$filter}' must be a array");
		endif;

		$sql = $this->selectSQL($filter);
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(array_values($filter));

		$results = $stmt->fetchAll();
		$array = [];
		foreach ($results as $result):
			$array[] = $this->mapObject($result);
		endforeach;
		return $array;
	}

	/** Retorna comando Select */
	protected function selectSQL(&$filter) {
		$keys = array_keys($filter);
		return 'SELECT * FROM ' . static::TABLE . ' ' . $this->whereSQL($keys) . '';
	}

	/**
	 * Retorna WHERE com base nas keys
	 * @param string[] $keys
	 * @return string
	 */
	private function whereSQL(&$keys) {
		return ($keys) ? 'WHERE ' . implode(' AND ', $keys) : '';
	}

	/**
	 * Retorna true se objeto existe
	 * @return boolean
	 */
	public function objExists() {
		return ($this->obj->getId() > 0);
	}

	/**
	 * Define pagina 404 se objeto não existir
	 */
	public function validateObject() {
		if ($this->obj->getId() == 0) {
			\Win\Mvc\Application::app()->pageNotFound();
		}
	}

}
