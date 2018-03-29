<?php

namespace app\Http\Controllers\Compras\_13030;

use App\Helpers\UserControl;

/**
 * Controller do objeto _22010 - Consulta de Compras
 */
class _13030Controller extends UserControl {
	
	/**
     * Código do menu
     * @var int 
     */
    public $menu = 'compras/_13030';
    
	public function index()
    {
        $permissaoMenu = $this->Menu()->consultar();
        
		return view(
            'compras._13030.ng.index', [
            'menu'          => $this->menu,
            'permissaoMenu' => $permissaoMenu
		]);  
    }
}