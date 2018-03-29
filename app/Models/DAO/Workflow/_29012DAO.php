<?php

namespace App\Models\DAO\Workflow;

/**
 * DAO do objeto _29012 - Workflow
 */
class _29012DAO {

    /**
     * Consultar itens de workflow por usuário.
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarWorkflowItem($param, $con) {
        
        $sql = "
            SELECT DISTINCT
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
                WI.DATAHORA_FIM,
                WI.DATAHORA_INSERT
                
            FROM 
                TBWORKFLOW_ITEM WI
                LEFT JOIN TBWORKFLOW_ITEM_DESTINATARIO WID 
                    ON WID.WORKFLOW_ITEM_ID = WI.ID
                
            WHERE
                WI.STATUSEXCLUSAO = '0'
            AND IIF(:STATUS_CONCLUSAO_0 IS NULL, 1=1, WI.STATUS_CONCLUSAO = :STATUS_CONCLUSAO_1)
            AND IIF(:WORKFLOW_ITEM_ID_0 = 0, WI.DATAHORA_INSERT BETWEEN :DATA_INI AND :DATA_FIM, WI.ID = :WORKFLOW_ITEM_ID_1)
            AND IIF(:USUARIO_ID_0 IS NULL, 1=1, WID.USUARIO_ID = :USUARIO_ID_1)
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
     * Consultar tarefas do item de workflow por usuário.
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarWorkflowItemTarefa($param, $con) {
        
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
                WT.DATAHORA_INI_PREVISTA,
                WT.DATAHORA_FIM_PREVISTA,
                WT.DATAHORA_INI_RECALCULADA,
                WT.DATAHORA_FIM_RECALCULADA,
                WT.TEMPO_CONCLUSAO,
                WT.DATAHORA_INI_CONCLUSAO,
                WT.DATAHORA_FIM_CONCLUSAO,
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
     * Consultar destinatários das tarefas do item de workflow por usuário.
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarWorkflowItemTarefaDestinatario($param, $con) {
        
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
     * Consultar notificados das tarefas do item de workflow por usuário.
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarWorkflowItemTarefaNotificado($param, $con) {
        
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
    public static function consultarWorkflowItemTarefaCampo($param, $con) {
        
        $sql = "
            SELECT
                WC.ID,
                WC.WORKFLOW_ID,
                WC.WORKFLOW_ITEM_ID,
                WC.WORKFLOW_ITEM_TAREFA_ID,
                WC.ROTULO,
                TRIM(WC.TIPO) TIPO,
                WC.VALOR,
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
     * Consultar comentários das tarefas do item de workflow por usuário.
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarWorkflowItemTarefaComentario($param, $con) {
        
        $sql = "
            SELECT 
                WC.ID,
                WC.WORKFLOW_ITEM_ID,
                WC.WORKFLOW_ITEM_TAREFA_ID,
                WC.COMENTARIO,
                WC.USUARIO_ID,

                (SELECT FIRST 1 IIF(U.NOME IS NULL, U.USUARIO, U.NOME) 
                    FROM TBUSUARIO U 
                    WHERE U.CODIGO = WC.USUARIO_ID
                ) USUARIO_DESCRICAO,

                TRIM(STATUSEXCLUSAO) STATUSEXCLUSAO

            FROM 
                TBWORKFLOW_ITEM_COMENTARIO WC
                            
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
     * Consultar comentários das tarefas do item de workflow por usuário.
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarWorkflowItemTarefaComentarioPorTarefa($param, $con) {
        
        $sql = "
            SELECT 
                WC.ID,
                WC.WORKFLOW_ITEM_ID,
                WC.WORKFLOW_ITEM_TAREFA_ID,
                WC.COMENTARIO,
                WC.USUARIO_ID,

                (SELECT FIRST 1 IIF(U.NOME IS NULL, U.USUARIO, U.NOME) 
                    FROM TBUSUARIO U 
                    WHERE U.CODIGO = WC.USUARIO_ID
                ) USUARIO_DESCRICAO,

                TRIM(STATUSEXCLUSAO) STATUSEXCLUSAO

            FROM 
                TBWORKFLOW_ITEM_COMENTARIO WC
                            
            WHERE
                WC.STATUSEXCLUSAO = '0'
            AND WC.WORKFLOW_ITEM_TAREFA_ID = :WORKFLOW_ITEM_TAREFA_ID
        ";

        $args = [
            ':WORKFLOW_ITEM_TAREFA_ID' => $param->WORKFLOW_ITEM_TAREFA_ID
        ];

        return $con->query($sql, $args);
    }

    /**
     * Consultar movimentações das tarefas do item de workflow por usuário.
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarWorkflowItemTarefaMovimentacao($param, $con) {
        
        $sql = "
            SELECT 
                WM.ID,
                WM.WORKFLOW_ITEM_ID,
                WM.WORKFLOW_ITEM_TAREFA_ID,
                WM.USUARIO_ID,

                (SELECT FIRST 1 IIF(U.NOME IS NULL, U.USUARIO, U.NOME) 
                    FROM TBUSUARIO U 
                    WHERE U.CODIGO = WM.USUARIO_ID
                ) USUARIO_DESCRICAO,
                
                TRIM(WM.STATUS_CONCLUSAO) STATUS_CONCLUSAO,
                WM.DATAHORA

            FROM 
                TBWORKFLOW_ITEM_MOVIMENTACAO WM
                            
            WHERE
                WM.WORKFLOW_ITEM_ID = :WORKFLOW_ITEM_ID
        ";

        $args = [
            ':WORKFLOW_ITEM_ID' => $param->WORKFLOW_ITEM_ID
        ];

        return $con->query($sql, $args);
    }

    /**
     * Consultar movimentações de determinada tarefa do item de workflow.
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarWorkflowItemTarefaMovimentacaoPorTarefa($param, $con) {
        
        $sql = "
            SELECT 
                WM.ID,
                WM.WORKFLOW_ITEM_ID,
                WM.WORKFLOW_ITEM_TAREFA_ID,
                WM.USUARIO_ID,

                (SELECT FIRST 1 IIF(U.NOME IS NULL, U.USUARIO, U.NOME) 
                    FROM TBUSUARIO U 
                    WHERE U.CODIGO = WM.USUARIO_ID
                ) USUARIO_DESCRICAO,
                
                TRIM(WM.STATUS_CONCLUSAO) STATUS_CONCLUSAO,
                WM.DATAHORA

            FROM 
                TBWORKFLOW_ITEM_MOVIMENTACAO WM
                            
            WHERE
                WM.WORKFLOW_ITEM_TAREFA_ID = :WORKFLOW_ITEM_TAREFA_ID
        ";

        $args = [
            ':WORKFLOW_ITEM_TAREFA_ID' => $param->TAREFA_ID
        ];

        return $con->query($sql, $args);
    }

    /**
     * Consultar progresso de determinado item de workflow.
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarWorkflowItemProgresso($param, $con) {
        
        $sql = "
            SELECT
                WI.PROGRESSO
                            
            FROM 
                TBWORKFLOW_ITEM WI
                            
            WHERE
                WI.STATUSEXCLUSAO = '0'
            AND WI.ID = :WORKFLOW_ITEM_ID
        ";

        $args = [
            ':WORKFLOW_ITEM_ID' => $param->WORKFLOW_ITEM_ID
        ];

        return $con->query($sql, $args);
    }

    /**
     * Consultar arquivos das tarefas do item de workflow por usuário.
     * @param json $param
     * @param _Conexao $conFile
     * @return array
     */
    public static function consultarWorkflowItemTarefaArquivo($param, $conFile) {

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
                V.TABELA = :TABELA
            AND V.TABELA_ID = :TABELA_ID
        ";

        $args = [
            ':TABELA'    => $param->TABELA,
            ':TABELA_ID' => $param->ID
        ];

        return $conFile->query($sql, $args);
    }

    /**
     * Consultar arquivos de uma tarefa do item de workflow.
     * @param json $param
     * @param _Conexao $conFile
     * @return array
     */
    public static function consultarWorkflowItemTarefaArquivoPorTarefa($param, $conFile) {

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
                V.TABELA = :TABELA
            AND V.TABELA_ID = :TABELA_ID
        ";

        $args = [
            ':TABELA'    => $param->TABELA,
            ':TABELA_ID' => $param->TAREFA_ID
        ];

        return $conFile->query($sql, $args);
    }

    /**
     * Alterar situação da tarefa do item de workflow.
     *
     * @param json $param
     * @param _Conexao $con
     */
    public static function alterarSituacaoWorkflowItemTarefa($param, $con) {

        $sql = "
            UPDATE TBWORKFLOW_ITEM_TAREFA WIT
            SET 
                WIT.STATUS_CONCLUSAO = :STATUS_CONCLUSAO,
                WIT.USUARIO_CONCLUSAO_ID = :USUARIO_CONCLUSAO_ID,
                WIT.TEMPO_CONCLUSAO = :TEMPO_CONCLUSAO,
                WIT.VERIFICADOR = 1
            WHERE
                WIT.ID = :TAREFA_ID
        ";

        $args = [
            ':STATUS_CONCLUSAO'     => $param->SITUACAO,
            ':USUARIO_CONCLUSAO_ID' => $param->USUARIO_ID,
            ':TEMPO_CONCLUSAO'      => $param->TAREFA_ATUAL->TEMPO_CONCLUSAO,
            ':TAREFA_ID'            => $param->TAREFA_ID
        ];

        $con->execute($sql, $args);
    }

    /**
     * Gravar tempo recalculado (efetuado) da tarefa do item de workflow.
     * @param json $param
     * @param _Conexao $con
     */
    public static function gravarWorkflowItemTarefaTempoEfetuado($param, $con) {

        $sql = "
            UPDATE TBWORKFLOW_ITEM_TAREFA WIT 
            SET 
                /*@DATAHORA_INI_RECALCULADA*/
                /*@DATAHORA_FIM_RECALCULADA*/
                /*@DATAHORA_INI_CONCLUSAO*/
                /*@DATAHORA_FIM_CONCLUSAO*/
                WIT.VERIFICADOR = 0
            WHERE
                WIT.ID = :ID
        ";

        $iniRec  = empty($param['DATAHORA_INI_RECALCULADA']) ? '' : 'WIT.DATAHORA_INI_RECALCULADA = \''.$param['DATAHORA_INI_RECALCULADA'].'\',';
        $fimRec  = empty($param['DATAHORA_FIM_RECALCULADA']) ? '' : 'WIT.DATAHORA_FIM_RECALCULADA = \''.$param['DATAHORA_FIM_RECALCULADA'].'\',';
        $iniConc = empty($param['DATAHORA_INI_CONCLUSAO'])   ? '' : 'WIT.DATAHORA_INI_CONCLUSAO = \''.$param['DATAHORA_INI_CONCLUSAO'].'\',';
        $fimConc = empty($param['DATAHORA_FIM_CONCLUSAO'])   ? '' : 'WIT.DATAHORA_FIM_CONCLUSAO = \''.$param['DATAHORA_FIM_CONCLUSAO'].'\',';

        $args = [
            '@DATAHORA_INI_RECALCULADA' => $iniRec,
            '@DATAHORA_FIM_RECALCULADA' => $fimRec,
            '@DATAHORA_INI_CONCLUSAO'   => $iniConc,
            '@DATAHORA_FIM_CONCLUSAO'   => $fimConc,
            ':ID'                       => $param['ID']
        ];

        $con->execute($sql, $args);
    }

    /**
     * Excluir notificações da tarefa do item de workflow.
     * @param json $param
     * @param _Conexao $con
     */
    public static function excluirNotificacaoWorkflowItemTarefa($param, $con) {

        $sql = "
            DELETE FROM TBNOTIFICACAO N
            WHERE
                N.TABELA = 'TBWORKFLOW_ITEM_TAREFA'
            AND N.TABELA_ID = :TABELA_ID
        ";

        $args = [
            ':TABELA_ID' => $param['ID']
        ];

        $con->execute($sql, $args);
    }

    /**
     * Gravar comentário da tarefa do item de workflow.
     *
     * @param json $param
     * @param _Conexao $con
     */
    public static function gravarWorkflowItemTarefaComentario($param, $con) {

        $sql = "
            UPDATE OR INSERT INTO TBWORKFLOW_ITEM_COMENTARIO (
                ID, 
                WORKFLOW_ID,
                WORKFLOW_ITEM_ID,
                WORKFLOW_ITEM_TAREFA_ID, 
                COMENTARIO, 
                USUARIO_ID, 
                STATUSEXCLUSAO
            ) 
            VALUES (
                :ID,
                :WORKFLOW_ID,
                :WORKFLOW_ITEM_ID,
                :WORKFLOW_ITEM_TAREFA_ID, 
                :COMENTARIO, 
                :USUARIO_ID, 
                :STATUSEXCLUSAO
            )
            MATCHING (ID)
        ";

        $args = [
            ':ID'                        => $param->ID, 
            ':WORKFLOW_ID'               => $param->WORKFLOW_ID,
            ':WORKFLOW_ITEM_ID'          => $param->WORKFLOW_ITEM_ID,
            ':WORKFLOW_ITEM_TAREFA_ID'   => $param->WORKFLOW_ITEM_TAREFA_ID, 
            ':COMENTARIO'                => $param->COMENTARIO, 
            ':USUARIO_ID'                => $param->USUARIO_ID, 
            ':STATUSEXCLUSAO'            => $param->STATUSEXCLUSAO
        ];

        $con->execute($sql, $args);
    }

    /**
     * Gravar campos dinâmicos da tarefa do item de workflow.
     * @param array $param
     * @param _Conexao $con
     */
    public static function gravarWorkflowItemTarefaCampo($param, $con) {

        $sql = "
            UPDATE OR INSERT INTO TBWORKFLOW_ITEM_CAMPO (
                ID,
                VALOR
            ) VALUES (
                :ID,
                :VALOR
            )
            MATCHING (ID)
        ";

        $args = [
            ':ID'    => $param->ID,
            ':VALOR' => $param->VALOR
        ];

        $con->execute($sql, $args);
    }
}