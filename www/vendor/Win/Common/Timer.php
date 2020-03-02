<?php

namespace Win\Common;

/**
 * Contador de Tempo
 *
 * Usado para medir o tempo de resposta de códigos
 *
 * <code>
 * $t = new Timer();
 * [code here]
 * echo $t->getTime();
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
	 * Retorna o tempo gasto (em microssegundos)
	 * @return float
	 */
	private function getMicroTime()
	{
		return microtime(true) - $this->startTime;
	}

	/**
	 * Retorna o tempo gasto (na unidade correta)
	 * @return string
	 */
	public function getTime()
	{
		$sec = $this->getMicroTime();
		$day = floor($sec / 86400);
		$sec -= $day * 86400;
		$hour = floor($sec / 3600);
		$sec -= $hour * 3600;
		$minute = floor($sec / 60);
		$sec -= $minute * 60;
		$microsec = ($sec - floor($sec)) * 1000;

		return
				(empty($day) ? '' : $day . 'd ') .
				(empty($hour) ? '' : $hour . 'h ') .
				(empty($minute) ? '' : $minute . 'm ') .
				floor($sec) . 's ' .
				$microsec . 'ms';
	}
}
