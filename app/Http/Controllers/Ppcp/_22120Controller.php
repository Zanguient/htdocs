<?php

namespace app\Http\Controllers\Ppcp;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Ppcp\_22120;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;
use App\Models\DTO\Helper\Historico;

/**
 * Controller do objeto _22120 - Estrutura Analítica de Remessas
 */
class _22120Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'ppcp/_22120';
    private $con  = null;
    private $not_return_empty = true;
	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'ppcp._22120.index', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu
		]);  
    }

    public function create()
    {
    	//
    }

    public function store(Request $request)
    {    	
        //
    }
    
    public function show($id)
    {
    	//
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
    
    public function find(Request $request)
    {
        $this->con = new _Conexao;
        
        try {
            $res = $this->getRemessasVinculo($request->all());
            $this->con->commit();
            return Response::json($res);
        }
        catch (Exception $e)
        {
			$this->con->rollback();
			throw $e;
		}
    }
    
    public function remessas(Request $request)
    {
        $this->con = new _Conexao;
        try {
            $res =$this->getRemessas($request->all());
            $this->con->commit();
            return Response::json($res);
        }
        catch (Exception $e)
        {
			$this->con->rollback();
			throw $e;
		}
    }
        
    public function remover(Request $request,$tipo)
    {
        _11010::permissaoMenu($this->menu,'EXCLUIR','Removendo ' . $tipo);
        
        set_time_limit(0);
        
        $this->con = new _Conexao;
        try {
            $res = (object) [];
            switch ($tipo) {
                case 'TALAO':
                    $res->success = $this->talaoDelete($request->dados);
                    break;
                case 'DETALHE':
                    $res->success = $this->talaoDetalheDelete($request->dados);
                    break;
                case 'CONSUMO':
                    $res->success = $this->talaoConsumoDelete($request->dados);
                    break;
                case 'REMESSA':
                    $res->success = $this->remessaDelete($request->dados);
                    break;
            }
            
            $this->talaoZeradoDelete();
            
            if ( $request->retorno ) {
                $res->dados = $this->getRemessasVinculo($request->param);
            }
            
//            $this->con->rollback();
            $this->con->commit();
            
            return Response::json($res);
        }
        catch (Exception $e)
        {
			$this->con->rollback();
			throw $e;
		}
    }
    
    public function reabrir(Request $request,$tipo)
    {
        _11010::permissaoMenu($this->menu,'ALTERAR','Reabrindo Talões');
        
        $this->con = new _Conexao;
        try {
            
            $res = (object) [];
            switch ($tipo) {
                case 'TALAO':
                    $res->success = $this->talaoReabrir($request->dados);
                    break;
                case 'DETALHE':
                    $res->success = $this->talaoReabrirDetalhe($request->dados);
                    break;
            }            
            
            if ( $request->retorno ) {
                $res->dados = $this->getRemessasVinculo($request->param);
            }
            
            $this->con->commit();
            
            return Response::json($res);
        }
        catch (Exception $e)
        {
            $this->con->rollback();
            throw $e;
        }
    }    
    
    public function reabrirDetalhe(Request $request,$tipo)
    {
        _11010::permissaoMenu($this->menu,'ALTERAR','Reabrindo Talões Detalhados');
        
        $this->con = new _Conexao;
        try {
            $res = (object) [];
            
            $res->success = $this->talaoReabrirDetalhe($request->dados);
            
            if ( $request->retorno ) {
                $res->dados = $this->getRemessasVinculo($request->param);
            }
            
            $this->con->commit();
            
            return Response::json($res);
        }
        catch (Exception $e)
        {
            $this->con->rollback();
            throw $e;
        }
    }    
    
    public function desmembrar(Request $request,$tipo)
    {
        _11010::permissaoMenu($this->menu,'ALTERAR','Desmembrando Talões');
        
        $this->con = new _Conexao;
        try {
            $res = (object) [];
            
            $res->success = $this->talaoDetalheDesmembrar($request->dados);
            
            if ( $request->retorno ) {
                $res->dados = $this->getRemessasVinculo($request->param);
            }
            
            $this->con->commit();
            
            return Response::json($res);
        }
        catch (Exception $e)
        {
            $this->con->rollback();
            throw $e;
        }
    }    

    public function encerrar(Request $request,$tipo)
    {
        _11010::permissaoMenu($this->menu,'ALTERAR','Encerrando Talões');
        
        $this->con = new _Conexao;
        try {
            $res = (object) [];
            
            $res->success = $this->talaoDetalheEncerrar($request->dados);
            
            if ( $request->retorno ) {
                $res->dados = $this->getRemessasVinculo($request->param);
            }
            
            $this->con->commit();
            
            return Response::json($res);
        }
        catch (Exception $e)
        {
			$this->con->rollback();
			throw $e;
		}
    }

    public function gerarConsumo(Request $request)
    {
        _11010::permissaoMenu($this->menu,'ALTERAR','Gerando Consumo');
        
        $this->con = new _Conexao;
        try {
            $res = (object) [];
            
            $res->success = $this->remessaConsumoGerar($request->dados);
            
            if ( $request->retorno ) {
                $res->dados = $this->getRemessasVinculo($request->param);
            }
            
//            $this->con->rollback();
            $this->con->commit();
            
            return Response::json($res);
        }
        catch (Exception $e)
        {
			$this->con->rollback();
			throw $e;
		}
    }
    
    public function getTaloesExtras(Request $request)
    {
        _11010::permissaoMenu($this->menu,'INCLUIR','Gerando Talões Extra de Sobra');
        
        $this->con = new _Conexao;
        try {
            set_time_limit(0);
            
            /* Realiza a validação dos campos */
            validator($request->all(), [
                'remessa_id' => ['Código da Remessa','required']
            ],true);       
            
            
            $skus            = _22120::selectSkus($request->all(),$this->con);
            $taloes_extra    = _22120::selectTaloesExtra($request->all(),$this->con);
            
            foreach ( $skus as $sku ) {
                
                $sku->DEFEITO_ORIGEM = _22120::selectDefeitoOrigem($sku,$this->con);                
            }            
                        
            $this->con->commit();
            
            $res = [
                'SKUS'            => $skus,
                'TALOES_EXTRA'    => $taloes_extra
            ];
            
            return Response::json($res);
        }
        catch (Exception $e)
        {
			$this->con->rollback();
			throw $e;
		}
    }
    
    public function postTaloesExtras(Request $request)
    {
        _11010::permissaoMenu($this->menu,'INCLUIR','Gerando Talões Extra de Sobra');
        
        $this->con = new _Conexao;
        try {
            $request = json_decode(json_encode((object) $request->all()), false);
            $dados = $request->dados;
            
            if ( !isset($dados[0]) ) {
                log_erro('Não há registros para gravar!');
            }
            
            _22120::deleteRemessaTalaoExtra($dados[0],$this->con);            
            
            $talao = 8001;
            foreach ($dados as $item) {
                
                if ( $item->QUANTIDADE > 0 ) {
                    $item->REMESSA_TALAO_ID = $talao++;
                    _22120::insertRemessaTalao($item, $this->con);
                }                
            }
            
            $res = (object) [];
            
            $res->success = 'Talões Extras Gerados.';
            Historico::setHistorico('TBREMESSA', $dados[0]->REMESSA_ID, $res->success,$this->con);
            
            if ( $request->retorno ) {
                $res->dados = $this->getRemessasVinculo((array) $request->param);
            }
            
            $this->con->commit();
            
            return Response::json($res);
        }
        catch (Exception $e)
        {
			$this->con->rollback();
			throw $e;
		}
    }
    
    public function postAproveitamentoSobra(Request $request)
    {
        _11010::permissaoMenu($this->menu,'INCLUIR','Processando Aproveitamento de Sobras');
        
        $this->con = new _Conexao;
        try {
            $request = json_decode(json_encode((object) $request->all()), false);
            $dados = $request->dados;
            
            /* Realiza a validação dos campos */
            validator($dados, [
                'remessa_id' => ['Código da Remessa','required']
            ],true);      
            
            _22120::spiRemessaSobra($dados,$this->con);            
            
            $res = (object) [];
            
            $res->success_msg = 'Aproveitamento de Sobras Processada.';
            
            if ( $request->retorno ) {
                $res->dados = $this->getRemessasVinculo((array) $request->param);
            }
            
            $this->con->commit();
            
            return Response::json($res);
        }
        catch (Exception $e)
        {
			$this->con->rollback();
			throw $e;
		}
    }

    /**
     * Consulta dados de remessas vinculadas
     * @param Array $param
     * @return json
     */
    private function getRemessas(Array $param)
    {
        
        $con = &$this->con;
        
        if ( !$con ) {
            log_erro('Conexão não estabelecida.');
        }
        
        $param = (object)$param;
        
        if ( isset($param->remessa) && strlen($param->remessa) > 0 ) {
            $param = (object) ['REMESSA'=>$param->remessa];
        } else {

            /* Realiza a validação dos campos */
            validator($param, [
                'data_1' => ['Data Inicial','required'],
                'data_2' => ['Data Final','required'],
            ],true);
        }
        
        $param->FIRST = 30;
        
        $remessas = _22120::selectRemessas($param,$con);
        
        if ( !isset($remessas[0]) && $this->not_return_empty ) {
            log_erro('Não houveram resultados para sua consulta.');
        }
       
        return $remessas;
    }
    
    /**
     * Consulta dados de remessas vinculadas
     * @param Array $param
     * @return json
     */
    public function getRemessasVinculo(Array $param,$p_con = null)
    {
        /* Realiza a validação dos campos */
        validator($param, [
            'remessa' => ['Remessa','required|string|max:10'],
        ],true);
        
        $ref_con = &$this->con;
        
        $con = isset($p_con) ? $p_con : $ref_con;
        
        if ( !$con ) {
            log_erro('Conexão não estabelecida.');
        }
        
        $remessas_talao = _22120::selectRemessasTalaoVinculo($param,$con);
        
        if ( !isset($remessas_talao[0]) && $this->not_return_empty ) {
            log_erro('Não houveram resultados para sua consulta.');
        }
        
        $remessas  = [];
        $item_push = [];
        $vinculo   = false;
        foreach ( $remessas_talao as $key => $item ) {
            
            $clone = clone $item;
            array_push($item_push, $clone);
            
            if ( $item->VINCULOS ) $vinculo = true;
            
            if ( !isset($remessas_talao[$key+1]) || $remessas_talao[$key+1]->REMESSA_ID != $item->REMESSA_ID) {
                
                $taloes_detalhe   = _22120::selectRemessaTalaoDetalhe($item,$con);
                $consumos         = _22120::selectRemessaConsumo($item,$con);
                $familias         = _22120::selectRemessaConsumoFamilia($item,$con);
                $consumo_alocacos = _22120::selectRemessaConsumoAlocacoes($item,$con);
                        
                $remessa_item = [
                    'REMESSA'              => $item->REMESSA,
                    'REMESSA_ID'           => $item->REMESSA_ID,
                    'REMESSA_DATA'         => $item->REMESSA_DATA,
                    'REMESSA_DATA_TEXT'    => $item->REMESSA_DATA_TEXT,
                    'REMESSA_WEB'          => $item->REMESSA_WEB,
                    'FAMILIA_ID'           => $item->FAMILIA_ID,
                    'FAMILIA_DESCRICAO'    => $item->FAMILIA_DESCRICAO,
                    'REMESSA_GP_ID'        => $item->REMESSA_GP_ID,
                    'REMESSA_GP_DESCRICAO' => $item->REMESSA_GP_DESCRICAO,
                    'UM'                   => $item->UM,
                    'UM_ALTERNATIVA'       => $item->UM_ALTERNATIVA,
                    'VINCULO'              => $vinculo,
                    'TALOES'               => $item_push,
                    'TALOES_DETALHE'       => $taloes_detalhe,
                    'CONSUMOS'             => $consumos,
                    'CONSUMO_FAMILIAS'     => $familias,
                    'CONSUMO_ALOCACOES'    => $consumo_alocacos
                ];
                
                array_push($remessas, (object) $remessa_item);
                $item_push = [];
                $vinculo   = false;
            }
        }
        
        return $remessas;
    }

    /**
     * Exclui taloes ZERADOS
     * @param array $param
     * @return void
     */
    private function talaoZeradoDelete()
    {
        $con = &$this->con;
        
        if ( !$con ) {
            log_erro('Conexão não estabelecida.');
        }
        
        _22120::deleteTalaoZerado([],$con);
        _22120::deleteRemessaVazia([],$con);
    }

    /**
     * Reabre talões finalizados
     * @param array $param
     * @return void
     */
    private function talaoReabrir(Array $param)
    {
        if ( empty($param) ) {
            log_erro ('Não há dados para serem processados!');
        }
        
        $con = &$this->con;
        
        if ( !$con ) {
            log_erro('Conexão não estabelecida.');
        }
        
        foreach ($param as $item) {
            
            /* Realiza a validação dos campos */
            validator($item, [
                'ID' => ['Talão','required|integer|min:1'],
            ],true);
            
            _22120::spuTalaoReabrir($item,$con);
        }
        
        return 'Talões reabertos com sucesso!';
    }
    

    /**
     * Exclui taloes detalhados
     * @param array $param
     * @return void
     */
    private function talaoReabrirDetalhe(Array $param)
    {     
        if ( empty($param) ) {
            log_erro ('Não há dados para serem processados!');
        }
        
        $con = &$this->con;
        
        if ( !$con ) {
            log_erro('Conexão não estabelecida.');
        }
        
        foreach ($param as $item) {
            
            /* Realiza a validação dos campos */
            validator($item, [
                'ID' => ['Talão Detalhado','required|integer|min:1'],
            ],true);
            
            _22120::updateRemessaTalaoDetalheReabrir($item,$con);
        }
        
        return 'Talões detalhados reabertos com sucesso!';
    }    

    /**
     * Exclui remessa
     * @param array $param
     * @return void
     */
    private function remessaDelete(Array $param)
    {
        if ( empty($param) ) {
            log_erro ('Não há dados para serem processados!');
        }
        
        $con = &$this->con;
        
        if ( !$con ) {
            log_erro('Conexão não estabelecida.');
        }
        
        foreach ($param as $item) {
            
            /* Realiza a validação dos campos */
            validator($item, [
                'REMESSA_ID' => ['Remessa','required|integer|min:1'],
            ],true);
            
            _22120::deleteRemessa($item,$con);
        }
        
        $this->not_return_empty = false;
        
        return 'Remessa excluída com sucesso!';
    }

    /**
     * Exclui taloes acumulados
     * @param array $param
     * @return void
     */
    private function talaoDelete(Array $param)
    {
        if ( empty($param) ) {
            log_erro ('Não há dados para serem processados!');
        }
        
        $con = &$this->con;
        
        if ( !$con ) {
            log_erro('Conexão não estabelecida.');
        }
        
        foreach ($param as $item) {
            
            /* Realiza a validação dos campos */
            validator($item, [
                'ID' => ['Talão','required|integer|min:1'],
            ],true);
            
            _22120::deleteTalao($item,$con);
        }
        
        return 'Talões excluídos com sucesso!';
    }

    /**
     * Exclui taloes detalhados
     * @param array $param
     * @return void
     */
    private function talaoDetalheDelete(Array $param)
    {     
        if ( empty($param) ) {
            log_erro ('Não há dados para serem processados!');
        }
        
        $con = &$this->con;
        
        if ( !$con ) {
            log_erro('Conexão não estabelecida.');
        }
        
        foreach ($param as $item) {
            
            /* Realiza a validação dos campos */
            validator($item, [
                'ID' => ['Talão Detalhado','required|integer|min:1'],
            ],true);
            
            _22120::deleteTalaoDetalhe($item,$con);
        }
        
        return 'Talões detalhados excluídos com sucesso!';
    }

    /**
     * Desmembra taloes detalhados
     * @param array $param
     * @return void
     */
    private function talaoDetalheDesmembrar(Array $param)
    {     
        if ( empty($param) ) {
            log_erro ('Não há dados para serem processados!');
        }
        
        $con = &$this->con;
        
        if ( !$con ) {
            log_erro('Conexão não estabelecida.');
        }
        
        /* Realiza a validação dos campos */
        validator($param[0], [
            'REMESSA_ID' => ['Código da Remessa','required|integer|min:1']
        ],true);
        
        $remessa_talao_id_max = _22120::selectRemessaTalaoMaxId($param[0],$con);
        
        $remessa_talao_id = isset( $remessa_talao_id_max[0]) ? $remessa_talao_id_max[0]->MAX_ID + 1 : 1;
                
        foreach ($param as $item) {
            
            /* Realiza a validação dos campos */
            validator($item, [
                'ID' => ['Talão Detalhado','required|integer|min:1'],
            ],true);
            
            $item['REMESSA_TALAO_ID'] = $remessa_talao_id;
            
            _22120::updateRemessaTalaoDetalhe($item,$con);
        }
        
        _22120::spuDesmembrarEtapa2([
            'REMESSA_ID'        => $param[0]['REMESSA_ID'],
            'REMESSA_TALAO_ID'  => $remessa_talao_id
        ],$con);
        
        return 'Detalhamento de talões desmembrados realizado com sucesso! Novo talão: ' . lpad($remessa_talao_id, 4, '0');
    }

    /**
     * Encerrar Talão Detalhado
     * @param array $param
     * @return void
     */
    private function talaoDetalheEncerrar(Array $param)
    {     
        if ( empty($param) ) {
            log_erro ('Não há dados para serem processados!');
        }
        
        $con = &$this->con;
        
        if ( !$con ) {
            log_erro('Conexão não estabelecida.');
        }

        foreach ($param as $item) {
            
            /* Realiza a validação dos campos */
            validator($item, [
                'ID' => ['Talão Detalhado','required|integer|min:1'],
            ],true);
            
            _22120::updateRemessaTalaoDetalheEncerrar($item,$con);
        }
        
        return 'Detalhamento de talões encerrados sucesso!';
    }

    /**
     * Encerrar Talão Detalhado
     * @param array $param
     * @return void
     */
    private function remessaConsumoGerar(Array $param)
    {     
        if ( empty($param) ) {
            log_erro ('Não há dados para serem processados!');
        }
        
        $con = &$this->con;
        
        if ( !$con ) {
            log_erro('Conexão não estabelecida.');
        }

        /* Realiza a validação dos campos */
        validator($param, [
            'REMESSA_ID'    => ['Remessa'           ,'required'],
            'MP_FAMILIA_ID' => ['Família de Consumo','required'],
        ],true);

        _22120::spiRemessaConsumo($param,$con);

        return 'Consumo gerado com sucesso!';
    }

    /**
     * Exclui taloes consumos
     * @param array $param
     * @return void
     */
    private function talaoConsumoDelete(Array $param)
    {  
        if ( empty($param) ) {
            log_erro ('Não há dados para serem processados!');
        }
        
        $con = &$this->con;
        
        if ( !$con ) {
            log_erro('Conexão não estabelecida.');
        }
              
        foreach ($param as $item) {
            
            /* Realiza a validação dos campos */
            validator($item, [
                'ID' => ['Consumo','required|integer|min:1'],
            ],true);
            
            _22120::deleteTalaoConsumo($item,$con);
        }
        
        return 'Consumos de talões excluídos com sucesso!';
    }
}