<?php

namespace app\Http\Controllers\Produto\_27010;

use App\Http\Controllers\Produto\_27010\_27010Controller as Ctrl;
use App\Models\DTO\Produto\_27010;
use App\Helpers\SSE;
use App\Models\Conexao\_Conexao;



/**
 * Controller do objeto _27010 - Geracao de Remessas de Bojo
 */
class _27010ControllerApi extends Ctrl {
      
    
    public function getFamilia() {
//        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_27010 = new _27010($this->con());
           
            $ret = $dto_27010->selectFamilia($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            
            if ( isset($con) ) {
                $con->rollback();
            }
            
            throw $e;
        }
    }
    
}