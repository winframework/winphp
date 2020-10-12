<?php

namespace Win\Services;

use Exception;

class Filesystem
{
	const DS = DIRECTORY_SEPARATOR;

	/** @var string[]|null */
	protected $tempFile;

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
			if (!@mkdir($path, $chmod, true)) {
				throw new Exception("O diretório '{$folderPath}' não existe ou não possui permissão.");
			}
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

	/**
	 * Prepara o upload
	 * @param string[] $tempFile
	 * @param string[] $extensions
	 */
	public function receiveFile(
		$tempFileFile,
		$extensions = ['csv', 'doc', 'docx', 'gif', 'jpeg', 'jpg', 'md', 'mp3', 'mp4', 'mpeg', 'pdf', 'png', 'svg', 'txt', 'wav', 'xls', 'xlsx', 'zip',]
	) {
		if (isset($tempFileFile['name'])) {
			$extension = pathinfo($tempFileFile['name'])['extension'];
			if (!in_array($extension, $extensions)) {
				throw new \Exception("A extensão {$extension} não é permitida.");
			}
		}

		if (is_null($tempFileFile) || $tempFileFile['error']) {
			throw new \Exception("Erro ao receber o arquivo.");
		}

		$this->tempFile = $tempFileFile;
	}

	/**
	 * Faz o upload para o diretório final
	 * @param string $directoryPath
	 * @param string $name
	 * @return string
	 */
	public function upload($directoryPath, $name = null)
	{
		if (!is_null($this->tempFile)) {
			$name = $this->generateName($name);
			$this->create($directoryPath);
			\move_uploaded_file($this->tempFile['tmp_name'], "$directoryPath/$name");

			return $name;
		} else {
			throw new \Exception("Erro ao enviar o arquivo.");
		}
	}

	/**
	 * Gera um novo nome, mantendo a extensão
	 * @param string $name
	 */
	protected function generateName($name)
	{
		$info = pathinfo($this->tempFile['name']);
		return ($name ?? md5('Y-m-d H:i:s') . $this->tempFile['name']) . '.' . $info['extension'];
	}
}
