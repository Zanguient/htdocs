<?php

namespace App\Models\DAO\Chamados;

/**
 * DAO do objeto _26021 - Pesquisa de satisfação do cliente
 */
class _26021DAO {

	/**
     * Consultar pesquisas.
     *
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarPesquisa($param, $con) {
        
        $sql = "
            SELECT
                FP.ID,
                FP.FORMULARIO_ID,

                (SELECT FIRST 1 F.TITULO
                    FROM TBFORMULARIO F
                    WHERE F.STATUSEXCLUSAO = '0'
                    AND F.ID = FP.FORMULARIO_ID)
                TITULO,

                (SELECT FIRST 1 F.DESCRICAO
                    FROM TBFORMULARIO F
                    WHERE
                        F.STATUSEXCLUSAO = '0'
                        AND F.ID = FP.FORMULARIO_ID)
                DESCRICAO,

                FP.USUARIO_ID,

                (SELECT FIRST 1 IIF(U.NOME <> '', U.NOME, U.USUARIO)
                    FROM TBUSUARIO U
                    WHERE U.CODIGO = FP.USUARIO_ID)
                USUARIO_DESCRICAO,

                FP.CLIENTE_ID,

                (SELECT FIRST 1 E.RAZAOSOCIAL
                    FROM TBEMPRESA E
                    WHERE E.CODIGO = FP.CLIENTE_ID)
                RAZAOSOCIAL,

                FP.SATISFACAO,
                TRIM(FP.STATUS) STATUS,
                FP.NOTA_DELFA,
                FP.OBSERVACAO_DELFA,
                FP.DATAHORA_INSERT

            FROM
                TBFORMULARIO_PESQ_CLIENTE FP

            WHERE
                FP.STATUSEXCLUSAO = '0'
            AND IIF(CAST(:STATUS_0 AS INTEGER) IS NULL, TRUE, FP.STATUS = :STATUS)
            AND IIF(CAST(:DATA_INI_0 AS TIMESTAMP) IS NULL, TRUE, FP.DATAHORA_INSERT BETWEEN :DATA_INI AND :DATA_FIM)
            AND IIF(CAST(:CLIENTE_ID_0 AS INTEGER) IS NULL, TRUE, FP.CLIENTE_ID = :CLIENTE_ID)
        ";

        $args = [
            ':STATUS_0'     => $param->STATUS,
            ':STATUS'       => $param->STATUS,
            ':DATA_INI_0'   => $param->DATA_INI,
            ':DATA_INI'     => $param->DATA_INI,
            ':DATA_FIM'     => $param->DATA_FIM,
            ':CLIENTE_ID_0' => $param->CLIENTE->ID,
            ':CLIENTE_ID'   => $param->CLIENTE->ID
        ];

        return $con->query($sql, $args);
    }

    /**
     * Consultar respostas de uma pesquisa.
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarResposta($param, $con) {

        $sql = "
            SELECT
                FR.ID,
                FR.FORMULARIO_ID,
                FR.FORMULARIO_PERGUNTA_ID,
                FR.ALTERNATIVA_ESCOLHIDA_ID,
                FR.DESCRICAO,
                FR.SOLUCAO

            FROM
                TBFORMULARIO_RESPOSTA FR

            WHERE
                FR.STATUSEXCLUSAO = '0'
            AND FR.FORMULARIO_PESQ_CLIENTE_ID = :PESQUISA_ID
        ";

        $args = [
            ':PESQUISA_ID' => $param->ID
        ];

        return $con->query($sql, $args);
    }

    /**
     * Consultar modelo de pesquisas (26020).
     *
     * @access public
     * @param _Conexao $con
     * @return array
     */
    public static function consultarModeloPesquisa($con) {

        $sql = "
            SELECT 
                F.ID,
                F.TITULO,
                F.DESCRICAO,
                F.USUARIO_ID,
                
                (SELECT FIRST 1 IIF(U.NOME <> '', U.NOME, U.USUARIO) 
                    FROM TBUSUARIO U 
                    WHERE U.CODIGO = F.USUARIO_ID
                ) USUARIO_DESCRICAO,
                
                TRIM(F.STATUS) STATUS,
                F.DATAHORA_INSERT

            FROM 
                TBFORMULARIO F

            WHERE 
                F.STATUSEXCLUSAO = '0'
            AND F.FORMULARIO_TIPO_ID = 3

            ORDER BY
                F.ID DESC
        ";

        return $con->query($sql);
    }

    /**
     * Consultar perguntas do modelo de pesquisas (26020).
     *
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarModeloPesquisaPergunta($param, $con) {

        $sql = "
            SELECT
                FP.ID,
                FP.FORMULARIO_ID,
                FP.INDICADOR,
                FP.TAG,
                FP.DESCRICAO,
                FP.FORMULARIO_RESPOSTA_TIPO_ID TIPO_RESPOSTA,
                FP.ORDEM

            FROM
                TBFORMULARIO_PERGUNTA FP

            WHERE
                FP.STATUSEXCLUSAO = '0'
                AND FP.FORMULARIO_ID = :FORMULARIO_ID

            ORDER BY
                FP.ORDEM
        ";

        $args = [
            ':FORMULARIO_ID' => $param->ID
        ];

        return $con->query($sql, $args);
    }

    /**
     * Consultar alternativas das perguntas do modelo de pesquisas (26020).
     *
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarModeloPesquisaPerguntaAlternativa($param, $con) {

        $sql = "
            SELECT
                FA.ID,
                FA.FORMULARIO_ID,
                FA.FORMULARIO_PERGUNTA_ID,
                FA.DESCRICAO,
                FA.NOTA,
                TRIM(FA.DESCRICAO_OBRIGATORIA) JUSTIFICATIVA_OBRIGATORIA

            FROM
                TBFORMULARIO_ALTERNATIVA FA

            WHERE
                FA.STATUSEXCLUSAO = '0'
                AND FA.FORMULARIO_ID = :FORMULARIO_ID
        ";

        $args = [
            ':FORMULARIO_ID' => $param->ID
        ];

        return $con->query($sql, $args);
    }
	
    /**
     * Consultar clientes.
     *
     * @access public
     * @param _Conexao $con
     * @return array
     */
    public static function consultarCliente($con) {

        $sql = "
            SELECT
                E.CODIGO ID,
                E.RAZAOSOCIAL,
                E.NOMEFANTASIA

            FROM
                TBEMPRESA E

            WHERE
                E.STATUSEXCLUSAO = '1'
            AND E.STATUS = '1'
            AND E.HABILITA_CLIENTE = '1'
        ";

        return $con->query($sql);
    }

    /**
     * Gerar id da pesquisa.
     *
     * @access public
     * @param _Conexao $con
     * @return array
     */
    public static function gerarIdPesquisa($con) {

        $sql = 'SELECT GEN_ID(GTBFORMULARIO_PESQ_CLIENTE, 1) ID FROM RDB$DATABASE';

        return $con->query($sql)[0]->ID;
    }


    /**
     * Gravar pesquisa.
     *
     * @access public
     * @param json $dado
     * @param _Conexao $con
     */
    public static function gravarPesquisa($dado, $con) {

        $sql = "
            UPDATE OR INSERT INTO TBFORMULARIO_PESQ_CLIENTE (
                ID,
                FORMULARIO_ID,
                USUARIO_ID,
                CLIENTE_ID,
                SATISFACAO,
                STATUS,
                NOTA_DELFA,
                OBSERVACAO_DELFA
            )
            VALUES(
                :ID,
                :FORMULARIO_ID,
                :USUARIO_ID,
                :CLIENTE_ID,
                :SATISFACAO,
                :STATUS,
                :NOTA_DELFA,
                :OBSERVACAO_DELFA
            )
            MATCHING(ID)
        ";

        $args = [
            ':ID'               => $dado->ID,
            ':FORMULARIO_ID'    => $dado->MODELO->ID,
            ':USUARIO_ID'       => $dado->USUARIO_ID,
            ':CLIENTE_ID'       => $dado->CLIENTE->ID,
            ':SATISFACAO'       => $dado->SATISFACAO,
            ':STATUS'           => $dado->STATUS,
            ':NOTA_DELFA'       => $dado->NOTA_DELFA,
            ':OBSERVACAO_DELFA' => $dado->OBSERVACAO_DELFA
        ];
        
        $con->execute($sql, $args);
    }

    /**
     * Gravar resposta.
     *
     * @access public
     * @param json $dado
     * @param _Conexao $con
     */
    public static function gravarResposta($dado, $con) {

        $sql = "
            UPDATE OR INSERT INTO TBFORMULARIO_RESPOSTA (
                ID,
                FORMULARIO_ID,
                FORMULARIO_PESQ_CLIENTE_ID,
                FORMULARIO_PERGUNTA_ID,
                ALTERNATIVA_ESCOLHIDA_ID,
                DESCRICAO,
                SOLUCAO,
                USUARIO_ID,
                CLIENTE_ID
            )
            VALUES(
                :ID,
                :FORMULARIO_ID,
                :FORMULARIO_PESQ_CLIENTE_ID,
                :FORMULARIO_PERGUNTA_ID,
                :ALTERNATIVA_ESCOLHIDA_ID,
                :DESCRICAO,
                :SOLUCAO,
                :USUARIO_ID,
                :CLIENTE_ID
            )
            MATCHING(ID)
        ";

        $args = [
            ':ID'                           => $dado->RESPOSTA->ID,
            ':FORMULARIO_ID'                => $dado->FORMULARIO_ID,
            ':FORMULARIO_PESQ_CLIENTE_ID'   => $dado->FORMULARIO_PESQ_CLIENTE_ID,
            ':FORMULARIO_PERGUNTA_ID'       => $dado->ID,
            ':ALTERNATIVA_ESCOLHIDA_ID'     => $dado->RESPOSTA->ALTERNATIVA_ESCOLHIDA_ID,
            ':DESCRICAO'                    => $dado->RESPOSTA->DESCRICAO,
            ':SOLUCAO'                      => $dado->RESPOSTA->SOLUCAO,
            ':USUARIO_ID'                   => $dado->USUARIO_ID,
            ':CLIENTE_ID'                   => $dado->CLIENTE_ID
        ];
        
        $con->execute($sql, $args);
    }

    /**
     * Excluir pesquisa.
     *
     * @access public
     * @param json $dado
     * @param _Conexao $con
     */
    public static function excluir($param, $con) {

        $sql = "
            UPDATE TBFORMULARIO_PESQ_CLIENTE
            SET STATUSEXCLUSAO = '1'
            WHERE ID = :ID
        ";

        $args = [
            ':ID' => $param->ID
        ];

        $con->execute($sql, $args);
    }
}