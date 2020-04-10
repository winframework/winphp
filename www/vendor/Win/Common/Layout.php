<?php

namespace Win\Common;

/**
 * Templates para Layouts em .PHTML
 * Layouts são a parte externa de um template
 * Ver arquivos em: "/app/templates/layouts"
 */
class Layout extends Template
{
	static $dir = '';

	public function __construct($layout, Template $content)
	{
		parent::__construct("{$content::$dir}/layouts/{$layout}", ['content' => $content]);
	}
}
