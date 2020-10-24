<?php

namespace Win\Services;

use Exception;
use Win\Common\InjectableTrait;

class Filesystem
{
	use InjectableTrait;
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
		if (!@rename(BASE_PATH . "/$filePath", BASE_PATH . "/$newFilePath")) {
			throw new Exception('Não foi possível "' . $filePath . '" para "' . $newFilePath . '".');
		}
	}

	/**
	 * Move o arquivo
	 * @param string $filePath
	 * @param string $newFolder
	 */
	public function move($filePath, $newFilePath)
	{
		return $this->rename($filePath, $newFilePath);
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

		if ($dir) {
			$this->create($dir, 0777);
			$fp = @fopen(BASE_PATH . "/{$dir}/{$file}", $mode);
		}
		if (!isset($fp)) {
			throw new Exception('Não foi possível escrever em "' . "{$dir}/{$file}" . '".');
		}
		fwrite($fp, $content);
		fclose($fp);
	}

	/**
	 * Retorna o conteúdo do arquivo
	 * @return string
	 */
	public function read($filePath)
	{
		$content = @file_get_contents(BASE_PATH . "/$filePath");
		if ($content === false) {
			throw new Exception('Arquivo "' . $filePath . '" não pode ser lido.');
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
		$extensions = ['csv', 'doc', 'docx', 'gif', 'jpeg', 'jpg', 'md', 'mp3', 'mp4', 'mpeg', 'pdf', 'png', 'svg', 'txt', 'wav', 'xls', 'xlsx', 'zip']
	) {
		if (isset($tempFileFile['name'])) {
			$extension = strtolower(pathinfo($tempFileFile['name'])['extension']);
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
	 * @param string $newName
	 * @return string
	 */
	public function upload($directoryPath, $newName = null)
	{
		if ($this->tempFile && key_exists('name', $this->tempFile)) {
			$name = $this->generateName($this->tempFile['name'], $newName);
			$this->create($directoryPath);
			\move_uploaded_file($this->tempFile['tmp_name'], "$directoryPath/$name");

			return $name;
		} else {
			throw new \Exception("Erro ao enviar o arquivo.");
		}
	}

	/**
	 * Gera um novo nome, mantendo a extensão
	 * @param string $originalName
	 * @param string $newName
	 */
	public function generateName($originalName, $newName = null)
	{
		$info = pathinfo($originalName);
		if (!$newName) {
			$newName = md5('Y-m-d H:i:s' . $originalName);
		}
		return $newName . '.' . strtolower($info['extension']);
	}
}
