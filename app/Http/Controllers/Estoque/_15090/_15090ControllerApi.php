<?php

namespace app\Http\Controllers\Estoque\_15090;

use App\Http\Controllers\Estoque\_15090\_15090Controller as Ctrl;
use App\Models\DTO\Estoque\_15090;
use App\Models\DTO\Ppcp\_22050;

/**
 * Controller do objeto _15090 - Geracao de Remessas de Bojo
 */
class _15090ControllerApi extends Ctrl {
      
    public function getConferenciaItens() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_15090 = new _15090($this->con());
            
            validator($request, [
                'CODIGO_BARRAS'    => ['Código de Barras','required'],
                'CONFERENCIA_TIPO' => ['Tipo de Conferencia','required'],
            ],true);  
            
            $conferencia_listas = $dto_15090->selectConferenciaItens($request);
            
            if (count($conferencia_listas) == 0 ) {
                log_erro('Nenhuma peça foi localizada ou o lote não está finalizado.');
            }
            
            $this->con()->commit();
                        
            return $conferencia_listas;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }

    public function getConferenciaPendentes() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_15090 = new _15090($this->con());
            
            $conferencia_listas = $dto_15090->selectConferenciaPendentes($request);
            
            $this->con()->commit();
                        
            return $conferencia_listas;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }

    public function getConferenciaPendentesLote() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_15090 = new _15090($this->con());
            
            $conferencia_listas = $dto_15090->selectConferenciaPendentesLote($request);
            
            $this->con()->commit();
                        
            return $conferencia_listas;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function postConferenciaConfirmar() {
        $this->Menu()->alterar('Confirmando conferencia');
        try {     
            
            $dto_15090 = new _15090($this->con());
            
            $request = $this->request();
            
            validator($request, [
                'ITENS'           => ['Itens','required'],
                'OPERADOR_BARRAS' => ['Código de Barras do Operador','required'],
                'CODIGO_BARRAS'   => ['Código de Barras à Conferir','required'],
            ],true);  
            
            $itens = $request->ITENS;
            
            $operador = _22050::validarOperador([ 
                'COD_BARRAS'    => $request->OPERADOR_BARRAS, 
                'OPERACAO_ID'   => 27, 
                'VALOR_EXT'     => 1, 
                'ABORT'         => true 
            ])[0];            

            foreach ( $itens as $item ) {

                validator($item, [
                    'ESTOQUE_ID' => ['Código da Transação de Estoque','required'],
                    'CONFERIR'   => ['Ação de Conferencia','required'],
                ],true);                  
                
                if ( $item->ESTOQUE_ID == '' ) {
                    log_erro('Códgio da Transação de Estoque inválida.');
                }
                
                $args = (object) [
                    'ESTOQUE_ID'  => $item->ESTOQUE_ID,
                    'OPERADOR_ID' => $operador->OPERADOR_ID,
                    'CONFERIR'    => $item->CONFERIR
                ];
                
                $dto_15090->updateConferencia($args);

            }
                        
            $ret = (object) [];
            
            $ret->DATA_RETURN = $dto_15090->selectConferenciaItens($request);
            
            $ret->SUCCESS_MSG = 'Conferencia Realizada com Sucesso.';
            
//            $this->con()->rollback();
            $this->con()->commit();
            
            return (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function deleteTransacao() {
        $this->Menu()->excluir('Excluindo baixas realizadas');
        try {     
            /**
             * Preparação da entrada dos dados
             */
            $dto_15090 = new _15090($this->con());
            
            $request = $this->request();
            
            validator($request, [
                'DADOS' => ['Dados','required'],
            ],true);  
            
            $dados = $request->DADOS;
            
            validator($dados, [
                'ITENS' => ['Itens'     ,'required']
            ],true);
            
            $transacoes = $dados->ITENS;
            
            
            /**
             * Tratamento dos dados
             */
            foreach ( $transacoes as $transacao ) {
                
                $dto_15090->deleteKanbanLoteDetalhe($transacao);
                
            }            
            
            
            /**
             * Pepração para o retorno
             */
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = [
                    'DADOS'  => $dto_15090->selectProdutoEstoqueMinimo($request->FILTRO),
                    'TRANSACOES' => $dto_15090->selectTransacao($request->FILTRO_TRANSACAO)
                ];
            }
            
            $ret->SUCCESS_MSG = 'Exclusão realizada com sucesso.';
            
//            $this->con()->rollback();
            $this->con()->commit();
            
            return (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
   
}