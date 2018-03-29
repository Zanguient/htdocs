<?php

namespace App\Http\Controllers\Chamados;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DTO\Chamados\_26010;
use Illuminate\Support\Facades\Response;
use App\Models\DTO\Admin\_11010;
use App\Helpers\Helpers;

class _26010Controller extends Controller
{
    /**
     * Código do menu
     * @var int 
     */
    private $menu = 'chamados/_26010';
    
    public function index()
    {        
        _11010::permissaoMenu($this->menu);

        /**
         * Informações do usuário conectado
         */
        $user = _11010::listar();
        
        /**
         * Lista os chamados não encerrados do centro de custo do usuário conectado
         */
        $res = _26010::listar([
            'DESTINO_CCUSTO' => [$user->CCUSTO],
            'STATUS_ENTRE'   => [1,99]   
        ]);

        /**
         * Ordena os chamados por sequencia do status, prioridade e id
         */
        $chamados = orderBy($res->CHAMADOS,'STATUS_SEQUENCIA', 'PRIORIDADE', 'ID');

        $i = 0;
        $x = 0;
        $arr_status = [];
        $chamados_aux = $chamados;
        foreach($chamados as $item){
            $i++;
            $x++;
            
            $current_status = next($chamados_aux);
            if ( empty($current_status) || $current_status->STATUS_ID != $item->STATUS_ID ) {
                array_push($arr_status, (object)[
                    'ID'         => $item->STATUS_ID,
                    'DESCRICAO'  => $item->STATUS_DESCRICAO,
                    'RGB'        => $item->STATUS_RGB,
                    'QTD'        => $i
                ]);          
                $i = 0;
            }            
        }      
        
        return view(
            'chamados._26010.index', [
            'chamados'   => $chamados,
            'arr_status' => $arr_status,
            'qtd_total'  => $x,
            ]);
    }

    public function create()
    {
    	//
    }

    public function store(Request $request)
    {    	
   		//
    }

    public function show(Request $request,$id)
    {
        _11010::permissaoMenu($this->menu);
        
        $chamado = _26010::listar(['ID' => [$id]])->CHAMADOS[0];
        
        if ( strripos($request->url(), 'show') ) {       
            $view = 'chamados._26010.show.body';
        } else {
            $view = 'chamados._26010.show';
        }
        
        return view($view,[
            'chamado' => $chamado
        ]);
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
}
