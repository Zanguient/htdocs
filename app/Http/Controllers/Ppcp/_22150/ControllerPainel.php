<?php

namespace app\Http\Controllers\Ppcp\_22150;

use App\Http\Controllers\Ppcp\_22150\Controller as Ctrl;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Admin\_11010;
use App\Models\DTO\Ppcp\_22150;
use App\Models\DTO\Ppcp\_22050;
use App\Models\Conexao\_Conexao;
use App\Helpers\SSE;


/**
 * Controller do objeto _22150 - Geracao de Remessas de Bojo
 */
class ControllerPainel extends Ctrl {
        
    public function sse() {

        $sse = new SSE();

        $sse->emitEvent(function() use ($sse) {
            $con = new _Conexao;  

            try {
                
                $sse->setValues(_22150::selectPainel([],$con));
                $con->commit();
            }
            catch (Exception $e) {
                $con->rollback();
                throw $e;
            }
        });
    }
        
    public function ferramentaProgramada() {
        
        $con = new _Conexao;  
        
        try {
            $res = _22150::selectFerramentaProgramada([],$con);
            $con->commit();            
            return Response::json($res);
        }
        catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }
}