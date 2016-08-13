<?php

namespace Win\Connection;

/**
 * Conexão com banco de dados
 *
 */
interface DataBase {

	/**
	 * Inicia a conexao
	 * @param string[] $dbConfig
	 */
	public function __construct($dbConfig);
}
