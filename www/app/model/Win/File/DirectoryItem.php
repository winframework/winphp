<?php

namespace Win\File;

use const BASE_PATH;

/**
 * Item dentro do Diretório
 * São outros diretório, arquivos, etc
 */
abstract class DirectoryItem implements DirectoryItemInterface {

	const REGEXP_PATH = '@^(([a-z0-9._\-][\/]?))+$@';
	const REGEXP_NAME = '@^(([a-z0-9._\-]?))+$@';

	/** @var string */
	private $path;

	/** @var Directory */
	private $directory;

	/** @return string */
	public function __toString() {
		return $this->toString();
	}

	/** @return string */
	public function getPath() {
		return $this->path;
	}

	/** @return string */
	public function getAbsolutePath() {
		return BASE_PATH . DIRECTORY_SEPARATOR . $this->path;
	}

	/**
	 * Retorna o diretório pai
	 * @return Directory
	 */
	public function getDirectory() {
		if (is_null($this->directory)) {
			$this->directory = new Directory(pathinfo($this->getPath(), PATHINFO_DIRNAME));
		}
		return $this->directory;
	}

	/** @param string $path */
	protected function setPath($path) {
		$this->path = $path;
	}

	protected function setDirectory(Directory $directory) {
		$this->directory = $directory;
		$path = $directory->getPath() . DIRECTORY_SEPARATOR . $this->toString();
		$this->setPath($path);
	}

	/** @param string */
	protected function setName($name) {
		$path = $this->getDirectory()->getPath() . DIRECTORY_SEPARATOR . $name;
		$this->setPath($path);
	}

	/**
	 * Renomeia
	 * @param string $newName Novo nome
	 * @return boolean
	 */
	public function rename($newName) {
		$oldPath = $this->getAbsolutePath();
		$this->setName($newName);
		return rename($oldPath, $this->getAbsolutePath());
	}

	/**
	 * Move para um novo diretório
	 * @param Directory $newDirectory
	 * @return boolean
	 */
	public function move(Directory $newDirectory) {
		$oldPath = $this->getAbsolutePath();
		$this->setDirectory($newDirectory);
		if (!$this->getDirectory()->exists()) {
			$this->getDirectory()->create();
		}
		return rename($oldPath, $this->getAbsolutePath());
	}

	/**
	 * Define a permissão ao diretório
	 * @param int $chmod
	 * @return boolean
	 */
	public function setChmod($chmod = 0755) {
		return @chmod($this->getAbsolutePath(), $chmod);
	}

	/** @return string */
	public function getChmod() {
		clearstatcache();
		return substr(decoct(fileperms($this->getAbsolutePath())), 2);
	}

}
