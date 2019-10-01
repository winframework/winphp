<?php

namespace Win\Models\Filesystem;

use Directory;
use Win\Models\DateTime;

/**
 * Item dentro do Diretório
 * Podendo ser outro diretório, arquivos, etc
 */
abstract class Storable
{
	const REGEXP_PATH = '@^(([a-zA-Z0-9._\-][\/]?))+$@';
	const REGEXP_NAME = '@^(([a-zA-Z0-9._\-]?))+$@';
	const DS = DIRECTORY_SEPARATOR;

	/** @var string */
	private $path;

	/** @var Directory */
	private $directory;

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

	/** @return DateTime */
	public function getLastModifiedDate()
	{
		$ts = filemtime($this->getAbsolutePath());

		return new DateTime("@$ts");
	}

	/** @param string $path */
	protected function setPath($path)
	{
		$this->path = $path;
	}

	/** @param Directory $directory */
	protected function setDirectory(Directory $directory)
	{
		$this->directory = $directory;
		$path = $directory->getPath() . static::DS . $this->getBaseName();
		$this->setPath($path);
	}

	/** @param string */
	protected function setName($name)
	{
		$path = $this->getDirectory()->getPath() . static::DS . $name;
		$this->setPath($path);
	}

	/**
	 * Renomeia
	 * @param string $newName Novo nome
	 * @return bool
	 */
	public function rename($newName)
	{
		$oldPath = $this->getAbsolutePath();
		$this->setName($newName);

		return rename($oldPath, $this->getAbsolutePath());
	}

	/**
	 * Move para um novo diretório
	 * @param Directory $destination
	 * @return bool
	 */
	public function move(Directory $destination)
	{
		$oldPath = $this->getAbsolutePath();
		$this->setDirectory($destination);
		if (!$this->getDirectory()->exists()) {
			$this->getDirectory()->create();
		}

		return rename($oldPath, $this->getAbsolutePath());
	}

	/**
	 * Define a permissão ao diretório
	 * @param int $chmod
	 * @return bool
	 */
	public function setChmod($chmod = 0755)
	{
		return @chmod($this->getAbsolutePath(), $chmod);
	}

	/** @return string */
	public function getChmod()
	{
		clearstatcache();

		return substr(decoct(fileperms($this->getAbsolutePath())), 2);
	}
}