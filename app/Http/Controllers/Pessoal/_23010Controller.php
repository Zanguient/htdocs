<?php

namespace app\Http\Controllers\Pessoal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\DTO\Admin\_11010;
use App\Models\DTO\Pessoal\_23010;


/**
 * Controller do objeto _23010 - Turno.
 */
class _23010Controller extends Controller {
	
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'pessoal/_23010';
	
	/**
	 * Listar Turno para cadastro.
	 * Utilizado por include e Ajax.
	 * 
	 * @return array
	 */
	public function listarSelect(Request $request) {
		
		if ( $request->ajax() ) {
			
//			_11010::permissaoMenu($this->menu, null, 'Listar (include)');
			return Response::json( _23010::listarSelect() );
		}
		
	}
}
