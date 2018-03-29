<?php

namespace App\Models\DAO\Workflow;

use App\Models\Conexao\_Conexao;

/**
 * DAO do objeto _29010 - Cadastro de Workflow
 */
class _29010DAO {

    /**
     * Consultar workflows cadastrados.
     *
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarWorkflow($param, $con) {
        
        $sql = "
            SELECT 
                W.ID,
                W.TITULO,
                W.DESCRICAO, 
                TRIM(W.STATUS) STATUS
                
            FROM 
                TBWORKFLOW W
                
            WHERE
                W.STATUSEXCLUSAO = '0'
            AND IIF(:STATUS_0 IS NULL, 1=1, W.STATUS = :STATUS_1)
            AND IIF(:USUARIO_ID_0 IS NULL, 1=1, W.USUARIO_ID = :USUARIO_ID_1)
        ";

        $args = [
            ':STATUS_0'     => $param->STATUS,
            ':STATUS_1'     => $param->STATUS,
            ':USUARIO_ID_0' => $param->USUARIO_ID,
            ':USUARIO_ID_1' => $param->USUARIO_ID
        ];

        return $con->query($sql, $args);
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
        
        $sql = "
            SELECT 
                WT.ID,
                WT.WORKFLOW_ID,
                WT.TITULO,
                WT.DESCRICAO,
                WT.SEQUENCIA,
                TRIM(WT.STATUS) STATUS,
                WT.TEMPO_PREVISTO,
                WT.ORDEM,
                WT.PONTO_REPROVACAO,
                TRIM(WT.STATUSEXCLUSAO) STATUSEXCLUSAO
                
            FROM 
                TBWORKFLOW_TAREFA WT
                
            WHERE
                WT.STATUSEXCLUSAO = '0'
            AND WT.WORKFLOW_ID = :WORKFLOW_ID
        ";

        $args = [
            ':WORKFLOW_ID' => $param->WORKFLOW_ID
        ];

        return $con->query($sql, $args);
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
        
        $sql = "
            SELECT
                WD.ID,
                WD.WORKFLOW_ID,
                WD.WORKFLOW_TAREFA_ID,
                WD.USUARIO_ID,
                TRIM(WD.STATUSEXCLUSAO) STATUSEXCLUSAO,
                U.USUARIO,
                U.NOME,
                U.SETOR,
                U.EMAIL
                
            FROM 
                TBWORKFLOW_DESTINATARIO WD
                LEFT JOIN TBUSUARIO U ON U.CODIGO = WD.USUARIO_ID
                
            WHERE
                WD.STATUSEXCLUSAO = '0'
            AND WD.WORKFLOW_ID = :WORKFLOW_ID
        ";

        $args = [
            ':WORKFLOW_ID' => $param->WORKFLOW_ID
        ];

        return $con->query($sql, $args);
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
        
        $sql = "
            SELECT
                WC.ID,
                WC.WORKFLOW_ID,
                WC.WORKFLOW_TAREFA_ID,
                WC.ROTULO,
                TRIM(WC.TIPO) TIPO,
                TRIM(WC.STATUSEXCLUSAO) STATUSEXCLUSAO
                
            FROM 
                TBWORKFLOW_CAMPO WC
                
            WHERE
                WC.STATUSEXCLUSAO = '0'
            AND WC.WORKFLOW_ID = :WORKFLOW_ID
        ";

        $args = [
            ':WORKFLOW_ID' => $param->WORKFLOW_ID
        ];

        return $con->query($sql, $args);
    }

    /**
     * Consultar arquivos das tarefas.
     * @param json $param
     * @param _Conexao $conFile
     * @return array
     */
    public static function consultarTarefaArquivo($param, $conFile) {

        $sql = "
            SELECT
                A.ID, 
                A.ARQUIVO,
                A.CONTEUDO,
                A.TAMANHO,
                A.EXTENSAO
            FROM 
                TBARQUIVO A
                INNER JOIN TBVINCULO V ON V.ARQUIVO_ID = A.ID
            WHERE 
                V.TABELA = 'TBWORKFLOW_TAREFA'
            AND V.TABELA_ID = :TABELA_ID
        ";

        $args = [
            ':TABELA_ID' => $param->ID
        ];

        return $conFile->query($sql, $args);
    }


    /**
     * Consultar workflow.
     * Para consultas a partir de outros objetos através de componente (modal).
     * @param _Conexao $con
     * @return array
     */
    public static function consultar($con) {
        
        $sql = "
            SELECT 
                W.ID,
                W.TITULO,
                W.DESCRICAO
                
            FROM 
                TBWORKFLOW W
                
            WHERE
                W.STATUSEXCLUSAO = '0'
            AND W.STATUS = '1'
        ";

        return $con->query($sql);
    }

    public static function gerarIdWorkflow($con) {

        $sql = 'SELECT GEN_ID(GTBWORKFLOW, 1) ID FROM RDB$DATABASE';

        return $con->query($sql)[0]->ID;
    }

    public static function gerarIdWorkflowTarefa($con) {

        $sql = 'SELECT GEN_ID(GTBWORKFLOW_TAREFA, 1) ID FROM RDB$DATABASE';

        return $con->query($sql)[0]->ID;
    }

    public static function gravarWorkflow($param, $con) {

        $sql = "
            UPDATE OR INSERT INTO TBWORKFLOW (
                ID,
                TITULO, 
                DESCRICAO, 
                USUARIO_ID, 
                STATUS
            ) 
            VALUES (
                :ID,
                :TITULO, 
                :DESCRICAO, 
                :USUARIO_ID, 
                :STATUS
            )
            MATCHING (ID)
        ";

        $args = [
            ':ID'           => $param['ID'],
            ':TITULO'       => $param['TITULO'],
            ':DESCRICAO'    => $param['DESCRICAO'],
            ':USUARIO_ID'   => $param['USUARIO_ID'],
            ':STATUS'       => $param['STATUS']
        ];

        $con->execute($sql, $args);
    }

    public static function gravarWorkflowTarefa($param, $con) {

        $sql = "
            UPDATE OR INSERT INTO TBWORKFLOW_TAREFA (
                ID,
                WORKFLOW_ID, 
                TITULO,
                DESCRICAO,
                SEQUENCIA, 
                STATUS,
                TEMPO_PREVISTO,
                ORDEM,
                PONTO_REPROVACAO,
                STATUSEXCLUSAO
            ) 
            VALUES (
                :ID,
                :WORKFLOW_ID,
                :TITULO,
                :DESCRICAO,
                :SEQUENCIA,
                :STATUS,
                :TEMPO_PREVISTO,
                :ORDEM,
                :PONTO_REPROVACAO,
                :STATUSEXCLUSAO
            )
            MATCHING (ID)
        ";

        $args = [
            ':ID'               => $param['ID'],
            ':WORKFLOW_ID'      => $param['WORKFLOW_ID'],
            ':TITULO'           => $param['TITULO'],
            ':DESCRICAO'        => $param['DESCRICAO'],
            ':SEQUENCIA'        => $param['SEQUENCIA'],
            ':STATUS'           => $param['STATUS'],
            ':TEMPO_PREVISTO'   => $param['TEMPO_PREVISTO'],
            ':ORDEM'            => $param['ORDEM'],
            ':PONTO_REPROVACAO' => $param['PONTO_REPROVACAO'],
            ':STATUSEXCLUSAO'   => $param['STATUSEXCLUSAO']
        ];

        $con->execute($sql, $args);
    }

    public static function gravarWorkflowDestinatario($param, $con) {

        $sql = "
            UPDATE OR INSERT INTO TBWORKFLOW_DESTINATARIO (
                ID, 
                WORKFLOW_ID, 
                WORKFLOW_TAREFA_ID, 
                USUARIO_ID,
                STATUSEXCLUSAO
            ) VALUES (
                :ID, 
                :WORKFLOW_ID, 
                :WORKFLOW_TAREFA_ID, 
                :USUARIO_ID,
                :STATUSEXCLUSAO
            )
            MATCHING (ID)
        ";

        $args = [
            ':ID'                   => $param['ID'],
            ':WORKFLOW_ID'          => $param['WORKFLOW_ID'],
            ':WORKFLOW_TAREFA_ID'   => $param['WORKFLOW_TAREFA_ID'],
            ':USUARIO_ID'           => $param['USUARIO_ID'],
            ':STATUSEXCLUSAO'       => $param['STATUSEXCLUSAO']
        ];

        $con->execute($sql, $args);
    }

    public static function gravarWorkflowCampo($param, $con) {

        $sql = "
            UPDATE OR INSERT INTO TBWORKFLOW_CAMPO (
                ID, 
                WORKFLOW_ID, 
                WORKFLOW_TAREFA_ID, 
                ROTULO,
                TIPO,
                STATUSEXCLUSAO
            ) VALUES (
                :ID, 
                :WORKFLOW_ID, 
                :WORKFLOW_TAREFA_ID, 
                :ROTULO,
                :TIPO,
                :STATUSEXCLUSAO
            )
            MATCHING (ID)
        ";

        $args = [
            ':ID'                   => $param['ID'],
            ':WORKFLOW_ID'          => $param['WORKFLOW_ID'],
            ':WORKFLOW_TAREFA_ID'   => $param['WORKFLOW_TAREFA_ID'],
            ':ROTULO'               => $param['ROTULO'],
            ':TIPO'                 => $param['TIPO'],
            ':STATUSEXCLUSAO'       => $param['STATUSEXCLUSAO']
        ];

        $con->execute($sql, $args);
    }
	
    public static function excluirWorkflow($param, $con) {

        $sql = "
            UPDATE TBWORKFLOW 
            SET STATUSEXCLUSAO = '1'
            WHERE ID = :WORKFLOW_ID
        ";

        $args = [
            ':WORKFLOW_ID' => $param->WORKFLOW_ID
        ];

        $con->execute($sql, $args);
    }

    public static function excluirWorkflowTarefa($param, $con) {

        $sql = "
            UPDATE TBWORKFLOW_TAREFA 
            SET STATUSEXCLUSAO = '1'
            WHERE WORKFLOW_ID = :WORKFLOW_ID
        ";

        $args = [
            ':WORKFLOW_ID' => $param->WORKFLOW_ID
        ];

        $con->execute($sql, $args);
    }

    public static function excluirWorkflowDestinatario($param, $con) {

        $sql = "
            UPDATE TBWORKFLOW_DESTINATARIO
            SET STATUSEXCLUSAO = '1'
            WHERE WORKFLOW_ID = :WORKFLOW_ID
        ";

        $args = [
            ':WORKFLOW_ID' => $param->WORKFLOW_ID
        ];

        $con->execute($sql, $args);
    }

    public static function gravarEmailUsuario($param, $con) {

         $sql = "
            UPDATE TBUSUARIO
            SET EMAIL = :EMAIL
            WHERE CODIGO = :USUARIO_ID
        ";

        $args = [
            ':EMAIL'        => $param->EMAIL,
            ':USUARIO_ID'   => $param->USUARIO_ID
        ];

        $con->execute($sql, $args);
    }
}