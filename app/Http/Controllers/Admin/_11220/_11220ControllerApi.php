<?php

namespace app\Http\Controllers\Admin\_11220;

use App\Http\Controllers\Admin\_11220\_11220Controller as Ctrl;
use App\Models\DTO\Admin\_11220;


/**
 * Controller do objeto _11220 - Geracao de Remessas de Bojo
 */
class _11220ControllerApi extends Ctrl {
      
    
    public function getDados() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_11220 = new _11220($this->con());

            $ret = $dto_11220->getDados();
            
            $this->con()->commit();
                        
            return response()->json($ret);
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function postModulo() {
        $this->Menu(false)->incluir();
        try {     
            
            $request = $this->request();
            
            $dto_11220 = new _11220($this->con());

            $dados = $request->DADOS;
            
            $dto_11220->updateInsertModulo($dados);
            
            
            $ret = (object) [];
            
            if ( isset($request->DATA_RETURN) ) {
                $ret->DATA_RETURN = $dto_11220->getDados();
            }
            
            $ret->SUCCESS_MSG = 'Alterações realizadas com sucesso.';
            
            $this->con()->rollback();
//            $this->con()->commit();            
            
                        
            return response()->json($ret);
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function postPeriodo() {
        $this->Menu(false)->incluir();
        try {     
            
            $request = $this->request();
            
            $dto_11220 = new _11220($this->con());

            $dados = $request->DADOS;
            
            foreach ( $dados as $periodo ) {
                $dto_11220->updateInsertPeriodo($periodo);
            }
            
            
            $ret = (object) [];
            
            if ( isset($request->DATA_RETURN) ) {
                $ret->DATA_RETURN = $dto_11220->getDados();
            }
            
            $ret->SUCCESS_MSG = 'Alterações realizadas com sucesso.';
            
//            $this->con()->rollback();
            $this->con()->commit();            
            
                        
            return response()->json($ret);
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
   
}