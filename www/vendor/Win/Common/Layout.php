<?php

namespace Win\Common;

use Win\Common\Utils\Str;

/**
 * Templates para Layouts em .PHTML
 * Layouts são a parte externa de um template
 * Ver arquivos em: "/templates/layouts"
 */
class Layout extends Template
{
	public static $dir = '/templates/layouts';

	public function __construct($layout, Template $child)
	{
		$class = get_class($child);
		$childName = lcfirst(substr($class, strrpos($class, '\\') + 1));
		parent::__construct($child::LAYOUT_PREFIX . '_' . $layout, [$childName => $child]);
	}
}
