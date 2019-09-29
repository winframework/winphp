<?php

namespace Win\Core\Common\Traits;

use Win\Core\Common\DependenceInjector;

/**
 * Implementa Design Pattern Singleton
 *
 * Ao utilizar o método instance(), DependenceInjector irá buscar a classe correspondente
 * @see DependenceInjector
 * Isso possibilita criar sub-classes sem comprometer as dependências da classe original
 */
trait SingletonTrait
{
	/**
	 * Array com todas as instâncias
	 * @var array
	 */
	protected static $instance = [];

	/**
	 * Retorna a instância da Classe
	 * Diferentes $alias retornam diferentes instâncias
	 * @param string $alias
	 * @return static
	 */
	public static function instance($alias = 'default')
	{
		$class = get_called_class();
		if (!isset(static::$instance[$class][$alias])) {
			$classDi = static::getClassDi();
			$instance = new $classDi();
			static::$instance[$class][$alias] = $instance;
		}

		return static::$instance[$class][$alias];
	}

	/**
	 * Retorna o nome a classe que deverá ser usada no $instance
	 * @return string
	 */
	protected static function getClassDi()
	{
		return DependenceInjector::getClassDi(get_called_class());
	}

	/**
	 * Não se deve usar o construtor de um objeto Singleton
	 */
	final private function __construct()
	{
		/* its not possible */
	}
}
