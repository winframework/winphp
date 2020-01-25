<?php

namespace Win\Models\Filesystem;

/**
 * Arquivo Temporário
 */
class TempFile extends File
{
	const REGEXP_PATH = '@^(([a-zA-Z0-9._\-\/]))+$@';
	const REGEXP_NAME = '@^(([a-zA-Z0-9._\-]?))+$@';

	protected $name;
	protected $baseName;
	protected $extension;
	protected $newName;

	/**
	 * Instância o arquivo temporário
	 * @param string $path
	 */
	public function __construct($path)
	{
		$this->name = pathinfo($path, PATHINFO_FILENAME);
		parent::__construct($path);
	}

	/** @return string */
	public function getName()
	{
		return $this->name;
	}

	/** @return string */
	public function getBaseName()
	{
		return $this->getName() . $this->getExtensionDot();
	}

	/** @return string */
	public function getExtension()
	{
		return $this->extension;
	}

	/** @param string $name */
	public function setName($name)
	{
		$this->name = ($name) ? $name : $this->randomName();
	}

	/** @param string $extension */
	public function setExtension($extension)
	{
		$this->extension = $extension;
	}

	/** @return string */
	public function getAbsolutePath()
	{
		if ($this->isTemporary()) {
			return $this->getPath();
		} else {
			return parent::getAbsolutePath();
		}
	}

	/** @return bool */
	public function isTemporary()
	{
		return 0 === strpos($this->getPath(), sys_get_temp_dir());
	}

	/** Retorna um nome aleatório */
	protected function randomName()
	{
		return md5($this->newName . '_' . time());
	}

	/**
	 * Cria um arquivo no diretório temporário
	 * @param string $prefixName prefixName
	 * @return static
	 */
	public static function create($prefixName = '')
	{
		$fileName = tempnam(null, $prefixName);

		return new static($fileName);
	}
}
