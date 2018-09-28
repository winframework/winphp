<?php

namespace Win\File\Upload;

use Win\File\Directory;
use Win\File\File;

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
	 * @param string $path
	 * @param string $name
	 */
	public function __construct($path) {
		$this->name = pathinfo($path, PATHINFO_FILENAME);
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
	 * @param Directory $directory
	 * @return boolean
	 */
	public function move(Directory $directory) {
		$path = $directory->getAbsolutePath() . DIRECTORY_SEPARATOR . $this->getName() . $this->getExtensionDot();
		return move_uploaded_file($this->getPath(), $path);
	}

	/**
	 * Cria uma instância a partir da variável $_FILES
	 * @param type $name
	 * @return static|null
	 */
	public static function fromFiles($name) {
		if (key_exists($name, $_FILES) && key_exists('tmp_name', $_FILES[$name])) {
			$tmp = new static($_FILES[$name]['tmp_name']);
			$tmp->setExtension(pathinfo($name, PATHINFO_EXTENSION));
		} else {
			$tmp = new TempFile('/tmp/000');
		}
		return $tmp;
	}

	/**
	 * Cria um arquivo no diretório temporário
	 * @param string $prefixName prefixName
	 * @param string $content
	 * @return static
	 */
	public static function create($prefixName, $content = '') {
		$tmpfname = tempnam('/tmp', $prefixName);
		$handle = fopen($tmpfname, 'w');
		fwrite($handle, $content);
		return new static($tmpfname);
	}

}
