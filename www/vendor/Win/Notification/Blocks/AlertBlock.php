<?php

namespace Win\Notification\Blocks;

use Win\Core\Block;
use Win\Notification\Repositories\Alert;

/**
 * Exibe os Alerta da sessão
 */
class AlertBlock extends Block
{
	/** @var string */
	const BLOCK = 'notification/alerts';

	public function __construct($group = '')
	{
		$alerts = Alert::instance($group)->popAll() ?? [];

		parent::__construct(static::BLOCK, ['alerts' => $alerts]);
	}
}
