<?php

namespace app\Http\Controllers\Opex;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Opex\_25010;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _25010 - Cadastro de Formulários
 */
class _25010Controller extends Controller {
	
	/**
     * Código do menu
     * @var $menu 
     */
    private $menu = 'opex/_25010';
	
	/**
     * Conexão.
     * @var $con
     */
    private $con = null;
    

    public function index()
    {
        $permissaoMenu = _11010::permissaoMenu($this->menu);

        return view(
            'opex._25010.index', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu
        ]);  
    }

    /**
     * Listar informações dos formulários.
     * @param Request $request
     */
    public function listar(Request $request) {

        $this->con = new _Conexao();
        
        try {

            $param          = json_decode(json_encode($request->all()));

            $pu217          = _11010::controle(217);
            $usuario_id     = \Auth::user()->CODIGO;

            $permissao      = _11010::permissaoMenu($this->menu);

            $param->STATUS   = $param->STATUS != ''     ? $param->STATUS : null;
            $param->TIPO     = !empty($param->TIPO)     ? $param->TIPO     : null;
            $param->DATA_INI = !empty($param->DATA_INI) ? $param->DATA_INI : null;
            $param->DATA_FIM = !empty($param->DATA_FIM) ? $param->DATA_FIM : null;

            $formulario     = _25010::listarFormulario($param, $pu217, $usuario_id, $this->con);
            $destinatario   = _25010::listarDestinatario($this->con);
            $pergunta       = _25010::listarPergunta($this->con);
            $alternativa    = _25010::listarAlternativa($this->con);

            $this->con->commit();
            
            return [
                'PERMISSAO'     => $permissao,
                'FORMULARIO'    => $formulario,
                'DESTINATARIO'  => $destinatario,
                'PERGUNTA'      => $pergunta,
                'ALTERNATIVA'   => $alternativa
            ];
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }

    }

    /**
     * Listar informações do formulário para o painel.
     * @param Request $request
     * @return json
     */
    public function listarPainel(Request $request) {

        $this->con = new _Conexao();
        
        try {

            $painel = _25010::listarPainel($request->formulario_id, $this->con);

            $this->con->commit();
            
            return Response::json($painel);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }

    }

    /**
     * Listar informações do formulário para o painel de pesquisa de cliente.
     * @param Request $request
     * @return json
     */
    public function listarPainelCliente(Request $request) {

        $this->con = new _Conexao();
        
        try {

            $param = json_decode(json_encode($request->all()));

            $painel = _25010::listarPainelCliente($param, $this->con);

            $this->con->commit();
            
            return Response::json($painel);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }

    }

    /**
     * Consultar UF's.
     * @return json
     */
    public function consultarUF() {

        $this->con = new _Conexao();
        
        try {

            $uf = _25010::consultarUF($this->con);

            $this->con->commit();
            
            return Response::json($uf);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }

    }

    /**
     * Listar informações do formulário para o painel.
     * @param Request $request
     * @return json
     */
    public function csv(Request $request) {

        $this->con = new _Conexao();
        
        try {

            $param = json_decode(json_encode($request->all()));

            $painel = _25010::csv($param, $this->con);

            $this->con->commit();
            
            return Response::json($painel);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }

    }

    /**
     * Listar respostas por usuário do formulário para o painel.
     * @param Request $request
     * @return json
     */
    public function painelResposta(Request $request) {

        $this->con = new _Conexao();
        
        try {

            $resposta = _25010::painelResposta($request->formulario_id, $request->destinatario_id, $this->con);

            $this->con->commit();
            
            return Response::json($resposta);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }

    }

    /**
     * Listar tipos de formulário.
     */
    public function listarTipoFormulario() {

        $this->con = new _Conexao();
        
        try {

            $tipo = _25010::listarTipoFormulario($this->con);

            $this->con->commit();
            
            return Response::json($tipo);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }

    }

    /**
     * Listar tipos de resposta.
     */
    public function listarTipoResposta() {

        $this->con = new _Conexao();
        
        try {

            $tipo = _25010::listarTipoResposta($this->con);

            $this->con->commit();
            
            return Response::json($tipo);
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }

    }

    /**
     * Listar níveis de satisfação das alternativas.
     */
    public function listarNivelSatisfacao() {

        $this->con = new _Conexao();

        try {

            $nivel = _25010::listarNivelSatisfacao($this->con);

            $this->con->commit();

            return Response::json($nivel);

        } 
        catch (Exception $e) {
            $this->con->rollback();
            throw $e;            
        }
    }

    /**
     * Gravar formulário.
     * @param Request $request
     */
    public function gravar(Request $request) {

        $this->con = new _Conexao();

        try {            
            
            $formulario_id = _25010::gerarId($this->con);            

            $this->gravarFormulario($request->formulario, $formulario_id);
            $this->gravarDestinatario($request->destinatario, $request->formulario['DESTINATARIO_TIPO'], $formulario_id);
            $this->gravarPergunta($request->pergunta, $formulario_id, $request->formulario['TIPO']);

            $this->con->commit();
        } 
        catch (Exception $e) {
            $this->con->rollback();
            throw $e;            
        }

    }

    public function gravarFormulario($dados, $formulario_id) {

        if ($dados['TIPO'] == 3) {
            $dados['PERIODO_INI'] = null;
            $dados['PERIODO_FIM'] = null;
        }
        else {
            $dados['PERIODO_INI'] = date_format(date_create($dados['PERIODO_INI']), 'Y-m-d');
            $dados['PERIODO_FIM'] = date_format(date_create($dados['PERIODO_FIM']), 'Y-m-d');
        }

        _25010::gravarFormulario($dados, $formulario_id, $this->con);

    }

    public function gravarDestinatario($dados, $destinatario_tipo, $formulario_id) {

        if ($destinatario_tipo == 'usuario') {
            
            foreach ($dados as $usuario) {
                
                $dados_destinatario = [
                    'USUARIO_ID'            => isset($usuario['ID']) ? $usuario['ID'] : null, 
                    'PESO'                  => isset($usuario['PESO']) ? $usuario['PESO'] : null,
                    'VISUALIZA_CADASTRO'    => isset($usuario['VISUALIZA_CADASTRO']) ? $usuario['VISUALIZA_CADASTRO'] : '0'
                ];

                _25010::gravarDestinatario($dados_destinatario, $formulario_id, $this->con);

            }

        }
        else if ($destinatario_tipo == 'ccusto') {

            foreach ($dados as $ccusto) {
                
                $dados_destinatario = [
                    'CCUSTO'                => isset($ccusto['ID']) ? $ccusto['ID'] : null, 
                    'PESO'                  => isset($ccusto['PESO']) ? $ccusto['PESO'] : null,
                    'VISUALIZA_CADASTRO'    => isset($ccusto['VISUALIZA_CADASTRO']) ? $ccusto['VISUALIZA_CADASTRO'] : '0'
                ];

                _25010::gravarDestinatario($dados_destinatario, $formulario_id, $this->con);
            }

        }

    }

    public function gravarPergunta($dados, $formulario_id, $formulario_tipo) {

        foreach ($dados as $pergunta) {
            
            $pergunta_id = _25010::gerarIdPergunta($this->con);
            _25010::gravarPergunta($pergunta, $formulario_id, $pergunta_id, $this->con);

            $this->gravarAlternativa($pergunta, $formulario_id, $formulario_tipo, $pergunta_id);

        }

    }

    public function gravarAlternativa($dados, $formulario_id, $formulario_tipo, $pergunta_id) {

        if ( ($dados['TIPO_RESPOSTA'] == 1) || ($dados['TIPO_RESPOSTA'] == 2) ) {

            foreach ($dados['ALTERNATIVA'] as $alternativa) {

                _25010::gravarAlternativa($alternativa, $formulario_id, $pergunta_id, $this->con);
            }
        }

    }

    /**
     * Alterar formulário.
     * @param Request $request
     */
    public function alterar(Request $request) {

        $this->con = new _Conexao();

        try {                        

            $this->alterarFormulario($request->formulario);
            $this->alterarDestinatario($request->destinatario, $request->formulario['ID'], $request->formulario['DESTINATARIO_TIPO']);
            $this->excluirDestinatario($request->destinatario_excluir);
            $this->alterarPergunta($request->pergunta, $request->formulario['ID']);
            $this->excluirPergunta($request->pergunta_excluir);

            $this->con->commit();
        } 
        catch (Exception $e) {
            $this->con->rollback();
            throw $e;            
        }

    }

    public function alterarFormulario($dados) {

        if ($dados['TIPO'] == 3) {
            $dados['PERIODO_INI'] = null;
            $dados['PERIODO_FIM'] = null;
        }
        else {
            $dados['PERIODO_INI'] = date_format(date_create($dados['PERIODO_INI']), 'Y-m-d');
            $dados['PERIODO_FIM'] = date_format(date_create($dados['PERIODO_FIM']), 'Y-m-d');
        }

        _25010::alterarFormulario($dados, $this->con);

    }

    public function alterarDestinatario($dados, $formulario_id, $destinatario_tipo) {

        if ($destinatario_tipo == 'usuario') {
            
            foreach ($dados as $usuario) {
                
                $dados_destinatario = [
                    'USUARIO_ID'            => $usuario['ID'], 
                    'PESO'                  => $usuario['PESO'],
                    'DESTINATARIO_ID'       => isset($usuario['DESTINATARIO_ID']) ? $usuario['DESTINATARIO_ID'] : null,
                    'FORMULARIO_ID'         => $formulario_id,
                    'STATUS_RESPOSTA'       => isset($usuario['STATUS_RESPOSTA']) ? $usuario['STATUS_RESPOSTA'] : '0',
                    'VISUALIZA_CADASTRO'    => isset($usuario['VISUALIZA_CADASTRO']) ? $usuario['VISUALIZA_CADASTRO'] : '0'
                ];

                _25010::alterarDestinatario($dados_destinatario, $this->con);
            }

        }
        else if ($destinatario_tipo == 'ccusto') {

            foreach ($dados as $ccusto) {
                
                $dados_destinatario = [
                    'CCUSTO'                => isset($ccusto['ID']) ? $ccusto['ID'] : $ccusto['CCUSTO'], 
                    'PESO'                  => $ccusto['PESO'],
                    'DESTINATARIO_ID'       => isset($ccusto['DESTINATARIO_ID']) ? $ccusto['DESTINATARIO_ID'] : null,
                    'FORMULARIO_ID'         => $formulario_id,
                    'STATUS_RESPOSTA'       => isset($usuario['STATUS_RESPOSTA']) ? $usuario['STATUS_RESPOSTA'] : '0',
                    'VISUALIZA_CADASTRO'    => isset($usuario['VISUALIZA_CADASTRO']) ? $usuario['VISUALIZA_CADASTRO'] : '0'
                ];

                _25010::alterarDestinatario($dados_destinatario, $this->con);
            }

        }

    }

    public function excluirDestinatario($dados) {

        foreach ($dados as $d) {
            
            if( isset($d['DESTINATARIO_ID']) ) {

                $dados_destinatario = [
                    'DESTINATARIO_ID'   => $d['DESTINATARIO_ID']
                ];

                _25010::excluirDestinatario($dados_destinatario, $this->con);

            }
        }

    }

    public function alterarPergunta($dados, $formulario_id) {

        foreach ($dados as $pergunta) {
            
            $pergunta_id = ($pergunta['ID'] > 0) ? $pergunta['ID'] : _25010::gerarIdPergunta($this->con);

            _25010::alterarPergunta($pergunta, $pergunta_id, $formulario_id, $this->con);

            $this->alterarAlternativa($pergunta, $formulario_id, $pergunta_id);
            $this->excluirAlternativa($pergunta['ALTERNATIVA_EXCLUIR']);

        }

    }

    public function alterarAlternativa($dados, $formulario_id, $pergunta_id) {

        if ( ($dados['TIPO_RESPOSTA'] == 1) || ($dados['TIPO_RESPOSTA'] == 2) ) {

            foreach ($dados['ALTERNATIVA'] as $alternativa) {

                _25010::alterarAlternativa($alternativa, $formulario_id, $pergunta_id, $this->con);
            }
        }

    }

    public function excluirAlternativa($dados) {

        foreach ($dados as $d) {
            
            if( isset($d['ID']) ) {

                $dados_alternativa = [
                    'ALTERNATIVA_ID' => $d['ID']
                ];

                _25010::excluirAlternativa($dados_alternativa, $this->con);
                
            }
        }

    }

    public function excluirPergunta($dados) {

        foreach ($dados as $d) {
            
            $dados_pergunta = [
                'PERGUNTA_ID'   => $d['ID']
            ];

            _25010::excluirPergunta($dados_pergunta, $this->con);
        }

    }

    /**
     * Excluir formulários.
     */
    public function excluirFormulario(Request $request) {

        $this->con = new _Conexao();

        try {

            _25010::excluirFormulario($request->id, $this->con);

            $this->con->commit();
        } 
        catch (Exception $e) {
            $this->con->rollback();
            throw $e;            
        }

    }


}