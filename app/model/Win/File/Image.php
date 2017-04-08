<?php

namespace Win\File;

use \PhpThumbFactory;
/**
 * Arquivos
 *
 */
class Image extends File {

	static $phpThumbLib = '/../../../../lib/thumb/ThumbLib.inc.php';

	const QUALITY = 70;
	const MAX_HEIGHT = 900;
	const MAX_WIDTH = 1200;

	private $subtitle;
	private $order;
	protected static $validExtensions = ['jpg', 'jpeg', 'gif', 'png'];

	/* @overwrite */

	public function __construct($name = '') {
		parent::__construct($name);
		$this->subtitle = '';
		$this->order = 0;
	}

	/* METODOS DE ACESSO */

	public function getSubtitle() {
		return $this->subtitle;
	}

	public function getOrder() {
		return $this->order;
	}

	public function setSubtitle($subtitle) {
		$this->subtitle = $subtitle;
	}

	public function setOrder($order) {
		$this->order = $order;
	}

	/* @overwrite */

	function upload($newName = '') {
		$error = parent::upload($newName);
		if ($this->uploadPrepared and is_null($error)) {
			$error = $this->saveThumb(self::MAX_WIDTH, self::MAX_HEIGHT);
		}
		return $error;
	}

	/**
	 * Gera a miniatura
	 * @return string Retorna o caminho da thumb
	 * @param int $width largura
	 * @param int $height altura
	 * @param boolean $mode modo de corte
	 */
	function showThumb($width = 100, $height = 100, $mode = 'normal') {
		return "lib/thumb/" . $mode . '/' . $width . '/' . $height . '/' . $this->__toString();
	}

	/**
	 * Cria uma miniatura da imagem 
	 * @return string
	 */
	function saveThumb() {
		$this->includeLibrary();

		$thumb = PhpThumbFactory::create($this->getFullName());
		$thumb->resize(self::MAX_WIDTH, self::MAX_HEIGHT);
		$this->remove();
		$thumb->save($this->getFullName());

		return null;
	}

	/**
	 * Inclui bibliotecas necessárias
	 * @return string
	 */
	protected function includeLibrary() {
		if (!class_exists('PhpThumbFactory')) {
			$phpThumbDir = dirname(realpath(__FILE__)) . static::$phpThumbLib;
			if (file_exists($phpThumbDir)) {
				include_once $phpThumbDir;
			} else {
				return 'PhpThumbFactory não incluído!';
			}
		}
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
		$this->clearCache();
		parent::removeOld();
	}

	/** Limpa imagens em cache */
	public function clearCache() {
		$cacheDirectory = new Directory('data/cache/thumb/');
		$cacheDirectory->remove();
	}

}
