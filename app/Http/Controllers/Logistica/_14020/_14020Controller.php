<?php

namespace app\Http\Controllers\Logistica\_14020;

use App\Helpers\UserControl;
use App\Models\DTO\Logistica\_14020;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _22010 - Consulta de Logistica
 */
class _14020Controller extends UserControl {
	
	/**
     * Código do menu
     * @var int 
     */
    public $menu = 'logistica/_14020';
    
	public function index()
    {
        $this->Menu()->consultar();
        
		return view(
            'logistica._14020.index', [
            'menu'          => $this->menu
		]);  
    }
    
	public function show($id, $origem_id = null, $transportadora_id = null)
    {
        $this->Menu()->consultar();
        
            $origem = null;
            $calcular = false;
            if ( $origem_id != null ) {
                $origem = $id;
                $id     = null;
                $calcular = true;
            }
            
		return view(
            'logistica._14020.show', [
            'menu'              => $this->menu,
            'id'                => $id,
            'origem'            => $origem,
            'origem_id'         => $origem_id,
            'transportadora_id' => $transportadora_id,
            'calcular'          => $calcular
        ]);  
    }
    
	public function comparar()
    {
        $this->Menu()->consultar();
        
		return view(
            'logistica._14020.comparar', [
            'menu'              => $this->menu,
        ]);  
    }
    
}