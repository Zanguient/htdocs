<?php

namespace App\Http\Controllers\Financeiro;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DTO\Financeiro\_20030;
use Illuminate\Support\Facades\Response;
use App\Models\Conexao\_Conexao;

/**
 * • Controller _20030 - Gestão de Centros de Custo
 */
class _20030Controller extends Controller
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
    
    /**
    * • Retorna um sentro de custo (desc e id)
    */
    public function getCCusto($id)
    {
        return _20030::getCCusto($id);
    }
    
    public function pesquisaCCusto(Request $request)
    {
    	if( $request->ajax() ) {
    		
    		$ccustos = _20030::pesquisaCCusto($request->get('filtro'));
    
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
    
    public function apiCcusto(Request $request)
    {
        
        $con = new _Conexao;  
        
        $dto_22200 = new _20030($con);
        return $dto_22200->selectCcusto(obj_case($request->all()));
    }

    /**
     * Pesquisa todos os Centro de Custos.
     * @return json
     */
    public function pesquisaCCustoTodos()
    {
        return Response::json( _20030::pesquisaCCustoTodos() );
    }
    
    public function pesquisaCCusto2(Request $request)
    {
    	if( $request->ajax() ) {
    		
    		$ccustos = _20030::pesquisaCCusto2($request->get('filtro'));
    
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
    
    public function pesquisaCCustoIndicador(Request $request)
    {
    	if( $request->ajax() ) {
    		
    		$ccustos = _20030::pesquisaCCustoIndicador($request->get('filtro'));
    
    		$res = '<ul class="nav ccustoindicador">';
    
    		if( !empty($ccustos) ) {
    				
    			foreach ($ccustos as $ccusto) {
    				
    				$res .= '<li>';
    				$res .= '<a href="#">'. $ccusto->MASK .' - '. $ccusto->DESCRICAO .'</a>';
    				$res .= '<input type="hidden" class="ccustoindicador-id" value="'. $ccusto->ID .'"" />';
    				$res .= '<input type="hidden" class="ccustoindicador-descricao" value="'. $ccusto->DESCRICAO .'"" />';
    				$res .= '</li>';
    			}
    
    		}
    		else $res .= '<div class="nao-cadastrado">Centro de Custo n&atildeo cadastrado.</div>';
    
    		$res .= '</ul>';
    			
    		echo $res;
    	}
    }
}
