<?php

namespace Win\Core\Filesystem;

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
	 * @param Directory $destination
	 * @param string $name
	 * @return bool
	 */
	public function move(Directory $destination, $name = '')
	{
		$this->setName($name);
		$oldPath = $this->getAbsolutePath();
		$newPath = $destination->getPath() . static::DS . $this->getBaseName();
		$this->setPath($newPath);

		return move_uploaded_file($oldPath, $this->getAbsolutePath());
	}

	/**
	 * Cria uma instância a partir da variável $_FILES
	 * @param string $name
	 * @return static
	 */
	public static function fromFiles($name)
	{
		if (key_exists($name, $_FILES) && key_exists('tmp_name', $_FILES[$name])) {
			$tmp = new static($_FILES[$name]['tmp_name']);
			$tmp->newName = $_FILES[$name]['name'];
			$tmp->setExtension(pathinfo($_FILES[$name]['name'], PATHINFO_EXTENSION));
		} else {
			$tmp = new TempFile('error');
		}

		return $tmp;
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
