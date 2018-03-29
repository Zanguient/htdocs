<?php

namespace app\Http\Controllers\Chamados;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Chamados\_26021;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _26021 - Pesquisa de satisfação do cliente
 */
class _26021Controller extends Controller {
	
	/**
     * Código do menu
     * @var int 
     */
    private $menu = 'chamados/_26021';

    /**
     * Conexão.
     * @var $con
     */
    private $con = null;

	
	public function index() {

    	$permissaoMenu = _11010::permissaoMenu($this->menu);

		return view(
            'chamados._26021.index', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu
		]);  
    }

    /**
     * Consultar pesquisas.
     * @access public
     * @param Request $request
     * @return json
     */
    public function consultarPesquisa(Request $request) {

        $this->con = new _Conexao();
        
        try {

            $param = json_decode(json_encode($request->all()));

            $param->STATUS   = $param->STATUS != ''     ? $param->STATUS    : null;
            $param->DATA_INI = !empty($param->DATA_INI) ? $param->DATA_INI  : null;
            $param->DATA_FIM = !empty($param->DATA_FIM) ? $param->DATA_FIM  : null;

            $pesquisa = _26021::consultarPesquisa($param, $this->con);

            $this->con->commit();
            
            return Response::json($pesquisa);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    /**
     * Consultar respostas de uma pesquisa.
     * @access public
     * @param Request $request
     * @return json
     */
    public function consultarResposta(Request $request) {

        $this->con = new _Conexao();
        
        try {

            $param = json_decode(json_encode($request->all()));

            $resposta = _26021::consultarResposta($param, $this->con);

            $this->con->commit();
            
            return Response::json($resposta);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    /**
     * Consultar modelo de pesquisas (26020).
     * @access public
     * @return json
     */
    public function consultarModeloPesquisa() {

        $this->con = new _Conexao();
        
        try {

            $pesquisa = _26021::consultarModeloPesquisa($this->con);

            $this->con->commit();
            
            return Response::json($pesquisa);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    /**
     * Consultar perguntas do modelo de pesquisas (26020).
     * @access public
     * @param Request $request
     * @return json
     */
    public function consultarModeloPesquisaPergunta(Request $request) {

        $this->con = new _Conexao();
        
        try {

            $param = json_decode(json_encode($request->all()));

            $ret = [
                'PERGUNTA'      => _26021::consultarModeloPesquisaPergunta($param, $this->con),
                'ALTERNATIVA'   => _26021::consultarModeloPesquisaPerguntaAlternativa($param, $this->con)
            ];

            $this->con->commit();
            
            return Response::json($ret);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    /**
     * Consultar clientes.
     * @access public
     * @return json
     */
    public function consultarCliente() {

        $this->con = new _Conexao();
        
        try {

            $cliente = _26021::consultarCliente($this->con);

            $this->con->commit();
            
            return Response::json($cliente);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    /**
     * Gravar respostas.
     * @access public
     * @param Request $request
     * @return json
     */
    public function gravar(Request $request) {

        _11010::permissaoMenu($this->menu,'INCLUIR','Gravando Resposta');

        $this->con = new _Conexao();
        
        try {

            $param = json_decode(json_encode($request->all()));

            $param->ID                  = empty($param->ID) 
                                            ? _26021::gerarIdPesquisa($this->con)
                                            : $param->ID;

            $param->USUARIO_ID          = \Auth::user()->CODIGO;

            $param->NOTA_DELFA          = !isset($param->NOTA_DELFA)        ? null : $param->NOTA_DELFA;
            $param->OBSERVACAO_DELFA    = empty($param->OBSERVACAO_DELFA)   ? null : $param->OBSERVACAO_DELFA;

            // Pesquisa.
            _26021::gravarPesquisa($param, $this->con);

            // Respostas.
            foreach ($param->PERGUNTA as $perg) {

                $perg->FORMULARIO_PESQ_CLIENTE_ID           = $param->ID;
                $perg->USUARIO_ID                           = $param->USUARIO_ID;
                $perg->CLIENTE_ID                           = $param->CLIENTE->ID;

                $perg->RESPOSTA->ID                         = empty($perg->RESPOSTA->ID) ? 0 : $perg->RESPOSTA->ID;

                $perg->RESPOSTA->ALTERNATIVA_ESCOLHIDA_ID   = empty($perg->RESPOSTA->ALTERNATIVA_ESCOLHIDA_ID) 
                                                                ? null : $perg->RESPOSTA->ALTERNATIVA_ESCOLHIDA_ID;

                $perg->RESPOSTA->DESCRICAO                  = empty($perg->RESPOSTA->DESCRICAO) 
                                                                ? null : $perg->RESPOSTA->DESCRICAO;

                $perg->RESPOSTA->SOLUCAO                    = empty($perg->RESPOSTA->SOLUCAO) 
                                                                ? null : $perg->RESPOSTA->SOLUCAO;

                _26021::gravarResposta($perg, $this->con);
            }

            $this->con->commit();
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }
    }

    /**
     * Excluir pesquisa.
     * @access public
     * @param Request $request
     * @return json
     */
    public function excluir(Request $request) {

        $this->con = new _Conexao();

        try {

            $param = json_decode(json_encode($request->all()));

            _26021::excluir($param, $this->con);

            $this->con->commit();
        } 
        catch (Exception $e) {
            $this->con->rollback();
            throw $e;            
        }
    }

}