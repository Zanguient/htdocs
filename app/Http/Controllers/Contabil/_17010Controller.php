<?php

namespace App\Http\Controllers\Contabil;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DTO\Contabil\_17010;

class _17010Controller extends Controller
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
    
    public function apiCcontabil(Request $request)
    {
        
        $request = obj_case($request->all());
        $filtro 	= isset($request->FILTRO) ? $request->FILTRO : false;
        $analitica	= isset($request->CCONTABIL_TIPO) ? ($request->CCONTABIL_TIPO  == 'analitica' ? 'S' : 'N' ) : 'N';

        return _17010::pesquisaCContabil($filtro,$analitica);
    }

    /**
     * Pesquisa empresa de acordo com o que for digitado pelo usuário.
     * Função chamada via Ajax.
     * 
     * @param Request $request
    */
    public function pesquisa(Request $request)
    {
    	if( $request->ajax() ) {
    		$filtro 	= $request->get('filtro');
    		$analitica	= $request->get('ccontabil_tipo') == 'analitica' ? 'S' : 'N';
    		
			$ccontabils = _17010::pesquisaCContabil($filtro,$analitica);
			
    		$res = '<ul class="nav ccontabil">';
    
    		if( !empty($ccontabils) ) {
    				
    			foreach ($ccontabils as $ccontabil) {
    				
    				$res .= '<li>';
    				$res .= '<a href="#">'. $ccontabil->MASK .' - '. $ccontabil->DESCRICAO .'</a>';
    				$res .= '<input type="hidden" class="ccontabil-conta" value=\''. floatval($ccontabil->CONTA) .'\' />';
    				$res .= '<input type="hidden" class="ccontabil-descricao" value=\''. $ccontabil->DESCRICAO .'\' />';
    				$res .= '</li>';
    			}
    
    		}
    		else $res .= '<div class="nao-cadastrado">Conta Contábil n&atildeo cadastrada.</div>';
    
    		$res .= '</ul>';
    			
    		echo $res;
    	}
    }
    
}
