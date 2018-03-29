<?php

namespace app\Http\Controllers\Ppcp\_22100;

use App\Http\Controllers\Ppcp\_22100\Controller as Ctrl;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Admin\_11010;
use App\Models\DTO\Ppcp\_22100;

/**
 * Controller do objeto _22100 - Geracao de Remessas de Bojo
 */
class ControllerStore extends Ctrl {
    
    private $remessa_id               = 0;
    private $talao_id                 = 0;
    private $remessa_talao_detalhe_id = 0;

    private $agrupamentos   = [];
    private $taloes         = [];
    private $taloes_detalhe = [];
    private $alocacoes      = [];
    private $programacoes   = [];
   
    private $controle           = 0;
    private $quantidade_talao   = 0;
    private $quantidade_saldo   = 0;
    private $cota_talao         = 0;
    
    private $tempo_multiplicador = 0;
    private $setup               = null;
    private $minutagem           = 0;
    
    private $quantidade_talao_detalhe = 0;
    private $cota_talao_detalhe       = 0;

    public function store(Request $request) {

        try {
            
            $this->valid();
            
            set_time_limit ( 0 );
            @ini_set('memory_limit','700M');
            
//            $array = arrayToObject($request->all());
            

            $array = json_decode(json_encode((object) $request->all()), false);
            
            parent::setRequest($array);

            parent::setCon();

//            $linhas   = obj_case($request->all()['linhas'  ]);
           
            $this->remessa_id = $this->insertRemessa(parent::getRequest()->FILTRO);

            $this->agrupamentos = parent::getRequest()->AGRUPAMENTOS;
            
            /**
             * Desbloqueia pedidos
             */
            _22100::insertPedidoDesbloqueio([],parent::getCon()); 
            
        
            $this->processarEstacoes(parent::getRequest()->ESTACOES);

            
//              
//            log_info($this->taloes);
//            log_info($this->taloes_detalhe);
//            log_info($this->alocacoes);
//            print_l($this->programacoes);

            
            
            _22100::spPedidoItemIntegridade([],parent::getCon()); 
                        
            parent::getCon()->commit();
            

            return Response::json([
                'success' => 'Remessa gerada com sucesso!',
                'remessa' => $this->remessa_id
            ]);
        }
        catch (Exception $e)
        {
			parent::getCon()->rollback();
			throw $e;
		}
        
    }

    private function valid() {
        _11010::permissaoMenu($this->menu,'INCLUIR','Gravando Remessa');
    }

    /**
     * Insere a remessa e retorna o id
     * @param array $args Recebe os parametros de inserção da remessa
     * @return integer
     */
    private function processarEstacoes ($args) {

        $estacoes = $args;

        foreach ( $estacoes as $estacao ) {

            if ( isset($estacao->itens_programados[0]) ) {
                
                $this->processarItensProgramados($estacao->itens_programados);
            }
        }        
    }
    
    private function processarItensProgramados($args) {
        
        $itens_programados = $args;
        
        foreach ( $itens_programados as $item ) {
            
            if ( isset($item->DATAHORA_INICIO) ) { continue; }
                    
            $this->agrupamentoVincular($item);
            
            // Captura a cota de itens por talão
            $this->cota_talao = $item->TALAO_COTA;
                    
            // Captura o tempo o talão
            $this->tempo_multiplicador = $item->TEMPO_ITEM / $item->QUANTIDADE_PROGRAMADA;
            
            // Inicializa a minutagem do talão
            $this->minutagem = $item->TEMPO_INICIO;
                                
            // Captura a cota de itens por talão detalhado
            $this->cota_talao_detalhe = $item->TALAO_DETALHE_COTA;

            // Captura a quantidade a ser programada
            $this->quantidade_saldo = $item->QUANTIDADE_PROGRAMADA;

            $this->processarTaloes($item);
        }
    }
    
    private function agrupamentoVincular($item) {
        
        $item->AGRUPAMENTO = [];
        
        foreach ( $this->agrupamentos as $agrupamento ) {
            if ( $agrupamento->PRODUTO_ID == $item->PRODUTO_ID && $agrupamento->TAMANHO == $item->TAMANHO ) {
                array_push($item->AGRUPAMENTO, $agrupamento);
            }
        }
        
    }
    
    /**
     * Insere a remessa e retorna o id
     * @param array $item Recebe os parametros de inserção da remessa
     * @return integer
     */
    private function processarTaloes($item) {
        
        // Habilita o setup para o primeiro talão
        $this->setup = true;
        
        do {

            // Define a quantidade do talão com base na cota
            $this->processarTalaoSaldo();

            // Refina a quantidade do talão com base no agrupamento
            $this->processarAgrupamentoSaldo($item->AGRUPAMENTO);
            
            // Controle do talão (campo REMESSA_TALAO_ID)
            $this->controle += 1;
            
            // Grava o talão e recupera o id do talão
            $this->talao_id = $this->insertRemessaTalao($item);
            
            // Insere a programação do talão
            $this->insertProgramacao($item);
            
            // Processa a gravação dos talões detalhados
            $this->processarTaloesDetalhe($item);
            
            // Atualiza o saldo utilizado no agrupamento com base no talão corrente
            $this->processarAgrupamentoSaldoAtualizar($item->AGRUPAMENTO);         
            
            //Desativa o setup
            $this->setup = false;
//            log_info($this->controle . ' ' . $this->talao_id);
        } while ( round($this->quantidade_saldo) > 0 );
                
    }
    
    /**
     * Calcula a quantidade a ser utlizada no talão com base na cota do modelo
     */
    private function processarTalaoSaldo() {
        switch(true) {

            case ($this->quantidade_saldo <= $this->cota_talao) : 
                $this->quantidade_talao = $this->quantidade_saldo;
                $this->quantidade_saldo = 0;
            break;

            case ($this->quantidade_saldo > $this->cota_talao) : 
                $this->quantidade_talao = $this->cota_talao; 
                $this->quantidade_saldo = $this->quantidade_saldo - $this->cota_talao;
            break;
        }
    }
    
    
    /**
     * Insere a remessa e retorna o id
     * @param array $item Recebe os parametros de inserção da remessa
     * @return integer
     */
    private function processarTaloesDetalhe($item) {

        do {

            $this->processarTalaoDetalheSaldo();
            
            $this->remessa_talao_detalhe_id = $this->insertRemessaTalaoDetalhe($item);
            
            $this->insertPedidoAlocacao($item);
            
        } while ( round($this->quantidade_talao) > 0 );
    }

    
    private function processarTalaoDetalheSaldo() {
        // Calcula a que será aplicada ao talão com base na cota de talão do modelo
        switch(true) {

            case ($this->quantidade_talao <= $this->cota_talao_detalhe) : 
                $this->quantidade_talao_detalhe = $this->quantidade_talao;
                $this->quantidade_talao         = 0;
            break;

            case ($this->quantidade_talao > $this->cota_talao_detalhe) : 
                $this->quantidade_talao_detalhe = $this->cota_talao_detalhe;
                $this->quantidade_talao         = $this->quantidade_talao - $this->cota_talao_detalhe;
            break;
        }
    }    
    
    private function processarAgrupamentoSaldo($agrupamentos) {
                
        foreach ( $agrupamentos as $agrupamento ) {
            if ( $agrupamento->QUANTIDADE_TOTAL > 0 ) {
                
                // Calcula a quantidade restante de talões (parte fracionada)
                if ( $agrupamento->QUANTIDADE_TOTAL <= $this->quantidade_talao ) { 
                    $this->quantidade_saldo = $this->quantidade_saldo + ( $this->quantidade_talao - $agrupamento->QUANTIDADE_TOTAL );
                    $this->quantidade_talao = $agrupamento->QUANTIDADE_TOTAL;
                    $agrupamento->QUANTIDADE_TOTAL_SALDO = $agrupamento->QUANTIDADE_TOTAL;
                } else 
                if ( $agrupamento->QUANTIDADE_TOTAL > $this->quantidade_talao ) { 
                    $agrupamento->QUANTIDADE_TOTAL_SALDO = $this->quantidade_talao;
                }
                
                break;
            }
        }
    }
    
    private function processarAgrupamentoSaldoAtualizar($agrupamentos) {
                
        foreach ( $agrupamentos as $agrupamento ) {
            if ( $agrupamento->QUANTIDADE_TOTAL > 0 && isset($agrupamento->QUANTIDADE_TOTAL_SALDO) ) {
                $agrupamento->QUANTIDADE_TOTAL = $agrupamento->QUANTIDADE_TOTAL - $agrupamento->QUANTIDADE_TOTAL_SALDO;
                break;
            }
        }
    }
    
    private function processarProgramacoes($agrupamentos) {
                
        foreach ( $agrupamentos as $agrupamento ) {
            if ( $agrupamento->QUANTIDADE_TOTAL > 0 && isset($agrupamento->QUANTIDADE_TOTAL_SALDO) ) {
                $agrupamento->QUANTIDADE_TOTAL = $agrupamento->QUANTIDADE_TOTAL - $agrupamento->QUANTIDADE_TOTAL_SALDO;
                break;
            }
        }
    }
    
    /**
     * Insere a remessa e retorna o id
     * @param array $args Recebe os parametros de inserção da remessa
     * @return integer
     */
    private function insertRemessa ($args) {

        return _22100::insertRemessa([
            'ESTABELECIMENTO_ID'   => $args->estabelecimento_id,
            'FAMILIA_ID'           => $args->familia_id,
            'FAMILIA_ID_MP'        => $args->familia_id_mp,
            'DATA'                 => $args->data_remessa,
            'DATA_DISPONIBILIDADE' => $args->data_disponibilidade,
//            'DATA'                 => date('Y-m-d',strtotime($args->data_remessa)),
//            'DATA_DISPONIBILIDADE' => date('Y-m-d',strtotime($args->data_disponibilidade)),            
            'TIPO'                 => $args->tipo,
            'AMOSTRA'              => $args->amostra
        ],parent::getCon());    
    }

    /**
     * Insere a remessa e retorna o id
     * @param array $item Recebe os parametros de inserção da remessa
     * @return integer
     */
    private function insertRemessaTalao ($item) {

        $talao = (object)[
            'REMESSA_ID'         => $this->remessa_id,
            'REMESSA_TALAO_ID'   => $this->controle,
            'ESTABELECIMENTO_ID' => $item->ESTABELECIMENTO_ID,
            'FAMILIA_ID'         => parent::getRequest()->FILTRO->familia_id,
            'PRODUTO_ID'         => $item->PRODUTO_ID,
            'DESCRICAO'          => $item->MODELO_DESCRICAO . ' ' . $item->COR_DESCRICAO,
            'MODELO_ID'          => $item->MODELO_ID,
            'GRADE_ID'           => $item->GRADE_ID,
            'TAMANHO'            => $item->TAMANHO,
            'QUANTIDADE'         => $this->quantidade_talao,
            'MATRIZ_ID'          => $item->MATRIZ_ID,
            'GP_ID'              => $item->GP_ID,
            'ESTACAO'            => $item->ESTACAO,
            'PERFIL_SKU'         => $item->PERFIL_SKU,
            'STATUS'             => '1'
//                                'SEQUENCIA'         => $item->SEQUENCIA,
            ];
        

            
        array_push($this->taloes, $talao);
            
        return _22100::insertRemessaTalao($talao,parent::getCon());    
    }

    /**
     * Insere a remessa e retorna o id
     * @param array $item Recebe os parametros de inserção da remessa
     * @return integer
     */
    private function insertRemessaTalaoDetalhe ($item) {

        $talao = (object)[
            'REMESSA_ID'               => $this->remessa_id,
            'REMESSA_TALAO_ID'         => $this->controle,
            'ESTABELECIMENTO_ID'       => $item->ESTABELECIMENTO_ID,
            'FAMILIA_ID'               => parent::getRequest()->FILTRO->familia_id,
            'PRODUTO_ID'               => $item->PRODUTO_ID,
            'MODELO_ID'                => $item->MODELO_ID,
            'COR_ID'                   => $item->COR_ID,
//                'GRADE_ID'                 => $item->GRADE_ID,
            'TAMANHO'                  => $item->TAMANHO,
            'QUANTIDADE'               => $this->quantidade_talao_detalhe,
//                'MATRIZ_ID'                => $item->MATRIZ_ID,
            'GP_ID'                    => $item->GP_ID,
            'ESTACAO'                  => $item->ESTACAO,
            'PERFIL_SKU'               => $item->PERFIL_SKU,
            'LOCALIZACAO_ID'           => $item->LOCALIZACAO_ID,
//                                       'SEQUENCIA'         => $item->SEQUENCIA,
        ];

        array_push($this->taloes_detalhe, $talao);  
        
        return _22100::insertRemessaTalaoDetalhe($talao,parent::getCon());     
    }

    /**
     * Insere a remessa e retorna o id
     * @param array $item Recebe os parametros de inserção da remessa
     * @return integer
     */
    private function insertPedidoAlocacao ($item) {
        
        $alocacao = (object) [
            'ESTABELECIMENTO_ID'       => $item->ESTABELECIMENTO_ID,
            'GP_ID'                    => $item->GP_ID,
            'REMESSA_ID'               => $this->remessa_id,
            'REMESSA_TALAO_DETALHE_ID' => $this->remessa_talao_detalhe_id,
            'PRODUTO_ID'               => $item->PRODUTO_ID,
            'TAMANHO'                  => $item->TAMANHO,
            'QUANTIDADE'               => $this->quantidade_talao_detalhe,
            'LOCALIZACAO_ID'           => $item->LOCALIZACAO_ID,
            'AGRUPAMENTO_ID'           => 0,
            'AGRUPAMENTO_PEDIDO_ID'    => 0,
            'PEDIDO_ID'                => 0,
            'PEDIDO_ITEM_ID'           => 0,
            'TIPO'                     => 'M'
        ];
                
        foreach ( $item->AGRUPAMENTO as $agrupamento ) {
            if ( $agrupamento->QUANTIDADE_TOTAL > 0 ) {
                
                $alocacao->AGRUPAMENTO_ID        = $agrupamento->AGRUPAMENTO_ID;
                $alocacao->AGRUPAMENTO_PEDIDO_ID = $agrupamento->ID;
                $alocacao->PEDIDO_ID             = $agrupamento->TABELA_ID;
                $alocacao->PEDIDO_ITEM_ID        = $agrupamento->TAB_ITEM_ID;
                $alocacao->TIPO                  = $agrupamento->TIPO;
                
                break;
            }
        }
        
        array_push($this->alocacoes, $alocacao);
        
        return _22100::insertPedidoAlocacao($alocacao,parent::getCon());    
    }

    /**
     * Insere a remessa e retorna o id
     * @param array $item Recebe os parametros de inserção da remessa
     * @return integer
     */
    private function insertProgramacao ($item) {
        
        $tempo_operacial            = (int)($this->quantidade_talao * $this->tempo_multiplicador);
        $tempo_setup_cor            = (isset($item->HABILITA_COR_SETUP                   ) && $this->setup) ? ($item->COR_TEMPO_SETUP                   ) : 0;
        $tempo_setup_aprovacao      = (isset($item->HABILITA_COR_SETUP_APROVACAO         ) && $this->setup) ? ($item->COR_TEMPO_SETUP_APROVACAO         ) : 0;
        $tempo_setup_ferramenta     = (isset($item->HABILITA_FERRAMENTA_SETUP            ) && $this->setup) ? ($item->TEMPO_FERRAMENTA_SETUP            ) : 0;
        $tempo_setup_aquecimento    = (isset($item->HABILITA_FERRAMENTA_SETUP_AQUECIMENTO) && $this->setup) ? ($item->TEMPO_FERRAMENTA_SETUP_AQUECIMENTO) : 0;
        $tempo_total                = $tempo_operacial + $tempo_setup_cor + $tempo_setup_aprovacao + $tempo_setup_ferramenta + $tempo_setup_aquecimento;
        
//        $tempo_inicio = $this->minutagem;
//        
//        $this->minutagem += $tempo_total;
//        
//        $tempo_fim = $this->minutagem - 1;
        $tempo_inicio = 0;
        $tempo_fim    = 0;
        
        $programacao = (object) [
            'ESTABELECIMENTO_ID'      => $item->ESTABELECIMENTO_ID,
            'REMESSA_ID'              => $this->remessa_id,
            'DATA_PRODUCAO'           => parent::getRequest()->FILTRO->data_remessa,
//            'DATA_PRODUCAO'           => date('Y-m-d',strtotime(parent::getRequest()->FILTRO->data_remessa)),            
            'GP_ID'                   => $item->GP_ID,
            'UP_ID'                   => $item->UP_ID,
            'ESTACAO'                 => $item->ESTACAO,
            'TALAO_ID'                => $this->talao_id,
            'PRODUTO_ID'              => $item->PRODUTO_ID,
            'TAMANHO'                 => $item->TAMANHO,
            'FERRAMENTA_ID'           => $item->FERRAMENTA_ID,
            'QUANTIDADE'              => $this->quantidade_talao,
            'TEMPO_OPERACIONAL'       => $tempo_operacial,
            'TEMPO_SETUP_COR'         => $tempo_setup_cor,
            'TEMPO_SETUP_APROVACAO'   => $tempo_setup_aprovacao,
            'TEMPO_SETUP_FERRAMENTA'  => $tempo_setup_ferramenta,
            'TEMPO_SETUP_AQUECIMENTO' => $tempo_setup_aquecimento,
            'MINUTO_INICIO'           => $tempo_inicio,
            'MINUTO_FIM'              => $tempo_fim,
        ];
        
        array_push($this->programacoes, $programacao);
        
        return _22100::insertProgramacao($programacao,parent::getCon());    
    }
}