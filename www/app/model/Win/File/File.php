<?php

namespace Win\File;

/**
 * Arquivos
 *
 */
class File {

	private $name = null;
	private $tempName = null;
	private $extension = null;
	private $size = 0;

	/** @var Directory */
	private $directory = null;
	protected $uploadPrepared = false;
	private $oldName = null;
	protected static $maxSize = 10;
	protected static $validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'csv', 'doc', 'docx', 'odt', 'pdf', 'txt', 'md', 'mp3', 'wav', 'mpeg'];

	/* Construtor */

	public function __construct($name = '') {
		if (is_file($name)) {
			$this->name = pathinfo($name, PATHINFO_BASENAME);
			$this->tempName = pathinfo($name, PATHINFO_BASENAME);
			$this->extension = pathinfo($name, PATHINFO_EXTENSION);
			$directoryPath = (pathinfo($name, PATHINFO_DIRNAME));
			$this->directory = new Directory($directoryPath);
			$this->size = 1;
		}
	}

	/* Metodos de acesso */

	public function getName() {
		return $this->name;
	}

	public function getTempName() {
		return $this->tempName;
	}

	public function getExtension() {
		if (is_null($this->extension)):
			$this->extension = static::getExtensionByName($this->name);
		endif;
		return $this->extension;
	}

	public function getSize() {
		return $this->size;
	}

	public function getDirectory() {
		return $this->directory;
	}

	public function getOldName() {
		return $this->oldName;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function setTempName($tempName) {
		$this->tempName = $tempName;
	}

	/** @param string $directory */
	public function setDirectory($directory) {
		$this->directory = new Directory($directory);
	}

	public function setOldName($oldName) {
		$this->oldName = $oldName;
	}

	public function getFullName() {
		return $this->getDirectory() . $this->getName();
	}

	public static function getValidExtensions() {
		return static::$validExtensions;
	}

	public function __toString() {
		if ($this->getName() != '') {
			return $this->getFullName();
		} else {
			return '';
		}
	}

	/* Metodos */

	/**
	 * Recebe o Arquivo temporário
	 * @param array $files $_FILES['arquivo']
	 * @example $obj->receiveFiles($_FILES['arquivo']);
	 */
	function receiveFiles($files) {
		if ($this->getName() != '') {
			$this->oldName = $this->getFullName();
		}
		if (!empty($files['name'])) {
			$this->tempName = $files['tmp_name'];
			$this->extension = static::getExtensionByName($files['name']);
			$this->size = $files['size'];
			$this->uploadPrepared = true;
		}
	}

	/**
	 * Recebe o Arquivo temporário
	 * @param string $fileName $_POST['arquivo']
	 * @example $obj->receivePost($_POST['arquivo']);
	 */
	public function receivePost($fileName) {
		if ($this->getName() != '') {
			$this->oldName = $this->getFullName();
		}
		if (!empty($fileName) && file_exists($fileName)) {
			$this->tempName = $fileName;
			$this->extension = static::getExtensionByName($fileName);
			$this->size = -1;
			$this->uploadPrepared = true;
		}
	}

	/**
	 * Realiza o envio para a pasta, reduzindo o tamanho e com um nome aleatório
	 * @param string $newName [opcional] escolhe o nome que será salvo
	 * @return string|null Retorna algum erro ou NULL
	 */
	public function upload($newName = '') {
		if ($this->uploadPrepared) {
			$error = $this->validateUpload();
			if (!$error) {
				$this->generateIdealName($newName);
				$this->removeOld();
				$this->remove();
				$this->moveTmpFile();
			}
		}
		return $error;
	}

	/** @return null|string */
	protected function validateUpload() {
		if (!file_exists($this->tempName)) {
			return 'Houve um erro ao enviar o arquivo, verifique se o arquivo não ultrapasse o tamanho máximo permitido. ';
		} elseif (!in_array($this->extension, static::$validExtensions)) {
			return 'Tipo de arquivo inválido, somente ' . strtoupper(implode('/', static::$validExtensions)) . '.';
		} elseif ((!file_exists($this->directory) && !$this->directory->create())) {
			return 'O diretorio ' . $this->directory . ' não existe.';
		} elseif ($this->size > (static::$maxSize * 1024 * 1024) or $this->size == 0) {
			return 'O tamanho do arquivo deve ser entre 0kb e ' . static::$maxSize . 'Mb.';
		}
		return null;
	}

	/** @param string $newName */
	protected function generateIdealName($newName) {
		if (empty($newName)) {
			$this->name = strtolower(md5(uniqid(time())) . '.' . $this->extension);
		} else {
			$this->name = strtolower($newName . '.' . $this->extension);
		}
	}

	/** Move o arquivo que era temporário */
	protected function moveTmpFile() {
		if ($this->size === -1) {
			$this->move();
		} else {
			move_uploaded_file($this->getTempName(), $this->getFullName());
		}
	}

	/**
	 * Retorna TRUE se arquivo existe
	 * @return boolean
	 */
	public function exists() {
		return ($this->getName() && is_file($this->getFullName()));
	}

	/** Move o arquivo de $temp para $diretorio atual */
	public function move() {
		if (file_exists($this->getTempName())) {
			if ($this->getName() == '') {
				$this->setName(md5(uniqid(time())));
			}
			rename($this->getTempName(), $this->getFullName());
			$this->setTempName($this->getFullName());
		}
	}

	/** Exclui o arquivo no diretório */
	public function remove() {
		if ($this->exists()) {
			unlink($this->getFullName());
		}
	}

	/** Exclui o arquivo que existia antes de enviar */
	public function removeOld() {
		if ($this->oldName) {
			$oldFile = new File($this->oldName);
			$oldFile->remove();
		}
	}

	/**
	 * Remove diretório(s) por expressão regular
	 * @param string $regExp
	 * @return int quantidade de diretórios removidos
	 */
	public static function removeRegExp($regExp) {
		$fileNames = glob($regExp);
		foreach ($fileNames as $filename) {
			unlink($filename);
		}
		return count($fileNames);
	}

	/**
	 * Retorna a extensão do arquivo
	 * @param string $name nome do arquivo
	 * @return string
	 */
	protected static function getExtensionByName($name) {
		$ext = explode('.', $name);
		return strtolower(end($ext));
	}

	/**
	 * Salva o conteúdo no arquivo
	 * @param string $content
	 * @param string $mode
	 * @return boolean
	 */
	public function write($content = '', $mode = 'a') {
		if (!is_null($this->getName())) {
			$this->directory->create();
			$fp = fopen($this->getFullName(), $mode);
			if ($fp !== false) {
				fwrite($fp, $content);
				fclose($fp);
				return true;
			}
			return false;
		}
	}

	/**
	 * Retorna o conteúdo do arquivo
	 * @param string $content
	 */
	public function read() {
		return file_get_contents($this->getFullName());
	}

}
