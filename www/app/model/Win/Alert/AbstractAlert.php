<?php

namespace Win\Alert;

use Win\DesignPattern\SingletonTrait;

/**
 * Alertas
 * São mensagens armazenadas na sessão e exibidas ao usuárioTYPE
 */
abstract class AbstractAlert {

	use SingletonTrait;
}
