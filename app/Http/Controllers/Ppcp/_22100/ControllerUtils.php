<?php

namespace app\Http\Controllers\Ppcp\_22100;

use App\Http\Controllers\Ppcp\_22100\Controller as Ctrl;

use App\Models\DTO\Ppcp\_22100;


/**
 * Controller do objeto _22100 - Geracao de Remessas de Bojo
 */
class ControllerUtils extends Ctrl {
    
    
    public function modeloTempo() {
        $this->Menu()->consultar('Consultando tempos dos modelos');
        
        $this->Con();
        
        try {             
            
            foreach ( $this->Request() as $item ) {
                
                validator((array) $item, [
                    'MODELO_ID' => ['Código do Modelo','required'],
                    'TAMANHO'   => ['Tamanho'         ,'required'],
                    'COR_ID'    => ['Código de Cor'   ,'required'],
                ],true);

                $tempo = _22100::selectModeloTempo($item, $this->Con());
                
                $item->TEMPO_PAR = isset($tempo[0]) ? $tempo[0]->TEMPO : 0;
            }
                        
            $this->Con()->commit();   
            
            return response()->json((array)$this->Request());
        }
        catch (Exception $e) {
            $this->Con()->rollback();
            throw $e;
        }
    }
    
    public function skuDefeitoPercentual() {
        $this->Menu(false)->consultar();
        
        $this->Con();
        
        try {             
            
            foreach ( $this->Request() as $item ) {
                
                validator((array) $item, [
                    'MODELO_ID' => ['Código do Modelo','required'],
                    'TAMANHO'   => ['Tamanho'         ,'required'],
                    'COR_ID'    => ['Código de Cor'   ,'required'],
                ],true);

                $res = _22100::selectSkuDefeitoPercentual($item, $this->Con());

                $item->PERCENTUAL_DEFEITO = isset($res[0]) ? $res[0]->PERCENTUAL_DEFEITO : 0;
            }            
                        
            $this->Con()->commit();   
            
            return response()->json((array)$this->Request());
        }
        catch (Exception $e) {
            $this->Con()->rollback();
            throw $e;
        }
    }   
    
    public function linhaRemessaHistorico() {
        $this->Menu(false)->consultar();
        
        $this->Con();
        
        try {           
            validator((array) $this->Request(), [
                'LINHA_ID' => ['Código do Modelo','required'],
                'TAMANHO'   => ['Tamanho'         ,'required'],
            ],true);

            $res = _22100::selectLinhaRemessaHistorico($this->Request(), $this->Con());

            if ( !isset($res[0]) ) {
                log_erro('Não houveram resultado para sua busca.');
            }
            
            $this->Con()->commit();   
            
            return response()->json($res);
        }
        catch (Exception $e) {
            $this->Con()->rollback();
            throw $e;
        }
    }   
    
    public function pedidoBloqueioUsuario() {
        $this->Menu(false)->consultar();
        
        $this->Con();
        
        try {           

            $res = _22100::selectPedidoBloqueioUsuario([], $this->Con());

            
            $this->Con()->commit();   
            
            return response()->json($res);
        }
        catch (Exception $e) {
            $this->Con()->rollback();
            throw $e;
        }
    }   
    
    public function postPedidoDesbloqueio() {
        $this->Menu(false)->consultar();
        
        $this->Con();
        
        try {           

            validator((array) $this->Request(), [
                'USUARIO' => ['Usuário','required'],
            ],true);
            
            _22100::insertPedidoDesbloqueio($this->Request(), $this->Con());

            $res = _22100::selectPedidoBloqueioUsuario([], $this->Con());
            
            $this->Con()->commit();   
            
            return response()->json($res);
        }
        catch (Exception $e) {
            $this->Con()->rollback();
            throw $e;
        }
    }   
   
}