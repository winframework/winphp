<?php

/* Configurações básicas da aplicação */
$config = [
	'name' => 'winPHP Framework'
];


/* Configurações do PHP */
ini_set('date.timezone', 'America/Sao_Paulo');


/* Recaptcha */
Win\Widget\ReCaptcha::$siteKey = '6LcDAioUAAAAAIMAHCFz02fuq7at3C6gf9_DIGum';
Win\Widget\ReCaptcha::$secretKey = '6LcDAioUAAAAAKLXofatfq3FP2TLkgkIQSbJwto0';
