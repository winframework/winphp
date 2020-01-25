<?php

namespace Win\Repositories\Filesystem;

use Win\Models\Filesystem\File;
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

	/** @var string[] */
	protected $temp;

	/** @var File */
	protected $uploaded;

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
	 * Retorna a instância do arquivo que foi enviado
	 * @return File
	 */
	public function getUploaded()
	{
		return $this->uploaded;
	}

	/**
	 * Prepara o upload
	 * @param string[] $temp
	 */
	public function prepare($tempFile)
	{
		$this->temp = $tempFile['tmp_name'] ? $tempFile : null;
	}

	/**
	 * Faz o upload para o diretório final
	 * @param string $name
	 */
	public function upload($name = '')
	{
		if (!$this->temp || $this->temp['error']) {
			throw new \Exception('Error during upload');
		}

		if (!is_null($this->temp)) {
			$name = $this->generateName($name);
			\move_uploaded_file($this->temp['tmp_name'],
			 $this->path . $name);
			$this->uploaded = new File($this->path . $name);
		}
	}

	public function generateName($name)
	{
		$info = pathinfo($this->temp['name']);

		return ($name ? $name : md5(time())) . '.' . $info['extension'];
	}
}
