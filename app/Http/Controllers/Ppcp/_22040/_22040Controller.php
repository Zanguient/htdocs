<?php

namespace app\Http\Controllers\Ppcp\_22040;

use App\Helpers\UserControl;

/**
 * Controller do objeto _22010 - Consulta de Ppcp
 */
class _22040Controller extends UserControl {
	
	/**
     * Código do menu
     * @var int 
     */
    public $menu = 'ppcp/_22040';
    
	public function index()
    {
        $this->Menu()->consultar();
        
		return view(
            'ppcp._22040.index', [
            'menu'          => $this->menu
		]);  
    }
    
}