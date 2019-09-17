<?php

namespace Win\Database\Orm;

/**
 * Errors
 */
abstract class ErrorEnum
{
	const ON_DELETE = 'Ocorreu um erro durante a exclusão de dados.';
	const ON_SAVE = 'Ocorreu um erro durante a gravação de dados.';
	const ON_FETCH = 'Ocorreu um erro durante a consulta de dados.';
}
