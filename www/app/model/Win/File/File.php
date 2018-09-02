<?php

namespace Win\File;

use Exception;

/**
 * Arquivos
 *
 */
class File extends DirectoryItem {

	/** @var string[] */
	public static $validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'csv', 'doc', 'docx', 'odt', 'pdf', 'txt', 'md', 'mp3', 'wav', 'mpeg'];

	/**
	 * Instância um novo arquivo
	 * @param string $path Caminho relativo
	 */
	public function __construct($path) {
		$this->setPath($path);
	}

	/** @return string */
	public function getName() {
		return pathinfo($this->getAbsolutePath(), PATHINFO_FILENAME);
	}

	/** @return string */
	public function getExtension() {
		return pathinfo($this->getAbsolutePath(), PATHINFO_EXTENSION);
	}

	/** @return string */
	public function getExtensionDot() {
		return $this->getExtension() ? '.' . $this->getExtension() : '';
	}

	/** @return string */
	public function getType() {
		return mime_content_type($this->getAbsolutePath());
	}

	/** @return int|false */
	public function getSize() {
		if ($this->exists()) {
			$size = filesize($this->getAbsolutePath());
		} else {
			$size = false;
		}
		return $size;
	}

	/** @return string */
	public function toString() {
		return $this->getName() . $this->getExtensionDot();
	}

	/** @return boolean */
	public function exists() {
		return is_file($this->getAbsolutePath());
	}

	/**
	 * @param string $path Caminho relativo
	 * @throws Exception
	 */
	protected function setPath($path) {
		if (!preg_match(static::REGEXP_PATH, $path)) {
			throw new Exception($path . ' is a invalid directory path.');
		}
		parent::setPath($path);
	}

	/**
	 * Exclui o arquivo
	 * @return boolean
	 */
	public function delete() {
		return unlink($this->getAbsolutePath());
	}

	/**
	 * Salva o conteúdo no arquivo
	 * @param string $content
	 * @param string $mode
	 * @return boolean
	 */
	public function write($content, $mode = 'w') {
		$return = false;
		if (strlen($this->getName()) > 0) {
			$this->getDirectory()->create();
			$fp = fopen($this->getAbsolutePath(), $mode);
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
			$content = file_get_contents($this->getAbsolutePath());
		}
		return $content;
	}

	/**
	 * Renomeia o arquivo
	 * @param string $newName [optional]
	 * @param string $newExtension [optional]
	 * @return boolean
	 */
	public function rename($newName = null, $newExtension = null) {
		$newName .= ($newExtension) ? '.' . $newExtension : $this->getExtensionDot();
		if (!preg_match(static::REGEXP_NAME, $newName)) {
			throw new Exception($newName . ' is a invalid file name.');
		}
		return parent::rename($newName);
	}

}
