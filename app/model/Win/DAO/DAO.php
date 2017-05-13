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
	protected $join = '';
	protected $primaryKey = null;
	protected $fixedFilter = [];

	/** @var boolean */
	public static $debug = false;

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
			$error = $this->beforeSave();
			$mode = (!$this->objExists($obj)) ? 'insert' : 'update';
			
			if ($mode == 'insert' && is_null($error)) {
				$error = $this->insert();
				$this->obj->setId($this->pdo->lastInsertId());
			} elseif (is_null($error)) {
				$error = $this->update();
			}
			
			if (is_null($error)) {
				$error = $this->afterSave();
			}
			if (!is_null($error) && $mode == 'insert') {
				$this->delete($obj);
			}
		}
		return $error;
	}

	/**
	 * Executa SQL via PDO
	 * @param string $sql
	 * @param mixed[] $values
	 * @return \PDOStatement
	 */
	protected function execSql($sql, $values) {
		if ($this->pdo) {
			$this->debug($sql, $values);
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute($values);
			return $stmt;
		}
	}

	/** Insere o registro */
	protected function insert() {
		$mapRow = static::mapRow($this->obj);
		$keys = array_keys($mapRow);
		$params = str_split(str_repeat('?', count($keys)));

		$sql = 'INSERT INTO ' . static::TABLE . ' (' . implode(',', $keys) . ') VALUES (' . implode(', ', $params) . ') ';
		$stmt = $this->execSql($sql, array_values($mapRow));
		return $this->error($stmt);
	}

	/** Atualiza o registro */
	protected function update() {
		$mapRow = static::mapRow($this->obj);
		$keys = array_keys($mapRow);
		$values = array_values($mapRow);
		$values[] = $this->obj->getId();
		$params = [];

		foreach ($keys as $key):
			$params[] = $key . ' = ?';
		endforeach;

		$sql = 'UPDATE ' . static::TABLE . ' SET ' . implode(', ', $params) . ' WHERE ' . $this->getPrimaryKey() . ' = ? ';
		$stmt = $this->execSql($sql, $values);
		return $this->error($stmt);
	}

	/**
	 * @param $stmt \PDOStatement
	 * @return string erro
	 */
	protected function error(\PDOStatement $stmt) {
		$error = null;
		if ($stmt->errorCode() !== '00000') {
			$error = 'Houve um erro ao salvar o registro. [Erro ' . $stmt->errorCode() . ']';
			if (Application::app()->isLocalHost()) {
				$error .= '<br /><small>' . $stmt->errorInfo()[2] . '</small>';
			}
		}
		return $error;
	}

	/**
	 * Exclui o registro
	 * @param object $obj
	 */
	public function delete($obj) {
		$this->obj = $obj;
		$this->onDelete();
		$filters = [$this->getPrimaryKey() . ' = ?' => $obj->getId()];
		$sql = 'DELETE FROM ' . static::TABLE . ' ' . $this->whereSQL($filters);
		$this->execSql($sql, $this->getFilterValues($filters));
	}

	/**
	 * Exclui o registro por id
	 * @param int $id
	 */
	public function deleteById($id) {
		$this->deleteByField($this->getPrimaryKey() . '', $id);
	}

	/**
	 * Exclui o registro por id
	 * @param string $name
	 * * @param mixed $value
	 */
	public function deleteByField($name, $value) {
		$this->deleteAll([$name . ' = ?' => $value]);
	}

	/**
	 * Exclui todos os registros
	 * @param mixed[] $filters
	 */
	public function deleteAll($filters = []) {
		$fixed = $this->fixedFilter;
		$this->fixedFilter = [];

		$objList = $this->fetchAll($filters);
		foreach ($objList as $obj):
			$this->delete($obj);
		endforeach;

		$this->fixedFilter = $fixed;
	}

	/**
	 * Busca o objeto pelo id
	 * @param int $id
	 */
	public function fetchById($id) {
		return $this->fetchByField($this->getPrimaryKey() . '', $id);
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
	public function fetch($filters, $option = 'ORDER BY 1 DESC') {
		if (!is_array($filters)):
			throw new \Exception("Filter: '{$filters}' must be a array");
		endif;
		$sql = $this->selectSQL($this->selectCollumns) . ' ' . ' ' . $this->whereSQL($filters) . ' ' . $option;
		$result = [];
		if ($this->pdo) {
			$stmt = $this->execSql($sql, $this->getFilterValues($filters));
			$result = $stmt->fetch();
		}
		return static::mapObject($result);
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
	public function fetchAll($filters = [], $option = 'ORDER BY 1 DESC') {
		$array = [];
		if (!is_array($filters)):
			throw new \Exception("Filter: '{$filters}' must be a array");
		endif;

		$sql = $this->selectSQL($this->selectCollumns) . ' ' . $this->whereSQL($filters) . ' ' . $option;

		if ($this->pdo) {
			$stmt = $this->execSql($sql, $this->getFilterValues($filters));

			$results = $stmt->fetchAll();
			foreach ($results as $result):
				$array[] = static::mapObject($result);
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
		return 'SELECT ' . implode(', ', $selectCollumns) . ' FROM ' . static::TABLE . ' ' . $this->join;
	}

	/**
	 * Retorna comando WHERE
	 * @param string[] $filters
	 * @return string
	 */
	protected function whereSQL(&$filters) {
		$keys = array_keys($filters + $this->fixedFilter);
		return ($keys) ? 'WHERE ' . implode(' AND ', $keys) : '';
	}

	/**
	 * Retorna o total de registros
	 * @param string[] $filters Array de filtros
	 * @param string $option
	 * @return int
	 */
	public function numRows($filters = [], $option = 'ORDER BY 1 DESC') {
		$total = 0;
		if (!is_array($filters)):
			throw new \Exception("Filter: '{$filters}' must be a array");
		endif;

		$sql = 'SELECT count(*) as total FROM ' . static::TABLE . ' ' . $this->join . ' ' . $this->whereSQL($filters) . ' ' . $option;

		if ($this->pdo) {
			$stmt = $this->execSql($sql, $this->getFilterValues($filters));
			$result = $stmt->fetch();
			$total = $result['total'];
		}
		return (int) $total;
	}

	/**
	 * Retorna True se objeto existir
	 * @return boolean
	 */
	protected function objExists($obj) {
		return ($this->numRows([$this->getPrimaryKey() . ' = ?' => $obj->getId()]));
	}

	/** Define como Página 404 se o objeto não existir */
	public function checkFoundRegistry($obj) {
		if (!$this->objExists($obj)) {
			Application::app()->pageNotFound();
			Application::app()->controller->reload();
		}
	}

	/**
	 * Exibe comando SQL, se debug está habilitado
	 * @param string $sql
	 * @param mixed[] $values
	 */
	protected function debug($sql, $values = []) {

		if (static::$debug) {
			foreach ($values as $value):
				$sql = preg_replace('/\?/', '<b style="color:#D22;">"' . $value . '"</b>', $sql, 1);
			endforeach;

			$find = [' WHERE ', ' ' . static::TABLE . ' '];
			$replace = [' <b style="color:#22D">WHERE</b> ', ' </b>' . static::TABLE . ' '];
			echo '<pre><b>' . str_replace($find, $replace, $sql) . '</pre>';
		}
	}

	private function getFilterValues($filters) {
		return array_values($filters + $this->fixedFilter);
	}

	protected function beforeSave() {
		
	}

	protected function afterSave() {
		
	}

	protected function onDelete() {
		
	}

	/** @return string Retorna o nome da PK */
	private function getPrimaryKey() {
		if (is_null($this->primaryKey)) {
			$this->primaryKey = static::TABLE . '_id';
		}
		return $this->primaryKey;
	}

}
