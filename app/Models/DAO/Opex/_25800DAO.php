<?php

namespace App\Models\DAO\Opex;

use App\Models\Conexao\_Conexao;
use Exception;

/**
 * DAO do objeto 25800 (BSC-TV)
 * @package Opex
 * @category 25800
 * @use App\Models\DTO\Opex\_25800;
 * @use App\Models\DTO\Helper\Historico;
 * @use App\Models\Conexao\_Conexao;
 * @use Exception;
 */
class _25800DAO
{   
    
    /**
     * FunÃ§Ã£o para codificar uma string 
     * @access public
     * @param string $string
     * @return string
     * @static
    */
    public static  function encrypt($string)
    {     
       $str     = self::codificar($string);
       $result  = self::misture($str);
       
       return $result;
    }
    
    /**
     * FunÃ§Ã£o para consultar dados de produÃ§Ã£o
     * @access public
     * @param string $dados
     * @return string
     * @static
    */
    public static function consultaprod($dados) {
            
        $con = new _Conexao();
                
        $id         = $dados['id'];
        $estab      = $dados['estab'];
        $data       = $dados['data'];
        
        //$sql = 'SELECT * FROM TBTV_HISTORICO H WHERE H.GP = :ID AND H.ESTABELECIMENTO = :ESTAB AND H.DATA_CONSULTA  = :DATA';
        
        $sql = '
            select
                GP,
                DATA_EXEC,
                DATA_CONSULTA,
                PR1,
                PR2,
                PR3,
                PR4,
                PR5,
                PR_SEMANA,
                PR_MEZ,
                DI1,
                DI2,
                DI3,
                DI4,
                DI5,
                DI_SEMANA,
                DI_MEZ,
                PE1,
                PE2,
                PE3,
                PE4,
                PE5,
                PE_SEMANA,
                PE_MEZ,

                (select PERC from calc_perc(PE1,PR1)) as PEP1,
                (select PERC from calc_perc(PE2,PR2)) as PEP2,
                (select PERC from calc_perc(PE3,PR3)) as PEP3,
                (select PERC from calc_perc(PE4,PR4)) as PEP4,
                (select PERC from calc_perc(PE5,PR5)) as PEP5,
                (select PERC from calc_perc(PE_SEMANA,PR_SEMANA)) as PEP_SEMANA,
                (select PERC from calc_perc(PE_MEZ,PR_MEZ)) as PEP_MEZ,

                (select PERC from calc_perc(PR1,DI1+PR1)) as EF1,
                (select PERC from calc_perc(PR2,DI2+PR2)) as EF2,
                (select PERC from calc_perc(PR3,DI3+PR3)) as EF3,
                (select PERC from calc_perc(PR4,DI4+PR4)) as EF4,
                (select PERC from calc_perc(PR5,DI5+PR5)) as EF5,
                (select PERC from calc_perc(PR_SEMANA,DI_SEMANA+PR_SEMANA)) as EF_SEMANA,
                (select PERC from calc_perc(PR_MEZ,DI_MEZ+PR_MEZ)) as EF_MEZ,

                META_DIA,
                META_HORA,
                EF,
                EFP,

                (select PERC from calc_med(EFICADIA,PR2)) as EFICADIA,
                (select PERC from calc_med(EFICBDIA,PR2)) as EFICBDIA,
                (select PERC from calc_med(PERDAADIA,PR2)) as PERDAADIA,
                (select PERC from calc_med(PERDABDIA,PR2)) as PERDABDIA,
                (select PERC from calc_med(EFICANOITE,PR4)) as EFICANOITE,
                (select PERC from calc_med(EFICBNOITE,PR4)) as EFICBNOITE,
                (select PERC from calc_med(PERDAANOITE,PR4)) as PERDAANOITE,
                (select PERC from calc_med(PERDABNOITE,PR4)) as PERDABNOITE,
                (select PERC from calc_med(EFICASEMA,PR_SEMANA)) as EFICASEMA,
                (select PERC from calc_med(EFICBSEMA,PR_SEMANA)) as EFICBSEMA,
                (select PERC from calc_med(PERDAASEMA,PR_SEMANA)) as PERDAASEMA,
                (select PERC from calc_med(PERDABSEMA,PR_SEMANA)) as PERDABSEMA,
                (select PERC from calc_med(EFICAMEZ,PR_MEZ)) as EFICAMEZ,
                (select PERC from calc_med(EFICBMEZ,PR_MEZ)) as EFICBMEZ,
                (select PERC from calc_med(PERDAAMEZ,PR_MEZ)) as PERDAAMEZ,
                (select PERC from calc_med(PERDABMEZ,PR_MEZ)) as PERDABMEZ,
                
                MPE1,
                MPE3,
                MDI1,
                MDI3,
                MPR1,
                MPR3,

                (select PERC from calc_perc(MPE1,MPR1)) as MPEP1,
                (select PERC from calc_perc(MPE3,MPR3)) as MPEP3,

                (select PERC from calc_perc(MPR1,MDI1+MPR1)) as MEF1,
                (select PERC from calc_perc(MPR3,MDI3+MPR3)) as MEF3

                from(

                SELECT
                list(GP,\',\') GP,
                max(DATA_EXEC) DATA_EXEC,
                min(DATA_CONSULTA) DATA_CONSULTA,
                sum(PR1) PR1,
                sum(PR2) PR2,
                sum(PR3) PR3,
                sum(PR4) PR4,
                sum(PR5) PR5,
                sum(PR_SEMANA) PR_SEMANA,
                sum(PR_MEZ) PR_MEZ,
                sum(DI1) DI1,
                sum(DI2) DI2,
                sum(DI3) DI3,
                sum(DI4) DI4,
                sum(DI5) DI5,
                sum(DI_SEMANA) DI_SEMANA,
                sum(DI_MEZ) DI_MEZ,
                sum(PE1) PE1,
                sum(PE2) PE2,
                sum(PE3) PE3,
                sum(PE4) PE4,
                sum(PE5) PE5,
                sum(PE_SEMANA) PE_SEMANA,
                sum(PE_MEZ) PE_MEZ,

                sum(META_DIA) META_DIA,
                sum(META_HORA) META_HORA,
                avg(EF) EF,
                list(EFP) EFP,
                sum(EFICADIA*PR2) EFICADIA,
                sum(EFICBDIA*PR2) EFICBDIA,
                sum(PERDAADIA*PR2) PERDAADIA,
                sum(PERDABDIA*PR2) PERDABDIA,
                sum(EFICANOITE*PR4) EFICANOITE,
                sum(EFICBNOITE*PR4) EFICBNOITE,
                sum(PERDAANOITE*PR4) PERDAANOITE,
                sum(PERDABNOITE*PR4) PERDABNOITE,
                sum(EFICASEMA*PR_SEMANA) EFICASEMA,
                sum(EFICBSEMA*PR_SEMANA) EFICBSEMA,
                sum(PERDAASEMA*PR_SEMANA) PERDAASEMA,
                sum(PERDABSEMA*PR_SEMANA) PERDABSEMA,
                sum(EFICAMEZ*PR_MEZ) EFICAMEZ,
                sum(EFICBMEZ*PR_MEZ) EFICBMEZ,
                sum(PERDAAMEZ*PR_MEZ) PERDAAMEZ,
                sum(PERDABMEZ*PR_MEZ) PERDABMEZ,
                sum(MPE1) MPE1,
                sum(MPE3) MPE3,
                sum(MDI1) MDI1,
                sum(MDI3) MDI3,
                avg(MPEP1) MPEP1,
                avg(MPEP3) MPEP3,
                avg(MEF1) MEF1,
                avg(MEF3) MEF3,
                sum(MPR1) MPR1,
                sum(MPR3) MPR3

                FROM TBTV_HISTORICO_PROD H WHERE H.GP in('.$id.') AND H.ESTABELECIMENTO = :ESTAB AND H.DATA_CONSULTA  = :DATA)
                ';
        
        $args = array(
            ':ESTAB' => $estab,
            ':DATA' => date('d.m.Y', strtotime($data))
        );
      

        $ret = $con->query($sql, $args);
 
        return $ret;
    }
    
    
    /**
     * grava resultado do calculo dos trofeis
     * @access public
     * @param int $t1 ouro
     * @param int $t2 prata
     * @param int $t3 bronze
     * @param string $mes mes de calculo
     * @param int $et estabelecimento
     * @return string
     * @static
    */
    public static function gravatrofeu($t1,$t2,$t3,$mes,$ano,$et) {
        
        $con = new _Conexao();
        
        $sql = '
                UPDATE OR INSERT INTO TBTV_trofeu (GP_IDO,GP_IDP,GP_IDB,ESTABELECIMENTO_ID,MES,ANO)
                VALUES (:T1,:T2,:T3,:ET,:MES,:ANO) MATCHING (ESTABELECIMENTO_ID,MES,ANO)
                ';
                
        $args = array(
            ':T1'  => $t1,
            ':T2'  => $t2,
            ':T3'  => $t3,
            ':ET'  => $et,
            ':MES' => $mes,
            ':ANO' => $ano
        ); 

        $ret = $con->execute($sql, $args);
        
        $con->commit();
        
        return $ret;
        
    }
    
    /**
     * consulta trofeis
     * @access public
     * @param int $mes
     * @param int $ano
     * @param int $estab Estabelecimento
     * @return string
     * @static
    */
    public static function consultatrofeu($mes,$ano,$estab) {
        
        $con = new _Conexao();
        
        $sql = 'select * from tbtv_trofeu t where t.estabelecimento_id = :ESTAB and t.mes = :MES and t.ano = :ANO';
                
        $args = array(
            ':MES'   => $mes,
            ':ESTAB' => $estab,
            ':ANO'   => $ano
        ); 

        $ret = $con->query($sql, $args);
        
        return $ret;
        
    }
    
    /**
     * consulta todo os trofeis de uma fabrica durante um ano
     * @access public
     * @param int $ano
     * @param int $estab Estabelecimento
     * @param int $gp
     * @return string
     * @static
    */
    public static function consultatrofeuallgp($ano,$estab,$gp) {
        
        $con = new _Conexao();
        
        $sql = '
            select (1) TROFEU,t.mes from tbtv_trofeu t where t.estabelecimento_id = '.$estab.'
            and t.ano = '.$ano.' and t.gp_ido in ('.$gp.')
                union all
            select (2) TROFEU,t.mes from tbtv_trofeu t where t.estabelecimento_id = '.$estab.'
            and t.ano = '.$ano.' and t.gp_idp in ('.$gp.')
                union all
            select (3) TROFEU,t.mes from tbtv_trofeu t where t.estabelecimento_id = '.$estab.'
            and t.ano = '.$ano.' and t.gp_idb in ('.$gp.')
            ';

        $ret = $con->query($sql);
        
        return $ret;
        
    }
    
    /**
     * FunÃ§Ã£o para consultar dados do BSC
     * @access public
     * @param string $dados
     * @param string $gp fabricas separadas por virdula
     * @param int $dia se 1 trata semana mes dia, se 2 trada dia
     * @return string
     * @static
    */
    public static function consultabsc($dados,$gp,$dia,$data) {
        
        
        $con = new _Conexao();
                
        $id         = $gp;
        $estab      = $dados['estab'];
        
        if($dia == 1){ $tabela = 'TBTV_HISTORICO_BSC';}else{ $tabela = 'TBTV_HISTORICO_BSC_DIA';}
        
        $sql = '  
            select
            DATA_EXEC,
            DATA_CONSULTA,
            VALORSEMANAINDICADOR1,
            VALORSEMANAINDICADOR2,
            VALORSEMANAINDICADOR3,
            VALORSEMANAINDICADOR4_1,
            VALORSEMANAINDICADOR4_2,
            (select PERC from calc_perc(VALORSEMANAINDICADOR5,VALORSEMANAINDICADOR5_2)) VALORSEMANAINDICADOR5,
            (select PERC from calc_perc(VALORSEMANAINDICADOR6,VALORSEMANAINDICADOR6_2)) VALORSEMANAINDICADOR6,
            VALORSEMANAINDICADOR7,
            VALORSEMANAINDICADOR8,
            VALORSEMANAINDICADOR8_2,
            VALORSEMANAINDICADOR9,

            VALORMESINDICADOR1,
            VALORMESINDICADOR2,
            VALORMESINDICADOR3,
            VALORMESINDICADOR4_1,
            VALORMESINDICADOR4_2,
            (select PERC from calc_perc(VALORMESINDICADOR5,VALORMESINDICADOR5_2)) VALORMESINDICADOR5,
            (select PERC from calc_perc(VALORMESINDICADOR6,VALORMESINDICADOR6_2)) VALORMESINDICADOR6,
            VALORMESINDICADOR7,
            VALORMESINDICADOR8,
            VALORMESINDICADOR8_2,
            VALORMESINDICADOR9,

            VALORSEMESTREINDICADOR1,
            VALORSEMESTREINDICADOR2,
            VALORSEMESTREINDICADOR3,
            VALORSEMESTREINDICADOR4_1,
            VALORSEMESTREINDICADOR4_2,
            (select PERC from calc_perc(VALORSEMESTREINDICADOR5,VALORSEMESTREINDICADOR5_2)) VALORSEMESTREINDICADOR5,
            (select PERC from calc_perc(VALORSEMESTREINDICADOR6,VALORSEMESTREINDICADOR6_2)) VALORSEMESTREINDICADOR6,
            VALORSEMESTREINDICADOR7,
            VALORSEMESTREINDICADOR8,
            VALORSEMESTREINDICADOR8_2,
            VALORSEMESTREINDICADOR9,

            (select PERC from calc_med(EFICSEMANAA,VALORSEMANAINDICADOR1)) EFICSEMANAA,
            (select PERC from calc_med(EFICSEMANAB,VALORSEMANAINDICADOR1)) EFICSEMANAB,
            (select PERC from calc_med(PERDASEMANAA,VALORSEMANAINDICADOR1)) PERDASEMANAA,
            (select PERC from calc_med(PERDASEMANAB,VALORSEMANAINDICADOR1)) PERDASEMANAB,
            (select PERC from calc_med(EFICMEZA,VALORMESINDICADOR1)) EFICMEZA,
            (select PERC from calc_med(EFICMEZB,VALORMESINDICADOR1)) EFICMEZB,
            (select PERC from calc_med(PERDAMEZA,VALORMESINDICADOR1)) PERDAMEZA,
            (select PERC from calc_med(PERDAMEZB,VALORMESINDICADOR1)) PERDAMEZB,
            (select PERC from calc_med(EFICSEMESTREA,VALORSEMESTREINDICADOR1)) EFICSEMESTREA,
            (select PERC from calc_med(EFICSEMESTREB,VALORSEMESTREINDICADOR1)) EFICSEMESTREB,
            (select PERC from calc_med(PERDASEMESTREA,VALORSEMESTREINDICADOR1)) PERDASEMESTREA,
            (select PERC from calc_med(PERDASEMESTREB,VALORSEMESTREINDICADOR1)) PERDASEMESTREB,

            PREV_SEMANA,
            PREV_MES,
            PREV_SEMESTRE

            from(
            SELECT
            max(DATA_EXEC) DATA_EXEC,
            min(DATA_CONSULTA) DATA_CONSULTA,
            sum(VALORSEMANAINDICADOR1) VALORSEMANAINDICADOR1,
            sum(VALORSEMANAINDICADOR2) VALORSEMANAINDICADOR2,
            sum(VALORSEMANAINDICADOR3) VALORSEMANAINDICADOR3,
            sum(VALORSEMANAINDICADOR4_1) VALORSEMANAINDICADOR4_1,
            sum(VALORSEMANAINDICADOR4_2) VALORSEMANAINDICADOR4_2,
            sum(VALORSEMANAINDICADOR5) VALORSEMANAINDICADOR5,
            sum(VALORSEMANAINDICADOR6) VALORSEMANAINDICADOR6,
            sum(VALORSEMANAINDICADOR5_2) VALORSEMANAINDICADOR5_2,
            sum(VALORSEMANAINDICADOR6_2) VALORSEMANAINDICADOR6_2,
            sum(VALORSEMANAINDICADOR7) VALORSEMANAINDICADOR7,
            sum(VALORSEMANAINDICADOR8) VALORSEMANAINDICADOR8,
            sum(VALORSEMANAINDICADOR8_2) VALORSEMANAINDICADOR8_2,
            sum(VALORSEMANAINDICADOR9) VALORSEMANAINDICADOR9,

            sum(VALORMESINDICADOR1) VALORMESINDICADOR1,
            sum(VALORMESINDICADOR2) VALORMESINDICADOR2,
            sum(VALORMESINDICADOR3) VALORMESINDICADOR3,
            sum(VALORMESINDICADOR4_1) VALORMESINDICADOR4_1,
            sum(VALORMESINDICADOR4_2) VALORMESINDICADOR4_2,
            sum(VALORMESINDICADOR5) VALORMESINDICADOR5,
            sum(VALORMESINDICADOR6) VALORMESINDICADOR6,
            sum(VALORMESINDICADOR5_2) VALORMESINDICADOR5_2,
            sum(VALORMESINDICADOR6_2) VALORMESINDICADOR6_2,
            sum(VALORMESINDICADOR7) VALORMESINDICADOR7,
            sum(VALORMESINDICADOR8) VALORMESINDICADOR8,
            sum(VALORMESINDICADOR8_2) VALORMESINDICADOR8_2,
            sum(VALORMESINDICADOR9) VALORMESINDICADOR9,

            sum(VALORSEMESTREINDICADOR1) VALORSEMESTREINDICADOR1,
            sum(VALORSEMESTREINDICADOR2) VALORSEMESTREINDICADOR2,
            sum(VALORSEMESTREINDICADOR3) VALORSEMESTREINDICADOR3,
            sum(VALORSEMESTREINDICADOR4_1) VALORSEMESTREINDICADOR4_1,
            sum(VALORSEMESTREINDICADOR4_2) VALORSEMESTREINDICADOR4_2,
            sum(VALORSEMESTREINDICADOR5) VALORSEMESTREINDICADOR5,
            sum(VALORSEMESTREINDICADOR6) VALORSEMESTREINDICADOR6,
            sum(VALORSEMESTREINDICADOR5_2) VALORSEMESTREINDICADOR5_2,
            sum(VALORSEMESTREINDICADOR6_2) VALORSEMESTREINDICADOR6_2,
            sum(VALORSEMESTREINDICADOR7) VALORSEMESTREINDICADOR7,
            sum(VALORSEMESTREINDICADOR8) VALORSEMESTREINDICADOR8,
            sum(VALORSEMESTREINDICADOR8_2) VALORSEMESTREINDICADOR8_2,
            sum(VALORSEMESTREINDICADOR9) VALORSEMESTREINDICADOR9,

            sum(VALORSEMANAINDICADOR1 * EFICSEMANAA) EFICSEMANAA,
            sum(VALORSEMANAINDICADOR1 * EFICSEMANAB) EFICSEMANAB,
            sum(VALORSEMANAINDICADOR1 * PERDASEMANAA) PERDASEMANAA,
            sum(VALORSEMANAINDICADOR1 * PERDASEMANAB) PERDASEMANAB,
            sum(VALORMESINDICADOR1 * EFICMEZA) EFICMEZA,
            sum(VALORMESINDICADOR1 * EFICMEZB) EFICMEZB,
            sum(VALORMESINDICADOR1 * PERDAMEZA) PERDAMEZA,
            sum(VALORMESINDICADOR1 * PERDAMEZB) PERDAMEZB,
            sum(VALORSEMESTREINDICADOR1 * EFICSEMESTREA) EFICSEMESTREA,
            sum(VALORSEMESTREINDICADOR1 * EFICSEMESTREB) EFICSEMESTREB,
            sum(VALORSEMESTREINDICADOR1 * PERDASEMESTREA) PERDASEMESTREA,
            sum(VALORSEMESTREINDICADOR1 * PERDASEMESTREB) PERDASEMESTREB,

            sum(PREV_SEMANA) PREV_SEMANA,
            sum(PREV_MES) PREV_MES,
            sum(PREV_SEMESTRE) PREV_SEMESTRE

            FROM '.$tabela.' C WHERE C.ESTABELECIMENTO = :ESTAB AND C.GP_ID in ('.$id.') AND C.DATA_CONSULTA = :DATA)';
        
        $args = array(
            ':ESTAB' => $estab,
            ':DATA' => date('d.m.Y', strtotime($data))
        ); 

        $ret = $con->query($sql, $args);
 
        return $ret;
        
    }
    
    /**
     * FunÃ§Ã£o para consultar um INDICADOR
     * @access public
     * @param string $id
     * @return string
     * @static
    */
    public static function consultaIndicador($id) {
            
        $con = new _Conexao();
        
        $sql = '
                SELECT ID, GRUPO, DESCRICAO, PESO, TIPO, STATUS, PERFIL1_A, PERFIL1_B,
                PERFIL1_DESCRICAO, PERFIL2_A, PERFIL2_B, PERFIL2_DESCRICAO, PERFIL3_A,
                PERFIL3_B, PERFIL3_DESCRICAO
                FROM TBBSC_INDICADORES I
                WHERE I.ID = :INDICADOR_ID AND I.STATUS = 1;
                ';
        
        $args = array(
            ':INDICADOR_ID' => $id
        );

        $ret = $con->query($sql, $args);
 
        return $ret;
        
    }
    
    /**
     * lista em ordem as descriÃ§Ãµes e ids de um grupo de gps
     * @access public
     * @param string $id lista de ids dos grupos de produÃ§Ã£o
     * @return string
     * @static
    */
    public static function selectListGP($id) {
            
        $con = new _Conexao();
        
        $sql = '
                select list(z.descricao,\',\') as DESC,list(z.id,\',\') as COD
                from (select * from tbgp g where g.id in ('.$id.') order by g.descricao) z
                ';

        $ret = $con->query($sql);
 
        return $ret;
        
    }
    
    /**
     * FunÃ§Ã£o para consultar criteriros de um indicador
     * @access public
     * @param int $id ID do indicador
     * @param string $gp ID do grupo de produÃ§Ã£o
     * @return string
     * @static
    */
    public static function consultaFaixasIndicador($id,$gp) {
        
        $con = new _Conexao();
        
        $ccusto = self::listaCCUSTOGP($gp);
        
        if($ccusto != '' && $ccusto != '0' && $ccusto != null){

            $sql = '
                    SELECT COUNT(DESCRICAO),U.DESCRICAO,SUM(U.VALOR2)/COUNT(DESCRICAO) AS VALOR2,
                    SUM(U.VALOR)/COUNT(DESCRICAO) AS VALOR,U.REGISTRO_INDICADOR_ID,U.PLANACAO_STATUS FROM
                    (SELECT D.DESCRICAO,R.VALOR2,R.VALOR,0 AS REGISTRO_INDICADOR_ID,0 AS PLANACAO_STATUS FROM TBBSC_DETALHE D,
                    TBBSC_REGISTRO R,
                    (SELECT MAX(Z.ID) AS COD,Z.C_CUSTO FROM
                    (SELECT I.ID,I.C_CUSTO FROM TBBSC_REGISTRO_INDICADOR I WHERE I.C_CUSTO IN
                    ('.$ccusto.')AND I.BSC_ID = :INDICADOR_ID GROUP BY I.C_CUSTO,I.ID,I.BSC_ID) Z GROUP BY Z.C_CUSTO) Y
                    WHERE R.BSC_DETALHE_ID = D.ID
                    AND R.C_CUSTO = Y.C_CUSTO
                    AND R.REGISTRO_INDICADOR_ID = Y.COD
                    ORDER BY R.ID DESC) U
                    GROUP BY U.DESCRICAO,U.REGISTRO_INDICADOR_ID,U.PLANACAO_STATUS
                    ';
            
            $args = array(
                ':INDICADOR_ID' => $id
            );
            
            $ret = $con->query($sql, $args);
        }else{
            $ret = [];    
        }

        return $ret;
        
    }
    
    /**
     * funÃ§Ã£o que consulta as fabricas do grupo todas
     * @access public
     * @return string
     * @static
    */
    public static function selectTodasGPS() {
        
        $con = new _Conexao();
        
        $sql = 'select list(d.gp_id) as GP from tbagrupamento_gp_detalhe d where d.agrupamento_gp_id = 14';
        
        $res = $con->query($sql);
        
        $ret = '0';
        if(count($res) > 0){
            foreach ($res as $r) {
                $ret = $r->GP;
            }
        }

        return $ret;
        
    }
    
    
    /**
     * funÃ§Ã£o que consulta as fabricas do grupo todas
     * @access public
     * @return string
     * @static
    */
    public static function letreiro($id,$ccusto,$estba){
        
        $con = new _Conexao();
        
        $sql = 'select (REPLACE(list(trim(mensagem),\' ### \'),\'### \',\'\')) GP from (select g.mensagem
                from tbgp g where g.id in ('.$id.') union all
                select h.mensagem from tbgp_mensagem h where h.gp_id in ('.$id.')
                and h.datahora_inicio <= \''.date('d.m.y').'\' and h.datahora_fim >= \''.date('d.m.y').'\')';
        
        $res = $con->query($sql);
        
        $ret = '';
        
        foreach ($res as $r) {
            $ret = $r->GP;
        }

        return $ret;
        
    }
    
    /**
     * consulta data de produÃ§Ã£o por familia
     * @access public
     * @param int $familia
     * @return string
     * @static
    */
    public static function dataproducao($familia){
        
        $con = new _Conexao();
        
        $sql = 'Select Data_Producao From TbFamilia Where Codigo = :FAMILIA';
        
        $args = array(':FAMILIA' => $familia);
            
        $res = $con->query($sql,$args);
        
        $ret = '';
        
        foreach ($res as $r) {
            $ret = $r->DATA_PRODUCAO;
        }
        
        return $ret;
        
    }
    
    /**
     * consulta horariuos do ranking
     * @access public
     * @param [] $dados
     * @return string
     * @static
    */
    public static function horasRanking($dados){
        
        $con = new _Conexao();
        
        $estab  = $dados['estab'];
        
        $sql = 'select * from tbranking_horario o where o.estabelecimento_id = :ESTAB';
        
        $args = array(':ESTAB' => $estab);
            
        $res = $con->query($sql,$args);

        return $res;
        
    }
    
    /**
     * consulta descriÃ§Ã£o dos grupos de produÃ§Ã£o
     * @access public
     * @param int $id
     * @return string
     * @static
    */
    public static function descfabrica($id){
        
        $con = new _Conexao();
        
        $sql = 'SELECT LIST(X.DESCRICAO,\',\') AS DESC FROM (SELECT G.DESCRICAO FROM TBGP G WHERE G.ID IN ('.$id.') ORDER BY 1) X ';
            
        $res = $con->query($sql);
        
        $ret = '';
        
        foreach ($res as $r) {
            $ret = $r->DESC;
        }

        return $ret;
        
    }
    
    /**
     * Lista os centros de custos de uma fabrica 
     * @access public
     * @param string $gp ID do grupo de produÃ§Ã£o
     * @return string
     * @static
    */
    public static function listaCCUSTOGP($gp) {
       
        $con = new _Conexao();
        
        $sql = 'SELECT LIST(C.CODIGO,\',\') AS CCUSTO FROM TBCENTRO_DE_CUSTO C WHERE C.GP_ID in ('.$gp.')';

        $res = $con->query($sql);

        
        $ret = '0';
        
        foreach ($res as $r) {
            $ret = $r->CCUSTO;
        }

        return $ret;
        
    }
    
    /**
     * Lista horarios de troca do CEPO 
     * @access public
     * @param int $estab ID do estabelecimento
     * @return string
     * @static
    */
    public static function listaHCEPO($estab) {
       
        $con = new _Conexao();
        
        $sql = 'select lpad(extract(hour from c.hora),2,\'0\')||\':\'||lpad(extract(minute from c.hora),2,\'0\') as HORA, c.cor from tbhorario_cepo c where c.estabelecimento_id = '.$estab;
        
        $ret = $con->query($sql);
        
        return $ret;
        
    }
    
    /**
     * FunÃ§Ã£o para decodificar uma string 
     * @access public
     * @param string $string
     * @return string
     * @static
    */
    public static  function dcrypt($string)
    {
       $result = self::desmisture($string);
       $result = self::decodificar($result);
       
       return str_replace('&', '/',$result);
    }
    
    /**
     * FunÃ§Ã£o para ebaralhar uma string
     * @access public
     * @param string $string
     * @return string
     * @static
    */
    public static  function misture($string)
    {
       $result = str_split($string, 6);
       
       $prfx = array('1230','3120','0132','1023','0321','0123','0321');
       $chave = array_rand($prfx);
       if($chave == '0'){$chave = 1;}
            
       $pos1 = substr($prfx[$chave],0,1);
       $pos2 = substr($prfx[$chave],1,1);
       $pos3 = substr($prfx[$chave],2,1);
       $pos4 = substr($prfx[$chave],3,1);
            
       $str = $chave.$result[$pos1].$result[$pos2].$result[$pos3].$result[$pos4];
       
       for($i=4; $i<count($result); $i++) {
         $str = $str.$result[$i]; 
       }
       
       return $str;

    }
    
    /**
     * FunÃ§Ã£o para desembaralhar uma string
     * @access public
     * @param string $string
     * @return string
     * @static
    */
    public static  function desmisture($string)
    {
       $result = str_split(substr($string,1,-1).substr($string,-1), 6);
       $temp = $string;

       $prfx = array('1230','3120','0132','1023','0321','0123','0321');
       $chave = substr($temp,0,1);
            
       $pos1 = substr($prfx[$chave],0,1);
       $pos2 = substr($prfx[$chave],1,1);
       $pos3 = substr($prfx[$chave],2,1);
       $pos4 = substr($prfx[$chave],3,1);
            
       $str = $result[$pos1].$result[$pos2].$result[$pos3].$result[$pos4];
       
       for($i=4; $i<count($result); $i++) {
         $str = $str.$result[$i]; 
       }
       
       return $str;

    }

    /**
     * FunÃ§Ã£o para codificar uma string
     * @access public
     * @param string $str
     * @return string
     * @static
    */
    public static  function codificar($str) {
        $prfx = array('AFVaIF', 'Vc2ddS', 'ZEcad1', 'aOhlVq', 'QhFmVJ', 'VTaU5U',
                      'QRVuiZ', 'lZnhnU', 'Hi10X1', 'Gb9nUV', 'TnZGZz', 'ZGiZZG',
                      'dodJe5', 'dcl0NT', 'Y0NeZy', 'dGnlNj', 'ac5lOD', 'BqbWdo',
                      'bFp0Ma', 'QMFjNy', 'ZmFMdm', 'dkaIF1', 'hrMakD', 'aVFsbG',
                      'bsm0Mz', 'opqRWv', 'QVlRWP', 'PWRdyQ', 'PQVRsa', 'RTWPAG',
                      'pdtGSV', 'PLETFG', 'SQWEGA', 'PETUFJ', 'THRPAH', 'PLFCVM');

        for($i=0; $i<3; $i++) {
            $str = $prfx[array_rand($prfx)].strrev(base64_encode($str));
        }

        $str = strtr($str,
                     "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123567894=",
                     "pQzqxPtfGdonMweL3Z5vHS46_OliRcuKJ1Uk2a8rgBbj79yDVIWshNFCm0TXEAY");

        return $str;
    }
    
    /**
     * FunÃ§Ã£o para decodificar uma string
     * @access public
     * @param string $str
     * @return string
     * @static
    */
    public static  function decodificar($str) {

        $str = strtr($str,
                     "pQzqxPtfGdonMweL3Z5vHS46_OliRcuKJ1Uk2a8rgBbj79yDVIWshNFCm0TXEAY",
                     "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123567894=");

        for($i=0; $i<3; $i++) {
          $str = base64_decode(strrev(substr($str,6)));
        }

        return $str;

    }

    /**
     * FunÃ§Ã£o para separar uma string no delimitador &
     * @access public
     * @param array $param
     * @return string
     * @static
    */
    public static  function separar_parametros($param) {
      return explode('&',$param);
    }

    /**
     * FunÃ§Ã£o para separar uma string no delimitador &
     * @access public
     * @param array $opcao
     * @return string
     * @static
    */
    public static  function separar_valor($opcao) {
      return explode('=',$opcao);
    }

    /**
     * FunÃ§Ã£o para listar tamanhos de um produto 
     * @access public
     * @param Integer $id
     * @return array
     * @static
    */
    public static function listarTamanho($id)
    {
        try{
            
            $con = new _Conexao();
            
            $sql = 'select first 1 * from tbcliente';

            $args = array(':id' => $id);

            $retorno = $con->query($sql, $args);

            $resposta = array('0' => 'sucesso');
     
            $Ret =  array(
                'retorno'  => $retorno,
                'resposta' => $resposta
            );

        } catch(ValidationException $e1) {
            $Ret = array('resposta'	=> array('0' => 'erro', '1' => $e1->getMessage()));
        } catch(Exception $e2) {
            $Ret = array('resposta'	=> array('0' => 'erro', '1' => $e2->getMessage()));
        }

        return $Ret;

    }
        
}