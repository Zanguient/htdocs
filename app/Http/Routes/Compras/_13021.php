<?php
  
	/**
	* Rotas do objeto _13021
	* @package Compras
	* @category Rotas
	*/

	Route::get  ('0/{orcamento_id}', 'Compras\_13021Controller@ver');
	Route::patch('0/{orcamento_id}', ['as' => '_13021.gravar', 'uses' => 'Compras\_13021Controller@gravar']);