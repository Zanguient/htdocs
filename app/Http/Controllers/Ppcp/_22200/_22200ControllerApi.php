<?php

namespace app\Http\Controllers\Ppcp\_22200;

use App\Http\Controllers\Ppcp\_22200\_22200Controller as Ctrl;
use App\Models\DTO\Ppcp\_22200;
use App\Helpers\SSE;
use App\Models\Conexao\_Conexao;
use App\Models\DTO\Ppcp\_22010;


/**
 * Controller do objeto _22200 - Geracao de Remessas de Bojo
 */
class _22200ControllerApi extends Ctrl {
      
    
    public function getTalao() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_22200 = new _22200($this->con());

            $ret = $dto_22200->selectTalao($request);
            
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
            
            $dto_22200 = new _22200($this->con());

            $ret = $dto_22200->getTaloesComposicao($request);
            
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
            
            $dto_22200 = new _22200($this->con());

            $args = (object)['TALAO_ID'=>$request->TALAO_ID];
            
            $ret = $dto_22200->getTalaoComposicao($args,true);
            
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
                
                $dto_22200 = new _22200($con);
                
                $sse->setValues($dto_22200->getTaloesComposicao($request));
                
                $dto_22200->__destruct();
                
                $con->commit();
            }
            catch (Exception $e) {
                $con->rollback();
                throw $e;
            }
        });
    }    
    
    public function postTalaoLiberar() {
        $this->Menu()->alterar('Registrando liberação do talão');
        try {     
            
            $dados = $this->request();
            
            validator($dados, [
                'OPERADOR_ID'    => ['Id do Operador'         ,'required'],
                'GP_ID'          => ['Id do Grupo de Produção','required'],
                'TALOES_LIBERAR' => ['Talões a Liberar','required'],
            ],true);
            
            $dto_22200 = new _22200($this->con());
            
            foreach ( $dados->TALOES_LIBERAR as $talao ) {
                
                $talao->GP_ID       = $dados->GP_ID;
                $talao->OPERADOR_ID = $dados->OPERADOR_ID;
                $dto_22200->postTalaoLiberar($talao);
            }
            
            $ret = (object) [];
            
//            if ( isset($request->FILTRO) ) {
//                $ret->DATA_RETURN = $dto_22200->getTaloesComposicao($request->FILTRO);
//            }
            
            $ret->SUCCESS_MSG = 'Liberação realizada com sucesso.';
            
            
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
            $dto_22200 = new _22200($this->con());
            
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
                        $dto_22200->deleteTransacaoAvulsa($transacao);
                        break;
                    case 'PEÇA':
                        $dto_22200->deleteTransacaoPeca($transacao);
                        break;
                }
            }            
            
            
            /**
             * Pepração para o retorno
             */
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = [
                    'DADOS'  => $dto_22200->selectProdutoPpcpMinimo($request->FILTRO),
                    'TRANSACOES' => $dto_22200->selectTransacao($request->FILTRO_TRANSACAO)
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
            
            $dto_22200 = new _22200($this->con());

            $consumos = $dto_22200->selectConsumo($request);
            
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
                        
            $dto_22200 = new _22200($this->con());

            $consumos = $dto_22200->selectTransacao($request);
            
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


            $dto_22200 = new _22200($this->con());
            
            /**
             * Verifica para onde será direcionado a consulta
             * 'RA' = [R=REMESSA; A=ACUMULADO] Id do talão Acumulado 
             */
            if ( strstr($cod_barras, 'RA') ) { 
                $id = (float) str_replace('RA', '', $cod_barras);
                $tipo = 'D'; // Registra vínculo a partir do talão detalhado

                $res = $dto_22200->selectTalaoConsumoComponenteVinculo((object)[
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

                $dto_22200->insertTalaoConsumoComponenteVinculo((object)[
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
                    'DADOS'      => $dto_22200->getTalaoComposicao($request->FILTRO,true)
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
            

            $dto_22200 = new _22200($this->con());
  
            $dto_22200->deleteTalaoConsumoComponente($dados);
            
            
            /**
             * Pepração para o retorno
             */
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = [
                    'DADOS'      => $dto_22200->getTalaoComposicao($request->FILTRO,true)
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
            
            $dto_22200 = new _22200($this->con());
            
            $etiquetas = '';
            
            /**
             * Tratamento dos dados
             */
            foreach ( $itens as $item ) {

                $etiquetas = $etiquetas . $this->processarEtiquetas($item,$dto_22200);                
                
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