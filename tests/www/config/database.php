<?php


use Win\Repositories\Mysql;

/** Configurações do banco */
return Mysql::connect([
	'host' => 'localhost',
	'user' => 'root',
	'pass' => 'wcorp@2014Mysql',
	'dbname' => 'winphp_demo'
]);
