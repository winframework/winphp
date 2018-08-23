<?php

namespace Win\Mvc;

use Win\DesignPattern\Singleton;
use const BASE_PATH;

/**
 * Sistema de Templates
 *
 * Permite que a aplicação tenha múltiplos Templates e facilita a alteração entre os Templates.
 *
 * Quando uma View ou Block é chamado, primeiro o arquivo será buscando em "template/[nome-do-template]"
 * E caso o arquivo não exista, será buscado em "template/default"
 */
class Template {

	use Singleton;

	protected static $dir = 'app/template/';
	protected static $themeDefault = 'default';

	/**
	 * Nome do Tema atual
	 * @var string
	 */
	private static $theme = null;

	/**
	 * Define o nome do Tema atual (Antes de instanciar o Application)
	 *
	 * Após esta chamada, todos os Blocos e Views serão buscados "template/[$theme]"
	 * @param string $theme
	 */
	public function setTheme($theme) {
		self::$theme = $theme;
	}

	/**
	 * Retorna o nome do Tema atual
	 * @return string
	 */
	public function getTheme() {
		return self::$theme;
	}

	/**
	 * Retorna o Novo caminho completo do arquivo
	 * (incluindo o diretório do template atual)
	 * 
	 * @param string $dir diretório atual da View
	 * @param string $file Arquivo atual da View
	 * @return string Novo caminho completo da View
	 */
	public function getFilePath($dir, $file) {
		$appDir = BASE_PATH . '/app/';
		$newDir = str_replace($appDir, '', $dir);
		if (file_exists(self::$dir . self::$theme . '/' . $newDir . $file . '.phtml')) {
			return self::$dir . self::$theme . '/' . $newDir . $file;
		}
		return self::$dir . self::$themeDefault . '/' . $newDir . $file;
	}

}
