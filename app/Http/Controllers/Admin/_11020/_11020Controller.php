<?php

namespace app\Http\Controllers\Admin\_11020;

use App\Helpers\UserControl;
use App\Models\DTO\Admin\_11020;

/**
 * Controller do objeto _22010 - Consulta de Admin
 */
class _11020Controller extends UserControl {
	
	/**
     * Código do menu
     * @var int 
     */
    public $menu = 'admin/_11020';
    
	public function index()
    {
        $this->Menu()->consultar();
        
		return view(
            'admin._11020.index', [
            'menu'          => $this->menu
		]);  
    }
    
}