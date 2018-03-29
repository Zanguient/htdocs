<?php

namespace App\Models\DAO\Ppcp;

use App\Models\Conexao\_Conexao;

/**
 * DAO do objeto _22130 - Conformacao
 */
class _22130DAO {

    /**
     * Função generica
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function getChecList($dados) {
        return $dados;
    }

    /**
     * Listar
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function listar($dados) {
        
        $con = new _Conexao();
        
        try {

            $sql = '';

            $args = array(
                ':id' => $dados->getId(),
            );

            $ret = $con->query($sql, $args);

            $con->commit();
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }


    /**
     * Turno
     * @access public
     * @param {} $filtro
     * @param {} $con
     * @return array
     */
    public static function getTurnos($filtro,$con){

        $sql = "SELECT FIRST 1
                    K.CODIGO,
                    K.DESCRICAO
                FROM tbturno_producao K
                WHERE K.TURNO_CORRENTE = 1
                    AND K.ESTABELECIMENTO_CODIGO = :ESTABELECIMENTO";

        $args = array(
            ':ESTABELECIMENTO' => $filtro['ESTABELECIMENTO']
        );

        $ret = $con->query($sql, $args);

        return $ret;
    }


    /**
     * Metas
     * @access public
     * @param {} $filtro
     * @param {} $con
     * @return array
     */
    public static function getMeta($filtro,$con){

        $data = '';
        if(!$filtro['FLAG_DATA2']){
            $data = " AND R.DATA  BETWEEN '".$filtro['DATA_INICIAL2']."' AND '".$filtro['DATA_FINAL2']."' ";
        }else{
            $data = " AND R.DATA  = (select first 2 k.data_producao from tbturno_producao k where k.turno_corrente = 1)";    
        }

        $sql = "SELECT

                P.ESTACAO,
                u.descricao,
                Trunc(sum(p.QUANTIDADE)) as QUANTIDADE

            FROM
                VWREMESSA_TALAO T,
                TBPROGRAMACAO P,
                VWREMESSA R,
                TBSUB_UP U

            WHERE P.TIPO     = 'A'
            AND P.TABELA_ID  = T.ID
            AND R.REMESSA_ID = T.REMESSA_ID
            AND P.GP_ID      = :GP_ID
            AND P.UP_ID      = :UP_ID
            AND P.ESTACAO    in (".$filtro['ESTACAO_ID'].")
            AND u.up_id = P.UP_ID
            AND u.id = P.ESTACAO

            ".$data."

            group by 1,2
            order by P.ESTACAO,u.descricao";

        $args = array(
            ':GP_ID' => $filtro['GP_ID'],
            ':UP_ID' => $filtro['UP_ID']
        );

        $ret = $con->query($sql, $args);

        return $ret;
    }

    /**
     * Consulta justificativas
     * @access public
     * @param String $flag
     * @param {} $con
     * @return array
     */
    public static function getProducao($filtro,$con){

        $date  = date_create($filtro['DATA_INICIAL']);
        $data1 = date_format($date, 'd.m.Y');

        $date  = date_create($filtro['DATA_FINAL']);
        $data2 = date_format($date, 'd.m.Y');

        $sql_total1 = "";
        $sql_total2 = "";
        $sql_total3 = "";

        $data = '';
        if(!$filtro['FLAG_DATA']){
            $flag = 1;
        }else{
            $flag = 0;
        }

        $sql = "SELECT * FROM SPC_PRODUCAO_CONFORMACAO_V2(:GP_ID,:UP_ID,'".$filtro['ESTACAO']."','$data1','$data2',:FLAG)";

        $args = array(
            ':GP_ID'   => $filtro['GP_ID'  ],
            ':UP_ID'   => $filtro['UP_ID'  ],
            ':FLAG'    => $flag  
        );

        $ret = $con->query($sql, $args);

        return $ret;
    }

    /**
     * Paradas de uma estacao - analitico
     * @access public
     * @param String $flag
     * @param {} $con
     * @return array
     */
    public static function getParadas_a($filtro,$con){

        $date  = date_create($filtro['DATA_INICIAL']);
        $data1 = date_format($date, 'd.m.Y');

        $date  = date_create($filtro['DATA_FINAL']);
        $data2 = date_format($date, 'd.m.Y');

        $flag  = 0;

        if(!$filtro['FLAG_DATA']){

        }else{
            $flag = 1;
        }
        
        $sql = "SELECT * from SPC_PARADAS_ESTACAO_V2(:GP_ID,:UP_ID,'".$filtro['ESTACAO']."','".$data1."','".$data2."',".$flag.")";

        $args = array(
            ':GP_ID'   => $filtro['GP_ID'  ],
            ':UP_ID'   => $filtro['UP_ID'  ]
        );

        $ret = $con->query($sql, $args);

        return $ret;
    }

    /**
     * paradas de uma estacao - sintetico
     * @access public
     * @param String $flag
     * @param {} $con
     * @return array
     */
    public static function getParadas_s($filtro,$con){

        $date  = date_create($filtro['DATA_INICIAL']);
        $data1 = date_format($date, 'd.m.Y');

        $date  = date_create($filtro['DATA_FINAL']);
        $data2 = date_format($date, 'd.m.Y');

        $flag = 0;

        if(!$filtro['FLAG_DATA']){

        }else{
            $flag = 1;
        }

        $sql = "SELECT

                        MOTIVO_PARADA,
                        ESTACAO,
                        tipo,
                        marcar,
                        sum(TEMPO_PARADO) as TEMPO_PARADO

                    from(

                        SELECT * from SPC_PARADAS_ESTACAO_V2(:GP_ID,:UP_ID,'".$filtro['ESTACAO']."','".$data1."','".$data2."',".$flag.")

                    )
                group by 1,2,3,4";

        $args = array(
            ':GP_ID'   => $filtro['GP_ID'  ],
            ':UP_ID'   => $filtro['UP_ID'  ]
        );

        $ret = $con->query($sql, $args);

        return $ret;
    }

    /**
     * Consulta justificativas
     * @access public
     * @param String $flag
     * @param {} $con
     * @return array
     */
    public static function consultaJustificativa($flag, _Conexao $con = null)
    {
        $con = $con ? $con : new _Conexao;

        $sql = "SELECT A.ID,A.DESCRICAO,A.MARCAR from TBJUSTIFICATIVA a where a.FLAG = :FLAG and a.STATUS = 1 order  by ORDEM";

        $args = array(
            ':FLAG' => $flag
        );

        $ret = $con->query($sql, $args);

        return $ret;
    }

    /**
     * Consulta justificativas
     * @access public
     * @param String $flag
     * @param {} $con
     * @return array
     */
    public static function pararEstacao($filtro,$con){

        $sql = "INSERT INTO TBREGISTRO_PARADA (TABELA_ID, TABELA, STATUS, VINCULO_ID, SUBVINCULO_ID, OPERADOR_ID)
                VALUES (:TABELA_ID, :TABELA, :STATUS, :VINCULO_ID, :SUBVINCULO_ID, :OPERADOR_ID);";

        $args = array(
            ':TABELA_ID'     => $filtro['TABELA_ID'],
            ':TABELA'        => $filtro['TABELA'],
            ':STATUS'        => $filtro['STATUS'],
            ':VINCULO_ID'    => $filtro['VINCULO_ID'],
            ':SUBVINCULO_ID' => $filtro['SUBVINCULO_ID'],
            ':OPERADOR_ID'   => $filtro['OPERADOR_ID']
        );

        $ret = $con->query($sql, $args);

        return $ret;
    }

    /**
     * justificativa ineficiencia
     * @access public
     * @param String $flag
     * @param {} $con
     * @return array
     */
    public static function justIneficiencia($filtro,$con){

        $sql = "UPDATE OR INSERT INTO TBREGISTRO_PARADA (TABELA_ID, TABELA, STATUS, VINCULO_ID, SUBVINCULO_ID, OPERADOR_ID,OBSERVACAO)
                VALUES (:TABELA_ID, :TABELA, :STATUS, :VINCULO_ID, :SUBVINCULO_ID, :OPERADOR_ID,:OBSERVACAO)
                matching(TABELA_ID,STATUS,TABELA);";

        $args = array(
            ':TABELA_ID'     => $filtro['TABELA_ID'],
            ':TABELA'        => $filtro['TABELA'],
            ':STATUS'        => $filtro['STATUS'],
            ':VINCULO_ID'    => $filtro['VINCULO_ID'],
            ':SUBVINCULO_ID' => $filtro['SUBVINCULO_ID'],
            ':OPERADOR_ID'   => $filtro['OPERADOR_ID'],
            ':OBSERVACAO'    => $filtro['OBSERVACAO'],
        );

        $ret = $con->query($sql, $args);

        $sql = "SELECT
                    list(j.descricao||': '||coalesce(p.observacao,''),',<br>') as DESC from

                TBREGISTRO_PARADA P,
                tbjustificativa J
                where p.tabela = 'INEFICIENCIA'
                and p.tabela_id = :PROGRAMACAO_ID
                and j.id = p.status;";

        $args = array(
            ':PROGRAMACAO_ID' => $filtro['TABELA_ID']
        );

        $ret = $con->query($sql, $args);

        return $ret;
    }


    /**
     * Metas
     * @access public
     * @param {} $filtro
     * @param {} $con
     * @return array
     */
    public static function getMeta_t($filtro,$con){

        $data = '';
        if(!$filtro['FLAG_DATA2']){
            $data = " AND R.DATA  BETWEEN '".$filtro['DATA_INICIAL2']."' AND '".$filtro['DATA_FINAL2']."' ";
        }else{
            $data = " AND R.DATA  = (select first 2 k.data_producao from tbturno_producao k where k.turno_corrente = 1)";    
        }

        $sql = "SELECT
                ESTACAO,
                TURNO,
                sum(QUANTIDADE) as QUANTIDADE

                FROM(


                    SELECT
                    
                        P.ESTACAO,
                        p.datahora_fim,
                        extract(weekday from p.datahora_fim) as DIA_SEMANA,
                        (select first 1 b.codigo from tbturno_producao b where (COMPARE_HORA(b.hora_inicio,b.hora_fim,gethora(p.datahora_fim))) = 1 and b.dia_semana = iif(gethora(p.datahora_fim) > '06:59', extract(weekday from p.datahora_fim),extract(weekday from p.datahora_fim) - 1) ) as turno,
                       
                        p.QUANTIDADE
                    
                    
                    FROM
                        VWREMESSA_TALAO T,
                        TBPROGRAMACAO P,
                        VWREMESSA R
                    
                    WHERE P.TIPO       = 'A'
                    AND P.TABELA_ID  = T.ID
                    AND R.REMESSA_ID = T.REMESSA_ID
                    AND P.GP_ID      = :GP_ID
                    AND P.UP_ID      = :UP_ID
                    AND P.ESTACAO    in (".$filtro['ESTACAO_ID'].")

                    ".$data."

                )

                where turno is not null

                group by 1,2
                order by 1,2";

        $args = array(
            ':GP_ID' => $filtro['GP_ID'],
            ':UP_ID' => $filtro['UP_ID']
        );

        $ret = $con->query($sql, $args);

        return $ret;
    }

    /**
     * Metas
     * @access public
     * @param {} $filtro
     * @param {} $con
     * @return array
     */
    public static function getTempo($filtro,$con){

        $data = '';
        if(!$filtro['FLAG_DATA']){
            $data = "and c.data BETWEEN '".$filtro['DATA_INICIAL']."' AND '".$filtro['DATA_FINAL']."' ";
        }else{
            $data = "and c.data = (select first 1 j.data_producao from tbturno_producao j where j.data_producao is not null)";    
        }

        $sql = "SELECT

                    sum(iif(codigo = 1, disponivel ,0)) disponivel_t1,
                    sum(iif(codigo = 2, disponivel ,0)) disponivel_t2,
                    sum(iif(codigo = 1, iif(ATUAL,corrido,disponivel),0)) corrido_t1,
                    sum(iif(codigo = 2, iif(ATUAL,corrido,disponivel),0)) corrido_t2

                from
                (
                    select
                    
                        p.codigo,
                        (c.data = d.data_producao) as ATUAL,
                        tempo_disponivel_up(
                                    c.data + p.hora_inicio,
                                    c.data + p.hora_fim,
                                    :UP_ID1) as disponivel,
                        iif(d.data_producao = c.data,tempo_corrido_up(
                                    c.data + p.hora_inicio,
                                    c.data + p.hora_fim,
                                    :UP_ID2),0) as corrido

                    from tbturno_producao p, tbcalendario_up c,(select first 1 j.data_producao from tbturno_producao j where j.data_producao is not null) d
                    where p.dia_semana = extract( WEEKDAY from c.data)
                    and c.up_id = :UP_ID3

                    $data

            )";

        $args = array(
            ':UP_ID1' => $filtro['UP_ID'],
            ':UP_ID2' => $filtro['UP_ID'],
            ':UP_ID3' => $filtro['UP_ID'],
        );

        $ret = $con->query($sql, $args);

        return $ret;
    }


    /**
     * Metas
     * @access public
     * @param {} $filtro
     * @param {} $con
     * @return array
     */
    public static function getProducao_t($filtro,$con){
        $date  = date_create($filtro['DATA_INICIAL']);
        $data1 = date_format($date, 'd.m.Y');

        $date  = date_create($filtro['DATA_FINAL']);
        $data2 = date_format($date, 'd.m.Y');

        $sql_total1 = "";
        $sql_total2 = "";
        $sql_total3 = "";

        $data = '';
        if(!$filtro['FLAG_DATA']){
            $flag = 1;
        }else{
            $flag = 0;
        }

        $sql = "SELECT
                        ESTACAO,
                        TURNO,
                        QUANTIDADE,
                        cast((EFICIENCIA_MINIMA / iif(QUANTIDADE >0,QUANTIDADE,1)) as numeric(15,2)) as EFICIENCIA_MINIMA,
                        cast((EFICIENCIA_A / iif(QUANTIDADE >0,QUANTIDADE,1)) as numeric(15,2)) as EFICIENCIA_A,
                        cast((EFICIENCIA_B / iif(QUANTIDADE >0,QUANTIDADE,1)) as numeric(15,2)) as EFICIENCIA_B,
                        cast((PERDAS_A / iif(QUANTIDADE >0,QUANTIDADE,1)) as numeric(15,2)) as PERDAS_A,
                        cast((PERDAS_B / iif(QUANTIDADE >0,QUANTIDADE,1)) as numeric(15,2)) as PERDAS_B

                    from(
                    SELECT
                            ESTACAO,
                            TURNO,
                            SUM(QUANTIDADE) as QUANTIDADE,
                            sum(EFICIENCIA_MINIMA) as EFICIENCIA_MINIMA,
                            sum(EFICIENCIA_A) as EFICIENCIA_A,
                            sum(EFICIENCIA_B) as EFICIENCIA_B,
                            sum(PERDAS_A) as PERDAS_A,
                            sum(PERDAS_B) as PERDAS_B
                        FROM(

                            SELECT * FROM SPC_PRODUCAO_CONFORMACAO_V2(:GP_ID,:UP_ID,'".$filtro['ESTACAO_ID']."','$data1','$data2',:FLAG)

                        )
            
                group BY 1,2
                order by 1,2
        ) w";

        $args = array(
            ':GP_ID'   => $filtro['GP_ID'  ],
            ':UP_ID'   => $filtro['UP_ID'  ],
            ':FLAG'    => $flag  
        );

        $ret = $con->query($sql, $args);

        return $ret;
    }
    
    /**
     * Metas
     * @access public
     * @param {} $filtro
     * @param {} $con
     * @return array
     */
    public static function getPerdas_t($filtro,$con){

        $data = '';
        if(!$filtro['FLAG_DATA']){
            $data = " AND D.DATA  BETWEEN '".$filtro['DATA_INICIAL']."' AND '".$filtro['DATA_FINAL']."' ";
        }else{
            $data = " AND D.DATA  = (select first 2 k.data_producao from tbturno_producao k where k.turno_corrente = 1)";    
        }

        $sql = "SELECT
                    
                    P.estacao,
                    D.turno,
                    SUM(D.quantidade) AS quantidade
                    
                    FROM
                        VWREMESSA_TALAO T,
                        TBPROGRAMACAO P,
                        TbDefeito_Transacao_Item D,

                        TBREMESSA R,

                        TBSAC_DEFEITO S
                    
                    WHERE P.TABELA_ID  = T.ID
                    AND P.REMESSA_ID  = T.REMESSA_ID
                    AND D.remessa = T.remessa_id
                    AND D.remessa_controle = T.remessa_talao_id
                    AND T.REMESSA_ID = R.NUMERO
                    AND D.defeito_id = S.codigo
                    and S.conformacao = '1'
                    AND P.ESTACAO   IN (".$filtro['ESTACAO_ID'].")
                    AND P.GP_ID      = :GP_ID
                    AND P.UP_ID      = :UP_ID

                    ".$data."

                    group BY 1,2";

        $args = array(
            ':GP_ID' => $filtro['GP_ID'],
            ':UP_ID' => $filtro['UP_ID']
        );

        $ret = $con->query($sql, $args);

        return $ret;
    }
    
    /**
     * Metas
     * @access public
     * @param {} $filtro
     * @param {} $con
     * @return array
     */
    public static function getMeta_g($filtro,$con){

        $data = '';
        if(!$filtro['FLAG_DATA2']){
            $data = " AND R.DATA  BETWEEN '".$filtro['DATA_INICIAL2']."' AND '".$filtro['DATA_FINAL2']."' ";
        }else{
            $data = " AND R.DATA  = (select first 2 k.data_producao from tbturno_producao k where k.turno_corrente = 1)";    
        }

        $sql = "SELECT

                    P.ESTACAO,
                    u.descricao,
                    Trunc(sum(p.QUANTIDADE)) as QUANTIDADE

                FROM
                    VWREMESSA_TALAO T,
                    TBPROGRAMACAO P,
                    VWREMESSA R,
                    TBSUB_UP U

                WHERE P.TIPO       = 'A'
                AND P.TABELA_ID  = T.ID
                AND R.REMESSA_ID = T.REMESSA_ID
                AND P.GP_ID      = :GP_ID
                AND P.UP_ID      = :UP_ID
                AND P.ESTACAO    in (".$filtro['ESTACAO_ID'].")
                AND u.up_id = P.UP_ID
                AND u.id = P.ESTACAO

                ".$data."

                group by 1,2
                order by P.ESTACAO,u.descricao";

        $args = array(
            ':GP_ID' => $filtro['GP_ID'],
            ':UP_ID' => $filtro['UP_ID']
        );

        $ret = $con->query($sql, $args);

        return $ret;
    }


    /**
     * Metas
     * @access public
     * @param {} $filtro
     * @param {} $con
     * @return array
     */
    public static function getProducao_g($filtro,$con){

        $date  = date_create($filtro['DATA_INICIAL']);
        $data1 = date_format($date, 'd.m.Y');

        $date  = date_create($filtro['DATA_FINAL']);
        $data2 = date_format($date, 'd.m.Y');

        $sql_total1 = "";
        $sql_total2 = "";
        $sql_total3 = "";

        $data = '';
        if(!$filtro['FLAG_DATA']){
            $flag = 1;
        }else{
            $flag = 0;
        }

        $sql = "SELECT

                        ESTACAO,
                        QUANTIDADE,
                        cast((EFICIENCIA_MINIMA / iif(QUANTIDADE >0,QUANTIDADE,1)) as numeric(15,2)) as EFICIENCIA_MINIMA,
                        cast((EFICIENCIA_A / iif(QUANTIDADE >0,QUANTIDADE,1)) as numeric(15,2)) as EFICIENCIA_A,
                        cast((EFICIENCIA_B / iif(QUANTIDADE >0,QUANTIDADE,1)) as numeric(15,2)) as EFICIENCIA_B,
                        cast((PERDAS_A / iif(QUANTIDADE >0,QUANTIDADE,1)) as numeric(15,2)) as PERDAS_A,
                        cast((PERDAS_B / iif(QUANTIDADE >0,QUANTIDADE,1)) as numeric(15,2)) as PERDAS_B

                    from(
                    SELECT
                            ESTACAO,
                            SUM(QUANTIDADE) as QUANTIDADE,
                            sum(EFICIENCIA_MINIMA) as EFICIENCIA_MINIMA,
                            sum(EFICIENCIA_A) as EFICIENCIA_A,
                            sum(EFICIENCIA_B) as EFICIENCIA_B,
                            sum(PERDAS_A) as PERDAS_A,
                            sum(PERDAS_B) as PERDAS_B
                        FROM(

                            SELECT * FROM SPC_PRODUCAO_CONFORMACAO_V2(:GP_ID,:UP_ID,'".$filtro['ESTACAO_ID']."','$data1','$data2',:FLAG)

                        )
            
                group BY 1
                order by 1
        ) w";

        $args = array(
            ':GP_ID'   => $filtro['GP_ID'  ],
            ':UP_ID'   => $filtro['UP_ID'  ],
            ':FLAG'    => $flag  
        );

        $ret = $con->query($sql, $args);

        return $ret;
    }
    
    /**
     * Metas
     * @access public
     * @param {} $filtro
     * @param {} $con
     * @return array
     */
    public static function getPerdas_g($filtro,$con){

        $data = '';
        if(!$filtro['FLAG_DATA']){
            $data = " AND D.DATA  BETWEEN '".$filtro['DATA_INICIAL']."' AND '".$filtro['DATA_FINAL']."' ";
        }else{
            $data = " AND D.DATA  = (select first 2 k.data_producao from tbturno_producao k where k.turno_corrente = 1)";    
        }

        $sql = "SELECT
                    
                    P.estacao,
                    SUM(D.quantidade) AS quantidade
                    
                    FROM
                        VWREMESSA_TALAO T,
                        TBPROGRAMACAO P,
                        TbDefeito_Transacao_Item D,

                        TBREMESSA R,

                        TBSAC_DEFEITO S
                    
                    WHERE P.TABELA_ID  = T.ID
                    AND P.REMESSA_ID  = T.REMESSA_ID
                    AND D.remessa = T.remessa_id
                    AND D.remessa_controle = T.remessa_talao_id
                    AND T.REMESSA_ID = R.NUMERO
                    AND D.defeito_id = S.codigo
                    and S.conformacao = '1'
                    AND P.ESTACAO   IN (".$filtro['ESTACAO_ID'].")
                    AND P.GP_ID      = :GP_ID
                    AND P.UP_ID      = :UP_ID

                    ".$data."

                    group BY 1";

        $args = array(
            ':GP_ID' => $filtro['GP_ID'],
            ':UP_ID' => $filtro['UP_ID']
        );

        $ret = $con->query($sql, $args);

        return $ret;
    }

    /**
     * Metas
     * @access public
     * @param {} $filtro
     * @param {} $con
     * @return array
     */
    public static function getTaloes_producao($filtro,$con){
        
        if(!isset($filtro['FILTRAR_TALAO']) ){$filtro['FILTRAR_TALAO'] = 0;}

        $data = '';
        if(!$filtro['FLAG_DATA']){
            $data = " AND R.DATA  BETWEEN '".$filtro['DATA_INICIAL']."' AND '".$filtro['DATA_FINAL']."' ";
        }

        $talao = '';
        $requisicao = '';
        $talao_filtro = 'AND P.STATUS  < 3';

        if($filtro['TALAO_ID'] > 0){
            $talao      = " FIRST 1 ";
            $requisicao = 'and iif(P.STATUS_REQUISICAO = 0, true,(P.STATUS <> 1))';

            if($filtro['FILTRAR_TALAO'] > 0){
                $talao_filtro = 'AND T.ID = '.$filtro['TALAO_ID'];
            }

            if($filtro['FILTRAR_TALAO'] == 999){
                $requisicao = '';
            }
        }

        $sql = "SELECT $talao

                100 as ORDEM,
                lpad((row_number() over (partition by P.ESTACAO ORDER BY P.DATAHORA_INICIO,T.REMESSA_ID,T.REMESSA_TALAO_ID)),4,0) as SEQUENCIAL,
                T.ID,
                '' INFO_TALAO,
                (SELECT first 1  b.serie FROM TBFERRAMENTARIA B WHERE B.ID = P.FERRAMENTA_ID) as SERIE,
                LPAD(P.FERRAMENTA_ID,4,0) as FERRAMENTA_ID,
                p.ENCERRADO as TALAO_ENCERRADO,
    
                COALESCE((SELECT FIRST 1 SITUACAO FROM TBFERRAMENTARIA x1
                        WHERE x1.ID = P.FERRAMENTA_ID
                        AND x1.GP_ID = P.GP_ID
                        AND x1.UP_ID = P.UP_ID
                        AND x1.ESTACAO = P.ESTACAO
                    ),'E') FERRAMENTA_SITUACAO_TALAO,
                
                 coalesce((select
                                list(j.descricao||': '||coalesce(q.observacao,''),',<br>') as DESC

                            from
                                TBREGISTRO_PARADA Q,
                                tbjustificativa J,
                                (select
                                    distinct v.acumulado,
                                    hh.remessa_id,
                                    hh.remessa_talao_id
                                from SPU_RASTREAMENTO_VINCULO2(T.REMESSA_ID,T.remessa_talao_id,T.ID,0) v, vwremessa_talao_detalhe hh
                                where v.acumulado > 0
                                and hh.id = v.acumulado

                                union

                                select
                                    distinct v.acumulado,
                                    hh.remessa_id,
                                    hh.remessa_talao_id
                                from vwremessa_talao y
                                left join SPU_RASTREAMENTO_VINCULO2(y.remessa_id,y.remessa_talao_id,y.id,0) v on (ACUMULADO > 0)
                                left join vwremessa_talao_detalhe hh on (hh.id = v.acumulado)
                                where v.acumulado > 0
                                and y.id = T.TALAO_EXTRA

                                ) kk

                            where Q.tabela = 'PRODUCAO'
                            and Q.vinculo_id = kk.remessa_id
                            and q.tabela_id  = kk.remessa_talao_id
                            and j.id = Q.status),'') as JUSTIFICATIVA_ORIGEM,

                coalesce((SELECT
                                    list(j.descricao||': '||coalesce(q.observacao,''),',<br>') as DESC from

                                TBREGISTRO_PARADA Q,
                                tbjustificativa J
                                where Q.tabela = 'INEFICIENCIA'
                                and Q.tabela_id = P.ID
                                and j.id = Q.status)

                        ,'') as JUSTIFICATIVA_INEFIC,

                P.DATAHORA_FIM, 
                P.DATAHORA_INICIO,
                0 as QTD_REQUISICAO,
                
                coalesce((select

                    list(ID||'|'||SETUP_ID||'|'||DATAHORA_INICIO||'|'||TEMPO||'|'||FIM,'#')

                from (select
                    n.ID,
                    n.SETUP_ID,
                    n.TALAO_ID,
                    n.DATAHORA_INICIO,
                    n.DATAHORA_FIM,
                    iif((n.datahora_fim - n.DATAHORA_INICIO) < 0, 0,trunc(coalesce((n.datahora_fim - n.DATAHORA_INICIO) * 86500000,0))) as TEMPO,
                    iif(n.datahora_fim is null,0,1) FIM

                from TBREGISTRO_SETUP n
                where n.talao_id = T.ID
                and n.requisicao = p.status_requisicao
                order by n.setup_id, n.id)),'0|0|0|0|1') as SETUP,

                COALESCE(

                    (SELECT FIRST 1 1
                     FROM TBPROGRAMACAO W

                    WHERE W.FERRAMENTA_ID = P.FERRAMENTA_ID
                      AND W.status < 3
                      and not (w.status = 1 and w.status_requisicao = 1)
                      AND W.ID <> P.ID
                      AND (P.DATAHORA_INICIO BETWEEN W.DATAHORA_INICIO AND W.DATAHORA_FIM
                       OR  P.DATAHORA_FIM    BETWEEN W.DATAHORA_INICIO AND W.DATAHORA_FIM
                       OR  W.DATAHORA_INICIO BETWEEN P.DATAHORA_INICIO AND P.DATAHORA_FIM
                       OR  W.DATAHORA_FIM    BETWEEN P.DATAHORA_INICIO AND P.DATAHORA_FIM )

                ),0) AS CONFLITO,

                COALESCE((select

                        mf.TM

                    from tbup_fluxo pf, TBMODELO_FLUXO_PRODUCAO mf
                    where pf.up_id   = p.UP_ID
                    and mf.fluxo_id  = pf.fluxo_id
                    and mf.modelo_id = T.MODELO_ID
                    and mf.cor_id    = D.COR_CODIGO
                    and mf.tamanho   = T.TAMANHO),0) as TEMPO_MAQUINA,
                
                T.PRODUTO_ID,
                P.ID AS PROGRAMACAO_ID,
                P.FLAG_REPROGRAMADO,
                P.ESTACAO,
                P.ESTACAO OLD_ESTACAO,
                U.DESCRICAO,
                R.REMESSA,
                P.tempo,
                T.REMESSA_ID,
                LPAD(T.REMESSA_TALAO_ID,4,'0') AS REMESSA_TALAO_ID,
                T.MODELO_ID,
                T.TAMANHO TAMANHO_POS,
                T.QUANTIDADE,
                P.TEMPO_SETUP_FERRAMENTA, 
                P.TEMPO_SETUP_AQUECIMENTO,
                P.TEMPO_SETUP_COR, 
                P.TEMPO_SETUP_APROVACAO,

                (P.TEMPO_SETUP_FERRAMENTA + P.TEMPO_SETUP_AQUECIMENTO + P.TEMPO_SETUP_COR + P.TEMPO_SETUP_APROVACAO) as SETUP_TOTAL,
                
                p.DATA_INICIADO,

                P.TEMPO_OPERACIONAL,
                D.COR_CODIGO,
                C.DESCRICAO AS COR_DESCRICAO,
                C.AMOSTRA AS COR_AMOSTRA,
                M.DESCRICAO AS MODELO_DESCRICAO,
                TAMANHO_GRADE(D.GRADE_CODIGO,T.TAMANHO)AS TAMANHO,

                FORMATDATE((select first 1 k.data_producao from tbturno_producao k where k.turno_corrente = 1)) AS DATA_PRODUCAO,

                D.DESCRICAO AS PRODUTO,

                IIF(P.STATUS_REQUISICAO = 1, COALESCE((select
                        list(
                            REMESSA||'|'||
                            id||'|'||
                            QUANTIDADE
                        ,'#')
                        FROM(
                            SELECT
                                J.id,
                                j.QUANTIDADE,
                                coalesce((select
                                    coalesce(I.REMESSA,0)||'|'||
                                    coalesce(v.CONTROLE,0)||'|'||
                                    coalesce(v.SITUACAO,0)||'|'||
                                    coalesce(v.sobras,0)||'|'||
                                    coalesce(formatdatetime(v.hora_liberacao),0)||'|'||
                                    coalesce(formatdatetime(v.hora_producao),0)
                                   FROM
                                        TBREMESSA_ITEM_PROCESSADO v,
                                        TBREMESSA I
                                    WHERE v.REMESSA    = j.REMESSA_GERADA
                                        AND v.CONTROLE   = j.TALAO
                                        AND I.NUMERO     = j.REMESSA_GERADA
                                ),'0|0|0|0|0|0') AS REMESSA
                            
                            FROM TBREQUISICAO j
                            
                            WHERE j.REMESSA    = T.REMESSA_ID
                                AND j.PRODUTO_ID = T.PRODUTO_ID
                                AND j.TAMANHO    = T.TAMANHO
                                AND j.GP_ID      = P.GP_ID

                        )),'0|0|0|0|0|0|0|0'),'0|0|0|0|0|0|0|0') AS REQUISICAO,

                COALESCE(P.STATUS_REQUISICAO,0) as STATUS_REQUISICAO,

                IIF(
                    iif( extract( WEEKDAY from current_date) = 6, R.DATA + 1,
                    iif( extract( WEEKDAY from R.DATA) = 5,R.DATA+3,
                    iif( extract( WEEKDAY from R.DATA) = 6,R.DATA+2,R.DATA+1)))
                < CURRENT_DATE,1,0) AS ATRASADO,

                IIF(
                    iif( extract( WEEKDAY from current_date) = 6, R.DATA,
                    iif( extract( WEEKDAY from R.DATA) = 5,R.DATA+2,
                    iif( extract( WEEKDAY from R.DATA) = 6,R.DATA+1,R.DATA)))
                < CURRENT_DATE,1,0) AS ATRASADO2,

                IIF(P.TEMPO_SETUP_FERRAMENTA > 0,'M','') AS TROCA_MATRIZ,
                IIF(R.AMOSTRA > 0,'A','') AS TROCA_AMOSTRA,
                IIF(R.TIPO = '2' AND R.REQUISICAO = '0','V','') AS TROCA_VIP,
                IIF(P.status_requisicao = '1','R','') AS TROCA_REQUISICAO,
                IIF(P.STATUS = '1','P','') AS TROCA_PARADA,

                TRIM(IIF( R.TIPO = '1' AND R.REQUISICAO = '0', '1',
                IIF( R.TIPO = '2' AND R.REQUISICAO = '0', '2',
                IIF( R.TIPO = '2' AND R.REQUISICAO = '1', '3', '0')))) TIPO,
                
                TRIM(IIF( R.TIPO = '1' AND R.REQUISICAO = '0', 'NORMAL',
                IIF( R.TIPO = '2' AND R.REQUISICAO = '0', 'VIP',
                IIF( R.TIPO = '2' AND R.REQUISICAO = '1', 'REQUISIÇÃO', 'N/D')))) TIPO_DESCRICAO,

                T.STATUS,
                formatdatetime(T.HORA_PRODUCAO) AS DATAHORA_PRODUCAO,
                formatdatetime(T.HORA_LIBERACAO) AS DATAHORA_LIBERACAO,
                formatdate(R.DATA)  AS DATA_REMESSA,

                TRIM(P.STATUS) PROGRAMACAO_STATUS,
                (CASE
                    P.STATUS
                WHEN '0' THEN 'NÃO INICIADO'
                WHEN '1' THEN 'PARADO'
                WHEN '2' THEN 'EM ANDAMENTO'
                WHEN '3' THEN 'FINALIZADO'
                WHEN '6' THEN 'ENCERRADO'
                ELSE 'INDEFINIDO' END) PROGRAMACAO_STATUS_DESCRICAO,
                
                T.DENSIDADE,
                T.ESPESSURA,

                coalesce((select max(DATA_A) - sum(SEGUNDOS) from

                    (select

                        ID,STATUS_A,DATA_A,STATUS_P,DATA_P,

                        coalesce(iif(STATUS_P is null,0,iif(STATUS_A > STATUS_P,0, DATA_P - DATA_A)),0) as SEGUNDOS

                    from
                    (
                        select
                            l.id,
                            l.status as STATUS_A,
                            l.datahora as DATA_A,
                            lead(l.status) over (order by l.id) as STATUS_P,
                            lead(l.datahora) over (order by l.id) as DATA_P,
                            sum(1) over (order by id desc) as LINHA
                        
                        from tbprogramacao_registro l
                        where l.programacao_id = P.ID
                        order by l.id
                    ) H)G),'0|0') as SEGUNDOS2,

                (select dateadd(minute,(O_TEMPO_FRACAO_MINUTO * -1),current_timestamp)||'#0'||HISTORICO from SPC_PROGRAMACAO_TEMPO_CORRIDO(p.ID,coalesce(P.REQUISICAO_ID,0))) as SEGUNDOS,

                coalesce(P.REQUISICAO_ID,0) as REQUISICAO_ID,

                COALESCE((SELECT FIRST 1 W.JUSTIFICATIVA_ID FROM TBPROGRAMACAO_REGISTRO W WHERE W.PROGRAMACAO_ID = P.ID ORDER BY W.ID DESC),0) AS STATUS_PARADA,
                COALESCE((SELECT FIRST 1 V.DESCRICAO||' EM '||FORMATDATETIME(W.DATAHORA) FROM TBPROGRAMACAO_REGISTRO W,TBJUSTIFICATIVA V WHERE W.PROGRAMACAO_ID = P.ID AND V.ID = W.JUSTIFICATIVA_ID ORDER BY W.ID DESC),'') AS DESCRICAO_PARADA,

                L.CODIGO ||' - '||L.DESCRICAO as LINHA_DESCRICAO,
                t.TALAO_EXTRA,
                iif(t.TALAO_EXTRA > 0,'+','') as LEGENDA_EXTRA,
                t.PERCENTUAL_EXTRA,
                coalesce((select
                
                    coalesce(b.quantidade,0)||'#@#'||
                    coalesce(b.remessa_talao_id,0)||'#@#'||
                    coalesce(b.remessa_id,0)||'#@#'||
                    coalesce(b.status,0)||'#@#'||
                    coalesce(b.hora_producao,'')||'#@#'||
                    coalesce(b.hora_liberacao,'')
                
                from vwremessa_talao b where b.id = t.TALAO_EXTRA),'') as EXTRA,

                COALESCE((
                 SELECT SUM(S.QUANTIDADE - S.SALDO)
                 FROM TBREQUISICAO_SOBRA S
                 WHERE S.REMESSA = r.REMESSA_ID
                   AND S.TALAO = t.REMESSA_TALAO_ID
                   AND S.REQUISICAO_ID = 0), 0.0000) as APROVEITAMENTO_SOBRA

            FROM
                VWREMESSA_TALAO T,
                TBPROGRAMACAO P,
                VWREMESSA R,
                TBSUB_UP U,
                TBPRODUTO D,
                TBCOR C,
                TBMODELO M,
                TBGRADE G,
                tbmodelo_linha l

            WHERE P.TIPO       = 'A'
            AND P.TABELA_ID  = T.ID
            AND R.REMESSA_ID = T.REMESSA_ID
            AND P.GP_ID      = :GP_ID
            AND P.UP_ID      = :UP_ID
            AND P.ESTACAO    IN (".$filtro['ESTACAO_ID'].")
            AND U.UP_ID = P.UP_ID
            AND U.ID = P.ESTACAO
            and L.CODIGO = D.linha_codigo

            AND R.data_disponibilidade <= current_date

            $requisicao
            $talao_filtro

            AND D.CODIGO = T.PRODUTO_ID

            AND C.CODIGO = D.COR_CODIGO
            AND M.CODIGO = T.MODELO_ID
            AND G.CODIGO = D.GRADE_CODIGO

            ORDER BY P.DATAHORA_INICIO,P.ESTACAO,T.REMESSA_ID,T.REMESSA_TALAO_ID";

        $args = array(
            ':GP_ID' => $filtro['GP_ID'],
            ':UP_ID' => $filtro['UP_ID']
        );

        $ret = $con->query($sql, $args);

        return $ret;
    }

    /**
     * Metas
     * @access public
     * @param {} $filtro
     * @param {} $con
     * @return array
     */
    public static function getEficiencia_t($filtro,$con){
        
        $date  = date_create($filtro['DATA_INICIAL']);
        $data1 = date_format($date, 'd.m.Y');

        $date  = date_create($filtro['DATA_FINAL']);
        $data2 = date_format($date, 'd.m.Y');

        $sql_total1 = "";
        $sql_total2 = "";
        $sql_total3 = "";

        $data = '';
        if(!$filtro['FLAG_DATA']){
            $flag = 1;
        }else{
            $flag = 0;
        }

        $sql = "SELECT

                    ESTACAO,
                    TURNO,
                    iif(eficiencia > 100,100,eficiencia) as EFICIENCIA,
                    TEMPO_REALIZADO_OPERACIONAL,
                    TEMPO_PREVISTO_OPERACIONAL,
                    TEMPO_EFIC,

                    iif(eficiencia < EFICIENCIA_A,1,
                    iif(eficiencia >= EFICIENCIA_A and eficiencia <= EFICIENCIA_B,2,
                    iif(eficiencia > EFICIENCIA_B,3,0))) as COR_EFIC,

                    EFICIENCIA_A,
                    EFICIENCIA_B,
                    PERDAS_A,
                    PERDAS_B,

                    QUANTIDADE

                From(
                    SELECT
                        ESTACAO,
                        TURNO,
                        (TEMPO_PREVISTO_OPERACIONAL / iif(TEMPO_EFIC>0,TEMPO_EFIC,1) * 100) as eficiencia,
                        TEMPO_REALIZADO_OPERACIONAL,
                        TEMPO_PREVISTO_OPERACIONAL,
                        TEMPO_EFIC,

                        (EFICIENCIA_A / iif(QUANTIDADE>0,QUANTIDADE,1)) as EFICIENCIA_A,
                        (EFICIENCIA_B / iif(QUANTIDADE>0,QUANTIDADE,1)) as EFICIENCIA_B,
                        (PERDAS_A     / iif(QUANTIDADE>0,QUANTIDADE,1)) as PERDAS_A,
                        (PERDAS_B     / iif(QUANTIDADE>0,QUANTIDADE,1)) as PERDAS_B,

                        iif(QUANTIDADE>0,QUANTIDADE,1) as QUANTIDADE

                    from(
                        SELECT
                            ESTACAO,
                            TURNO,
                            sum(TEMPO_PREVISTO) as TEMPO_PREVISTO,
                            sum(TEMPO_REALIZADO) as TEMPO_REALIZADO,
                            sum(TEMPO_REALIZADO_OPERACIONAL) as TEMPO_REALIZADO_OPERACIONAL,
                            sum(TEMPO_PREVISTO_OPERACIONAL) as TEMPO_PREVISTO_OPERACIONAL,
                            sum(TEMPO_EFIC) as TEMPO_EFIC,
                            sum(EFICIENCIA_A * QUANTIDADE) as EFICIENCIA_A,
                            sum(EFICIENCIA_B * QUANTIDADE) as EFICIENCIA_B,
                            sum(PERDAS_A     * QUANTIDADE) as PERDAS_A,
                            sum(PERDAS_B     * QUANTIDADE) as PERDAS_B,
                            sum(QUANTIDADE) as QUANTIDADE
                        FROM(

                            SELECT * FROM SPC_PRODUCAO_CONFORMACAO_V2(:GP_ID,:UP_ID,'".$filtro['ESTACAO_ID']."','$data1','$data2',:FLAG)

                        )
                        group BY 1,2
                    )
                )

                order by 1,2";
                
        $args = array(
            ':GP_ID'   => $filtro['GP_ID'  ],
            ':UP_ID'   => $filtro['UP_ID'  ],
            ':FLAG'    => $flag  
        );

        $ret = $con->query($sql, $args);

        return $ret;
    }

    /**
     * Metas
     * @access public
     * @param {} $filtro
     * @param {} $con
     * @return array
     */
    public static function getEficiencia_g($filtro,$con){
        
        $date  = date_create($filtro['DATA_INICIAL']);
        $data1 = date_format($date, 'd.m.Y');

        $date  = date_create($filtro['DATA_FINAL']);
        $data2 = date_format($date, 'd.m.Y');

        $sql_total1 = "";
        $sql_total2 = "";
        $sql_total3 = "";

        $data = '';
        if(!$filtro['FLAG_DATA']){
            $flag = 1;
        }else{
            $flag = 0;
        }

        $sql = "SELECT

                    ESTACAO,
                    iif(eficiencia > 100,100,eficiencia) as EFICIENCIA,
                    TEMPO_REALIZADO_OPERACIONAL,
                    TEMPO_PREVISTO_OPERACIONAL,
                    TEMPO_EFIC,

                    iif(eficiencia < EFICIENCIA_A,1,
                    iif(eficiencia >= EFICIENCIA_A and eficiencia <= EFICIENCIA_B,2,
                    iif(eficiencia > EFICIENCIA_B,3,0))) as COR_EFIC,

                    EFICIENCIA_A,
                    EFICIENCIA_B,
                    PERDAS_A,
                    PERDAS_B,

                    QUANTIDADE

                From(
                    SELECT
                        ESTACAO,
                        (TEMPO_PREVISTO_OPERACIONAL / iif(TEMPO_EFIC>0,TEMPO_EFIC,1) * 100) as eficiencia,
                        TEMPO_REALIZADO_OPERACIONAL,
                        TEMPO_PREVISTO_OPERACIONAL,
                        TEMPO_EFIC,

                        (EFICIENCIA_A / iif(QUANTIDADE>0,QUANTIDADE,1)) as EFICIENCIA_A,
                        (EFICIENCIA_B / iif(QUANTIDADE>0,QUANTIDADE,1)) as EFICIENCIA_B,
                        (PERDAS_A     / iif(QUANTIDADE>0,QUANTIDADE,1)) as PERDAS_A,
                        (PERDAS_B     / iif(QUANTIDADE>0,QUANTIDADE,1)) as PERDAS_B,

                        iif(QUANTIDADE>0,QUANTIDADE,1) as QUANTIDADE

                    from(
                        SELECT
                            ESTACAO,
                            sum(TEMPO_PREVISTO) as TEMPO_PREVISTO,
                            sum(TEMPO_REALIZADO) as TEMPO_REALIZADO,
                            sum(TEMPO_REALIZADO_OPERACIONAL) as TEMPO_REALIZADO_OPERACIONAL,
                            sum(TEMPO_PREVISTO_OPERACIONAL) as TEMPO_PREVISTO_OPERACIONAL,
                            sum(TEMPO_EFIC) as TEMPO_EFIC,
                            sum(EFICIENCIA_A * QUANTIDADE) as EFICIENCIA_A,
                            sum(EFICIENCIA_B * QUANTIDADE) as EFICIENCIA_B,
                            sum(PERDAS_A     * QUANTIDADE) as PERDAS_A,
                            sum(PERDAS_B     * QUANTIDADE) as PERDAS_B,
                            sum(QUANTIDADE) as QUANTIDADE
                        FROM(

                            SELECT * FROM SPC_PRODUCAO_CONFORMACAO_V2(:GP_ID,:UP_ID,'".$filtro['ESTACAO_ID']."','$data1','$data2',:FLAG)

                        )
                        group BY 1
                    )
                )

                order by 1";

        $args = array(
            ':GP_ID'   => $filtro['GP_ID'  ],
            ':UP_ID'   => $filtro['UP_ID'  ],
            ':FLAG'    => $flag  
        );

        $ret = $con->query($sql, $args);

        return $ret;
    }

    /**
     * Metas
     * @access public
     * @param {} $filtro
     * @param {} $con
     * @return array
     */
    public static function getEstacoes($filtro,$con){
        
        $data = '';
        if(!$filtro['FLAG_DATA']){
            $data = " AND R.DATA  BETWEEN '".$filtro['DATA_INICIAL']."' AND '".$filtro['DATA_FINAL']."' ";
        }

        $sql = "SELECT

                ESTACAO,
                DESCRICAO,
                TALOES,
                coalesce((select
                    first 1
                    S.STATUS

                from TBREGISTRO_PARADA S

                where S.TABELA_ID = A.ESTACAO
                AND S.TABELA = 'ESTACAO'
                AND S.VINCULO_ID = :GP_ID2
                AND S.SUBVINCULO_ID = A.UP_ID

                order by S.ID DESC),0) STATUS_PARADA,

                coalesce((select
                    first 1
                    j.DESCRICAO||' em '||formatDateTime(s.hora_registro)

                from TBREGISTRO_PARADA S,tbjustificativa j

                where S.TABELA_ID = A.ESTACAO
                AND S.TABELA = 'ESTACAO'
                AND S.VINCULO_ID = :GP_ID1
                AND S.SUBVINCULO_ID = A.UP_ID

                and j.id = s.status

                order by S.ID DESC),'') DESCRI_PARADA,
                
                (select first 1 w.codigo from tbturno_producao w where w.turno_corrente = 1) as TURNO

            from(SELECT
                u.id as ESTACAO,
                u.up_id,
                u.descricao,
                '' as TALOES


                FROM
                    TBSUB_UP U

                WHERE u.up_id = :UP_ID
                AND u.id IN (".$filtro['ESTACAO_ID'].")
                
                group by 1,2,3,4
            ) A";

        $args = array(
            ':GP_ID1' => $filtro['GP_ID'],
            ':GP_ID2' => $filtro['GP_ID'],
            ':UP_ID'  => $filtro['UP_ID']
        );

        $ret = $con->query($sql, $args);

        return $ret;
    }

    /**
     * Iniciar Talao
     * @access public
     * @param {} $filtro
     * @param {} $con
     * @return array
     */
    public static function reprogramarTalao($estabelecimento,$gp_ip,$up_id,$estacao,$con){

        $sql = "EXECUTE PROCEDURE SPU_REPROGRAMACAO_BOJO (:ESTABELECIMENTO,'A',:GP,:UP,:ESTACAO,null);";

        $args = array(
            ':ESTABELECIMENTO'  => $estabelecimento,
            ':GP'               => $gp_ip,
            ':UP'               => $up_id,
            ':ESTACAO'          => $estacao
        );
        
        $con->query($sql, $args);
    }

    /**
     * Historico Talao
     * @access public
     * @param {} $filtro
     * @param {} $con
     * @return array
     */
    public static function historicoTalao($operador_id,$programacao_id,$status,$motivo_id,$con){

        $status_storico = '4';

        if($status == 1){$status_storico = 1;}
        if($status == 2){$status_storico = 0;}
        if($status == 3){$status_storico = 2;}

        //grava historico de alteração de status do talao
        //$sql = "INSERT INTO TBPROGRAMACAO_REGISTRO (PROGRAMACAO_ID, DATAHORA, STATUS, OPERADOR_ID, JUSTIFICATIVA_ID)
        //                VALUES (:PROGRAMACAO_ID, current_timestamp, :STATUS , :OPERADOR_ID,:JUSTIFICATIVA_ID);";

        //$args = array(
        //    ':OPERADOR_ID'      => $operador_id,
        //    ':PROGRAMACAO_ID'   => $programacao_id,
        //    ':STATUS'           => $status_storico,
        //    ':JUSTIFICATIVA_ID' => $motivo_id
        //);

        //$con->query($sql, $args);
    }

    /**
     * Update Data talao Talao
     * @access public
     * @param {} $filtro
     * @param {} $con
     * @return array
     */
    public static function updateDataTalao($programacao_id,$data_talao,$con){

        $sql = "UPDATE TBPROGRAMACAO
                SET datahora_inicio = ".$data_talao."
                WHERE ID = :PROGRAMACAO_ID";

        $args = array(
            ':PROGRAMACAO_ID' => $programacao_id
        );

        $con->query($sql, $args);
    }

    /**
     * Update Data talao Talao
     * @access public
     * @param {} $filtro
     * @param {} $con
     * @return array
     */
    public static function historicoFerramenta($dados,$parada,$status,$con){

        $sql = "INSERT INTO
                TBFERRAMENTARIA_HISTORICO
                (
                    FERRAMENTA_TIPO,
                    FERRAMENTA_ID,
                    STATUS,
                    OPERADOR_ID,
                    GP_ID,
                    UP_ID,
                    ESTACAO,
                    MOTIVO_PARADA,
                    TALAO_ID
                )
                
                VALUES (
                    'M',
                    :FERRAMENTA,
                    :STATUS,
                    :OPERADOR,
                    :GP,
                    :UP,
                    :ESTACAO,
                    :PARADA,
                    :TALAO
                );";

        $args = array(
            ':FERRAMENTA' => $dados['TALAO'   ]['FERRAMENTA_ID'],
            ':OPERADOR'   => $dados['OPERADOR']['OPERADOR_ID'  ],
            ':GP'         => $dados['FILTRO'  ]['GP_ID'        ],
            ':UP'         => $dados['FILTRO'  ]['UP_ID'        ],
            ':ESTACAO'    => $dados['TALAO'   ]['ESTACAO'      ],
            ':TALAO'      => $dados['TALAO'   ]['ID'           ],
            ':PARADA'     => $parada,
            ':STATUS'     => $status
        );

        $con->query($sql, $args);
    }

    /**
     * Update Data talao Talao
     * @access public
     * @param {} $filtro
     * @param {} $con
     * @return array
     */
    public static function updateDataTalao2($programacao_id,$data_talao,$con){

        $sql = "UPDATE TBPROGRAMACAO
                SET datahora_inicio = ".$data_talao.",
                    datahora_fim    = ".$data_talao."
                WHERE ID = :PROGRAMACAO_ID";

        $args = array(
            ':PROGRAMACAO_ID' => $programacao_id
        );

        $con->query($sql, $args);
    }

    /**
     * Trocar Ferramenta
     * @access public
     * @param {} $dados
     * @param {} $con
     * @return array
     */
    public static function trocarFerramenta($dados,$con){

        $sql = "UPDATE TBPROGRAMACAO p
                SET p.ferramenta_id = :FERRAMENTA
                WHERE ID = :PROGRAMACAO_ID";

        $args = array(
            ':PROGRAMACAO_ID' => $dados['TALAO']['PROGRAMACAO_ID'],
            ':FERRAMENTA'     => $dados['FERRAMENTA']['ID']
        );

        $con->query($sql, $args);

        _22130DAO::reprogramarTalao(
            $dados['FILTRO']['ESTABELECIMENTO'],
            $dados['FILTRO']['GP_ID'],
            $dados['FILTRO']['UP_ID'],
            $dados['TALAO']['ESTACAO'],
            $con 
        );
    }


    /**
     * Update status e estacao do talao Talao
     * @access public
     * @param {} $filtro
     * @param {} $con
     * @return array
     */
    public static function updateTalao($programacao_id,$operador_id,$justificativa_id,$status,$estacao,$flag_reprogramado,$con){

        $sql = "UPDATE TBPROGRAMACAO
                SET ESTACAO = :ESTACAO,
                OPERADOR_ID = :OPERADOR_ID,
                JUSTIFICATIVA_ID = :JUSTIFICATIVA_ID,
                STATUS = :STATUS,
                FLAG_REPROGRAMADO = :FLAG_REPROGRAMADO

                WHERE ID = :PROGRAMACAO_ID";

        $args = array(
            ':ESTACAO'           => $estacao,
            ':STATUS'            => $status,
            ':PROGRAMACAO_ID'    => $programacao_id,
            ':OPERADOR_ID'       => $operador_id,
            ':JUSTIFICATIVA_ID'  => $justificativa_id,
            ':FLAG_REPROGRAMADO' => $flag_reprogramado
        );

        $con->query($sql, $args);
    }

    /**
     * Insert Setup talao
     * @access public
     */
    public static function setupInicio($SETUP_ID,$TALAO_ID,$DATA_SETUP,$REQUISICAO,$REQUISICAO_ID,$con){     

        $sql = "UPDATE OR INSERT INTO TBREGISTRO_SETUP (SETUP_ID, DATAHORA_INICIO, TALAO_ID, REQUISICAO, REQUISICAO_ID)
                    VALUES (:SETUP_ID, '".$DATA_SETUP."', :TALAO_ID,:REQUISICAO,:REQUISICAO_ID)
                    MATCHING (SETUP_ID, TALAO_ID,REQUISICAO);";

        $args = array(
            ':SETUP_ID'         => $SETUP_ID,
            ':TALAO_ID'         => $TALAO_ID,
            ':REQUISICAO'       => $REQUISICAO,
            ':REQUISICAO_ID'    => $REQUISICAO_ID
        );

        $con->query($sql, $args);
    }

        /**
     * Insert Setup talao
     * @access public
     */
    public static function iniciarSetup($dados,$con){

        $sql = "UPDATE TBREGISTRO_SETUP N
            SET N.DATAHORA_FIM = '".$dados['DATA_SETUP']."'
            WHERE N.TALAO_ID = :TALAO
            AND N.SETUP_ID = :SETUP
            AND N.DATAHORA_FIM IS NULL";

        $args = array(
            ':TALAO'     => $dados['ID'],
            ':SETUP'     => $dados['SETUP_ID'] - 1,
        );

        $con->query($sql, $args);    

        if($dados['SETUP_ULTIMO'] == 0){
            $sql = "INSERT INTO TBREGISTRO_SETUP (SETUP_ID, DATAHORA_INICIO, TALAO_ID, REQUISICAO)
                          VALUES (:SETUP, '".$dados['DATA_SETUP']."', :TALAO ,:REQUISICAO);";

            $args = array(
                ':TALAO'      => $dados['ID'],
                ':SETUP'      => $dados['SETUP_ID'],
                ':REQUISICAO' => $dados['STATUS_REQUISICAO'],
            );

            $con->query($sql, $args);
        }
    }


    /**
     * Iniciar Talao
     * @access public
     * @param {} $filtro
     * @param {} $con
     * @return array
     */
    public static function iniciarTalao($dados,$con,$commit){
        $status = 2;

        try{

            $dados['FILTRO']['TALAO_ID'] = $dados['TALAO']['ID'];
            $dados['FILTRO']['ESTACAO_ID'] = $dados['TALAO']['ESTACAO'];

            $talao = _22130DAO::getTaloes_producao(
                $dados['FILTRO'],
                $con
            );

            if(isset($talao)){
                if(count($talao) > 0){   
                   $talao = $talao[0];

                   if($talao->ID != $dados['TALAO']['ID']){
                        log_erro('Estação reprogramada, Atualize a tela! | Iniciar:'.$dados['TALAO']['REMESSA_ID'].'/'.$dados['TALAO']['REMESSA_TALAO_ID']);
                   }

                   if($talao->DATAHORA_INICIO != $dados['TALAO']['DATAHORA_INICIO']){
                        log_erro('Este talão foi reprogramado, Atualize a tela! | Iniciar:'.$dados['TALAO']['REMESSA_ID'].'/'.$dados['TALAO']['REMESSA_TALAO_ID']);
                   }

                   if(trim($talao->FERRAMENTA_SITUACAO_TALAO) != 'S'){
                        //log_erro('Ferramenta não esta a caminho | Iniciar:'.$dados['TALAO']['REMESSA_ID'].'/'.$dados['TALAO']['REMESSA_TALAO_ID']);
                   }

                   if($talao->PROGRAMACAO_STATUS == 2){
                        log_erro('Talão já foi iniciado | Iniciar:'.$dados['TALAO']['REMESSA_ID'].'/'.$dados['TALAO']['REMESSA_TALAO_ID']);
                   }

                   if($talao->PROGRAMACAO_STATUS == 3){
                        log_erro('Talão já foi finalizado | Iniciar:'.$dados['TALAO']['REMESSA_ID'].'/'.$dados['TALAO']['REMESSA_TALAO_ID']);
                   }
               }
            }

            _22130DAO::updateDataTalao(
                $dados['TALAO']['PROGRAMACAO_ID'],            
                '\'01.01.2000 00:00:00\'',
                $con
            );

            _22130DAO::reprogramarTalao(
                $dados['FILTRO']['ESTABELECIMENTO'],
                $dados['FILTRO']['GP_ID'],
                $dados['FILTRO']['UP_ID'],
                $dados['TALAO']['ESTACAO'],
                $con 
            );

            $setup1 = 0;
            $setup2 = 0;
            $setup3 = 0;
            foreach ($dados['TALAO']['SETUP'] as $key => $value) {
                if($value['SETUP_ID'] == 1){$setup1 = $value['ID'];}
                if($value['SETUP_ID'] == 2){$setup2 = $value['ID'];}
                if($value['SETUP_ID'] == 3){$setup3 = $value['ID'];}
            }

            if($dados['TALAO']['TEMPO_SETUP_FERRAMENTA']  > 0 && $setup1 == 0){
                _22130DAO::setupInicio(1,$dados['TALAO']['ID'],$dados['TALAO']['DATA_SETUP'],$dados['TALAO']['STATUS_REQUISICAO'],$dados['TALAO']['REQUISICAO_ID'],$con);
            }else{

                if(($dados['TALAO']['TEMPO_SETUP_AQUECIMENTO']  > 0 || $dados['TALAO']['TEMPO_SETUP_COR']  > 0)  && $setup2 == 0){
                    _22130DAO::setupInicio(2,$dados['TALAO']['ID'],$dados['TALAO']['DATA_SETUP'],$dados['TALAO']['STATUS_REQUISICAO'],$dados['TALAO']['REQUISICAO_ID'],$con);
                }else{

                    if($dados['TALAO']['TEMPO_SETUP_APROVACAO']  > 0  && $setup3 == 0){
                        _22130DAO::setupInicio(3,$dados['TALAO']['ID'],$dados['TALAO']['DATA_SETUP'],$dados['TALAO']['STATUS_REQUISICAO'],$dados['TALAO']['REQUISICAO_ID'],$con);
                    }
                }
            }

            _22130DAO::updateTalao(
                $dados['TALAO']['PROGRAMACAO_ID'],
                $dados['OPERADOR']['OPERADOR_ID'],
                0,
                $status,
                $dados['TALAO']['ESTACAO'],
                0,
                $con
            );

            if($commit > 0){
                $con->commit();
            }

        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }

        return 0;

    }

    /**
     * Iniciar Talao
     * @access public
     * @param {} $filtro
     * @param {} $con
     * @return array
     */
    public static function trocarEstacaoTalao($dados,$con,$commit){
        $status = 0;

        try{

            $dados['FILTRO']['TALAO_ID'] = $dados['TALAO']['ID'];
            $dados['FILTRO']['ESTACAO_ID'] = $dados['TALAO']['OLD_ESTACAO'];
            $dados['FILTRO'] ['FILTRAR_TALAO'] = 1;
            
            $talao = _22130DAO::getTaloes_producao(
                $dados['FILTRO'],
                $con
            );

            if(isset($talao)){
                if(count($talao) > 0){    
                   $talao = $talao[0];

                   if($talao->ID != $dados['TALAO']['ID']){
                        log_erro('Estação reprogramada, Atualize a tela! | Trocar:'.$dados['TALAO']['REMESSA_ID'].'/'.$dados['TALAO']['REMESSA_TALAO_ID']);
                   }

                   if($talao->DATAHORA_INICIO != $dados['TALAO']['DATAHORA_INICIO']){
                        log_erro('Este talão foi reprogramado, Atualize a tela! | Trocar:'.$dados['TALAO']['REMESSA_ID'].'/'.$dados['TALAO']['REMESSA_TALAO_ID']);
                   }

                   if($talao->PROGRAMACAO_STATUS == 2){
                        log_erro('Talão em produção | Trocar:'.$dados['TALAO']['REMESSA_ID'].'/'.$dados['TALAO']['REMESSA_TALAO_ID']);
                   }

                   if($talao->PROGRAMACAO_STATUS == 3){
                        log_erro('Talão já foi finalizado | Trocar:'.$dados['TALAO']['REMESSA_ID'].'/'.$dados['TALAO']['REMESSA_TALAO_ID']);
                   }
                }
            }

            $date1  = date_create($dados['TALAO']['HORA_TALAO_ANTERIOR']);
            $dataf1 = date_format($date1, 'd.m.Y H:i:s');
            $dataf1 = '\''.$dataf1.'\'';

            $date2  = date_create($dados['TALAO']['DATAHORA_INICIO']);
            $dataf2 = date_format($date2, 'd.m.Y H:i:s');
            $dataf2 = '\''.$dataf2.'\'';

            if($date1 > $date2){
                $data_velha = $dataf2;
                $dataf_velha = $date2;
            }else{
                $data_velha = $dataf1;
                $dataf_velha = $date1;
            }

            _22130DAO::updateDataTalao(
                $dados['TALAO']['PROGRAMACAO_ID'],
                $dataf1,
                $con
            );
            
            _22130DAO::updateTalao(
                $dados['TALAO']['PROGRAMACAO_ID'],
                $dados['OPERADOR']['OPERADOR_ID'],
                0,
                0,
                $dados['TALAO']['ESTACAO'],
                1,
                $con
            );

            $d1 = strtotime(date_format($dataf_velha, 'd.m.Y H:i:s'));
            $d2 = strtotime(date_format(date_create(date("Y-m-d H:i:s")), 'd.m.Y H:i:s'));

            _22130DAO::reprogramarTalao(
                $dados['FILTRO']['ESTABELECIMENTO'],
                $dados['FILTRO']['GP_ID'],
                $dados['FILTRO']['UP_ID'],
                $dados['TALAO']['ESTACAO'],
                $con 
            );

            //verifica se a estacao origem é diferente da destino 
            if($dados['TALAO']['OLD_ESTACAO'] != $dados['TALAO']['ESTACAO']){
                //executa reprogramacao na estacao de origem
                _22130DAO::reprogramarTalao(
                    $dados['FILTRO']['ESTABELECIMENTO'],
                    $dados['FILTRO']['GP_ID'],
                    $dados['FILTRO']['UP_ID'],
                    $dados['TALAO']['OLD_ESTACAO'],
                    $con 
                );
            }

            if($commit > 0){
                $con->commit();
            }

        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }

        return 0;

    }

    /**
     * Parar Talao
     * @access public
     * @param {} $filtro
     * @param {} $con
     * @return array
     */
    public static function pararTalao($dados,$con,$commit){
        $status = 1;

        try{

            $dados['FILTRO']['TALAO_ID'] = $dados['TALAO']['ID'];
            $dados['FILTRO']['ESTACAO_ID'] = $dados['TALAO']['ESTACAO'];

            $talao = _22130DAO::getTaloes_producao(
                $dados['FILTRO'],
                $con
            );

            if(isset($talao)){
                if(count($talao) > 0){   
                   $talao = $talao[0];

                   if($talao->ID != $dados['TALAO']['ID']){
                        log_erro('Estação reprogramada, Atualize a tela! | Parar:'.$dados['TALAO']['REMESSA_ID'].'/'.$dados['TALAO']['REMESSA_TALAO_ID']);
                   }

                   if($talao->DATAHORA_INICIO != $dados['TALAO']['DATAHORA_INICIO']){
                        log_erro('Este talão foi reprogramado, Atualize a tela! | Parar:'.$dados['TALAO']['REMESSA_ID'].'/'.$dados['TALAO']['REMESSA_TALAO_ID']);
                   }

                   if($talao->PROGRAMACAO_STATUS < 2){
                        log_erro('Talão não entrou em produção | Parar:'.$dados['TALAO']['REMESSA_ID'].'/'.$dados['TALAO']['REMESSA_TALAO_ID']);
                   }

                   if($talao->PROGRAMACAO_STATUS == 3){
                        log_erro('Talão já foi finalizado | Iniciar:'.$dados['TALAO']['REMESSA_ID'].'/'.$dados['TALAO']['REMESSA_TALAO_ID']);
                   }
                }
            }

            _22130DAO::updateTalao(
                $dados['TALAO']['PROGRAMACAO_ID'],
                $dados['OPERADOR']['OPERADOR_ID'],
                $dados['TALAO']['STATUS_PARADA'],
                $status,
                $dados['TALAO']['ESTACAO'],
                0,
                $con
            );

            $sql = "DELETE FROM TBREGISTRO_SETUP N
                WHERE N.TALAO_ID = :TALAO
                AND N.REQUISICAO = :REQUISICAO
                AND N.DATAHORA_FIM IS NULL";

            $args = array(
                ':TALAO'      => $dados['TALAO']['ID'],
                ':REQUISICAO' => $dados['TALAO']['STATUS_REQUISICAO']
            );

            $con->query($sql, $args); 

            if($commit > 0){
                $con->commit();
            }

        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }

        return 0;
    }

    /**
     * Finalizar Talao
     * @access public
     * @param {} $filtro
     * @param {} $con
     * @return array
     */
    public static function finalizarTalao($dados,$con,$commit){
        $status = 3;

        try{

            $dados['FILTRO']['TALAO_ID'] = $dados['TALAO']['ID'];
            $dados['FILTRO']['ESTACAO_ID'] = $dados['TALAO']['ESTACAO'];

            $talao = _22130DAO::getTaloes_producao(
                $dados['FILTRO'],
                $con
            );

            if(isset($talao) && $commit == 0){
                if(count($talao) > 0){   
                   $talao = $talao[0];

                   if($talao->ID != $dados['TALAO']['ID']){
                        log_erro('Erro: Estação foi reprogramada, Atualize a tela! | Finalizar:'.$dados['TALAO']['REMESSA_ID'].'/'.$dados['TALAO']['REMESSA_TALAO_ID']);
                   }

                   if($talao->DATAHORA_INICIO != $dados['TALAO']['DATAHORA_INICIO']){
                        log_erro('Erro: Este talão foi reprogramado, Atualize a tela! | Finalizar:'.$dados['TALAO']['REMESSA_ID'].'/'.$dados['TALAO']['REMESSA_TALAO_ID']);
                   }

                   if($talao->PROGRAMACAO_STATUS == 3){
                        log_erro('Talão já foi finalizado | Finalizar:'.$dados['TALAO']['REMESSA_ID'].'/'.$dados['TALAO']['REMESSA_TALAO_ID']);
                   }

                   if($talao->PROGRAMACAO_STATUS < 2){
                        log_erro('Talão não entrou em produção | Finalizar:'.$dados['TALAO']['REMESSA_ID'].'/'.$dados['TALAO']['REMESSA_TALAO_ID']);
                   }
               }
            }
            
            _22130DAO::updateTalao(
                $dados['TALAO']['PROGRAMACAO_ID'],
                $dados['OPERADOR']['OPERADOR_ID'],
                0,
                3,
                $dados['TALAO']['ESTACAO'],
                0,
                $con
            );

            _22130DAO::historicoTalao(
                $dados['OPERADOR']['OPERADOR_ID'],
                $dados['TALAO']['PROGRAMACAO_ID'],
                3,
                0,
                $con
            );
            
            _22130DAO::reprogramarTalao(
                $dados['FILTRO']['ESTABELECIMENTO'],
                $dados['FILTRO']['GP_ID'],
                $dados['FILTRO']['UP_ID'],
                $dados['TALAO']['ESTACAO'],
                $con 
            );
            
            $sql = "EXECUTE PROCEDURE AJUSTAR_SETUP_TALAO(:TALAO,:SETUP1,:SETUP2,:SETUP3,:DATA_TALAO,:REQUISICAO,:REQUISICAO_ID)";

            $args = array(
                ':TALAO'            => $dados['TALAO']['ID'],
                ':SETUP1'           => $dados['TALAO']['TEMPO_SETUP_FERRAMENTA'],
                ':SETUP2'           => $dados['TALAO']['TEMPO_SETUP_AQUECIMENTO'] + $dados['TALAO']['TEMPO_SETUP_COR'],
                ':SETUP3'           => $dados['TALAO']['TEMPO_SETUP_APROVACAO'],
                ':DATA_TALAO'       => $dados['TALAO']['DATA_INICIADO'],
                ':REQUISICAO'       => $dados['TALAO']['STATUS_REQUISICAO'],
                ':REQUISICAO_ID'    => $dados['TALAO']['REQUISICAO_ID']
            );

            $con->query($sql, $args);

            if($commit > 0){
                $con->commit();
            }

        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }

        return 0;
    }

    /**
     * Conformacoes
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function getConformacao($dados) {
        
        $con = new _Conexao();
        
        try {

            $filtro = $dados['FILTRO'];

            $filtro = isset($filtro) ? "'%" .str_replace(' ', '%', $filtro). "%'" : '%';

            $sql = "SELECT * FROM TBUP U WHERE U.ID||' '||U.DESCRICAO LIKE ".$filtro."
                    AND U.FAMILIA_ID = :FAMILIA
                    AND U.STATUS = :STATUS

                    ORDER BY U.DESCRICAO";

            $args = array(
                ':FAMILIA' => $dados['FAMILIA'],
                ':STATUS' => 1,
            );

            $ret = $con->query($sql, $args);

            $con->commit();
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Matriz
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function getMatriz($dados) {
        
        $con = new _Conexao();
        
        try {

            $sql = "SELECT
    
                       B.DESCRICAO AS FERRAMENTA_DESCRICAO,
                       B.ID AS FERRAMENTA_CODIGO,
                       C.LINHA_ID,
                       C.TAMANHO,
                       B.codbarras
                    
                    FROM TBMATRIZ A, TBFERRAMENTARIA B, TBFERRAMENTARIA_ITEM C
                    WHERE B.ID = :FERRAMENTA
                    AND C.FERRAMENTARIA_ID = B.ID
                    AND B.codbarras = :CODBARRAS";

            $args = array(
                ':FERRAMENTA' => $dados['FERRAMENTA_ID'],
                ':CODBARRAS'  => $dados['MATRIZ_BARRAS']
            );

            $ret = $con->query($sql, $args);

            $con->commit();
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Conformacoes
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function getMaquina($dados) {
        
        $con = new _Conexao();
        
        try {

            $filtro = $dados['FILTRO'];

            $filtro = isset($filtro) ? "'%" .str_replace(' ', '%', $filtro). "%'" : '%';

            $sql = "SELECT * FROM TBSUB_UP U WHERE U.ID||' '||U.DESCRICAO LIKE ".$filtro."
                    AND U.STATUS = :STATUS

                    ORDER BY U.DESCRICAO";

            $args = array(
                ':STATUS' => 1,
            );

            $ret = $con->query($sql, $args);

            $con->commit();
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Dados de pedido de um talão
     */
    public static function pedidosTalao($dados,$con){

        if(array_key_exists('REMESSA_ID', $dados)){

            $sql = "SELECT PEDIDO, DATA, NOMEFANTASIA from(
                    SELECT

                        i.pedido,
                        i.data_cliente as DATA,
                        c.nomefantasia
                        
                        FROM
                        
                            vwremessa_talao_detalhe w,
                            tbremessa_item_alocacao a,
                            tbpedido i,
                            tbempresa c
                        
                        WHERE w.remessa_id        = :REMESSA_ID1
                        and w.remessa_talao_id  = :REMESSA_TALAO_ID1
                        and a.remessa           = w.remessa_id
                        and a.talao             = w.id
                        and i.pedido            = a.tabela_id
                        and c.codigo            = i.cliente_codigo

                    group by 1,2,3

                    union all

                    SELECT

                        0 as pedido,
                        ''  as DATA,
                        'PRONTA ENTREGA' as nomefantasia

                        FROM

                            vwremessa_talao_detalhe w,
                            tbestoque_minimo m

                        WHERE w.remessa_id         = :REMESSA_ID2
                        and w.remessa_talao_id   = :REMESSA_TALAO_ID2
                        and m.produto_codigo     = w.produto_id
                        and m.localizacao_codigo = w.localizacao_id

                    group by 1,2,3
                    ) order by pedido desc";

            $args = array(
                ':REMESSA_ID1'       => $dados['REMESSA_ID'],
                ':REMESSA_ID2'       => $dados['REMESSA_ID'],
                ':REMESSA_TALAO_ID1' => $dados['REMESSA_TALAO_ID'],
                ':REMESSA_TALAO_ID2' => $dados['REMESSA_TALAO_ID']
            );

            $ret = $con->query($sql, $args);

        }else{
            $ret = [];
        }
        
        return $ret;
    }

    /**
     * Dados de espuma de um talão
     */
    public static function espumaTalao($dados,$con){

        $espumas = _22130DAO::espumaTalaoRequisicao($dados,$con);
        
        $sql = "SELECT

                V.TABELA_ID,
                V.tipo

                FROM
                VWREMESSA_CONSUMO C,
                TBREMESSA_TALAO_VINCULO V,
                (SELECT FIRST 1
                    V.REMESSA_ID,
                    V.REMESSA_TALAO_ID
                FROM
                    VWREMESSA_CONSUMO C,
                    TBREMESSA_CONSUMO_VINCULO V
                WHERE
                    C.REMESSA_ID = :REMESSA_ID
                    AND C.REMESSA_TALAO_ID = :REMESSA_TALAO_ID
                    AND V.CONSUMO_ID = C.ID) J
            WHERE
                C.REMESSA_ID = J.REMESSA_ID
                AND C.REMESSA_TALAO_ID = J.REMESSA_TALAO_ID
                AND V.CONSUMO_ID = C.ID
                AND C.REMESSA_TALAO_DETALHE_ID = 0";

        $args = array(
            ':REMESSA_ID'       => $dados['REMESSA_ID'],
            ':REMESSA_TALAO_ID' => $dados['REMESSA_TALAO_ID']
        );

        $ret = $con->query($sql, $args);

        if(count($ret) > 0){
            array_push($espumas,$ret);
        }

        $itens = [
            [
            'TABELA_ID' => 'NÃO VINCULADA',
            'TIPO'      => '0'
            ]
        ];

        if(count($espumas) > 0){
            $itens = [];

            foreach ($espumas as $key => $espuma) {
                foreach ($espuma as $key => $value) {
                    array_push($itens,$value);
                }
            }
        }
        
        return $itens;
    }

    /**
     * Dados de matriz de um talão
     */
    public static function matrizTalao($dados,$con){
        
        $sql = "SELECT

                   M.DESCRICAO AS MATRIZ_DESCRICAO,
                   F.DESCRICAO AS FERRAMENTA_DESCRICAO,
                   M.CODIGO AS MATRIZ_CODIGO,
                   F.ID AS FERRAMENTA_CODIGO

                FROM TBMATRIZ M, TBFERRAMENTARIA F
                WHERE F.MATRIZ_ID = M.CODIGO
                AND F.ID = :FERRAMENTA";

        $args = array(
            ':FERRAMENTA' => $dados['FERRAMENTA_ID'],
        );

        $ret = $con->query($sql, $args);

        $matriz = [
            'FERRAMENTA_CODIGO'     => '',
            'FERRAMENTA_DESCRICAO'  => '',
            'MATRIZ_CODIGO'         => '',
            'MATRIZ_DESCRICAO'      => ''
        ];

        if(count($ret) > 0){
           $matriz  = $ret[0];
        }
        
        return $matriz;
    }

    /**
     * Dados de um talão
     */
    public static function dadosTalao($dados,$con){
        $ret = [];

        /*
        $sql = "SELECT FIRST 10

                    RAZAOSOCIAL,
                    CODIGO,
                    REPRESENTANTE_CODIGO,
                    TRANSPORTADORA_CODIGO,
                    PAGAMENTO_CONDICAO,
                    PAGAMENTO_FORMA,
                    STATUS

                FROM TBCLIENTE C WHERE C.CODIGO > :CODIGO";

        $args = array(
            ':CODIGO' => 1000,
        );

        $ret = $con->query($sql, $args);
        //*/

        return $ret;
    }

    /**
     * Dados de produto de um talão
     */
    public static function skuTalao($dados,$con){
        $ret = [];
        
        $sql = "SELECT FIRST 1 

                Y.DESCRICAO,
                Y.DESCRICAO_COMPLETA,
                K.PERFIL

                FROM

                VWSKU K,
                TBPERFIL Y

                WHERE K.MODELO_ID = :MODELO
                    AND K.COR_ID = :COR
                    AND K.TAMANHO = :TAMANHO
                    AND Y.ID = K.PERFIL
                    AND Y.TABELA = 'SKU'";

        $args = array(
            ':MODELO'   => $dados['MODELO_ID'],
            ':COR'      => $dados['COR_CODIGO'],
            ':TAMANHO'  => $dados['TAMANHO_POS']
        );

        $ret = $con->query($sql, $args);

        $produto = [
            'DESCRICAO'           => '',
            'DESCRICAO_COMPLETA'  => '',
            'PERFIL'              => ''
        ];

        if(count($ret) > 0){
           $produto  = $ret[0];
        }
        
        return $produto;
    }

    /**
     * Dados de tecido de um talão
     */
    public static function tecidoTalao($dados,$con){
        $sql = "SELECT LIST(OB,',') AS OB FROM
                (SELECT DISTINCT
                        P.OB||'-'||O.classificacao as OB
                        FROM
                        VWREMESSA_TALAO_DETALHE D,
                        VWREMESSA_CONSUMO C,
                        TBREMESSA_TALAO_VINCULO V,
                        TBREVISAO P,
                        TBREVISAO_OB O

                WHERE
                    D.ID = (
                        SELECT FIRST 1
                            V.TABELA_ID
                            FROM
                            VWREMESSA_TALAO_DETALHE D,
                            VWREMESSA_CONSUMO C,
                            TBREMESSA_TALAO_VINCULO V,
                            (SELECT FIRST 1
                                V.REMESSA_ID,
                                V.REMESSA_TALAO_ID,
                                v.remessa_talao_detalhe_id
                            FROM
                                VWREMESSA_CONSUMO C,
                                TBREMESSA_CONSUMO_VINCULO V
                            WHERE
                                C.REMESSA_ID = :REMESSA_ID
                                AND C.REMESSA_TALAO_ID = :REMESSA_TALAO_ID
                                AND V.CONSUMO_ID = C.ID) J
                        WHERE
                            C.REMESSA_ID = J.REMESSA_ID
                            AND C.REMESSA_TALAO_ID = J.REMESSA_TALAO_ID
                            AND V.CONSUMO_ID = C.ID
                            AND D.ID = C.REMESSA_TALAO_DETALHE_ID
                            AND C.REMESSA_TALAO_DETALHE_ID > 0
                            and c.remessa_talao_detalhe_id = j.remessa_talao_detalhe_id
                        )
                    AND C.REMESSA_ID = D.REMESSA_ID
                    AND C.REMESSA_TALAO_ID = D.REMESSA_TALAO_ID
                    AND V.CONSUMO_ID = C.ID
                    AND P.ID = V.TABELA_ID
                    AND V.TIPO = 'R'
                    AND C.REMESSA_TALAO_DETALHE_ID > 0
                    AND P.PRODUTO_ID = D.PRODUTO_ID
                    and O.OB = P.OB

                )";

                //log_info($dados);

        $args = array(
            ':REMESSA_ID'       => $dados['REMESSA_ID'],
            ':REMESSA_TALAO_ID' => $dados['REMESSA_TALAO_ID']
        );

        $ret = $con->query($sql, $args);


        $obs = _22130DAO::tecidoTalaoRequisicao($dados,$con);

        if(count($ret) > 0){
            $ob = $ret[0];

            if($obs == ''){
                $obs = $ob->OB;
            }else{
                $obs = $obs . ', ' . $ob->OB;
            }
        }
        
        return $obs;
    }

    /**
     * Dados de tecido da requisicao de um talão
     */
    public static function tecidoTalaoRequisicao($dados,$con){

        $obs = '';

        foreach ($dados['REQUISICAO']['ITENS'] as $key => $requisicao) {
                
            $sql = "SELECT LIST(OB,',') AS OB FROM
                    (SELECT DISTINCT
                            P.OB
                            FROM
                            VWREMESSA_TALAO_DETALHE D,
                            VWREMESSA_CONSUMO C,
                            TBREMESSA_TALAO_VINCULO V,
                            TBREVISAO P
                    WHERE
                        D.ID = (
                            SELECT FIRST 1
                                V.TABELA_ID
                                FROM
                                VWREMESSA_TALAO_DETALHE D,
                                VWREMESSA_CONSUMO C,
                                TBREMESSA_TALAO_VINCULO V,
                                (SELECT FIRST 1
                                    V.REMESSA_ID,
                                    V.REMESSA_TALAO_ID
                                FROM
                                    VWREMESSA_CONSUMO C,
                                    TBREMESSA_CONSUMO_VINCULO V
                                WHERE
                                    C.REMESSA_ID = :REMESSA_ID
                                    AND C.REMESSA_TALAO_ID = :REMESSA_TALAO_ID
                                    AND V.CONSUMO_ID = C.ID) J
                            WHERE
                                C.REMESSA_ID = J.REMESSA_ID
                                AND C.REMESSA_TALAO_ID = J.REMESSA_TALAO_ID
                                AND V.CONSUMO_ID = C.ID
                                AND D.ID = C.REMESSA_TALAO_DETALHE_ID
                                AND C.REMESSA_TALAO_DETALHE_ID > 0
                            )
                        AND C.REMESSA_ID = D.REMESSA_ID
                        AND C.REMESSA_TALAO_ID = D.REMESSA_TALAO_ID
                        AND V.CONSUMO_ID = C.ID
                        AND P.ID = V.TABELA_ID
                        AND V.TIPO = 'R'
                        AND C.REMESSA_TALAO_DETALHE_ID > 0
                        AND P.PRODUTO_ID = D.PRODUTO_ID
                    )";

            $args = array(
                ':REMESSA_ID'       => $requisicao['REMESSA'],
                ':REMESSA_TALAO_ID' => $requisicao['TALAO']
            );

            $ret = $con->query($sql, $args);

            if( count($ret) > 0){
                $ob = $ret[0];

                if($obs == ''){
                    $obs = $ob->OB;

                }else{
                    $obs = $obs . ', ' . $ob->OB;
                }
            }
        }

        return $obs;
    }

    /**
     * Dados de espuma da requisicao de um talão
     */
    public static function espumaTalaoRequisicao($dados,$con){

        $espuma = [];

        foreach ($dados['REQUISICAO']['ITENS'] as $key => $requisicao) {
                
            $sql = "SELECT

                V.TABELA_ID,
                V.tipo

                FROM
                VWREMESSA_CONSUMO C,
                TBREMESSA_TALAO_VINCULO V,
                (SELECT FIRST 1
                    V.REMESSA_ID,
                    V.REMESSA_TALAO_ID
                FROM
                    VWREMESSA_CONSUMO C,
                    TBREMESSA_CONSUMO_VINCULO V
                WHERE
                    C.REMESSA_ID = :REMESSA_ID
                    AND C.REMESSA_TALAO_ID = :REMESSA_TALAO_ID
                    AND V.CONSUMO_ID = C.ID) J
            WHERE
                C.REMESSA_ID = J.REMESSA_ID
                AND C.REMESSA_TALAO_ID = J.REMESSA_TALAO_ID
                AND V.CONSUMO_ID = C.ID
                AND C.REMESSA_TALAO_DETALHE_ID = 0";

            $args = array(
                ':REMESSA_ID'       => $requisicao['REMESSA'],
                ':REMESSA_TALAO_ID' => $requisicao['TALAO']
            );

            $ret = $con->query($sql, $args);

            if( count($ret) > 0){
                array_push($espuma,$ret);
            }
        }

        return $espuma;
    }

    /**
     * Ferramentas livres para troca
     */
    public static function ferramentasLivres($dados,$con){
        $ret = [];

        $data_inicio = date('d.m.Y', strtotime($dados['DATA_INICIO']));

        $sql = "select

                    GRUPO_ID,
                    ID,
                    DESCRICAO,
                    SERIE,
                    MATRIZ_ID,
                    MATRIZ_DESCRICAO,
                    LINHA_ID,
                    TAMANHO,
                    LARGURA,
                    COMPRIMENTO,
                    ALTURA,
                    DATA,
                    OBSERVACAO,
                    TEMPO_SETUP,
                    TEMPO_SETUP_AQUECIMENTO,
                    STATUS_CONFLITO

                from spc_ferramenta_disponivel(:FERRAMENTA, :DATAHORA_INICIO)";

        $args = array(
            ':FERRAMENTA'      => $dados['FERRAMENTA'],
            ':DATAHORA_INICIO' => $data_inicio
        );

        $ret = $con->query($sql, $args);

        return $ret;
    }


    /**
     * Componentes usados na linha do tempo de producao de um talao/remessa
     */
    public static function getComponentes($dados,$con){
        $ret = [];

        $sql = "SELECT
                    DISTINCT

                    DESCRICAO,
                    REMESSA_ID,
                    REMESSA_TALAO_ID,
                    DATA_PRODUCAO

                FROM SPU_RASTREAMENTO_CONSUMO(:REMESSA_ID,:REMESSA_TALAO_ID) C

                ORDER BY C.NIVEL,C.DESCRICAO";

        $args = array(
            ':REMESSA_ID'       => $dados['REMESSA_ID'],
            ':REMESSA_TALAO_ID' => $dados['REMESSA_TALAO_ID']
        );

        $ret = $con->query($sql, $args);

        return $ret;
    }

    /**
     * Consultra minutos do procimo intervalo disponivel
     * @access public
     * @param {} $dados
     * @param {} $con
     * @return array
     */
    public static function jornadaIntervalo($dados,$con){

        $sql = "SELECT FIRST 1
                     DATEDIFF(MINUTE, DATEADD(-1 MINUTE TO IIF(D.DATAHORA_INICIO < CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,D.DATAHORA_INICIO)), D.DATAHORA_FIM) MINUTOS_DESCANSO,
                     formatdatetime(IIF(D.DATAHORA_INICIO < CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,D.DATAHORA_INICIO)) DATAHORA_INICIO,
                     formatdatetime(D.DATAHORA_FIM) AS DATAHORA_FIM
                FROM SPC_CALENDARIO_HORARIO_DESCANSO(NULL,:UP,:ESTACAO,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP+7) D";

        $args = array(
            ':UP'      => $dados['UP'],
            ':ESTACAO' => $dados['ESTACAO']
        );

        $ret = $con->query($sql, $args); 

        return $ret;
    }

    /**
     * Consultra minutos do procimo intervalo disponivel
     * @access public
     * @param {} $dados
     * @param {} $con
     * @return array
     */
    public static function jornadaGravar($dados){

        $con = new _Conexao();

        try {

            $sql = "EXECUTE PROCEDURE SPU_CALENDARIO_DESCANSO_DELETE(0,:UP_ID,:ESTACAO,CURRENT_TIMESTAMP,:MINUTOS);";

            $args = array(
                ':UP_ID'   => $dados['UP'],
                ':ESTACAO' => $dados['ESTACAO'],
                ':MINUTOS' => $dados['MINUTOS']
            );

            $ret = $con->query($sql, $args);

            $con->commit();
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Consultra minutos do procimo intervalo disponivel
     * @access public
     * @param {} $dados
     * @param {} $con
     * @return array
     */
    public static function getHistoricoTalao($dados){

        $con = new _Conexao();

        try {

            $sql = "select

                            iif((STATUS1 = 1) and (STATUS2 = 0) AND (rec = 0),0,
                            iif((STATUS1 = 1) and (STATUS2 = 0) AND (rec > 0),3,
                            iif((STATUS1 = 0) and (STATUS2 = 1),1,
                            iif((STATUS1 = 0) and (STATUS2 = 2),1,
                            iif(((STATUS1 = 2) and (STATUS2 = 1)) or ((STATUS1 = 2) and (STATUS2 = 0)),2,
                            3))))) as FLAG,

                            iif((STATUS1 = 1) and (STATUS2 = 0),TEMPO_PARADO,
                            iif((STATUS1 = 0) and (STATUS2 = 1),TEMPO_INICIADO,
                            iif((STATUS1 = 0) and (STATUS2 = 2),TEMPO_INICIADO,
                            iif(((STATUS1 = 2) and (STATUS2 = 1)) or ((STATUS1 = 2) and (STATUS2 = 0)) ,sum(TEMPO_INICIADO) over (PARTITION BY requisicao1 order by LINHA)||'/'||sum(TEMPO_PARADO) over (PARTITION  BY requisicao1 order by LINHA)
                            ,0)))) as TEMPO,

                            status1,
                            status2,
                            DATAHORA2,
                            DATAHORA2,

                            TEMPO_PARADO,
                            TEMPO_INICIADO,
                            FIM,
                            OPERADOR,
                            DATAHORA,
                            JUSTIFICATIVA,
                            ESTACAO,
                            TIPO,
                            STATUS,
                            LINHA

                        from(
                            select
                            
                                --iif((x.STATUS1 = 1) and (x.STATUS2 = 0) AND (rec = 0), datediff(minute,x.datahora1,x.datahora2),0) as TEMPO_PARADO,
                                iif((x.STATUS1 = 1) and (x.STATUS2 = 0) AND (rec = 0), (select sum(cast(PRODUTIVO as integer)) from spc_calendario_estacao(up_id,estacao_id,x.datahora1,x.datahora2)),0) as TEMPO_PARADO,
                                iif(((x.STATUS1 = 0) and (x.STATUS2 = 1)) or ((x.STATUS1 = 0) and (x.STATUS2 = 2)) , datediff(minute,x.datahora1,x.datahora2),0) as TEMPO_INICIADO,
                                iif(x.STATUS2 is null,1,0) as FIM,
                            
                                x.*
                            
                            from(
                                select
                                        row_number() over (order by datahora) as LINHA,
                                        r.status as status1,
                                        coalesce(lead(r.status) over (order by r.datahora ascending),0) STATUS2,
                                        lead(r.datahora) over (order by r.datahora ascending) datahora2,

                                        lead(r.requisicao_id) over (order by r.datahora ascending) as requisicao2,
                                        r.requisicao_id as requisicao1,

                                        iif((lag(r.requisicao_id) over (order by r.datahora ascending) = r.requisicao_id),0,1) AS rec,

                                        r.datahora as datahora1,
                                        lpad(p.codigo,6,0)||' - '||p.nome as OPERADOR,
                                        formatdatetime(r.datahora) as DATAHORA,
                                        j.descricao as justificativa,
                                        u.descricao as ESTACAO,
                                        iif(r.requisicao_id > 0,'REQUISIÇÃO','')as TIPO,
                                        iif(r.status = 0,'INICIADO/REINICIADO',
                                        iif(r.status = 1,'PARADA TEMPORARIA',
                                        iif(r.status = 2,'FINALIZADO',''))) as STATUS,
                                    
                                        c.up_id,
                                        c.estacao as estacao_id,
                                        c.gp_id
                                    
                                    from tbprogramacao_registro r,
                                    tboperador p,
                                    tbjustificativa j,
                                    tbsub_up u,
                                    tbprogramacao m,
                                    tbprogramacao c
                                    
                                    where r.programacao_id = :PROGRAMACAO_ID
                                    and p.codigo = r.operador_id
                                    and j.id = r.justificativa_id
                                    and u.up_id = m.up_id
                                    and u.id = r.estacao_id
                                    and m.id = r.programacao_id

                                    and c.id = r.programacao_id
                                    
                                    order by r.datahora
                            ) x
                        ) z";

            $args = array(
                ':PROGRAMACAO_ID' => $dados['PROGRAMACAO_ID']
            );

            $ret = $con->query($sql, $args);

            $con->commit();
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * get Composicao componentes
     * @access public
     * @param {} $dados
     * @param {} $con
     * @return array
     */
    public static function getComposicao($dados,$con){

        try {

            $sql = "SELECT
                        X.TIPO_ID,
                        X.TIPO_DESCRICAO,
                        CAST(COALESCE(MFT.QUANTIDADE,0) AS NUMERIC(15,4)) QUANTIDADE_PADRAO,
                        CAST(COALESCE(RTF.QUANTIDADE,0) AS NUMERIC(15,4)) QUANTIDADE

                    FROM (
                        SELECT D.ID AS REMESSA_TALAO_ID,
                               T.MODELO_ID        AS MODELO_ID,
                               FFM.TIPO_CODIGO    AS TIPO_ID,
                               FFM.TIPO_DESCRICAO AS TIPO_DESCRICAO
                          FROM VWREMESSA_TALAO T,
                               tbremessa r,
                               TBMODELO M,
                               TBFAMILIA_FICHA_MODELO FFM,
                               vwremessa_talao_detalhe d
                         WHERE M.CODIGO           = T.MODELO_ID
                           AND FFM.FAMILIA_CODIGO = M.FAMILIA_CODIGO
                           and r.numero           = t.remessa_id
                           and r.remessa          = :REMESSA
                           and t.remessa_talao_id = :TALAO
                           and d.remessa_id       = r.numero
                           and d.remessa_talao_id = t.remessa_talao_id
                        ) X
                        LEFT JOIN TBMODELO_FICHA_TECNICA MFT
                               ON MFT.TIPO_CODIGO   = X.TIPO_ID
                              AND MFT.MODELO_CODIGO = X.MODELO_ID
                        LEFT JOIN TBREMESSA_TALAO_FICHA  RTF
                               ON RTF.TIPO_ID           = X.TIPO_ID
                              AND RTF.REMESSA_TALAO_ID  = X.REMESSA_TALAO_ID";

            $args = array(
                ':REMESSA' => $dados['REMESSA_ID'],
                ':TALAO'   => $dados['REMESSA_TALAO_ID']
            );

            $ret = $con->query($sql, $args);

            $con->commit();
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }
    
}