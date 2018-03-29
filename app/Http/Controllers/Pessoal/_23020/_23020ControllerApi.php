<?php

namespace app\Http\Controllers\Pessoal\_23020;

use App\Http\Controllers\Pessoal\_23020\_23020Controller as Ctrl;
use App\Models\DTO\Pessoal\_23020;


/**
 * Controller do objeto _23020 - Geracao de Remessas de Bojo
 */
class _23020ControllerApi extends Ctrl {
      
    
    public function getColaboradores() {
        $this->Menu(false)->consultar('Consultando Colaboradores');
        try {     
            
            $request = $this->request();
            
            $dto_23020 = new _23020($this->con());

            $ret = $dto_23020->selectColaborador($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function updateColaboradorCentroDeTrabalho() {
        $this->Menu()->alterar('Alterando centro de trabalho');
        try {     
            /**
             * Preparação da entrada dos dados
             */
            $dto_23020 = new _23020($this->con());
            
            $request = $this->request();
            
            validator($request, [
                'COLABORADOR_ID'  => ['Id do Colaborador','required'],
                'CCUSTO_PRODUCAO' => ['C.Custo de produção','required'],
            ],true);  
            
            $dto_23020->updateColaboradorCentroDeTrabalho((object)[
                'COLABORADOR_ID'  => $request->COLABORADOR_ID,
                'CCUSTO_PRODUCAO' => $request->CCUSTO_PRODUCAO,
            ]);
            
            $ret = (object) [];
            
            $ret->SUCCESS_MSG = 'Centro de trabalho alterado com sucesso.';
            
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