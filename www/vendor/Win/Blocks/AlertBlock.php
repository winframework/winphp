<?php

namespace Win\Blocks;

use Win\Blocks\Block;
use Win\Repositories\Alert;

/**
 * Exibe os Alerta da sessão
 */
class AlertBlock extends Block
{
	public function __construct($group = 'default')
	{
		$alerts = Alert::instance($group)->popAll() ?? [];
		parent::__construct('shared/alerts', ['alerts' => $alerts]);
	}
}
