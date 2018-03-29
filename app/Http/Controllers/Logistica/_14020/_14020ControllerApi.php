<?php

namespace app\Http\Controllers\Logistica\_14020;

use App\Http\Controllers\Logistica\_14020\_14020Controller as Ctrl;
use App\Models\DTO\Logistica\_14020;
use App\Helpers\SSE;
use App\Models\Conexao\_Conexao;
use App\Models\DTO\Logistica\_22010;


/**
 * Controller do objeto _14020 - Geracao de Remessas de Bojo
 */
class _14020ControllerApi extends Ctrl {
      
    
    public function getFrete($id) {
        $this->Menu(false)->consultar();
        try {     
            
            $ret = $this->frete($id);
            
            $this->con()->commit();
                        
            return response()->json($ret);
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function postFreteCalcular() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_14020 = new _14020($this->con());

            $simulador = ( isset($request->ORIGEM) && preg_match('/SIMULADOR/', $request->ORIGEM));
                        
            if ( isset($request->ITENS) && $simulador ) {
                
                foreach( $request->ITENS as $item ) {
                    $dto_14020->insertFreteTmp($item);
                }
            }
            
            
            $frete = $dto_14020->spcFreteCalcular($request);
            
            $ret = (object) [];
            
            if ( isset($request->RETURN) || $simulador ) {
                if ( isset($frete[0]) ) {
                    $id = $frete[0]->FRETE_ID;
                    $ret = $this->frete($id);
                }                
            }

            if ( isset($request->ROLLBACK) || $simulador ) {
                $this->con()->rollback();
            } else {
                $this->con()->commit();
            }
            
            return response()->json($ret);
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getComposicao() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_14020 = new _14020($this->con());

            $ret = $dto_14020->selectComposicao($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    
    public function getTransportadora() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_14020 = new _14020($this->con());

            $ret = $dto_14020->selectTransportadora($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getTransportadoraCidade() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_14020 = new _14020($this->con());

            $ret = $dto_14020->selectTransportadoraCidade($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    
    public function getCtrc($id = null) {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_14020 = new _14020($this->con());

            
            if ( $id > 0 ) {
                $request->ID = $id;
                $request->FIRST = 1;
                $request->SKIP = 0;
            }
            
            $ret = $dto_14020->selectCtrc($request);
            
            if ( $id > 0 && isset($ret[0]) ) {
                $ret = $ret[0];
            }            
            
            $this->con()->commit();
                        
            return response()->json($ret);
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    
    public function getCidade() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_14020 = new _14020($this->con());

            $ret = $dto_14020->selectCidade($request);
            
            $this->con()->commit();
                        
            return response()->json($ret);
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getCliente() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_14020 = new _14020($this->con());

            $ret = $dto_14020->selectCliente($request);
            
            $this->con()->commit();
                        
            return response()->json($ret);
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function frete($id) {

        $dto_14020 = new _14020($this->con());

        $frete = $dto_14020->selectFrete((object)[
            'ID' => $id
        ]);

        $ret = (object)[];

        if ( isset($frete[0]) ) {
            $ret = $frete[0];

            $ret->DADOS_CARGA = $dto_14020->selectComposicao((object)[
                'FRETE_ID'       => $ret->ID,
                'HABILITA_CARGA' => true
            ]);

            $ret->DADOS_COMPOSICAO = $dto_14020->selectComposicao((object)[
                'FRETE_ID'            => $ret->ID,
                'HABILITA_COMPOSICAO' => true
            ]);

            $ret->DETALHES = $dto_14020->selectFreteDetalhe((object)[
                'FRETE_ID' => $ret->ID,
            ]);

            $ret->ITENS = [];
            foreach ( $ret->DETALHES as $detalhe ) {

                array_push($ret->ITENS, (object) [
                    'MODELO_ID' => $detalhe->MODELO_ID,
                    'COR_ID' => $detalhe->COR_ID,
                    'TAMANHO' => $detalhe->TAMANHO,
                    'QUANTIDADE' => $detalhe->QUANTIDADE,
                    'VALOR_UNITARIO' => $detalhe->VALOR_UNITARIO,
                ]);
                
                $detalhe->DADOS_CUBAGEM = $dto_14020->selectFreteDetalheCubagem((object)[
                    'FRETE_DETALHE_ID' => $detalhe->ID
                ]);

                $detalhe->DADOS_PESO = $dto_14020->selectFreteDetalhePeso((object)[
                    'FRETE_DETALHE_ID' => $detalhe->ID
                ]);
            }
        }   

        return $ret;
    }
   
}