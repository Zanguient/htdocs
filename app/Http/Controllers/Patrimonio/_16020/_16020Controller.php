<?php

namespace app\Http\Controllers\Patrimonio\_16020;

use App\Helpers\UserControl;
use App\Models\DTO\Patrimonio\_16020;

/**
 * Controller do objeto _22010 - Consulta de Patrimonio
 */
class _16020Controller extends UserControl {
	
	/**
     * Código do menu
     * @var int 
     */
    public $menu = 'patrimonio/_16020';
    
	public function index()
    {
        $this->Menu()->consultar();
        
		return view(
            'patrimonio._16020.index', [
            'menu'          => $this->menu
		]);  
    }
    
}