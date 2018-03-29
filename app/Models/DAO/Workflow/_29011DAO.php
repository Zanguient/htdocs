<?php

namespace App\Models\DAO\Workflow;

/**
 * DAO do objeto _29011 - Cadastro de item de workflow
 */
class _29011DAO {

	/**
     * Consultar itens de workflow.
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarItem($param, $con) {
        
        $sql = "
            SELECT 
                WI.ID,
                WI.WORKFLOW_ID,
                
                (SELECT FIRST 1 W.TITULO 
                    FROM TBWORKFLOW W 
                    WHERE W.ID = WI.WORKFLOW_ID
                ) WORKFLOW_TITULO,

                WI.TITULO,
                WI.DESCRICAO,
                WI.PROGRESSO,
                TRIM(WI.STATUS_CONCLUSAO) STATUS_CONCLUSAO,
                WI.DATAHORA_INI_PREVISTA,
                WI.DATAHORA_FIM_PREVISTA,
                WI.DATAHORA_INI,
                WI.DATAHORA_FIM
                
            FROM 
                TBWORKFLOW_ITEM WI
                
            WHERE
                WI.STATUSEXCLUSAO = '0'
            AND IIF(:STATUS_CONCLUSAO_0 IS NULL, 1=1, WI.STATUS_CONCLUSAO = :STATUS_CONCLUSAO_1)
            AND IIF(:WORKFLOW_ITEM_ID_0 = 0, WI.DATAHORA_INSERT BETWEEN :DATA_INI AND :DATA_FIM, WI.ID = :WORKFLOW_ITEM_ID_1)
            AND IIF(:USUARIO_ID_0 IS NULL, 1=1, WI.USUARIO_ID = :USUARIO_ID_1)
        ";

        $args = [
            ':STATUS_CONCLUSAO_0'   => $param->STATUS_CONCLUSAO,
            ':STATUS_CONCLUSAO_1'   => $param->STATUS_CONCLUSAO,
            ':DATA_INI'             => $param->DATA_INI,
            ':DATA_FIM'             => $param->DATA_FIM,
            ':WORKFLOW_ITEM_ID_0'   => !empty($param->WORKFLOW_ITEM_ID) ? $param->WORKFLOW_ITEM_ID : 0,
            ':WORKFLOW_ITEM_ID_1'   => !empty($param->WORKFLOW_ITEM_ID) ? $param->WORKFLOW_ITEM_ID : 0,
            ':USUARIO_ID_0'         => $param->USUARIO_ID,
            ':USUARIO_ID_1'         => $param->USUARIO_ID
        ];

        return $con->query($sql, $args);
    }

    /**
     * Consultar tarefas do item de workflow.
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarItemTarefa($param, $con) {
        
        $sql = "
            SELECT 
                WT.ID,
                WT.WORKFLOW_ID,
                WT.WORKFLOW_ITEM_ID,
                WT.TITULO,
                WT.DESCRICAO,
                WT.SEQUENCIA,
                TRIM(WT.STATUS_CONCLUSAO) STATUS_CONCLUSAO,
                WT.USUARIO_CONCLUSAO_ID,
                WT.TEMPO_PREVISTO,
                WT.COMENTARIO,
                TRIM(WT.DOMINGO) DOMINGO,
                TRIM(WT.SEGUNDA) SEGUNDA,
                TRIM(WT.TERCA) TERCA,
                TRIM(WT.QUARTA) QUARTA,
                TRIM(WT.QUINTA) QUINTA,
                TRIM(WT.SEXTA) SEXTA,
                TRIM(WT.SABADO) SABADO,
                WT.HORARIO_PERMITIDO,
                WT.ORDEM,
                WT.PONTO_REPROVACAO
                            
            FROM 
                TBWORKFLOW_ITEM_TAREFA WT
                            
            WHERE
                WT.STATUSEXCLUSAO = '0'
            AND WT.WORKFLOW_ITEM_ID = :WORKFLOW_ITEM_ID
        ";

        $args = [
            ':WORKFLOW_ITEM_ID' => $param->WORKFLOW_ITEM_ID
        ];

        return $con->query($sql, $args);
    }

    /**
     * Consultar destinatários das tarefas do item de workflow.
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarItemTarefaDestinatario($param, $con) {
        
        $sql = "
            SELECT 
                WD.ID,
                WD.WORKFLOW_ID,
                WD.WORKFLOW_ITEM_ID,
                WD.WORKFLOW_ITEM_TAREFA_ID,
                WD.USUARIO_ID,
                U.USUARIO,
                U.NOME,
                U.SETOR,
                U.EMAIL
                            
            FROM 
                TBWORKFLOW_ITEM_DESTINATARIO WD
                LEFT JOIN TBUSUARIO U ON U.CODIGO = WD.USUARIO_ID
                            
            WHERE
                WD.STATUSEXCLUSAO = '0'
            AND WD.WORKFLOW_ITEM_ID = :WORKFLOW_ITEM_ID
        ";

        $args = [
            ':WORKFLOW_ITEM_ID' => $param->WORKFLOW_ITEM_ID
        ];

        return $con->query($sql, $args);
    }

    /**
     * Consultar notificados das tarefas do item de workflow.
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarItemTarefaNotificado($param, $con) {
        
        $sql = "
            SELECT 
                WN.ID,
                WN.WORKFLOW_ID,
                WN.WORKFLOW_ITEM_ID,
                WN.WORKFLOW_ITEM_TAREFA_ID,
                WN.USUARIO_ID,
                U.USUARIO,
                U.NOME,
                U.SETOR,
                U.EMAIL
                            
            FROM 
                TBWORKFLOW_ITEM_NOTIFICADO WN
                LEFT JOIN TBUSUARIO U ON U.CODIGO = WN.USUARIO_ID
                            
            WHERE
                WN.STATUSEXCLUSAO = '0'
            AND WN.WORKFLOW_ITEM_ID = :WORKFLOW_ITEM_ID
        ";

        $args = [
            ':WORKFLOW_ITEM_ID' => $param->WORKFLOW_ITEM_ID
        ];

        return $con->query($sql, $args);
    }

    /**
     * Consultar campos dinâmicos das tarefas de determinado item de workflow.
     *
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarItemTarefaCampo($param, $con) {
        
        $sql = "
            SELECT
                WC.ID,
                WC.WORKFLOW_ID,
                WC.WORKFLOW_ITEM_ID,
                WC.WORKFLOW_ITEM_TAREFA_ID,
                WC.ROTULO,
                TRIM(WC.TIPO) TIPO,
                TRIM(WC.STATUSEXCLUSAO) STATUSEXCLUSAO
                
            FROM 
                TBWORKFLOW_ITEM_CAMPO WC
                
            WHERE
                WC.STATUSEXCLUSAO = '0'
            AND WC.WORKFLOW_ITEM_ID = :WORKFLOW_ITEM_ID
        ";

        $args = [
            ':WORKFLOW_ITEM_ID' => $param->WORKFLOW_ITEM_ID
        ];

        return $con->query($sql, $args);
    }

    /**
     * Consultar arquivos das tarefas do item de workflow.
     * @param json $param
     * @param _Conexao $conFile
     * @return array
     */
    public static function consultarItemTarefaArquivo($param, $conFile) {

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
                V.TABELA = 'TBWORKFLOW_ITEM_TAREFA'
            AND V.TABELA_ID = :TABELA_ID
        ";

        $args = [
            ':TABELA_ID' => $param->ID
        ];

        return $conFile->query($sql, $args);
    }

    /**
     * Consultar tarefas do workflow.
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarWorkflowTarefa($param, $con) {
        
        $sql = "
            SELECT 
                WT.ID,
                WT.WORKFLOW_ID,
                WT.TITULO,
                WT.DESCRICAO,
                WT.SEQUENCIA,
                WT.TEMPO_PREVISTO,
                WT.ORDEM,
                WT.PONTO_REPROVACAO,
                1 DO_MODELO
                            
            FROM 
                TBWORKFLOW_TAREFA WT
                            
            WHERE
                WT.STATUSEXCLUSAO = '0'
            AND WT.STATUS = '1'
            AND WT.WORKFLOW_ID = :WORKFLOW_ID
        ";

        $args = [
            ':WORKFLOW_ID' => $param->WORKFLOW_ID
        ];

        return $con->query($sql, $args);
    }

    /**
     * Consultar destinatários das tarefas do workflow.
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
                U.USUARIO,
                U.NOME,
                U.SETOR,
                1 DO_MODELO
                                        
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
                TRIM(WC.STATUSEXCLUSAO) STATUSEXCLUSAO,
                1 DO_MODELO
                
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
     * Consultar arquivos das tarefas do workflow.
     * @param json $param
     * @param _Conexao $conFile
     * @return array
     */
    public static function consultarWorkflowTarefaArquivo($param, $conFile) {

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
     * Gerar id do item de workflow.
     * @param _Conexao $con
     * @return array
     */
    public static function gerarIdWorkflowItem($con) {

        $sql = 'SELECT GEN_ID(GTBWORKFLOW_ITEM, 1) ID FROM RDB$DATABASE';

        return $con->query($sql)[0]->ID;
    }

    /**
     * Gerar id da tarefa do item de workflow.
     * @param _Conexao $con
     * @return array
     */
    public static function gerarIdWorkflowItemTarefa($con) {

        $sql = 'SELECT GEN_ID(GTBWORKFLOW_ITEM_TAREFA, 1) ID FROM RDB$DATABASE';

        return $con->query($sql)[0]->ID;
    }

    /**
     * Gravar item de workflow.
     * @param array $param
     * @param _Conexao $con
     */
    public static function gravarWorkflowItem($param, $con) {

        $sql = "
            UPDATE OR INSERT INTO TBWORKFLOW_ITEM (
                ID,
                WORKFLOW_ID,
                TITULO, 
                DESCRICAO, 
                USUARIO_ID,
                DATAHORA_INI_PREVISTA,
                DATAHORA_FIM_PREVISTA
            ) 
            VALUES (
                :ID,
                :WORKFLOW_ID,
                :TITULO, 
                :DESCRICAO, 
                :USUARIO_ID,
                :DATAHORA_INI_PREVISTA,
                :DATAHORA_FIM_PREVISTA
            )
            MATCHING (ID)
        ";

        $args = [
            ':ID'                       => $param['ID'],
            ':WORKFLOW_ID'              => $param['WORKFLOW_ID'],
            ':TITULO'                   => $param['TITULO'],
            ':DESCRICAO'                => $param['DESCRICAO'],
            ':USUARIO_ID'               => $param['USUARIO_ID'],
            ':DATAHORA_INI_PREVISTA'    => $param['DATAHORA_INI_PREVISTA'],
            ':DATAHORA_FIM_PREVISTA'    => $param['DATAHORA_FIM_PREVISTA']
        ];

        $con->execute($sql, $args);
    }

    /**
     * Gravar tarefa do item de workflow.
     * @param array $param
     * @param _Conexao $con
     */
    public static function gravarWorkflowItemTarefa($param, $con) {

        $sql = "
            UPDATE OR INSERT INTO TBWORKFLOW_ITEM_TAREFA (
                ID,
                WORKFLOW_ID,
                WORKFLOW_ITEM_ID,
                TITULO,
                DESCRICAO,
                SEQUENCIA,
                TEMPO_PREVISTO,
                DATAHORA_INI_PREVISTA,
                DATAHORA_FIM_PREVISTA,
                DOMINGO,
                SEGUNDA,
                TERCA,
                QUARTA,
                QUINTA,
                SEXTA,
                SABADO,
                HORARIO_PERMITIDO,
                ORDEM,
                PONTO_REPROVACAO,
                STATUSEXCLUSAO
            ) 
            VALUES (
                :ID,
                :WORKFLOW_ID,
                :WORKFLOW_ITEM_ID,
                :TITULO,
                :DESCRICAO,
                :SEQUENCIA,
                :TEMPO_PREVISTO,
                :DATAHORA_INI_PREVISTA,
                :DATAHORA_FIM_PREVISTA,
                :DOMINGO,
                :SEGUNDA,
                :TERCA,
                :QUARTA,
                :QUINTA,
                :SEXTA,
                :SABADO,
                :HORARIO_PERMITIDO,
                :ORDEM,
                :PONTO_REPROVACAO,
                :STATUSEXCLUSAO
            )
            MATCHING (ID)
        ";

        $args = [
            ':ID'                    => $param['ID'],
            ':WORKFLOW_ID'           => $param['WORKFLOW_ID'],
            ':WORKFLOW_ITEM_ID'      => $param['WORKFLOW_ITEM_ID'],
            ':TITULO'                => $param['TITULO'],
            ':DESCRICAO'             => $param['DESCRICAO'],
            ':SEQUENCIA'             => $param['SEQUENCIA'],
            ':TEMPO_PREVISTO'        => $param['TEMPO_PREVISTO'],
            ':DATAHORA_INI_PREVISTA' => date_format($param['DATAHORA_INI_PREVISTA'], 'd.m.Y H:i:s'),
            ':DATAHORA_FIM_PREVISTA' => date_format($param['DATAHORA_FIM_PREVISTA'], 'd.m.Y H:i:s'),
            ':DOMINGO'               => $param['DOMINGO'],
            ':SEGUNDA'               => $param['SEGUNDA'],
            ':TERCA'                 => $param['TERCA'],
            ':QUARTA'                => $param['QUARTA'],
            ':QUINTA'                => $param['QUINTA'],
            ':SEXTA'                 => $param['SEXTA'],
            ':SABADO'                => $param['SABADO'],
            ':HORARIO_PERMITIDO'     => $param['HORARIO_PERMITIDO'],
            ':ORDEM'                 => $param['ORDEM'],
            ':PONTO_REPROVACAO'      => $param['PONTO_REPROVACAO'],
            ':STATUSEXCLUSAO'        => $param['STATUSEXCLUSAO']
        ];

        $con->execute($sql, $args);
    }

    /**
     * Gravar destinatário da tarefa do item de workflow.
     * @param array $param
     * @param _Conexao $con
     */
    public static function gravarWorkflowItemDestinatario($param, $con) {

        $sql = "
            UPDATE OR INSERT INTO TBWORKFLOW_ITEM_DESTINATARIO (
                ID, 
                WORKFLOW_ID, 
                WORKFLOW_ITEM_ID,
                WORKFLOW_ITEM_TAREFA_ID, 
                USUARIO_ID,
                STATUSEXCLUSAO
            ) VALUES (
                :ID, 
                :WORKFLOW_ID, 
                :WORKFLOW_ITEM_ID, 
                :WORKFLOW_ITEM_TAREFA_ID, 
                :USUARIO_ID,
                :STATUSEXCLUSAO
            )
            MATCHING (ID)
        ";

        $args = [
            ':ID'                       => $param['ID'],
            ':WORKFLOW_ID'              => $param['WORKFLOW_ID'],
            ':WORKFLOW_ITEM_ID'         => $param['WORKFLOW_ITEM_ID'],
            ':WORKFLOW_ITEM_TAREFA_ID'  => $param['WORKFLOW_ITEM_TAREFA_ID'],
            ':USUARIO_ID'               => $param['USUARIO_ID'],
            ':STATUSEXCLUSAO'           => $param['STATUSEXCLUSAO']
        ];

        $con->execute($sql, $args);
    }

    /**
     * Gravar notificações da tarefa do item de workflow.
     * @param array $param
     * @param _Conexao $con
     */
    public static function gravarWorkflowItemNotificacao($param, $con) {

        $sql = "
            INSERT INTO TBNOTIFICACAO (
                TIPO, 
                USUARIO_ID, 
                TITULO, 
                MENSAGEM, 
                EMITENTE, 
                AGENDAMENTO,
                TABELA,
                TABELA_ID
            ) VALUES (
                :TIPO, 
                :USUARIO_ID, 
                :TITULO, 
                :MENSAGEM, 
                :EMITENTE, 
                :AGENDAMENTO,
                :TABELA,
                :TABELA_ID
            )
        ";

        $args = [
            ':TIPO'         => $param['TIPO'],
            ':USUARIO_ID'   => $param['USUARIO_ID'],
            ':TITULO'       => $param['TITULO'],
            ':MENSAGEM'     => $param['MENSAGEM'],
            ':EMITENTE'     => $param['EMITENTE'],
            ':AGENDAMENTO'  => $param['AGENDAMENTO'],
            ':TABELA'       => $param['TABELA'],
            ':TABELA_ID'    => $param['TABELA_ID']
        ];

        $con->execute($sql, $args);
    }

    /**
     * Gravar notificados da tarefa do item de workflow.
     * @param array $param
     * @param _Conexao $con
     */
    public static function gravarWorkflowItemNotificado($param, $con) {

        $sql = "
            UPDATE OR INSERT INTO TBWORKFLOW_ITEM_NOTIFICADO (
                ID, 
                WORKFLOW_ID, 
                WORKFLOW_ITEM_ID,
                WORKFLOW_ITEM_TAREFA_ID, 
                USUARIO_ID,
                STATUSEXCLUSAO
            ) VALUES (
                :ID, 
                :WORKFLOW_ID, 
                :WORKFLOW_ITEM_ID, 
                :WORKFLOW_ITEM_TAREFA_ID, 
                :USUARIO_ID,
                :STATUSEXCLUSAO
            )
            MATCHING (ID)
        ";

        $args = [
            ':ID'                       => $param['ID'],
            ':WORKFLOW_ID'              => $param['WORKFLOW_ID'],
            ':WORKFLOW_ITEM_ID'         => $param['WORKFLOW_ITEM_ID'],
            ':WORKFLOW_ITEM_TAREFA_ID'  => $param['WORKFLOW_ITEM_TAREFA_ID'],
            ':USUARIO_ID'               => $param['USUARIO_ID'],
            ':STATUSEXCLUSAO'           => $param['STATUSEXCLUSAO']
        ];

        $con->execute($sql, $args);
    }

    /**
     * Gravar campos dinâmicos da tarefa do item de workflow.
     * @param array $param
     * @param _Conexao $con
     */
    public static function gravarWorkflowItemCampo($param, $con) {

        $sql = "
            UPDATE OR INSERT INTO TBWORKFLOW_ITEM_CAMPO (
                ID, 
                WORKFLOW_ID,
                WORKFLOW_ITEM_ID,
                WORKFLOW_ITEM_TAREFA_ID,
                ROTULO,
                TIPO,
                STATUSEXCLUSAO
            ) VALUES (
                :ID, 
                :WORKFLOW_ID, 
                :WORKFLOW_ITEM_ID,
                :WORKFLOW_ITEM_TAREFA_ID,
                :ROTULO,
                :TIPO,
                :STATUSEXCLUSAO
            )
            MATCHING (ID)
        ";

        $args = [
            ':ID'                       => $param['ID'],
            ':WORKFLOW_ID'              => $param['WORKFLOW_ID'],
            ':WORKFLOW_ITEM_ID'         => $param['WORKFLOW_ITEM_ID'],
            ':WORKFLOW_ITEM_TAREFA_ID'  => $param['WORKFLOW_ITEM_TAREFA_ID'],
            ':ROTULO'                   => $param['ROTULO'],
            ':TIPO'                     => $param['TIPO'],
            ':STATUSEXCLUSAO'           => $param['STATUSEXCLUSAO']
        ];

        $con->execute($sql, $args);
    }

    public static function encerrarWorkflowItem($param, $con) {

        $sql = "
            UPDATE TBWORKFLOW_ITEM WI
            SET 
                WI.STATUS_CONCLUSAO = :STATUS_CONCLUSAO
            WHERE
                WI.ID = :ID
        ";

        $args = [
            ':STATUS_CONCLUSAO' => $param->STATUS_CONCLUSAO,
            ':ID'               => $param->WORKFLOW_ITEM_ID
        ];

        $con->execute($sql, $args);
    }

    public static function excluirWorkflowItem($param, $con) {

        $sql = "
            UPDATE TBWORKFLOW_ITEM 
            SET STATUSEXCLUSAO = '1'
            WHERE ID = :ID
        ";

        $args = [
            ':ID' => $param->WORKFLOW_ITEM_ID
        ];

        $con->execute($sql, $args);
    }

    public static function excluirWorkflowItemTarefa($param, $con) {

        $sql = "
            UPDATE TBWORKFLOW_ITEM_TAREFA 
            SET STATUSEXCLUSAO = '1'
            WHERE WORKFLOW_ITEM_ID = :WORKFLOW_ITEM_ID
        ";

        $args = [
            ':WORKFLOW_ITEM_ID' => $param->WORKFLOW_ITEM_ID
        ];

        $con->execute($sql, $args);
    }

    public static function excluirWorkflowItemTarefaDestinatario($param, $con) {

        $sql = "
            UPDATE TBWORKFLOW_ITEM_DESTINATARIO
            SET STATUSEXCLUSAO = '1'
            WHERE WORKFLOW_ITEM_ID = :WORKFLOW_ITEM_ID
        ";

        $args = [
            ':WORKFLOW_ITEM_ID' => $param->WORKFLOW_ITEM_ID
        ];

        $con->execute($sql, $args);
    }

    public static function excluirWorkflowItemTarefaNotificado($param, $con) {

        $sql = "
            UPDATE TBWORKFLOW_ITEM_NOTIFICADO
            SET STATUSEXCLUSAO = '1'
            WHERE WORKFLOW_ITEM_ID = :WORKFLOW_ITEM_ID
        ";

        $args = [
            ':WORKFLOW_ITEM_ID' => $param->WORKFLOW_ITEM_ID
        ];

        $con->execute($sql, $args);
    }

    /**
     * Excluir notificações da tarefa do item de workflow.
     * @param json $param
     * @param _Conexao $con
     */
    public static function excluirWorkflowItemTarefaNotificacao($param, $con) {

        $sql = "
            DELETE FROM TBNOTIFICACAO N
            WHERE
                N.TABELA = 'TBWORKFLOW_ITEM_TAREFA'
            AND N.TABELA_ID = :TABELA_ID
        ";

        $args = [
            ':TABELA_ID' => $param->ID
        ];

        $con->execute($sql, $args);
    }
}