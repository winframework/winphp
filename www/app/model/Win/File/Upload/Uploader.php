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

	/** @var UploadbleInterface */
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
	 * @param UploadbleInterface $temp
	 * @return boolean
	 */
	public function prepare(UploadbleInterface $temp) {
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
	 * @param $name
	 * @return boolean
	 */
	public function upload($name = null) {
		$success = false;
		if (!is_null($this->temp)) {
			$this->temp->setName($name);
			$success = $this->moveTempToDestination();
		}
		return $success;
	}

	/**
	 * Move o arquivo temporário para o destino final
	 * @return boolean
	 */
	protected function moveTempToDestination() {
		return $this->temp->move($this->destination);
	}

	/**
	 * Gera um nome aleatório antes de realizar o upload
	 * @return this
	 */
	public function genarateName() {
		$this->temp->setName((string) time());
		return $this;
	}

}
