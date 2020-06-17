<?php

namespace Win\Common\Traits;

use Win\Common\DependenceInjector as DI;

/**
 * Comportamento injetável
 */
trait InjectableTrait
{

	/**
	 * Cria instância da classe via DI
	 * @return static
	 */
	public static function instance()
	{
		return DI::make(get_called_class());
	}
}
