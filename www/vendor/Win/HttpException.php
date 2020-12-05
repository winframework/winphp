<?php

namespace Win;

use Exception;

/**
 * Erro Http
 * 403, 404, 500, etc
 * 
 * @example throw new HttpException('Pagina não encontrada', 404);
 * @example throw new Exception("Autenticação obrigatória", 401);
 */
class HttpException extends Exception
{
}
