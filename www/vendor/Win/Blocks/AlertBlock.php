<?php

namespace Win\Blocks;

use Win\Blocks\Block;
use Win\Repositories\Alert;

/**
 * Exibe os Alertas da sessão
 */
class AlertBlock extends Block
{
	public function __construct($group = '')
	{
		$alerts = Alert::popAll($group) ?? [];
		parent::__construct('shared/alerts', ['alerts' => $alerts]);
	}
}
