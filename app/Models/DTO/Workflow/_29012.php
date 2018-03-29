<?php

namespace App\Models\DTO\Workflow;

use App\Models\DAO\Workflow\_29012DAO;

/**
 * Objeto _29012 - Workflow
 */
class _29012
{
	/**
	 * Consultar itens de workflow por usuário.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarWorkflowItem($param, $con) {
		return _29012DAO::consultarWorkflowItem($param, $con);
	}

	/**
	 * Consultar tarefas do item de workflow por usuário.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarWorkflowItemTarefa($param, $con) {
		return _29012DAO::consultarWorkflowItemTarefa($param, $con);
	}

	/**
	 * Consultar destinatários do item de workflow por usuário.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarWorkflowItemTarefaDestinatario($param, $con) {
		return _29012DAO::consultarWorkflowItemTarefaDestinatario($param, $con);
	}

	/**
	 * Consultar notificados das tarefas do item de workflow por usuário.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarWorkflowItemTarefaNotificado($param, $con) {
		return _29012DAO::consultarWorkflowItemTarefaNotificado($param, $con);
	}

	/**
	 * Consultar notificados das tarefas do item de workflow por usuário.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarWorkflowItemTarefaCampo($param, $con) {
		return _29012DAO::consultarWorkflowItemTarefaCampo($param, $con);
	}

	/**
	 * Consultar comentários da tarefa do item de workflow por usuário.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarWorkflowItemTarefaComentario($param, $con) {
		return _29012DAO::consultarWorkflowItemTarefaComentario($param, $con);
	}

	/**
	 * Consultar comentários da tarefa do item de workflow por usuário por tarefa.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarWorkflowItemTarefaComentarioPorTarefa($param, $con) {
		return _29012DAO::consultarWorkflowItemTarefaComentarioPorTarefa($param, $con);
	}

	/**
	 * Consultar movimentações do item de workflow por usuário.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarWorkflowItemTarefaMovimentacao($param, $con) {
		return _29012DAO::consultarWorkflowItemTarefaMovimentacao($param, $con);
	}

	/**
	 * Consultar movimentações de determinada tarefa do item de workflow.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarWorkflowItemTarefaMovimentacaoPorTarefa($param, $con) {
		return _29012DAO::consultarWorkflowItemTarefaMovimentacaoPorTarefa($param, $con);
	}

	/**
	 * Consultar progresso de determinado item de workflow.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarWorkflowItemProgresso($param, $con) {
		return _29012DAO::consultarWorkflowItemProgresso($param, $con);
	}

	/**
	 * Consultar arquivos do item de workflow por usuário.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarWorkflowItemTarefaArquivo($param, $con) {
		return _29012DAO::consultarWorkflowItemTarefaArquivo($param, $con);
	}

	/**
	 * Consultar arquivos de uma tarefa do item de workflow.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarWorkflowItemTarefaArquivoPorTarefa($param, $con) {
		return _29012DAO::consultarWorkflowItemTarefaArquivoPorTarefa($param, $con);
	}
	
	/**
	 * Alterar situação da tarefa do item de workflow.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function alterarSituacaoWorkflowItemTarefa($param, $con) {
		return _29012DAO::alterarSituacaoWorkflowItemTarefa($param, $con);
	}

	/**
	 * Gravar tempo recalculado (efetuado) da tarefa do item de workflow.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function gravarWorkflowItemTarefaTempoEfetuado($param, $con) {
		return _29012DAO::gravarWorkflowItemTarefaTempoEfetuado($param, $con);
	}

	/**
	 * Excluir notificações da tarefa do item de workflow.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function excluirNotificacaoWorkflowItemTarefa($param, $con) {
		return _29012DAO::excluirNotificacaoWorkflowItemTarefa($param, $con);
	}

	/**
	 * Gravar comentário da tarefa do item de workflow.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function gravarWorkflowItemTarefaComentario($param, $con) {
		return _29012DAO::gravarWorkflowItemTarefaComentario($param, $con);
	}
	
	/**
	 * Gravar campos dinâmicos da tarefa do item de workflow.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function gravarWorkflowItemTarefaCampo($param, $con) {
		return _29012DAO::gravarWorkflowItemTarefaCampo($param, $con);
	}

}