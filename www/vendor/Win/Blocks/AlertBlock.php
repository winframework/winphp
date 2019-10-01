<?php

namespace Win\Blocks;

use Win\Blocks\Block;
use Win\Repositories\Alert;

/**
 * Exibe os Alerta da sessão
 */
class AlertBlock extends Block
{
	/** @var string */
	const BLOCK = 'alerts';

	public function __construct($group = '')
	{
		$alerts = Alert::instance($group)->popAll() ?? [];
		parent::__construct(static::BLOCK, ['alerts' => $alerts]);
	}
}
