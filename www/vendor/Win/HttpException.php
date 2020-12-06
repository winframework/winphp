<?php

namespace Win;

use Exception;

/**
 * Erro Http
 * - Lançar essa exceção irá causar um 403, 404, 500, etc
 * 
 * @example throw new HttpException('Pagina não encontrada', 404);
 * @example throw new HttpException("Autenticação obrigatória", 401);
 */
class HttpException extends Exception
{
}
