<?php

namespace Win\Mvc;

/**
 * Sistema de Layouts
 *
 * Gerencia qual será o arquivo principal de layout
 *
 * É possível criar varios arquivos de layout e alterar-lo no
 * Controller de acordo com a necessidade
 * Ex: main.phtml, 2-column.phtml, 3-column.phtml
 */
class Layout extends Block {

	public static $dir = '/app/block/layout/';

}
