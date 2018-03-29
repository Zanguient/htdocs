<?php

namespace app\Http\Controllers\Estoque\_15120;

use App\Http\Controllers\Estoque\_15120\_15120Controller as Ctrl;
use App\Models\DTO\Estoque\_15120;
use App\Models\DTO\Ppcp\_22050;

/**
 * Controller do objeto _15120 - Geracao de Remessas de Bojo
 */
class _15120ControllerApi extends Ctrl {
      
    public function getFamilia() {
        try {     
            
            $request = $this->request();
            
            $dto_15120 = new _15120($this->con());
            
            $filtro = '';
            if ( isset($request->FILTRO) ) {
                $filtro = $request->FILTRO;
            }
            
            $this->Menu()->consultar('Filtrando: ' . strtoupper($filtro));
            
            $familias = $dto_15120->selectFamilia($request);
            
            $this->con()->commit();
                        
            return $familias;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }

    public function getEstoque() {
        try {     
            
            $request = $this->request();
            
            $dto_15120 = new _15120($this->con());
            
            $filtro = '';
            if ( isset($request->FILTRO) ) {
                $filtro = $request->FILTRO;
            }
            
            $this->Menu()->consultar('Filtrando: ' . strtoupper($filtro));
            
            $estoques = $dto_15120->selectEstoque($request);
            
            if ( !isset($estoques[0]) ) {
                log_erro('Nenhum produto foi localizado para os dados informados.');
            }
            
            $this->con()->commit();
                        
            return $estoques;
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
            
            $dto_15120 = new _15120($this->con());
            
            $conferencia_listas = $dto_15120->selectConferenciaPendentes($request);
            
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
            
            $dto_15120 = new _15120($this->con());
            
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
                
                $dto_15120->updateConferencia($args);

            }
                        
            $ret = (object) [];
            
            $ret->DATA_RETURN = $dto_15120->selectConferenciaItens($request);
            
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
            $dto_15120 = new _15120($this->con());
            
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
                
                $dto_15120->deleteKanbanLoteDetalhe($transacao);
                
            }            
            
            
            /**
             * Pepração para o retorno
             */
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = [
                    'DADOS'  => $dto_15120->selectProdutoEstoqueMinimo($request->FILTRO),
                    'TRANSACOES' => $dto_15120->selectTransacao($request->FILTRO_TRANSACAO)
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