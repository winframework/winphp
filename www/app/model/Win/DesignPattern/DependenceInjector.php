<?php

namespace Win\DesignPattern;

/**
 * Dependence Injector
 *
 * Auxilia a Injetar dependências
 * As classes ficam salvas em $container, então poderão ser sobrescritas
 * desde que todas as chamadas das classes estejam utilizando o intance() do Singleton
 * ao invés de instanciar a classe.
 */
class DependenceInjector {

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
	public static function getClassDi($class) {
		if (key_exists($class, static::$container)) {
			$class = static::$container[$class];
		}
		return $class;
	}

}
