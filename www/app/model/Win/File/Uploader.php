<?php

namespace Win\File;

/**
 * Auxilia fazer upload de Arquivos
 */
class Uploader {

	/** @var Directory */
	protected $directory;

	/** @var File */
	protected $file;

	/** @var File */
	protected $temp;

	public function __construct(Directory $directory) {
		$this->directory = $directory;
	}

	/** @return File */
	public function getFile() {
		return $this->file;
	}

	public function prepare($file) {
		$success = false;
		$this->temp = null;
		if (isset($_FILES[$file]) && isset($_FILES[$file]['name'])) {
			$success = true;
			$this->temp = new File($_FILES[$file]['tmp_name']);
		}
		return $success;
	}

	public function upload($newName = null) {
		$success = false;
		$this->file = null;
		if (!is_null($this->temp) && $this->temp->exists()) {
			$success = true;
			$this->temp->move($this->directory);
			$this->file = clone $this->temp;
			if ($newName) {
				$this->file->rename($newName);
				var_dump($this->file->getPath());
			}
		}
		return $success;
	}

}
