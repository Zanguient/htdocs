<?php

namespace app\Http\Controllers\Custo\_31020;

use App\Http\Controllers\Custo\_31020\_31020Controller as Ctrl;
use App\Models\DTO\Custo\_31020;
use App\Helpers\SSE;
use App\Models\Conexao\_Conexao;
use App\Models\DTO\Custo\_22010;


/**
 * Controller do objeto _31020 - Geracao de Remessas de Bojo
 */
class _31020ControllerApi extends Ctrl {
      
    
    public function getRateioCcusto() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_31020 = new _31020($this->con());

            $ret = $dto_31020->selectRateioCcusto($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function postRateioCcusto() {
        $this->Menu()->alterar('Confirmando alterações');
        try {     
            
            $request = $this->request();
            
            $dados = $request->DADOS;

            
            $dto_31020 = new _31020($this->con());
            
            foreach ( $dados as $item ) {
                
                validator($item, [
                    'ABRANGENCIA'       =>   ['Abrangencia','required'],
                    'ORDEM'             =>   ['Talões a Liberar','required'],
                    'CCUSTO'            =>   ['Centro de Custo','required'],
                    'TIPO_ID'           =>   ['Tipo de Rateio','required'],
                    'VALOR_ORIGEM'      =>   ['Origem','required'],
                    'RATEAMENTO_GRUPO'  =>   ['Grupo','required']
                ],true);
                           
                if ( isset($item->HIERARQUIA) && $item->HIERARQUIA == 1 ) {
                    $item->CCUSTO = $item->CCUSTO . '*';
                }
                
                if ( $item->EXCLUIDO == true && $item->ID > 0 ) {
                    $dto_31020->deleteRateioCcusto($item);
                } else {
                    $dto_31020->updateInsertRateioCcusto($item);
                }
                
                
                if ( isset($item->CCUSTOS) ) {
                    foreach ( $item->CCUSTOS as $ccusto ) {
                        if ( $ccusto->EXCLUIDO == true && $ccusto->ID > 0 ) {
                            $dto_31020->deleteCCustoAbsorcao($ccusto);
                        } else {
                                         
                            validator($ccusto, [
                                'CCUSTO'            =>   ['Centro de Custo'         ,'required'],
                                'CCUSTO_ABSORCAO'   =>   ['Centrod e Custo Absorção','required'],
                                'PERC_ABSORCAO'     =>   ['Percentual Absorção'     ,'required'],
                                'RATEAMENTO_GRUPO'  =>   ['Grupo'                   ,'required']
                            ],true);                            
                            
                            $dto_31020->updateInsertCCustoAbsorcao($ccusto);
                        }
                    }
                }
            }
            
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = $dto_31020->selectRateioCcusto($request->FILTRO);
            }
            
            $ret->SUCCESS_MSG = 'Alterações realizadas com sucesso.';
            
//            $this->con()->rollback();
            $this->con()->commit();
            
            return (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getCCustoAbsorcao() {
        $this->Menu(false)->consultar();
        try {   
            
            $request = $this->request();
            
            $dto_31020 = new _31020($this->con());

            $ret = $dto_31020->selectCCustoAbsorcao($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
   
}