<?php

namespace Win\DesignPattern;

/**
 * Dependence Injector
 *
 * Auxilia a Injetar dependencias
 * As classes ficam salvas em $container, então poderao ser sobreescritas
 * desde que todas as chamadas das classes estejam utilizando o intance() do Singleton
 * ao inves de instanciar a classe.
 */
class DependenceInjector {

	/**
	 * Lista com nomes das classes extendidas
	 * @var string[]
	 */
	public static $container = [];

}
