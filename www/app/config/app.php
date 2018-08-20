<?php

/* Configurações básicas da aplicação */
$config = [
	'name' => 'winPHP Framework',
	'BASE_PATH', getcwd()
];


/* Configurações do PHP */
ini_set('date.timezone', 'America/Sao_Paulo');



/* Recaptcha */
\Win\Html\Form\ReCaptcha::$siteKey = '6LcDAioUAAAAAIMAHCFz02fuq7at3C6gf9_DIGum';
\Win\Html\Form\ReCaptcha::$secretKey = '6LcDAioUAAAAAKLXofatfq3FP2TLkgkIQSbJwto0';
