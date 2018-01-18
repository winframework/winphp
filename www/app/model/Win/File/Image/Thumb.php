<?php

namespace Win\File\Image;

use PHPThumb\GD;
use Win\File\Directory;
use Win\File\Image;

/**
 * Gera thumbs utilizando alguma biblioteca de terceiros
 */
class Thumb {

	private $url;
	private $cache = null;
	private $default = null;
	private $opacity = 100;

	/** @var AdapterInterface Biblioteca responsável pela thumb */
	public $adapter;

	/** @var GD */
	private $image;
	private $imageUrl;
	private $width;
	private $height;
	private $padding;
	private $quality;
	private $mode;
	static private $modes = ['normal', 'center', 'padding'];

	/**
	 * Construtor
	 */
	public function __construct() {
		$this->width = Image::MAX_WIDTH;
		$this->height = Image::MAX_WIDTH;
		$this->quality = Image::QUALITY;
		$this->mode = 'normal';
	}

	public function setCache($cache) {
		$this->cache = $cache;
	}

	/**
	 * Define a imagem padrão caso não encontre a imagem
	 * @param string $default
	 * @param int $opacity
	 */
	public function setDefault($default, $opacity) {
		$this->default = $default;
		$this->opacity = $opacity;
	}

	/**
	 * Inicia configurações com base na url
	 * @param string $url
	 */
	public function config($url) {
		$config = explode('/', str_replace('cache/thumb/', '', $url));

		if (count($config) >= 4) {
			$this->setMode(array_shift($config));
			$this->url = implode('/', $config);
			$this->setSize($config);
			$this->setImageUrl($config);
		}
	}

	/**
	 * Define o nome da imagem
	 * é possivel utililizar dois modos de apelido para a imagem
	 * diretorio/real/[APELIDO]_nome-imagem.jpg
	 * diretorio/real/nome-imagem/[APELIDO].jpg
	 */
	private function setImageUrl($config) {
		$imageName = explode('_', array_pop($config));
		$this->imageUrl = BASE_PATH . '/' . implode('/', $config) . '/' . end($imageName);

		if (!file_exists($this->imageUrl)) {
			$imageExt = explode('.', end($imageName));
			$this->imageUrl = BASE_PATH . '/' . implode('/', $config) . '.' . $imageExt[1];
		}
	}

	/** @param string $mode */
	private function setMode($mode) {
		if (in_array($mode, static::$modes)) {
			$this->mode = $mode;
		}
	}

	/**
	 * Define as dimensões
	 * @param mixed[] $config
	 */
	private function setSize(&$config) {
		$size = explode('x', array_shift($config));
		if (isset($size[0]) && $size[0] > 0) {
			$this->width = (int) $size[0];
		}
		if (isset($size[1]) && $size[1] > 0) {
			$this->height = (int) $size[1];
		}
		if (isset($size[2])) {
			$this->padding = (int) $size[2];
		}
		if (count($size) == 1) {
			$this->height = array_shift($config);
		}
	}

	/** Exibe a thumb final */
	public function show() {
		if ($this->validate()) {
			$this->image = new GD($this->imageUrl);
			$this->image->setOptions(['jpegQuality' => 75]);
			$thumb = $this->{$this->mode}();
			$this->saveCache($thumb);
			$thumb->show();
		} else {
			$this->showDefault();
		}
	}

	/** @return boolean */
	private function validate() {
		if (!file_exists($this->imageUrl)) {
			return false;
		}
		return true;
	}

	/**
	 * Salva a imagem no diretorio de cache se necessário
	 * @param GD $thumb
	 */
	private function saveCache($thumb) {
		if (!is_null($this->cache)) {
			$cacheDir = BASE_PATH . '/' . $this->cache . '/thumb';
			$url = explode('/', $cacheDir . '/' . $this->mode . '/' . $this->url);
			$newFile = array_pop($url);
			$dir = new Directory(implode('/', $url));
			$dir->create();
			$thumb->save($dir->getPath() . $newFile);
		}
	}

	/** Exibe a imagem default */
	private function showDefault() {
		$this->image = new GD(BASE_PATH . '/' . $this->default);
		$this->padding = $this->width * 0.2;
		$thumb = $this->padding();
		$this->applyOpacity($thumb);
		$thumb->show();
	}

	/**
	 * Aplica opacidade na imagem
	 * @param GD $thumb
	 */
	private function applyOpacity($thumb) {
		$img = imagecreatetruecolor($this->width, $this->height);
		imagefilledrectangle($img, 0, 0, $this->width, $this->height, imagecolorallocate($img, 255, 255, 255));
		imagecopymerge($thumb->getOldImage(), $img, 0, 0, 0, 0, $this->width, $this->height, 100 - $this->opacity);
	}

	/* Modos */

	/** @return GD */
	protected function normal() {
		return $this->image->resize($this->width, $this->height);
	}

	/** @return GD */
	protected function center() {
		return $this->image->adaptiveResize($this->width, $this->height);
	}

	/** @return GD */
	protected function padding() {
		return $this->image->resize($this->width, $this->height)->pad($this->width + $this->padding, $this->height + $this->padding);
	}

}
