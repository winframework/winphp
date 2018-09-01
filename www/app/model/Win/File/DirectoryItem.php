<?php

namespace Win\File;

use const BASE_PATH;
use function strToURL;

/**
 * Item dentro do Diretório
 * São outros diretório, arquivos, etc
 */
abstract class DirectoryItem implements DirectoryItemInterface {

	/** @var string */
	private $path;

	/** @return string */
	public function __toString() {
		return $this->toString();
	}

	/** @return string */
	public function getPath() {
		return $this->path;
	}

	/** @param string $path */
	protected function setPath($path) {
		$this->path = $path;
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
		return new Directory(pathinfo($this->getPath(), PATHINFO_DIRNAME));
	}

	/**
	 * Renomeia
	 * @param string $newName Novo nome
	 * @return boolean
	 */
	public function rename($newName) {
		$oldPath = $this->getAbsolutePath();
		$path = $this->getDirectory()->getPath() . DIRECTORY_SEPARATOR . $newName;
		$this->setPath($path);
		return rename($oldPath, $this->getAbsolutePath());
	}

	/**
	 * Move para um novo diretório
	 * @param Directory $newDirectory
	 * @return boolean
	 */
	public function move(Directory $newDirectory) {
		$oldPath = $this->getAbsolutePath();
		$path = $newDirectory->getPath() . DIRECTORY_SEPARATOR . $this->toString();
		$this->setPath($path);
		if (!$newDirectory->exists()) {
			$newDirectory->create();
		}
		return rename($oldPath, $this->getAbsolutePath());
	}

	/**
	 * Converte uma string para um nome válido
	 * @param string $string
	 * @return string
	 */
	public static function strToValidName($string) {
		return trim(strToURL($string), '-');
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
