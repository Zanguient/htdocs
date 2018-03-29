<?php

namespace app\Http\Controllers\Custo\_31060;

use App\Http\Controllers\Custo\_31060\_31060Controller as Ctrl;
use App\Models\DTO\Custo\_31060;
use App\Helpers\SSE;
use App\Models\Conexao\_Conexao;
use App\Models\DTO\Custo\_22010;


/**
 * Controller do objeto _31060 - Geracao de Remessas de Bojo
 */
class _31060ControllerApi extends Ctrl {
      
    
    
    public function getRegra() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_31060 = new _31060($this->con());

            $ret = $dto_31060->selectRegra($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function postRegra() {
        $this->Menu()->alterar('Confirmando alterações');
        try {     
            
            $request = $this->request();
            
            $dados = $request->DADOS;

            
            $dto_31060 = new _31060($this->con());
            
            foreach ( $dados as $item ) {
                
                validator($item, [
                    'FAMILIA_PRODUCAO'      =>   ['Agrupamento Família'      ,'required'],
                    'FAMILIA_ID'            =>   ['Família'                  ,'required'],
                    'UP_PADRAO1'            =>   ['1ª Unidade Produtiva'     ,'required'],
                    'CALCULO_CONFORMACAO'   =>   ['Calc. Conformação'        ,'required'],
                    'CALCULO_REBOBINAMENTO' =>   ['Calc. Rebobinamento'      ,'required'],
                    'CCUSTO'                =>   ['Centro de Custo'          ,'required'],
                    'FATOR'                 =>   ['Fator de Conversão'       ,'required'],
                    'REMESSAS_DEFEITO'      =>   ['Qtd. de Remessas Defeitos','required'],
                ],true);
                
                if ( isset($item->CCUSTO_HIERARQUIA) && $item->CCUSTO_HIERARQUIA == 1 ) {
                    $item->CCUSTO = $item->CCUSTO . '*';
                }
                
                if ( isset($item->GP_TODOS) && $item->GP_TODOS == 1 ) {
                    $item->GP_ID = '*';
                }
                
                if ( isset($item->PERFIL_UP_TODOS) && $item->PERFIL_UP_TODOS == 1 ) {
                    $item->PERFIL_UP = '*';
                }
                
                if ( isset($item->EXCLUIDO) && $item->EXCLUIDO == true && $item->ID > 0 ) {
                    $dto_31060->deleteRegra($item);
                } else {
                    $dto_31060->updateInsertRegra($item);
                }
            }
            
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = $dto_31060->selectRegra($request->FILTRO);
            }
            
            $ret->SUCCESS_MSG = 'Alterações realizadas com sucesso.';
            
            $this->con()->rollback();
//            $this->con()->commit();
            
            return (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
   
}