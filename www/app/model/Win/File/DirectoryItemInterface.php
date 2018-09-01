<?php

namespace Win\File;

/**
 * Interface: Item dentro do Diretório
 * São outros diretório, arquivos, etc
 */
interface DirectoryItemInterface {

	/**
	 * Instância
	 * @param string $path Caminho relativo
	 */
	public function __construct($path);

	/** @return string Caminho relativo */
	public function getPath();

	/** @return string Caminho absoluto */
	public function getAbsolutePath();

	/** @return string Nome sem complemento */
	public function getName();

	/** @return Directory */
	public function getDirectory();

	/** @return string Nome com complemento */
	public function __toString();

	/** @return string Nome com complemento */
	public function toString();

	/** @return boolean */
	public function exists();

	/** @return boolean */
	public function delete();

	/** @return boolean */
	public function move(Directory $newDirectory);

	/** @return boolean */
	public function rename($newName);

	/**
	 * Define a permissão ao diretório
	 * @param int $chmod
	 * @return boolean
	 */
	public function setChmod($chmod = 0755);

	/** @return string */
	public function getChmod();
}
