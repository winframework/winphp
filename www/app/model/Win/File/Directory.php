<?php

namespace Win\File;

use Exception;
use const BASE_PATH;

/**
 * Diretório de Arquivos
 *
 */
class Directory {

	/** @var string */
	private $path;

	/**
	 * Instância um diretório
	 * @param string $path
	 */
	public function __construct($path) {
		$this->path = static::toAbsolutePath($path);
	}

	/** @return string */
	public function getPath() {
		return $this->path;
	}

	/** @return string */
	public function getRelativePath() {
		return str_replace(BASE_PATH . DIRECTORY_SEPARATOR, '', $this->path);
	}

	/**
	 * Converte o caminho relativo para absoluto
	 * @param $relativePath
	 * @return string
	 */
	public static function toAbsolutePath($relativePath) {
		if (!preg_match('@^(([a-z0-9._\-][\/]?))+$@', $relativePath . DIRECTORY_SEPARATOR)) {
			throw new Exception($relativePath . ' is a invalid path.');
		}
		return BASE_PATH . DIRECTORY_SEPARATOR . $relativePath;
	}

	/** @return boolean */
	public function exists() {
		return is_dir($this->path);
	}

	/**
	 * Renomeia o diretório
	 * @param string $newPath Caminho para o novo diretório
	 * @return boolean
	 */
	public function rename($newPath) {
		$newFullPath = static::toAbsolutePath($newPath);
		$oldFullPath = $this->path;
		$this->path = $newFullPath;
		return rename($oldFullPath, $newFullPath);
	}

	/**
	 * Exclui o diretório e o seu conteúdo
	 * @return boolean
	 */
	public function delete() {
		$return = false;
		if ($this->exists()) {
			$this->deleteContent();
			$return = rmdir($this->path);
		}
		return $return;
	}

	/**
	 * Exclui o conteúdo do diretório
	 */
	protected function deleteContent() {
		foreach ($this->scan() as $content) {
			if (is_dir($this->path . DIRECTORY_SEPARATOR . $content)) {
				$subDirectory = new Directory($this->getRelativePath() . DIRECTORY_SEPARATOR . $content);
				$subDirectory->delete();
			} else {
				unlink($this->path . DIRECTORY_SEPARATOR . $content);
			}
		}
	}

	/**
	 * Cria o diretório
	 * @param int $chmod
	 * @return boolean
	 */
	public function create($chmod = 0755) {
		if (!$this->exists()) {
			@mkdir($this->path, $chmod, (boolean) STREAM_MKDIR_RECURSIVE);
			$this->chmod($chmod);
		}
		return $this->exists();
	}

	/**
	 * Retorna o conteúdo do diretório
	 * @return string[]
	 */
	public function scan() {
		return array_diff(scandir($this->path), ['.', '..']);
	}

	/**
	 * Define a permissão ao diretório
	 * @param int $chmod
	 * @return boolean
	 */
	public function chmod($chmod = 0755) {
		return @chmod($this->path, $chmod);
	}

	/** @return string */
	public function getPermission() {
		clearstatcache();
		return substr(decoct(fileperms($this->path)), 2);
	}

}
