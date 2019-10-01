<?php

namespace Win\Filesystem\Models;

use Exception;

/**
 * Arquivo
 */
class File extends Storable
{
	/**
	 * Instância um novo arquivo
	 * @param string $path Caminho relativo
	 */
	public function __construct($path)
	{
		$this->setPath($path);
	}

	/** @return string */
	public function getExtension()
	{
		return pathinfo($this->getAbsolutePath(), PATHINFO_EXTENSION);
	}

	/** @return string */
	protected function getExtensionDot()
	{
		return $this->getExtension() ? '.' . $this->getExtension() : '';
	}

	/** @return string */
	public function getType()
	{
		return mime_content_type($this->getAbsolutePath());
	}

	/** @return int|false */
	public function getSize()
	{
		$size = false;
		if ($this->exists()) {
			$size = filesize($this->getAbsolutePath());
		}

		return $size;
	}

	/** @return bool */
	public function exists()
	{
		return is_file($this->getAbsolutePath());
	}

	/**
	 * @param string $path Caminho relativo
	 * @throws Exception
	 */
	protected function setPath($path)
	{
		if (!preg_match(static::REGEXP_PATH, $path)) {
			throw new Exception($path . ' is a invalid file path.');
		}
		parent::setPath($path);
	}

	/** @param string $name */
	protected function setName($name)
	{
		if ($name) {
			if (!preg_match(static::REGEXP_NAME, $name)) {
				throw new Exception($name . ' is a invalid file name.');
			}
			$relativePath = static::DS . $name . $this->getExtensionDot();
			$this->setPath($this->getDirectory()->getPath() . $relativePath);
		}
	}

	/** @param string $extension */
	protected function setExtension($extension)
	{
		if ($extension) {
			$relativePath = static::DS . $this->getName() . '.' . $extension;
			$this->setPath($this->getDirectory()->getPath() . $relativePath);
		}
	}

	/**
	 * Retorna o conteúdo do arquivo
	 * @return string|false
	 */
	public function getContent()
	{
		$content = false;
		if ($this->exists()) {
			$content = file_get_contents($this->getAbsolutePath());
		}

		return $content;
	}
}