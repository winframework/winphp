<?php

namespace Win\Common;

/**
 * Contador de Desempenho
 *
 * Usado para analisar desempenho de códigos códigos
 *
 * <code>
 * $b = new Benchmark();
 * [code here]
 * echo $b->getTime();
 * </code>
 */
class Benchmark
{
	private $startTime;
	private $memory;

	/**
	 * Inicia a contagem de tempo
	 */
	public function __construct()
	{
		$this->startTime = microtime(true);
		$this->memory = memory_get_usage();
	}

	/**
	 * Reinicia a contagem de tempo
	 */
	public function reset()
	{
		$this->startTime = microtime(true);
		$this->memory = memory_get_usage();
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
		$microSec = ($sec - floor($sec)) * 1000;

		return
				(empty($day) ? '' : $day . 'd ') .
				(empty($hour) ? '' : $hour . 'h ') .
				(empty($minute) ? '' : $minute . 'm ') .
				floor($sec) . 's ' .
				$microSec . 'ms';
	}

	/**
	 * Retorna a quantidade de memória gasta
	 * @return int
	 */
	public function getMemory()
	{
		return (memory_get_usage() - $this->memory) / (1024 * 1024);
	}
}
