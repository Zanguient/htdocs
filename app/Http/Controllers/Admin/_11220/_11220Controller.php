<?php

namespace app\Http\Controllers\Admin\_11220;

use App\Helpers\UserControl;
use App\Models\DTO\Admin\_11220;

/**
 * Controller do objeto _22010 - Consulta de Admin
 */
class _11220Controller extends UserControl {
	
	/**
     * Código do menu
     * @var int 
     */
    public $menu = 'admin/_11220';
    
	public function index()
    {
        $permissaoMenu = $this->Menu()->consultar();
        
		return view(
            'admin._11220.index', [
            'menu'          => $this->menu,
            'permissaoMenu' => $permissaoMenu
		]);  
    }
    
}