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
	 * Lista com nomes das classes extendidas
	 * @var string[]
	 */
	public static $container = [];

}
