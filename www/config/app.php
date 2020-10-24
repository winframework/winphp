<?php

/** Constantes */
define('APP_NAME', 'winPHP Framework');

/** Configurações do PHP */
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

/** ReCaptcha */
define('RECAPTCHA_SITE_KEY', '6LcDAioUAAAAAIMAHCFz02fuq7at3C6gf9_DIGum');
define('RECAPTCHA_SECRET_KEY', '6LcDAioUAAAAAKLXofatfq3FP2TLkgkIQSbJwto0');

session_start();