<?php

namespace app\Http\Controllers\Estoque\_15070;

use App\Http\Controllers\Estoque\_15070\_15070Controller as Ctrl;
use App\Models\DTO\Estoque\_15070;
use App\Models\DTO\Admin\_11050;


/**
 * Controller do objeto _15070 - Geracao de Remessas de Bojo
 */
class _15070ControllerApi extends Ctrl {
      
    
    public function getConsumo() {
        $this->Menu(false)->incluir();
        try {     
            
            $request = $this->request();
            
            $dto_15070 = new _15070($this->con());

            $consumos = $dto_15070->selectConsumo($request);
            
            $this->con()->commit();
                        
            return $consumos;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getRemessa() {
        $this->Menu(false)->incluir();
        try {     
            
            $request = $this->request();
            
            $dto_15070 = new _15070($this->con());

            $consumos = $dto_15070->selectRemessa($request);
            
            $this->con()->commit();
                        
            return $consumos;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getFamilia() {
        $this->Menu(false)->incluir();
        try {     
            
            $request = $this->request();
            
            $dto_15070 = new _15070($this->con());

            $consumos = $dto_15070->selectFamilia($request);
            
            $this->con()->commit();
                        
            return $consumos;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getTransacao() {
        $this->Menu(false)->incluir();
        try {     
            
            $request = $this->request();
            
            $dto_15070 = new _15070($this->con());

            $avulsas = $dto_15070->selectConsumoTransacaoAvulsa($request);
            $pecas   = $dto_15070->selectConsumoTransacaoPeca($request);
            
            $this->con()->commit();
                        
            return [
                'AVULSA' => $avulsas,
                'PECA'   => $pecas
            ];
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
            $dto_15070 = new _15070($this->con());
            
            $request = $this->request();
            
            validator($request, [
                'DADOS' => ['Dados','required'],
            ],true);  
            
            $transacoes = $request->DADOS;
            
            
            /**
             * Tratamento dos dados
             */
            foreach ( $transacoes as $transacao ) {
                switch ($transacao->TIPO) {
                    case 'A':
                        $dto_15070->deleteConsumoTransacaoAvulsa($transacao);
                        break;
                    case 'P':
                        $dto_15070->deleteConsumoTransacaoPeca($transacao);
                        break;
                }
            }            
            
            
            /**
             * Pepração para o retorno
             */
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = [
                    'DADOS'  => $dto_15070->selectConsumo($request->FILTRO),
                    'AVULSA' => $dto_15070->selectConsumoTransacaoAvulsa($request->FILTRO_TRANSACAO),
                    'PECA'   => $dto_15070->selectConsumoTransacaoPeca($request->FILTRO_TRANSACAO)
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
    
    public function postAvulso() {
        $this->Menu()->incluir('Registrando baixas avulsas');
        try {     
            
            $dto_15070 = new _15070($this->con());
            
            $request = $this->request();
            
            validator($request, [
                'DADOS' => ['Dados','required'],
            ],true);  
            
            $dados = $request->DADOS;
            
            validator($dados, [
                'QUANTIDADE' => ['Quantidade','required'],
                'ITENS'      => ['Itens'     ,'required']
            ],true);  
            
            
            $this->distribuirQuantidade($dados->ITENS,$dados->QUANTIDADE, function($item) use ($dto_15070) {

                $args = (object)[
                    'GP_ID'              => $item->GP_ID,
                    'PERFIL_UP'          => $item->PERFIL_UP,
                    'FAMILIA_ID'         => $item->CONSUMO_FAMILIA_ID,
                    'CONSUMO_ID'         => $item->CONSUMO_ID,        
                    'ESTABELECIMENTO_ID' => $item->REMESSA_ESTABELECIMENTO_ID,
                    'LOCALIZACAO_ID'     => $item->CONSUMO_LOCALIZACAO_ID,       
                    'PRODUTO_ID'         => $item->CONSUMO_PRODUTO_ID,        
                    'TAMANHO'            => $item->CONSUMO_TAMANHO,           
                    'QUANTIDADE'         => $item->QUANTIDADE_CONSUMO,        
                    'TIPO'               => 'S',              
                    'CONSUMO'            => '1',           
                    'CCUSTO'             => $item->UP_CCUSTO,            
                    'OBSERVACAO'         => 'SAIDA POR TRANSACAO DE REMESSA'
                ];

                $dto_15070->insertConsumoTransacao($args);

                $args->LOCALIZACAO_ID = $item->CONSUMO_LOCALIZACAO_ID_PROCESSO;
                $args->TIPO           = 'E';
                $args->CONSUMO        = '1';
                $args->OBSERVACAO     = 'ENTRADA POR TRANSACAO DE REMESSA';

                $dto_15070->insertConsumoTransacao($args);
            });
                        
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = $dto_15070->selectConsumo($request->FILTRO);
            }
            
            $ret->SUCCESS_MSG = 'Baixa realizada com sucesso.';
            
//            $this->con()->rollback();
            $this->con()->commit();
            
            return (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function postPeca() {
        $this->Menu()->incluir('Registrando baixas por peça');
        try {     
            
            $dto_15070 = new _15070($this->con());
            
            $request = $this->request();
            
            validator($request, [
                'DADOS' => ['Dados','required'],
            ],true);  
            
            $dados = $request->DADOS;
            
            validator($dados, [
                'PECA_BARRAS' => ['Código de Barras da Peça','required'],
                'ITENS'       => ['Itens'     ,'required']
            ],true);  
            
            
            $peca = $this->getPecaDisponivel($this->validaBarrasPeca($dados->PECA_BARRAS));
            
            $this->distribuirQuantidade($dados->ITENS,$peca->QUANTIDADE_SALDO, function($item) use ($dto_15070, $peca,$dados) {

                $transacao_id_saida   = $this->con()->gen_id('GTBESTOQUE_TRANSACAO_ITEM');
                $transacao_id_entrada = $this->con()->gen_id('GTBESTOQUE_TRANSACAO_ITEM');
                
                $dto_15070->insertRemessaTalaoVinculo((object)[
                    'CONSUMO_ID'             => $item->CONSUMO_ID,
                    'TIPO'                   => $peca->TIPO,
                    'TABELA_ID'              => $peca->PECA_ID,
                    'PRODUTO_ID'             => $peca->PRODUTO_ID,
                    'TAMANHO'                => $peca->TAMANHO,
                    'QUANTIDADE'             => $item->QUANTIDADE_CONSUMO,
                    'ESTOQUE_ID_ENTRADA'     => $transacao_id_entrada,
                    'ESTOQUE_ID_SAIDA'       => $transacao_id_saida,
                ]);
                
                $args = (object)[
                    'GP_ID'              => $item->GP_ID,
                    'PERFIL_UP'          => $item->PERFIL_UP,
                    'FAMILIA_ID'         => $item->CONSUMO_FAMILIA_ID,
                    'TRANSACAO_ID'       => $transacao_id_saida,        
                    'CONSUMO_ID'         => $item->CONSUMO_ID,        
                    'ESTABELECIMENTO_ID' => $item->REMESSA_ESTABELECIMENTO_ID,
                    'LOCALIZACAO_ID'     => $item->CONSUMO_LOCALIZACAO_ID,       
                    'PRODUTO_ID'         => $item->CONSUMO_PRODUTO_ID,        
                    'TAMANHO'            => $item->CONSUMO_TAMANHO,           
                    'QUANTIDADE'         => $item->QUANTIDADE_CONSUMO,        
                    'TIPO'               => 'S',            
                    'CONSUMO'            => '1',           
                    'CCUSTO'             => $item->UP_CCUSTO,            
                    'OBSERVACAO'         => 'SAIDA POR TRANSACAO DE REMESSA',
                    'PECA_ID'            => $dados->PECA_BARRAS
                ];

                $dto_15070->insertConsumoTransacao($args);

                $args->TRANSACAO_ID   = $transacao_id_entrada;
                $args->LOCALIZACAO_ID = $item->CONSUMO_LOCALIZACAO_ID_PROCESSO;
                $args->TIPO           = 'E';
                $args->CONSUMO        = '1';
                $args->OBSERVACAO     = 'ENTRADA POR TRANSACAO DE REMESSA';

                $dto_15070->insertConsumoTransacao($args);
            });
            
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = $dto_15070->selectConsumo($request->FILTRO);
            }
            
            $ret->SUCCESS_MSG = 'Baixa realizada com sucesso.';
            
//            $this->con()->rollback();
            $this->con()->commit();
            
            return (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    
    public function getPecaDisponivel($param) {
        
        $pecas = [];
        
        $dto_15070 = new _15070($this->con());

        switch ($param->TIPO) {
            case 'R':
                
                $pecas = $dto_15070->selectRevisao((object)['ID' => $param->TABELA_ID]);
                
                break;
            case 'D':

                $pecas = $dto_15070->selectRemessaTalaoDetalhe((object)['ID' => $param->TABELA_ID]);
                
                break;
        }
        
        if ( !isset($pecas[0]) ) {
            log_erro('Não há peças disponíveis para este código. Código informado: ' . $param->BARRAS);
        }
        
        if ( isset($pecas[1]) ) {
            log_erro('Ocorreu uma falha. A consulta retornou mais registros do que o esperado.');
        }
        
        return $pecas[0];
    }
    
    public function getEtiqueta() {
        $this->Menu(false)->incluir();
        try {     
            
            $request = $this->request();
            
            validator($request, [
                'REMESSA_ID' => ['Id da Remessa','required'],
            ],true);  
            
            $dto_15070 = new _15070($this->con());

            
            $dados = $request;

            $lotes = $dto_15070->selectEtiquetaRemessa($dados);
            $lote = (object)[];
            
            if ( isset($lotes[0]) ) {
                $lote = $lotes[0];
            } else {
                log_erro('Lote não localizado');
            }
            
            
//            print_l($lote);
            $lote_detalhes = $dto_15070->selectEtiquetaRemessaComposicaoAgrup($dados);
            
            if ( !isset($lote_detalhes[0]) ) {
                log_erro('Não há itens para serem conferidos.');
            }            
            
            $tag = str_shuffle("ABCDEFG");
            
            $string = '';
            
            /***********************************************
             * Etiqueta do Cabeçalho
             **********************************************/
            
            $str_talao = _11050::etiqueta(182);   

            $lote_descricao       = str_pad($lote->REMESSA, 6, '0', STR_PAD_LEFT);
            $modelo_descricao     = 'TRANS.REMESSA: ' . $lote_descricao;
            $codigo_barras        = 'TR' . str_pad($lote->REMESSA_ID, 11, "0", STR_PAD_LEFT);
            $datahora_iniciado    = '';//date_format(date_create($lote->DATAHORA_INICIADO),'d/m/y H:i:s');
            $datahora_finalizado  = '';//date_format(date_create($lote->DATAHORA_FINALIZADO),'d/m/y H:i:s');
//            $via                  = ' VIA:' . ($lote->VIA_ETIQUETA + 1);
            $impressao            = date('d/m/y H:i:s');
            $usuario              = str_remove_acento($lote->USUARIO);
            
            $str_talao = str_replace('#ETIQUETA#' , $tag     , $str_talao);
            $str_talao = str_replace('ETQPROD' , $tag         , $str_talao);


            $str_talao = str_replace('#MODELO#'              , $modelo_descricao          , $str_talao);                   
            $str_talao = str_replace('#REMESSA#'             ,  ''                        , $str_talao);                    
            $str_talao = str_replace('#DATA_HORA_INICIADO#'  , $datahora_iniciado         , $str_talao);                    
            $str_talao = str_replace('#DATA_HORA_FINALIZADO#', $datahora_finalizado       , $str_talao);                    
            $str_talao = str_replace('#QTD_PRODUTOS#'       , ''                          , $str_talao);                                
            $str_talao = str_replace('#QTD_PROD#'           , ''                          , $str_talao);                              
            $str_talao = str_replace('#QTD_PROJ_ALT#'       , ''                          , $str_talao);                              
            $str_talao = str_replace('#QTD_PROD_ALT#'       , ''                          , $str_talao);                        
            $str_talao = str_replace('#UP#'                 , ''                          , $str_talao);                                   
            $str_talao = str_replace('#COD_BARRAS#'         , $codigo_barras              , $str_talao);
            $str_talao = str_replace('#RENDIMENTO#'         , ''                          , $str_talao);
            $str_talao = str_replace('#UP_DESTINO#'         , ''                          , $str_talao);
            $str_talao = str_replace('#USUARIO#'            , $usuario                    , $str_talao);
            $str_talao = str_replace('#USUARIO2#'           , ''                          , $str_talao);
            $str_talao = str_replace('#DATA_HORA_IMPRESSAO#', $impressao                  , $str_talao);

            $string1 = $str_talao;


            //////////////////////////////////////////////////////////

            $n_itens     = count($lote_detalhes);
            $n_etiquetas =  ceil($n_itens / 10);
            $i           = 0;
            $j           = -1;
            $string2     = '';
            $etiqueta    = _11050::etiqueta(183);
            // Laço que varre a quantidade de etiquetas
            for ($i = 0; $i < $n_etiquetas; $i++) {
                $str_talao   = $etiqueta;

                $str_talao = str_replace('#ETIQUETA#'   , $tag      , $str_talao);
                $str_talao = str_replace('ETQPROD'      , $tag      , $str_talao);

                $str_talao = str_replace('#VIA#'   , $i+1 . '/' . $n_etiquetas  , $str_talao);               
                $str_talao = str_replace('#LOTE#'  , $modelo_descricao          , $str_talao);                    
                $str_talao = str_replace('#RODAPE#', ''                         , $str_talao);   

                //Variável que guarda o sequenciamento dos pares
                $seq = 0;

                //Laço que varre todos os pares da etiqueta
                for ($y = 0; $y < 10; $y++) {
                    $j++;
                    $seq++;

                    //Variável que guarda o número do item
                    $item = 0;

                    //Verifica se o item existe 
                    if (($j+1) <= $n_itens){

                            $detalhe = $lote_detalhes[$j];
                            $produto    = $detalhe->PRODUTO_ID . ' - ' . str_remove_acento($detalhe->PRODUTO_DESCRICAO);
                            $quantidade = str_pad(number_format($detalhe->QUANTIDADE,5,',','.'), 5, ' ', STR_PAD_LEFT) . ' ' . $detalhe->UM;
                            $peca       = 'TAM.: ' . $detalhe->TAMANHO_DESCRICAO;

                            $str_talao = str_replace('#PRODUTO_' . $seq . '#'            , $produto     , $str_talao); 
                            $str_talao = str_replace('#PRODUTO_' . $seq . '_QUANTIDADE#' , $quantidade  , $str_talao);
                            $str_talao = str_replace('#PRODUTO_' . $seq . '_PECA#'       , $peca        , $str_talao);
                      
                    }

                    $str_talao = str_replace('#PRODUTO_' . $seq . '#'            , '', $str_talao); 
                    $str_talao = str_replace('#PRODUTO_' . $seq . '_QUANTIDADE#' , '', $str_talao); 
                    $str_talao = str_replace('#PRODUTO_' . $seq . '_PECA#'       , '', $str_talao); 
                }    

                $string2 = $str_talao . $string2;
            }

            $string = $string2 . $string1;            
            
            
            
            $this->con()->commit();
                        
            return $string;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
   
}