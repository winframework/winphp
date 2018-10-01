<?php

namespace Win\DesignPattern;

/**
 * Implementa Design Pattern Singleton
 *
 * Ao utilizar o método instance(), será buscado no DependenceInjector a classe correspondente
 * @see DependenceInjector
 * Isso possibilita criar classes extendidas sem comprometer as dependências da classe original
 */
trait SingletonTrait {

	protected static $instance = [];

	/**
	 * Retorna a instância da Classe
	 *
	 * Este método é capaz de retornar uma classe extendida a partir do container em Dependence Injector
	 * @param string $alias Com este parâmetro é possível criar múltiplas instâncias
	 * @return static
	 */
	public static function instance($alias = 'default') {
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
	protected static function getClassDi() {
		return DependenceInjector::getClassDi(get_called_class());
	}

	/**
	 * Não se deve usar o construtor de um objeto Singleton
	 */
	private final function __construct() {
		/* its not possible */
	}

}
