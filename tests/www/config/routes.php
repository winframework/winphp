<?php

use App\Controllers\BasicController;
use App\Controllers\ContatoController;
use App\Controllers\EmailController;
use App\Controllers\IndexController;
use App\Controllers\PageController;
use App\Controllers\UploaderController;
use Win\Services\Router;

$router = Router::instance();

/**
 * Direciona uma URL para um Controller
 *
 * url => [NomeDoController, nomeDoAction]
 */
$router->routes = [
	// Index
	'' => [IndexController::class],

	// Básicos
	'basic' => [BasicController::class],
	'alternative-layout' => [BasicController::class, 'alternativeLayout'],
	'basic-class' => [BasicController::class, 'classView'],
	'return-five' => [BasicController::class, 'returnFive'],
	'other-page/(.*)' => [BasicController::class, 'index'],
	'private' => [BasicController::class, 'methodPrivate'],
	'json-api' => [BasicController::class, 'json'],
	'alerts/show' => [BasicController::class, 'showAlerts'],
	'alerts' => [BasicController::class, 'createAlerts'],
	'redirecting' => [BasicController::class, 'redirecting'],
	'erro-view' => [BasicController::class, 'erroView'],

	// 404
	'controller404' => ['IndexControllasdf'],
	'action404' => [BasicController::class, 'naoExiste'],
	'not-found1' => [BasicController::class, 'notFound1'],
	'not-found2' => [BasicController::class, 'notFound2'],

	// Namespace
	'namespace' => [App\Controllers\MyModule\IndexController::class, 'index'],

	// Avançados
	'send-email' => [EmailController::class, 'index'],
	'uploader' => [UploaderController::class, 'index'],
	'pages' => [PageController::class, 'index'],
	'pages/save' => [PageController::class, 'save'],
	'pages/update' => [PageController::class, 'update'],
	'pages/(.*)' => [PageController::class, 'listByCategory'],
	'page/(.*)' => [PageController::class, 'detail'],

	// Contato
	'contato' => [ContatoController::class, 'index'],
];
