<?php

namespace Win\DesignPattern;

/**
 * Implementa Design Pattern Singleton
 *
 * Ao utilizar o método instance(), será buscado no DependenceInjector a classe correspondente
 * @see DependenceInjector
 * Isso possibilita criar classes extendidas sem comprometer as dependências da classe original
 */
trait Singleton {

	protected static $instance = [];

	/**
	 * Retorna a instância da Classe
	 *
	 * Este método é capaz de retornar uma classe extendida a partir do container em Dependence Injector
	 * @return static
	 */
	public static function instance() {
		$class = get_called_class();
		if (!isset(static::$instance[$class])):
			$classDi = static::getClassDi();
			static::$instance[$class] = new $classDi();
		endif;
		return static::$instance[$class];
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
