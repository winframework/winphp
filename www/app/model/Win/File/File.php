<?php

namespace Win\File;

use Exception;
use const BASE_PATH;

/**
 * Arquivos
 *
 */
class File {

	/** @var Directory */
	private $directory;

	/** @var string */
	private $name;

	/** @var string */
	private $extension;

	/** @var string[] */
	public static $validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'csv', 'doc', 'docx', 'odt', 'pdf', 'txt', 'md', 'mp3', 'wav', 'mpeg'];

	const VALIDATE_PATH = '@^(([a-z0-9._\-][\/]?))+$@';
	const VALIDATE_NAME = '@^(([a-z0-9._\-]?))+$@';

	/**
	 * Instância um novo arquivo
	 * @param string $path
	 */
	public function __construct($path) {
		if (!preg_match(static::VALIDATE_PATH, $path)) {
			throw new Exception($path . ' is a invalid file path.');
		}
		$this->directory = new Directory(dirname($path));
		$this->extension = pathinfo($path, PATHINFO_EXTENSION);
		$this->name = basename(BASE_PATH . DIRECTORY_SEPARATOR . $path, '.' . $this->extension);
	}

	/** @return string */
	public function __toString() {
		return $this->toString();
	}

	/** @return string */
	public function toString() {
		if ($this->extension) {
			return $this->name . '.' . $this->extension;
		}
		return $this->name;
	}

	/** @return string */
	public function getName() {
		return $this->name;
	}

	/** @return string */
	public function getExtension() {
		return $this->extension;
	}

	/** @return int|false */
	public function getSize() {
		if ($this->exists()) {
			$size = filesize($this->getPath());
		} else {
			$size = false;
		}
		return $size;
	}

	/** @return Directory */
	public function getDirectory() {
		return $this->directory;
	}

	/** @return string */
	public function getPath() {
		return (BASE_PATH . DIRECTORY_SEPARATOR . $this->getDirectory()->getRelativePath() .
				DIRECTORY_SEPARATOR . $this->toString());
	}

	/** @return string */
	public function getRelativePath() {
		return $this->getDirectory()->getRelativePath() . DIRECTORY_SEPARATOR . $this->toString();
	}

	/** @return boolean */
	public function exists() {
		return is_file($this->getPath());
	}

	/**
	 * Exclui o arquivo
	 * @return boolean
	 */
	public function delete() {
		return unlink($this->getPath());
	}

	/**
	 * Salva o conteúdo no arquivo
	 * @param string $content
	 * @param string $mode
	 * @return boolean
	 */
	public function write($content, $mode = 'a') {
		$return = false;
		if (strlen($this->getName()) > 0) {
			$this->directory->create();
			$fp = fopen($this->getPath(), $mode);
			if ($fp !== false) {
				fwrite($fp, $content);
				$return = fclose($fp);
			}
		}
		return $return;
	}

	/**
	 * Retorna o conteúdo do arquivo
	 * @param string|false $content
	 */
	public function read() {
		$content = false;
		if ($this->exists()) {
			$content = file_get_contents($this->getPath());
		}
		return $content;
	}

	/**
	 * Move o arquivo para um novo diretório
	 * @param Directory $newDirectory
	 * @return boolean
	 */
	public function move(Directory $newDirectory) {
		$oldPath = $this->getPath();
		$this->directory = $newDirectory;
		return rename($oldPath, $this->getPath());
	}

	/**
	 * Renomeia o arquivo
	 * @param string $newName
	 * @return boolean
	 */
	public function rename($newName) {
		if (!preg_match(static::VALIDATE_NAME, $newName)) {
			throw new Exception($newName . ' is a invalid file name.');
		}
		$oldPath = $this->getPath();
		$this->name = $newName;
		return rename($oldPath, $this->getPath());
	}

}
