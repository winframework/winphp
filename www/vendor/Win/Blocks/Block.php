<?php

namespace Win\Blocks;

use Win\Common\Template;

/**
 * Pequeno bloco .PHTML que é exibido dentro de outros templates
 * 
 * Blocos não possuem layout
 */
class Block extends Template
{
	public function __construct($file, $data = [])
	{
		parent::__construct($file, $data);
	}
}
