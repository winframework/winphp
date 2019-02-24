<?php

namespace Win\Database\Orm\Traits;

trait DebugTrait
{
	/** @var bool */
	protected $debug;

	/** Liga o debug */
	public function debugOn()
	{
		$this->debug = true;
	}

	/** Desliga o debug */
	public function debugOff()
	{
		$this->debug = false;
	}

	/** @return bool */
	public function getDebugMode()
	{
		return $this->debug;
	}
}
