<?php

namespace Win\Common;

use ReflectionClass;

/**
 * Dependence Injector
 * 
 * TODO: Usar singleton?
 * 
 * Se há um apelido para a classe dentro de $container,
 * então ela será utilizada ao invés da classe original.
 */
class DependenceInjector
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
	public static function make(string $class)
	{
		$class = static::$container[$class] ?? $class;

		if (!key_exists($class, static::$instances)) {
			$args = [];
			$con = (new ReflectionClass($class))->getConstructor();
			if (!is_null($con)) {
				foreach ($con->getParameters() as $param) {
					$args[] = static::make($param->getType());
				}
			}
			static::$instances[$class] = new $class(...$args);
		}

		return static::$instances[$class];
	}
}
