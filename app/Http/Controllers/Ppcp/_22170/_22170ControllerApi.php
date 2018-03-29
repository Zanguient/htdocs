<?php

namespace app\Http\Controllers\Ppcp\_22170;

use App\Http\Controllers\Ppcp\_22170\_22170Controller as Ctrl;
use App\Models\DTO\Ppcp\_22170;
use App\Helpers\SSE;
use App\Models\Conexao\_Conexao;


/**
 * Controller do objeto _22170 - Geracao de Remessas de Bojo
 */
class _22170ControllerApi extends Ctrl {
      
    
    public function getTalao() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_22170 = new _22170($this->con());

            $ret = $dto_22170->selectTalao($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getTaloesComposicao() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_22170 = new _22170($this->con());

            $ret = $dto_22170->getTaloesComposicao($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getTalaoComposicao() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            validator($request, [
                'TALAO_ID' => ['Id do Talão','required'],
            ],true);              
            
            $dto_22170 = new _22170($this->con());

            $ret = $dto_22170->getTalaoComposicao($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function sse() {

        $request = $this->request();
        
        $sse = new SSE();
        
        $sse->emitEvent(function() use ($sse, $request) {
            $con = new _Conexao;  

            try {
                
                $dto_22170 = new _22170($con);
                
                $sse->setValues($dto_22170->getTaloesComposicao($request));
                
                $dto_22170->__destruct();
                
                $con->commit();
            }
            catch (Exception $e) {
                $con->rollback();
                throw $e;
            }
        });
    }    
    
    public function postTaloesAcao($tipo) {
        $this->Menu()->incluir('Registrando ação no talão');
        try {     
            $request = $this->request();
            
            validator($request, [
                'DADOS' => ['Dados','required'],
            ],true);  
            
            $dados = $request->DADOS;
            
            validator($dados, [
                'ITENS'              => ['Itens'                ,'required'],
                'ESTABELECIMENTO_ID' => ['Id do Estabelecimento','required'],
                'UP_ID'              => ['Id da UP'             ,'required'],
                'ESTACAO'            => ['Id da Estção'         ,'required'],
                'OPERADOR_ID'        => ['Id do Operador'       ,'required'],
            ],true);
            
            if ( !($dados->OPERADOR_ID > 0) ) {
                log_erro('Id do Operador deve ser maior que zero.');
            }

            $dto_22170 = new _22170($this->con());
            
            foreach ( $dados->ITENS as $talao ) {
                
                $talao->ESTABELECIMENTO_ID = $dados->ESTABELECIMENTO_ID;
                $talao->UP_ID              = $dados->UP_ID;
                $talao->ESTACAO            = $dados->ESTACAO;
                $talao->OPERADOR_ID        = $dados->OPERADOR_ID;
                
                validator($talao, [
                    'REMESSA_ID'       => ['Id da Remessa'    ,'required'],
                    'REMESSA_TALAO_ID' => ['Controle do Talão','required'],
                    'TALAO_ID'         => ['Id do Talão'      ,'required'],
                    'PROGRAMACAO_ID'   => ['Id da Programação','required'],
                ],true);                
                
                switch (strtoupper($tipo)) {
                    case 'INICIAR':

                        $talao->PROGRAMACAO_HISTORICO_STATUS = 0; // 0 - INICIADO/REINICIADO
                        $talao->PROGRAMACAO_STATUS           = 2; // 2 - EM ANDAMENTO
                        $talao->ESTACAO_TALAO_ID             = $talao->TALAO_ID;
                            
                        break;
                    case 'PAUSAR':
                        $talao->PROGRAMACAO_HISTORICO_STATUS = 1; // 1 - PARADA TEMPORÁRIA
                        $talao->PROGRAMACAO_STATUS           = 1; // 1 - INICIADO/PARADO
                        $talao->ESTACAO_TALAO_ID             = 0;
                            
                        break;
                    case 'FINALIZAR':
                                                    
                        $talao->PROGRAMACAO_HISTORICO_STATUS = 2; // 2 - FINALIZADO
                        $talao->PROGRAMACAO_STATUS           = 3; // 3 - FINALIZADO
//                        $talao->REMESSA_TALAO_STATUS         = 2; // 2 - PRODUZIDO
                        $talao->ESTACAO_TALAO_ID             = 0;
                            
                        break;
                    default :
                        log_erro('Acão inválida.');
                        break;
                }
                
                $dto_22170->postTalaoAcao($talao);
            }
            
            
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = $dto_22170->getTaloesComposicao($request->FILTRO);
            }
            
            $ret->SUCCESS_MSG = 'Ação realizada com sucesso.';
            
            
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
            $dto_22170 = new _22170($this->con());
            
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
                switch ($transacao->TIPO) {
                    case 'AVULSO':
                        $dto_22170->deleteTransacaoAvulsa($transacao);
                        break;
                    case 'PEÇA':
                        $dto_22170->deleteTransacaoPeca($transacao);
                        break;
                }
            }            
            
            
            /**
             * Pepração para o retorno
             */
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = [
                    'DADOS'  => $dto_22170->selectProdutoPpcpMinimo($request->FILTRO),
                    'TRANSACOES' => $dto_22170->selectTransacao($request->FILTRO_TRANSACAO)
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
 
    

    public function getConsumoBaixado() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_22170 = new _22170($this->con());

            $consumos = $dto_22170->selectConsumo($request);
            
            $this->con()->commit();
                        
            return $consumos;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }

    public function getConsumoBaixadoTransacao() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            validator($request, [
                'CONSUMO_ID' => ['Código do Consumo','required'],
            ],true);  
                        
            $dto_22170 = new _22170($this->con());

            $consumos = $dto_22170->selectTransacao($request);
            
            $this->con()->commit();
                        
            return $consumos;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
        

    public function postConsumoBaixadoTransacaoDelete() {
        $this->Menu()->excluir('Excluindo transação');
        try {     
            
            $request = $this->request();
            
            validator($request, [
                'DADOS' => ['Dados','required'],
            ],true);  
            
            $dados = $request->DADOS;
            
            validator($dados, [
                'ITENS' => ['Itens'     ,'required']
            ],true);
            
            $transacoes = $dados->ITENS;
            
            $dto_22170 = new _22170($this->con());
            
            /**
             * Tratamento dos dados
             */
            foreach ( $transacoes as $transacao ) {

                $dto_22170->deleteTransacao($transacao); 
                $dto_22170->updateConsumo((object) [
                    'QUANTIDADE_CONSUMO' => -$transacao->QUANTIDADE,
                    'CONSUMO_STATUS'     => '0',
                    'CONSUMO_ID'         => $transacao->CONSUMO_ID,
                ]); 
            }     
                        
            
            /**
             * Pepração para o retorno
             */
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = [
                    'DADOS'      => $dto_22170->selectConsumo  ($request->FILTRO),
                    'TRANSACOES' => $dto_22170->selectTransacao($request->FILTRO_TRANSACAO)
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
        
    
    public function getEtiqueta() {
        $this->Menu()->consultar('Imprimindo etiquetas');
        try {     
            
            $request = $this->request();
            
            validator($request, [
                'ITENS' => ['Itens','required'],
            ],true);  
            
            $itens = $request->ITENS;
            
            $dto_22170 = new _22170($this->con());
            
            $etiquetas = '';
            
            /**
             * Tratamento dos dados
             */
            foreach ( $itens as $item ) {

                $etiquetas = $etiquetas . $this->processarEtiquetas($item,$dto_22170);                
                
            }     
            
            $this->con()->commit();

                        
            return response()->json($etiquetas);
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }        
    }
   
}