<?php

namespace App\Models\DTO\Workflow;

use App\Models\DAO\Workflow\_29011DAO;

/**
 * Objeto _29011 - Cadastro de item de workflow
 */
class _29011
{
	
	/**
	 * Consultar itens de workflow.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarItem($param, $con) {
		return _29011DAO::consultarItem($param, $con);
	}

	/**
	 * Consultar tarefas do item de workflow.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarItemTarefa($param, $con) {
		return _29011DAO::consultarItemTarefa($param, $con);
	}

	/**
	 * Consultar destinatários das tarefas do item de workflow.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarItemTarefaDestinatario($param, $con) {
		return _29011DAO::consultarItemTarefaDestinatario($param, $con);
	}

	/**
	 * Consultar notificados das tarefas do item de workflow.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarItemTarefaNotificado($param, $con) {
		return _29011DAO::consultarItemTarefaNotificado($param, $con);
	}

	/**
	 * Consultar campos dinâmicos das tarefas do item de workflow.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarItemTarefaCampo($param, $con) {
		return _29011DAO::consultarItemTarefaCampo($param, $con);
	}

	/**
	 * Consultar arquivos das tarefas do item de workflow.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarItemTarefaArquivo($param, $con) {
		return _29011DAO::consultarItemTarefaArquivo($param, $con);
	}

	/**
	 * Consultar tarefas do workflow.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarWorkflowTarefa($param, $con) {
		return _29011DAO::consultarWorkflowTarefa($param, $con);
	}

	/**
	 * Consultar destinatários das tarefas do workflow.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarTarefaDestinatario($param, $con) {
		return _29011DAO::consultarTarefaDestinatario($param, $con);
	}

	/**
	 * Consultar campos dinâmicos das tarefas do workflow.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarTarefaCampo($param, $con) {
		return _29011DAO::consultarTarefaCampo($param, $con);
	}

	/**
	 * Consultar arquivos das tarefas do workflow.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function consultarWorkflowTarefaArquivo($param, $con) {
		return _29011DAO::consultarWorkflowTarefaArquivo($param, $con);
	}

	/**
	 * Gerar id do item de workflow.
	 * @param _Conexao $con
	 * @return array
	 */
	public static function gerarIdWorkflowItem($con) {
		return _29011DAO::gerarIdWorkflowItem($con);
	}

	/**
	 * Gerar id da tarefa do item de workflow.
	 * @param _Conexao $con
	 * @return array
	 */
	public static function gerarIdWorkflowItemTarefa($con) {
		return _29011DAO::gerarIdWorkflowItemTarefa($con);
	}

	/**
	 * Gravar item de workflow.
	 * @param array $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function gravarWorkflowItem($param, $con) {
		return _29011DAO::gravarWorkflowItem($param, $con);
	}

	/**
	 * Gravar tarefa do item de workflow.
	 * @param array $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function gravarWorkflowItemTarefa($param, $con) {
		return _29011DAO::gravarWorkflowItemTarefa($param, $con);
	}

	/**
	 * Gravar destinatário do item de workflow.
	 * @param array $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function gravarWorkflowItemDestinatario($param, $con) {
		return _29011DAO::gravarWorkflowItemDestinatario($param, $con);
	}

	/**
	 * Gravar notificações da tarefa do item de workflow.
	 * @param array $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function gravarWorkflowItemNotificacao($param, $con) {
		return _29011DAO::gravarWorkflowItemNotificacao($param, $con);
	}

	/**
	 * Gravar notificados do item de workflow.
	 * @param array $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function gravarWorkflowItemNotificado($param, $con) {
		return _29011DAO::gravarWorkflowItemNotificado($param, $con);
	}

	/**
	 * Gravar campos dinâmicos do item de workflow.
	 * @param array $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function gravarWorkflowItemCampo($param, $con) {
		return _29011DAO::gravarWorkflowItemCampo($param, $con);
	}

	/**
	 * Encerrar item de workflow.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function encerrarWorkflowItem($param, $con) {
		return _29011DAO::encerrarWorkflowItem($param, $con);
	}

	/**
	 * Excluir item de workflow.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function excluirWorkflowItem($param, $con) {
		return _29011DAO::excluirWorkflowItem($param, $con);
	}

	/**
	 * Excluir tarefas do item de workflow.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function excluirWorkflowItemTarefa($param, $con) {
		return _29011DAO::excluirWorkflowItemTarefa($param, $con);
	}

	/**
	 * Excluir destinatários do item de workflow.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function excluirWorkflowItemTarefaDestinatario($param, $con) {
		return _29011DAO::excluirWorkflowItemTarefaDestinatario($param, $con);
	}

	/**
	 * Excluir notificados da tarefa do item de workflow.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function excluirWorkflowItemTarefaNotificado($param, $con) {
		return _29011DAO::excluirWorkflowItemTarefaNotificado($param, $con);
	}

	/**
	 * Excluir notificações da tarefa do item de workflow.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
    public static function excluirWorkflowItemTarefaNotificacao($param, $con) {
        return _29011DAO::excluirWorkflowItemTarefaNotificacao($param, $con);
    }
}