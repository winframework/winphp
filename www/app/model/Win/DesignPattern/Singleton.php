<?php

namespace Win\DesignPattern;

/**
 * Implementa Design Pattern Singleton
 *
 * Ao utilizar o metodo instance(), será buscado no DependenceInjector a classe correspondente
 * @see DependenceInjector
 * Isso possibilita criar classes extendidas sem comprometer as dependencias da classe original
 */
trait Singleton {

	protected static $instance = [];

	/**
	 * Retorna a instancia da Classe
	 *
	 * Este metodo é capaz de retornar uma classe extendida a partir do container em Dependence Injector
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
	 *
	 * Se existe uma classe personalizada em DependenceInjector::$container, então ela será usada
	 * @return string
	 */
	protected static function getClassDi() {
		$class = get_called_class();
		if (key_exists($class, DependenceInjector::$container)):
			$class = DependenceInjector::$container[$class];
		endif;
		return $class;
	}

	/**
	 * Não se deve usar o construtor de um objeto Singleton
	 */
	private final function __construct() {
		/* its not possible */
	}

}
