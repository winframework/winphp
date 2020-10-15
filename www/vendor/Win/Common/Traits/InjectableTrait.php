<?php

namespace Win\Common\Traits;

use Win\Common\DI;

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
		return DI::instance(get_called_class());
	}
}
