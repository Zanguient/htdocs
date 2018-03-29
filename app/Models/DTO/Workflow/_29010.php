<?php

namespace App\Models\DTO\Workflow;

use App\Models\DAO\Workflow\_29010DAO;

/**
 * Objeto _29010 - Cadastro de Workflow
 */
class _29010
{
    /**
     * Consultar workflows cadastrados.
     *
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
	public static function consultarWorkflow($param, $con) {
		return _29010DAO::consultarWorkflow($param, $con);
	}

    /**
     * Consultar workflows cadastrados.
     * Para consultas a partir de outros objetos através de componente (modal).
     *
     * @param _Conexao $con
     * @return array
     */
    public static function consultar($con) {
        return _29010DAO::consultar($con);
    }

    /**
     * Consultar tarefas de determinado workflow.
     *
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarTarefa($param, $con) {
        return _29010DAO::consultarTarefa($param, $con);
    }

    /**
     * Consultar destinatários das tarefas de determinado workflow.
     *
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarTarefaDestinatario($param, $con) {
        return _29010DAO::consultarTarefaDestinatario($param, $con);
    }

    /**
     * Consultar campos dinâmicos das tarefas de determinado workflow.
     *
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarTarefaCampo($param, $con) {
        return _29010DAO::consultarTarefaCampo($param, $con);
    }

    /**
     * Consultar arquivos das tarefas.
     *
     * @access public
     * @param json $param
     * @param _Conexao $conFile
     * @return array
     */
    public static function consultarTarefaArquivo($param, $conFile) {
        return _29010DAO::consultarTarefaArquivo($param, $conFile);
    }

    public static function gerarIdWorkflow($con) {
        return _29010DAO::gerarIdWorkflow($con);
    }

    public static function gerarIdWorkflowTarefa($con) {
        return _29010DAO::gerarIdWorkflowTarefa($con);
    }

    public static function gravarWorkflow($param, $con) {
        return _29010DAO::gravarWorkflow($param, $con);
    }

    public static function gravarWorkflowTarefa($param, $con) {
        return _29010DAO::gravarWorkflowTarefa($param, $con);
    }

    public static function gravarWorkflowDestinatario($param, $con) {
        return _29010DAO::gravarWorkflowDestinatario($param, $con);
    }

    public static function gravarWorkflowCampo($param, $con) {
        return _29010DAO::gravarWorkflowCampo($param, $con);
    }

    public static function excluirWorkflow($param, $con) {
        return _29010DAO::excluirWorkflow($param, $con);
    }

    public static function excluirWorkflowTarefa($param, $con) {
        return _29010DAO::excluirWorkflowTarefa($param, $con);
    }

    public static function excluirWorkflowDestinatario($param, $con) {
        return _29010DAO::excluirWorkflowDestinatario($param, $con);
    }

    public static function gravarEmailUsuario($param, $con) {
        return _29010DAO::gravarEmailUsuario($param, $con);
    }
}