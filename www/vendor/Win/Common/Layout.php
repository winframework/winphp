<?php

namespace Win\Common;

/**
 * Templates para Layouts em .PHTML
 * Layouts são a parte externa de um template
 */
class Layout extends Template
{
	public function __construct($layout, Template $content)
	{
		parent::__construct($layout, ['content' => $content]);
	}
}
