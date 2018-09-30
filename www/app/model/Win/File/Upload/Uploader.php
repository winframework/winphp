<?php

namespace Win\File\Upload;

use Win\File\Directory;
use Win\File\File;

/**
 * Auxilia fazer upload de Arquivos
 */
class Uploader {

	/** @var Directory */
	protected $destination;

	/** @var TempFile */
	protected $temp;

	/** @var File */
	protected $file;

	/**
	 * Instância um arquivo temporário
	 * @param Directory $destination
	 */
	public function __construct(Directory $destination) {
		$this->destination = $destination;
		$this->destination->create(0777);
	}

	/** @return File */
	public function getFile() {
		return $this->file;
	}

	/**
	 * Prepara o upload
	 * @param TempFile $temp
	 * @return boolean
	 */
	public function prepare(TempFile $temp) {
		$success = false;
		$this->temp = null;
		if ($temp->exists()) {
			$success = true;
			$this->temp = $temp;
		}
		return $success;
	}

	/**
	 * Faz o upload para o diretório final
	 * @param string $name
	 * @return boolean
	 */
	public function upload($name = '') {
		$success = false;
		if (!is_null($this->temp)) {
			$success = $this->temp->move($this->destination, $name);
		}
		if ($success) {
			$this->file = $this->temp;
		}
		return $success;
	}

}
