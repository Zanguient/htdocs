<?php

namespace App\Models\DTO\Workflow;

use App\Models\DAO\Workflow\_29013DAO;

/**
 * Objeto _29013 - Painel com cronograma das tarefas
 */
class _29013
{
	/**
     * Consultar itens de workflow por usuário.
     * @param json $param
     * @param _Conexao $con
     * @return json
     */
    public static function consultarWorkflowItem($param, $con) {
    	return _29013DAO::consultarWorkflowItem($param, $con);
    }

    /**
     * Consultar tarefa de itens de workflow por usuário.
     * @param json $param
     * @param _Conexao $con
     * @return json
     */
    public static function consultarWorkflowItemTarefa($param, $con) {
    	return _29013DAO::consultarWorkflowItemTarefa($param, $con);
    }
}