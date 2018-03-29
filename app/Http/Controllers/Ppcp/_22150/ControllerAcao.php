<?php

namespace app\Http\Controllers\Ppcp\_22150;

use App\Http\Controllers\Ppcp\_22150\Controller as Ctrl;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Admin\_11010;
use App\Models\DTO\Ppcp\_22150;
use App\Models\DTO\Ppcp\_22050;
use App\Models\Conexao\_Conexao;


/**
 * Controller do objeto _22150 - Geracao de Remessas de Bojo
 */
class ControllerAcao extends Ctrl {
    
    private $operador = [];
    
    public function registrar(Request $request) {
        _11010::permissaoMenu($this->menu,'ALTERAR','Registrando Ação na Ferramenta');
        
        parent::setRequest(json_decode(json_encode((object) $request->all()), false));
                
        $this->valid();

        $con = new _Conexao;  
        
        try {   
            $request_all     = parent::getRequest();
            $update_executed = false;
            
            if ( (isset(parent::getRequest()->FERRAMENTA_ID) && parent::getRequest()->FERRAMENTA_ID != '') || (isset(parent::getRequest()->FERRAMENTA_BARRAS) && parent::getRequest()->FERRAMENTA_BARRAS != '') ) {
                
                $ferramenta_args = (object)[];
                
                isset($request_all->FERRAMENTA_ID) && $request_all->FERRAMENTA_ID > 0 ? $ferramenta_args->FERRAMENTA_ID     = $request_all->FERRAMENTA_ID : null;                
                isset($request_all->FERRAMENTA_BARRAS) && $request_all->FERRAMENTA_BARRAS > 0 ? $ferramenta_args->FERRAMENTA_BARRAS = $request_all->FERRAMENTA_BARRAS : null;                
                $ferramenta_args->FERRAMENTA_STATUS = '1';
                
                $ferramentas = _22150::selectFerramenta($ferramenta_args,$con);
                
                if ( isset($ferramentas[1]) ) {
                    log_erro('Existe mais de uma ferramenta ativa com o código de barras: ' . $ferramenta_args->FERRAMENTA_BARRAS . '. Operação de cancelada.');
                }
                
                if ( isset($ferramentas[0]) ) {

                    $ferramenta = $ferramentas[0];

                    _22150::updateFerramenta([
                        'GP_ID'         => parent::getRequest()->GP_ID,
                        'UP_ID'         => parent::getRequest()->UP_ID,
                        'ESTACAO'       => parent::getRequest()->ESTACAO,
                        'SITUACAO'      => parent::getRequest()->SITUACAO,
                        'TALAO_ID'      => parent::getRequest()->TALAO_ID,
                        'OPERADOR_ID'   => $this->operador->OPERADOR_ID,
                        'FERRAMENTA_ID' => $ferramenta->ID,
                    ], $con);
                    $update_executed = true;
                } else {
                    log_erro('Código de barras da Ferramenta não localizado ou inativada.');
                }
            }
            
            if ( !$update_executed ) {
                log_erro('Paramentros incorretos. Registro não realizado.');
            }
            
            $res = _22150::selectPainel([],$con);
            
            $con->commit();   
            
            return $res;
        }
        catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }
    
    private function valid() {
        validator(parent::getRequest(), [
            'OPERADOR_BARRAS'   => ['Código de Barras do Operador'  ,'required']
        ],true);
                
        $this->operador = _22050::validarOperador([ 
            'COD_BARRAS'    => parent::getRequest()->OPERADOR_BARRAS, 
            'OPERACAO_ID'   => 24, 
            'VALOR_EXT'     => 1, 
            'ABORT'         => true 
        ])[0];
    }
}