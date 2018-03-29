<?php

namespace app\Http\Controllers\Custo\_31040;

use App\Http\Controllers\Custo\_31040\_31040Controller as Ctrl;
use App\Models\DTO\Custo\_31040;
use App\Helpers\SSE;
use App\Models\Conexao\_Conexao;
use App\Models\DTO\Custo\_22010;


/**
 * Controller do objeto _31040 - Geracao de Remessas de Bojo
 */
class _31040ControllerApi extends Ctrl {
      
    
    public function getRateioTipoDetalhe() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_31040 = new _31040($this->con());

            $ret = $dto_31040->selectRateioTipoDetalhe($request);
            
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
            
            $dto_31040 = new _31040($this->con());

            $ret = $dto_31040->selectRateioTipo($request);
            
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

            
            $dto_31040 = new _31040($this->con());
            
            foreach ( $dados as $item ) {
                
                validator($item, [
                    'CCUSTO'  =>   ['Centro de Custo','required'],
                    'TIPO_ID' =>   ['Tipo','required'],
                    'VALOR'   =>   ['Valor','required']
                ],true);
                
                if ( $item->EXCLUIDO == true && $item->ID > 0 ) {
                    $dto_31040->deleteRateioTipo($item);
                } else {
                    $dto_31040->updateInsertRateioTipo($item);
                }
            }
            
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = $dto_31040->selectRateioTipoDetalhe($request->FILTRO);
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