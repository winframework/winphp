<?php

namespace Win\Common;

use ReflectionClass;

/**
 * Dependence Injector
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

	/**
	 * Cria a classe, injetando as dependências
	 * @param string $class
	 * @return object
	 */
	public static function make(string $class)
	{
		$args = [];
		$class = static::$container[$class] ?? $class;
		$con = (new ReflectionClass($class))->getConstructor();

		if (!is_null($con)) {
			foreach ($con->getParameters() as $param) {
				$args[] = static::make($param->getType());
			}
		}

		return new $class(...$args);
	}
}
