<?php

namespace App\Models\DAO\Opex;

use App\Models\Conexao\_Conexao;

/**
 * DAO do objeto _25010 - Cadastro de Formulários
 */
class _25010DAO {


    public static function listarFormulario($param, $pu217, $usuario_id, _Conexao $con) {

        $porSetor = '';
        $porAutor = '';
        $periodo  = '';

        // Se o usuário NÃO pode ver tudo (por tipo).
        if ($pu217 != '1') {

            if ($param->TIPO == '3') {
                $porAutor = "AND F.USUARIO_ID = $usuario_id";
                $periodo  = "AND F.DATAHORA_INSERT BETWEEN '$param->DATA_INI' AND '$param->DATA_FIM'";
            }
            else {
                $porSetor = "AND (FD.USUARIO_ID = $usuario_id AND FD.VISUALIZA_CADASTRO = '1')";
                $periodo  = "AND F.PERIODO_INI BETWEEN '$param->DATA_INI' AND '$param->DATA_FIM'";
            }
        }


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
                F.DATAHORA_INSERT,
                IIF(F.FORMULARIO_TIPO_ID = 3, 'cliente', IIF(FD.USUARIO_ID > 0, 'usuario', 'ccusto')) DESTINATARIO_TIPO

            FROM 
                TBFORMULARIO F
                LEFT JOIN TBFORMULARIO_DESTINATARIO FD 
                    ON  FD.FORMULARIO_ID = F.ID 
                    AND FD.STATUSEXCLUSAO = '0'

            WHERE 
                F.STATUSEXCLUSAO = '0'
                AND IIF(CAST(:STATUS_0 AS INTEGER) IS NULL, TRUE, F.STATUS = :STATUS)
                AND IIF(CAST(:TIPO_0 AS INTEGER) IS NULL, TRUE, F.FORMULARIO_TIPO_ID = :TIPO)
                /*@PERIODO*/
                /*@POR_SETOR*/
                /*@POR_AUTOR*/
        ";

        $args = [
            ':STATUS_0'  => $param->STATUS,
            ':STATUS'    => $param->STATUS,
            ':TIPO_0'    => $param->TIPO,
            ':TIPO'      => $param->TIPO,
            '@PERIODO'   => $periodo,
            '@POR_SETOR' => $porSetor,
            '@POR_AUTOR' => $porAutor
        ];

        return $con->query($sql, $args);

    }

    public static function listarDestinatario(_Conexao $con) {

        $sql = "
            SELECT
                LPAD(FD.USUARIO_ID, 4, '0') ID,
                U.USUARIO,
                U.NOME, 
                FD.CCUSTO,
                C.DESCRICAO,
                IIF(char_length(C.CODIGO)=2,C.CODIGO,
                IIF(char_length(C.CODIGO)=5,Substring(C.CODIGO From 1 For 2)||'.'||Substring(C.CODIGO From 3 For 3),
                IIF(char_length(C.CODIGO)=8,Substring(C.CODIGO From 1 For 2)||'.'||Substring(C.CODIGO From 3 For 3)||'.'||Substring(C.CODIGO From 6 For 3),''))) MASK,
                LPAD(FD.ID, 4, '0') DESTINATARIO_ID,
                FD.FORMULARIO_ID,
                FD.PESO_RESPOSTA PESO,
                TRIM(FD.STATUS_RESPOSTA) STATUS_RESPOSTA,
                TRIM(FD.VISUALIZA_CADASTRO) VISUALIZA_CADASTRO

            FROM
                TBFORMULARIO_DESTINATARIO FD
                LEFT JOIN TBUSUARIO U ON U.CODIGO = FD.USUARIO_ID
                LEFT JOIN VWCENTRO_DE_CUSTO C ON C.CODIGO = FD.CCUSTO

            WHERE
                FD.STATUSEXCLUSAO = '0'
        ";

        return $con->query($sql);

    }

    public static function listarPergunta(_Conexao $con) {

        $sql = "
            SELECT
                FP.ID,
                FP.FORMULARIO_ID,
                FP.DESCRICAO,
                FP.INDICADOR,
                FP.TAG,
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
                CAST(FA.FORMULARIO_ALTERN_NIVEL_ID AS VARCHAR(50)) NIVEL_SATISFACAO,
                FA.DESCRICAO,
                TRIM(FA.DESCRICAO_OBRIGATORIA) JUSTIFICATIVA_OBRIGATORIA,
                FA.NOTA

            FROM
                TBFORMULARIO_ALTERNATIVA FA

            WHERE
                FA.STATUSEXCLUSAO = '0'
        ";

        return $con->query($sql);

    }

    public static function listarPainel($formulario_id, _Conexao $con) {

        return [
            'QTD'                   => self::painelQtdResposta($formulario_id, $con),
            'DESTINATARIO'          => self::painelDestinatario($formulario_id, $con),
            'SATISFACAO_GERAL'      => self::painelSatisfacaoGeral($formulario_id, $con),
            'SATISFACAO_PERGUNTA'   => self::painelSatisfacaoPergunta($formulario_id, $con),
            'PERGUNTA'              => self::painelPergunta($formulario_id, $con),
            'ALTERNATIVA'           => self::painelAlternativa($formulario_id, $con)
        ];

    }

    public static function painelQtdResposta($formulario_id, _Conexao $con) {

        $sql = "
            SELECT
                F.ID FORMULARIO_ID,
                
               (Select Count(FD.Usuario_id) From TBFORMULARIO_DESTINATARIO FD
                 Where FD.FORMULARIO_ID = F.ID
                   AND FD.usuario_id > 0
                   AND FD.STATUSEXCLUSAO = '0'
                   AND FD.VISUALIZA_CADASTRO = '0') QTD_RESPOSTA_ESPERADA_USUARIO,

               (Select Count(C.Codigo) From TBCOLABORADOR C, TBFORMULARIO_DESTINATARIO FD
                 Where FD.usuario_id IS NULL
                   and FD.FORMULARIO_ID = F.ID
                   AND C.CENTRO_DE_CUSTO_CODIGO = FD.CCUSTO
                   AND ((C.SITUACAO = '1' AND C.DATA_ADMISSAO <= F.PERIODO_FIM) or
                        (C.SITUACAO = '2' AND C.DATA_ADMISSAO <= F.PERIODO_FIM and C.DATA_DEMISSAO > F.PERIODO_INI))
                   AND FD.STATUSEXCLUSAO = '0') QTD_RESPOSTA_ESPERADA_COLAB,

               (Select Count(DISTINCT FR.usuario_id) From TBFORMULARIO_RESPOSTA FR
                 Where FR.FORMULARIO_ID = F.ID
                   AND FR.STATUSEXCLUSAO = '0') QTD_RESPONDIDA_USUARIO,

               (Select Count(DISTINCT FR.COLABORADOR_ID) From TBFORMULARIO_RESPOSTA FR
                 Where FR.FORMULARIO_ID = F.ID
                   AND FR.STATUSEXCLUSAO = '0') QTD_RESPONDIDA_COLAB

            FROM 
                TBFORMULARIO F
                
            WHERE 
                F.STATUSEXCLUSAO = '0' 
            AND F.ID = :FORMULARIO_ID
        ";

        $args = [
            ':FORMULARIO_ID' => $formulario_id
        ];

        return $con->query($sql, $args);

    }

    public static function csv($param, _Conexao $con) {

        $sql = "
            SELECT
                IIF(FR.FORMULARIO_PESQ_CLIENTE_ID IS NULL, 
                    coalesce(c.pessoal_nome = null, u.nome, c.pessoal_nome),
                    (SELECT FIRST 1 E.RAZAOSOCIAL
                     FROM TBEMPRESA E
                     WHERE E.CODIGO = FR.CLIENTE_ID))
                USUARIO,

                FR.FORMULARIO_ID,
                FR.FORMULARIO_PERGUNTA_ID,

                (SELECT FIRST 1 FP.ORDEM 
                    FROM TBFORMULARIO_PERGUNTA FP 
                    WHERE 
                        FP.FORMULARIO_ID = FR.FORMULARIO_ID
                    AND FP.ID = FR.FORMULARIO_PERGUNTA_ID
                ) FORMULARIO_PERGUNTA_ORDEM,

                (SELECT FIRST 1 FP.DESCRICAO 
                    FROM TBFORMULARIO_PERGUNTA FP 
                    WHERE 
                        FP.FORMULARIO_ID = FR.FORMULARIO_ID 
                    AND FP.ID = FR.FORMULARIO_PERGUNTA_ID
                ) PERGUNTA_DESCRICAO,

                FA.DESCRICAO ALTERNATIVA_DESCRICAO,
                FA.NOTA ALTERNATIVA_NOTA,

                (SELECT MAX(FA2.NOTA) FROM TBFORMULARIO_ALTERNATIVA FA2 WHERE FA2.FORMULARIO_PERGUNTA_ID = FR.FORMULARIO_PERGUNTA_ID
                ) ALTERNATIVA_NOTA_MAIOR,

                FA.FORMULARIO_ALTERN_NIVEL_ID NIVEL_SATISFACAO,

                (SELECT FIRST 1 FN.DESCRICAO FROM TBFORMULARIO_ALTERN_NIVEL FN WHERE FN.ID = FA.FORMULARIO_ALTERN_NIVEL_ID
                ) NIVEL_SATISFACAO_DESCRICAO,

                FR.DESCRICAO JUSTIFICATIVA

            FROM
                TBFORMULARIO_RESPOSTA FR
                LEFT JOIN TBFORMULARIO_ALTERNATIVA FA
                     ON FA.FORMULARIO_ID = FR.FORMULARIO_ID
                    AND FA.ID = FR.ALTERNATIVA_ESCOLHIDA_ID
                left join tbusuario u on (u.codigo = fr.usuario_id)
                left join tbcolaborador c on (c.codigo = fr.COLABORADOR_ID)
                LEFT JOIN TBFORMULARIO_PESQ_CLIENTE FPC
                    ON FPC.ID = FR.FORMULARIO_PESQ_CLIENTE_ID
                    AND FPC.STATUSEXCLUSAO = '0'
                LEFT JOIN TBCLIENTE CL ON CL.CODIGO = FR.CLIENTE_ID

            WHERE
                FR.STATUSEXCLUSAO = '0'
            AND FR.USUARIO_ID IS NOT NULL
            AND FR.FORMULARIO_ID = :FORMULARIO_ID
            AND IIF(CAST(:DATA_INI_0 AS TIMESTAMP) IS NULL, TRUE, FPC.DATAHORA_INSERT BETWEEN :DATA_INI AND :DATA_FIM)
            AND IIF(CAST(:REPRESENTANTE_ID_0 AS INTEGER) IS NULL, TRUE, CL.REPRESENTANTE_CODIGO = :REPRESENTANTE_ID)
            AND IIF(CAST(:UF_0 AS VARCHAR(2)) IS NULL, TRUE, CL.UF = :UF)

            ORDER BY 1,4
        ";

        $args = [
            ':FORMULARIO_ID'        => $param->formulario_id,
            ':DATA_INI_0'           => $param->data_ini,
            ':DATA_INI'             => $param->data_ini,
            ':DATA_FIM'             => $param->data_fim,
            ':REPRESENTANTE_ID_0'   => $param->representante->CODIGO,
            ':REPRESENTANTE_ID'     => $param->representante->CODIGO,
            ':UF_0'                 => $param->uf->UF,
            ':UF'                   => $param->uf->UF
        ];

        return $con->query($sql, $args);

    }

    /**
     * Retornar os destinatários identificando se os mesmos já responderam.

     * Na tabela TBFORMULARIO_DESTINATARIO, não é gravado o ID do colaborador, apenas o ccusto.

     * Quando todos os ccusto são selecionados, os destinatários retornados se repetem várias vezes,
     *  pois a verificação de ccusto é feita de acordo com a herança.
     *  Ex.: 71, 71008 => destinatário que tem ccusto 71008 irá aparecer 2x.
     *  Devido a isso, foi utilizado ROW_NUMBER com PARTITION para trazer apenas um registro.

     * Caso exista CCUSTO em TBFORMULARIO_DESTINATARIO, o destinatário é um colaborador (TBCOLABORADOR.CODIGO);
     *   do contrário, trata-se de um usuário (TBFORMULARIO_DESTINATARIO.USUARIO_ID).
     */
    public static function painelDestinatario($formulario_id, _Conexao $con) {

        $sql = "
            SELECT *
            FROM (

                SELECT
                    FD.FORMULARIO_ID,

                    IIF(FD.CCUSTO > 0,
                        C.CODIGO,
                        FD.USUARIO_ID
                    ) DESTINATARIO_ID,

                    IIF(FD.CCUSTO > 0,
                        C.PESSOAL_NOME,
                        (SELECT FIRST 1 IIF(U.NOME IS NULL, U.USUARIO, U.NOME)
                            FROM TBUSUARIO U
                            WHERE U.CODIGO = FD.USUARIO_ID)
                    ) DESTINATARIO_DESCRICAO,

                    FD.PESO_RESPOSTA DESTINATARIO_PESO,
                    FD.CCUSTO DESTINATARIO_CCUSTO,

                    IIF(FD.USUARIO_ID > 0,

                        FD.STATUS_RESPOSTA,

                        IIF( (SELECT FIRST 1 FR.COLABORADOR_ID
                                FROM TBFORMULARIO_RESPOSTA FR
                                WHERE FR.FORMULARIO_ID = FD.FORMULARIO_ID
                                AND FR.COLABORADOR_ID = C.CODIGO
                                AND FR.STATUSEXCLUSAO = '0') > 0,
                            '1',
                            '0'
                        )

                    ) DESTINATARIO_STATUS_RESPOSTA,

                    ROW_NUMBER() OVER (PARTITION BY FD.FORMULARIO_ID, IIF(FD.CCUSTO > 0, C.CODIGO, FD.USUARIO_ID)) AS ROWNUMBER

                FROM 
                    TBFORMULARIO_DESTINATARIO FD
                    LEFT JOIN TBCOLABORADOR C 
                        ON  C.SITUACAO = '1' 
                        AND C.CENTRO_DE_CUSTO_CODIGO LIKE FD.CCUSTO||'%'

                WHERE 
                    FD.STATUSEXCLUSAO = '0'
                AND FD.VISUALIZA_CADASTRO = '0'
                AND FD.FORMULARIO_ID = :FORMULARIO_ID

            ) AS A

            WHERE
                A.ROWNUMBER = 1
        ";

        $args = [
            ':FORMULARIO_ID' => $formulario_id
        ];

        return $con->query($sql, $args);

    }

    public static function painelSatisfacaoGeral($formulario_id, _Conexao $con) {

        $sql = "
            SELECT PERC_SATISF FROM SPC_FORMULARIO_SATISF_GERAL(:FORMULARIO_ID)
        ";

        $args = [
            ':FORMULARIO_ID' => $formulario_id
        ];

        return $con->query($sql, $args);
    }

    public static function painelSatisfacaoPergunta($formulario_id, _Conexao $con) {

        $sql = "
            SELECT 
                FSP.PERC_SATISF, 
                FSP.FORMULARIO_PERGUNTA_ID, 
                FSP.FORMULARIO_PERGUNTA_ORDEM
            FROM 
                SPC_FORMULARIO_SATISF_PERGUNTA(:FORMULARIO_ID) FSP
        ";

        $args = [
            ':FORMULARIO_ID' => $formulario_id
        ];

        return $con->query($sql, $args);
    }

    /**
     * Consultar respostas por usuários para o painel.
     * @param int $formulario_id
     * @param int $destinatario_id
     * @param _Conexao $con
     * @return array
     */
    public static function painelResposta($formulario_id, $destinatario_id, _Conexao $con) {

        $sql = "
            SELECT
                FR.ID,
                FR.FORMULARIO_ID,
                FR.FORMULARIO_PERGUNTA_ID,

                (SELECT FIRST 1 FP.ORDEM 
                    FROM TBFORMULARIO_PERGUNTA FP 
                    WHERE 
                        FP.FORMULARIO_ID = FR.FORMULARIO_ID
                    AND FP.ID = FR.FORMULARIO_PERGUNTA_ID
                ) FORMULARIO_PERGUNTA_ORDEM,

                (SELECT FIRST 1 FP.DESCRICAO 
                    FROM TBFORMULARIO_PERGUNTA FP 
                    WHERE 
                        FP.FORMULARIO_ID = FR.FORMULARIO_ID 
                    AND FP.ID = FR.FORMULARIO_PERGUNTA_ID
                ) PERGUNTA_DESCRICAO,

                FA.DESCRICAO ALTERNATIVA_DESCRICAO,
                FA.NOTA ALTERNATIVA_NOTA,

                (SELECT MAX(FA2.NOTA) FROM TBFORMULARIO_ALTERNATIVA FA2 WHERE FA2.FORMULARIO_PERGUNTA_ID = FR.FORMULARIO_PERGUNTA_ID
                ) ALTERNATIVA_NOTA_MAIOR,

                FA.FORMULARIO_ALTERN_NIVEL_ID NIVEL_SATISFACAO,

                (SELECT FIRST 1 FN.DESCRICAO FROM TBFORMULARIO_ALTERN_NIVEL FN WHERE FN.ID = FA.FORMULARIO_ALTERN_NIVEL_ID
                ) NIVEL_SATISFACAO_DESCRICAO,

                FR.DESCRICAO JUSTIFICATIVA

            FROM
                TBFORMULARIO_RESPOSTA FR
                LEFT JOIN TBFORMULARIO_ALTERNATIVA FA
                     ON FA.FORMULARIO_ID = FR.FORMULARIO_ID
                    AND FA.ID = FR.ALTERNATIVA_ESCOLHIDA_ID

            WHERE
                FR.STATUSEXCLUSAO = '0'
            AND FR.USUARIO_ID IS NOT NULL
            AND FR.FORMULARIO_ID = :FORMULARIO_ID
            AND IIF(FR.FORMULARIO_PESQ_CLIENTE_ID IS NULL,
                    (FR.COLABORADOR_ID = :DESTINATARIO_ID OR FR.USUARIO_ID = :DESTINATARIO_ID_1),
                    FR.CLIENTE_ID = :DESTINATARIO_ID_2)

            ORDER BY 
                4
        ";

        $args = [
            ':FORMULARIO_ID'    => $formulario_id,
            ':DESTINATARIO_ID'  => $destinatario_id,
            ':DESTINATARIO_ID_1'=> $destinatario_id,
            ':DESTINATARIO_ID_2'=> $destinatario_id
        ];

        return $con->query($sql, $args);

    }

    public static function painelPergunta($formulario_id, _Conexao $con) {

        $sql = "
            SELECT
                FR.FORMULARIO_ID,
                FR.FORMULARIO_PERGUNTA_ID,
                FP.ORDEM PERGUNTA_ORDEM,
                FP.DESCRICAO PERGUNTA_DESCRICAO,
                COUNT(FR.ID) QUANTIDADE_RESPOSTA,
                SUM(FA.NOTA) ALTERNATIVA_NOTA_SOMA,
                (SELECT MAX(FA2.NOTA) FROM TBFORMULARIO_ALTERNATIVA FA2 WHERE FA2.FORMULARIO_PERGUNTA_ID = FR.FORMULARIO_PERGUNTA_ID
                ) ALTERNATIVA_NOTA_MAIOR,
                COUNT(IIF(FA.FORMULARIO_ALTERN_NIVEL_ID = 1, FA.FORMULARIO_ALTERN_NIVEL_ID, NULL)) CONTA_NIVEL_SATISFACAO,
                COUNT(IIF(FA.FORMULARIO_ALTERN_NIVEL_ID = 2, FA.FORMULARIO_ALTERN_NIVEL_ID, NULL)) CONTA_NIVEL_INSATISFACAO

            FROM
                TBFORMULARIO_RESPOSTA FR
                LEFT JOIN TBFORMULARIO_ALTERNATIVA FA
                     ON FA.FORMULARIO_ID = FR.FORMULARIO_ID
                    AND FA.ID = FR.ALTERNATIVA_ESCOLHIDA_ID
                INNER JOIN TBFORMULARIO_PERGUNTA FP
                     ON FP.FORMULARIO_ID = FR.FORMULARIO_ID
                    AND FP.ID = FR.FORMULARIO_PERGUNTA_ID

            WHERE
                FR.STATUSEXCLUSAO = '0'
            AND FR.USUARIO_ID IS NOT NULL
            AND FR.FORMULARIO_ID = :FORMULARIO_ID

            GROUP BY
                1,2,3,4

            ORDER BY 
                FP.ORDEM
        ";

        $args = [
            ':FORMULARIO_ID' => $formulario_id
        ];

        return $con->query($sql, $args);

    }

    public static function painelAlternativa($formulario_id, _Conexao $con) {

        $sql = "
            SELECT
                FA.FORMULARIO_ID,
                FA.FORMULARIO_PERGUNTA_ID,
                FA.ID ALTERNATIVA_ID,
                FA.DESCRICAO ALTERNATIVA_DESCRICAO,
                COALESCE(
                    (SELECT SUM(1) 
                        FROM TBFORMULARIO_RESPOSTA FR 
                        WHERE 
                            FR.ALTERNATIVA_ESCOLHIDA_ID = FA.ID
                        AND FR.STATUSEXCLUSAO = '0'
                    )
                , 0) ALTERNATIVA_QTD_ESCOLHIDA

            FROM
                TBFORMULARIO_ALTERNATIVA FA                

            WHERE
                FA.STATUSEXCLUSAO = '0'
            AND FA.FORMULARIO_ID = :FORMULARIO_ID
        ";

        $args = [
            ':FORMULARIO_ID' => $formulario_id
        ];

        return $con->query($sql, $args);

    }


    public static function listarPainelCliente($param, $con) {

        return [
            'RESUMO'                => self::painelClienteResumo($param, $con),
            'DESTINATARIO'          => self::painelClienteDestinatario($param, $con),
            'SATISFACAO_PERGUNTA'   => self::painelClienteSatisfacaoPergunta($param, $con),
            'PERGUNTA'              => self::painelPergunta($param->formulario_id, $con),
            'ALTERNATIVA'           => self::painelAlternativa($param->formulario_id, $con)
        ];
    }

    public static function painelClienteResumo($param, $con) {

        $sql = "
            SELECT
                COUNT(DISTINCT P.ID) QTD_PESQUISA,
                AVG(P.SATISFACAO) MEDIA_SATISFACAO,
                AVG(P.NOTA_DELFA) MEDIA_DELFA

            FROM
                TBFORMULARIO_PESQ_CLIENTE P
                LEFT JOIN TBCLIENTE C ON C.CODIGO = P.CLIENTE_ID

            WHERE
                P.STATUSEXCLUSAO = '0'
            AND P.FORMULARIO_ID = :FORMULARIO_ID
            AND P.DATAHORA_INSERT BETWEEN :DATA_INI AND :DATA_FIM
            AND IIF(CAST(:REPRESENTANTE_ID_0 AS INTEGER) IS NULL, TRUE, C.REPRESENTANTE_CODIGO = :REPRESENTANTE_ID)
            AND IIF(CAST(:UF_0 AS VARCHAR(2)) IS NULL, TRUE, C.UF = :UF)
        ";

        $args = [
            ':FORMULARIO_ID'        => $param->formulario_id,
            ':DATA_INI'             => $param->data_ini,
            ':DATA_FIM'             => $param->data_fim,
            ':REPRESENTANTE_ID_0'   => $param->representante->CODIGO,
            ':REPRESENTANTE_ID'     => $param->representante->CODIGO,
            ':UF_0'                 => $param->uf->UF,
            ':UF'                   => $param->uf->UF
        ];

        return $con->query($sql, $args);
    }

    public static function painelClienteDestinatario($param, $con) {

        $sql = "
            SELECT
                P.ID,
                P.FORMULARIO_ID,
                P.CLIENTE_ID,
                C.RAZAOSOCIAL CLIENTE_RAZAOSOCIAL,
                C.UF CLIENTE_UF,
                P.SATISFACAO,
                P.NOTA_DELFA,
                P.OBSERVACAO_DELFA

            FROM
                TBFORMULARIO_PESQ_CLIENTE P
                LEFT JOIN TBCLIENTE C ON C.CODIGO = P.CLIENTE_ID

            WHERE
                P.STATUSEXCLUSAO = '0'
            AND P.FORMULARIO_ID = :FORMULARIO_ID
            AND P.DATAHORA_INSERT BETWEEN :DATA_INI AND :DATA_FIM
            AND IIF(CAST(:REPRESENTANTE_ID_0 AS INTEGER) IS NULL, TRUE, C.REPRESENTANTE_CODIGO = :REPRESENTANTE_ID)
            AND IIF(CAST(:UF_0 AS VARCHAR(2)) IS NULL, TRUE, C.UF = :UF)
        ";

        $args = [
            ':FORMULARIO_ID'        => $param->formulario_id,
            ':DATA_INI'             => $param->data_ini,
            ':DATA_FIM'             => $param->data_fim,
            ':REPRESENTANTE_ID_0'   => $param->representante->CODIGO,
            ':REPRESENTANTE_ID'     => $param->representante->CODIGO,
            ':UF_0'                 => $param->uf->UF,
            ':UF'                   => $param->uf->UF

        ];

        return $con->query($sql, $args);
    }

    public static function painelClienteSatisfacaoPergunta($param, $con) {

        $sql = "
            SELECT
                FSP.FORMULARIO_PERGUNTA_ID, 
                FSP.FORMULARIO_PERGUNTA_ORDEM,
                FSP.PERC_SATISF
            FROM 
                SPC_FORMULARIO_SAT_PERG_CLIENTE(
                    :FORMULARIO_ID, 
                    :DATA_INI, 
                    :DATA_FIM,
                    :REPRESENTANTE_ID,
                    :UF
                ) FSP
        ";

        $args = [
            ':FORMULARIO_ID'    => $param->formulario_id,
            ':DATA_INI'         => $param->data_ini,
            ':DATA_FIM'         => $param->data_fim,
            ':REPRESENTANTE_ID' => $param->representante->CODIGO,
            ':UF'               => $param->uf->UF
        ];

        return $con->query($sql, $args);
    }

    public static function consultarUF($con) {

        $sql = "
            SELECT
                U.UF,
                U.DESCRICAO

            FROM
                TBUF U
        ";

        return $con->query($sql);
    }

    /**
     * Listar tipos de formulário.
     * @param _Conexao $con
     */
    public static function listarTipoFormulario(_Conexao $con) {

        $sql = "
            SELECT
                FT.ID,
                FT.DESCRICAO

            FROM
                TBFORMULARIO_TIPO FT
            
            WHERE
                FT.STATUSEXCLUSAO = '0'
        ";

        return $con->query($sql);

    }

    /**
     * Listar tipos de resposta.
     * @param _Conexao $con
     */
    public static function listarTipoResposta(_Conexao $con) {

        $sql = "
            SELECT
                RT.ID,
                RT.DESCRICAO

            FROM
                TBFORMULARIO_RESPOSTA_TIPO RT

            WHERE
                RT.STATUSEXCLUSAO = '0'
        ";

        return $con->query($sql);

    }

    /**
     * Listar níveis de satisfação das alternativas.
     * @param _Conexao $con
     */
    public static function listarNivelSatisfacao(_Conexao $con) {

        $sql = "
            SELECT
                CAST(FN.ID AS VARCHAR(50)) ID,
                FN.DESCRICAO

            FROM
                TBFORMULARIO_ALTERN_NIVEL FN

            WHERE
                FN.STATUSEXCLUSAO = '0'
        ";

        return $con->query($sql);

    }


    /**
     * Gerar id do objeto.
     * @param _Conexao $con
     * @return integer
     */
    public static function gerarId(_Conexao $con) {

        $sql = 'SELECT GEN_ID(GTBFORMULARIO, 1) ID FROM RDB$DATABASE';

        return $con->query($sql)[0]->ID;
    }

    /**
     * Gerar id da pergunta.
     * @param _Conexao $con
     * @return integer
     */
    public static function gerarIdPergunta(_Conexao $con) {

        $sql = 'SELECT GEN_ID(GTBFORMULARIO_PERGUNTA, 1) ID FROM RDB$DATABASE';

        return $con->query($sql)[0]->ID;
    }

    public static function gravarFormulario($dados, $formulario_id, _Conexao $con) {

        $sql = "
            INSERT INTO TBFORMULARIO (
                ID,
                FORMULARIO_TIPO_ID,
                TITULO,
                DESCRICAO,
                PERIODO_INI,
                PERIODO_FIM,
                USUARIO_ID
            )
            VALUES (
                :ID,
                :TIPO,
                :TITULO,
                :DESCRICAO,
                :PER_INI,
                :PER_FIM,
                :USU_ID
            )
        ";
        
        $args = [
            ':ID'       => $formulario_id,
            ':TIPO'     => $dados['TIPO'],
            ':TITULO'   => $dados['TITULO'],
            ':DESCRICAO'=> $dados['DESCRICAO'],
            ':PER_INI'  => $dados['PERIODO_INI'],
            ':PER_FIM'  => $dados['PERIODO_FIM'],
            ':USU_ID'   => \Auth::user()->CODIGO
        ];

        $con->execute($sql, $args);

    }

    public static function gravarDestinatario($dados, $formulario_id, _Conexao $con) {
        
        $sql = "
            INSERT INTO TBFORMULARIO_DESTINATARIO (
                FORMULARIO_ID,
                USUARIO_ID,
                CCUSTO,
                PESO_RESPOSTA,
                VISUALIZA_CADASTRO
            )
            VALUES(
                :FORM_ID,
                :USU_ID,
                :CCUSTO,
                :PESO,
                :VISUALIZA_CADASTRO
            )
        ";

        $args = [
            ':FORM_ID'              => $formulario_id,
            ':USU_ID'               => isset($dados['USUARIO_ID']) ? $dados['USUARIO_ID'] : null,
            ':CCUSTO'               => isset($dados['CCUSTO']) ? $dados['CCUSTO'] : null,
            ':PESO'                 => $dados['PESO'],
            ':VISUALIZA_CADASTRO'   => $dados['VISUALIZA_CADASTRO']
        ];

        $con->execute($sql, $args);

    }

    public static function gravarPergunta($dados, $formulario_id, $pergunta_id, _Conexao $con) {
        
        $sql = "
            INSERT INTO TBFORMULARIO_PERGUNTA (
                ID,
                FORMULARIO_ID,
                DESCRICAO,
                INDICADOR, 
                TAG,
                FORMULARIO_RESPOSTA_TIPO_ID,
                ORDEM
            )
            VALUES(
                :ID,
                :FORM_ID,
                :DESCRICAO,
                :INDICADOR,
                :TAG,
                :TIPO,
                :ORDEM
            )
        ";

        $args = [
            ':ID'           => $pergunta_id,
            ':FORM_ID'      => $formulario_id,
            ':DESCRICAO'    => $dados['DESCRICAO'],
            ':INDICADOR'    => $dados['INDICADOR'],
            ':TAG'          => $dados['TAG'],
            ':TIPO'         => $dados['TIPO_RESPOSTA'],
            ':ORDEM'        => $dados['ORDEM'] ? $dados['ORDEM'] : 1
        ];

        $con->execute($sql, $args);

    }

    public static function gravarAlternativa($dados, $formulario_id, $pergunta_id, _Conexao $con) {

        $sql = "
            INSERT INTO TBFORMULARIO_ALTERNATIVA (
                FORMULARIO_ID,
                FORMULARIO_PERGUNTA_ID,
                FORMULARIO_ALTERN_NIVEL_ID,
                DESCRICAO,
                DESCRICAO_OBRIGATORIA,
                NOTA
            )
            VALUES(
                :FORM_ID,
                :PERGUNTA_ID,
                :FORMULARIO_ALTERN_NIVEL_ID,
                :DESCRICAO,
                :DESCRICAO_OBRIGATORIA,
                :NOTA
            )
        ";

        $args = [
            ':FORM_ID'                      => $formulario_id,
            ':PERGUNTA_ID'                  => $pergunta_id,
            ':FORMULARIO_ALTERN_NIVEL_ID'   => $dados['NIVEL_SATISFACAO'],
            ':DESCRICAO'                    => $dados['DESCRICAO'],
            ':DESCRICAO_OBRIGATORIA'        => $dados['JUSTIFICATIVA_OBRIGATORIA'],
            ':NOTA'                         => $dados['NOTA']
        ];

        $con->execute($sql, $args);

    }

    /**
     * Alterar formulário.
     */
    public static function alterarFormulario($dados, _Conexao $con) {

        $sql = "
            UPDATE 
                TBFORMULARIO 
            SET
                FORMULARIO_TIPO_ID = :TIPO,
                TITULO = :TITULO,
                DESCRICAO = :DESCRICAO,
                PERIODO_INI = :PER_INI,
                PERIODO_FIM = :PER_FIM
            WHERE
                ID = :ID
        ";
        
        $args = [
            ':TIPO'     => $dados['TIPO'],
            ':TITULO'   => $dados['TITULO'],
            ':DESCRICAO'=> $dados['DESCRICAO'],
            ':PER_INI'  => $dados['PERIODO_INI'],
            ':PER_FIM'  => $dados['PERIODO_FIM'],
            ':ID'       => $dados['ID']
        ];

        $con->execute($sql, $args);

    }

    public static function alterarDestinatario($dados, _Conexao $con) {
        
        $sql = "
            UPDATE OR INSERT INTO TBFORMULARIO_DESTINATARIO (
                ID,
                FORMULARIO_ID,
                USUARIO_ID,
                CCUSTO,
                PESO_RESPOSTA,
                STATUS_RESPOSTA,
                VISUALIZA_CADASTRO
            )
            VALUES (
                :ID,
                :FORM_ID,
                :USU_ID,
                :CCUSTO,
                :PESO,
                :STATUS_RESPOSTA,
                :VISUALIZA_CADASTRO
            )
            MATCHING (ID)
        ";

        $args = [
            ':ID'                   => isset($dados['DESTINATARIO_ID']) ? $dados['DESTINATARIO_ID'] : 0,
            ':FORM_ID'              => $dados['FORMULARIO_ID'],
            ':USU_ID'               => isset($dados['USUARIO_ID']) ? $dados['USUARIO_ID'] : null,
            ':CCUSTO'               => isset($dados['CCUSTO']) ? $dados['CCUSTO'] : null,
            ':PESO'                 => $dados['PESO'],
            ':STATUS_RESPOSTA'      => $dados['STATUS_RESPOSTA'],
            ':VISUALIZA_CADASTRO'   => $dados['VISUALIZA_CADASTRO']
        ];

        $con->execute($sql, $args);

    }

    public static function excluirDestinatario($dados, _Conexao $con) {
        
        $sql = "
            UPDATE TBFORMULARIO_DESTINATARIO
            SET STATUSEXCLUSAO = '1'
            WHERE ID = :ID
        ";

        $args = [
            ':ID' => $dados['DESTINATARIO_ID']
        ];

        $con->execute($sql, $args);

    }

    public static function alterarPergunta($dados, $pergunta_id, $formulario_id, _Conexao $con) {
        
        $sql = "
            UPDATE OR INSERT INTO TBFORMULARIO_PERGUNTA (
                ID,
                FORMULARIO_ID,
                DESCRICAO,
                INDICADOR,
                TAG,
                FORMULARIO_RESPOSTA_TIPO_ID,
                ORDEM
            )
            VALUES (
                :ID,
                :FORM_ID,
                :DESCRICAO,
                :INDICADOR,
                :TAG,
                :TIPO,
                :ORDEM
            )
            MATCHING(ID)
        ";

        $args = [
            ':ID'           => $pergunta_id,
            ':FORM_ID'      => isset($dados['FORMULARIO_ID']) ? $dados['FORMULARIO_ID'] : $formulario_id,
            ':DESCRICAO'    => $dados['DESCRICAO'],
            ':INDICADOR'    => $dados['INDICADOR'],
            ':TAG'          => $dados['TAG'],
            ':TIPO'         => $dados['TIPO_RESPOSTA'],
            ':ORDEM'        => $dados['ORDEM'] ? $dados['ORDEM'] : 1
        ];

        $con->execute($sql, $args);

    }

    public static function excluirPergunta($dados, _Conexao $con) {
        
        $sql = "
            DELETE FROM TBFORMULARIO_PERGUNTA WHERE ID = :ID
        ";

        $args = [
            ':ID' => $dados['PERGUNTA_ID']
        ];

        $con->execute($sql, $args);

    }

    public static function alterarAlternativa($dados, $formulario_id, $pergunta_id, _Conexao $con) {

        $sql = "
            UPDATE OR INSERT INTO TBFORMULARIO_ALTERNATIVA (
                ID,
                FORMULARIO_ID,
                FORMULARIO_PERGUNTA_ID,
                FORMULARIO_ALTERN_NIVEL_ID,
                DESCRICAO,
                DESCRICAO_OBRIGATORIA,
                NOTA
            )
            VALUES (
                :ID,
                :FORM_ID,
                :PERG_ID,
                :FORMULARIO_ALTERN_NIVEL_ID,
                :DESCRICAO,
                :DESCRICAO_OBRIGATORIA,
                :NOTA
            )
            MATCHING(ID)
        ";

        $args = [
            ':ID'                           => isset($dados['ID']) ? $dados['ID'] : 0,
            ':FORM_ID'                      => isset($dados['FORMULARIO_ID']) ? $dados['FORMULARIO_ID'] : $formulario_id,
            ':PERG_ID'                      => isset($dados['FORMULARIO_PERGUNTA_ID']) ? $dados['FORMULARIO_PERGUNTA_ID'] : $pergunta_id,
            ':FORMULARIO_ALTERN_NIVEL_ID'   => $dados['NIVEL_SATISFACAO'],
            ':DESCRICAO'                    => $dados['DESCRICAO'],
            ':DESCRICAO_OBRIGATORIA'        => $dados['JUSTIFICATIVA_OBRIGATORIA'],
            ':NOTA'                         => $dados['NOTA']
        ];

        $con->execute($sql, $args);

    }

    public static function excluirAlternativa($dados, _Conexao $con) {
        
        $sql = "
            DELETE FROM TBFORMULARIO_ALTERNATIVA WHERE ID = :ID
        ";

        $args = [
            ':ID' => $dados['ALTERNATIVA_ID']
        ];

        $con->execute($sql, $args);

    }

    /**
     * Excluir formulários.
     * @access public
     * @param Array $id
     * @param _Conexao $con
     * @return array
     */
    public static function excluirFormulario($id, _Conexao $con) {
        
        $id_list = "F.ID IN(". arrayToList($id, 9999999999999) .")";

        $sql = "
            UPDATE
                TBFORMULARIO F
            SET
                F.STATUSEXCLUSAO = '1'
            WHERE
                /*@ID*/
        ";

        $args = [
            '@ID' => $id_list 
        ];

        $con->execute($sql, $args);

    }
	
}