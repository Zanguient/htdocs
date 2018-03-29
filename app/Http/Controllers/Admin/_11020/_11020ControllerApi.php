<?php

namespace app\Http\Controllers\Admin\_11020;

use App\Http\Controllers\Admin\_11020\_11020Controller as Ctrl;
use App\Models\DTO\Admin\_11020;


/**
 * Controller do objeto _11020 - Geracao de Remessas de Bojo
 */
class _11020ControllerApi extends Ctrl {
      
    
    public function getEstabelecimento() {
//        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_11020 = new _11020($this->con());

            $consumos = $dto_11020->selectEstabelecimento($request);
            
            $this->con()->commit();
                        
            return $consumos;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
}