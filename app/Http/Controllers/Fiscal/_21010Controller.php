<?php

namespace App\Http\Controllers\Fiscal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DTO\Fiscal\_21010;
use Illuminate\Support\Facades\Response;
use App\Models\DTO\Admin\_11010;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;



class _21010Controller extends Controller
{
    /**
     * Código do menu
     * @var int 
     */
    private $menu = 'fiscal/_21010';
    
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
    
    public function update(Request $request)
    {
    	//
    }
    
    public function destroy($id)
    {
    	//
    }

    /**
     * Pesquisa empresa de acordo com o que for digitado pelo usuário.
     * Função chamada via Ajax.
     * @param Request $request
    */
    public function find(Request $request)
    {
    	if( $request->ajax() ) {
    		//_11010::permissaoMenu(21010);
    		
    		$produto_id = $request->get('produto_id');
    		$filtro     = $request->get('filtro');
    		$data       = date('Y.m.01');
    		
			$operacoes = _21010::pesquisa($produto_id, $data, $filtro);
            
    		$res = '<ul class="nav operacao">';
    
    		if( !empty($operacoes) ) {
                
                $res .= '<li>';
                $res .= '<div class="titulo-lista">';
                $res .= '<span class="span-codigo">         Cód.</span>';
                $res .= '<span class="span-ccontabil">      C. Contábil</span>';
                $res .= '<span class="span-ccontabil-desc"> C. Contábil Descrição</span>';
                $res .= '<span class="span-ccusto">         C. Custo</span>';
                $res .= '<span class="span-ccusto-desc">    C. Custo Descrição</span>';
                $res .= '</div>';
                $res .= '</li>';    				
                
    			foreach ($operacoes as $operacao) {
    				
    				$res .= '<li>';
    				
                    $res .= '<a href="#">';
					$res .= '<span class="span-codigo">'.           $operacao->CODIGO               .'</span>';
					$res .= '<span class="span-ccontabil">'.        $operacao->CCONTABIL_MASK       .'</span>';
					$res .= '<span class="span-ccontabil-desc">'.   $operacao->CCONTABIL_DESCRICAO  .'</span>';
					$res .= '<span class="span-ccusto">'.           $operacao->CCUSTO_MASK          .'</span>';
					$res .= '<span class="span-ccusto-desc">'.      $operacao->CCUSTO_DESCRICAO     .'</span>';
                    $res .= '</a>';
                    $res .= '<input type="hidden" class="descricao" value="'.  $operacao->CODIGO    .'" />';
                    $res .= '<input type="hidden" class="codigo"    value="'.  $operacao->CODIGO    .'" />';
    				$res .= '<input type="hidden" class="ccontabil" value="'.  $operacao->CCONTABIL .'" />';
    				$res .= '<input type="hidden" class="ccusto"    value="'.  $operacao->CCUSTO    .'" />';
                    $res .= '</li>';
    			}
    		}
            else { $res .= '<div class="nao-cadastrado">Operação não cadastrada.</div>'; }
    
    		$res .= '</ul>';
            
    		return Response::json( $res );
    	}
    }
    
}
