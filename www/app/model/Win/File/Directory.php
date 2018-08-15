<?php

namespace Win\File;

use Exception;
use Win\Request\Server;

/**
 * Diretório de arquivos
 */
class Directory {

	private $path;

	public function __construct($path) {
		$this->path = str_replace(['///', '//'], ['/', '/'], $path . '/');
	}

	public function __toString() {
		return $this->path;
	}

	public function getPath() {
		return $this->path;
	}

	public function setPath($path) {
		$this->path = $path;
	}

	/**
	 * Cria o diretório para salvar a imagem
	 * @param string $pathetorio Caminho do novo diretório
	 * @param int $chmod permissões deste diretório
	 * @return boolean Retorna TRUE caso obtenha algum sucesso
	 */
	public function create($chmod = 0755) {
		if (!file_exists($this->path)) {
			$success = @mkdir($this->path, $chmod, STREAM_MKDIR_RECURSIVE);
			return $success;
		}
		if (Server::isLocalHost()) {
			$success = @chmod($this->path, 0777);
			if (!$success) {
				throw new Exception('Changing permission (chmod) is not allowed on directory: "' .
				$this->path . '". Remove this directory and try again.');
			}
		}
		return false;
	}

	/**
	 * Renomeia o diretório
	 * @param string $newName Caminho para o novo diretório
	 * @return boolean
	 */
	public function rename($newName) {
		if ($this->path != $newName) {
			rename($this->path, $newName);
			return true;
		}
		return false;
	}

	/**
	 * Exclui o diretório e os arquivos dentro dele
	 * @param string $path
	 * @return boolean
	 */
	public function remove() {
		$path = str_replace('//', '/', $this->path);
		if (is_dir($path)) {
			$this->removeAllFiles();
			return rmdir($path);
		} else {
			return false;
		}
	}

	/** Exclui os arquivos deste diretório */
	protected function removeAllFiles() {
		$path = str_replace('//', '/', $this->path);
		$files = array_diff(scandir($path), ['.', '..']);
		foreach ($files as $file) {
			if (is_dir($path . '/' . $file)) {
				$subDirectory = new Directory($path . '/' . $file);
				$subDirectory->remove();
			} else {
				unlink($path . '/' . $file);
			}
		}
	}

}
