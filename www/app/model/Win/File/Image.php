<?php

namespace Win\File;

/**
 * Arquivos de Imagem
 *
 */
class Image extends File {

	protected static $validExtensions = ['jpg', 'jpeg', 'gif', 'png'];

	/* @overwrite */

	public function upload($newName = '') {
		$error = parent::upload($newName);
		if ($this->uploadPrepared && is_null($error)) {
			$error = $this->saveThumb();
		}
		return $error;
	}

	public function saveThumb() {
		return false;
	}

	public function __toString() {
		if ($this->getName() != '') {
			return parent::__toString();
		} else {
			return $this->getFullName();
		}
	}

	public function getFullName() {
		if ($this->getName() != '') {
			return parent::getFullName();
		} else {
			return $this->getDirectory() . 'default.png';
		}
	}

	public function removeOld() {
		$this->clearCache($this->getOldName());
		parent::removeOld();
	}

	public function remove() {
		$this->clearCache($this->getFullName());
		parent::remove();
	}

	/** Limpa imagens em cache */
	public function clearCache($name) {
		if ($this->exists()) {
			$dir = 'data/cache/thumb/*/*/';
			File::removeRegExp($dir . $name);
		}
	}

}
