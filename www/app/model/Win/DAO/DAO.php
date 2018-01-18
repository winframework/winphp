<?php

namespace Win\DAO;

use Exception;
use PDO;
use PDOException;
use PDOStatement;
use Win\Alert\AlertError;
use Win\Connection\Mysql;
use Win\Mvc\Application;
use Win\Request\Server;

/**
 * Data Access Object
 */
abstract class DAO implements DAOInterface {

	/** @var PDO */
	protected $pdo;

	/** @var string */
	protected $primaryKey = null;

	/** @var string[] */
	protected $selectCollumns = ['*'];

	/** @var string[] */
	protected $joins = [];

	/** @var Where */
	protected $where;

	/** @var Option */
	protected $option;

	/** @var Pagination */
	protected $pagination;

	/** @var boolean */
	protected static $debug = false;
	protected static $instance = [];

	/**
	 * Valida os campos retornando string de Erro ou Null
	 * @return string|null
	 */
	abstract protected function validate();

	/**
	 * Retorna a instancia do DAO
	 * @return static
	 */
	public static function instance() {
		$class = get_called_class();
		if (!isset(static::$instance[$class])):
			static::$instance[$class] = new $class();
		endif;
		return static::$instance[$class];
	}

	/** Inicia o DAO */
	final public function __construct() {
		$this->pdo = Mysql::instance()->getPDO();
		$this->primaryKey = static::TABLE . '_id';
		$this->option = new Option();
		$this->pagination = new Pagination();
		$this->where = new Where();
	}

	public function pagination() {
		return $this->pagination;
	}

	/**
	 * Define uma conexão manualmente
	 * @param PDO $pdo
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

	/** Insere o registro */
	protected function insert() {
		$mapRow = static::mapRow($this->obj);
		$keys = array_keys($mapRow);
		$params = str_split(str_repeat('?', count($keys)));

		$sql = 'INSERT INTO ' . static::TABLE . ' (' . implode(',', $keys) . ') VALUES (' . implode(', ', $params) . ') ';
		$stmt = $this->exec($sql, array_values($mapRow));
		return $this->errorSql($stmt);
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

		$sql = 'UPDATE ' . static::TABLE . ' SET ' . implode(', ', $params) . ' WHERE ' . $this->primaryKey . ' = ? ';
		$stmt = $this->exec($sql, $values);
		return $this->errorSql($stmt);
	}

	/**
	 * Exclui o registro
	 * @param object $obj
	 * @param mixed[] $filters
	 */
	public function delete($obj) {
		$this->obj = $obj;
		$this->onDelete();
		$where = new Where();
		$where->filter($this->primaryKey . ' = ?', [$obj->getId()]);
		$sql = 'DELETE FROM ' . static::TABLE . ' ' . $this->where->toSql();
		$this->exec($sql, $this->where->values());
	}

	/**
	 * Exclui o registro por id
	 * @param int $id
	 */
	public function deleteById($id) {
		$this->deleteByField($this->primaryKey . '', $id);
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
		$fixed = $this->fixedFilters;
		$this->fixedFilters = [];

		$objList = $this->fetchAll($filters);
		foreach ($objList as $obj):
			$this->delete($obj);
		endforeach;

		$this->fixedFilters = $fixed;
	}

	/**
	 * Executa SQL via PDO
	 * @param string $sql
	 * @param mixed[] $values
	 * @return PDOStatement
	 */
	protected function exec($sql, $values) {
		if ($this->pdo) {
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->showDebug($sql, $values);
			$stmt = $this->pdo->prepare($sql);
			try {
				$stmt->execute($values);
			} catch (PDOException $e) {
				$alert = new AlertError($this->errorSql($stmt, $e));
				//$alert->load();
			}
			return $stmt;
		}
	}

	/**
	 * @param $stmt \PDOStatement
	 * @param PDOException|null $e
	 * @return string erro
	 */
	protected function errorSql(PDOStatement $stmt, PDOException $e = null) {
		$error = null;
		if ($stmt->errorCode() !== '00000') {
			$error = 'Houve um durante a execução do comando SQL. [Erro ' . $stmt->errorCode() . ']';
			if ($e instanceof PDOException) {
				$error .= '<br /><small>' . $e->getMessage() . '</small>';
			} elseif (Server::isLocalHost()) {
				$error .= '<br /><small>' . $stmt->errorInfo()[2] . '</small>';
			}
		}
		return $error;
	}

	/**
	 * Busca o objeto pelo id
	 * @param int $id
	 */
	public function fetchById($id) {
		return $this->fetchByField($this->primaryKey . '', $id);
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
	 */
	public function fetch($filters) {
		$this->validateArray($filters);

		$this->addFilters($filters);
		$sql = $this->selectSQL($this->selectCollumns) . $this->where->toSql() . $this->option->toSql();

		$result = [];
		if ($this->pdo) {
			$stmt = $this->exec($sql, $this->where->values());
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
	 */
	public function fetchAll($filters = []) {
		$this->validateArray($filters);

		$this->addFilters($filters);
		$this->pagination->setTotal($this->numRows());
		$sql = $this->selectSQL($this->selectCollumns) . $this->where->toSql() . $this->option->toSql() . $this->pagination->toSql();

		$array = [];
		if ($this->pdo) {
			$stmt = $this->exec($sql, $this->where->values());

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
		return 'SELECT ' . implode(', ', $selectCollumns) . ' FROM ' . static::TABLE . implode(' ', $this->joins);
	}

	/**
	 * Adiciona o array de filtros
	 * @param mixed[] $filters
	 */
	private function addFilters($filters = []) {
		$this->filter(implode(' AND ', array_keys($filters)), $filters);
	}

	/**
	 * Filtra a proxima busca
	 * @param string $condition
	 * @param mixed[] $values
	 */
	public function filter($condition, $values) {
		$this->where->filter($condition, $values);
	}

	/**
	 * Adiciona opções de busca
	 * @param string $option
	 */
	public function option($option) {
		$this->option->set($option);
	}

	/**
	 * Define a paginação dos itens
	 * @param int $totalPerPage
	 * @param int $currentPage
	 */
	public function paginate($totalPerPage, $currentPage = null) {
		$this->pagination = new Pagination($totalPerPage, $currentPage);
	}


	/**
	 * Retorna o total de registros
	 * @param string[] $filters Array de filtros
	 * @return int
	 */
	public function numRows($filters = []) {
		$this->validateArray($filters);

		$this->addFilters($filters);
		$sql = 'SELECT count(*) as total FROM ' . static::TABLE . implode(' ', $this->joins) . $this->where->toSql() . $this->option->toSql();

		$total = 0;
		if ($this->pdo) {
			$stmt = $this->exec($sql, $this->where->values());
			$result = $stmt->fetch();
			$total = $result['total'];
		}
		return (int) $total;
	}

	/**
	 * Retorna True se objeto existir
	 * @param mixed $obj
	 * @return boolean
	 */
	public function objExists($obj) {
		return ($obj->getId() > 0 && $this->numRows([$this->primaryKey . ' = ?' => $obj->getId()]));
	}

	/** Define como Página 404 se o objeto não existir */
	public function checkFoundRegistry($obj) {
		if (!$this->objExists($obj)) {
			Application::app()->pageNotFound();
			Application::app()->controller->reload();
		}
	}

	private function validateArray($filters) {
		if (!is_array($filters)):
			throw new Exception("Filter: '{$filters}' must be a array");
		endif;
	}

	/** Habilita o modo debug */
	final public static function debug() {
		static::$debug = 1;
	}

	/**
	 * Exibe comando SQL, se debug está habilitado
	 * @param string $sql
	 * @param mixed[] $values
	 */
	protected function showDebug($sql, $values) {

		if (static::$debug) {
			foreach ($values as $value):
				$sql = preg_replace('/\?/', '<b style="color:#D22;">"' . $value . '"</b>', $sql, 1);
			endforeach;

			$find = [' WHERE ', ' ' . static::TABLE . ' '];
			$replace = [' <b style="color:#22D">WHERE</b> ', ' </b>' . static::TABLE . ' '];
			echo '<pre><b>' . str_replace($find, $replace, $sql) . '</pre>';
		}
	}

	protected function beforeSave() {
		
	}

	protected function afterSave() {
		
	}

	protected function onDelete() {
		
	}

}
