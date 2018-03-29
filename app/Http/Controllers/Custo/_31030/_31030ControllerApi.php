<?php

namespace app\Http\Controllers\Custo\_31030;

use App\Http\Controllers\Custo\_31030\_31030Controller as Ctrl;
use App\Models\DTO\Custo\_31030;
use App\Helpers\SSE;
use App\Models\Conexao\_Conexao;
use App\Models\DTO\Custo\_22010;


/**
 * Controller do objeto _31030 - Geracao de Remessas de Bojo
 */
class _31030ControllerApi extends Ctrl {
      
    
    public function getRateioCContabil() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_31030 = new _31030($this->con());

            $ret = $dto_31030->selectRateioCContabil($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function postRateioCContabil() {
        $this->Menu()->alterar('Confirmando alterações');
        try {     
            
            $request = $this->request();
            
            $dados = $request->DADOS;

            
            $dto_31030 = new _31030($this->con());
            
            foreach ( $dados as $item ) {
                
                validator($item, [
                    'CCONTABIL'         =>   ['Conta Contábil','required'],
//                    'REGRA_RATEAMENTO'  =>   ['Regra','required'],
                    'VALOR_ORIGEM'      =>   ['Origem','required'],
                    'RATEAMENTO_GRUPO'  =>   ['Grupo','required']
                ],true);
                
                if ( $item->EXCLUIDO == true && $item->ID > 0 ) {
                    $dto_31030->deleteRateioCContabil($item);
                } else {
                    $dto_31030->updateInsertRateioCContabil($item);
                }
            }
            
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = $dto_31030->selectRateioCContabil($request->FILTRO);
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