<?php

namespace App\Models\DAO\Ppcp;

use App\Models\Conexao\_Conexao;

/**
 * DAO do objeto _22120 - Estrutura Analítica de Remessas
 */
class _22120DAO {


    public static function selectRemessasTalaoVinculo($param, _Conexao $con = null)
    {
		$con = $con ? $con : new _Conexao;
        
        $sql = "
            SELECT
                Y.ID,
                Y.REMESSA_ID_ORIGEM,
                Y.REMESSA_ID,
                Y.REMESSA,
                Y.REMESSA_NIVEL,
                Y.REMESSA_GP_ID,
                Y.REMESSA_GP_DESCRICAO,
                Y.REMESSA_DATA,
                Y.REMESSA_DATA_TEXT,
                Y.REMESSA_WEB,
                Y.REMESSA_TALAO_ID,
                Y.FAMILIA_ID,
                Y.FAMILIA_DESCRICAO,
                Y.MODELO_ID,
                Y.MODELO_DESCRICAO,
                Y.GRADE_ID,
                Y.TAMANHO,
                COALESCE(
                    (SELECT FIRST 1 TAM_DESCRICAO
                       FROM SP_TAMANHO_GRADE(Y.GRADE_ID,Y.TAMANHO)),'')TAMANHO_DESCRICAO,
                Y.COR_DADOS,
                Y.QUANTIDADE,
                Y.UM,
                Y.QUANTIDADE_ALTERNATIVA,
                Y.UM_ALTERNATIVA,
                Y.QUANTIDADE_SOBRA_APROVEITAMENTO,
                Y.QUANTIDADE_POR_PLACA * APROVEITAMENTO_PLACA QUANTIDADE_APROVEITAMENTO,
                Y.GP_ID,
                Y.GP_DESCRICAO,
                Y.GP_PERFIL,
                Y.UP_ID,
                Y.UP_DESCRICAO,
                Y.ESTACAO,
                Y.ESTACAO_DESCRICAO,
                Y.DENSIDADE,
                Y.ESPESSURA,
                Y.HORA_PRODUCAO,
                Y.STATUS,
                Y.STATUS_DESCRICAO,
                Y.PROGRAMACAO_DADOS,
                Y.VINCULOS,
                ('') ORIGEM,
                Y.COR_ID,
                IIF(Y.COR_DESCRICAO = '',Y.CORES,Y.COR_DESCRICAO) COR_DESCRICAO,
                Y.CORES,
                Y.COR_AMOSTRA,
                IIF(Y.COR_AMOSTRA2 = 0,Y.COR_AMOSTRA,Y.COR_AMOSTRA2)COR_AMOSTRA2,
                Y.TEMPO,
                Y.TEMPO_REALIZADO,
                Y.PROGRAMACAO_STATUS,
                Y.PROGRAMACAO_STATUS_DESCRICAO


            FROM
                (SELECT
                    X.*,                    
                    LPAD(CAST(SUBSTRING(X.COR_DADOS FROM  1 FOR 20) AS INTEGER),4,'0') COR_ID,
                    CAST(COALESCE(SUBSTRING(X.COR_DADOS FROM  21 FOR 20),'') AS VARCHAR(20)) COR_DESCRICAO,
                    CAST(SUBSTRING(X.COR_DADOS FROM 41 FOR 30) AS INTEGER) COR_AMOSTRA,
                    CAST(IIF(SUBSTRING(X.COR_DADOS FROM 71 FOR 30) = '', 0, SUBSTRING(X.COR_DADOS FROM 71 FOR 30)) AS INTEGER) COR_AMOSTRA2,

                    CAST(IIF(COALESCE(SUBSTRING(X.COR_DADOS FROM  21 FOR 20),'') = '',
                        (SELECT LIST(DISTINCT C1.DESCRICAO,', ')
                           FROM VWREMESSA_TALAO_DETALHE D1,
                                TBPRODUTO P1,
                                TBCOR C1
                          WHERE D1.PRODUTO_ID = P1.CODIGO
                            AND P1.COR_CODIGO = C1.CODIGO
                            AND D1.REMESSA_ID = X.REMESSA_ID
                            AND D1.REMESSA_TALAO_ID = X.REMESSA_TALAO_ID),NULL) AS VARCHAR(500)) CORES,

                    CAST(COALESCE(SUBSTRING(X.PROGRAMACAO_DADOS FROM  1 FOR 20),0) AS NUMERIC(15,4)) TEMPO,
                    CAST(COALESCE(SUBSTRING(X.PROGRAMACAO_DADOS FROM  21 FOR 20),0) AS NUMERIC(15,4)) TEMPO_REALIZADO,
                    CAST(COALESCE(SUBSTRING(X.PROGRAMACAO_DADOS FROM  41 FOR 1),'') AS VARCHAR(1)) PROGRAMACAO_STATUS,
                    CAST(COALESCE(SUBSTRING(X.PROGRAMACAO_DADOS FROM  42 FOR 20),'') AS VARCHAR(20)) PROGRAMACAO_STATUS_DESCRICAO,
                    
                    COALESCE((
                        SELECT SUM(S.QUANTIDADE)
                          FROM TBREQUISICAO_SOBRA S
                         WHERE S.REMESSA = X.REMESSA_ID
                           AND S.TALAO = X.REMESSA_TALAO_ID
                           AND S.REQUISICAO_ID = 0), 0.0000) QUANTIDADE_SOBRA_APROVEITAMENTO
                FROM
                    (SELECT
                        TRIM((SELECT FIRST 1 GP.PERFIL
                                FROM TBGP GP
                               WHERE GP.ID = T.GP_ID)) GP_PERFIL,
                        COALESCE(
                            (SELECT
                                LIST(DISTINCT '[' ||
                                    (SELECT FIRST 1 GP.PERFIL
                                       FROM TBGP GP
                                      WHERE GP.ID = R2.GP_ID) || '/' ||
                                     LPAD(V1.REMESSA_TALAO_ID,4,'0') || ']',', ') VINCULO
                              FROM VWREMESSA_CONSUMO C, VWREMESSA R1, TBREMESSA_CONSUMO_VINCULO V1, VWREMESSA R2
                             WHERE C.REMESSA_ID = R1.REMESSA_ID
                               AND V1.CONSUMO_ID = C.ID
                               AND R2.REMESSA_ID = V1.REMESSA_ID
                               AND R1.REMESSA_ID = V.REMESSA_ID
                               AND C.REMESSA_TALAO_ID = T.REMESSA_TALAO_ID),'')VINCULOS,
                        V.REMESSA_ID_ORIGEM,
                        T.ID,
                        V.REMESSA_ID,
                        V.REMESSA,
                        V.REMESSA_NIVEL,
                        R.DATA REMESSA_DATA,
                        TRIM(R.WEB) REMESSA_WEB,
                        R.GP_ID REMESSA_GP_ID,
                        (SELECT FIRST 1 DESCRICAO
                           FROM TBGP G
                          WHERE G.ID = R.GP_ID) REMESSA_GP_DESCRICAO,
                        FN_DATE_TO_STRING(R.DATA) REMESSA_DATA_TEXT,
                        LPAD(T.REMESSA_TALAO_ID,4,'0') REMESSA_TALAO_ID,
                        LPAD(F.CODIGO,3,'0') FAMILIA_ID,
                        F.DESCRICAO FAMILIA_DESCRICAO,
                        LPAD(T.MODELO_ID,4,'0') MODELO_ID,
                        M.DESCRICAO MODELO_DESCRICAO,
                        M.GRADE_CODIGO GRADE_ID,
                        IIF(COALESCE(T.TAMANHO,0) = 0,(SELECT FIRST 1 TAMANHO
                           FROM VWREMESSA_TALAO_DETALHE 
                          WHERE REMESSA_ID = T.REMESSA_ID
                            AND REMESSA_TALAO_ID = T.REMESSA_TALAO_ID),T.TAMANHO) TAMANHO,
                    
                        (SELECT
                            RPAD(C.CODIGO,20) ||
                            RPAD(C.DESCRICAO,20) || 
                            COALESCE(
                                (SELECT FIRST 2 LIST(LPAD(C1.AMOSTRA,30),'')
                                   FROM TBCOR C1, TBCOR_COMPOSICAO CC
                                  WHERE CC.COR_ID = C.CODIGO
                                    AND CC.COR_COMPOSICAO_ID = C1.CODIGO),
                                 LPAD(C.AMOSTRA,30))CORES
                          FROM TBCOR C, TBPRODUTO PR
                         WHERE C.CODIGO = PR.COR_CODIGO
                           AND PR.CODIGO = T.PRODUTO_ID)COR_DADOS,
                    
                        COALESCE(T.QUANTIDADE,0) QUANTIDADE,          
                        (SELECT FIRST 1 PP.UNIDADEMEDIDA_SIGLA
                           FROM VWREMESSA_TALAO_DETALHE D,
                                TBPRODUTO PP
                          WHERE D.REMESSA_ID       = T.REMESSA_ID
                            AND D.REMESSA_TALAO_ID = T.REMESSA_TALAO_ID
                            AND PP.CODIGO           = D.PRODUTO_ID) UM,
                        COALESCE(T.QUANTIDADE_ALTERNATIVA,0) QUANTIDADE_ALTERNATIVA,
                        COALESCE(F.UNIDADEMEDIDA_ALTERNATIVO,'') UM_ALTERNATIVA,
                        LPAD(T.GP_ID,3,'0') GP_ID,
                        COALESCE(G.DESCRICAO,'') GP_DESCRICAO,
                        LPAD(T.UP_ID,3,'0') UP_ID,
                        COALESCE((SELECT FIRST 1 DESCRICAO
                                    FROM TBUP U
                                   WHERE U.ID = T.UP_ID),G.DESCRICAO) UP_DESCRICAO,
                        LPAD(T.ESTACAO,2,'0') ESTACAO,
                        (SELECT FIRST 1 DESCRICAO
                           FROM TBSUB_UP S
                          WHERE S.UP_ID = T.UP_ID
                            AND S.ID    = T.ESTACAO) ESTACAO_DESCRICAO,
                        T.DENSIDADE,
                        T.ESPESSURA,
                        T.HORA_PRODUCAO,
                        T.STATUS,
                       (CASE
                            T.STATUS
                        WHEN 1 THEN 'EM ABERTO'
                        WHEN 2 THEN 'CORTADO'
                        WHEN 3 THEN 'LIBERADO'
                        ELSE 'INDEFINIDO' END) STATUS_DESCRICAO,
                    
                        (SELECT FIRST 1
                            LPAD(COALESCE(P.TEMPO,0),20) ||
                            LPAD(COALESCE(P.TEMPO_REALIZADO,0),20) ||
                            LPAD(P.STATUS,1) ||
                            RPAD(

                            TRIM(
                            IIF(P.STATUS = 0 AND T.STATUS = 2,'CORTADO',
                            IIF(P.STATUS = 0 AND T.STATUS = 3,'LIBERADO',
                            (CASE P.STATUS
                            WHEN '0' THEN 'NÃO INICIADO'
                            WHEN '1' THEN 'PARADO'
                            WHEN '2' THEN 'EM ANDAMENTO'
                            WHEN '3' THEN 'FINALIZADO'
                            WHEN '6' THEN 'ENCERRADO'
                            ELSE 'INDEFINIDO' END))))
                            
                            ,20)
                        
                        FROM
                            TBPROGRAMACAO P
                        
                        WHERE
                            P.TABELA_ID = T.ID
                        AND P.TIPO = 'A')PROGRAMACAO_DADOS,
                        T.APROVEITAMENTO_PLACA,
                        (SELECT QUANTIDADE
                           FROM SPC_REMESSA_CONSUMO_MATRIZ(M.MATRIZ_CODIGO,M.GRADE_CODIGO,T.TAMANHO)) QUANTIDADE_POR_PLACA
                    
                    
                    FROM
                        (SELECT * FROM SPC_REMESSAS_VINCULO1 (:REMESSA,0)) V,
                        VWREMESSA R,
                        VWREMESSA_TALAO T,
                        TBMODELO M,
                        TBFAMILIA F,
                        TBGP G
                    
                    WHERE
                        R.REMESSA_ID = V.REMESSA_ID
                    AND T.REMESSA_ID = V.REMESSA_ID
                    AND M.CODIGO           = T.MODELO_ID
                    AND F.CODIGO           = M.FAMILIA_CODIGO
                    AND G.ID               = T.GP_ID
                    )X
                )Y     
            ORDER BY REMESSA_NIVEL,REMESSA_TALAO_ID            
        ";
        
        $args = [
            ':REMESSA' => $param->REMESSA,
        ];
        
        return $con->query($sql,$args);
    }
	
    public static function selectRemessas($param, _Conexao $con = null)
    {
		$con = $con ? $con : new _Conexao;
        
        $sql = "
            SELECT /*@FIRST*/ /*@SKIP*/
                REMESSA,
                REMESSA_ID,
                DATA,
                FN_DATE_TO_STRING(DATA) DATA_TEXT,
                FAMILIA_ID,
                FAMILIA_DESCRICAO,
                UM,        
                WEB,
                CASE WEB
                WHEN '0' THEN 'GC 1.0'
                WHEN '1' THEN 'GC/WEB'
                WHEN '2' THEN 'GC 2.0'
                ELSE '#N/D' END WEB_DESCRICAO,
                TIPO,  
                TRIM((CASE TIPO
                WHEN '1' THEN 'NORMAL'
                WHEN '2' THEN 'VIP'
                WHEN '3' THEN 'REQ'
                ELSE '#ND' END)) TIPO_DESCRICAO,
                STATUS,
                USUARIO_ID,
                USUARIO_DESCRICAO

            FROM
                (SELECT
                    R.REMESSA,
                    R.REMESSA_ID,
                    R.DATA,
                    IIF(R.WEB = '1' AND R.DESCRICAO IS NOT NULL,2,R.WEB) WEB,
                    LPAD(F.CODIGO,3,'0') FAMILIA_ID,
                    F.DESCRICAO FAMILIA_DESCRICAO,
                    f.UNIDADEMEDIDA_SIGLA UM,
                    (SELECT FIRST 1 ID FROM TBREMESSA_CONSUMO_VINCULO V WHERE V.REMESSA_ID = R.REMESSA_ID) VINC,

                    /* 1 - NORMAL     */ CAST(IIF(R.TIPO = '1', '1',
                    /* 2 - VIP        */ IIF(R.TIPO = '2' AND R.REQUISICAO = '0','2',
                    /* 3 - REQUISICAO */ IIF(R.TIPO = '2' AND R.REQUISICAO = '1','3','0'))) AS VARCHAR(1))TIPO,
                    R.STATUS,
                    LPAD(R.USUARIO_ID,4,'0') USUARIO_ID,
                    UPPER((SELECT FIRST 1 IIF(TRIM(U.NOME) = '', U.USUARIO, U.NOME) FROM TBUSUARIO U WHERE U.CODIGO = R.USUARIO_ID)) USUARIO_DESCRICAO
                FROM
                    VWREMESSA R,
                    TBFAMILIA F
                WHERE
                    F.CODIGO = R.FAMILIA_ID
                /*@PERIODO*/
                /*@REMESSA*/
                    )X

            WHERE
                X.VINC IS NULL
                
            ORDER BY DATA DESC, REMESSA DESC
        ";
        
        
        $periodo = '';
		//se o 'período' e 'turno' forem passados
		if ( array_key_exists('DATA_1', $param) && array_key_exists('DATA_2', $param) ) {
			$periodo = "AND R.DATA BETWEEN '$param->DATA_1' AND '$param->DATA_2'";
		}        
        
        $args = [
            '@PERIODO' => $periodo,
            '@FIRST'   => array_key_exists('FIRST', $param) ? "FIRST $param->FIRST " : '',
            '@SKIP'    => array_key_exists('SKIP' , $param) ? "SKIP  $param->SKIP  " : '',
            '@REMESSA' => array_key_exists('REMESSA' , $param) ? "AND REMESSA LIKE UPPER('%" . str_replace(' ', '%', $param->REMESSA) . "%')" : ''
        ];
        
        return $con->query($sql,$args);
    }
	
    public static function selectRemessaTalaoDetalhe($param, _Conexao $con = null)
    {
		$con = $con ? $con : new _Conexao;

        $sql = "
            SELECT
                Y.ID,
                Y.REMESSA_ID,
                Y.REMESSA_TALAO_ID,
                Y.PECA_CONJUNTO,
                Y.PERFIL,
                Y.PRODUTO_ID,
                Y.PRODUTO_DESCRICAO,
                Y.TAMANHO,
                Y.TAMANHO_DESCRICAO,
                Y.MODELO_ID,
                Y.COR_ID,
                Y.COR_DESCRICAO,
                Y.COR_AMOSTRA,
                IIF(Y.COR_AMOSTRA2 = 0,Y.COR_AMOSTRA,Y.COR_AMOSTRA2)COR_AMOSTRA2,
                Y.QUANTIDADE,
                Y.QUANTIDADE_PRODUCAO,
                Y.UM,
                Y.QUANTIDADE_ALTERN,
                Y.QUANTIDADE_ALTERN_PRODUCAO,
                Y.UM_ALTERNATIVA,
                Y.STATUS,
                Y.STATUS_DESCRICAO,
                Y.DATAHORA_PRODUCAO
            
            FROM
                (SELECT
                    X.*,
                    CAST(SUBSTRING(X.COR_DADOS FROM  1 FOR 20) AS VARCHAR(20)) COR_DESCRICAO,
                    CAST(SUBSTRING(X.COR_DADOS FROM 21 FOR 30) AS INTEGER) COR_AMOSTRA,
                    CAST(IIF(SUBSTRING(X.COR_DADOS FROM 52 FOR 30) = '', 0, SUBSTRING(X.COR_DADOS FROM 52 FOR 30)) AS INTEGER) COR_AMOSTRA2
                
                FROM
                    (SELECT
                        D.ID,
                        D.REMESSA_ID,
                        LPAD(D.REMESSA_TALAO_ID,4,'0') REMESSA_TALAO_ID,
                        D.PECA_CONJUNTO,
                        LPAD(D.PRODUTO_ID,6,'0') PRODUTO_ID,
                        P.DESCRICAO PRODUTO_DESCRICAO,
                        P.MODELO_CODIGO MODELO_ID,
                        LPAD(D.COR_ID,4,'0') COR_ID,
                        (SELECT
                            RPAD(C.DESCRICAO,20) || 
                            COALESCE(
                                (SELECT FIRST 2 LIST(LPAD(C1.AMOSTRA,30),'')
                                   FROM TBCOR C1, TBCOR_COMPOSICAO CC
                                  WHERE CC.COR_ID = C.CODIGO
                                    AND CC.COR_COMPOSICAO_ID = C1.CODIGO),
                                 LPAD(C.AMOSTRA,30))CORES
                          FROM TBCOR C
                         WHERE C.CODIGO = P.COR_CODIGO)COR_DADOS,
                    
                        D.TAMANHO,
                        COALESCE(
                            (SELECT FIRST 1 TAM_DESCRICAO
                               FROM SP_TAMANHO_GRADE(P.GRADE_CODIGO,D.TAMANHO)),'')TAMANHO_DESCRICAO,
                        D.PERFIL,
                        D.QUANTIDADE,
                        IIF(D.STATUS < 3, D.QUANTIDADE_PRODUCAO_TMP, D.QUANTIDADE_PRODUCAO) QUANTIDADE_PRODUCAO,
                        F.UNIDADEMEDIDA_SIGLA UM,
                        D.QUANTIDADE_ALTERN,
                        IIF(D.STATUS < 3, D.QUANTIDADE_ALTERN_PRODUCAO_TMP, D.QUANTIDADE_ALTERN_PRODUCAO) QUANTIDADE_ALTERN_PRODUCAO,
                        F.UNIDADEMEDIDA_ALTERNATIVO UM_ALTERNATIVA,
                        D.STATUS,   
                        (CASE
                            D.STATUS
                        WHEN 1 THEN 'EM ABERTO'
                        WHEN 2 THEN 'EM PRODUÇÃO'
                        WHEN 3 THEN 'PRODUZIDO'
                        WHEN 6 THEN 'ENCERRADO'
                        ELSE 'INDEFINIDO' END) STATUS_DESCRICAO,
                        D.DATAHORA_PRODUCAO
                    
                    
                    FROM
                        VWREMESSA R,
                        VWREMESSA_TALAO_DETALHE D,
                        TBPRODUTO P,
                        TBFAMILIA F
                    
                    WHERE
                        D.REMESSA_ID = R.REMESSA_ID
                    AND P.CODIGO     = D.PRODUTO_ID
                    AND F.CODIGO     = P.FAMILIA_CODIGO
                    AND R.REMESSA = :REMESSA)X)Y
        ";
        
        $args = [
            ':REMESSA' => $param->REMESSA,
        ];
        
        return $con->query($sql,$args);
    }
	

    public static function selectRemessaTalaoMaxId($param, _Conexao $con = null)
    {
		$con = $con ? $con : new _Conexao;

        $sql = "
            SELECT
                MAX(T.REMESSA_TALAO_ID) MAX_ID
            FROM
                VWREMESSA_TALAO T
            WHERE
                T.REMESSA_ID = :REMESSA_ID
        ";
        
        $args = [
            ':REMESSA_ID' => $param->REMESSA_ID,
        ];
        
        return $con->query($sql,$args);
    }
	
    public static function selectRemessaConsumo($param, _Conexao $con = null)
    {
		$con = $con ? $con : new _Conexao;

        $sql = "
            SELECT
                Y.ID,
                Y.REMESSA_ID,
                Y.REMESSA_TALAO_ID,
                Y.CONTROLE,
                Y.FAMILIA_ID,
                Y.PRODUTO_ID,
                Y.PRODUTO_DESCRICAO,
                Y.TAMANHO,
                Y.TAMANHO_DESCRICAO,
                Y.QUANTIDADE,
                Y.QUANTIDADE_CONSUMO,
                Y.UM,
                Y.QUANTIDADE_ALTERNATIVA,
                Y.QUANTIDADE_ALTERNATIVA_CONSUMO,
                Y.UM_ALTERNATIVA,
                Y.COMPONENTE,
                Y.COMPONENTE_DESCRICAO,
                Y.STATUS,
                Y.STATUS_DESCRICAO,
                (SELECT FIRST 1 LOCALIZACAO_CODIGO FROM TBFAMILIA WHERE CODIGO = Y.FAMILIA_ID) LOCALIZACAO_ID,
                Y.COR_ID,
                Y.COR_DESCRICAO,
                Y.COR_AMOSTRA,
                IIF(Y.COR_AMOSTRA2 = 0,Y.COR_AMOSTRA,Y.COR_AMOSTRA2)COR_AMOSTRA2,
                VINCULOS
            
            FROM
                (SELECT
                    X.*,
                    CAST(SUBSTRING(X.COR_DADOS FROM  1 FOR 20) AS VARCHAR(20)) COR_DESCRICAO,
                    CAST(SUBSTRING(X.COR_DADOS FROM 21 FOR 30) AS INTEGER) COR_AMOSTRA,
                    CAST(IIF(SUBSTRING(X.COR_DADOS FROM 52 FOR 30) = '', 0, SUBSTRING(X.COR_DADOS FROM 52 FOR 30)) AS INTEGER) COR_AMOSTRA2
                
                FROM
                    (SELECT
                        C.ID,
                        C.REMESSA_ID,
                        LPAD(C.REMESSA_TALAO_ID,4,'0') REMESSA_TALAO_ID,
                        C.CONTROLE,
                        F.CODIGO FAMILIA_ID,
                        LPAD(C.PRODUTO_ID,6,'0') PRODUTO_ID,
                        P.DESCRICAO PRODUTO_DESCRICAO,
                        LPAD(P.COR_CODIGO,4,'0') COR_ID,
                        (SELECT
                            RPAD(CR.DESCRICAO,20) ||
                            COALESCE(
                                (SELECT FIRST 2 LIST(LPAD(C1.AMOSTRA,30),'')
                                   FROM TBCOR C1, TBCOR_COMPOSICAO CC
                                  WHERE CC.COR_ID = CR.CODIGO
                                    AND CC.COR_COMPOSICAO_ID = C1.CODIGO),
                                 LPAD(CR.AMOSTRA,30))CORES
                          FROM TBCOR CR
                         WHERE CR.CODIGO = P.COR_CODIGO)COR_DADOS,
                    
                        C.TAMANHO,
                        COALESCE(
                            (SELECT FIRST 1 TAM_DESCRICAO
                               FROM SP_TAMANHO_GRADE(P.GRADE_CODIGO,C.TAMANHO)),'')TAMANHO_DESCRICAO,
                        C.QUANTIDADE,
                        C.QUANTIDADE_CONSUMO,
                        F.UNIDADEMEDIDA_SIGLA UM,
                        C.QUANTIDADE_ALTERNATIVA,
                        C.QUANTIDADE_ALTERNATIVA_CONSUMO,
                        F.UNIDADEMEDIDA_ALTERNATIVO UM_ALTERNATIVA,
                        TRIM(C.COMPONENTE) COMPONENTE,
                        TRIM(IIF(C.COMPONENTE = '1','COMPONENTE','MATERIA-PRIMA')) COMPONENTE_DESCRICAO,
                        C.STATUS,
                        TRIM((CASE
                            C.STATUS
                        WHEN '0' THEN 'PENDENTE'
                        WHEN '1' THEN 'CONSUMIDO'
                        ELSE 'INDEFINIDO' END)) STATUS_DESCRICAO,


                       (SELECT LIST('[Talão: '||REMESSA_TALAO_DETALHE_ID || '/ Qtd: ' || FN_FORMAT_NUMBER(QUANTIDADE,4) || ']',', ')
                          FROM TBREMESSA_CONSUMO_VINCULO V
                         WHERE V.CONSUMO_ID = C.ID) VINCULOS                        
                    
                    
                    FROM
                        VWREMESSA R,
                        VWREMESSA_CONSUMO C,
                        TBPRODUTO P,
                        TBFAMILIA F
                    
                    WHERE
                        C.REMESSA_ID = R.REMESSA_ID
                    AND P.CODIGO     = C.PRODUTO_ID
                    AND F.CODIGO     = P.FAMILIA_CODIGO
                    AND R.REMESSA = :REMESSA)X)Y
            ORDER BY Y.FAMILIA_ID,Y.ID
        ";
        
        $args = [
            ':REMESSA' => $param->REMESSA,
        ];
        
        return $con->query($sql,$args);
    }
	
    public static function selectRemessaConsumoAlocacoes($param, _Conexao $con = null)
    {
		$con = $con ? $con : new _Conexao;

        $remessa_id = array_key_exists('REMESSA_ID', $param) ? "AND REMESSA_ID = $param->REMESSA_ID" : '';        
        
        $sql = "
            SELECT
                REMESSA_ID,
                ID,
                TALAO_ID,
                CONSUMO_ID,
                TIPO,
                TABELA_ID,
                PRODUTO_ID,
                PRODUTO_DESCRICAO,
                LPAD(LOCALIZACAO_ID,3,'0')LOCALIZACAO_ID,
                (SELECT FIRST 1 DESCRICAO FROM TBLOCALIZACAO WHERE CODIGO = LOCALIZACAO_ID)LOCALIZACAO_DESCRICAO,
                OB,
                QUANTIDADE,
                QUANTIDADE_ALTERNATIVA,
                UM,
                UM_ALTERNATIVA,
                STATUS
            FROM
                (SELECT
                    T.REMESSA_ID,
                    V.ID,
                    V.TALAO_ID,
                    V.CONSUMO_ID,
                    TRIM(V.TIPO) TIPO,
                    v.TABELA_ID,
                   (CASE
                        V.TIPO
                    WHEN 'R' THEN (SELECT FIRST 1 PRODUTO_ID FROM TBREVISAO WHERE ID = V.TABELA_ID)
                    WHEN 'D' THEN (SELECT FIRST 1 PRODUTO_ID FROM VWREMESSA_TALAO_DETALHE WHERE ID = V.TABELA_ID)
                    ELSE 0 END)PRODUTO_ID,
                    P.DESCRICAO PRODUTO_DESCRICAO,
                   (CASE
                        V.TIPO
                    WHEN 'R' THEN (SELECT FIRST 1 LOCALIZACAO_ENTRADA FROM TBREVISAO WHERE ID = V.TABELA_ID)
                    WHEN 'D' THEN (SELECT FIRST 1 LOCALIZACAO_ID FROM VWREMESSA_TALAO_DETALHE WHERE ID = V.TABELA_ID)
                    ELSE 0 END)LOCALIZACAO_ID,
                    IIF(V.TIPO = 'R', (SELECT FIRST 1 OB FROM TBREVISAO WHERE ID = V.TABELA_ID), '') OB,
                    V.QUANTIDADE,
                    V.QUANTIDADE_ALTERNATIVA,
                    F.UNIDADEMEDIDA_SIGLA UM,
                    F.UNIDADEMEDIDA_ALTERNATIVO UM_ALTERNATIVA,
                    TRIM(V.STATUS) STATUS


                FROM
                    TBREMESSA_TALAO_VINCULO V,
                    TBPRODUTO P,
                    TBFAMILIA F,
                    VWREMESSA_TALAO T

                WHERE
                    P.CODIGO = V.PRODUTO_ID
                AND F.CODIGO = P.FAMILIA_CODIGO
                AND V.TALAO_ID = T.ID)X

            WHERE TRUE
            /*@REMESSA_ID*/
        ";
        
        $args = [
            '@REMESSA_ID' => $remessa_id,
        ];
        
        return $con->query($sql,$args);
    }
	
    public static function selectRemessaConsumoFamilia($param, _Conexao $con = null)
    {
		$con = $con ? $con : new _Conexao;

        $sql = "
            SELECT
                DISTINCT
                LPAD(F.CODIGO,3,'0') FAMILIA_ID,
                F.DESCRICAO FAMILIA_DESCRICAO
            FROM
                VWREMESSA_CONSUMO C,
                TBPRODUTO P,
                TBFAMILIA F
            WHERE
                C.REMESSA_ID = :REMESSA_ID
            AND P.CODIGO = C.PRODUTO_ID
            AND F.CODIGO = P.FAMILIA_CODIGO
        ";
        
        $args = [
            ':REMESSA_ID' => $param->REMESSA_ID,
        ];
        
        return $con->query($sql,$args);
    }
	
    public static function selectSkus($param, _Conexao $con = null)
    {
        $sql = "
            SELECT
                REMESSA_ID,
                MODELO_ID,
                MODELO_DESCRICAO,
                COR_ID,
                COR_DESCRICAO,
                TAMANHO,
                TAMANHO_DESCRICAO,
                PERCENTUAL,
                PERCENTUAL PERCENTUAL_BASE
            FROM (
    
                SELECT
                    REMESSA_ID,
                    MODELO_ID,
                    MODELO_DESCRICAO,
                    COR_ID,
                    COR_DESCRICAO,
                    TAMANHO,
                    TAMANHO_DESCRICAO,
                    CAST(((SELECT PERCENTUAL_DEFEITO FROM SPC_REMESSA_SKU_DEFEITO_PERCENT (MODELO_ID,COR_ID,TAMANHO,REMESSA_ID,3,'1')) * 100) AS NUMERIC(15,2)) PERCENTUAL
                FROM (
                    SELECT
                        T.REMESSA REMESSA_ID,
                        M.CODIGO MODELO_ID,
                        M.DESCRICAO MODELO_DESCRICAO,
                        C.CODIGO COR_ID,
                        C.DESCRICAO COR_DESCRICAO,
                        T.TAMANHO,
                        TAMANHO_GRADE(P.GRADE_CODIGO,T.TAMANHO) TAMANHO_DESCRICAO
    
                    FROM
                        TBREMESSA_ITEM_PROCESSADO T,
                        TBPRODUTO P,
                        TBMODELO M,
                        TBCOR C
    
                    WHERE
                        P.CODIGO = T.PRODUTO_CODIGO
                    AND T.REMESSA = :REMESSA_ID
                    AND M.CODIGO = P.MODELO_CODIGO
                    AND C.CODIGO = P.COR_CODIGO
                    AND (T.QUANTIDADE -
                        COALESCE((
                            SELECT SUM(S.QUANTIDADE)
                              FROM TBREQUISICAO_SOBRA S
                             WHERE S.REMESSA = T.REMESSA
                               AND S.TALAO = T.CONTROLE
                               AND S.REQUISICAO_ID = 0), 0.0000)) > 0
    
                    GROUP BY 1,2,3,4,5,6,7
                    ) X
                ) Y
        ";
        
        $args = [
            ':REMESSA_ID' => $param->REMESSA_ID,
        ];
        
        return $con->query($sql,$args);
    }
	
    public static function selectTaloesExtra($param, _Conexao $con = null)
    {
        $sql = "
            SELECT         
                X.ESTABELECIMENTO_ID,
                X.FAMILIA_ID,     
                X.REMESSA_ID,   
                8000 + ROW_NUMBER() OVER() REMESSA_TALAO_ID,   
                X.MATRIZ_ID,
                X.MODELO_ID,  
                M.DESCRICAO MODELO_DESCRICAO,
                X.COR_ID,
                C.DESCRICAO COR_DESCRICAO,
                X.PRODUTO_ID,
                X.TAMANHO,
                TAMANHO_GRADE(M.GRADE_CODIGO,X.TAMANHO) TAMANHO_DESCRICAO,
                X.STATUS,
                X.PERFIL_SKU,
                X.GP_ID,
                G.DESCRICAO GP_DESCRICAO,
                0 ESTACAO,
                X.QUANTIDADE_TOTAL_TALOES
            
            FROM (
                SELECT
                    T.REMESSA                AS REMESSA_ID,
                    T.ESTABELECIMENTO_CODIGO AS ESTABELECIMENTO_ID,
                    T.FAMILIA_CODIGO         AS FAMILIA_ID,
                    P.MODELO_CODIGO          AS MODELO_ID,
                    T.MATRIZ_CODIGO          AS MATRIZ_ID,
                    T.PRODUTO_CODIGO         AS PRODUTO_ID,
                    P.COR_CODIGO             AS COR_ID,
                    T.TAMANHO                AS TAMANHO,
                    '1'                      AS STATUS,
                    T.PERFIL                 AS PERFIL_SKU,
                    T.PROGRAMACAO_ESTEIRA    AS GP_ID,
                    SUM(T.QUANTIDADE)        AS QUANTIDADE_TOTAL_TALOES
                
                FROM
                    TBREMESSA_ITEM_PROCESSADO T,
                    TBPRODUTO P
                
                WHERE
                    T.REMESSA = :REMESSA_ID
                AND P.CODIGO  = T.PRODUTO_CODIGO
                AND T.CONTROLE NOT BETWEEN 8000 AND 8999
                AND (T.QUANTIDADE -
                    COALESCE((
                        SELECT SUM(S.QUANTIDADE)
                          FROM TBREQUISICAO_SOBRA S
                         WHERE S.REMESSA = T.REMESSA
                           AND S.TALAO = T.CONTROLE
                           AND S.REQUISICAO_ID = 0), 0.0000)) > 0
                
                GROUP BY 1,2,3,4,5,6,7,8,9,10,11
                ) X,
                TBMODELO M,
                TBCOR C,
                TBGP G
            WHERE
                M.CODIGO = X.MODELO_ID
            AND C.CODIGO = X.COR_ID
            AND G.ID     = X.GP_ID
        ";
        
        $args = [
            ':REMESSA_ID' => $param->REMESSA_ID,
        ];
        
        return $con->query($sql,$args);
    }
	
    public static function selectDefeitoOrigem($param, _Conexao $con = null)
    {
        $sql = "
            SELECT
                R.REMESSA_ID,
                R.DATA REMESSA_DATA,
                X.PRODUTO_ID,
                X.MODELO_ID,
                X.TAMANHO,
                X.COR_ID,
                X.DATAHORA,
                X.QUANTIDADE_PRODUCAO,
                X.QUANTIDADE_DEFEITO,
                X.PERCENTUAL_DEFEITO

            FROM
                SPC_REMESSA_SKU_DEFEITO_PERCENT (:MODELO_ID,:COR_ID,:TAMANHO,:REMESSA_ID,3,'0') X,
                VWREMESSA R

            WHERE
                R.REMESSA_ID = X.REMESSA_ID
        ";
        
        $args = [
            'MODELO_ID'     => $param->MODELO_ID,
            'COR_ID'        => $param->COR_ID,
            'TAMANHO'       => $param->TAMANHO,
            'REMESSA_ID'    => $param->REMESSA_ID,
        ];
        
        return $con->query($sql,$args);
    }
    
    public static function insertRemessaTalao($param, _Conexao $con = null)
    {
        $sql = "
            INSERT INTO TBREMESSA_ITEM_PROCESSADO (
                ESTABELECIMENTO_CODIGO,   
                FAMILIA_CODIGO,  
                REMESSA,
                CONTROLE,
                MATRIZ_CODIGO,
                MODELO_CODIGO,
                PRODUTO_CODIGO,
                TAMANHO,
                QUANTIDADE,
                SITUACAO,
                PERFIL,
                PROGRAMACAO_ESTEIRA,
                PROGRAMACAO_BOCA,
                PERCENTUAL_TALAO_EXTRA
            ) VALUES (        
                :ESTABELECIMENTO_ID, 
                :FAMILIA_ID,
                :REMESSA_ID,
                :REMESSA_TALAO_ID, 
                :MATRIZ_ID,
                :MODELO_ID,
                :PRODUTO_ID,
                :TAMANHO,
                :QUANTIDADE,
                :STATUS,
                :PERFIL_SKU,
                :GP_ID,
                :ESTACAO,
                :PERCENTUAL
            );
        ";
        
        $args = [
            'ESTABELECIMENTO_ID' => $param->ESTABELECIMENTO_ID, 
            'FAMILIA_ID'         => $param->FAMILIA_ID,
            'REMESSA_ID'         => $param->REMESSA_ID,
            'REMESSA_TALAO_ID'   => $param->REMESSA_TALAO_ID, 
            'MATRIZ_ID'          => $param->MATRIZ_ID,
            'MODELO_ID'          => $param->MODELO_ID,
            'PRODUTO_ID'         => $param->PRODUTO_ID,
            'TAMANHO'            => $param->TAMANHO,
            'QUANTIDADE'         => $param->QUANTIDADE,
            'STATUS'             => $param->STATUS,
            'PERFIL_SKU'         => $param->PERFIL_SKU,
            'GP_ID'              => $param->GP_ID,
            'ESTACAO'            => $param->ESTACAO,
            'PERCENTUAL'         => $param->PERCENTUAL,		
        ];
        
        return $con->query($sql,$args);
    }	
    
    public static function updateRemessaTalaoDetalhe($param, _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $sql = "
            UPDATE VWREMESSA_TALAO_DETALHE T
               SET T.REMESSA_TALAO_ID = :REMESSA_TALAO_ID
             WHERE T.ID = :ID
        ";
        
        $args = [
            ':REMESSA_TALAO_ID' => $param->REMESSA_TALAO_ID,
            ':ID'               => $param->ID
        ];
        
        return $con->query($sql,$args);
    }	

    public static function updateRemessaTalaoDetalheEncerrar($param, _Conexao $con = null)
    {
		$con = $con ? $con : new _Conexao;

        $sql = "
            UPDATE VWREMESSA_TALAO_DETALHE T
               SET T.STATUS = 6
             WHERE T.ID = :ID
        ";
        
        $args = [
            ':ID'               => $param->ID
        ];
        
        return $con->query($sql,$args);
    }

    public static function updateRemessaTalaoDetalheReabrir($param, _Conexao $con = null)
    {
		$con = $con ? $con : new _Conexao;

        $sql = "
            UPDATE VWREMESSA_TALAO_DETALHE T
               SET T.STATUS = 1
             WHERE T.ID = :ID
        ";
        
        $args = [
            ':ID'               => $param->ID
        ];
        
        return $con->query($sql,$args);
    }
    
    public static function spiRemessaConsumo($param, _Conexao $con = null)
    {
        $sql = "
            EXECUTE PROCEDURE SPI_REMESSA_CONSUMO_BOJO(:REMESSA_ID,:MP_FAMILIA_ID)
        ";
        
        $args = [
            'REMESSA_ID'    => $param->REMESSA_ID,
            'MP_FAMILIA_ID' => $param->MP_FAMILIA_ID    
        ];
                
        return $con->query($sql,$args);
    }
    
    public static function spiRemessaSobra($param, _Conexao $con = null)
    {
        $sql = "
            EXECUTE PROCEDURE SPI_REMESSA_SOBRA(:REMESSA_ID);
        ";
        
        $args = [
            'REMESSA_ID'    => $param->REMESSA_ID  
        ];
                
        return $con->query($sql,$args);
    }
    
    public static function spuTalaoReabrir($param, _Conexao $con = null)
    {
		$con = $con ? $con : new _Conexao;

        $sql = "
            EXECUTE PROCEDURE REABRIR_TALAO(:ID);
        ";
        
        $args = [
            ':ID' => $param->ID
        ];
        
        return $con->query($sql,$args);
    }
    
    public static function spuDesmembrarEtapa2($param, _Conexao $con = null)
    {
		$con = $con ? $con : new _Conexao;

        $sql = "
            EXECUTE PROCEDURE SPU_DESMEMBRAR_TALAO_ETAPA2(:REMESSA_ID,:REMESSA_TALAO_ID);
        ";
        
        $args = [
            ':REMESSA_ID'       => $param->REMESSA_ID,
            ':REMESSA_TALAO_ID' => $param->REMESSA_TALAO_ID
        ];
        
        return $con->query($sql,$args);
    }
    
    public static function deleteRemessaTalaoExtra($param, _Conexao $con = null)
    {
        $sql = "
            DELETE FROM VWREMESSA_TALAO WHERE REMESSA_ID = :REMESSA_ID AND REMESSA_TALAO_ID BETWEEN 8000 AND 8999
        ";
        
        $args = [
            'REMESSA_ID' => $param->REMESSA_ID
        ];
        
        $con->query($sql, $args);
    }
    
    
    public static function deleteTalaoZerado($param, _Conexao $con = null)
    {
		$con = $con ? $con : new _Conexao;

        $sql = "
            DELETE
              FROM VWREMESSA_TALAO T
             WHERE
                   T.QUANTIDADE = 0
               AND ID IS NOT NULL
               AND T.STATUS = 1
               AND COALESCE(T.PRODUTO_ID,0) = 0
               AND (T.REMESSA_ID||'/'||T.REMESSA_TALAO_ID) NOT IN (
                SELECT
                    FIRST 1 C.REMESSA_ID||'/'||C.REMESSA_TALAO_ID

                FROM
                    VWREMESSA_CONSUMO C
                WHERE
                    C.REMESSA_ID = T.REMESSA_ID
                AND C.REMESSA_TALAO_ID = T.REMESSA_TALAO_ID
                AND C.STATUS = '1');
        ";
        
        $con->query($sql);

        $sql = "
            DELETE
              FROM VWREMESSA_TALAO_DETALHE D
             WHERE
                   D.QUANTIDADE = 0
               AND D.STATUS < 3
        ";
        
        $con->query($sql);
    }
    
    public static function deleteRemessaVazia($param, _Conexao $con = null)
    {
		$con = $con ? $con : new _Conexao;

        $sql = "
            DELETE FROM VWREMESSA WHERE REMESSA_ID IN (

            SELECT
                REMESSA_ID
            FROM (
                SELECT
                    R.REMESSA_ID,
                    (SELECT COUNT(*) FROM VWREMESSA_TALAO T WHERE T.REMESSA_ID = R.REMESSA_ID) CONT_TAL,
                    (SELECT COUNT(*) FROM VWREMESSA_CONSUMO T WHERE T.REMESSA_ID = R.REMESSA_ID) CONT_CONS

                FROM
                    VWREMESSA R,
                    TbConfiguracao_Periodo A
                WHERE R.DATA BETWEEN A.DATAINICIAL AND A.DATAFINAL AND  A.Estabelecimento_Codigo = R.ESTABELECIMENTO_ID and A.Modulo_Codigo = 8

                ) X
            WHERE
                X.CONT_CONS = 0
            AND X.CONT_TAL = 0

            )
        ";
        
        $con->query($sql);
    }
	
    public static function deleteRemessa($param, _Conexao $con = null)
    {
		$con = $con ? $con : new _Conexao;

        $sql = "
            DELETE FROM
                VWREMESSA
            WHERE
                REMESSA_ID = :REMESSA_ID
        ";
        
        $args = [
            'REMESSA_ID' => $param->REMESSA_ID
        ];
        
        return $con->query($sql,$args);
    }
	
    public static function deleteTalao($param, _Conexao $con = null)
    {
		$con = $con ? $con : new _Conexao;

        $sql = "
            DELETE FROM
                VWREMESSA_TALAO
            WHERE
                ID = :ID
        ";
        
        $args = [
            ':ID' => $param->ID
        ];
        
        return $con->query($sql,$args);
    }
	
    public static function deleteTalaoDetalhe($param, _Conexao $con = null)
    {
		$con = $con ? $con : new _Conexao;

        $sql = "
            DELETE FROM
                VWREMESSA_TALAO_DETALHE
            WHERE
                ID = :ID
        ";
        
        $args = [
            ':ID' => $param->ID
        ];
        
        return $con->query($sql,$args);
    }
	
    public static function deleteTalaoConsumo($param, _Conexao $con = null)
    {
		$con = $con ? $con : new _Conexao;

        $sql = "
            DELETE FROM
                VWREMESSA_CONSUMO
            WHERE
                ID = :ID
        ";
        
        $args = [
            ':ID' => $param->ID
        ];
        
        return $con->query($sql,$args);
    }
}