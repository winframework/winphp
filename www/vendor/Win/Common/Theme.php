<?php

namespace Win\Common;

use Win\Common\Traits\SingletonTrait;

/**
 * Sistema de Temas
 *
 * Permite que a aplicação tenha múltiplos Temas e facilita a alteração entre eles.
 *
 * Quando um Template é chamado, primeiro o arquivo será buscando em "themes/[nome-do-tema]"
 * E caso o arquivo não exista, será buscado em "themes/default"
 */
class Theme
{
	use SingletonTrait;

	protected static $dir = '/templates/themes';
	const THEME_DEFAULT = 'default';

	/**
	 * Nome do Tema atual
	 * @var string
	 */
	private static $theme = null;

	/**
	 * Define o nome do Tema atual (Antes de instanciar o Application)
	 *
	 * Após esta chamada, os Templates serão buscados em "themes/[$theme]"
	 * @param string $theme
	 */
	public function set($theme)
	{
		self::$theme = $theme;
	}

	/**
	 * Retorna o nome do Tema atual
	 * @return string
	 */
	public function get()
	{
		return self::$theme;
	}

	/**
	 * Retorna o Novo caminho completo do arquivo
	 * (incluindo o diretório do template atual)
	 *
	 * @param string $file Arquivo atual da Template
	 * @return string Novo caminho completo do Template
	 */
	public function getTemplatePath(Template $template)
	{
		$filePath = $this->getFilePath($this->get(), $template);
		var_dump($filePath);

		if (file_exists($filePath)) {
			return $filePath;
		}

		return $this->getFilePath(static::THEME_DEFAULT, $template);
	}

	/**
	 * Retorna o nome do arquivo com base no tema
	 * @param string $theme
	 * @param Template $template
	 * @return string
	 */
	private function getFilePath($theme, $template)
	{
		$themeDir = self::$dir . DIRECTORY_SEPARATOR . $theme;

		return str_replace('/templates', $themeDir, $template->getFile());
	}
}
