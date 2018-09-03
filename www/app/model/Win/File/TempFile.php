<?php

namespace Win\File;

/**
 * Arquivos Temporários
 *
 */
class TempFile extends File implements UploadbleInterface {

	const REGEXP_PATH = '@^(([a-zA-Z0-9._\-\/]))+$@';
	const REGEXP_NAME = '@^(([a-zA-Z0-9._\-]?))+$@';

	protected $name;
	protected $extension;

	/**
	 * Instância o arquivo temporário
	 * @param mixed[] $fileVar $_FILE['name']
	 */
	public function __construct($fileVar) {
		$path = $fileVar['tmp_name'];
		$this->name = pathinfo($fileVar['name'], PATHINFO_FILENAME);
		$this->extension = pathinfo($fileVar['name'], PATHINFO_EXTENSION);
		parent::__construct($path);
	}

	/** @return string */
	public function getName() {
		return $this->name;
	}

	/** @return string */
	public function getExtension() {
		return $this->extension;
	}

	/** @return string */
	public function getAbsolutePath() {
		return $this->getPath();
	}

	/** @param string $name */
	public function setName($name) {
		if ($name) {
			$this->name = $name;
		}
	}

	/**
	 * Move o arquivo temporário para o destino final
	 * @param \Win\File\Directory $directory
	 * @return boolean
	 */
	public function move(Directory $directory) {
		$path = $directory->getAbsolutePath() . DIRECTORY_SEPARATOR . $this->getName() . $this->getExtensionDot();
		return move_uploaded_file($this->getPath(), $path);
	}

}
