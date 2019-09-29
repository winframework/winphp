<?php

namespace Win\Core\Common;

/**
 * Contador de Tempo
 *
 * Usado para medir o tempo de resposta de códigos
 *
 * <code>
 * $t = new Timer();
 * [code here]
 * echo $t->time();
 * </code>
 */
class Timer
{
	private $startTime;

	/**
	 * Inicia a contagem de tempo
	 */
	public function __construct()
	{
		$this->startTime = microtime(true);
	}

	/**
	 * Reinicia a contagem de tempo
	 */
	public function reset()
	{
		$this->startTime = microtime(true);
	}

	/**
	 * Retorna o tempo gasto (na unidade correta)
	 * @return string
	 */
	public function time()
	{
		$secs = $this->getMicroTime();
		$days = floor($secs / 86400);
		$secs -= $days * 86400;
		$hours = floor($secs / 3600);
		$secs -= $hours * 3600;
		$minutes = floor($secs / 60);
		$secs -= $minutes * 60;
		$microSecs = ($secs - floor($secs)) * 1000;

		return
				(empty($days) ? '' : $days . 'd ') .
				(empty($hours) ? '' : $hours . 'h ') .
				(empty($minutes) ? '' : $minutes . 'm ') .
				floor($secs) . 's ' .
				$microSecs . 'ms';
	}

	/**
	 * Retorna o tempo gasto (em microssegundos)
	 * @return float
	 */
	private function getMicroTime()
	{
		return microtime(true) - $this->startTime;
	}
}
