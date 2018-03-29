<?php

namespace app\Http\Controllers\Ppcp\_22030;

use App\Helpers\UserControl;
use App\Models\DTO\Ppcp\_22030;

/**
 * Controller do objeto _22010 - Consulta de Ppcp
 */
class _22030Controller extends UserControl {
	
	/**
     * Código do menu
     * @var int 
     */
    public $menu = 'ppcp/_22030';
    
	public function index()
    {
        $this->Menu()->consultar();
        
		return view(
            'ppcp._22030.index', [
            'menu'          => $this->menu
		]);  
    }
    
}