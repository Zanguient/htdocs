<?php

namespace app\Http\Controllers\Ppcp\_22160;

use App\Http\Controllers\Ppcp\_22160\_22160Controller as Ctrl;
use App\Models\DTO\Ppcp\_22160;


/**
 * Controller do objeto _22160 - Geracao de Remessas de Bojo
 */
class _22160ControllerApi extends Ctrl {
      
    
    public function getConsumoBaixar() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_22160 = new _22160($this->con());

            $consumos = $dto_22160->selectConsumo($request);
            
            $this->con()->commit();
                        
            return $consumos;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }

    public function operador() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_22160 = new _22160($this->con());

            $operador = $dto_22160->selectOperador($request);
            
            $this->con()->commit();
                        
            return (array) $operador;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function postConsumoBaixar() {
        $this->Menu()->incluir('Registrando Baixa de Consumo');
        try {     
            $request = $this->request();
            
            validator($request, [
                'DADOS'    => ['Dados'    , 'required'],
                'OPERADOR' => ['Operador' , 'required'],
            ],true);  
            
            $dados    = $request->DADOS;
            $operador = $request->OPERADOR;
            
            validator($dados, [
                'ITENS'      => ['Itens'     ,'required']
            ],true);
            
            if ( !isset($dados->PESO) ) {
                log_erro('Informe a "Qtd. Baixar".');
            }
                        
            if ( !($dados->PESO > 0) ) {
                log_erro('"Qtd. Baixar" deve ser maior que zero.');
            }
            
            $dto_22160 = new _22160($this->con());
            
            $etiquetas = (object)['ETIQUETA' => ''];
            
            $this->distribuirQuantidade($dados->ITENS,$dados->PESO, function($item) use ($dto_22160,$etiquetas,$operador) {
                    
                $item->CONSUMO_STATUS = '0';

                if ( $item->QUANTIDADE_CONSUMO > $item->CONSUMO_TOLERANCIA_MIN ) {
                    $item->CONSUMO_STATUS = '1';
                    
                    $etiquetas->ETIQUETA = $etiquetas->ETIQUETA . $this->processarEtiquetas($item,$dto_22160);
                }

                $dto_22160->updateConsumo($item);

                $args = (object)[
                    'GP_ID'              => $item->TALAO_GP_ID,
                    'PERFIL_UP'          => $item->TALAO_PERFIL_UP,
                    'FAMILIA_ID'         => $item->CONSUMO_FAMILIA_ID,
                    'LOCALIZACAO_ID'     => $item->CONSUMO_LOCALIZACAO_ID,       
                    'CONSUMO_ID'         => $item->CONSUMO_ID,        
                    'ESTABELECIMENTO_ID' => $item->CONSUMO_ESTABELECIMENTO_ID,
                    'PRODUTO_ID'         => $item->CONSUMO_PRODUTO_ID,        
                    'TAMANHO'            => $item->CONSUMO_TAMANHO,           
                    'QUANTIDADE'         => $item->QUANTIDADE_CONSUMO,        
                    'TIPO'               => 'S',            
                    'CONSUMO'            => '1',           
                    'CCUSTO'             => $item->TALAO_UP_CCUSTO,            
                    'OBSERVACAO'         => 'PAINEL DE CONSUMO DE MATERIA-PRIMA. REMESSA: ' . $item->REMESSA . ' TALAO: ' . $item->REMESSA_TALAO_ID,
                    'DOCUMENTO'          => $item->REMESSA_ID
                ];

                $dto_22160->insertTransacao($args);

                $param = (object) [];
                $param->TABELA    = 'TBREMESSA';
                $param->TABELA_ID = $item->REMESSA_ID;
                $param->HISTORICO = 'CONSUMO DE MATERIA-PRIMA BAIXADA.'.
                                    ' TALAO->'      . $item->REMESSA_TALAO_ID.
                                    ' PRODUTO->'   . $item->CONSUMO_PRODUTO_ID.
                                    ' TAMANHO->'    . $item->CONSUMO_TAMANHO.
                                    ' QUANTIDADE->' . $item->QUANTIDADE_CONSUMO.
                                    ' OPERADOR->'   . $operador->ID .' - '. $operador->NOME;
                                    

                $dto_22160->historico($param);
                
            });
                        
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = $dto_22160->selectConsumo($request->FILTRO);
                $ret->ETIQUETAS = $etiquetas->ETIQUETA;
            }

            $ret->SUCCESS_MSG = 'Baixa realizada com sucesso.';
            
//            print_l($ret);
//            $this->con()->rollback();
            $this->con()->commit();
            
//            print_l($ret);
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
            $dto_22160 = new _22160($this->con());
            
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
                        $dto_22160->deleteTransacaoAvulsa($transacao);
                        break;
                    case 'PEÇA':
                        $dto_22160->deleteTransacaoPeca($transacao);
                        break;
                }
            }            
            
            
            /**
             * Pepração para o retorno
             */
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = [
                    'DADOS'  => $dto_22160->selectProdutoPpcpMinimo($request->FILTRO),
                    'TRANSACOES' => $dto_22160->selectTransacao($request->FILTRO_TRANSACAO)
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
            
            $dto_22160 = new _22160($this->con());

            $consumos = $dto_22160->selectConsumo($request);
            
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
                        
            $dto_22160 = new _22160($this->con());

            $consumos = $dto_22160->selectTransacao($request);
            
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
            
            $dto_22160 = new _22160($this->con());
            
            /**
             * Tratamento dos dados
             */
            foreach ( $transacoes as $transacao ) {

                $dto_22160->deleteTransacao($transacao); 
                $dto_22160->updateConsumo((object) [
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
                    'DADOS'      => $dto_22160->selectConsumo  ($request->FILTRO),
                    'TRANSACOES' => $dto_22160->selectTransacao($request->FILTRO_TRANSACAO)
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
            
            $dto_22160 = new _22160($this->con());
            
            $etiquetas = '';
            
            /**
             * Tratamento dos dados
             */
            foreach ( $itens as $item ) {

                $etiquetas = $etiquetas . $this->processarEtiquetas($item,$dto_22160);                
                
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