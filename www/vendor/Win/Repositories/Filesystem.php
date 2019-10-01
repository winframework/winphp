<?php

namespace Win\Repositories;

class FileSystem
{
	private $basePath;

	/**
	 * Instância um novo arquivo
	 * @param string $path Caminho relativo
	 */
	public function __construct($basePath = BASE_PATH)
	{
		$this->basePath = $basePath;
	}

	public function list()
	{
		return [];
	}

	public function count($filePath)
	{
		return 0;
	}

	public function create($filePath)
	{
		return true;
	}

	/**
	 * Renomeia o arquivo
	 * @param string $newName
	 * @param string $newExtension
	 * @return bool
	 */
	public function rename($filePath = null, $newFilePath)
	{
		return rename($filePath, $newFilePath);
	}

	public function move($filePath = null, $newFolder)
	{
		$currentName = '';

		return rename($filePath, $newFolder . $currentName);
	}

	/**
	 * Exclui o arquivo
	 * @return bool
	 */
	public function delete($filePath, $recursive = true)
	{
		unlink($filePath);
	}

	/**
	 * Salva o conteúdo no arquivo
	 * @param string $content
	 * @param string $mode
	 * @return bool
	 */
	public function write($filePath, $content, $mode = 'w')
	{
		$return = false;
		if (!empty($this->getName())) {
			$this->getDirectory()->create(0777);
			$fp = fopen($filePath, $mode);
			if (false !== $fp) {
				fwrite($fp, $content);
				$return = fclose($fp);
			}
		}

		return $return;
	}

	/**
	 * Retorna o conteúdo do arquivo
	 * @return string|false
	 */
	public function read($filePath)
	{
		$content = false;
		if ($this->exists()) {
			$content = file_get_contents($filePath);
		}

		return $content;
	}
}
