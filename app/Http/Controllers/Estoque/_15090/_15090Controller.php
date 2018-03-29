<?php

namespace app\Http\Controllers\Estoque\_15090;

use App\Helpers\UserControl;

/**
 * Controller do objeto _22010 - Consulta de Estoque
 */
class _15090Controller extends UserControl {
	
	/**
     * Código do menu
     * @var int 
     */
    public $menu = 'estoque/_15090';
    
	public function index()
    {
        $this->Menu()->consultar();
        
		return view(
            'estoque._15090.index', [
            'menu'          => $this->menu
		]);  
    }
    
}