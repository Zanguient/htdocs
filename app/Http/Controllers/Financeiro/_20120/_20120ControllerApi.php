<?php

namespace app\Http\Controllers\Financeiro\_20120;

use App\Http\Controllers\Financeiro\_20120\_20120Controller as Ctrl;
use App\Models\DTO\Financeiro\_20120;
use App\Helpers\SSE;
use App\Models\Conexao\_Conexao;
use App\Models\DTO\Financeiro\_22010;


/**
 * Controller do objeto _20120 - Geracao de Remessas de Bojo
 */
class _20120ControllerApi extends Ctrl {
      
    
    public function getUnidadeMedida() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_20120 = new _20120($this->con());

            $ret = $dto_20120->selectUnidadeMedida($request);
            
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
            
            $dto_20120 = new _20120($this->con());

            $ret = $dto_20120->selectRateioTipo($request);
            
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

            
            $dto_20120 = new _20120($this->con());
            
            foreach ( $dados as $item ) {
                
                validator($item, [
                    'CCUSTO'  =>   ['Centro de Financeiro','required'],
                    'TIPO_ID' =>   ['Tipo','required'],
                    'VALOR'   =>   ['Valor','required']
                ],true);
                
                if ( $item->EXCLUIDO == true && $item->ID > 0 ) {
                    $dto_20120->deleteRateioTipo($item);
                } else {
                    $dto_20120->updateInsertRateioTipo($item);
                }
            }
            
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = $dto_20120->selectRateioTipoDetalhe($request->FILTRO);
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