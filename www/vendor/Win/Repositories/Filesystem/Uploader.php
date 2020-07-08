<?php

namespace Win\Repositories\Filesystem;

use Win\Repositories\Filesystem\File;
use Win\Repositories\Filesystem;

/**
 * Auxilia fazer upload de Arquivos
 */
class Uploader
{
	/** @var string[] */
	protected static $validExtensions = [
		'csv', 'doc', 'docx', 'gif', 'jpeg', 'jpg', 'md', 'mp3',
		'mp4', 'mpeg', 'pdf', 'png', 'svg', 'txt', 'wav', 'xls', 'xlsx', 'zip',
	];

	/** @var Filesystem */
	protected $fs;

	/** @var string[]|null */
	protected $temp;

	/**
	 * Inicializa o uploader
	 */
	public function __construct(Filesystem $fs)
	{
		$this->fs = $fs;
	}

	/**
	 * Prepara o upload
	 * @param string[] $temp
	 */
	public function prepare($tempFile)
	{
		if (is_null($tempFile) || $tempFile['error']) {
			throw new \Exception("Erro ao enviar o arquivo.");
		}
		$this->temp = $tempFile;
	}

	/**
	 * Faz o upload para o diretório final
	 * @param string $directoryPath
	 * @param string $name
	 */
	public function upload($directoryPath, $name = null)
	{
		if (!is_null($this->temp)) {
			$name = $this->generateName($name);
			$this->fs->create($directoryPath);
			\move_uploaded_file($this->temp['tmp_name'], "$directoryPath/$name");

			return new File("$directoryPath/$name");
		}

		return null;
	}

	protected function generateName($name)
	{
		$info = pathinfo($this->temp['name']);

		return ($name ?? md5(time())) . '.' . $info['extension'];
	}
}
