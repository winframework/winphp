<?php

use Win\InfraServices\ReCaptcha;

ini_set('display_errors', 0);

/* Constantes */
define('APP_NAME', 'winPHP Framework');

/* Configurações do PHP */
ini_set('date.timezone', 'America/Sao_Paulo');

/* ReCaptcha */
ReCaptcha::$siteKey = '6LcDAioUAAAAAIMAHCFz02fuq7at3C6gf9_DIGum';
ReCaptcha::$secretKey = '6LcDAioUAAAAAKLXofatfq3FP2TLkgkIQSbJwto0';
