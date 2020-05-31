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

	/** @var string */
	protected $path;

	/** @var Filesystem */
	protected $fs;

	/** @var string[]|null */
	protected $temp;

	/**
	 * Inicializa o upload para o diretório de destino
	 * @param string $path
	 */
	public function __construct($path)
	{
		$this->path = $path . '/';
		$this->fs = new Filesystem();
		$this->fs->create($path);
	}

	/**
	 * Prepara o upload
	 * @param string[] $temp
	 */
	public function prepare($tempFile)
	{
		if (is_null($tempFile) || $tempFile['error']) {
			throw new \Exception('Error during upload');
		}
		$this->temp = $tempFile;
	}

	/**
	 * Faz o upload para o diretório final
	 * @param string $name
	 */
	public function upload($name = '')
	{
		if (!is_null($this->temp)) {
			$name = $this->generateName($name);
			\move_uploaded_file(
				$this->temp['tmp_name'],
				$this->path . $name
			);

			return new File($this->path . $name);
		}

		return null;
	}

	protected function generateName($name)
	{
		$info = pathinfo($this->temp['name']);

		return ($name ? $name : md5(time())) . '.' . $info['extension'];
	}
}
