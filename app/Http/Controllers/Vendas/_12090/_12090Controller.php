<?php

namespace app\Http\Controllers\Vendas\_12090;

use App\Helpers\UserControl;

/**
 * Controller do objeto _22010 - Consulta de Vendas
 */
class _12090Controller extends UserControl {
	
	/**
     * Código do menu
     * @var int 
     */
    public $menu = 'vendas/_12090';
    
	public function index()
    {
        $permissaoMenu = $this->Menu()->consultar();
        
		return view(
            'vendas._12090.ng.index', [
            'menu'          => $this->menu
		]);  
    }
}