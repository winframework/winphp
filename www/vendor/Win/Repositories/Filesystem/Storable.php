<?php

namespace Win\Repositories\Filesystem;

/**
 * Item dentro do Diretório
 * Podendo ser outro diretório, arquivos, etc
 */
abstract class Storable
{
	const REGEXP_PATH = '@^(([a-zA-Z0-9._\-][\/]?))+$@';
	const REGEXP_NAME = '@^(([a-zA-Z0-9._\-]?))+$@';
	const DS = DIRECTORY_SEPARATOR;

	private string $path;
	private ?Directory $directory = null;

	/** @return string */
	public function __toString()
	{
		return $this->getPath();
	}

	/** @return string */
	public function getPath()
	{
		return $this->path;
	}

	/** @return string */
	public function getAbsolutePath()
	{
		return BASE_PATH . static::DS . $this->path;
	}

	/**
	 * Retorna o diretório pai
	 * @return Directory
	 */
	public function getDirectory()
	{
		if (is_null($this->directory)) {
			$path = pathinfo($this->getPath(), PATHINFO_DIRNAME);
			$this->directory = new Directory($path);
		}

		return $this->directory;
	}

	/** @return string */
	public function getName()
	{
		return pathinfo($this->getAbsolutePath(), PATHINFO_FILENAME);
	}

	/** @return string */
	public function getBaseName()
	{
		return pathinfo($this->getAbsolutePath(), PATHINFO_BASENAME);
	}

	/** @return string */
	public function getUpdatedAt()
	{
		$ts = filemtime($this->getAbsolutePath());

		return date('Y-m-d H:i:s', $ts);
	}

	/** @param string $path */
	protected function setPath($path)
	{
		$this->path = $path;
	}
}
