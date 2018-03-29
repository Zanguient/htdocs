<?php

namespace App\Models\DAO\Opex;

use App\Models\Conexao\_Conexao;

/**
 * DAO do objeto _25011 - Formulários
 */
class _25011DAO {

    public static function listarFormulario(_Conexao $con) {

        $sql = "
            SELECT DISTINCT
                LPAD(F.ID, 4, '0') ID,
                F.FORMULARIO_TIPO_ID TIPO,
                (SELECT FIRST 1 T.DESCRICAO FROM TBFORMULARIO_TIPO T WHERE T.ID = F.FORMULARIO_TIPO_ID) FORMULARIO_TIPO_DESCRICAO,
                F.TITULO,
                F.DESCRICAO,
                F.USUARIO_ID,
                (SELECT FIRST 1 IIF(U.NOME <> '', U.NOME, U.USUARIO) FROM TBUSUARIO U WHERE U.CODIGO = F.USUARIO_ID) USUARIO_DESCRICAO,
                F.STATUS,
                F.PERIODO_INI,
                F.PERIODO_FIM,
                IIF(FD.USUARIO_ID > 0, 'usuario', 'ccusto') DESTINATARIO_TIPO,
                COALESCE(FD.STATUS_RESPOSTA, 0) DESTINATARIO_STATUS_RESPOSTA

            FROM 
                TBFORMULARIO F
                LEFT JOIN TBFORMULARIO_DESTINATARIO FD ON FD.FORMULARIO_ID = F.ID

            WHERE 
                F.STATUSEXCLUSAO = '0'
            AND FD.VISUALIZA_CADASTRO = '0'
            AND FD.STATUSEXCLUSAO = '0'
            AND (FD.USUARIO_ID = :USUARIO_ID OR F.FORMULARIO_TIPO_ID = 2)
        ";

        $args = [
            ':USUARIO_ID' => \Auth::user()->CODIGO
        ];

        return $con->query($sql, $args);

    }

    public static function listarPergunta(_Conexao $con) {

        $sql = "
            SELECT
                FP.ID,
                FP.FORMULARIO_ID,
                FP.DESCRICAO,
                FP.FORMULARIO_RESPOSTA_TIPO_ID TIPO_RESPOSTA,
                FP.ORDEM

            FROM
                TBFORMULARIO_PERGUNTA FP

            WHERE
                FP.STATUSEXCLUSAO = '0'

            ORDER BY
                FP.ORDEM
        ";

        return $con->query($sql);

    }

    public static function listarAlternativa(_Conexao $con) {

        $sql = "
            SELECT
                FA.ID,
                FA.FORMULARIO_ID,
                FA.FORMULARIO_PERGUNTA_ID,
                FA.DESCRICAO,
                TRIM(FA.DESCRICAO_OBRIGATORIA) JUSTIFICATIVA_OBRIGATORIA

            FROM
                TBFORMULARIO_ALTERNATIVA FA

            WHERE
                FA.STATUSEXCLUSAO = '0'
        ";

        return $con->query($sql);

    }

    public static function listarResposta(_Conexao $con) {

        $sql = "
            SELECT
                FR.ID,
                FR.FORMULARIO_ID,
                FR.FORMULARIO_PERGUNTA_ID,
                FR.DESCRICAO,
                FR.ALTERNATIVA_ESCOLHIDA_ID,
                FR.USUARIO_ID,
                FR.COLABORADOR_ID

            FROM
                TBFORMULARIO_RESPOSTA FR

            WHERE
                FR.STATUSEXCLUSAO = '0'
            AND FR.USUARIO_ID = :USUARIO_ID
            AND FR.COLABORADOR_ID IS NULL
        ";

        $args = [
            ':USUARIO_ID' => \Auth::user()->CODIGO
        ];

        return $con->query($sql, $args);

    }

    /**
     * Autenticar colaborador.
     * @param string $cpf
     * @param string $cracha
     * @param integer $formulario_id
     * @param _Conexao $con
     */
    public static function autenticarColaborador($cpf, $cracha, $formulario_id, _Conexao $con) {

        $sql = "
            SELECT DISTINCT
                C.CODIGO COLABORADOR_ID,
                C.PESSOAL_NOME COLABORADOR_NOME,
                C.CENTRO_DE_CUSTO_CODIGO COLABORADOR_CCUSTO,
                COALESCE(
                    (SELECT FIRST 1 1
                        FROM TBFORMULARIO_RESPOSTA FR 
                        WHERE 
                            FR.STATUSEXCLUSAO = '0' 
                        AND FR.FORMULARIO_ID = FD.FORMULARIO_ID
                        AND FR.COLABORADOR_ID = C.CODIGO)
                    , 0
                ) RESPONDEU

            FROM
                TBCOLABORADOR C
                INNER JOIN TBFORMULARIO_DESTINATARIO FD ON C.CENTRO_DE_CUSTO_CODIGO LIKE FD.CCUSTO||'%'

            WHERE
                (C.DOCUMENTO_CPF = :CPF OR C.CRACHA = :CRACHA)
            AND FD.STATUSEXCLUSAO = '0'
            AND FD.FORMULARIO_ID = :FORMULARIO_ID
            AND C.SITUACAO = 1
        ";

        $args = [
            ':CPF'           => $cpf,
            ':CRACHA'        => $cracha,
            ':FORMULARIO_ID' => $formulario_id
        ];

        return $con->query($sql, $args);

    }

    public static function gravarResposta($dados, _Conexao $con) {

        $sql = "
            INSERT INTO TBFORMULARIO_RESPOSTA (
                FORMULARIO_ID,
                FORMULARIO_PERGUNTA_ID,
                DESCRICAO,
                ALTERNATIVA_ESCOLHIDA_ID,
                USUARIO_ID,
                CCUSTO,
                COLABORADOR_ID
            )
            VALUES(
                :FORMULARIO_ID,
                :FORMULARIO_PERGUNTA_ID,
                :DESCRICAO,
                :ALTERNATIVA_ESCOLHIDA_ID,
                :USUARIO_ID,
                :CCUSTO,
                :COLABORADOR_ID
            )
        ";

        $args = [
            ':FORMULARIO_ID'            => $dados['FORMULARIO_ID'],
            ':FORMULARIO_PERGUNTA_ID'   => $dados['FORMULARIO_PERGUNTA_ID'],
            ':DESCRICAO'                => $dados['DESCRICAO'],
            ':ALTERNATIVA_ESCOLHIDA_ID' => $dados['ALTERNATIVA_ESCOLHIDA_ID'],
            ':USUARIO_ID'               => $dados['USUARIO_ID'],
            ':CCUSTO'                   => $dados['COLABORADOR_CCUSTO'],
            ':COLABORADOR_ID'           => $dados['COLABORADOR_ID']
        ];
        
        $con->execute($sql, $args);

    }
	
}