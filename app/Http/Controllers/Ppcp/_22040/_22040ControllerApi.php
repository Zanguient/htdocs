<?php

namespace app\Http\Controllers\Ppcp\_22040;

use App\Http\Controllers\Ppcp\_22040\_22040Controller as Ctrl;
use App\Models\DTO\Ppcp\_22040;
use App\Models\DTO\Ppcp\_22050;

/**
 * Controller do objeto _22040 - Geracao de Remessas de Bojo
 */
class _22040ControllerApi extends Ctrl {
      
    public function getReposicao() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_22040 = new _22040($this->con());
            
            validator($request, [
                'ESTABELECIMENTO_ID' => ['Id do Estabelecimento','required'],
                'FAMILIA_ID'         => ['Id da Família de Produto','required'],
            ],true);  
            
            $ret = $dto_22040->selectReposicao($request);
            
            if (count($ret) == 0 ) {
                log_erro('Nenhuma registro foi localizado.');
            }
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
      
    public function getProducao() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_22040 = new _22040($this->con());
            
            validator($request, [
                'ESTABELECIMENTO_ID' => ['Id do Estabelecimento','required'],
                'PRODUTO_ID'         => ['Id do Produto','required'],
                'TAMANHO'            => ['Tamanho do Produto','required'],
            ],true);  
            
            $ret = $dto_22040->selectProducao($request);
            
            if (count($ret) == 0 ) {
                log_erro('Nenhuma registro foi localizado.');
            }
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
      
    public function getPedido() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_22040 = new _22040($this->con());
            
            validator($request, [
                'ESTABELECIMENTO_ID' => ['Id do Estabelecimento','required'],
                'PRODUTO_ID'         => ['Id do Produto','required'],
                'TAMANHO'            => ['Tamanho do Produto','required'],
            ],true);  
            
            $ret = $dto_22040->selectPedido($request);
            
            if (count($ret) == 0 ) {
                log_erro('Nenhuma registro foi localizado.');
            }
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
      
    public function getEmpenhado() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_22040 = new _22040($this->con());
            
            validator($request, [
                'ESTABELECIMENTO_ID' => ['Id do Estabelecimento','required'],
                'PRODUTO_ID'         => ['Id do Produto','required'],
                'TAMANHO'            => ['Tamanho do Produto','required'],
            ],true);  
            
            $ret = $dto_22040->selectEmpenhado($request);
            
            if (count($ret) == 0 ) {
                log_erro('Nenhuma registro foi localizado.');
            }
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }

   
}