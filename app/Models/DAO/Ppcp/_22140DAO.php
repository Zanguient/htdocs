<?php

namespace App\Models\DAO\Ppcp;

use App\Models\Conexao\_Conexao;

/**
 * DAO do objeto _22140 - Painel de Programacao
 */
class _22140DAO {
    
    /**
     *
     * @var _Conexao 
     */
    public $con;
    
    public function __construct($con) {
        $this->con = $con;
    } 
    
    public function selectProgramacaoEstacao($param = [])
    {
        $sql =
        "
            SELECT
                F.CODIGO        FAMILIA_ID,
                F.DESCRICAO     FAMILIA_DESCRICAO,
                G.ID            GP_ID,
                G.DESCRICAO     GP_DESCRICAO,
                U.ID            UP_ID,
                U.DESCRICAO     UP_DESCRICAO,
                S.ID            ESTACAO,
                S.DESCRICAO     ESTACAO_DESCRICAO,
                X.PROGRAMACAO_STATUS

            FROM (
                SELECT DISTINCT
                    M.FAMILIA_CODIGO FAMILIA_ID,
                    P.GP_ID,
                    P.UP_ID,
                    P.ESTACAO,
                    MAX(P.STATUS) PROGRAMACAO_STATUS
                FROM
                    TBPROGRAMACAO P,
                    VWREMESSA_TALAO T,
                    TBMODELO M

                WHERE
                    P.FERRAMENTA_ID > 0
                AND P.STATUS        < 3
                AND P.TIPO          = 'A'
                AND T.ID            = P.TABELA_ID
                AND M.CODIGO        = T.MODELO_ID

                GROUP BY 1,2,3,4
                ) X,
                TBFAMILIA F,
                TBGP G,
                TBUP U,
                TBSUB_UP S

            WHERE
                F.CODIGO    = X.FAMILIA_ID
            AND G.ID        = X.GP_ID
            AND U.ID        = X.UP_ID
            AND S.UP_ID     = U.ID
            AND S.ID        = X.ESTACAO
        ";
        
        return $this->con->query($sql,[]);
    }   
    
    public function selectProgramacaoGp($param = [])
    {
        $sql =
        "
            SELECT
                DISTINCT
                P.GP_ID,
                G.DESCRICAO GP_DESCRICAO,
                G.FAMILIA_ID,
                F.DESCRICAO FAMILIA_DESCRICAO,
                TRIM(IIF(P.FERRAMENTA_ID > 0,'1','0'))CHECKED


            FROM
                TBPROGRAMACAO P
                LEFT JOIN TBGP G ON G.ID = P.GP_ID
                LEFT JOIN TBFAMILIA F ON F.CODIGO = G.FAMILIA_ID

            WHERE
                P.STATUS < 3
            AND P.TIPO = 'A'
            
            ORDER BY FAMILIA_DESCRICAO, GP_DESCRICAO
        ";
        
        return $this->con->query($sql,[]);
    }   
    
    public function spuProgramacaoEstacao($param = [])
    {
        $sql =
        "
            EXECUTE PROCEDURE
            SPU_REPROGRAMACAO_BOJO(
                1,
                'A',
                :GP_ID_1,
                :UP_ID_1,
                :ESTACAO_1,
                IIF(:DATAHORA_1 = '2000.01.01 00:00:00', (SELECT MIN(P.DATAHORA_INICIO)
                   FROM TBPROGRAMACAO P
                  WHERE NOT (P.STATUS   = 1 AND P.STATUS_REQUISICAO = 1)
                    AND P.STATUS        < 3
                    AND P.FERRAMENTA_ID > 0
                    AND IIF(:EM_PRODUCAO  = 0, P.STATUS < 2, TRUE)
                    AND P.GP_ID         = :GP_ID_2
                    AND P.UP_ID         = :UP_ID_2
                   AND P.ESTACAO        = :ESTACAO_2),
                  :DATAHORA_2) ,
                  :ORDEM_DATA_REMESSA
            );
        ";
        
        return $this->con->query($sql,$param);
    }   
    
    
    public static function selectEstacoesPorData($param, _Conexao $con = null)
    {
        $sql = "
            SELECT DISTINCT
                P.GP_ID, 
                G.DESCRICAO GP_DESCRICAO,
                P.UP_ID,                 
                U.DESCRICAO UP_DESCRICAO,
                P.ESTACAO,
                E.DESCRICAO ESTACAO_DESCRICAO

            FROM
                TBPROGRAMACAO P,
                TBGP G,
                TBUP U,
                TBSUB_UP E

            WHERE
                P.FERRAMENTA_ID > 0
            AND G.ID            = P.GP_ID
            AND U.ID            = P.UP_ID
            AND E.UP_ID         = P.UP_ID
            AND E.ID            = P.ESTACAO
            AND P.DATA    BETWEEN :DATA_1 AND :DATA_2
            AND P.STATUS       IN ('0','1')

            ORDER BY P.GP_ID, P.UP_ID, P.ESTACAO
        ";
        
        $args = [
            'DATA_1' => date('Y-m-d',strtotime($param->DATA_1)),
            'DATA_2' => date('Y-m-d',strtotime($param->DATA_2)),
        ];
                
        $ret = $con->query($sql,$args);
        
        return $ret;
    }
    
    public static function selectCalendarioPorData($param, _Conexao $con = null)
    {
        $sql = "
            SELECT
                DATA,
                MAX(MINUTOS) MINUTOS
            FROM 
                TBCALENDARIO_UP U
            WHERE 
                U.DATA BETWEEN :DATA_1 AND :DATA_2
            AND U.MINUTOS > 0
            
            GROUP BY 1
        ";
        
        $args = [
            'DATA_1' => date('Y-m-d',strtotime($param->DATA_1)),
            'DATA_2' => date('Y-m-d',strtotime($param->DATA_2))
        ];
        
        return $con->query($sql,$args);
    }
	
    public static function selectTaloesPorData($param, _Conexao $con = null)
    {
        $sql = "
            SELECT FIRST 3
                REMESSA_ID,
                REMESSA_TALAO_ID,
                MINUTO_INICIO,
                MINUTO_FIM,
                TEMPO_TOTAL,
                IIF(DATEDIFF(MINUTE, REF_INICIO_DATAHORA, REF_FIM_DATAHORA) > 0, DATEDIFF(MINUTE, REF_INICIO_DATAHORA, REF_FIM_DATAHORA), 0) MINUTOS_DESCONTO,
                DATEDIFF(MINUTE, DATAHORA_BASE, REF_INICIO_DATAHORA) REF_INICIO_MINUTO,   
                DATEDIFF(MINUTE, DATAHORA_BASE, REF_FIM_DATAHORA) REF_FIM_MINUTO,
                REF_INICIO_DATAHORA,
                REF_FIM_DATAHORA,  
                REF_INICIO_TALAO,
                REF_FIM_TALAO,
                ID,
                REMESSA,
                TALAO_ID,
                DATAHORA_INICIO,
                DATAHORA_FIM,
                GP_ID,
                UP_ID,
                ESTACAO,
                TEMPO,
                FERRAMENTA_ID

              FROM (

                SELECT

                    (SELECT FIRST 1 DATEADD(12 MINUTE TO P1.DATAHORA_INICIO)
                       FROM TBPROGRAMACAO P1
                      WHERE 1=1
                        AND P1.TIPO = 'A'
                        AND P1.FERRAMENTA_ID > 0
                        AND P1.DATAHORA_INICIO < REF_FIM_DATAHORA
                      ORDER BY P1.DATAHORA_INICIO DESC) REF_INICIO_DATAHORA,

                    (SELECT FIRST 1 (T1.REMESSA_ID || ' / ' || T1.REMESSA_TALAO_ID)
                       FROM TBPROGRAMACAO P1, VWREMESSA_TALAO T1
                      WHERE 1=1
                        AND T1.ID = P1.TABELA_ID
                        AND P1.TIPO = 'A'
                        AND P1.FERRAMENTA_ID > 0
                        AND P1.DATAHORA_INICIO < REF_FIM_DATAHORA
                      ORDER BY P1.DATAHORA_INICIO DESC) REF_INICIO_TALAO,

                    X.*,
                    (MINUTO_FIM - MINUTO_INICIO + 1) TEMPO_TOTAL

                FROM (
                    SELECT
                        (SELECT FIRST 1 P1.DATAHORA_FIM
                           FROM TBPROGRAMACAO P1
                          WHERE 1=1
                            AND P1.FERRAMENTA_ID > 0
                            AND DATEADD(12 MINUTE TO P.DATAHORA_INICIO) BETWEEN P1.DATAHORA_INICIO AND P1.DATAHORA_FIM
                            AND P1.DATAHORA_FIM <= P.DATAHORA_FIM
                          ORDER BY P1.DATAHORA_FIM) REF_FIM_DATAHORA,

                        (SELECT FIRST 1 R1.REMESSA || ' / ' || T1.REMESSA_TALAO_ID
                           FROM TBPROGRAMACAO P1, VWREMESSA_TALAO T1, VWREMESSA R1
                          WHERE 1=1
                            AND P1.FERRAMENTA_ID > 0
                            AND T1.ID = P1.TABELA_ID
                            AND R1.REMESSA_ID = T1.REMESSA_ID
                            AND DATEADD(12 MINUTE TO P.DATAHORA_INICIO) BETWEEN P1.DATAHORA_INICIO AND P1.DATAHORA_FIM
                            AND P1.DATAHORA_FIM <= P.DATAHORA_FIM
                          ORDER BY P1.DATAHORA_FIM) REF_FIM_TALAO,

                        P.ID,
                        R.REMESSA,
                        R.REMESSA_ID,
                        LPAD(T.REMESSA_TALAO_ID,4,'0') REMESSA_TALAO_ID,
                        P.TABELA_ID TALAO_ID,
                        P.DATAHORA_INICIO,
                        P.DATAHORA_FIM,
                        P.GP_ID,
                        P.UP_ID, 
                        P.ESTACAO,
                        P.TEMPO,
                        P.FERRAMENTA_ID,

                        DATEDIFF(MINUTE,
                        CAST(:DATAHORA_1_2 AS TIMESTAMP)
                        , P.DATAHORA_INICIO) MINUTO_INICIO,

                        DATEDIFF(MINUTE,
                        CAST(:DATAHORA_1_3 AS TIMESTAMP)
                        , P.DATAHORA_FIM) MINUTO_FIM,

                        CAST(:DATAHORA_1_4 AS TIMESTAMP) DATAHORA_BASE

                    FROM
                        TBPROGRAMACAO P,
                        VWREMESSA_TALAO T,
                        VWREMESSA R

                    WHERE
                        T.ID               = P.TABELA_ID
                    AND R.REMESSA_ID       = T.REMESSA_ID
                    AND P.TIPO             = 'A'
                    AND P.DATAHORA_INICIO >= :DATAHORA_1_1
                    AND P.DATAHORA_FIM    <= :DATAHORA_2_1
                    AND P.GP_ID            = :GP_ID
                    AND P.UP_ID            = :UP_ID
                    AND P.ESTACAO          = :ESTACAO
                    ) X
                ) Z
        ";
        
        $args = [
            'DATAHORA_1_1' => date('Y-m-d H:i:s',strtotime($param->DATAHORA_1)),
            'DATAHORA_2_1' => date('Y-m-d H:i:s',strtotime($param->DATAHORA_2)),
            'GP_ID'      => $param->GP_ID,
            'UP_ID'      => $param->UP_ID,
            'ESTACAO'    => $param->ESTACAO,
            'DATAHORA_1_2' => date('Y-m-d H:i:s',strtotime($param->DATAHORA_1)),
            'DATAHORA_1_3' => date('Y-m-d H:i:s',strtotime($param->DATAHORA_1)),
            'DATAHORA_1_4' => date('Y-m-d H:i:s',strtotime($param->DATAHORA_1))
        ];
        
        return $con->query($sql,$args);
    }
	
    public static function selectTaloesPorDataHora($param, _Conexao $con = null)
    {
        $sql = "
            SELECT FIRST 1
                P.ID,
                R.REMESSA,
                R.REMESSA_ID,
                LPAD(T.REMESSA_TALAO_ID,4,'0') REMESSA_TALAO_ID,
                P.TABELA_ID TALAO_ID,
                P.DATAHORA_INICIO,
                P.DATAHORA_FIM,
                P.GP_ID,
                P.UP_ID, 
                P.ESTACAO,
                P.TEMPO,
                P.FERRAMENTA_ID

            FROM
                TBPROGRAMACAO P,
                VWREMESSA_TALAO T,
                VWREMESSA R

            WHERE
                T.ID              = P.TABELA_ID
            AND R.REMESSA_ID      = T.REMESSA_ID
            AND P.TIPO            = 'A'
            AND P.DATAHORA_INICIO = :DATAHORA
            AND P.GP_ID           = :GP_ID
            AND P.UP_ID           = :UP_ID
            AND P.ESTACAO         = :ESTACAO
        ";
        
        $args = [
            'DATAHORA' => date('Y-m-d H:i:s',strtotime($param->DATAHORA)),
            'GP_ID'    => $param->GP_ID,
            'UP_ID'    => $param->UP_ID,
            'ESTACAO'  => $param->ESTACAO
        ];
        
        return $con->query($sql,$args);
    }
    
    
	
    public static function selectDiasPeriodo($param, _Conexao $con = null)
    {
        $sql = "
            SELECT DIA FROM SP_DIAS_PERIODO(:DATA_1,:DATA_2)
        ";
        
        $args = [
            'DATA_1' => date('Y-m-d',strtotime($param->DATA_1)),
            'DATA_2' => date('Y-m-d',strtotime($param->DATA_2))
        ];
        
        return $con->query($sql,$args);
    }
	
    public static function selectMinutosDia($param, _Conexao $con = null)
    {
        $sql = "
            SELECT * FROM SP_HORAS_DIA('00:00:00', '23:59:59','M')
        ";
        
        return $con->query($sql);
    }
}