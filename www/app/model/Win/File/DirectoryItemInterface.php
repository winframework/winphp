<?php

namespace Win\File;

/**
 * Itens que estão dentro do diretório
 * São outros Diretórios, Arquivos, etc
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

	/** @return string Nome com complemento */
	public function __toString();

	/** @return string Nome com complemento */
	public function toString();

	/** @return boolean */
	public function exists();
}
