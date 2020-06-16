<?php

namespace Win\Common;

use ReflectionClass;

/**
 * Dependence Injector
 *
 * Auxilia a Injetar dependências
 * As classes ficam salvas em $container, então poderão ser sobrescritas
 * desde que todas as chamadas das classes estejam utilizando o Singleton
 * ao invés de instanciar a classe.
 *
 * @see SingletonTrait
 */
class DependenceInjector
{
	/**
	 * Armazena os nomes de classes
	 * @var string[]
	 */
	public static $container = [];

	/**
	 * Cria a classe, injetando as dependências automaticamente
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
