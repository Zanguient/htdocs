<?php

namespace App\Http\Controllers\Ppcp;

use App\Http\Controllers\Controller;
use App\Models\DTO\Admin\_11010;
use App\Models\DTO\Ppcp\_22030;
use App\Models\DTO\Ppcp\_22040;
use App\Models\DTO\Ppcp\_22070;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

/**
 * Controller do menu Reprogramação de Talões
 */
class _22070Controller extends Controller
{  
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'ppcp/_22070';
    
    public function index()
    {
        $permissaoMenu = _11010::permissaoMenu($this->menu);
        
        return view(
            'ppcp._22070.index', [
                'permissaoMenu' => $permissaoMenu,
				'menu'			=> $this->menu
            ]);
    }

    public function create()
    {
        _11010::permissaoMenu($this->menu,'INCLUIR');
    }

    public function store(Request $request)
    {    	
        _11010::permissaoMenu($this->menu,'INCLUIR','Fixando Dados');
    }

    public function show(Request $request,$id)
    {
        $permissaoMenu = _11010::permissaoMenu($this->menu,null,'Visualizando o talão ' . $id);
    }
    
    public function edit($id)
    {
        _11010::permissaoMenu($this->menu,'ALTERAR');
    }
    
    public function update(Request $request)
    {
        _11010::permissaoMenu($this->menu,'ALTERAR','Fixando Dados');
    }
    
    public function destroy($id)
    {
        _11010::permissaoMenu($this->menu,'ALTERAR','Excluindo talão ' . $id);
    }
    
    public function filtrar(Request $request)
    {
        $param = (object)[];

        /**
         * Retorno da consulta
         */
        $param->RETORNO              = ['TALAO'];    
        $param->PROGRAMACAO_STATUS   = [0,1];
        $param->PROGRAMACAO_DATAHORA = [date('Y.m.d 00:00:00',strtotime($request->datahora_inicio)),date('Y.m.d 23:59:59',strtotime($request->datahora_fim))];
        
        empty($request->estabelecimento_id) ?: $param->ESTABELECIMENTO_ID = $request->estabelecimento_id;
        empty($request->gp_id             ) ?: $param->GP_ID              = $request->gp_id             ;
        empty($request->up_id             ) ?: $param->UP_ID              = $request->up_id             ;
        empty($request->estacao           ) ?: $param->ESTACAO            = $request->estacao           ;
        
        /**
         * Realiza a consulta
         */
        $taloes = _22040::listar($param)->TALAO;

        if ( !isset($taloes[0]->REMESSA_ID) ) {
            log_erro('Não há registros à exibir.');
        }  
        
        orderBy($taloes, 'UP_DESCRICAO', 'ESTACAO_DESCRICAO', 'DATAHORA_INICIO');

        /**
         * Abre informações das UP's / ESTAÇÕES do grupo de produção da remessa
         */
        $param_up = (object)[];
        $param_up->RETORNO = ['GP_UP_ESTACAO'];    
        $param_up->STATUS  = [1];    
        
        empty($request->gp_id  ) ?: $param_up->GP      = $request->gp_id  ;
        empty($request->up_id  ) ?: $param_up->UP      = $request->up_id  ;
        empty($request->estacao) ?: $param_up->ESTACAO = $request->estacao;
        
        $up_estacoes = _22030::listar($param_up)->GP_UP_ESTACAO;
        
        /**
         * Retira o array de UP da consulta de UP/ESTACAO
         */
        $arr_up = [];
        $up_estacoes_aux = $up_estacoes;

        foreach($up_estacoes as $item){

            $next = next($up_estacoes_aux);

            if ( empty($next) || $next->UP_ID != $item->UP_ID ) {
                array_push($arr_up, (object)[
                    'ID'        => $item->UP_ID,
                    'DESCRICAO' => $item->UP_DESCRICAO,
                    'STATUS'    => $item->UP_STATUS
                ]);   
            }                    
        }   
        
		return view('ppcp._22070.index.body',[
            'taloes'         => $taloes,
            'ups'            => $arr_up,
            'estacoes'       => $up_estacoes,
            'menu'           => $this->menu
		]);
    }
    
    public function reprogramar(Request $request)
    {   
        _11010::permissaoMenu($this->menu,'ALTERAR','Reprogramando Gp: ' . $request->gp_descricao);
        
        $estabelecimento_id      = $request->estabelecimento_id;
        $gp_id                   = $request->gp_id;
        $origem_up_id            = $request->origem_up_id;
        $origem_estacao          = $request->origem_estacao;
        $origem_datahora_inicio  = strtotime($request->origem_datahora_inicio);
        $origem_data             = strtotime($request->origem_data);
        $destino_up_id           = $request->destino_up_id;
        $destino_estacao         = $request->destino_estacao;
        $destino_datahora_inicio = strtotime($request->destino_datahora_inicio);
        $destino_data            = strtotime($request->destino_data);
        $time                    = strtotime('2000-01-01 00:00');
        $destino_taloes          = (array) arrayToObject($request->destino_taloes);
        $taloes                  = [];
        $reprogramar_origem      = [];
        $reprogramar_destino     = [];
        
        if ( ( ( $origem_up_id != $destino_up_id ) || ( $origem_estacao != $destino_estacao ) ) ||
             ( ( $origem_up_id == $destino_up_id ) && ( $origem_estacao == $destino_estacao ) && ( $origem_datahora_inicio < $destino_datahora_inicio ) )
           ) {
            $datahora_inicio = $origem_datahora_inicio ;
        } else {
            $datahora_inicio = $destino_datahora_inicio;
        }
                
        if ( ( ( $origem_up_id != $destino_up_id ) || ( $origem_estacao != $destino_estacao ) ) ||
             ( ( $origem_up_id == $destino_up_id ) && ( $origem_estacao == $destino_estacao ) && ( $destino_datahora_inicio > $origem_datahora_inicio ) )
           ) {
            $destino_push = (object)[
                'PROGRAMACAO_TIPO'   => 'A', // A -> ID DO TALÃO ACUMULADO (VWREMESSA_TALAO.ID)
                'ESTABELECIMENTO_ID' => $estabelecimento_id,
                'GP_ID'              => $gp_id,
                'UP_ID'              => $destino_up_id,
                'ESTACAO'            => $destino_estacao,
                'DATAHORA_INICIO'    => date('Y.m.d H:i:s',$destino_datahora_inicio),
            ];
            array_push($reprogramar_destino, $destino_push);  
        }
        
        $origem_push = (object)[
            'PROGRAMACAO_TIPO'   => 'A', // A -> ID DO TALÃO ACUMULADO (VWREMESSA_TALAO.ID)
            'ESTABELECIMENTO_ID' => $estabelecimento_id,
            'GP_ID'              => $gp_id,
            'UP_ID'              => $origem_up_id,
            'ESTACAO'            => $origem_estacao,
            'DATAHORA_INICIO'    => date('Y.m.d H:i:s',$datahora_inicio),
        ];
        array_push($reprogramar_origem, $origem_push);  

        /**
         * Cria o array que vai separar pela data no 
         * banco de dados os talões a serem reprogramados
         */
        foreach($destino_taloes as $destino_talao ) {
            
            $time        = strtotime('+1 day',$time);
            $data_inicio = date('Y.m.d',$destino_data);
                
            $talao_push = (object)[
                'PROGRAMACAO_TIPO'   => 'A', // A -> ID DO TALÃO ACUMULADO (VWREMESSA_TALAO.ID)
                'PROGRAMACAO_ID'     => $destino_talao->programacao_id,
                'TALAO_ID'           => $destino_talao->talao_id,
                'ESTABELECIMENTO_ID' => $estabelecimento_id,
                'UP_ID'              => $destino_up_id,
                'ESTACAO'            => $destino_estacao,
                'DATAHORA_INICIO'    => date('Y.m.d H:i:s',$time),
                'DATA_INICIO'        => $data_inicio
            ];
            
            array_push($taloes, $talao_push);  
        }
        
        $param = [
            'TALOES_DESTINO'      => $taloes,
            'REPROGRAMAR_DESTINO' => $reprogramar_destino,
            'REPROGRAMAR_ORIGEM'  => $reprogramar_origem
        ];
        
        _22070::gravar($param);
        
        return $this->filtrar($request);
    }
}