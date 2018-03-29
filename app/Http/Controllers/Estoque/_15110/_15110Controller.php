<?php

namespace app\Http\Controllers\Estoque\_15110;

use App\Helpers\UserControl;

/**
 * Controller do objeto _22010 - Consulta de Estoque
 */
class _15110Controller extends UserControl {
	
	/**
     * Código do menu
     * @var int 
     */
    public $menu = 'estoque/_15110';
    
	public function index()
    {
        $this->Menu()->consultar();
        
		return view(
            'estoque._15110.index', [
            'menu'          => $this->menu
		]);  
    }
    
}