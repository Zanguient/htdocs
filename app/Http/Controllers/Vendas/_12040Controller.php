<?php

namespace app\Http\Controllers\Vendas;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Models\DTO\Vendas\_12040;
use App\Models\DTO\Admin\_11010;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Conexao\_Conexao;
use PDF;

/**
 * Controller do objeto _12040 - Registro de Pedidos
 */
class _12040Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'vendas/_12040';

    /**
     * Conexão.
     * @var _Conexao
     */
    private $con = null;

    private $pu218 = '';

    public function getPDF(Request $request)
    {
        
        $INFO = $request->all();

        $file_name = 'PEDIDO-' . $INFO['PEDIDO']['PEDIDO']. '.pdf';
        $path_file = '/assets/temp/relatorios/';
        
        //caminho e nome do arquivo
        $arq_temp = public_path().$path_file.$file_name;

        //apaga arquivos de relatórios antigos
        deleleFilesTree(public_path().$path_file);
        
        //apagar arquivo, caso já exista
        if(file_exists($arq_temp) ) {
            unlink($arq_temp);
        }
        
        PDF::setPaper('A4','portrait')
            ->loadView('vendas._12040.pdf.2_body', ['info'   => $INFO])  
            ->setOption('header-html',view('vendas._12040.pdf.1_header', ['menu' => $this->menu,'info' => $INFO]),'html')     
            ->setOption('footer-html',view('vendas._12040.pdf.3_footer'),'html')   
            ->save($arq_temp)
        ;  
        
        if ( $request->isMethod('post') ) {
            return $path_file.$file_name;
        } else {
            return view('vendas._12040.pdf.2_body', ['info'   => $INFO]);
        }
    }

	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        $this->pu218 = _11010::controle(218);
        
		return view(
            'vendas._12040.index', [
            'permissaoMenu' => $permissaoMenu,
            'pu218'         => $this->pu218,
            'menu'          => $this->menu
		]);
    }

    public function create()
    {
    	_11010::permissaoMenu($this->menu, 'INCLUIR');
		
		return view(
            'vendas._12040.create', [
            'menu'          => $this->menu,
			'info_geral'	=> $info_geral[0]
		]);
    }

    public function viewPedidoIndex() {

        $permissaoMenu = _11010::permissaoMenu($this->menu);
        return view('vendas._12040.index.index', ['menu' => $this->menu, 'permissaoMenu' => $permissaoMenu]);
    }

    public function viewPedidoEdit() {
        return view('vendas._12040.edit.edit', ['menu' => $this->menu]);
    }

    public function viewPedidoCreate() {
        return view('vendas._12040.create.create', ['menu' => $this->menu]);
    }

    public function viewInfoGeral() {
        return view('vendas._12040.create.info-geral', ['menu' => $this->menu]);
    }

    public function viewPedidoItemEscolhido() {

        $pu218 = _11010::controle(218);

        return view('vendas._12040.create.pedido-item-escolhido', [
            'menu'  => $this->menu, 
            'pu218' => $pu218
        ]);
    }

    public function viewPedidoItem() {

        $pu218 = _11010::controle(218);

        return view('vendas._12040.create.modal-pedido-item', [
            'menu'  => $this->menu, 
            'pu218' => $pu218
        ]);
    }

    public function viewLiberacao() {
        return view('vendas._12040.index.liberacao', ['menu' => $this->menu]);
    }

    /**
     * Verificar se o usuário é um representante.
     * @return json
     */
    public function verificarUsuarioEhRepresentante() {

        $this->con = new _Conexao();

        try {

            $param = (object)[];
            $param->USUARIO_CODIGO = Auth::user()->CODIGO;

            $pedido = _12040::verificarUsuarioEhRepresentante($param, $this->con);

            $this->con->commit();

            return Response::json($pedido);

        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    /**
     * Consultar representante do cliente.
     * @return json
     */
    public function consultarRepresentanteDoCliente() {

        $this->con = new _Conexao();

        try {

            $param = (object)[];
            $param->CLIENTE_ID = Auth::user()->CLIENTE_ID;

            if ( !empty($param->CLIENTE_ID) )
                $repres = _12040::consultarRepresentanteDoCliente($param, $this->con);
            else 
                $repres = [];

            $this->con->commit();

            return Response::json($repres);

        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    /**
     * Verificar se o id do cliente está vinculado ao usuário ou se não foi passado nenhum id.
     *
     * @param int $clienteId
     * @return int
     */
    public function verificarClienteId($clienteId) {

        // se o usuário (CLIENTE) possuir cliente vinculado, utiliza este cliente.
        if ( !empty(Auth::user()->CLIENTE_ID) )
            $clienteId = Auth::user()->CLIENTE_ID;

        // Se o usuário (CLIENTE) não possui cliente vinculado.
        if ( $clienteId === null )
            log_erro('Você precisa ter o ID de Cliente vinculado a seu usuário.<br/>Entre em contato com o administrador do sistema.');

        // Se o usuário (REPRESENTANTE OU SETOR COMERCIAL) não escolheu um cliente.
        else if ( $clienteId == 0 ){
            //log_erro('Selecione um cliente.');
        }

        return $clienteId;
    }

    /**
     * Consultar pedidos.
     *
     * @param Request $request
     * @return json
     */
    public function consultarPedido(Request $request) {

        $this->con = new _Conexao();

        try {

            $filtro = json_decode(json_encode($request->filtro));
            
            $filtro->CLIENTE_ID = $this->verificarClienteId( $filtro->CLIENTE_ID );

            $pedido = _12040::consultarPedido($filtro, $this->con);

            $this->con->commit();

            return Response::json($pedido);

        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }

    }

    /**
     * Consultar pedidos.
     *
     * @param Request $request
     * @return json
     */
    public function consultarPedido2(Request $request) {

        $this->con = new _Conexao();

        try {

            $filtro = json_decode(json_encode($request->filtro));
            
            $filtro->CLIENTE_ID = $this->verificarClienteId( $filtro->CLIENTE_ID );

            $pedido = _12040::consultarPedido2($filtro, $this->con);

            $this->con->commit();

            return Response::json($pedido);

        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }

    }

    /**
     * Consultar pedidos através de parâmetros da url.
     *
     * @param Request $request
     * @return json
     */
    public function consultarPedidoPorUrl(Request $request) {

        $this->con = new _Conexao();

        try {

            $filtro = json_decode(json_encode($request->filtro));

            $pedido = _12040::consultarPedido($filtro, $this->con);

            $this->con->commit();

            return Response::json($pedido);

        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }

    }

    /**
     * Consultar itens de pedidos.
     *
     * @param Request $request
     * @return json
     */
    public function consultarPedidoItem(Request $request) {

        $this->con = new _Conexao();

        try {

            $filtro = json_decode(json_encode($request->filtro));

            $pedidoItem = _12040::consultarPedidoItem($filtro, $this->con);

            $this->con->commit();

            return Response::json($pedidoItem);

        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }

    }

    public function consultarInfoGeral(Request $request) {

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));

            $param->CLIENTE_ID = $this->verificarClienteId( $param->CLIENTE_ID );

            $info_geral = _12040::consultarInfoGeral($param, $this->con);

            $this->con->commit();

            return Response::json($info_geral[0]);

        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }

    }

    /**
     * Consultar grade.
     * @param Request $request
     * @return json
     */
    public function consultarTamanhoComPreco(Request $request) {

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));

            $param->ESTABELECIMENTO_ID = !empty($param->ESTABELECIMENTO_ID) ? $param->ESTABELECIMENTO_ID : 1;

            $param->CLIENTE_ID = $this->verificarClienteId( $param->CLIENTE_ID );

            $dados = _12040::consultarTamanhoComPreco($param, $this->con);

            $this->con->commit();

            return Response::json($dados);

        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }

    }

    /**
     * Consultar informações de perfil (quantidades e prazos) e 
     * quantidade mínima e múltipla do modelo de acordo com o tamanho.
     * @param Request $request
     * @return json
     */
    public function consultarQtdEPrazoPorTamanho(Request $request) {

        $this->con = new _Conexao();

        try {

            $filtro = [
                'COR_ID'                => $request->COR_ID,
                'DATA'                  => $request->DATA,
                'ESTABELECIMENTO_ID'    => !empty(Auth::user()->ESTABELECIMENTO_ID) ? Auth::user()->ESTABELECIMENTO_ID : 1,
                'PRODUTO_ID'            => $request->PRODUTO_ID,
                'TAMANHO_ID'            => $request->TAMANHO_ID,
                'MODELO_ID'             => $request->MODELO_ID,
                'FAMILIA_ID'            => $request->FAMILIA_ID,
                'CHAVE'                 => $request->CHAVE
            ];

            $dados = _12040::consultarQtdEPrazoPorTamanho($filtro, $this->con);

            $this->con->commit();

            return Response::json($dados[0]);

        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }

    }

    /**
     * Consultar a quantidade mínima liberada para uma cor.
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public function consultarQtdLiberada(Request $request) {

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));

            $dados = _12040::consultarQtdLiberada($param, $this->con);

            $this->con->commit();

            return Response::json($dados);

        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    /**
     * Verificar se o número de pedido do cliente já existe em outro pedido do mesmo cliente.
     * @param json $pedido
     */
    public function verificarNumPedidoCliente($pedido) {

        $dado = _12040::consultarNumPedidoCliente($pedido, $this->con);

        if (!empty($dado))
            log_erro('Seu nº do pedido (<b>'.$pedido->PEDIDO_CLIENTE.'</b>) já existe em outro pedido.');
    }

    public function store(Request $request) {

        $this->con = new _Conexao();

        try {

            $pedido = json_decode(json_encode($request->pedido));

            $pedido->CLIENTE_ID = $this->verificarClienteId( $pedido->CLIENTE_ID );

            if ($pedido->TIPO_TELA != 'alterar')
                $this->verificarNumPedidoCliente($pedido);

            $pedido->PEDIDO                 = empty($pedido->PEDIDO) ? _12040::gerarId($this->con) : $pedido->PEDIDO;
            $pedido->ESTABELECIMENTO_CODIGO = !empty(Auth::user()->ESTABELECIMENTO_ID) ? Auth::user()->ESTABELECIMENTO_ID : 1;
            $pedido->USUARIO_CODIGO         = Auth::user()->CODIGO;

            $this->gravarPedido($pedido);
            //_12040::alterarEmpresaEmailXml($pedido, $this->con);
            $this->gravarPedidoItem($pedido, $pedido->PEDIDO_ITEM);
            $this->excluirPedidoItem($pedido->PEDIDO_ITEM_EXCLUIR);

            $this->con->commit();

        }
        catch(Exception $e) {
            $this->con->rollback();
            throw $e;
        }

    }

    public function gravarPedido($pedido) {

        $pedido->PROGRAMADO     = (isset($pedido->PROGRAMADO) && $pedido->PROGRAMADO == '1') ? 1 : 0;
        $pedido->PEDIDO_CLIENTE = !empty($pedido->PEDIDO_CLIENTE) ? $pedido->PEDIDO_CLIENTE : null;
        $pedido->OBSERVACAO     = !empty($pedido->OBSERVACAO) ? $pedido->OBSERVACAO : null;
        $pedido->SITUACAO       = '0';  // Todos os pedidos devem ser não-confirmados. A confirmação será feita pelo comercial.

        _12040::gravarPedido($pedido, $this->con);

    }

    public function gravarPedidoItem($pedido, $pedidoItem) {
        
        foreach ($pedidoItem as $item) {
        
            $item->T01 = ($item->TAMANHO == '1')  ? $item->QUANTIDADE : 0;
            $item->T02 = ($item->TAMANHO == '2')  ? $item->QUANTIDADE : 0;
            $item->T03 = ($item->TAMANHO == '3')  ? $item->QUANTIDADE : 0;
            $item->T04 = ($item->TAMANHO == '4')  ? $item->QUANTIDADE : 0;
            $item->T05 = ($item->TAMANHO == '5')  ? $item->QUANTIDADE : 0;
            $item->T06 = ($item->TAMANHO == '6')  ? $item->QUANTIDADE : 0;
            $item->T07 = ($item->TAMANHO == '7')  ? $item->QUANTIDADE : 0;
            $item->T08 = ($item->TAMANHO == '8')  ? $item->QUANTIDADE : 0;
            $item->T09 = ($item->TAMANHO == '9')  ? $item->QUANTIDADE : 0;
            $item->T10 = ($item->TAMANHO == '10') ? $item->QUANTIDADE : 0;
            $item->T11 = ($item->TAMANHO == '11') ? $item->QUANTIDADE : 0;
            $item->T12 = ($item->TAMANHO == '12') ? $item->QUANTIDADE : 0;
            $item->T13 = ($item->TAMANHO == '13') ? $item->QUANTIDADE : 0;
            $item->T14 = ($item->TAMANHO == '14') ? $item->QUANTIDADE : 0;
            $item->T15 = ($item->TAMANHO == '15') ? $item->QUANTIDADE : 0;
            $item->T16 = ($item->TAMANHO == '16') ? $item->QUANTIDADE : 0;
            $item->T17 = ($item->TAMANHO == '17') ? $item->QUANTIDADE : 0;
            $item->T18 = ($item->TAMANHO == '18') ? $item->QUANTIDADE : 0;
            $item->T19 = ($item->TAMANHO == '19') ? $item->QUANTIDADE : 0;
            $item->T20 = ($item->TAMANHO == '20') ? $item->QUANTIDADE : 0;

            $item->CONTROLE = intval( $pedido->ESTABELECIMENTO_CODIGO . $pedido->PEDIDO . str_pad($item->SEQUENCIA, 3, '0', STR_PAD_LEFT) );

            _12040::gravarPedidoItem($pedido, $item, $this->con);

        }

    }

    public function excluirPedidoItem($pedidoItemExcluir) {

        foreach ($pedidoItemExcluir as $item) {

            _12040::excluirPedidoItem($item, $this->con);

        }

    }

    public function excluir(Request $request) {

        $this->con = new _Conexao();

        try {

            $pedido = json_decode(json_encode($request->pedido));

            _12040::excluirPedido($pedido, $this->con);

            $this->con->commit();

        }
        catch(Exception $e) {
            $this->con->rollback();
            throw $e;
        }

    }

    /**
     * Gerar chave para liberação de nova quantidade mínima para cor.
     * @return json
     */
    public function gerarChave() {

        $this->con = new _Conexao();

        try {

            $chave = _12040::gerarChave($this->con);

            $this->con->commit();

            return Response::json($chave[0]);

        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    /**
     * Gravar liberação de nova quantidade mínima para cor.
     * @param Request $request
     * @return json
     */
    public function gravarLiberacao(Request $request) {

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));
            $param->USUARIO_ID = Auth::user()->CODIGO;

            // Múltiplas cores para uma chave.
            foreach ($param->COR as $cor) {

                $param->COR_ID      = $cor->CODIGO;
                $param->QUANTIDADE  = $cor->QUANTIDADE;

                _12040::gravarLiberacao($param, $this->con);
            }

            $this->con->commit();

        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

}