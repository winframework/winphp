<?php

namespace Win\Templates;

use Win\Services\Alert;

/**
 * Exibe os Alertas da sessão
 */
class AlertBlock extends Block
{
	public function __construct($alerts = null)
	{
		parent::__construct('layout/alerts', ['alerts' => $alerts ?? Alert::popAll()]);
	}
}
