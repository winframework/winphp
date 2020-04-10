<?php

namespace Win\Blocks;

use Win\Common\Template;

/**
 * Blocos .PHTML
 * Pequeno arquivo .phtml que é chamado em templates, views, emails, etc
 */
class Block extends Template
{
	public function __construct($file, $data = [])
	{
		parent::__construct($file, $data);
	}
}
