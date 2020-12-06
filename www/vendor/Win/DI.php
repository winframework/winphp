<?php

namespace Win;

use ReflectionClass;
use ReflectionParameter;

/**
 * Dependence Injector
 * 
 * Se há um apelido para a classe dentro de $container,
 * então ela será utilizada ao invés da classe original.
 */
abstract class DI
{
	/**
	 * Armazena os nomes de classes
	 * @var string[]
	 */
	public static $container = [];
	public static $instances = [];

	/**
	 * Cria a classe, injetando as dependências
	 * @param string $class
	 * @return object
	 */
	public static function instance(string $class)
	{
		$class = static::$container[$class] ?? $class;

		if (!key_exists($class, static::$instances)) {
			$args = [];
			$con = (new ReflectionClass($class))->getConstructor();
			/** @var ReflectionParameter[] $params */
			$params = $con ? $con->getParameters() : [];

			foreach ($params as $param) {
				if ($param->getType()) {
					$args[] = static::instance($param->getType()->getName());
				}
			}
			static::$instances[$class] = new $class(...$args);
		}

		return static::$instances[$class];
	}
}
