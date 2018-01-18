<?php

namespace Win\Helper;

/**
 * Contador de Tempo
 *
 * Usado para medir o tempo de execução de códigos
 *
 * <code>
 * $t = new Win\Helper\Timer();
 * [code here]
 * echo $t->time();
 * </code>
 */
class Timer {

	private $startTime;

	/**
	 * Inicia a contagem de tempo
	 */
	public function __construct() {
		$this->startTime = microtime(true);
	}

	/**
	 * Reinicia a contagem de tempo
	 */
	public function reset() {
		$this->startTime = microtime(true);
	}

	/**
	 * Retorna o tempo gasto (em microsegundos)
	 * @return float
	 */
	private function getMicroTime() {
		return microtime(true) - $this->startTime;
	}

	/**
	 * Retorna o tempo gasto (na unidade correta)
	 * @return string
	 */
	public function time() {
		$segs = $this->getMicroTime();
		$days = floor($segs / 86400);
		$segs -= $days * 86400;
		$hours = floor($segs / 3600);
		$segs -= $hours * 3600;
		$minutes = floor($segs / 60);
		$segs -= $minutes * 60;
		$microsegs = ($segs - floor($segs)) * 1000;

		return
				(empty($days) ? "" : $days . "d ") .
				(empty($hours) ? "" : $hours . "h ") .
				(empty($minutes) ? "" : $minutes . "m ") .
				floor($segs) . "s " .
				$microsegs . "ms";
	}

}
