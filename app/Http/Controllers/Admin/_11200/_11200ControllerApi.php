<?php

namespace app\Http\Controllers\Admin\_11200;

use App\Http\Controllers\Admin\_11200\_11200Controller as Ctrl;
use App\Models\DTO\Admin\_11200;


/**
 * Controller do objeto _11200 - Geracao de Remessas de Bojo
 */
class _11200ControllerApi extends Ctrl {
      
    
    public function getPerfil() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_11200 = new _11200($this->con());

            $ret = $dto_11200->selectPerfil($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
   
}