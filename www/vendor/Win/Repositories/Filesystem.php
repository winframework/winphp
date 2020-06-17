<?php

namespace Win\Repositories;

class Filesystem
{
	const DS = DIRECTORY_SEPARATOR;

	/**
	 * Retorna array com arquivos e diretórios
	 * @param string $path
	 * @return string[]
	 */
	public function children($path = '')
	{
		return array_diff((array) scandir(BASE_PATH . "/$path"), ['..', '.']);
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
		$path = BASE_PATH . "/$folderPath";
		if (!is_dir($path)) {
			$mask = umask(0);
			mkdir($path, $chmod, true);
			umask($mask);
		}
	}

	/**
	 * Renomeia o arquivo
	 * @param string $filePath
	 * @param string $newFilePath
	 * @return bool
	 */
	public function rename($filePath, $newFilePath)
	{
		return rename(BASE_PATH . "/$filePath", BASE_PATH . "/$newFilePath");
	}

	/**
	 * Move o arquivo
	 * @param string $filePath
	 * @param string $newFolder
	 */
	public function move($filePath, $newFolder)
	{
		return rename(BASE_PATH . "/$filePath", BASE_PATH . "/$newFolder");
	}

	/**
	 * Exclui o arquivo/diretório
	 * @param string $path Caminho do arquivo/diretório
	 * @return bool
	 */
	public function delete($path)
	{
		$fullPath = BASE_PATH . "/$path";
		if (is_dir($fullPath) && !is_link($fullPath)) {
			foreach ($this->children($path) as $child) {
				$this->delete("$path/$child");
			}
			rmdir($fullPath);
		} elseif (is_file($fullPath)) {
			unlink($fullPath);
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
			$fp = fopen(BASE_PATH . "/{$dir}/{$file}", $mode);
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
		if (!$this->exists($filePath)) {
			return false;
		}
		return file_get_contents(BASE_PATH . "/$filePath");
	}

	/**
	 * Retorna TRUE se o arquivo existe
	 * @param string $filePath
	 * @return bool
	 */
	public function exists($filePath)
	{
		$filePath = BASE_PATH . "/$filePath";

		return is_file($filePath) or is_dir($filePath);
	}
}
