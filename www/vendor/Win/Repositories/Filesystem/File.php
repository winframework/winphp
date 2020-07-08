<?php

namespace Win\Repositories\Filesystem;

use Exception;
use Win\Repositories\Filesystem;

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

	/**
	 * Retorna o conteúdo do arquivo
	 * @return string|false
	 */
	public function getContent()
	{
		return (new Filesystem())->read($this->getPath());
	}
}
