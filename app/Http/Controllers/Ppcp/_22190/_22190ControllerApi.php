<?php

namespace app\Http\Controllers\Ppcp\_22190;

use App\Http\Controllers\Ppcp\_22190\_22190Controller as Ctrl;
use App\Models\DTO\Ppcp\_22190;
use App\Helpers\SSE;
use App\Models\Conexao\_Conexao;
use App\Models\DTO\Ppcp\_22010;


/**
 * Controller do objeto _22190 - Geracao de Remessas de Bojo
 */
class _22190ControllerApi extends Ctrl {
      
    
    public function getTalao() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_22190 = new _22190($this->con());

            $ret = $dto_22190->selectTalao($request);
            
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
            
            $dto_22190 = new _22190($this->con());

            $ret = $dto_22190->getTaloesComposicao($request);
            
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
            
            $dto_22190 = new _22190($this->con());

            $args = (object)['TALAO_ID'=>$request->TALAO_ID];
            
            $ret = $dto_22190->getTalaoComposicao($args,true);
            
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
                
                $dto_22190 = new _22190($con);
                
                $sse->setValues($dto_22190->getTaloesComposicao($request));
                
                $dto_22190->__destruct();
                
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
            
            $dto_22190 = new _22190($this->con());
            
            $etiquetas = (object)['ETIQUETA' => ''];
            
            if ( strtoupper($tipo) == 'INICIAR' || strtoupper($tipo) == 'PAUSAR' ) {
                $dados->ITENS = [$dados->TALAO];
            } else {
                validator($dados, [
                    'ITENS' => ['Itens' ,'required'],
                ],true);
            }
            
            $qtd_itens = count($dados->ITENS);
            $i         = 1;
            
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

                            if ( $talao->ULTIMO_TALAO && $i++ == $qtd_itens ) {
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

                    $dto_22190->postTalaoAcao($talao);
                }
                
//                $etiquetas->ETIQUETA = $etiquetas->ETIQUETA . $this->processarEtiquetas($talao,$dto_22190);
            }
            
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = $dto_22190->getTaloesComposicao($request->FILTRO);
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
            $dto_22190 = new _22190($this->con());
            
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
                        $dto_22190->deleteTransacaoAvulsa($transacao);
                        break;
                    case 'PEÇA':
                        $dto_22190->deleteTransacaoPeca($transacao);
                        break;
                }
            }            
            
            
            /**
             * Pepração para o retorno
             */
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = [
                    'DADOS'  => $dto_22190->selectProdutoPpcpMinimo($request->FILTRO),
                    'TRANSACOES' => $dto_22190->selectTransacao($request->FILTRO_TRANSACAO)
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
            
            $dto_22190 = new _22190($this->con());

            $consumos = $dto_22190->selectConsumo($request);
            
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
                        
            $dto_22190 = new _22190($this->con());

            $consumos = $dto_22190->selectTransacao($request);
            
            $this->con()->commit();
                        
            return $consumos;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
        

    public function postComponenteAlocar() {
        $this->Menu()->excluir('Alocando componente');
        try {     
            
            $request = $this->request();
            
            validator($request, [
                'DADOS' => ['Dados','required'],
            ],true);  
            
            $dados = $request->DADOS;
            
            validator($dados, [
                'COMPONENTE_BARRAS' => ['Código de Barras do Componente','required'],
                'REMESSA_ID'        => ['Id da Remessa','required'],
                'REMESSA_TALAO_ID'  => ['Controle do Talão','required'],
                'TALAO_ID'          => ['Id do talão','required'],
            ],true);
            

            $cod_barras = $dados->COMPONENTE_BARRAS;

            /**
             * Verifica se a quantidade de caracteres do código de barras é diferente de 13
             */
            if ( strlen($cod_barras) != 13 && strlen($cod_barras) != 12 ) {
                log_erro('Código de barras de componente inválido. Código: ' . $cod_barras);
            }


            $dto_22190 = new _22190($this->con());
            
            /**
             * Verifica para onde será direcionado a consulta
             * 'RA' = [R=REMESSA; A=ACUMULADO] Id do talão Acumulado 
             */
            if ( strstr($cod_barras, 'RA') ) { 
                $id = (float) str_replace('RA', '', $cod_barras);
                $tipo = 'D'; // Registra vínculo a partir do talão detalhado

                $res = $dto_22190->selectTalaoConsumoComponenteVinculo((object)[
                    'REMESSA_ID_ORIGEM'       => $dados->REMESSA_ID,
                    'REMESSA_TALAO_ID_ORIGEM' => $dados->REMESSA_TALAO_ID,
                    'TALAO_ID_DESTINO'        => $id,
                ]);
            }
            else {
                log_erro('Tipo de código de barras de componente inválido. Código: ' . $cod_barras);
            }

            if ( !isset($res[0]) ) {
                log_erro('Registro não localizado, Talão '.((float) str_replace('RA', '', $cod_barras)).' não produzido, ou não vinculado a esta remessa.');
            }

            $param = [];

            //log_info($res);

            foreach ($res as $item) {

                if ( !( $item->QUANTIDADE_SALDO > 0 ) ) {
                    log_erro('Não há saldo disponível para este item! Cód. Talão: ' . $item->REMESSA_TALAO_DETALHE_ID);
                }

                $dto_22190->insertTalaoConsumoComponenteVinculo((object)[
                    'TALAO_ID'          => $dados->TALAO_ID,
                    'CONSUMO_ID'        => $item->CONSUMO_ID,
                    'TIPO'              => $tipo,
                    'ITEM_ESTOQUE_ID'   => $item->REMESSA_TALAO_DETALHE_ID,
                    'QUANTIDADE_ALOCAR' => $item->QUANTIDADE_SALDO
                ]);
            }
            
            
            /**
             * Pepração para o retorno
             */
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = [
                    'DADOS'      => $dto_22190->getTalaoComposicao($request->FILTRO,true)
                ];
            }
            
            $ret->SUCCESS_MSG = 'Componente alocado com sucesso.';
            
//            $this->con()->rollback();
            $this->con()->commit();

                        
            return (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
        
    public function deleteComponenteAlocado() {
        $this->Menu()->excluir('Excluindo componente alocado');
        try {     
            
            $request = $this->request();
            
            validator($request, [
                'DADOS' => ['Dados','required'],
            ],true);  
            
            $dados = $request->DADOS;
            
            validator($dados, [
                'COMPONENTE_TALAO_ID' => ['Id do talão de componente','required'],
                'TALAO_ID'            => ['Id do Talão','required'],
            ],true);
            

            $dto_22190 = new _22190($this->con());
  
            $dto_22190->deleteTalaoConsumoComponente($dados);
            
            
            /**
             * Pepração para o retorno
             */
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = [
                    'DADOS'      => $dto_22190->getTalaoComposicao($request->FILTRO,true)
                ];
            }
            
            $ret->SUCCESS_MSG = 'Componente excluído com sucesso.';
            
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
            
            $dto_22190 = new _22190($this->con());
            
            $etiquetas = '';
            
            /**
             * Tratamento dos dados
             */
            foreach ( $itens as $item ) {

                $etiquetas = $etiquetas . $this->processarEtiquetas($item,$dto_22190);                
                
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