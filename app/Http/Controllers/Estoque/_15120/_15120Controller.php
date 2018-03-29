<?php

namespace app\Http\Controllers\Estoque\_15120;

use App\Helpers\UserControl;

/**
 * Controller do objeto _15120 - Consulta de Estoque
 */
class _15120Controller extends UserControl {
	
	/**
     * Código do menu
     * @var int 
     */
    public $menu = 'estoque/_15120';
    
	public function index()
    {
        $this->Menu()->consultar();
        
		return view(
            'estoque._15120.index', [
            'menu'          => $this->menu
		]);  
    }
    
}