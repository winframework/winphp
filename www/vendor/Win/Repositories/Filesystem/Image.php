<?php

namespace Win\Repositories\Filesystem;

/**
 * Arquivo de Imagem
 */
class Image extends File
{
	public static $validExtensions = ['jpg', 'jpeg', 'gif', 'png'];

	const SIZE_WIDTH_INDEX = 0;
	const SIZE_HEIGHT_INDEX = 1;

	/**
	 * Retorna a largura da imagem
	 * @return int|null
	 */
	public function getWidth()
	{
		return $this->getImageSize(static::SIZE_WIDTH_INDEX);
	}

	/**
	 * Retorna a altura da imagem
	 * @return int|null
	 */
	public function getHeight()
	{
		return $this->getImageSize(static::SIZE_HEIGHT_INDEX);
	}

	/**
	 * @param int $param
	 * @return int|null
	 */
	protected function getImageSize($param)
	{
		$size = null;
		if ($this->exists()) {
			$size = getimagesize($this->getAbsolutePath())[$param];
		}

		return $size;
	}
}
