<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\DTO\Admin\_11010;
use App\Models\DTO\Admin\_11020;

/**
 * Controller do objeto _11020 - Estabelecimento.
 */
class _11020Controller extends Controller {
	
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'admin/_11020';
	
	/**
	 * Listar Estabelecimentos para cadastro.
	 * Utilizado por include e Ajax.
	 * 
	 * @return array
	 */
	public function listarSelect(Request $request) {
		
		if ( $request->ajax() ) {
					
			$estab_perm = _11020::listarSelect();
			
			//se a consulta acima não retornar nenhum valor, 
			//o usuário poderá ver todos os estabelecimentos.
			if( count($estab_perm) === 0 ) {
				$estab_perm = _11020::listarTodos();
			}
			
			return Response::json( $estab_perm );
		}
		
	}
}
