<?php

namespace App\Http\Controllers\Opex;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DTO\Opex\_25200;

class _25200Controller extends Controller
{
            
    public function index()
    {
    	//
    }

    public function create()
    {
    	//
    }
    
    public function store(Request $request)
    {    	
    	//
    }
    
    public function show($id)
    {
        //
    }
    
    public function edit($id)
    {
        //
    }
    
    public function update(Request $request, $id)
    {
        //
    }
    
    public function destroy($id)
    {
        //
    }
    
    public function filtrar(Request $request)
    {
    	if( $request->ajax() ) {
            
    		$indicadores = _25200::filtrar($request->get('filtro'));

    		$res = '<ul class="nav indicadores">';
    
    		if( !empty($indicadores) ) {
    				
    			foreach ($indicadores as $indicador) {
    				
    				$res .= '<li>';
    				$res .= '<a href="#">'. $indicador->MASK .' - '. $indicador->DESCRICAO .'</a>';
    				$res .= '<input type="hidden" class="indicadores-id" value="'. $indicador->ID .'"" />';
    				$res .= '<input type="hidden" class="indicadores-descricao" value="'. $indicador->DESCRICAO .'"" />';
    				$res .= '</li>';
    			}
    
    		}
    		else $res .= '<div class="nao-cadastrado">Indicador n&atildeo cadastrado.</div>';
    
    		$res .= '</ul>';
    			
    		echo $res;
    	}
    }

}
