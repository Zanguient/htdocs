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
class ControllerFerramenta extends Ctrl {
    
    private $operador   = [];
    private $ferramenta = [];
    
    public function listarDisponiveis(Request $request) {
        _11010::permissaoMenu($this->menu,null,'Listando ferramentas disponíveis para troca');
        
        parent::setRequest(json_decode(json_encode((object) $request->all()), false));
                
        $this->valid();

        $this->con = new _Conexao;
        
        try { 
            $this->sets();
                        
            $res = _22150::selectFerramentaDisponivel([
                'FERRAMENTA_ID'   => $this->ferramenta->ID,
                'DATAHORA_INICIO' => date('Y-m-d H:i:s',strtotime($this->request->DATAHORA_INICIO)),
            ], $this->con);
            
            if ( !isset($res[0]) ) {
                log_erro('Não há ferramentas disponíveis para troca');
            }
            
            $this->con->commit();   
            
            return $res;
        }
        catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }
    
    
    public function alterar(Request $request) {
        _11010::permissaoMenu($this->menu,'ALTERAR','Confirmando alteração de ferramenta');
        
        parent::setRequest(json_decode(json_encode((object) $request->all()), false));
                
        $this->validAlter();

        $this->con = new _Conexao;
        
        try { 
            $this->sets();
                        
            _22150::updateFerramentaProgramacao([
                'FERRAMENTA_ID'      => $this->ferramenta->ID,
                'DEST_FERRAMENTA_ID' => $this->request->DEST_FERRAMENTA_ID,
                'DATAHORA_INICIO'    => date('Y-m-d H:i:s',strtotime($this->request->DATAHORA_INICIO)),
                'OPERADOR_ID'    => $this->operador->OPERADOR_ID
            ], $this->con);
            
            $res = (object)[];
            $res->RETORNO = _22150::selectPainel([],$this->con);
            
            $this->con->commit();   
            
            $res->MSG = 'Ferramenta alterada com sucesso!';
            
            return Response::json($res);
        }
        catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }
    
    public function historico(Request $request) {
        _11010::permissaoMenu($this->menu,'ALTERAR','Visualizando histórico de ferramenta');
        
        parent::setRequest(json_decode(json_encode((object) $request->all()), false));
                
        $this->validHistorico();

        $this->con = new _Conexao;
        
        try {                         
            
            $res = _22150::selectHistorico($this->request,$this->con);
            
            if ( !isset($res[0]) ) {
                log_erro('Não há histórico para listar.');
            }
            
            $this->con->commit();   
            
            return Response::json($res);
        }
        catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }
    
    /**
     * Efetua as validações para entrada dos dados
     */
    private function valid() {
        validator((array) parent::getRequest(), [
//            'FERRAMENTA_BARRAS' => ['Código de Barras da Ferramenta','required'],
            'OPERADOR_BARRAS'   => ['Código de Barras do Operador'  ,'required'],
            'DATAHORA_INICIO'   => ['Data/Hora Início'  ,'required'],
        ],true);
    }
    
    /**
     * Efetua as validações para entrada dos dados
     */
    private function validAlter() {
        validator((array) parent::getRequest(), [
            'OPERADOR_BARRAS'    => ['Código de Barras do Operador','required'],
            'FERRAMENTA_ID'      => ['Ferramenta'                  ,'required'],
            'DEST_FERRAMENTA_ID' => ['Ferramenta de Destino'       ,'required'],
            'DATAHORA_INICIO'    => ['Data/Hora Início'            ,'required'],
        ],true);
    }
    
    /**
     * Efetua as validações para entrada dos dados
     */
    private function validHistorico() {
        validator((array) parent::getRequest(), [
            'FERRAMENTA_ID' => ['Ferramenta','required'],
        ],true);
    }
    
    private function sets() {
            
        /**
         * Setta o operador
         */
        $this->operador = _22050::validarOperador([ 
            'COD_BARRAS'    => parent::getRequest()->OPERADOR_BARRAS, 
            'OPERACAO_ID'   => 25, 
            'VALOR_EXT'     => 1, 
            'ABORT'         => true 
        ])[0];
        
        /**
         * Setta a ferramenta
         */
        $ferramentas = _22150::selectFerramenta(parent::getRequest(),$this->con);

        if ( isset($ferramentas[0]) ) {
            $this->ferramenta = $ferramentas[0];
        } else {
            log_erro('Código de barras não localizado ou ferramenta inativa.');
        }
        
    }
}