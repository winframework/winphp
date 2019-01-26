<?php

namespace Win\Filesystem\Upload;

use Win\File\Directory;
use Win\File\File;

/**
 * Auxilia fazer upload de Arquivos
 */
class Uploader {

	/** @var string[] */
	protected static $validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'csv', 'doc', 'docx', 'odt', 'pdf', 'txt', 'md', 'mp3', 'wav', 'mpeg'];

	/** @var Directory */
	protected $destination;

	/** @var TempFile */
	protected $temp;

	/** @var File */
	protected $uploaded;

	/**
	 * Inicializa o upload para o diretório de destino
	 * @param Directory $destination
	 */
	public function __construct(Directory $destination) {
		$this->destination = $destination;
		$this->destination->create(0777);
	}

	/**
	 * Retorna a instância do arquivo que foi enviado
	 * @return File
	 */
	public function getUploaded() {
		return $this->uploaded;
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
			$this->uploaded = new File($this->temp->getPath());
		}
		return $success;
	}

}
