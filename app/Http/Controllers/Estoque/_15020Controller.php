<?php

namespace app\Http\Controllers\Estoque;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\DTO\Admin\_11010;
use App\Models\DTO\Estoque\_15020;

/**
 * Controller do objeto _15020 - Localização.
 */
class _15020Controller extends Controller {
	
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'estoque/_15020';
	
	/**
	 * Listar Localização para cadastro.
	 * Utilizado por include e Ajax.
	 * 
	 * @return array
	 */
	public function listarSelect(Request $request) {
		
		if ( $request->ajax() ) {
			
//			_11010::permissaoMenu($this->menu, null, 'Listar (include)');
			return Response::json( _15020::listarSelect() );
		}
		
	}
}
