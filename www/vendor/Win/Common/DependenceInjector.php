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
	 * Retorna o nome da classe
	 * @param string $class
	 * @return string
	 */
	public static function getClass($class)
	{
		if (key_exists($class, static::$container)) {
			$class = static::$container[$class];
		}

		return $class;
	}

	/**
	 * Cria a classe, injetando as dependências automaticamente
	 * @param string $class
	 * @return object
	 */
	public static function make(string $class)
	{
		$args = [];
		$con = (new ReflectionClass($class))->getConstructor();

		if (!is_null($con)) {
			foreach ($con->getParameters() as $param) {
				$args[] = static::make($param->getType());
			}
		}

		return new $class(...$args);
	}
}
