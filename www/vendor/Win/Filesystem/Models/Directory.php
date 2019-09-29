<?php

namespace Win\Core\Filesystem;

use const BASE_PATH;
use Exception;

/**
 * Diretório de Arquivos
 */
class Directory extends Storable
{
	const MKDIR_MODE = STREAM_MKDIR_RECURSIVE;

	/**
	 * Instância um diretório
	 * @param string $path Caminho relativo
	 */
	public function __construct($path)
	{
		$this->setPath($path);
	}

	/** @return bool */
	public function exists()
	{
		return is_dir($this->getAbsolutePath());
	}

	/**
	 * @param string $path Caminho relativo
	 * @throws Exception
	 */
	protected function setPath($path)
	{
		if (!preg_match(static::REGEXP_PATH, $path . static::DS)) {
			throw new Exception($path . ' is a invalid directory path.');
		}
		parent::setPath($path);
	}

	/** @return bool */
	public function isEmpty()
	{
		return 0 == count($this->getItemsName());
	}

	/**
	 * Retorna nome dos itens dentro do diretório (em ordem alfabética)
	 * @return string[]
	 */
	public function listName()
	{
		$items = (array) scandir($this->getAbsolutePath());

		return array_values(array_diff($items, ['.', '..']));
	}

	/**
	 * Retorna os itens dentro do diretório (em ordem alfabética)
	 * @return Storable[]
	 */
	public function list()
	{
		$items = [];
		foreach ($this->getItemsName() as $itemName) {
			$itemPath = $this->getPath() . static::DS . $itemName;
			if (is_dir(BASE_PATH . static::DS . $itemPath)) {
				$items[] = new Directory($itemPath);
			} else {
				$items[] = new File($itemPath);
			}
		}

		return $items;
	}
}
