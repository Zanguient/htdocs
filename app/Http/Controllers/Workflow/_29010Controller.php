<?php

namespace app\Http\Controllers\Workflow;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Workflow\_29010;
use App\Models\DTO\Admin\_11010;
use App\Http\Controllers\Helper\ArquivoController;
use App\Models\Conexao\_Conexao;

/**
 * Controller do objeto _29010 - Cadastro de Workflow
 */
class _29010Controller extends Controller {
	
	/**
	 * Código do menu
	 * @var int 
	 */
	private $menu = 'workflow/_29010';

	/**
	 * Conexão.
	 * @var _Conexao
	 */
	private $con = null;


	public function viewWorkflowIndex() {
		
		$permissaoMenu = _11010::permissaoMenu($this->menu);
		return view('workflow._29010.index.index', ['menu' => $this->menu, 'permissaoMenu' => $permissaoMenu]);
	}

	public function viewWorkflowCreate() {
		return view('workflow._29010.create.create', ['menu' => $this->menu]);
	}

	public function viewInfoGeral() {
		return view('workflow._29010.create.info-geral', ['menu' => $this->menu]);
	}

	public function viewTarefa() {
		return view('workflow._29010.create.tarefa', ['menu' => $this->menu]);
	}

	public function viewConsulta() {
		return view('workflow._29010.modal-consulta', ['menu' => $this->menu]);
	}

	/**
	 * Consultar workflows cadastrados.
	 *
	 * @access public
	 * @param Request $request
	 * @return json
	 */
	public function consultarWorkflow(Request $request) {

		$this->con = new _Conexao();

		try {

			$param = json_decode(json_encode($request->all()));

			$pu224              = _11010::controle(224);
			$param->USUARIO_ID  = ($pu224 == '1') ? null : \Auth::user()->CODIGO;

			$workflow = _29010::consultarWorkflow($param, $this->con);

			$this->con->commit();

			return Response::json($workflow);

		} catch (Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	/**
	 * Consultar workflow.
	 * Para consultas a partir de outros objetos através de componente (modal).
	 *
	 * @return json
	 */
	public function consultar() {

		$this->con = new _Conexao();

		try {

			$workflow = _29010::consultar($this->con);

			$this->con->commit();

			return Response::json($workflow);

		} catch (Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	/**
	 * Consultar tarefas de determinado workflow.
	 *
	 * @access public
	 * @param Request $request
	 * @return json
	 */
	public function consultarTarefa(Request $request) {

		$this->con = new _Conexao();

		try {

			$param = json_decode(json_encode($request->all()));

			$tarefa         = _29010::consultarTarefa($param, $this->con);
			$destinatario   = _29010::consultarTarefaDestinatario($param, $this->con);
			$campo          = _29010::consultarTarefaCampo($param, $this->con);
			$tarefa         = $this->consultarTarefaArquivo($tarefa);

			$ret = [
				'TAREFA'        => $tarefa,
				'DESTINATARIO'  => $destinatario,
				'CAMPO'         => $campo
			];

			$this->con->commit();

			return Response::json($ret);

		} catch (Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	/**
	 * Consultar arquivos das tarefas.
	 * @param array $tarefa
	 */
	public function consultarTarefaArquivo($tarefa) {

		$conFile = new _Conexao('FILES');

		try {

			$caminho = env('APP_TEMP', '').'workflowTarefa/';

			foreach ($tarefa as $t) {

				$arquivo = _29010::consultarTarefaArquivo($t, $conFile);
				$t->ARQUIVO = [];

				if (!empty($arquivo)) {

					foreach ($arquivo as $key => $a) {
						
						$novoNome = \Auth::user()->CODIGO .'-'. $t->ID .'-'. $a->ID .'-'. $a->ARQUIVO;

						// Para o JSON (angular). Não pode retornar o CONTEUDO (blob).
						$t->ARQUIVO[$key]['WORKFLOW_TAREFA_ID'] = $t->ID;
						$t->ARQUIVO[$key]['ID']                 = $a->ID;
						$t->ARQUIVO[$key]['NOME']               = $a->ARQUIVO;
						$t->ARQUIVO[$key]['TIPO']               = $a->EXTENSAO;
						$t->ARQUIVO[$key]['TAMANHO']            = $a->TAMANHO;
						$t->ARQUIVO[$key]['BINARIO']            = '/assets/temp/workflowTarefa/'.$novoNome;
						
						// Gravar no diretório temporário.
						$novoNome = $caminho . $novoNome;
						$novoArquivo = fopen($novoNome, "a+");
						fwrite($novoArquivo, $a->CONTEUDO);
						fclose($novoArquivo);
					}
				}
			}

			$conFile->commit();

			return $tarefa;

		} catch (Exception $e) {
			$conFile->rollback();
			throw $e;
		}
	}
	
	public function index()
	{
		$permissaoMenu = _11010::permissaoMenu($this->menu);
		
		return view(
			'workflow._29010.index', [
			'permissaoMenu' => $permissaoMenu,
			'menu'          => $this->menu
		]);  
	}

	public function store(Request $request) {

		$this->con = new _Conexao();

		try {

			$dados = $request->all();

			$this->gravarWorkflow($dados);

			$this->con->commit();

			return Response::json($dados['WORKFLOW']);
		}
		catch(Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	public function gravarWorkflow(&$dados) {

		$dados['WORKFLOW']['ID']         = empty($dados['WORKFLOW']['ID'])
											? _29010::gerarIdWorkflow($this->con) 
											: $dados['WORKFLOW']['ID'];

		$dados['WORKFLOW']['USUARIO_ID'] = empty($dados['WORKFLOW']['USUARIO_ID'])
											? \Auth::user()->CODIGO 
											: $dados['WORKFLOW']['USUARIO_ID'];

		_29010::gravarWorkflow($dados['WORKFLOW'], $this->con);

		$this->gravarWorkflowTarefa($dados);
	}

	public function gravarWorkflowTarefa($dados) {

		foreach($dados['TAREFA'] as $key => &$tarefa) {

			$tarefa['ID']               = empty($tarefa['ID']) 
											? _29010::gerarIdWorkflowTarefa($this->con) 
											: $tarefa['ID'];

			$tarefa['WORKFLOW_ID']      = $dados['WORKFLOW']['ID'];

			$tarefa['ORDEM']            = empty($tarefa['ORDEM']) ? $key+1 : $tarefa['ORDEM'];
			
			$tarefa['PONTO_REPROVACAO'] = (empty($tarefa['PONTO_REPROVACAO']) || $tarefa['PONTO_REPROVACAO'] == 'null')
											? null
											: $tarefa['PONTO_REPROVACAO'];

			$tarefa['STATUSEXCLUSAO']   = empty($tarefa['STATUSEXCLUSAO']) ? 0 : $tarefa['STATUSEXCLUSAO'];

			_29010::gravarWorkflowTarefa($tarefa, $this->con);
			
			$this->gravarWorkflowDestinatario($tarefa);
			$this->gravarWorkflowCampo($tarefa);
			$this->gravarTarefaArquivo($tarefa);
		}

	}

	public function gravarWorkflowDestinatario($tarefa) {

		if (isset($tarefa['DESTINATARIO']) && count($tarefa['DESTINATARIO']) > 0) {

			foreach($tarefa['DESTINATARIO'] as $destinatario) {

				$destinatario['ID']                 = empty($destinatario['ID']) ? 0 : $destinatario['ID'];
				$destinatario['WORKFLOW_ID']        = $tarefa['WORKFLOW_ID'];
				$destinatario['WORKFLOW_TAREFA_ID'] = $tarefa['ID'];
				$destinatario['STATUSEXCLUSAO']     = empty($destinatario['STATUSEXCLUSAO']) ? 0 : $destinatario['STATUSEXCLUSAO'];
				
				_29010::gravarWorkflowDestinatario($destinatario, $this->con);
			}
		}
	}

	public function gravarWorkflowCampo($tarefa) {

		if (isset($tarefa['CAMPO']) && count($tarefa['CAMPO']) > 0) {

			foreach($tarefa['CAMPO'] as $campo) {

				// Só grava se o rótulo estiver definido.
				if (!empty($campo['ROTULO']) && $campo['ROTULO'] != 'null') {

					$campo['ID']                 = empty($campo['ID']) ? 0 : $campo['ID'];
					$campo['WORKFLOW_ID']        = $tarefa['WORKFLOW_ID'];
					$campo['WORKFLOW_TAREFA_ID'] = $tarefa['ID'];
					$campo['STATUSEXCLUSAO']     = empty($campo['STATUSEXCLUSAO']) ? 0 : $campo['STATUSEXCLUSAO'];
					
					_29010::gravarWorkflowCampo($campo, $this->con);
				}
			}
		}
	}

	public function gravarTarefaArquivo($tarefa) {

		foreach($tarefa['ARQUIVO'] as $arquivo) {

			// Se algum arquivo foi escolhido e se não existe ID do Arquivo, o que significa que ele ainda não foi gravado.
			if ( $arquivo['BINARIO'] != 'null' && array_key_exists('ID', $arquivo) == false ) {

				$arquivo['VINCULO'] = $tarefa['ID'];
				ArquivoController::gravarArquivo($arquivo);
			}
		}

		// Arquivos para excluir.
		if (isset($tarefa['ARQUIVO_EXCLUIR'])) {
		 
			foreach($tarefa['ARQUIVO_EXCLUIR'] as $arquivo) {

				ArquivoController::excluir($arquivo['ID']);
			}
		}
	}

	public function excluir(Request $request) {

		$this->con = new _Conexao();

		try {

			$param = json_decode(json_encode($request->all()));

			_29010::excluirWorkflow($param, $this->con);
			_29010::excluirWorkflowTarefa($param, $this->con);
			_29010::excluirWorkflowDestinatario($param, $this->con);
			$this->excluirTarefaArquivo($param->TAREFA);

			$this->con->commit();
		}
		catch(Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	public function excluirTarefaArquivo($tarefa) {

		foreach($tarefa as $trf) {

			foreach($trf->ARQUIVO as $arquivo) {

				// Se existe ID do Arquivo, o que significa que ele foi gravado.
				if ( !empty($arquivo->ID) ) {

					ArquivoController::excluir($arquivo->ID);
				}
			}
		}
	}

	/**
	 * Alterar e-mail do usuário.
	 * @param Request $request
	 */
	public function gravarEmailUsuario(Request $request) {

		$this->con = new _Conexao();

		try {

			$param = json_decode(json_encode($request->all()));

			_29010::gravarEmailUsuario($param, $this->con);

			$this->con->commit();
		}
		catch(Exception $e) {
			$this->con->rollback();
			throw $e;
		}
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

}