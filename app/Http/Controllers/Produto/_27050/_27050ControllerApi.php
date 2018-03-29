<?php

namespace app\Http\Controllers\Produto\_27050;

use App\Http\Controllers\Produto\_27050\_27050Controller as Ctrl;
use App\Models\DTO\Produto\_27050;
use App\Helpers\SSE;
use App\Models\Conexao\_Conexao;


/**
 * Controller do objeto _27050 - Geracao de Remessas de Bojo
 */
class _27050ControllerApi extends Ctrl {
      
    
    public function getProduto() {
//        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_27050 = new _27050($this->con());

            $ret = $dto_27050->selectProduto($request);
            
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
            
            $dto_27050 = new _27050($this->con());

            $ret = $dto_27050->getTaloesComposicao($request);
            
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
            
            $dto_27050 = new _27050($this->con());

            $args = (object)['TALAO_ID'=>$request->TALAO_ID];
            
            $ret = $dto_27050->getTalaoComposicao($args,true);
            
            $this->con()->commit();
                        
            return response()->json($ret);
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
                
                $dto_27050 = new _27050($con);
                
                $sse->setValues($dto_27050->getTaloesComposicao($request));
                
                $dto_27050->__destruct();
                
                $con->commit();
            }
            catch (Exception $e) {
                $con->rollback();
                throw $e;
            }
        });
    }    
    
    public function postTaloesAcao($tipo) {
        $this->Menu()->incluir('Registrando Baixa de Consumo');
        try {     
            $request = $this->request();
            
            validator($request, [
                'DADOS' => ['Dados','required'],
            ],true);  
            
            $dados = $request->DADOS;
            
            validator($dados, [
                'TALAO'              => ['Talão'                ,'required'],
                'ESTABELECIMENTO_ID' => ['Id do Estabelecimento','required'],
                'UP_ID'              => ['Id da UP'             ,'required'],
                'ESTACAO'            => ['Id da Estção'         ,'required'],
                'OPERADOR_ID'        => ['Id do Operador'       ,'required'],
            ],true);
            
            if ( !($dados->OPERADOR_ID > 0) ) {
                log_erro('Id do Operador deve ser maior que zero.');
            }
            
            
            validator($dados->TALAO, [
                'TALAO_ID'       => ['Talão'                ,'required'],
                'PROGRAMACAO_ID' => ['Itens'                ,'required'],
            ],true);            
            
            $dto_27050 = new _27050($this->con());
            
            $etiquetas = (object)['ETIQUETA' => ''];
            
            if ( strtoupper($tipo) == 'INICIAR' || strtoupper($tipo) == 'PAUSAR' ) {
                $dados->ITENS = [$dados->TALAO];
            } else {
                validator($dados, [
                    'ITENS' => ['Itens' ,'required'],
                ],true);
            }
            
            
            foreach ( $dados->ITENS as $talao ) {
                
                $talao->ESTABELECIMENTO_ID = $dados->ESTABELECIMENTO_ID;
                $talao->UP_ID              = $dados->UP_ID;
                $talao->ESTACAO            = $dados->ESTACAO;
                $talao->OPERADOR_ID        = $dados->OPERADOR_ID;
                $talao->TALAO_ID           = $dados->TALAO->TALAO_ID;
                $talao->PROGRAMACAO_ID     = $dados->TALAO->PROGRAMACAO_ID;
                $talao->ULTIMO_TALAO       = $dados->TALAO->ULTIMO_TALAO == true ? true : false;
                
                validator($talao, [
                    'REMESSA_ID'       => ['Id da Remessa'    ,'required'],
                    'REMESSA_TALAO_ID' => ['Controle do Talão','required'],
                ],true);                
                
                
                if ( !(strtoupper($tipo) == 'ETIQUETA') ) {
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

                            if ( $talao->ULTIMO_TALAO ) {
                                $talao->PROGRAMACAO_HISTORICO_STATUS = 2; // 2 - FINALIZADO
                                $talao->PROGRAMACAO_STATUS           = 3; // 3 - FINALIZADO
        //                        $talao->REMESSA_TALAO_STATUS         = 2; // 2 - PRODUZIDO
                                $talao->ESTACAO_TALAO_ID             = 0;
                            }
                            
                            $talao->TALAO_DETALHE_STATUS         = 2; // 2 - PRODUZIDO

                            break;
                        default :
                            log_erro('Acão inválida.');
                            break;
                    }

                    $dto_27050->postTalaoAcao($talao);
                }
                
//                $etiquetas->ETIQUETA = $etiquetas->ETIQUETA . $this->processarEtiquetas($talao,$dto_27050);
            }
            
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = $dto_27050->getTaloesComposicao($request->FILTRO);
                $ret->ETIQUETAS   = $etiquetas->ETIQUETA;
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
            $dto_27050 = new _27050($this->con());
            
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
                        $dto_27050->deleteTransacaoAvulsa($transacao);
                        break;
                    case 'PEÇA':
                        $dto_27050->deleteTransacaoPeca($transacao);
                        break;
                }
            }            
            
            
            /**
             * Pepração para o retorno
             */
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = [
                    'DADOS'  => $dto_27050->selectProdutoProdutoMinimo($request->FILTRO),
                    'TRANSACOES' => $dto_27050->selectTransacao($request->FILTRO_TRANSACAO)
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
            
            $dto_27050 = new _27050($this->con());

            $consumos = $dto_27050->selectConsumo($request);
            
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
                        
            $dto_27050 = new _27050($this->con());

            $consumos = $dto_27050->selectTransacao($request);
            
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
            
            $dto_27050 = new _27050($this->con());
            
            /**
             * Tratamento dos dados
             */
            foreach ( $transacoes as $transacao ) {

                $dto_27050->deleteTransacao($transacao); 
                $dto_27050->updateConsumo((object) [
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
                    'DADOS'      => $dto_27050->selectConsumo  ($request->FILTRO),
                    'TRANSACOES' => $dto_27050->selectTransacao($request->FILTRO_TRANSACAO)
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
            
            $dto_27050 = new _27050($this->con());
            
            $etiquetas = '';
            
            /**
             * Tratamento dos dados
             */
            foreach ( $itens as $item ) {

                $etiquetas = $etiquetas . $this->processarEtiquetas($item,$dto_27050);                
                
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