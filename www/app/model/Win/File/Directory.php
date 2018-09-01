<?php

namespace Win\File;

use Exception;
use const BASE_PATH;
use function strToURL;

/**
 * Diretório de Arquivos
 *
 */
class Directory implements DirectoryItemInterface {

	/** @var string Caminho relativo */
	private $path;

	const REGEXP_PATH = '@^(([a-z0-9._\-][\/]?))+$@';

	/**
	 * Instância um diretório
	 * @param string $path Caminho relativo
	 */
	public function __construct($path) {
		$this->setPath($path);
	}

	/** @return string */
	public function __toString() {
		return $this->toString();
	}

	/**
	 * @param string $path Caminho relativo
	 * @throws Exception
	 */
	protected function setPath($path) {
		if (!preg_match(static::REGEXP_PATH, $path . DIRECTORY_SEPARATOR)) {
			throw new Exception($path . ' is a invalid directory path.');
		}
		$this->path = $path;
	}

	/** @return string */
	public function getPath() {
		return $this->path;
	}

	/** @return string */
	public function getAbsolutePath() {
		return BASE_PATH . DIRECTORY_SEPARATOR . $this->path;
	}

	/** @return string */
	public function getName() {
		return pathinfo($this->path, PATHINFO_BASENAME);
	}

	/** @return string */
	public function toString() {
		return $this->getName();
	}

	/** @return boolean */
	public function exists() {
		return is_dir($this->getAbsolutePath());
	}

	/**
	 * Renomeia o diretório
	 * @param string $newPath Caminho para o novo diretório
	 * @return boolean
	 */
	public function rename($newPath) {
		$old = clone $this;
		$this->setPath($newPath);
		return rename($old->getAbsolutePath(), $this->getAbsolutePath());
	}

	/**
	 * Exclui o diretório e o seu conteúdo
	 * @return boolean
	 */
	public function delete() {
		$success = false;
		if ($this->exists()) {
			$this->clear();
			$success = rmdir($this->getAbsolutePath());
		}
		return $success;
	}

	/**
	 * Exclui apenas o conteúdo do diretório
	 */
	public function clear() {
		foreach ($this->getItemsName() as $content) {
			if (is_dir($this->getAbsolutePath() . DIRECTORY_SEPARATOR . $content)) {
				$subDirectory = new Directory($this->getPath() . DIRECTORY_SEPARATOR . $content);
				$subDirectory->delete();
			} else {
				unlink($this->getAbsolutePath() . DIRECTORY_SEPARATOR . $content);
			}
		}
	}

	/**
	 * Cria o diretório
	 * @param int $chmod
	 * @return boolean
	 * @throws Exception
	 */
	public function create($chmod = 0755) {
		if (!$this->exists()) {
			if (@mkdir($this->getAbsolutePath(), $chmod, (boolean) STREAM_MKDIR_RECURSIVE) === false) {
				throw new Exception('The directory ' . $this->path . ' could not be created.');
			}
			$this->chmod($chmod);
		}
		return $this->exists();
	}

	/** @return boolean */
	public function isEmpty() {
		return (count($this->getItemsName()) == 0);
	}

	/**
	 * Retorna o conteúdo do diretório
	 * @return string[]
	 */
	public function getItemsName() {
		return array_values(array_diff(scandir($this->getAbsolutePath()), ['.', '..']));
	}

	/**
	 * Retorna o conteúdo do diretório em Objetos
	 * @return Directory[]|File[]
	 */
	public function getItems() {
		$items = [];
		foreach ($this->getItemsName() as $itemName) {
			$itemPath = $this->getPath() . DIRECTORY_SEPARATOR . $itemName;
			if (is_dir($itemPath)) {
				$items[] = new Directory($itemPath);
			} else {
				$items[] = new File($itemPath);
			}
		}
		return $items;
	}

	/**
	 * Define a permissão ao diretório
	 * @param int $chmod
	 * @return boolean
	 */
	public function chmod($chmod = 0755) {
		return @chmod($this->getAbsolutePath(), $chmod);
	}

	/** @return string */
	public function getPermission() {
		clearstatcache();
		return substr(decoct(fileperms($this->getAbsolutePath())), 2);
	}

	/**
	 * Converte uma string para um nome de diretório válido
	 * @param string $string
	 * @return string
	 */
	public static function strToDirectoryName($string) {
		return trim(strToURL($string), '-');
	}

}
