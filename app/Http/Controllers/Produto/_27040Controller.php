<?php

namespace App\Http\Controllers\Produto;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\DTO\Admin\_11010;
use App\Models\DTO\Produto\_27040;

/**
 * Controller do objeto _27040 - Grade.
 */
class _27040Controller extends Controller {
	
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'produto/_27040';
	
	/**
	 * Listar tamanhos do produto.
	 * Utilizado por include e Ajax.
	 * 
	 * @return array
	 */
	public function listarTamanho(Request $request) {		
		return Response::json( _27040::listarTamanho($request->id_prod) );
		
	}
}
