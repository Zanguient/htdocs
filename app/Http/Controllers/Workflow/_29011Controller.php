<?php

namespace app\Http\Controllers\Workflow;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Workflow\_29011;
use App\Models\DTO\Admin\_11010;
use App\Http\Controllers\Helper\ArquivoController;
use App\Models\Conexao\_Conexao;
use Auth;
use App\Helpers\Helpers;

/**
 * Controller do objeto _29011 - Cadastro de item de workflow
 */
class _29011Controller extends Controller {
	
	/**
	 * Código do menu
	 * @var int 
	 */
	private $menu = 'workflow/_29011';

	/**
	 * Conexão.
	 * @var _Conexao
	 */
	private $con = null;


	public function viewItem() {

		$permissaoMenu = _11010::permissaoMenu($this->menu);
		return view('workflow._29011.index.index', ['menu' => $this->menu, 'permissaoMenu' => $permissaoMenu]);
	}

	public function viewCreate() {
		return view('workflow._29011.create.create', ['menu' => $this->menu]);
	}

	public function viewInfoGeral() {
		return view('workflow._29011.create.info-geral', ['menu' => $this->menu]);
	}

	public function viewTarefa() {
		return view('workflow._29011.create.tarefa', ['menu' => $this->menu]);
	}
	
	public function index()
	{
		$permissaoMenu = _11010::permissaoMenu($this->menu);
		
		return view(
			'workflow._29011.index', [
			'permissaoMenu' => $permissaoMenu,
			'menu'          => $this->menu
		]);  
	}

	/**
	 * Consultar itens de workflow.
	 * @param Request $request
	 * @return json
	 */
	public function consultarItem(Request $request) {

		$this->con = new _Conexao();

		try {

			$param = json_decode(json_encode($request->all()));

			$pu224              = _11010::controle(224);
			$param->USUARIO_ID  = ($pu224 == '1') ? null : Auth::user()->CODIGO;

			$item = _29011::consultarItem($param, $this->con);

			$this->con->commit();

			return Response::json($item);

		} catch (Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	/**
	 * Consultar tarefas do item de workflow.
	 * @param Request $request
	 * @return json
	 */
	public function consultarItemTarefa(Request $request) {

		$this->con = new _Conexao();

		try {

			$param = json_decode(json_encode($request->all()));

			$tarefa         = _29011::consultarItemTarefa($param, $this->con);
			$destinatario   = _29011::consultarItemTarefaDestinatario($param, $this->con);
			$notificado     = _29011::consultarItemTarefaNotificado($param, $this->con);
			$campo          = _29011::consultarItemTarefaCampo($param, $this->con);
			$tarefa         = $this->consultarItemTarefaArquivo($tarefa);

			$ret = [
				'TAREFA'        => $tarefa,
				'DESTINATARIO'  => $destinatario,
				'NOTIFICADO'    => $notificado,
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
	 * Consultar arquivos das tarefas do item de workflow.
	 * @param array $tarefa
	 */
	public function consultarItemTarefaArquivo($tarefa) {

		$conFile = new _Conexao('FILES');

		try {

			$caminho = env('APP_TEMP', '').'workflowTarefa/';

			foreach ($tarefa as $t) {

				$arquivo = _29011::consultarItemTarefaArquivo($t, $conFile);
				$t->ARQUIVO = [];

				if (!empty($arquivo)) {

					foreach ($arquivo as $key => $a) {
						
						$novoNome = Auth::user()->CODIGO .'-'. $t->ID .'-'. $a->ID .'-'. $a->ARQUIVO;

						// Para o JSON (angular). Não pode retornar o CONTEUDO (blob).
						$t->ARQUIVO[$key]['WORKFLOW_ITEM_TAREFA_ID'] = $t->ID;
						$t->ARQUIVO[$key]['ID']                      = $a->ID;
						$t->ARQUIVO[$key]['NOME']                    = $a->ARQUIVO;
						$t->ARQUIVO[$key]['TIPO']                    = $a->EXTENSAO;
						$t->ARQUIVO[$key]['TAMANHO']                 = $a->TAMANHO;
						$t->ARQUIVO[$key]['BINARIO']                 = '/assets/temp/workflowTarefa/'.$novoNome;
						
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

	/**
	 * Consultar tarefas do workflow.
	 * @param Request $request
	 * @return json
	 */
	public function consultarWorkflowTarefa(Request $request) {

		$this->con = new _Conexao();

		try {

			$param = json_decode(json_encode($request->all()));

			$tarefa         = _29011::consultarWorkflowTarefa($param, $this->con);
			$destinatario   = _29011::consultarTarefaDestinatario($param, $this->con);
			$campo          = _29011::consultarTarefaCampo($param, $this->con);
			$tarefa         = $this->consultarWorkflowTarefaArquivo($tarefa);

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
	public function consultarWorkflowTarefaArquivo($tarefa) {

		$conFile = new _Conexao('FILES');

		try {

			$caminho = env('APP_TEMP', '').'workflowTarefa/';

			foreach ($tarefa as $t) {

				$arquivo = _29011::consultarWorkflowTarefaArquivo($t, $conFile);
				$t->ARQUIVO = [];

				if (!empty($arquivo)) {

					foreach ($arquivo as $key => $a) {
						
						$novoNome = Auth::user()->CODIGO .'-'. $t->ID .'-'. $a->ID .'-'. $a->ARQUIVO;

						// Para o JSON (angular). Não pode retornar o CONTEUDO (blob).
						$t->ARQUIVO[$key]['WORKFLOW_TAREFA_ID'] = $t->ID;
						$t->ARQUIVO[$key]['ID']                 = $a->ID;
						$t->ARQUIVO[$key]['NOME']               = $a->ARQUIVO;
						$t->ARQUIVO[$key]['TIPO']               = $a->EXTENSAO;
						$t->ARQUIVO[$key]['TAMANHO']            = $a->TAMANHO;
						$t->ARQUIVO[$key]['BINARIO']            = 'assets/temp/workflowTarefa/'.$novoNome;
						$t->ARQUIVO[$key]['TABELA']             = 'TBWORKFLOW_ITEM_TAREFA';  // tabela destino
						
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

	public function gravar(Request $request) {

		$this->con = new _Conexao();

		try {

			$dados = $request->all();

			$this->gravarWorkflowItem($dados);

			$this->con->commit();

			return Response::json($dados['WORKFLOW']);
		}
		catch(Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	public function gravarWorkflowItem(&$dados) {

		// id do modelo
		$dados['WORKFLOW']['WORKFLOW_ID']           = $dados['WORKFLOW']['WORKFLOW_MODELO']['ID'];
			
		// id do item
		$dados['WORKFLOW']['ID']                    = empty($dados['WORKFLOW']['ID'])
														? _29011::gerarIdWorkflowItem($this->con) 
														: $dados['WORKFLOW']['ID'];
			
		$dados['WORKFLOW']['USUARIO_ID']            = empty($dados['WORKFLOW']['USUARIO_ID'])
														? Auth::user()->CODIGO 
														: $dados['WORKFLOW']['USUARIO_ID'];

		$dados['WORKFLOW']['DATAHORA_FIM_PREVISTA'] = empty($dados['WORKFLOW']['DATAHORA_FIM_PREVISTA']) || $dados['WORKFLOW']['DATAHORA_FIM_PREVISTA'] == 'null'
														? null
														: $dados['WORKFLOW']['DATAHORA_FIM_PREVISTA'];

		_29011::gravarWorkflowItem($dados['WORKFLOW'], $this->con);

		$this->gravarWorkflowItemTarefa($dados);
	}

	public function gravarWorkflowItemTarefa($dados) {

		foreach($dados['TAREFA'] as $key => $tarefa) {

			// Se a tarefa não possui ID ou se vem do modelo.            
			$dados['TAREFA'][$key]['ID']               = empty($tarefa['ID']) || array_key_exists('DO_MODELO', $tarefa)
															? _29011::gerarIdWorkflowItemTarefa($this->con)
															: $tarefa['ID'];

			$dados['TAREFA'][$key]['WORKFLOW_ID']      = $dados['WORKFLOW']['WORKFLOW_ID'];
			$dados['TAREFA'][$key]['WORKFLOW_ITEM_ID'] = $dados['WORKFLOW']['ID'];
			
			$dados['TAREFA'][$key]['ORDEM']            = empty($dados['TAREFA'][$key]['ORDEM'])
															? $key+1
															: $dados['TAREFA'][$key]['ORDEM'];
			
			$dados['TAREFA'][$key]['PONTO_REPROVACAO'] = (empty($dados['TAREFA'][$key]['PONTO_REPROVACAO']) || $dados['TAREFA'][$key]['PONTO_REPROVACAO'] == 'null')
															? null 
															: $dados['TAREFA'][$key]['PONTO_REPROVACAO'];

			$dados['TAREFA'][$key]['STATUSEXCLUSAO']   = empty($tarefa['STATUSEXCLUSAO']) ? 0 : $tarefa['STATUSEXCLUSAO'];

			$this->calcularTempoWorkflowItemTarefa($dados['TAREFA'], $key, $dados['TAREFA'][$key], $dados['WORKFLOW']);

			_29011::gravarWorkflowItemTarefa($dados['TAREFA'][$key], $this->con);

			$this->gravarWorkflowItemGestorNotificacao($dados['TAREFA'][$key], $dados['WORKFLOW']);
			$this->gravarWorkflowItemDestinatario($dados['TAREFA'][$key], $dados['WORKFLOW']);
			$this->gravarWorkflowItemNotificado($dados['TAREFA'][$key], $dados['WORKFLOW']);
			$this->gravarWorkflowItemCampo($dados['TAREFA'][$key]);            
			$this->gravarWorkflowItemArquivo($dados['TAREFA'][$key]);
		}
	}

	/**
	 * Calcular tempos das tarefas.
	 *
	 * @param array $tarefaAll
	 * @param integer $key
	 * @param array $tarefa
	 * @param array $workflow
	 * @param boolean $recalculo Opção utilizada pelo _29012Controller para recálculo de tempo.
	 */
	public function calcularTempoWorkflowItemTarefa($tarefaAll, $key, &$tarefa, $workflow, $recalculo = false) {

		// Se for a primeira tarefa, a data inicial vai ser a data inicial prevista do workflow.
		if ( $key == 0 ) {

			if ($recalculo == true) {
				// Data inicial calculada.
				$tarefa['DATAHORA_INI_PREVISTA'] = date_create();
			}
			else {
				// Data inicial calculada.
				$tarefa['DATAHORA_INI_PREVISTA'] = date_create($workflow['DATAHORA_INI_PREVISTA']);
			}
			
			// Data final calculada.
			$tarefa['DATAHORA_FIM_PREVISTA'] = clone $tarefa['DATAHORA_INI_PREVISTA'];
		}
		else {

			// Se for a próxima sequência.
			if ( $tarefaAll[$key-1]['SEQUENCIA'] != $tarefa['SEQUENCIA'] ) {

				// se a tarefa anterior estiver sendo ou já estiver concluída, a data inicial deve ser a atual.
				if ($recalculo == true && (isset($tarefaAll[$key-1]['CONCLUINDO']) || $tarefaAll[$key-1]['STATUS_CONCLUSAO'] == 3) ) {
					
					$tarefa['DATAHORA_INI_PREVISTA'] = date_create();
				}
				// Se for recálculo e houver data final de conclusão (data efetiva) na tarefa anterior.
				// Ou seja, já foi efetuado um recálculo, então a data precisa ser a efetiva.
				else if ($recalculo == true && isset($tarefaAll[$key-1]['DATAHORA_FIM_CONCLUSAO'])) {

					$tarefa['DATAHORA_INI_PREVISTA'] = is_object($tarefaAll[$key-1]['DATAHORA_FIM_CONCLUSAO']) 
															? clone $tarefaAll[$key-1]['DATAHORA_FIM_CONCLUSAO'] 
															: date_create($tarefaAll[$key-1]['DATAHORA_FIM_CONCLUSAO']);
				}
				// a data inicial calculada é a data final calculada da tarefa anterior + 1 minuto.
				else {

					$tarefa['DATAHORA_INI_PREVISTA'] = is_object($tarefaAll[$key-1]['DATAHORA_FIM_PREVISTA']) 
															? clone $tarefaAll[$key-1]['DATAHORA_FIM_PREVISTA'] 
															: date_create($tarefaAll[$key-1]['DATAHORA_FIM_PREVISTA']);
				}

				date_modify($tarefa['DATAHORA_INI_PREVISTA'], '+1 minutes');
			}
			
			// Se for da mesma sequência, a data inicial calculada é a mesma da tarefa anterior.
			else {

				// se a tarefa anterior estiver sendo ou já estiver concluída, a data inicial deve ser a atual.
				if ($recalculo == true && (isset($tarefaAll[$key-1]['CONCLUINDO']) || $tarefaAll[$key-1]['STATUS_CONCLUSAO'] == 3) ) {
					
					$tarefa['DATAHORA_INI_PREVISTA'] = date_create();
				}
				// Se for recálculo e houver data final de conclusão (data efetiva) na tarefa anterior.
				// Ou seja, já foi efetuado um recálculo, então a data precisa ser a efetiva.
				else if ($recalculo == true && isset($tarefaAll[$key-1]['DATAHORA_INI_CONCLUSAO'])) {

					$tarefa['DATAHORA_INI_PREVISTA'] = is_object($tarefaAll[$key-1]['DATAHORA_INI_CONCLUSAO']) 
															? clone $tarefaAll[$key-1]['DATAHORA_INI_CONCLUSAO'] 
															: date_create($tarefaAll[$key-1]['DATAHORA_INI_CONCLUSAO']);
				}
				// a data inicial calculada é a data final calculada da tarefa anterior + 1 minuto.
				else {

					$tarefa['DATAHORA_INI_PREVISTA'] = is_object($tarefaAll[$key-1]['DATAHORA_INI_PREVISTA'])
															? clone $tarefaAll[$key-1]['DATAHORA_INI_PREVISTA']
															: date_create($tarefaAll[$key-1]['DATAHORA_INI_PREVISTA']);
				}
			}

			// Data final calculada.
			$tarefa['DATAHORA_FIM_PREVISTA'] = clone $tarefa['DATAHORA_INI_PREVISTA'];
		}


		/////// Cálculos

		// tempo previsto
		$duracao = $tarefa['TEMPO_PREVISTO'];

		// Se for recálculo, a duração deve ser subtraída do tempo já efetuado.
		if ($recalculo == true)
			$duracao -= $tarefa['TEMPO_CONCLUSAO'];

		// periodos
		$horarioPermitido = explode(';', $tarefa['HORARIO_PERMITIDO']);

		// intervalos
		foreach ($horarioPermitido as $key1 => $h) {

			$horarioPermitido[$key1] = explode('-', $h);
		}

		// dias permitidos
		$diaSemanaPermitido = [];
		if ($tarefa['DOMINGO']  == '1') array_push($diaSemanaPermitido, 0);
		if ($tarefa['SEGUNDA']  == '1') array_push($diaSemanaPermitido, 1);
		if ($tarefa['TERCA']    == '1') array_push($diaSemanaPermitido, 2);
		if ($tarefa['QUARTA']   == '1') array_push($diaSemanaPermitido, 3);
		if ($tarefa['QUINTA']   == '1') array_push($diaSemanaPermitido, 4);
		if ($tarefa['SEXTA']    == '1') array_push($diaSemanaPermitido, 5);
		if ($tarefa['SABADO']   == '1') array_push($diaSemanaPermitido, 6);

		for ($i = 0; $i < $duracao; $i++) {

			$jaCalculou = false;
			$jaAdicDia  = false;

			foreach ($horarioPermitido as $key2 => $horPerm) {

				$horaPerm0 = date('H:i', strtotime($horPerm[0]));
				$horaPerm1 = date('H:i', strtotime($horPerm[1]));
				$horaFim   = date_format($tarefa['DATAHORA_FIM_PREVISTA'], 'H:i');
				$diaSemana = date('w', strtotime(date_format($tarefa['DATAHORA_FIM_PREVISTA'], 'Y-m-d H:i')));

				// Dia diferente e não permitido.
				if ( !in_array($diaSemana, $diaSemanaPermitido) ) {

					date_modify($tarefa['DATAHORA_FIM_PREVISTA'], '+1 days');
					$jaAdicDia = true;
				}

				// Se estiver dentro da faixa de horário permitida, adiciona 1 minuto.
				else if ( ($horaFim >= $horaPerm0) && ($horaFim < $horaPerm1) ) {

					date_modify($tarefa['DATAHORA_FIM_PREVISTA'], '+1 minutes');
					$jaCalculou = true;
					$jaAdicDia  = false;
				}

				// Se ainda existir mais um intervalo e se o horário inicial desse próximo intervalo 
				// for maior do que a hora final do intervalo atual, define essa hora como próximo horário da data.
				//
				// Ex.: 07:00-[[11:50]];[[13:02]]-17:00
				//      horaCorrente = 11:50 e proxHora = 13:02, 
				//      então horaCorrente será 13:02.
				else if ( isset($horarioPermitido[$key2+1]) && ($horarioPermitido[$key2+1][0] > $horaFim) ) {

					// Próximo horário inicial do intervalo.
					// Ex.: 07:00-11:50;[[13:02]]-17:00
					$proxHorario0 = strtotime($horarioPermitido[$key2+1][0]);

					date_time_set(
						$tarefa['DATAHORA_FIM_PREVISTA'], 
						date('H', $proxHorario0), 
						date('i', $proxHorario0));
				}
				
				// Se for o último horário permitido dentro da faixa, adiciona 1 dia.
				// Ex.: [[09/10]] 17:00 -> [[10/10]] 07:00
				else if ( ($key2 == count($horarioPermitido)-1) && ($jaCalculou == false) ) {

					if ($jaAdicDia == false)
						date_modify($tarefa['DATAHORA_FIM_PREVISTA'], '+1 days');
					
					// Ajusta o horário quando o dia é passado (virado).
					// Ex.: 09/10 [[17:00]] - 10/10 [[07:00]]
					date_time_set(
						$tarefa['DATAHORA_FIM_PREVISTA'], 
						date('H', strtotime($horarioPermitido[0][0])), 
						date('i', strtotime($horarioPermitido[0][0])), 
						date('s', strtotime($horarioPermitido[0][0])));

					// Adiciona 1 minuto ao horário final, a fim de ajustar a contagem de minutos. 
					// Ex.: 16:55...17:00-07:00...07:04 -> 16:55...17:00-07:00...07:05
					date_modify($tarefa['DATAHORA_FIM_PREVISTA'], '+1 minutes');
					
					$jaCalculou = true;
				}
			}
		}

	}

	/**
	 * Notifica gestor (criador do workflow) quando o tempo previsto para a conclusão da tarefa expirar.
	 *
	 * @param array $tarefa
	 * @param array $workflow
	 * @param _Conexao $conexao
	 */
	public function gravarWorkflowItemGestorNotificacao($tarefa, $workflow, $conexao = null) {

		$notificacao['TIPO']        = '1';
		$notificacao['USUARIO_ID']  = Auth::user()->CODIGO;
		$notificacao['TITULO']      = 'Excedido o tempo de tarefa';
		$notificacao['MENSAGEM']    = '<span>O tempo previsto para a Tarefa <b>'. $tarefa['TITULO'] .'</b> do Workflow <b>'. $workflow['TITULO'] .'</b> foi excedido.</span>';
		$notificacao['MENSAGEM']   .= ' <a href="'. env('URL_PRINCIPAL') .'/_29012?workflowItemId='. $tarefa['WORKFLOW_ITEM_ID'] .'&tarefaId='. $tarefa['ID'] .'" target="_blank">Verifique aqui.</a>';
		$notificacao['EMITENTE']    = Auth::user()->CODIGO;
		$notificacao['AGENDAMENTO'] = date_format($tarefa['DATAHORA_FIM_PREVISTA'], 'd.m.Y H:i:s');
		$notificacao['TABELA']      = 'TBWORKFLOW_ITEM_TAREFA';
		$notificacao['TABELA_ID']   = $tarefa['ID'];

		$conexao = $conexao ?: $this->con;
		_29011::gravarWorkflowItemNotificacao($notificacao, $conexao);
	}

	public function gravarWorkflowItemDestinatario($tarefa, $workflow) {

		if (isset($tarefa['DESTINATARIO']) && count($tarefa['DESTINATARIO']) > 0) {

			foreach($tarefa['DESTINATARIO'] as $destinatario) {

				// Se o id estiver vazio, significa que é um novo destinatário ou
				// se o destinatário for do modelo, precisa de um novo id.
				$destinatario['ID']                      = empty($destinatario['ID']) || array_key_exists('DO_MODELO', $destinatario)
																? 0 
																: $destinatario['ID'];

				$destinatario['WORKFLOW_ID']             = $tarefa['WORKFLOW_ID'];
				$destinatario['WORKFLOW_ITEM_ID']        = $tarefa['WORKFLOW_ITEM_ID'];
				$destinatario['WORKFLOW_ITEM_TAREFA_ID'] = $tarefa['ID'];
				$destinatario['STATUSEXCLUSAO']          = empty($destinatario['STATUSEXCLUSAO']) ? 0 : $destinatario['STATUSEXCLUSAO'];
				
				_29011::gravarWorkflowItemDestinatario($destinatario, $this->con);

				$this->gravarWorkflowItemDestinatarioNotificacaoAntecipada($tarefa, $destinatario, $workflow);
				$this->gravarWorkflowItemDestinatarioNotificacaoExpiracao($tarefa, $destinatario, $workflow);
			}
		}
	}

	/**
	 * Notifica destinatário há 30 minutos do início da tarefa.
	 *
	 * @param array $tarefa
	 * @param array $destinatario
	 * @param array $workflow
	 * @param _Conexao $conexao
	 */
	public function gravarWorkflowItemDestinatarioNotificacaoAntecipada($tarefa, $destinatario, $workflow, $conexao = null) {

		$notificacao['TIPO']        = '1';
		$notificacao['USUARIO_ID']  = $destinatario['USUARIO_ID'];
		$notificacao['TITULO']      = 'Lembrete de tarefa';
		$notificacao['MENSAGEM']    = '<span>Faltam 30 minutos para que a Tarefa <b>'. $tarefa['TITULO'] .'</b> do Workflow <b>'. $workflow['TITULO'] .'</b> seja iniciada.</span>';
		$notificacao['MENSAGEM']   .= ' <a href="'. env('URL_PRINCIPAL') .'/_29012?workflowItemId='. $tarefa['WORKFLOW_ITEM_ID'] .'&tarefaId='. $tarefa['ID'] .'" target="_blank">Verifique aqui.</a>';
		$notificacao['EMITENTE']    = Auth::user()->CODIGO;
		$notificacao['TABELA']      = 'TBWORKFLOW_ITEM_TAREFA';
		$notificacao['TABELA_ID']   = $tarefa['ID'];

		$dataAntecipacao = clone $tarefa['DATAHORA_INI_PREVISTA'];
		date_modify($dataAntecipacao, '-30 minutes');

		$notificacao['AGENDAMENTO'] = date_format($dataAntecipacao, 'd.m.Y H:i:s');

		$conexao = $conexao ?: $this->con;
		_29011::gravarWorkflowItemNotificacao($notificacao, $conexao);
	}

	/**
	 * Notifica quando o tempo previsto para a conclusão da tarefa expirar.
	 *
	 * @param array $tarefa
	 * @param array $destinatario
	 * @param array $workflow
	 * @param _Conexao $conexao
	 */
	public function gravarWorkflowItemDestinatarioNotificacaoExpiracao($tarefa, $destinatario, $workflow, $conexao = null) {

		$notificacao['TIPO']        = '1';
		$notificacao['USUARIO_ID']  = $destinatario['USUARIO_ID'];
		$notificacao['TITULO']      = 'Excedido o tempo de tarefa';
		$notificacao['MENSAGEM']    = '<span>O tempo previsto para a Tarefa <b>'. $tarefa['TITULO'] .'</b> do Workflow <b>'. $workflow['TITULO'] .'</b> foi excedido.</span>';
		$notificacao['MENSAGEM']   .= ' <a href="'. env('URL_PRINCIPAL') .'/_29012?workflowItemId='. $tarefa['WORKFLOW_ITEM_ID'] .'&tarefaId='. $tarefa['ID'] .'" target="_blank">Verifique aqui se você já a concluiu.</a>';
		$notificacao['EMITENTE']    = Auth::user()->CODIGO;
		$notificacao['TABELA']      = 'TBWORKFLOW_ITEM_TAREFA';
		$notificacao['TABELA_ID']   = $tarefa['ID'];
		$notificacao['AGENDAMENTO'] = date_format($tarefa['DATAHORA_FIM_PREVISTA'], 'd.m.Y H:i:s');

		$conexao = $conexao ?: $this->con;
		_29011::gravarWorkflowItemNotificacao($notificacao, $conexao);
	}

	public function gravarWorkflowItemNotificado($tarefa, $workflow) {

		if (isset($tarefa['NOTIFICADO']) && count($tarefa['NOTIFICADO']) > 0) {

			foreach($tarefa['NOTIFICADO'] as $notificado) {

				// Se o id estiver vazio, significa que é um novo usuário notificado ou
				// se o notificado for do modelo, precisa de um novo id.
				$notificado['ID']                      = empty($notificado['ID']) || array_key_exists('DO_MODELO', $notificado)
															? 0 
															: $notificado['ID'];

				$notificado['WORKFLOW_ID']             = $tarefa['WORKFLOW_ID'];
				$notificado['WORKFLOW_ITEM_ID']        = $tarefa['WORKFLOW_ITEM_ID'];
				$notificado['WORKFLOW_ITEM_TAREFA_ID'] = $tarefa['ID'];
				$notificado['STATUSEXCLUSAO']          = empty($notificado['STATUSEXCLUSAO']) ? 0 : $notificado['STATUSEXCLUSAO'];
				
				_29011::gravarWorkflowItemNotificado($notificado, $this->con);

				$this->gravarWorkflowItemNotificadoNotificacao($tarefa, $notificado, $workflow);
			}
		}
	}

	/**
	 * Notifica 'notificados' quando o tempo previsto para a conclusão da tarefa expirar.
	 *
	 * @param array $tarefa
	 * @param array $notificado
	 * @param array $workflow
	 * @param _Conexao $conexao
	 */
	public function gravarWorkflowItemNotificadoNotificacao($tarefa, $notificado, $workflow, $conexao = null) {

		$notificacao['TIPO']        = '1';
		$notificacao['USUARIO_ID']  = $notificado['USUARIO_ID'];
		$notificacao['TITULO']      = 'Excedido o tempo de tarefa';
		$notificacao['MENSAGEM']    = '<span>O tempo previsto para a Tarefa <b>'. $tarefa['TITULO'] .'</b> do Workflow <b>'. $workflow['TITULO'] .'</b> foi excedido.</span>';
		$notificacao['MENSAGEM']   .= ' <a href="'. env('URL_PRINCIPAL') .'/_29012?workflowItemId='. $tarefa['WORKFLOW_ITEM_ID'] .'&tarefaId='. $tarefa['ID'] .'" target="_blank">Verifique aqui.</a>';
		$notificacao['EMITENTE']    = Auth::user()->CODIGO;
		$notificacao['AGENDAMENTO'] = date_format($tarefa['DATAHORA_FIM_PREVISTA'], 'd.m.Y H:i:s');
		$notificacao['TABELA']      = 'TBWORKFLOW_ITEM_TAREFA';
		$notificacao['TABELA_ID']   = $tarefa['ID'];

		$conexao = $conexao ?: $this->con;
		_29011::gravarWorkflowItemNotificacao($notificacao, $conexao);
	}

	public function gravarWorkflowItemCampo($tarefa) {

		if (isset($tarefa['CAMPO']) && count($tarefa['CAMPO']) > 0) {

			foreach($tarefa['CAMPO'] as $campo) {

				// Só grava se o rótulo estiver definido.
				if (!empty($campo['ROTULO']) && $campo['ROTULO'] != 'null') {

					// Se o id estiver vazio, significa que é um novo campo ou
					// se o campo for do modelo, precisa de um novo id.
					$campo['ID']                      = empty($campo['ID']) || array_key_exists('DO_MODELO', $campo)
															? 0 
															: $campo['ID'];

					$campo['WORKFLOW_ID']             = $tarefa['WORKFLOW_ID'];
					$campo['WORKFLOW_ITEM_ID']        = $tarefa['WORKFLOW_ITEM_ID'];
					$campo['WORKFLOW_ITEM_TAREFA_ID'] = $tarefa['ID'];
					$campo['STATUSEXCLUSAO']          = empty($campo['STATUSEXCLUSAO']) ? 0 : $campo['STATUSEXCLUSAO'];
					
					_29011::gravarWorkflowItemCampo($campo, $this->con);
				}
			}
		}
	}

	public function gravarWorkflowItemArquivo($tarefa) {

		foreach($tarefa['ARQUIVO'] as $arquivo) {

			// Se algum arquivo foi escolhido 
			// e se o arquivo vem do modelo de workflow 
			// ou se não existe ID do Arquivo, o que significa que ele ainda não foi gravado.
			if ( $arquivo['BINARIO'] != 'null' && (array_key_exists('WORKFLOW_TAREFA_ID', $arquivo) == true || array_key_exists('ID', $arquivo) == false) ) {

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

	public function encerrar(Request $request) {

		$this->con = new _Conexao();

		try {

			$param = json_decode(json_encode($request->all()));

			_29011::encerrarWorkflowItem($param, $this->con);

			$this->con->commit();
		}
		catch(Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	public function excluir(Request $request) {

		$this->con = new _Conexao();

		try {

			$param = json_decode(json_encode($request->all()));

			_29011::excluirWorkflowItem($param, $this->con);
			_29011::excluirWorkflowItemTarefa($param, $this->con);
			_29011::excluirWorkflowItemTarefaDestinatario($param, $this->con);
			_29011::excluirWorkflowItemTarefaNotificado($param, $this->con);
			$this->excluirWorkflowItemTarefaNotificacao($param->TAREFA);
			$this->excluirWorkflowItemTarefaArquivo($param->TAREFA);

			$this->con->commit();
		}
		catch(Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	public function excluirWorkflowItemTarefaNotificacao($tarefa) {

		foreach($tarefa as $trf)
			_29011::excluirWorkflowItemTarefaNotificacao($trf, $this->con);
	}

	public function excluirWorkflowItemTarefaArquivo($tarefa) {

		foreach($tarefa as $trf) {

			foreach($trf->ARQUIVO as $arquivo) {

				// Se existe ID do Arquivo, o que significa que ele foi gravado.
				if ( !empty($arquivo->ID) ) {

					ArquivoController::excluir($arquivo->ID);
				}
			}
		}
	}

}