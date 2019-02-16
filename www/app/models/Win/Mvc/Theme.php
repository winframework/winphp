<?php

namespace Win\Mvc;

use Win\Singleton\SingletonTrait;
use const BASE_PATH;

/**
 * Sistema de Temas
 *
 * Permite que a aplicação tenha múltiplos Temas e facilita a alteração entre eles.
 *
 * Quando uma View ou Block é chamado, primeiro o arquivo será buscando em "themes/[nome-do-tema]"
 * E caso o arquivo não exista, será buscado em "themes/default"
 */
class Theme {

	use SingletonTrait;

	protected static $dir = '/app/themes';
	const THEME_DEFAULT = 'default';

	/**
	 * Nome do Tema atual
	 * @var string
	 */
	private static $theme = null;

	/**
	 * Define o nome do Tema atual (Antes de instanciar o Application)
	 *
	 * Após esta chamada, os Blocos e Views serão buscados em "themes/[$theme]"
	 * @param string $theme
	 */
	public function set($theme) {
		self::$theme = $theme;
	}

	/**
	 * Retorna o nome do Tema atual
	 * @return string
	 */
	public function get() {
		return self::$theme;
	}

	/**
	 * Retorna o Novo caminho completo do arquivo
	 * (incluindo o diretório do template atual)
	 * 
	 * @param string $file Arquivo atual da View
	 * @return string Novo caminho completo da View
	 */
	public function getFilePath($file) {
		$viewDir = str_replace('app/', '', View::$dir);
		$path = self::$dir . DIRECTORY_SEPARATOR . self::$theme 
		. $viewDir . DIRECTORY_SEPARATOR;
		if (file_exists(BASE_PATH . $path . $file . '.phtml')) {
			return $path . $file;
		}
		return self::$dir . DIRECTORY_SEPARATOR . self::THEME_DEFAULT . $viewDir . DIRECTORY_SEPARATOR . $file;
	}

}
