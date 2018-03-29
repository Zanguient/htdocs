<?php

namespace app\Http\Controllers\Patrimonio\_16010;

use App\Helpers\UserControl;
use App\Models\DTO\Patrimonio\_16010;

/**
 * Controller do objeto _22010 - Consulta de Patrimonio
 */
class _16010Controller extends UserControl {
	
	/**
     * Código do menu
     * @var int 
     */
    public $menu = 'patrimonio/_16010';
    
	public function index($id = null)
    {
        $this->Menu()->consultar();
        
		return view(
            'patrimonio._16010.index', [
            'menu' => $this->menu,
            'id'   => $id
		]);  
    }
    
}