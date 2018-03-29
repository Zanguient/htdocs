<?php

namespace app\Http\Controllers\Custo\_31050;

use App\Http\Controllers\Custo\_31050\_31050Controller as Ctrl;
use App\Models\DTO\Custo\_31050;
use App\Helpers\SSE;
use App\Models\Conexao\_Conexao;
use App\Models\DTO\Custo\_22010;


/**
 * Controller do objeto _31050 - Geracao de Remessas de Bojo
 */
class _31050ControllerApi extends Ctrl {
      
    
    public function getRateioTipoDetalhe() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_31050 = new _31050($this->con());

            $ret = $dto_31050->selectRateioTipoDetalhe($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getRateioTipo() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_31050 = new _31050($this->con());

            $ret = $dto_31050->selectRateioTipo($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function postRateioTipo() {
        $this->Menu()->alterar('Confirmando alterações');
        try {     
            
            $request = $this->request();
            
            $dados = $request->DADOS;

            
            $dto_31050 = new _31050($this->con());
            
            foreach ( $dados as $item ) {
                
                validator($item, [
                    'DESCRICAO'     =>   ['Descrição'        ,'required'],
                    'DATA_INICIAL'  =>   ['Data Inicial'     ,'required'],
                    'DATA_CORRENTE' =>   ['Data Corrente'    ,'required'],
                    'UM_ID'         =>   ['Unidade de Medida','required']
                ],true);
                
                if ( isset($item->EXCLUIDO) && $item->EXCLUIDO == true && $item->ID > 0 ) {
                    $dto_31050->deleteRateioTipo($item);
                } else {
                    $dto_31050->updateInsertRateioTipo($item);
                }
            }
            
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = $dto_31050->selectRateioTipo($request->FILTRO);
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
   
}