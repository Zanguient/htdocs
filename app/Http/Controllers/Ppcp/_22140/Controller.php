<?php

namespace app\Http\Controllers\Ppcp\_22140;

use App\Helpers\UserControl;

/**
 * Controller do objeto _22140 - Consulta de Ppcp
 */
class Controller extends UserControl {
	
	/**
     * Código do menu
     * @var int 
     */
    public $menu = 'ppcp/_22140';
    
	public function index()
    {
        $permissaoMenu = $this->Menu()->consultar();
        
		return view(
            'ppcp._22140.index', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu
		]);  
    }
}