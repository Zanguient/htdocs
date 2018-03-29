<?php

namespace app\Http\Controllers\Ppcp\_22100;

//use App\Http\Controllers\Controller as Ctrl;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Admin\_11010;
use App\Models\DTO\Ppcp\_22100;
use App\Models\Conexao\_Conexao;
use App\Helpers\UserControl;

/**
 * Controller do objeto _22100 - Geracao de Remessas de Bojo
 */
class Controller extends UserControl {
	
	/**
     * Código do menu
     * @var int 
     */
    public $menu                = 'ppcp/_22100';
//    private $con                = null;
//    public  $request            = [];
    private $estacoes           = [];
    private $itens_linhas       = [];
    private $itens_programar    = [];
    private $produtos_consumo   = [];
    private $produtos_estoque   = [];
    private $ferramentas        = [];
    private $taloes_programados = [];
    private $AGRUPAMENTOS       = [];
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'ppcp._22100.index', [
            'permissaoMenu'           => $permissaoMenu,
            'menu'                    => $this->menu
		]);  
    }

    public function create()
    {
    	//
    }

    public function store(Request $request)
    {   
    }
    
    public function edit($id)
    {
    	//
    }
    
    public function update(Request $request, $id)
    {
    	//
    }
    
    public function destroy($id)
    {
    	//
    }
    
    public function getNecessidadeItens(Request $request)
    {
        /* Realiza a validação dos campos */
        validator($request->all(), [
            'estabelecimento_id' => ['Estabelecimento' ,'required'     ],
            'agrupamento_id'     => ['Agrupamento'     ,'required'     ],
            'familia_id'         => ['Família'         ,'required'     ],
            'tonalidade'         => ['Tonalidade'      ,'required|string|max:1'],
            'pedido_perfil'      => ['Perfil de Pedido','max:1'                ], 
        ],true);
                
        set_time_limit ( 0 );
        
        /* Setta os valores passados como parametro */
        $this->setRequest($request->all());
        
        /* Inicializa a conexão */
        $this->setCon();
        
        try {

            /* Agrupa os itens do agrupamento de pedidos */
            $this->setItensProgramar();

            foreach ( $this->AGRUPAMENTOS as $item ) {
                _22100::insertPedidoBloqueio($item,$this->getCon());
            }
            
            /* Agrupa os itens do agrupamento de pedidos por linha/tamanho */
            $this->setItensLinhas();

            /* Carrega todas os ESTAÇÕES da familia informada */
            $this->setEstacoes();

            /* Carrega todos os produtos de estoque dos itens à programar */
            $this->setProdutosEstoque();

            /* Carrega todas as Ferramentas dos itens à programar */
            $this->setFerramentas();

            /* Carrega o ultimo talão programado por estação */
            $this->setTaloesProgramados();

            
            $this->getCon()->commit();
                    
            /* Retorna o resultado no formato json */
            return Response::json([
                'agrupamentos'       => $this->AGRUPAMENTOS,
                'agrupamento_linhas' => $this->getItensLinhas(),
                'agrupamento_itens'  => $this->getItensProgramar(),
                'estacoes'           => $this->getEstacoes(),
                'produtos_estoque'   => $this->getProdutosEstoque(),
                'ferramentas'        => $this->getFerramentas(),
                'taloes_programados' => $this->getTaloesProgramados()
            ]); 
        } catch (Exception $e) {
            $this->getCon()->rollback();
			throw $e;
        }
    }
    
    public function setCon() {
        $this->con = new _Conexao;
    }
    
    public function setRequest($request) {
        $this->request = obj_case($request);
    }
    
    private function setEstacoes() {

        $this->estacoes = _22100::selectGpUpEstacao([
            'STATUS'    => [1],
            'UP_STATUS' => [1],
            'FAMILIA'   => [$this->getRequest()->FAMILIA_ID],
            'DATA'      =>  $this->getRequest()->DATA_REMESSA,
            'ORDER'     => 'FAMILIA_DESCRICAO, GP_DESCRICAO,UP_DESCRICAO,ESTACAO_DESCRICAO'
        ],$this->getCon());
    }
    
    private function setItensLinhas() {
        
        /* 
         * Carrega todos os sku do agrupamento de pedidos informado para traz
         */
        $itens_agrupamento = $this->getItensProgramar();
        
        orderBy($itens_agrupamento, 'LINHA_DESCRICAO', SORT_STRING, 'TAMANHO_DESCRICAO', SORT_STRING);
        
        $array = [];
        
        foreach( $itens_agrupamento as $key => $item ) {
            if ( !isset($itens_agrupamento[$key+1]) ||
                $itens_agrupamento[$key+1]->LINHA_ID != $item->LINHA_ID ||
                $itens_agrupamento[$key+1]->TAMANHO  != $item->TAMANHO
            ) {  
                array_push($array, $item);
            }
        }
                
        $this->itens_linhas = $array;
    }
    
    private function setItensProgramar() {
        
        /* 
         * Carrega todos os sku do agrupamento de pedidos informado para traz
         */
//        $this->AGRUPAMENTOS = _22100::selectAgrupamentoItens($this->getRequest(), $this->getCon());
//        $itens_agrupamento  = $this->AGRUPAMENTOS;
        
        $itens_agrupamento  = _22100::selectAgrupamentoItens($this->getRequest(), $this->getCon());
        
        $this->AGRUPAMENTOS = [];
        
        
        $array = [];
        
        $item_push    = [];
        $qtd          = 0;
        foreach( $itens_agrupamento as $key => $item ) {
            
            array_push($this->AGRUPAMENTOS, clone $item);
            
            $qtd += $item->QUANTIDADE_TOTAL;
            
            $clone = clone $item;
            array_push($item_push, $clone);
            
            if ( !isset($itens_agrupamento[$key+1]) || $itens_agrupamento[$key+1]->PRODUTO_ID != $item->PRODUTO_ID || $itens_agrupamento[$key+1]->TAMANHO != $item->TAMANHO ) {
                
                $item->QUANTIDADE_TOTAL      = $qtd;
                $item->QUANTIDADE_SALDO      = $qtd;
                $item->QUANTIDADE_PROGRAMADA = $qtd;
                $item->AGRUPAMENTO           = $item_push;
                
                orderBy($item->AGRUPAMENTO, 'DATA', 'TABELA_ID');
                
                array_push($array, $item);
                
                $item_push = [];
                $qtd       = 0;
            }
        }
        
        $this->itens_programar = $array;
        
        /**
         * Insere o consumo de alocação nos produtos dos agrupamentos
         * Campo "CONSUMO_ALOCACAO"
         */
        $this->setConsumo();
    }
    
    private function setConsumo() {
              
        $produtos_consumo = [];
        foreach($this->getItensProgramar() as $item) {
            
            $item->CORES_SIMILARES = _22100::selectCoresSimilares([
                'FAMILIA_ID' => $this->getRequest()->FAMILIA_ID,
                'COR_ID'     => $item->COR_ID
            ], $this->getCon());
            
            $consumos_mp_alocacao = _22100::selectConsumoMpAlocacao([
                'MODELO_ID' => $item->MODELO_ID,
                'COR_ID'    => $item->COR_ID,
                'TAMANHO'   => $item->TAMANHO
            ], $this->getCon());
            
            $item->CONSUMO_ALOCACAO = [];
            
            foreach($consumos_mp_alocacao as $consumo_mp_alocacao) {
                array_push($item->CONSUMO_ALOCACAO, $consumo_mp_alocacao);
                
                array_push($produtos_consumo, (object) [
                    'ESTABELECIMENTO_ID' => $item->ESTABELECIMENTO_ID,
                    'PRODUTO_ID'         => $consumo_mp_alocacao->PRODUTO_ID
                ]);
            }
        }
        
        $this->setProdutosConsumo($produtos_consumo);
    }
    
    private function setProdutosConsumo($produtos_consumo) {
        $this->produtos_consumo = array_map("unserialize", array_unique(array_map("serialize", $produtos_consumo)));
    }
    
    private function setProdutosEstoque() {
              
        $produtos_estoque = [];
        foreach($this->getProdutosConsumo() as $item) {
            
            $estoques = _22100::selectProdutoEstoque($item, $this->getCon());
            
            foreach ( $estoques as $estoque ) {
                array_push($produtos_estoque, $estoque);
            }
        }
        
        $this->produtos_estoque = $produtos_estoque;
    }
    
    private function setFerramentas() {
              
        $ferramentas = [];
        
        foreach($this->getItensProgramar() as $item) {
            
            $item = clone $item;
            
            $item->DATA_REMESSA = $this->getRequest()->DATA_REMESSA;
            
            $dados = _22100::selectFerramenta($item, $this->getCon());
                        
            foreach ( $dados as $ferramenta ) {
                if (!in_array($ferramenta, $ferramentas)) { 
                    array_push($ferramentas, $ferramenta);
                }
            }
        }
        
        foreach ( $ferramentas as $ferramenta ) {
            $ferramenta->ALOCACOES = 
            _22100::selectFerramentaAlocacoes([
                'FERRAMENTA_ID' => $ferramenta->ID,
                'DATA'          => $this->getRequest()->DATA_REMESSA
            ], $this->getCon());
        }
        
        $this->ferramentas = $ferramentas;
    }
    
    private function setTaloesProgramados() {
        $this->taloes_programados = _22100::selectUltimoTalaoEstacao([],$this->con);       
    }
    
    public function getCon() {
        return $this->con;
    }
    
    public function getRequest() {
        return $this->request;
    }
    
    private function getEstacoes() {
        return $this->estacoes;
    }
    
    private function getItensLinhas() {
        return $this->itens_linhas;
    }
    
    private function getItensProgramar() {
        return $this->itens_programar;
    }
    
    private function getProdutosConsumo() {
        return $this->produtos_consumo;
    }

    private function getProdutosEstoque() {
        return $this->produtos_estoque;
    }

    private function getFerramentas() {
        return $this->ferramentas;
    }

    private function getTaloesProgramados() {
        return $this->taloes_programados;
    }
}