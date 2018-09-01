<?php

namespace Win\File;

use Exception;
use const BASE_PATH;

/**
 * Diretório de Arquivos
 *
 */
class Directory extends DirectoryItem {

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
		parent::setPath($path);
	}

	/** @return string */
	public function getName() {
		return pathinfo($this->getPath(), PATHINFO_BASENAME);
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
	 * Renomeia o diretório
	 * @param string $newPath
	 * @return boolean
	 */
	public function rename($newPath) {
		parent::rename($newPath);
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
				throw new Exception('The directory ' . $this->getPath() . ' could not be created.');
			}
			$this->setChmod($chmod);
		}
		return $this->exists();
	}

	/** @return boolean */
	public function isEmpty() {
		return (count($this->getItemsName()) == 0);
	}

	/**
	 * Retorna nome dos itens dentro do diretório (em ordem alfabética)
	 * @return string[]
	 */
	public function getItemsName() {
		return array_values(array_diff(scandir($this->getAbsolutePath()), ['.', '..']));
	}

	/**
	 * Retorna os itens dentro do diretório (em ordem alfabética)
	 * @return Directory[]|File[]
	 */
	public function getItems() {
		$items = [];
		foreach ($this->getItemsName() as $itemName) {
			$itemPath = $this->getPath() . DIRECTORY_SEPARATOR . $itemName;
			if (is_dir(BASE_PATH . DIRECTORY_SEPARATOR . $itemPath)) {
				$items[] = new Directory($itemPath);
			} else {
				$items[] = new File($itemPath);
			}
		}
		return $items;
	}

}
