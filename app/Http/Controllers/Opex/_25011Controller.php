<?php

namespace app\Http\Controllers\Opex;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Opex\_25011;
use App\Models\DTO\Admin\_11010;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _25011 - Formulários
 */
class _25011Controller extends Controller {
	
	/**
     * Código do menu.
     * @var int 
     */
    private $menu = 'opex/_25011';

    /**
     * Conexão.
     * @var $con
     */
    private $con = null;

	
	public function index()
    {
    	$permissaoMenu = _11010::permissaoMenu($this->menu);
        
		return view(
            'opex._25011.index', [
            'permissaoMenu' => $permissaoMenu,
            'menu'          => $this->menu
		]);  
    }

    /**
     * Listar formulários.
     */
    public function listar() {

        $this->con = new _Conexao();
        
        try {

            $formulario     = _25011::listarFormulario($this->con);
            $pergunta       = _25011::listarPergunta($this->con);
            $alternativa    = _25011::listarAlternativa($this->con);
            $resposta       = _25011::listarResposta($this->con);

            $this->con->commit();
            
            return [
                'FORMULARIO'    => $formulario,
                'PERGUNTA'      => $pergunta,
                'ALTERNATIVA'   => $alternativa,
                'RESPOSTA'      => $resposta
            ];
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }

    }

    /**
     * Autenticar colaborador.
     * @param Request $request
     */
    public function autenticarColaborador(Request $request) {

        if ( empty($request->CODIGO) ) 
            log_erro('Digite o código do seu crachá.');

        $this->con = new _Conexao();
        
        try {

            $autenticado = false;

            $cpf = strlen($request->CODIGO) > 11 ? '' : $request->CODIGO;
            $cracha = $request->CODIGO;

            $colaborador = _25011::autenticarColaborador($cpf, $cracha, $request->FORMULARIO_ID, $this->con);
 
            if ( !empty($colaborador) ) {

                if ( empty($colaborador[0]->RESPONDEU) || ($colaborador[0]->RESPONDEU == 0) )
                    $autenticado = true;
                else
                    log_erro('Falha ao autenticar. Colaborador já respondeu à este formulário.');

            }                
            else
                log_erro('Falha ao autenticar. Possíveis causas: <ul><li>1. Código inválido;</li><li>2. Pesquisa não está destinada ao colaborador.</li></ul>');

            $this->con->commit();
            
            return [
                'AUTENTICADO'           => $autenticado,
                'COLABORADOR_ID'        => ($autenticado == true) ? $colaborador[0]->COLABORADOR_ID         : '',
                'COLABORADOR_NOME'      => ($autenticado == true) ? $colaborador[0]->COLABORADOR_NOME       : '',
                'COLABORADOR_CCUSTO'    => ($autenticado == true) ? $colaborador[0]->COLABORADOR_CCUSTO    : ''
            ];
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }

    }

    public function gravarResposta(Request $request) {

        _11010::permissaoMenu($this->menu,'INCLUIR','Gravando Resposta');
        
        $this->con = new _Conexao();
        
        try {

            if ( empty($request->pergunta) ) {
                log_erro('Não há dados para serem gravados');
            }
            
            $dados = [];

            foreach ($request->pergunta as $pergunta) {
                
                $dados['FORMULARIO_ID']             = $pergunta['FORMULARIO_ID'];
                $dados['FORMULARIO_PERGUNTA_ID']    = $pergunta['ID'];
                $dados['ALTERNATIVA_ESCOLHIDA_ID']  = !empty($pergunta['RESPOSTA'][0]['ALTERNATIVA_ESCOLHIDA_ID']) ? $pergunta['RESPOSTA'][0]['ALTERNATIVA_ESCOLHIDA_ID'] : '';
                $dados['DESCRICAO']                 = !empty($pergunta['RESPOSTA'][0]['DESCRICAO']) ? $pergunta['RESPOSTA'][0]['DESCRICAO'] : '';
                $dados['USUARIO_ID']                = \Auth::user()->CODIGO;
                $dados['COLABORADOR_ID']            = !empty($request->autenticacao['COLABORADOR_ID']) ? $request->autenticacao['COLABORADOR_ID'] : '';
                $dados['COLABORADOR_CCUSTO']        = !empty($request->autenticacao['COLABORADOR_CCUSTO']) ? $request->autenticacao['COLABORADOR_CCUSTO'] : '';

                _25011::gravarResposta($dados, $this->con);

            }

            $this->con->commit();
            
        } catch (Exception $e) {
            $this->con->rollback();
            throw $e;
        }

    }

}