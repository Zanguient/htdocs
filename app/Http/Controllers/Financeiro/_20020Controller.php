<?php

namespace App\Http\Controllers\Financeiro;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DTO\Financeiro\_20020;

class _20020Controller extends Controller
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
    
    public function find($id)
    {
        //
    }

        public function search(Request $request)
    {
    	if( $request->ajax() ) {
    		
    		$ccustos = _20020::listar($request->get('filtro'));
    
    		$res = '<ul class="nav ccusto">';
    
    		if( !empty($ccustos) ) {
    				
    			foreach ($ccustos as $ccusto) {
    				
    				$res .= '<li>';
    				$res .= '<a href="#">'. $ccusto->MASK .' - '. $ccusto->DESCRICAO .'</a>';
    				$res .= '<input type="hidden" class="ccusto-id" value="'. $ccusto->ID .'"" />';
    				$res .= '<input type="hidden" class="ccusto-descricao" value="'. $ccusto->DESCRICAO .'"" />';
    				$res .= '</li>';
    			}
    
    		}
    		else $res .= '<div class="nao-cadastrado">Centro de Custo n&atildeo cadastrado.</div>';
    
    		$res .= '</ul>';
    			
    		echo $res;
    	}
    }
}
