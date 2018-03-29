<?php

namespace app\Http\Controllers\Admin\_11005;

use App\Http\Controllers\Admin\_11005\_11005Controller as Ctrl;
use App\Models\DTO\Admin\_11005;


/**
 * Controller do objeto _11005 - Geracao de Remessas de Bojo
 */
class _11005ControllerApi extends Ctrl {
      
    
    public function getTabela() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_11005 = new _11005($this->con());

            $ret = $dto_11005->selectTabela($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
   
    
    public function getParametro($tabela) {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_11005 = new _11005($this->con());

            $ret = $dto_11005->selectParametro((object)['TABELA'=>$tabela]);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
   
    
    public function getParametroDetalhe($parametro_id) {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_11005 = new _11005($this->con());

            $ret = $dto_11005->selectParametroDetalhe((object)['PARAMETRO_ID'=>$parametro_id]);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getParametroDetalheTabela($tabela) {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_11005 = new _11005($this->con());

            $ret = $dto_11005->selectParametroDetalheTabela((object)['TABELA'=>$tabela]);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getParametroDetalheItem($tabela,$tabela_id) {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_11005 = new _11005($this->con());

            $ret = $dto_11005->selectParametroDetalheItem((object)['TABELA'=>$tabela, 'TABELA_ID' => $tabela_id]);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getParametroTabela() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_11005 = new _11005($this->con());

            $ret = $dto_11005->selectParametroTabela($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
   
}