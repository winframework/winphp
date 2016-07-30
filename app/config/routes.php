<?php

/*
 * Rotas Personalizadas
 * 
 * Aponta uma URL para um controller de forma manual
 * Rotas personalizadas funcionam apenas se
 * a rota automática não encontrar nem o controller e nem a view que está na Url
 */

$route = [];

/**
 * Exemplos de rotas personalizadas
 * @example
 * <code>
 * $route['home'] = 'index/index';
 * $route['foo'] = 'example/foo';
 * $route['product/(.*)'] = 'example/bar/$1';
 * $route['category/(.*)'] = 'not-found/because-this-file/doesnt-exist';
 * </code>
 */
$config['route'] = $route;
