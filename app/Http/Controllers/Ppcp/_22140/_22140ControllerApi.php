<?php

namespace app\Http\Controllers\Ppcp\_22140;

use App\Http\Controllers\Ppcp\_22140\Controller as Ctrl;
use App\Models\DTO\Ppcp\_22140;
use App\Models\DTO\Ppcp\_22040;


/**
 * Controller do objeto _22140 - Geracao de Remessas de Bojo
 */
class _22140ControllerApi extends Ctrl {
      
    
    public function getProgramacaoEstacao() {
        $this->Menu(false)->incluir();
        try {     
            
            $dto_22140 = new _22140($this->con());
            
            $familia = $dto_22140->getProgramacaoEstacao([]);
            
            $this->con()->commit();
                        
            return response()->json($familia);
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
      
    
    public function getProgramacaoGp() {
        try {     
            
            $dto_22140 = new _22140($this->con());
            
            $familia = $dto_22140->getProgramacaoGp([]);
            
            $this->con()->commit();
                        
            return $familia;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function updateProgramacaoGpCalendario() {
        try {     
            
            $dto_22140 = new _22140($this->con());
        
            $request = $this->request();
            
            /**
             * Parametros com Requisitos mínimos
             */
            validator($request, [
                'DATA'    => ['Data'             ,'required'],
                'GPS'     => ['Grupo de Produção','required'],
            ],true);  
            
            setDefValue($request->HORARIO,'');
            
            foreach ( $request->GPS as $gp ) {
                
                $gp->DATA    = $request->DATA;
                $gp->HORARIO = $request->HORARIO;
                
                $dto_22140->updateProgramacaoGpCalendario($gp);
            }
            
            
            $this->con()->commit();
//            $this->con()->rollback();
                        
            return [
                'SUCCESS_MSG' => 'Canlendário atualizado com sucesso.'
            ];
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function postProgramacaoEstacao() {
        $this->Menu()->alterar('Reprocessando Tempos das Estações');
        try {     
            
            $dto_22140 = new _22140($this->con());
            
            $request = $this->request();
            
            /**
             * Parametros com Requisitos mínimos
             */
            validator($request, [
                'EM_PRODUCAO' => ['Situação dos Talões','required'],
                'ESTACOES'    => ['Estações'           ,'required'],
                'AGORA'       => ['Data base agora'    ,'required'],
            ],true);  
            
            if ( $request->AGORA == '1' ) {
                $datahora = '2000.01.01 00:00:00';
            } else {
                $datahora = date('Y.m.d H:i:s',strtotime($request->DATA_HORA));
            }
            
            foreach ( $request->ESTACOES as $estacao ) {
                /**
                 * Parametros com Requisitos mínimos
                 */
                validator($estacao, [
                    'GP_ID'                     => ['Código do Grupo de Produção'  ,'required'],
                    'UP_ID'                     => ['Código da Unidade Produtiva'  ,'required'],
                    'ESTACAO'                   => ['Código da Estação de Trabalho','required'],
                ],true);  
                
                $estacao->EM_PRODUCAO = $request->EM_PRODUCAO == true ? '1' : '0';
                $estacao->ORDEM_DATA_REMESSA = $request->ORDEM_DATA_REMESSA == true ? '1' : '0';
                $estacao->DATAHORA    = $datahora;
                
                $dto_22140->postProgramacaoEstacao($estacao);
            }
            
            $ret = (object) [];
            
            if ( isset($request->DATA_RETURN) ) {
                $ret->DATA_RETURN = $dto_22140->getProgramacaoEstacao([]);
            }
            
            $ret->SUCCESS_MSG = 'Tempos Reprocessados com sucesso.';
            
            $this->con()->commit();
            
            return response()->json($ret);
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
   
}