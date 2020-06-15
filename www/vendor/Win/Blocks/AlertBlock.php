<?php

namespace Win\Blocks;

use Win\Blocks\Block;
use Win\Services\Alert;

/**
 * Exibe os Alertas da sessão
 */
class AlertBlock extends Block
{
	public function __construct($group = '')
	{
		parent::__construct('shared/alerts', ['alerts' => Alert::popAll($group)]);
	}
}
