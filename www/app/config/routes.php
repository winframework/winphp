<?php

/*
 * Rotas Personalizadas
 *
 * Aponta uma URL para um controller de forma manual
 * Rotas personalizadas funcionam apenas se
 * a rota automática não encontrar o controller e nem a view
 */

$routes = [];

/**
 * Exemplos de rotas personalizadas
 *
 * @example
 * <code>
 * $routes['^home'] = 'index/index';
 * $routes['^foo'] = 'example/foo';
 * $routes['^product/(.*)'] = 'product/detail/$1';
 * </code>
 */
return $routes;
