<?php

namespace Win\Mvc;

/**
 * Sistema de Layout
 *
 * Define qual será o arquivo principal de Layout
 *
 * É possível criar vários arquivos de Layout e então escolher no Controller
 * qual Layout será carregado.
 * Ex: 'main', 'empty', '2-column', '3-column'
 */
class Layout extends Block
{
	public static $dir = '/app/templates';
}