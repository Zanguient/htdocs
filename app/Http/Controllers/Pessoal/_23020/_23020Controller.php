<?php

namespace app\Http\Controllers\Pessoal\_23020;

use App\Helpers\UserControl;
use App\Models\DTO\Pessoal\_23020;

/**
 * Controller do objeto _22010 - Consulta de Pessoal
 */
class _23020Controller extends UserControl {
	
	/**
     * Código do menu
     * @var int 
     */
    public $menu = 'pessoal/_23020';
    
	public function index()
    {
        $this->Menu()->consultar();
        
		return view(
            'pessoal._23020.index', [
            'menu'          => $this->menu
		]);  
    }
    
}