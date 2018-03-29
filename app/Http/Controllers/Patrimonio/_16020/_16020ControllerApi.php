<?php

namespace app\Http\Controllers\Patrimonio\_16020;

use App\Http\Controllers\Patrimonio\_16020\_16020Controller as Ctrl;
use App\Models\DTO\Patrimonio\_16020;
use App\Helpers\SSE;
use App\Models\Conexao\_Conexao;
use App\Models\DTO\Patrimonio\_22010;


/**
 * Controller do objeto _16020 - Geracao de Remessas de Bojo
 */
class _16020ControllerApi extends Ctrl {
      
    
    public function getTipos() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_16020 = new _16020($this->con());

            $ret = $dto_16020->selectTipo($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function postTipo() {
        $this->Menu()->alterar('Confirmando alterações');
        try {     
            
            $request = $this->request();
            
            $dados = $request->DADOS;

            
            $dto_16020 = new _16020($this->con());
            
            foreach ( $dados as $item ) {
                
                validator($item, [
                    'DESCRICAO'         =>   ['Descrição'       ,'required'],
                    'TAXA_DEPRECIACAO'  =>   ['Taxa Depreciação','required'],
                    'VIDA_UTIL'         =>   ['Vida Útil'       ,'required'],
                    'TIPO_GASTO'        =>   ['Tipo Gasto'      ,'required'],
                    'CCONTABIL'         =>   ['Conta Contábil Crétio'  ,'required'],
                    'CCONTABIL_DEBITO'  =>   ['Conta Contábil Débito'  ,'required']
                ],true);
                
                if ( isset($item->EXCLUIDO) && $item->EXCLUIDO == true && $item->ID > 0 ) {
                    $dto_16020->deleteTipo($item);
                } else {
                    $dto_16020->updateInsertTipo($item);
                }
            }
            
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = $dto_16020->selectTipo($request->FILTRO);
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