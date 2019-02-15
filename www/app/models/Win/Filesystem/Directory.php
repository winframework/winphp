<?php

namespace Win\Filesystem;

use Exception;
use const BASE_PATH;

/**
 * Diretório de Arquivos
 *
 */
class Directory extends Storable {

	const MKDIR_MODE = STREAM_MKDIR_RECURSIVE;

	/**
	 * Instância um diretório
	 * @param string $path Caminho relativo
	 */
	public function __construct($path) {
		$this->setPath($path);
	}

	/** @return boolean */
	public function exists() {
		return is_dir($this->getAbsolutePath());
	}

	/**
	 * @param string $path Caminho relativo
	 * @throws Exception
	 */
	protected function setPath($path) {
		if (!preg_match(static::REGEXP_PATH, $path . static::DS)) {
			throw new Exception($path . ' is a invalid directory path.');
		}
		parent::setPath($path);
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
			if (is_dir($this->getAbsolutePath() . static::DS . $content)) {
				$subDirectory = new Directory($this->getPath() . static::DS . $content);
				$subDirectory->delete();
			} else {
				unlink($this->getAbsolutePath() . static::DS . $content);
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
			$success = @mkdir($this->getAbsolutePath(), $chmod, static::MKDIR_MODE);
			if (!$success) {
				$message = 'The directory ' . $this->getPath() . ' could not be created.';
				throw new Exception($message);
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
		$items = scandir($this->getAbsolutePath());
		return array_values(array_diff($items, ['.', '..']));
	}

	/**
	 * Retorna os itens dentro do diretório (em ordem alfabética)
	 * @return Storable[]
	 */
	public function getItems() {
		$items = [];
		foreach ($this->getItemsName() as $itemName) {
			$itemPath = $this->getPath() . static::DS . $itemName;
			if (is_dir(BASE_PATH . static::DS . $itemPath)) {
				$items[] = new Directory($itemPath);
			} else {
				$items[] = new File($itemPath);
			}
		}
		return $items;
	}

}
