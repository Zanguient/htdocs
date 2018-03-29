<?php

namespace app\Http\Controllers\Ppcp\_22120;

use App\Helpers\UserControl;

/**
 * Controller do objeto _22010 - Consulta de Ppcp
 */
class _22120Controller extends UserControl {
	
	/**
     * Código do menu
     * @var int 
     */
    public $menu = 'ppcp/_22120';
    
	public function index()
    {
        $this->Menu()->consultar();
        
		return view(
            'ppcp._22120.index', [
            'menu'          => $this->menu
		]);  
    }
    
	public function remessaIntermediaria()
    {
        $this->Menu()->incluir();
        
		return view(
            'ppcp._22120.remessa-intermediaria.index', [
            'menu'          => $this->menu
		]);  
    }
    
	public function remessaComponente()
    {
        $this->Menu()->incluir();
        
		return view(
            'ppcp._22120.remessa-componente.index', [
            'menu'          => $this->menu
		]);  
    }
}