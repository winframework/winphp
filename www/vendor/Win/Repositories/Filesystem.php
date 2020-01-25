<?php

namespace Win\Repositories;

class Filesystem
{
	private $basePath;
	const DS = DIRECTORY_SEPARATOR;

	/**
	 * Instância um novo arquivo
	 * @param string $basePath
	 */
	public function __construct($basePath = BASE_PATH)
	{
		$this->basePath = $basePath . '/';
	}

	/**
	 * Retorna array com arquivos e diretórios
	 * @param string $path
	 * @return string[]
	 */
	public function children($path = '')
	{
		return array_diff((array) scandir($this->basePath . $path), ['..', '.']);
	}

	/**
	 * Retorna total de items
	 * @param string $path
	 * @return int
	 */
	public function count($path)
	{
		return count($this->children($path));
	}

	/**
	 * Cria o diretório
	 * @param string $folderPath
	 * @param int $chmod Permissão (base 8)
	 */
	public function create($folderPath, $chmod = 0755)
	{
		$path = $this->basePath . $folderPath;
		if (!is_dir($path)) {
			$mask = umask(0);
			mkdir($path, $chmod, true);
			umask($mask);
		}
	}

	/**
	 * Renomeia o arquivo
	 * @param string $newName
	 * @param string $newExtension
	 * @return bool
	 */
	public function rename($filePath = null, $newFilePath)
	{
		return rename($this->basePath . $filePath, $$this->basePath . $newFilePath);
	}

	public function move($filePath = null, $newFolder)
	{
		$currentName = '';

		return rename($this->basePath . $filePath, $this->basePath . $newFolder . $currentName);
	}

	/**
	 * Exclui o arquivo/diretório
	 * @param string $path Caminho do arquivo/diretório
	 * @return bool
	 */
	public function delete($path)
	{
		$path = $path;
		if (is_dir($path) && !is_link($path)) {
			foreach ($this->children($path) as $child) {
				$this->delete("$path/$child");
			}
			rmdir($this->basePath . $path);
		} elseif (is_file($path)) {
			unlink($this->basePath . $path);
		}
	}

	/**
	 * Salva o conteúdo no arquivo
	 * @param string $content
	 * @param string $mode
	 * @return bool
	 */
	public function write($filePath, $content, $mode = 'w')
	{
		$dir = pathinfo($filePath, PATHINFO_DIRNAME);
		$file = pathinfo($filePath, PATHINFO_BASENAME);
		$return = false;

		if ($dir) {
			$this->create($dir, 0777);
			$fp = fopen("$dir/$file", $mode);
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
		if ($this->exists($filePath)) {
			$content = file_get_contents($this->basePath . $filePath);
		}

		return $content;
	}

	/**
	 * Retorna TRUE se o arquivo existe
	 * @param string $filePath
	 * @return bool
	 */
	public function exists($filePath)
	{
		return is_file($this->basePath . $filePath);
	}
}
