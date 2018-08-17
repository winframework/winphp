<?php

namespace Win\Mvc;

use Win\Html\Seo\Title;

/**
 * Paginas de Erro
 */
class ErrorPage {

	/** @var string[] */
	public static $pages = [
		404 => 'Página não encontrada',
		401 => 'Não autorizado',
		403 => 'Acesso negado',
		500 => 'Erro no Servidor',
		503 => 'Problemas de Conexão'
	];

	/**
	 * Chama pageNotFound se o usuário acessar /404
	 *
	 * Isso garante que todas as funcionalidades de pageNotFound serão executadas
	 * mesmo se a página existente 404 for acessada
	 */
	public static function validate() {
		if (key_exists((int) static::app()->getParam(0), static::$pages)):
			Application::app()->pageNotFound();
		endif;
	}

	/**
	 * Define a página como Página de Erro
	 * @param int $errorCode
	 */
	public static function setError($errorCode) {
		if (key_exists($errorCode, static::$pages)):
			static::stopControllerIf403($errorCode);
			static::app()->setPage((string) $errorCode);
			static::app()->view = new View($errorCode);

			static::app()->controller = ControllerFactory::create('Error' . $errorCode);
			static::app()->view->addData('title', Title::otimize(static::$pages[$errorCode]));
			http_response_code($errorCode);
			static::app()->controller->reload();
		endif;
	}

	/**
	 * Trava o carregamento do Controller, se ele definir um erro 403
	 * Isso evita que códigos sem permissões de acesso nunca sejam executados
	 * @param int $errorCode
	 */
	private static function stopControllerIf403($errorCode) {
		if ($errorCode == 403 && static::app()->getParam(0) !== (string) $errorCode):
			static::app()->redirect(403 . '/index/' . static::app()->getUrl());
		endif;
	}

	/** @return Application */
	protected static function app() {
		return Application::app();
	}

	/** @return boolean */
	public static function isErrorPage() {
		return (boolean) (key_exists((int) static::app()->getPage(), static::$pages));
	}

}
