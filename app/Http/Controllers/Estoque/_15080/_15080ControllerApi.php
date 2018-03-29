<?php

namespace app\Http\Controllers\Estoque\_15080;

use App\Http\Controllers\Estoque\_15080\_15080Controller as Ctrl;
use App\Models\DTO\Estoque\_15080;
use App\Models\DTO\Admin\_11050;

/**
 * Controller do objeto _15080 - Geracao de Remessas de Bojo
 */
class _15080ControllerApi extends Ctrl {
      
    public function getLocalizacoes() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_15080 = new _15080($this->con());

            $localizacoes = $dto_15080->selectLocalizacoes();
            
            $this->con()->commit();
                        
            return $localizacoes;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getLotes() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_15080 = new _15080($this->con());

            $lotes = $dto_15080->selectKanbanLote($request);
            
            $this->con()->commit();
                        
            return $lotes;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }

    public function lotes_gerados() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_15080 = new _15080($this->con());

            $lotes         = $dto_15080->lotes_gerados($request);
            $lotes_detalhe = $dto_15080->lotes_gerados_detalhe($request);
            
            $this->con()->commit();
            
            $ret = (object) [];

            $ret->LOTE = $lotes;
            $ret->DETALHE = $lotes_detalhe; 

            return  (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }

    public function excluirItem() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_15080 = new _15080($this->con());

            log_info($request->KANBAN_LOTE_DETALHE_ID);

            $dto_15080->deleteKanbanLoteDetalhe($request);

            $lotes         = $dto_15080->lotes_gerados($request);
            $lotes_detalhe = $dto_15080->lotes_gerados_detalhe($request);

            $ret = (object) [];

            $ret->LOTE = $lotes;
            $ret->DETALHE = $lotes_detalhe; 

            $ret->SUCCESS_MSG = 'Exclusão realizada com sucesso.';
            
            $this->con()->commit();
            
            return  (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }

    }
    
    public function getProdutoEstoqueMinimo() {
        $this->Menu(false)->consultar();
        try {     
            
            $request = $this->request();
            
            $dto_15080 = new _15080($this->con());

            $consumos = $dto_15080->selectProdutoEstoqueMinimo($request);
            
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
            
            set_time_limit(0);
            
            $request = $this->request();
            
            $dto_15080 = new _15080($this->con());

            $ret = $dto_15080->selectTransacao($request);
            
            $this->con()->commit();
                        
            return $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function getPecaDisponivel($param) {
        
        $pecas = [];
        
        $dto_15080 = new _15080($this->con());

        switch ($param->TIPO) {
            case 'R':
                
                $pecas = $dto_15080->selectRevisao((object)['ID' => $param->TABELA_ID]);
                
                break;
            case 'D':

                $pecas = $dto_15080->selectRemessaTalaoDetalhe((object)['ID' => $param->TABELA_ID]);
                
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
    
    public function postLoteIniciar() {
        $this->Menu(false)->incluir();
        try {
            
            $dto_15080 = new _15080($this->con());
            
            $request = $this->request();
            
            validator($request, [
                'DADOS' => ['Dados','required'],
            ],true);  
            
            $dados = $request->DADOS;
            
            validator($dados, [
                'LOCALIZACAO_ID' => ['Id da Localização','required']
            ],true);
            
            $dados->KANBAN_LOTE_ID = $dto_15080->insertKanbanLote($dados);
                        
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = [
                    'DADOS'      => $dto_15080->selectProdutoEstoqueMinimo($dados)
                ];
            }
            
            $ret->DATA_RETURN['LOTE'] = $dto_15080->selectKanbanLote($dados)[0];
            
            $ret->SUCCESS_MSG = 'Lote inicializado com sucesso.';
            
//            $this->con()->rollback();
            $this->con()->commit();
            
            return (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function postLoteContinuar() {
        $this->Menu(false)->incluir();
        try {
            
            $dto_15080 = new _15080($this->con());
            
            $request = $this->request();
            
            validator($request, [
                'DADOS' => ['Dados','required'],
            ],true);  
            
            $dados = $request->DADOS;
            
            validator($dados, [
                'KANBAN_LOTE_ID' => ['Id do Lote','required']
            ],true);
                                    
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = [
                    'DADOS'      => $dto_15080->selectProdutoEstoqueMinimo($dados)
                ];
            }
            
            $ret->DATA_RETURN['LOTE'] = $dto_15080->selectKanbanLote($dados)[0];
            
            $ret->SUCCESS_MSG = 'Lote inicializado com sucesso.';
            
//            $this->con()->rollback();
            $this->con()->commit();
            
            return (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function postLoteCancelar() {
        $this->Menu()->excluir();
        try {
            
            $dto_15080 = new _15080($this->con());
            
            $request = $this->request();
            
            validator($request, [
                'DADOS' => ['Dados','required'],
            ],true);  
            
            $dados = $request->DADOS;
            
            validator($dados, [
                'KANBAN_LOTE_ID' => ['Lote','required']
            ],true);
            
            
            $dto_15080->deleteKanbanLote((object)[
                'KANBAN_LOTE_ID'     => $dados->KANBAN_LOTE_ID
            ]);
            
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = [
                    'DADOS'      => []
                ];
            }
            
            $ret->DATA_RETURN['LOTE'] = (object)[];
            
            $ret->SUCCESS_MSG = 'Lote cancelado com sucesso.';
            
//            $this->con()->rollback();
            $this->con()->commit();
            
            return (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }

    public static function etiqueta($dados, $lote, $lote_detalhes){
    
        /***********************************************
         * Etiqueta do Cabeçalho
         **********************************************/
        $tag = str_shuffle("ABCDEFG");

        $string = '';

        $str_talao = _11050::etiqueta(180);   

        $lote_descricao       = str_pad($dados->KANBAN_LOTE_ID, 6, '0', STR_PAD_LEFT);
        $modelo_descricao     = 'KANBAN LOTE: ' . $lote_descricao;
        $codigo_barras        = 'KB' . str_pad($dados->KANBAN_LOTE_ID, 11, "0", STR_PAD_LEFT);
        $datahora_iniciado    = date_format(date_create($lote->DATAHORA_INICIADO),'d/m/y H:i:s');
        $datahora_finalizado  = date_format(date_create($lote->DATAHORA_FINALIZADO),'d/m/y H:i:s');
        $impressao            = date('d/m/y H:i:s');
        $usuario              = str_remove_acento($lote->USUARIO_FIM);
        
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
        $etiqueta    = _11050::etiqueta(181);
        // Laço que varre a quantidade de etiquetas
        for ($i = 0; $i < $n_etiquetas; $i++) {
            $str_talao   = $etiqueta;

            $str_talao = str_replace('#ETIQUETA#'   , $tag      , $str_talao);
            $str_talao = str_replace('ETQPROD'      , $tag      , $str_talao);

            $str_talao = str_replace('#VIA#'   , $i+1 . '/' . $n_etiquetas  , $str_talao);               
            $str_talao = str_replace('#LOTE#'  , $lote_descricao            , $str_talao);                    
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
                        $peca       = 'TAM.: ' . $detalhe->TAMANHO_DESCRICAO . ' / PECAS: ' . $detalhe->PECAS . ' ';

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

        return $string;
    }
    
    public function postLoteFinalizar() {
        $this->Menu(false)->incluir();
        try {
            
            $dto_15080 = new _15080($this->con());
            
            $request = $this->request();
            
            validator($request, [
                'DADOS' => ['Dados','required'],
            ],true);  
            
            $dados = $request->DADOS;
            
            validator($dados, [
                'KANBAN_LOTE_ID' => ['Lote','required']
            ],true);
            
            
            $dto_15080->updateKanbanLote((object)[
                'KANBAN_LOTE_ID'     => $dados->KANBAN_LOTE_ID,
                'KANBAN_LOTE_STATUS' => '1', // FINALIZAR
            ]);
            
            $lotes = $dto_15080->selectKanbanLote($dados);
            $lote = (object)[];
            
            if ( isset($lotes[0]) ) {
                $lote = $lotes[0];
            } else {
                log_erro('Lote não localizado');
            }
            
            $lote_detalhes = $dto_15080->selectKanbanLoteDetalheAgrup($dados);
            
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = [
                    'DADOS'      => []
                ];
            }
            
            $ret->DATA_RETURN['LOTE'] = (object)[];
            $ret->DATA_RETURN['ETIQUETAS'] = _15080ControllerApi::etiqueta($dados, $lote, $lote_detalhes);
            
            $ret->SUCCESS_MSG = 'Lote finalizado com sucesso.';
            
            $this->con()->commit();
            
            return (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }

    public function imprimirLote() {
        $this->Menu(false)->incluir();
        try {
            
            $dto_15080 = new _15080($this->con());
            
            $request   = $this->request();
            
            validator($request, [
                'DADOS' => ['Dados','required'],
            ],true);  
            
            $dados = $request->DADOS;
            
            validator($dados, [
                'KANBAN_LOTE_ID' => ['Lote','required']
            ],true);
            
            $lotes = $dto_15080->selectKanbanLote($dados);
            $lote = (object)[];
            
            if ( isset($lotes[0]) ) {
                $lote = $lotes[0];
            } else {
                log_erro('Lote não localizado');
            }
            
            $lote_detalhes = $dto_15080->selectKanbanLoteDetalheAgrup($dados);
            
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = [
                    'DADOS'      => []
                ];
            }
            
            $ret->DATA_RETURN['LOTE'] = (object)[];
            $ret->DATA_RETURN['ETIQUETAS'] = _15080ControllerApi::etiqueta($dados, $lote, $lote_detalhes);
            
            $this->con()->commit();
            
            return (array) $ret;
        }
        catch (Exception $e) {
            $this->con()->rollback();
            throw $e;
        }
    }
    
    public function postTransacao() {
        $this->Menu()->incluir('Gravando Transação');
        try {     
            
            $dto_15080 = new _15080($this->con());
            
            $request = $this->request();
            
            validator($request, [
                'DADOS' => ['Dados','required'],
            ],true);  
            
            $dados = $request->DADOS;
            
            validator($dados, [
                'ITENS'      => ['Itens'     ,'required']
            ],true);
            
            if ( !isset($dados->QUANTIDADE) && !isset($dados->PECA_BARRAS) ) {
                log_erro('Informe a "Quantidade" ou o "Código de barras da peça".');
            }
                        
            if ( isset($dados->QUANTIDADE) && !($dados->QUANTIDADE > 0) ) {
                log_erro('Quantidade deve ser maior que zero.');
            } else
            if ( isset($dados->PECA_BARRAS) && $dados->PECA_BARRAS != '' ) {
                $peca = $this->getPecaDisponivel($this->validaBarrasPeca($dados->PECA_BARRAS));
            }
            
            $tipo       = '';
            $quantidade = 0;
            
            if ( isset($peca) ) {
                $tipo = 'PECA';
                $quantidade = $peca->QUANTIDADE_SALDO; 
            } else {
                $tipo = 'QUANTIDADE';
                $quantidade = $dados->QUANTIDADE;
            }
               

            foreach ($dados->ITENS as $item ) {
                
                $transacao_id_saida   = $this->con()->gen_id('GTBESTOQUE_TRANSACAO_ITEM');
                $transacao_id_entrada = $this->con()->gen_id('GTBESTOQUE_TRANSACAO_ITEM');
                $vinculo_id           = 0;
                                
                $args = (object)[
                    'KANBAN_LOTE_ID'     => $item->KANBAN_LOTE_ID,        
                    'ESTOQUE_MINIMO_ID'  => $item->ESTOQUE_MINIMO_ID,        
                    'ESTABELECIMENTO_ID' => $item->ESTABELECIMENTO_ID,
                    'LOCALIZACAO_ID'     => $item->PRODUTO_LOCALIZACAO_ID,    
                    'FAMILIA_ID'         => $item->FAMILIA_ID,
                    'PRODUTO_ID'         => $item->PRODUTO_ID,        
                    'TAMANHO'            => $item->TAMANHO,           
                    'QUANTIDADE'         => $quantidade,        
                    'TIPO'               => 'S',              
                    'CONSUMO'            => '0',           
                    'CCUSTO'             => 0,
                    'OBSERVACAO'         => 'SAIDA POR KANBAN',
                    'TRANSACAO_ID'       => $transacao_id_saida
                ];
               
                $args->PECA_ID            = $vinculo_id;
                $args->ESTOQUE_ID_SAIDA   = $transacao_id_saida;
                $args->ESTOQUE_ID_ENTRADA = $transacao_id_entrada;                

                $lote_detalhe_id = $dto_15080->insertKanbanLoteDetalhe($args);
                
                if ( $tipo == 'PECA' ) {
                    $vinculo_id = $dto_15080->insertRemessaTalaoVinculo((object)[
                        'ESTOQUE_MINIMO_ID'      => $item->ESTOQUE_MINIMO_ID,
                        'TIPO'                   => $peca->TIPO,
                        'TABELA_ID'              => $peca->PECA_ID,
                        'PRODUTO_ID'             => $peca->PRODUTO_ID,
                        'TAMANHO'                => $peca->TAMANHO,
                        'QUANTIDADE'             => $quantidade,
                        'ESTOQUE_ID_ENTRADA'     => $transacao_id_entrada,
                        'ESTOQUE_ID_SAIDA'       => $transacao_id_saida,
                        'KANBAN_LOTE_DETALHE_ID' => $lote_detalhe_id
                    ]);     
                }
                
                $args->KANBAN_LOTE_DETALHE_ID = $lote_detalhe_id;
                $args->PECA_ID                = $dados->PECA_BARRAS;
                
                $dto_15080->insertTransacao($args);

                $args->LOCALIZACAO_ID = $item->LOCALIZACAO_ID;
                $args->TIPO           = 'E';
                $args->OBSERVACAO     = 'ENTRADA POR KANBAN';
                $args->TRANSACAO_ID   = $transacao_id_entrada;

                $dto_15080->insertTransacao($args);
                
            }
                        
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = [
                    'DADOS'      => $dto_15080->selectProdutoEstoqueMinimo($request->FILTRO),
                    'TRANSACOES' => $dto_15080->selectTransacao($request->FILTRO_TRANSACAO)
                ];
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

    public function deleteTransacao() {
        $this->Menu()->excluir('Excluindo baixas realizadas');
        try {     
            /**
             * Preparação da entrada dos dados
             */
            $dto_15080 = new _15080($this->con());
            
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
                
                $dto_15080->deleteKanbanLoteDetalhe($transacao);
                
            }            
            
            
            /**
             * Pepração para o retorno
             */
            $ret = (object) [];
            
            if ( isset($request->FILTRO) ) {
                $ret->DATA_RETURN = [
                    'DADOS'  => $dto_15080->selectProdutoEstoqueMinimo($request->FILTRO),
                    'TRANSACOES' => $dto_15080->selectTransacao($request->FILTRO_TRANSACAO)
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