<?php

namespace Win\Models\Filesystem;

use const BASE_PATH;
use Exception;

/**
 * Diretório de Arquivos
 */
class Directory extends Storable
{
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
		return count($this->getChildrenNames()) == 0;
	}

	/**
	 * Retorna nome dos itens dentro do diretório (em ordem alfabética)
	 * @return string[]
	 */
	public function getChildrenNames()
	{
		$items = (array) scandir($this->getAbsolutePath());

		return array_values(array_diff($items, ['.', '..']));
	}

	/**
	 * Retorna os itens dentro do diretório (em ordem alfabética)
	 * @return Storable[]
	 */
	public function getChildren()
	{
		$items = [];
		foreach ($this->getChildrenNames() as $itemName) {
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
