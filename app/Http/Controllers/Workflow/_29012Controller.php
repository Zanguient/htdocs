<?php

namespace app\Http\Controllers\Workflow;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\DTO\Workflow\_29012;
use App\Models\DTO\Admin\_11010;
use App\Http\Controllers\Helper\ArquivoController;
use App\Models\Conexao\_Conexao;
use App\Models\DTO\Helper\Email;
use App\Http\Controllers\Workflow\_29011Controller;

/**
 * Controller do objeto _29012 - Workflow
 */
class _29012Controller extends Controller {
	
	/**
	 * Código do menu
	 * @var int 
	 */
	private $menu = 'workflow/_29012';

	/**
	 * Conexão.
	 * @var _Conexao
	 */
	private $con = null;


	public function viewItem() {
		return view('workflow._29012.index.index', ['menu' => $this->menu]);
	}

	public function viewCreate() {
		return view('workflow._29012.create.create', ['menu' => $this->menu]);
	}

	public function viewInfoGeral() {
		return view('workflow._29012.create.info-geral', ['menu' => $this->menu]);
	}

	public function viewTarefa() {

		$pu224 = _11010::controle(224);

		return view('workflow._29012.create.tarefa', [
			'menu'  => $this->menu,
			'pu224' => $pu224 ? $pu224 : '0'
		]);
	}
	
	public function index()
	{
		$permissaoMenu = _11010::permissaoMenu($this->menu);
		
		return view(
			'workflow._29012.index', [
			'permissaoMenu'     => $permissaoMenu,
			'menu'              => $this->menu
		]);  
	}

	/**
	 * Consultar itens de workflow por usuário.
	 * @param Request $request
	 * @return json
	 */
	public function consultarWorkflowItem(Request $request) {

		$this->con = new _Conexao();

		try {

			$param = json_decode(json_encode($request->all()));

			$pu224              = _11010::controle(224);
			$param->USUARIO_ID  = ($pu224 == '1') ? null : \Auth::user()->CODIGO;

			$item = _29012::consultarWorkflowItem($param, $this->con);

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
	public function consultarWorkflowItemTarefa(Request $request) {

		$this->con = new _Conexao();

		try {

			$param = json_decode(json_encode($request->all()));

			$tarefa         = _29012::consultarWorkflowItemTarefa($param, $this->con);
			$destinatario   = _29012::consultarWorkflowItemTarefaDestinatario($param, $this->con);
			$notificado     = _29012::consultarWorkflowItemTarefaNotificado($param, $this->con);
			$campo          = _29012::consultarWorkflowItemTarefaCampo($param, $this->con);
			$comentario     = _29012::consultarWorkflowItemTarefaComentario($param, $this->con);
			$movimentacao   = _29012::consultarWorkflowItemTarefaMovimentacao($param, $this->con);
			$tarefa         = $this->consultarWorkflowItemTarefaArquivo($tarefa, 'TBWORKFLOW_ITEM_TAREFA');
			$tarefa         = $this->consultarWorkflowItemTarefaArquivo($tarefa, 'TBWORKFLOW_ITEM_TAREFA_DESTINAT');

			$ret = [
				'TAREFA'                => $tarefa,
				'DESTINATARIO'          => $destinatario,
				'NOTIFICADO'            => $notificado,
				'CAMPO'                 => $campo,
				'COMENTARIO'            => $comentario,
				'MOVIMENTACAO'          => $movimentacao
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
	public function consultarWorkflowItemTarefaArquivo($tarefa, $tabela) {

		$conFile = new _Conexao('FILES');

		try {

			$caminho = env('APP_TEMP', '').'workflowTarefa/';

			foreach ($tarefa as $t) {

				$t->TABELA = $tabela;
				$arquivo = _29012::consultarWorkflowItemTarefaArquivo($t, $conFile);

				if ($tabela == 'TBWORKFLOW_ITEM_TAREFA')
					$t->ARQUIVO = [];
				else
					$t->ARQUIVO_DESTINATARIO = [];

				if (!empty($arquivo)) {

					foreach ($arquivo as $key => $a) {
						
						$novoNome = \Auth::user()->CODIGO .'-'. $t->ID .'-'. $a->ID .'-'. $a->ARQUIVO;

						// Para o JSON (angular). Não pode retornar o CONTEUDO (blob).
						$arq[$key]['WORKFLOW_ITEM_TAREFA_ID'] = $t->ID;
						$arq[$key]['ID']                      = $a->ID;
						$arq[$key]['NOME']                    = $a->ARQUIVO;
						$arq[$key]['TIPO']                    = $a->EXTENSAO;
						$arq[$key]['BINARIO']                 = '/assets/temp/workflowTarefa/'.$novoNome;

						if ($tabela == 'TBWORKFLOW_ITEM_TAREFA')
							$t->ARQUIVO[$key] = $arq[$key];
						else
							$t->ARQUIVO_DESTINATARIO[$key] = $arq[$key];
						
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
	 * Consultar arquivos de uma tarefa do item de workflow.
	 * @param array $tarefa
	 */
	public function consultarWorkflowItemTarefaArquivoPorTarefa($tarefa) {

		$conFile = new _Conexao('FILES');

		try {

			$caminho = env('APP_TEMP', '').'workflowTarefa/';

			$tarefa = json_decode(json_encode($tarefa));

			$tarefa->TABELA = 'TBWORKFLOW_ITEM_TAREFA_DESTINAT';
			$arquivo = _29012::consultarWorkflowItemTarefaArquivoPorTarefa($tarefa, $conFile);
			$tarefa->ARQUIVO = [];

			if (!empty($arquivo)) {

				foreach ($arquivo as $key => $a) {
					
					$novoNome = \Auth::user()->CODIGO .'-'. $tarefa->TAREFA_ID .'-'. $a->ID .'-'. $a->ARQUIVO;

					// Para o JSON (angular). Não pode retornar o CONTEUDO (blob).
					$tarefa->ARQUIVO[$key]['WORKFLOW_ITEM_TAREFA_ID'] = $tarefa->TAREFA_ID;
					$tarefa->ARQUIVO[$key]['ID']                      = $a->ID;
					$tarefa->ARQUIVO[$key]['NOME']                    = $a->ARQUIVO;
					$tarefa->ARQUIVO[$key]['TIPO']                    = $a->EXTENSAO;
					$tarefa->ARQUIVO[$key]['BINARIO']                 = '/assets/temp/workflowTarefa/'.$novoNome;
					
					// Gravar no diretório temporário.
					$novoNome = $caminho . $novoNome;
					$novoArquivo = fopen($novoNome, "a+");
					fwrite($novoArquivo, $a->CONTEUDO);
					fclose($novoArquivo);
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
	 * Alterar situação da tarefa do item de workflow.
	 *
	 * @param Request $request
	 * @return json
	 */
	public function alterarSituacaoWorkflowItemTarefa(Request $request) {

		$this->con = new _Conexao();

		try {

			$param = json_decode(json_encode($request->all()));
			$param->USUARIO_ID = \Auth::user()->CODIGO;

			// Zerar tempo efetuado ao reativar uma tarefa.
			if ($param->SITUACAO == 0)
				$param->TAREFA_ATUAL->TEMPO_CONCLUSAO = 0;

			$this->alterarSituacaoTarefa($param);

			$movimentacao   = _29012::consultarWorkflowItemTarefaMovimentacaoPorTarefa($param, $this->con);
			$progresso      = _29012::consultarWorkflowItemProgresso($param->TAREFA_ATUAL, $this->con);

			$this->recalcularTempoWorkflowItemTarefa($param);
			$this->enviarNotificacao($param);

			$tarefa         = _29012::consultarWorkflowItemTarefa($param, $this->con);
			$destinatario   = _29012::consultarWorkflowItemTarefaDestinatario($param, $this->con);
			$notificado     = _29012::consultarWorkflowItemTarefaNotificado($param, $this->con);
			$campo          = _29012::consultarWorkflowItemTarefaCampo($param, $this->con);
			$comentario     = _29012::consultarWorkflowItemTarefaComentario($param, $this->con);
			$movimentacao   = _29012::consultarWorkflowItemTarefaMovimentacao($param, $this->con);
			$tarefa         = $this->consultarWorkflowItemTarefaArquivo($tarefa, 'TBWORKFLOW_ITEM_TAREFA');
			$tarefa         = $this->consultarWorkflowItemTarefaArquivo($tarefa, 'TBWORKFLOW_ITEM_TAREFA_DESTINAT');

			$this->con->commit();

			$res = [
				'MOVIMENTACAO'  => $movimentacao,
				'PROGRESSO'     => $progresso,
				'TAREFA'        => $tarefa,
				'DESTINATARIO'  => $destinatario,
				'NOTIFICADO'    => $notificado,
				'CAMPO'         => $campo,
				'COMENTARIO'    => $comentario,
				'MOVIMENTACAO'  => $movimentacao
			];

			return Response::json($res);

		} catch (Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	/**
	 * Alterar situação da tarefa.
	 * @param json $param
	 */
	public function alterarSituacaoTarefa($param) {

		// Reprovar tarefa
		if ($param->SITUACAO == 4) {

			foreach ($param->TAREFA as $trf) {

				// Reprova da tarefa atual à tarefa marcada como ponto de retorno.
				if ( ($trf->ORDEM >= $param->TAREFA_ATUAL->PONTO_REPROVACAO) && ($trf->ORDEM <= $param->TAREFA_ATUAL->ORDEM) ) {
				
					$prm                = new \stdClass();
					$prm->TAREFA_ATUAL  = new \stdClass();

					$prm->SITUACAO                      = $param->SITUACAO;
					$prm->USUARIO_ID                    = $param->USUARIO_ID;
					$prm->TAREFA_ATUAL->TEMPO_CONCLUSAO = $trf->TEMPO_CONCLUSAO;
					$prm->TAREFA_ID                     = $trf->ID;

					_29012::alterarSituacaoWorkflowItemTarefa($prm, $this->con);
				}
			}
		}
		else
			_29012::alterarSituacaoWorkflowItemTarefa($param, $this->con);
	}

	/**
	 * Recalcular o tempo da tarefa.
	 * @param object $dadosObj
	 */
	public function recalcularTempoWorkflowItemTarefa($dadosObj) {

		// Se a tarefa estivendo sendo iniciada ou concluída.
		if ($dadosObj->SITUACAO == 1 || $dadosObj->SITUACAO == 3) {

			$_29011Ctrl = new _29011Controller();

			// Necessário transformar em array pois a função que calcula funciona com array.
			$dados = clone $dadosObj;
			$dados = json_decode(json_encode($dados), true);

			$ehTarefaAtual = false;

			foreach ($dados['TAREFA'] as $key => $tarefa) {

				// Determina se a tarefa corrente é a tarefa atual.
				$ehTarefaAtual = ($dados['TAREFA'][$key]['ID'] == $dados['TAREFA_ATUAL']['ID']);

				// Não recalcular quando a tarefa corrente já estiver concluída.
				if ( $dados['TAREFA'][$key]['STATUS_CONCLUSAO'] == 3 )
					continue;

				// Se a tarefa corrente for a tarefa atual (em ação) e a tarefa atual estiver sendo concluída,
				// o tempo previsto (no cálculo) deve ser o tempo efetuado. 
				// Necessário para o cálculo (se a tarefa anterior já estiver concluída, a data inicial deve ser a atual).
				if ( $ehTarefaAtual && ($dados['SITUACAO'] == 3) ) {

					$dados['TAREFA'][$key]['TEMPO_PREVISTO'] = $dados['TAREFA'][$key]['TEMPO_CONCLUSAO'];
					$dados['TAREFA'][$key]['CONCLUINDO']     = true;
				}
			
				// Efetuar cálculos.
				$_29011Ctrl->calcularTempoWorkflowItemTarefa($dados['TAREFA'], $key, $dados['TAREFA'][$key], $dados['INFO_GERAL'], true);

				$this->gravarTempoEfetuado($dados['TAREFA'], $key, $dados['TAREFA'][$key], $dados['SITUACAO'], $ehTarefaAtual);
				$this->reenviarNotificacao($dados['TAREFA'][$key], $dados['SITUACAO'], $dados['INFO_GERAL'], $_29011Ctrl, $ehTarefaAtual);
			}
		}
	}

	/**
	 * Gravar as datas efetuadas depois de recalcular o tempo.
	 * @param array $tarefaAll
	 * @param integer $key
	 * @param array $tarefaCorrente
	 * @param integer $situacao
	 * @param boolean $ehTarefaAtual
	 */
	public function gravarTempoEfetuado($tarefaAll, $key, &$tarefaCorrente, $situacao, $ehTarefaAtual) {

		$gravar = false;

		// Se estiver sendo iniciada pela primeira vez.
		if (($tarefaCorrente['STATUS_CONCLUSAO'] == 0) && ($situacao == 1)) {

			$tarefaCorrente['DATAHORA_INI_RECALCULADA'] = $ehTarefaAtual 
															? date_format(date_create(), 'd.m.Y H:i:s') 
															: date_format($tarefaCorrente['DATAHORA_INI_PREVISTA'], 'd.m.Y H:i:s');

			// Só define a data inicial efetiva se for a tarefa atual.
			if ($ehTarefaAtual)
				$tarefaCorrente['DATAHORA_INI_CONCLUSAO'] = $tarefaCorrente['DATAHORA_INI_RECALCULADA'];

			$tarefaCorrente['DATAHORA_FIM_RECALCULADA'] = date_format($tarefaCorrente['DATAHORA_FIM_PREVISTA'], 'd.m.Y H:i:s');

			$gravar = true;
		}

		// Se estiver sendo continuada (pausada => iniciada).
		else if (($tarefaCorrente['STATUS_CONCLUSAO'] == 2) && ($situacao == 1)) {
		
			$tarefaCorrente['DATAHORA_FIM_RECALCULADA'] = date_format($tarefaCorrente['DATAHORA_FIM_PREVISTA'], 'd.m.Y H:i:s');
			$gravar = true;
		}

		// Se estiver sendo concluída e for a tarefa atual.
		else if ($situacao == 3 && $ehTarefaAtual) {
		
			$tarefaCorrente['DATAHORA_FIM_CONCLUSAO'] = date_format(date_create(), 'd.m.Y H:i:s');
			$gravar = true;
		}

		// Se estiver sendo concluída e a tarefa anterior estiver concluindo, 
		// ou seja, está no processo de gravação de conclusão da tarefa anterior.
		else if ($situacao == 3 && isset($tarefaAll[$key-1]['CONCLUINDO'])) {

			$tarefaCorrente['DATAHORA_INI_RECALCULADA'] = date_format(date_create(), 'd.m.Y H:i:s');
			$tarefaCorrente['DATAHORA_FIM_RECALCULADA'] = date_format($tarefaCorrente['DATAHORA_FIM_PREVISTA'], 'd.m.Y H:i:s');
			$gravar = true;
		}

		else if ($situacao == 3) {

			$tarefaCorrente['DATAHORA_INI_RECALCULADA'] = date_format($tarefaCorrente['DATAHORA_INI_PREVISTA'], 'd.m.Y H:i:s');
			$tarefaCorrente['DATAHORA_FIM_RECALCULADA'] = date_format($tarefaCorrente['DATAHORA_FIM_PREVISTA'], 'd.m.Y H:i:s');
			$gravar = true;
		}

		if ($gravar == true)
			_29012::gravarWorkflowItemTarefaTempoEfetuado($tarefaCorrente, $this->con);
	}

	/**
	 * Reenviar notificação após recalcular o tempo.
	 * @param array $tarefaCorrente
	 * @param integer $situacao
	 * @param array $workflow
	 * @param object $_29011Ctrl
	 * @param boolean $ehTarefaAtual
	 */
	public function reenviarNotificacao($tarefaCorrente, $situacao, $workflow, $_29011Ctrl, $ehTarefaAtual) {

		_29012::excluirNotificacaoWorkflowItemTarefa($tarefaCorrente, $this->con);

		// Se a tarefa atual estiver sendo concluída, não será agendada notificação para ela.
		if ( $ehTarefaAtual && $situacao == 3 )
			return false;

		$_29011Ctrl->gravarWorkflowItemGestorNotificacao($tarefaCorrente, $workflow, $this->con);

		foreach ($tarefaCorrente['DESTINATARIO'] as $destinatario) {

			if (!$ehTarefaAtual)
				$_29011Ctrl->gravarWorkflowItemDestinatarioNotificacaoAntecipada($tarefaCorrente, $destinatario, $workflow, $this->con);

			$_29011Ctrl->gravarWorkflowItemDestinatarioNotificacaoExpiracao($tarefaCorrente, $destinatario, $workflow, $this->con);
		}

		if (isset($tarefaCorrente['NOTIFICADO']) && count($tarefaCorrente['NOTIFICADO']) > 0) {

			foreach($tarefaCorrente['NOTIFICADO'] as $notificado) {

				$_29011Ctrl->gravarWorkflowItemNotificadoNotificacao($tarefaCorrente, $notificado, $workflow, $this->con);
			}
		}
	}

	/**
	 * Enviar notificação de tarefa concluída.
	 *
	 * @param json $param
	 * @return \Illuminate\Http\Response
	 */
	public function enviarNotificacao($param) {

		// Se a tarefa estiver sendo concluída.
		if ($param->SITUACAO == '3') {

			foreach ($param->TAREFA as $trf) {
				
				// Pular tarefa atual e tarefas concluídas.
				if ( ($trf->ID != $param->TAREFA_ID) && ($trf->STATUS_CONCLUSAO != '3') ) {

					// Se a tarefa for de mesma sequência ou se for a próxima sequência.
					if ( ($trf->SEQUENCIA == $param->TAREFA_ATUAL->SEQUENCIA) || (intval($param->TAREFA_ATUAL->SEQUENCIA)+1 == intval($trf->SEQUENCIA)) ) {

						foreach ($trf->DESTINATARIO as $destin) {

							$this->enviarNotificacaoAoProxDestinatario($trf, $destin, $param->INFO_GERAL);
						}

						break;
					}
				}
			}
		}

		// Se a tarefa estiver sendo reprovada.
		if ($param->SITUACAO == '4') {

			foreach ($param->TAREFA as $trf) {
			
				// Reprova da tarefa atual à tarefa marcada como ponto de retorno.
				if ( ($trf->ORDEM >= $param->TAREFA_ATUAL->PONTO_REPROVACAO) && ($trf->ORDEM <= $param->TAREFA_ATUAL->ORDEM) ) {

					foreach ($trf->DESTINATARIO as $destin)
						$this->enviarNotificacaoAoDestinatario($trf, $destin, $param->INFO_GERAL);

					if ( !empty($trf->NOTIFICADO) )
						foreach ($trf->NOTIFICADO as $notif)
							$this->enviarNotificacaoAoUsuarioNotificado($trf, $notif, $param->SITUACAO, $param->INFO_GERAL);
				}
			}
		}

		// Se a tarefa estiver sendo reativada, iniciada ou concluída.
		if ($param->SITUACAO == '0' || $param->SITUACAO == '1' || $param->SITUACAO == '3') {

			foreach ($param->TAREFA as $trf) {

				// Tarefa atual.
				if ( $trf->ID == $param->TAREFA_ID ) {

					foreach ($trf->NOTIFICADO as $notif) {

						$this->enviarNotificacaoAoUsuarioNotificado($trf, $notif, $param->SITUACAO, $param->INFO_GERAL);
					}

					break;
				}
			}
		}
	}

	/**
	 * Enviar notificação de tarefa concluída para o próximo destinatário por e-mail.
	 *
	 * @param json $tarefa
	 * @param json $destinatario
	 * @return \Illuminate\Http\Response
	 */
	public function enviarNotificacaoAoProxDestinatario($tarefa, $destinatario, $infoGeral) {

		$obj = new Email();
		
		$workflowId = str_pad($tarefa->WORKFLOW_ITEM_ID, 5, 0, STR_PAD_LEFT);
		$tarefaId   = str_pad($tarefa->ID, 5, 0, STR_PAD_LEFT);

		$msg  = "A tarefa a seguir est&aacute; &agrave; sua espera: <br><br>";
		$msg .= "<b>Workflow:</b><br>". $workflowId ." - ". $infoGeral->TITULO ."<br><br>";
		$msg .= "<b>Tarefa:</b><br>". $tarefaId ." - ". $tarefa->TITULO ."<br><br>";
		$msg .= "<b>Descrição:</b><br>". $tarefa->DESCRICAO;

		$obj->setEmail($destinatario->EMAIL);
		$obj->setUsuarioId( \Auth::user()->CODIGO );
		$obj->setMensagem($msg);
		$obj->setUrl(env('URL_PRINCIPAL'));
		$obj->setAssunto('Delfa GC - Workflow '. $workflowId .' - Tarefa em espera.');
		$obj->setCorpo( env('URL_PRINCIPAL') .'/_29012?workflowItemId='. $tarefa->WORKFLOW_ITEM_ID .'&tarefaId='. $tarefa->ID );
		$obj->setStatus('1');
		$obj->setDatahora(date('d.m.Y H:i:s'));
		$obj->setCodigo(3); //template

		return Response::json(Email::gravar($obj));

	}

	/**
	 * Enviar notificação de tarefa reprovada para o destinatário por e-mail.
	 *
	 * @param json $tarefa
	 * @param json $destinatario
	 * @return \Illuminate\Http\Response
	 */
	public function enviarNotificacaoAoDestinatario($tarefa, $destinatario, $infoGeral) {

		$obj = new Email();
		
		$workflowId = str_pad($tarefa->WORKFLOW_ITEM_ID, 5, 0, STR_PAD_LEFT);
		$tarefaId   = str_pad($tarefa->ID, 5, 0, STR_PAD_LEFT);

		$msg  = "A tarefa a seguir foi reprovada: <br><br>";
		$msg .= "<b>Workflow:</b><br>". $workflowId ." - ". $infoGeral->TITULO ."<br><br>";
		$msg .= "<b>Tarefa:</b><br>". $tarefaId ." - ". $tarefa->TITULO ."<br><br>";
		$msg .= "<b>Descrição:</b><br>". $tarefa->DESCRICAO;

		$obj->setEmail($destinatario->EMAIL);
		$obj->setUsuarioId( \Auth::user()->CODIGO );
		$obj->setMensagem($msg);
		$obj->setUrl(env('URL_PRINCIPAL'));
		$obj->setAssunto('Delfa GC - Workflow '. $workflowId .' - Tarefa reprovada.');
		$obj->setCorpo( env('URL_PRINCIPAL') .'/_29012?workflowItemId='. $tarefa->WORKFLOW_ITEM_ID .'&tarefaId='. $tarefa->ID );
		$obj->setStatus('1');
		$obj->setDatahora(date('d.m.Y H:i:s'));
		$obj->setCodigo(3); //template

		return Response::json(Email::gravar($obj));

	}

	/**
	 * Enviar notificação de alteração de status da tarefa por e-mail para os usuários listados para serem notificados.
	 *
	 * @param json $tarefa
	 * @param json $notificado
	 * @return \Illuminate\Http\Response
	 */
	public function enviarNotificacaoAoUsuarioNotificado($tarefa, $notificado, $situacao, $infoGeral) {
		
		$obj = new Email();
		
		$workflowId = str_pad($tarefa->WORKFLOW_ITEM_ID, 5, 0, STR_PAD_LEFT);
		$tarefaId   = str_pad($tarefa->ID, 5, 0, STR_PAD_LEFT);
		
		if ($situacao == '0')
			$situacaoDesc = 'reativada';
		else if ($situacao == '1')
			$situacaoDesc = 'iniciada';
		else if ($situacao == '3')
			$situacaoDesc = 'concluída';
		else if ($situacao == '4')
			$situacaoDesc = 'reprovada';

		$msg  = "A tarefa a seguir foi ". $situacaoDesc .": <br><br>";
		$msg .= "<b>Workflow:</b><br>". $workflowId ." - ". $infoGeral->TITULO ."<br><br>";
		$msg .= "<b>Tarefa:</b><br>". $tarefaId ." - ". $tarefa->TITULO ."<br><br>";
		$msg .= "<b>Descrição:</b><br>". $tarefa->DESCRICAO;

		$obj->setEmail($notificado->EMAIL);
		$obj->setUsuarioId( \Auth::user()->CODIGO );
		$obj->setMensagem($msg);
		$obj->setUrl(env('URL_PRINCIPAL'));
		$obj->setAssunto('Delfa GC - Workflow '. $workflowId .' - Tarefa '. $situacaoDesc .'.');
		$obj->setCorpo( env('URL_PRINCIPAL') .'/_29012?workflowItemId='. $tarefa->WORKFLOW_ITEM_ID .'&tarefaId='. $tarefa->ID );
		$obj->setStatus('1');
		$obj->setDatahora(date('d.m.Y H:i:s'));
		$obj->setCodigo(3); //template

		return Response::json(Email::gravar($obj));

	}

	/**
	 * Gravar arquivo do destinatário da tarefa do item de workflow.
	 *
	 * @param Request $request
	 */
	public function gravarWorkflowItemArquivoDoDestinatario(Request $request) {

		$tarefa = $request->all();

		foreach($tarefa['ARQUIVO_DESTINATARIO'] as $arquivo) {

			// Se algum arquivo foi escolhido 
			// e se não existe ID do Arquivo, o que significa que ele ainda não foi gravado.
			if ( $arquivo['BINARIO'] != 'null' && array_key_exists('ID', $arquivo) == false ) {

				$arquivo['VINCULO'] = $tarefa['TAREFA_ID'];
				ArquivoController::gravarArquivo($arquivo);
			}
		}

		// Arquivos para excluir.
		if (isset($tarefa['ARQUIVO_DESTINATARIO_EXCLUIR'])) {
		 
			foreach($tarefa['ARQUIVO_DESTINATARIO_EXCLUIR'] as $arquivo) {

				ArquivoController::excluir($arquivo['ID']);
			}
		}

		return Response::json($this->consultarWorkflowItemTarefaArquivoPorTarefa($tarefa));
	}

	/**
	 * Gravar comentário da tarefa do item de workflow.
	 *
	 * @param Request $request
	 */
	public function gravarWorkflowItemTarefaComentario(Request $request) {

		$this->con = new _Conexao();

		try {

			$param = json_decode(json_encode($request->all()));

			foreach ($param->COMENTARIO as $comentario) {

				if ( !empty($comentario->COMENTARIO) ) {

					$comentario->ID                      = !empty($comentario->ID) ? $comentario->ID : 0;
					$comentario->STATUSEXCLUSAO          = !empty($comentario->STATUSEXCLUSAO) ? $comentario->STATUSEXCLUSAO : 0;
					$comentario->WORKFLOW_ID             = $param->WORKFLOW_ID;
					$comentario->WORKFLOW_ITEM_ID        = $param->WORKFLOW_ITEM_ID;
					$comentario->WORKFLOW_ITEM_TAREFA_ID = $param->WORKFLOW_ITEM_TAREFA_ID;
					$comentario->USUARIO_ID              = $param->USUARIO_ID;

					_29012::gravarWorkflowItemTarefaComentario($comentario, $this->con);
				}
			}

			$comentarioRet = _29012::consultarWorkflowItemTarefaComentarioPorTarefa($param, $this->con);

			$this->con->commit();

			return Response::json($comentarioRet);
		}
		catch(Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}

	/**
	 * Gravar campos dinâmicos da tarefa do item de workflow.
	 *
	 * @param Request $request
	 */
	public function gravarWorkflowItemTarefaCampo(Request $request) {

		$this->con = new _Conexao();

		try {

			$param = json_decode(json_encode($request->all()));

			foreach ($param->CAMPO as $campo) {

				$campo->VALOR = ($campo->VALOR == 'null') ? null : $campo->VALOR;
				_29012::gravarWorkflowItemTarefaCampo($campo, $this->con);
			}

			$this->con->commit();
		}
		catch(Exception $e) {
			$this->con->rollback();
			throw $e;
		}
	}
}