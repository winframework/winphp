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
		if (key_exists((int) Application::app()->getParam(0), static::$pages)):
			ErrorPage::setError((int) Application::app()->getParam(0));
		endif;
	}

	/**
	 * Define a página como Página de Erro
	 * @param int $errorCode
	 */
	public static function setError($errorCode) {
		$app = Application::app();
		if (key_exists($errorCode, static::$pages)):
			static::stopControllerIf403($errorCode);
			$app->setPage((string) $errorCode);
			$app->view = new View($errorCode);

			$app->controller = ControllerFactory::create('Error' . $errorCode);
			$app->controller->setTitle(Title::otimize(static::$pages[$errorCode]));
			http_response_code($errorCode);
			$app->controller->reload();
		endif;
	}

	/**
	 * Trava o carregamento do Controller, se ele definir um erro 403
	 * Isso evita que códigos sem permissões de acesso nunca sejam executados
	 * @param int $errorCode
	 */
	private static function stopControllerIf403($errorCode) {
		$app = Application::app();
		if ($errorCode == 403 && $app->getParam(0) !== (string) $errorCode):
			$app->redirect(403 . '/index/' . $app->getUrl());
		endif;
	}

	/** @return Application */
	protected static function app() {
		return Application::app();
	}

	/** @return boolean */
	public static function isErrorPage() {
		return (boolean) (key_exists((int) Application::app()->getPage(), static::$pages));
	}

}
