<?php

namespace App\Models\DAO\Custo;

use App\Models\Conexao\_Conexao;
use PDO;
use Exception;
use PDOException;

/**
 * DAO do objeto _31010 - Custos Gerenciais
 */
class _31010DAO {

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
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function Consultar($filtro,$con) {
        
        $desc = strtoupper($filtro['FILTRO']);
        $mercado = $filtro['PARAN']['MERCADO'];

        if($desc != ''){
                $desc = 'and m.descricao like \'%'.str_replace(' ', '%', $desc).'%\'';
        }else{
            $desc = '';   
        }

        try {

            $sql = 'SELECT FIRST 50
                        *
                    FROM(
                        SELECT FIRST 50

                            lpad(m.codigo,6,0) as ID,
                            coalesce(21,-1) as tamanho,
                            coalesce(p.cor_id,-1)  as cor_id,
                            m.familia_codigo,
                            m.descricao || coalesce((select
                                \' (\'||

                                iif(m.t01 = 0,iif(m.t02 = 0,iif(m.t03 = 0,iif(m.t04 = 0,iif(m.t05 = 0,
                                iif(m.t06 = 0,iif(m.t07 = 0,iif(m.t08 = 0,iif(m.t09 = 0,iif(m.t10 = 0,
                                iif(m.t11 = 0,iif(m.t12 = 0,iif(m.t13 = 0,iif(m.t14 = 0,iif(m.t15 = 0,
                                iif(m.t16 = 0,iif(m.t17 = 0,iif(m.t18 = 0,iif(m.t19 = 0,iif(m.t20 = 0,
                                g.t20,\'\'),g.t19),g.t18),g.t17),g.t16),g.t15),g.t14),g.t16),g.t12),g.t11),
                                g.t10),g.t09),g.t08),g.t07),g.t06),g.t05),g.t04),g.t03),g.t02),g.t01)

                                ||\'-\'||

                                iif(m.t20 = 0,iif(m.t19 = 0,iif(m.t18 = 0,iif(m.t17 = 0,iif(m.t16 = 0,
                                iif(m.t15 = 0,iif(m.t14 = 0,iif(m.t13 = 0,iif(m.t12 = 0,iif(m.t11 = 0,
                                iif(m.t10 = 0,iif(m.t09 = 0,iif(m.t08 = 0,iif(m.t07 = 0,iif(m.t06 = 0,
                                iif(m.t05 = 0,iif(m.t04 = 0,iif(m.t03 = 0,iif(m.t02 = 0,iif(m.t01 = 0,
                                g.t01,\'\'),g.t02),g.t03),g.t04),g.t05),g.t06),g.t07),g.t08),g.t09),g.t10),
                                g.t11),g.t12),g.t13),g.t14),g.t15),g.t16),g.t17),g.t18),g.t19),g.t20)

                                ||\') \'

                            from tbgrade g where g.codigo = m.grade_codigo),\'\') as DESCRICAO

                        from tbmodelo m left join tbmodelo_padrao p on p.modelo_id = m.codigo
                        where m.status = 1
                        --and m.classificacao = \'A\'
                        --and (m.descricao like \'%A\' or m.descricao like \'%A XT\' or m.descricao like \'%A PS\')

                        '.$desc.'
                        and  m.familia_codigo = '.$mercado['FAMILIA_ID'].'
                        

                    ) A
                    order by A.descricao';

            $ret = $con->query($sql);

            $con->commit();
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Consultar Cor de um modelo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarCor($filtro,$con) {

        $desc   = strtoupper($filtro['FILTRO']);
        $paran  = $filtro['PARAN'];
        $padrao = $paran['PADRAO'];
        $modelo = $filtro['OPTIONS']['dados'];


        if($padrao == 1){
            $padrao = ' and c.codigo = '.$modelo['COR_ID'].' ';
        }else{
            $padrao = '';
        }

        if($desc != ''){
            if(!is_numeric($desc)){
                $desc = 'and c.descricao like \'%'.str_replace(' ', '%', $desc).'%\'';
            }else{
                $desc = 'and c.codigo = '.$desc;
            }
        }else{
            $desc = '';   
        }        

        try {

            $sql = 'SELECT
                        lpad(c.codigo,5,0) as ID,
                        c.descricao || iif(c.codigo = '.$modelo['COR_ID'].',\' ★ \',\'\')  as DESCRICAO
                    from tbmodelo_cor m, tbcor c
                    where c.status = 1
                    and m.modelo_id = '.$modelo['ID'].'
                    and m.cor_id = c.codigo
                    
                    '.$padrao.'
                    '.$desc.'

                    ';

            $ret = $con->query($sql);

            $con->commit();
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Consultar Mao de Obra de um modelo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarMaoDeObra($filtro,$con) {

        $modelo  = $filtro['MODELO'];
        $cor     = $filtro['COR'];
        $tamanho = $filtro['TAMANHO'];
        $data    = $filtro['DATA']; 
        $confg   = $filtro['CONFIGURACAO'];

        try {

            $sql = 'SELECT

                        ID2 as ID,
                        CCUSTO_DESCRICAO,
                        count(COLABORADOR) as COLABORADOR,
                        sum(MINUTOS) as MINUTOS,
                        sum(SALARIO) as SALARIO

                    from(
                        select
                            ID,
                            ID2,
                            COLABORADOR,
                            CCUSTO_DESCRICAO,
                            CARGO,
                            DATA_ADMISSAO,
                            DATA_DEMISSAO,
                            sum(MINUTOS) as MINUTOS,
                            sum(SALARIO) as SALARIO
                        from(
                            SELECT
                            lpad(c.codigo,6,0) as ID,
                            fn_mask(cc.codigo,\'CC\') as ID2,
                            c.pessoal_nome as COLABORADOR,
                            IIF(char_length(cc.codigo)=2,
                              (Select First 1 C.Descricao From TbCentro_de_Custo C Where C.CODIGO = SUBSTRING(cc.codigo FROM 1 FOR 2)),
                            IIF(char_length(cc.codigo)=5,
                              (Select First 1 C.Descricao From TbCentro_de_Custo C Where C.CODIGO = SUBSTRING(cc.codigo FROM 1 FOR 2))||\' - \'||
                              (Select First 1 C.Descricao From TbCentro_de_Custo C Where C.CODIGO = SUBSTRING(cc.codigo FROM 1 FOR 5)),
                            IIF(char_length(cc.codigo)=8,
                              (Select First 1 C.Descricao From TbCentro_de_Custo C Where C.CODIGO = SUBSTRING(cc.codigo FROM 1 FOR 5))||\' - \'||
                              (Select First 1 C.Descricao From TbCentro_de_Custo C Where C.CODIGO = SUBSTRING(cc.codigo FROM 1 FOR 8)),\'\'))) CCUSTO_DESCRICAO,
    
                            (Select First 1
                            (Select First 1 lpad(D.Codigo,4,0)||\' - \'||D.Descricao From TbCargo D Where CCC.cargo_codigo = D.Codigo)
                               From tbcolaborador_cargo CCC Where C.Codigo = CCC.Colaborador_codigo
                                and CCC.Data_Inicial <= FN_LASTDAY(Cast(\'01.\'||A.MES||\'.\'||A.ANO AS DATE))
                              Order By CCC.Data_Inicial Desc) Cargo,
    
                            C.Data_Admissao, C.Data_Demissao,
                            (13200.000  / '.$data['FATOR'].') as MINUTOS,
                            (Sum(VALOR) / '.$data['FATOR'].') as SALARIO
    
                            From VWFOLPAG A, TBCENTRO_DE_CUSTO CC, TBCOLABORADOR C
                            Where A.Estabelecimento_id = 1
                                and cast(\'01.\'||a.mes||\'.\'||a.ano as date) between \'01.'.$data['MES'].'.'.$data['ANO'].'\' and \'01.'.$data['MES2'].'.'.$data['ANO2'].'\'
                                and A.CCusto = CC.Codigo
                                and A.CCusto like \''.$confg['CCUSTO2'].'\' 
                                and CC.TIPO_CUSTO = 1
                                and c.codigo = a.colaborador_id
    
                            group by c.codigo,cc.codigo,c.pessoal_nome,cc.descricao,A.ANO,A.MES,C.Data_Admissao, C.Data_Demissao
                            order by cc.descricao
                        )  group by 1,2,3,4,5,6,7
                    )

                    group by 1,2';

                    

            $ret1 = $con->query($sql);

            $sql = 'SELECT

                        CARGO,
                        count(COLABORADOR) COLABORADOR,
                        sum(MINUTOS) as MINUTOS,
                        sum(SALARIO) as SALARIO

                    from(
                        select
                            ID,
                            ID2,
                            COLABORADOR,
                            CCUSTO_DESCRICAO,
                            CARGO,
                            DATA_ADMISSAO,
                            DATA_DEMISSAO,
                            sum(MINUTOS) as MINUTOS,
                            sum(SALARIO) as SALARIO
                        from(
                            SELECT
                            lpad(c.codigo,6,0) as ID,
                            fn_mask(cc.codigo,\'CC\') as ID2,
                            c.pessoal_nome as COLABORADOR,
                            IIF(char_length(cc.codigo)=2,
                              (Select First 1 C.Descricao From TbCentro_de_Custo C Where C.CODIGO = SUBSTRING(cc.codigo FROM 1 FOR 2)),
                            IIF(char_length(cc.codigo)=5,
                              (Select First 1 C.Descricao From TbCentro_de_Custo C Where C.CODIGO = SUBSTRING(cc.codigo FROM 1 FOR 2))||\' - \'||
                              (Select First 1 C.Descricao From TbCentro_de_Custo C Where C.CODIGO = SUBSTRING(cc.codigo FROM 1 FOR 5)),
                            IIF(char_length(cc.codigo)=8,
                              (Select First 1 C.Descricao From TbCentro_de_Custo C Where C.CODIGO = SUBSTRING(cc.codigo FROM 1 FOR 5))||\' - \'||
                              (Select First 1 C.Descricao From TbCentro_de_Custo C Where C.CODIGO = SUBSTRING(cc.codigo FROM 1 FOR 8)),\'\'))) CCUSTO_DESCRICAO,
    
                            (Select First 1
                            (Select First 1 lpad(D.Codigo,4,0)||\' - \'||D.Descricao From TbCargo D Where CCC.cargo_codigo = D.Codigo)
                               From tbcolaborador_cargo CCC Where C.Codigo = CCC.Colaborador_codigo
                                and CCC.Data_Inicial <= FN_LASTDAY(Cast(\'01.\'||A.MES||\'.\'||A.ANO AS DATE))
                              Order By CCC.Data_Inicial Desc) Cargo,
    
                            C.Data_Admissao, C.Data_Demissao,
                            (13200.000  / '.$data['FATOR'].') as MINUTOS,
                            (Sum(VALOR) / '.$data['FATOR'].') as SALARIO
    
                            From VWFOLPAG A, TBCENTRO_DE_CUSTO CC, TBCOLABORADOR C
                            Where A.Estabelecimento_id = 1
                                and cast(\'01.\'||a.mes||\'.\'||a.ano as date) between \'01.'.$data['MES'].'.'.$data['ANO'].'\' and \'01.'.$data['MES2'].'.'.$data['ANO2'].'\'
                                and A.CCusto = CC.Codigo
                                and A.CCusto like \''.$confg['CCUSTO2'].'\' 
                                and CC.TIPO_CUSTO = 1
                                and c.codigo = a.colaborador_id
    
                            group by c.codigo,cc.codigo,c.pessoal_nome,cc.descricao,A.ANO,A.MES,C.Data_Admissao, C.Data_Demissao
                            order by cc.descricao
                        )  group by 1,2,3,4,5,6,7
                    )

                    group by 1
                ';

            $ret2 = $con->query($sql);

            $sql = '
                    SELECT
                            ID,
                            ID2,
                            COLABORADOR,
                            CCUSTO_DESCRICAO,
                            CARGO,
                            DATA_ADMISSAO,
                            DATA_DEMISSAO,
                            sum(MINUTOS) as MINUTOS,
                            sum(SALARIO) as SALARIO
                        from(
                            SELECT
                            lpad(c.codigo,6,0) as ID,
                            fn_mask(cc.codigo,\'CC\') as ID2,
                            c.pessoal_nome as COLABORADOR,
                            IIF(char_length(cc.codigo)=2,
                              (Select First 1 C.Descricao From TbCentro_de_Custo C Where C.CODIGO = SUBSTRING(cc.codigo FROM 1 FOR 2)),
                            IIF(char_length(cc.codigo)=5,
                              (Select First 1 C.Descricao From TbCentro_de_Custo C Where C.CODIGO = SUBSTRING(cc.codigo FROM 1 FOR 2))||\' - \'||
                              (Select First 1 C.Descricao From TbCentro_de_Custo C Where C.CODIGO = SUBSTRING(cc.codigo FROM 1 FOR 5)),
                            IIF(char_length(cc.codigo)=8,
                              (Select First 1 C.Descricao From TbCentro_de_Custo C Where C.CODIGO = SUBSTRING(cc.codigo FROM 1 FOR 5))||\' - \'||
                              (Select First 1 C.Descricao From TbCentro_de_Custo C Where C.CODIGO = SUBSTRING(cc.codigo FROM 1 FOR 8)),\'\'))) CCUSTO_DESCRICAO,
    
                            (Select First 1
                            (Select First 1 lpad(D.Codigo,4,0)||\' - \'||D.Descricao From TbCargo D Where CCC.cargo_codigo = D.Codigo)
                               From tbcolaborador_cargo CCC Where C.Codigo = CCC.Colaborador_codigo
                                and CCC.Data_Inicial <= FN_LASTDAY(Cast(\'01.\'||A.MES||\'.\'||A.ANO AS DATE))
                              Order By CCC.Data_Inicial Desc) Cargo,
    
                            C.Data_Admissao, C.Data_Demissao,
                            (13200.000  / '.$data['FATOR'].') as MINUTOS,
                            (Sum(VALOR) / '.$data['FATOR'].') as SALARIO
    
                            From VWFOLPAG A, TBCENTRO_DE_CUSTO CC, TBCOLABORADOR C
                            Where A.Estabelecimento_id = 1
                                and cast(\'01.\'||a.mes||\'.\'||a.ano as date) between \'01.'.$data['MES'].'.'.$data['ANO'].'\' and \'01.'.$data['MES2'].'.'.$data['ANO2'].'\'
                                and A.CCusto = CC.Codigo
                                and A.CCusto like \''.$confg['CCUSTO2'].'\' 
                                and CC.TIPO_CUSTO = 1
                                and c.codigo = a.colaborador_id
    
                            group by c.codigo,cc.codigo,c.pessoal_nome,cc.descricao,A.ANO,A.MES,C.Data_Admissao, C.Data_Demissao
                            order by cc.descricao
                        )  group by 1,2,3,4,5,6,7';


            $ret3 = $con->query($sql);

            $con->commit();
            
            return ['G1' => $ret1, 'G2' => $ret2, 'G3' => $ret3];
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Consultar Mao de Obra de um modelo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarDetalheDespesa($filtro,$con) {

        $modelo  = $filtro['MODELO'];
        $cor     = $filtro['COR'];
        $tamanho = $filtro['TAMANHO'];
        $data    = $filtro['DATA']; 

        try {

            $sql = 'select
                        c.conta,
                        c.descricao,
                        (select sum(h.valor) / '.$data['FATOR'].' from tbdespesa_historico h where h.conta like c.conta||\'%\' and cast(\'01.\'||h.mes||\'.\'||h.ano as date) between \'01.'.$data['MES'].'.'.$data['ANO'].'\' and \'01.'.$data['MES2'].'.'.$data['ANO2'].'\') as VALOR
                    from tbcontacontabil c
                    where c.destacar_despesa = 1

                    group by 1,2';

            $ret = $con->query($sql);

            $con->commit();
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    
    /**
     * Consultar Mao de Obra de um modelo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarSimulacao($filtro,$con) {

        $desc = strtoupper($filtro['FILTRO']);

        if($desc != ''){
                $desc = 'and formatdatetime(s.data)||\' \'||s.ID||\' \'||s.DESCRICAO like \'%'.str_replace(' ', '%', $desc).'%\'';
        }else{
            $desc = '';   
        }

        try {

            $sql = 'SELECT first 50
                        formatdatetime(s.data) as DATA_HORA, 
                        s.*
                    from TBCUSTO_SIMULACAO s
                    where STATUS_EXCLUSAO = 0
                    '.$desc.'

                    order by 3,1
                ';

            $ret = $con->query($sql);

            $con->commit();
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Consultar Mao de Obra de um modelo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function Simulacao($filtro,$con) {

        $id  = $filtro['ID'];

        try {

            $sql = 'SELECT
                        *
                    from TBCUSTO_SIMULACAO s
                    where s.id = '.$id.'
                    AND STATUS_EXCLUSAO = 0

                    ';

            $ret1 = $con->query($sql);

            $sql = 'SELECT
                        *
                    from tbcusto_simulacao_item s
                    where s.SIMULACAO_ID = '.$id.'
                    AND STATUS_EXCLUSAO = 0

                    ';

            $ret2 = $con->query($sql);

            $con->commit();
            
            return ['PARAMETROS' => $ret1, 'ITENS' => $ret2];
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    
    /**
     * Excluir Simulacao
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function excluirSimulacao($filtro,$con) {

        try {

            $id = $filtro['ID']; 

            if($id > 0){
                $sql = 'UPDATE TBCUSTO_SIMULACAO SET STATUS_EXCLUSAO = 1 WHERE ID = ' . $id;

                $ret1 = $con->query($sql);

                $sql = 'UPDATE TBCUSTO_SIMULACAO_ITEM SET STATUS_EXCLUSAO = 1 WHERE SIMULACAO_ID = ' . $id;

                $ret2 = $con->query($sql);

                $con->commit();
                
                return $id;
            }else{
                log_erro('Erro ao excluir simulação (ID:'.$id.')');
            }
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }

    }

    /**
     * Gravar Simulacao
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function gravarSimulacao($filtro,$con) {

        try {

            if($filtro['ID'] > 0){
                $id = $filtro['ID']; 
            }else{
                $sql = 'SELECT
                             gen_id(gtbcusto_simulacao,1) as ID
                        from rdb$database';

                $ret = $con->query($sql);
                $id  = $ret[0]->ID;
            }

            if($id > 0){
                $sql = 'UPDATE OR INSERT INTO TBCUSTO_SIMULACAO ( ID,  DESCRICAO,  MERCADO,  MERCADO_ITENS,  TRANSPORTADORA,  CLIENTE,  CIDADE,  DATAS,  FRETES,  FATORES,  MARGEM)
                                                         VALUES (:ID, :DESCRICAO, :MERCADO, :MERCADO_ITENS, :TRANSPORTADORA, :CLIENTE, :CIDADE, :DATAS, :FRETES, :FATORES, :MARGEM)
                                                        MATCHING (ID);';

                $query = $con->pdo->prepare($sql);

                $query->bindValue(':ID'             , $id                         , PDO::PARAM_INT);
                $query->bindValue(':MARGEM'         , $filtro['MARGEM'          ]                 );
                $query->bindValue(':DESCRICAO'      , $filtro['DESCRICAO'       ] , PDO::PARAM_STR);
                $query->bindValue(':MERCADO'        , $filtro['MERCADO'         ] , PDO::PARAM_LOB);
                $query->bindValue(':MERCADO_ITENS'  , $filtro['MERCADO_ITENS'   ] , PDO::PARAM_LOB);
                $query->bindValue(':TRANSPORTADORA' , $filtro['TRANSPORTADORA'  ] , PDO::PARAM_LOB);
                $query->bindValue(':CLIENTE'        , $filtro['CLINETE'         ] , PDO::PARAM_LOB);
                $query->bindValue(':CIDADE'         , $filtro['CIDADE'          ] , PDO::PARAM_LOB);
                $query->bindValue(':DATAS'          , $filtro['DATAS'           ] , PDO::PARAM_LOB);
                $query->bindValue(':FRETES'         , $filtro['FRETE'           ] , PDO::PARAM_LOB);
                $query->bindValue(':FATORES'        , $filtro['FATORES'         ] , PDO::PARAM_LOB);
                $ret = $query->execute();

                $sql = 'UPDATE OR INSERT INTO TBCUSTO_SIMULACAO_ITEM ( SIMULACAO_ID,  VALOR)
                                                              VALUES (:SIMULACAO_ID, :VALOR)
                                                            MATCHING ( SIMULACAO_ID );';

                $query = $con->pdo->prepare($sql);

                $query->bindParam(':SIMULACAO_ID' , $id             , PDO::PARAM_INT);
                $query->bindParam(':VALOR'        , $filtro['ITENS'], PDO::PARAM_LOB);
                $ret = $query->execute();

                $con->commit();
                
                return $id;
            }else{
                log_erro('Erro ao gravar simulação (gen_id)');
            }
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }

    }
    

    /**
     * consultar incentivo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function consultarincentivo($filtro,$con) {

        $flag = strtoupper($filtro['FLAG']);

        try {

            $sql = 'select
                        ID,
                        DESCRICAO,
                        PERCENTUAL,
                        PERCENTUAL_IR
                    from tbcusto_incentivo i';

            $ret = $con->query($sql);

            $con->commit();
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Consulta os tipos de mercado
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function custoPadrao($filtro,$con) {

        $desc = strtoupper($filtro['FILTRO']);

        if($desc != ''){
                $desc = 'where c.ID||\' \'||c.DESCRICAO like \'%'.str_replace(' ', '%', $desc).'%\'';
        }else{
            $desc = '';   
        }

        try {

            $sql = "SELECT

                        ID,
                        FAMILIA_ID,
                        c.familia_id||' - '||coalesce((select f.descricao from tbfamilia f where f.codigo = c.familia_id),'') as FAMILIA_DESCRICAO,
                        DESCRICAO,
                        incentivo,
                        PERC_incentivo

                    FROM tbcusto_padrao C
                        
                        ".$desc."

                        order by 2,3

                    ";

            $ret = $con->query($sql);

            $con->commit();
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Consulta os itens do tipos de mercado
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function custoPadraoItem($filtro,$con) {

        $padrao = $filtro['PADRAO'];
        $id     = $padrao['ID'];

        try {

            $sql = 'SELECT
                        ID,
                        DESCRICAO,
                        PERCENTUAL,
                        FATOR,
                        AVOS,
                        USAR_FATOR,
                        EDITAVEL,
                        PADRAO_ID,
                        INCENTIVO,
                        FRETE,
                        MARGEM,
                        PERCENTUAL as OLD_FRETE,
                        0 as VALOR
                    FROM tbcusto_padrao_item C
                    where c.padrao_id = '. $id;

            $ret = $con->query($sql);

            $con->commit();
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Consultar Mao de Obra de um modelo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarDespesas($filtro,$con) {

        $modelo  = $filtro['MODELO'];
        $cor     = $filtro['COR'];
        $tamanho = $filtro['TAMANHO'];
        $data    = $filtro['DATA']; 
        $mercado = $filtro['MERCADO'];

        try {

            $sql = 'SELECT
                        ID,
                        DESC_TIPO,
                        DESCRICAO,
                        MASK,
                        GRUPO,
                        CCUSTO,
                        DESC_GRUPO,
                        REG,
                        VALOR,
                        cast((VALOR / iif(VALOR_CONTA > 0, VALOR_CONTA,VALOR)) as numeric(18,8)) as PERCENTUAL,
                        TIPO,
                        VALOR_CONTA as VALOR_DESPESA
                    from(
                        SELECT

                            ID,
                            DESC_TIPO,
                            coalesce(CONTA,\'C. Custo\') as DESCRICAO,

                            max(ccusto) as CCUSTO,
                            
                            fn_mask(ID,\'CONTA\') as MASK,
                            fn_mask(substring(ID from 1 for 7),\'CONTA\') as GRUPO,
                            coalesce((select first 1 c.descricao from tbcontacontabil c where c.conta = substring(l.ID from 1 for 7)),\'\') as DESC_GRUPO,
                            (count(CONTA) / '.$data['FATOR'].') as REG,
                            (sum(valor) / '.$data['FATOR'].') as VALOR,
                            (sum(percentual) / '.$data['FATOR'].') as percentual,
                            max(TIPO) as TIPO,

                            iif( DESC_TIPO = \'\',
                                coalesce((select sum(m.valor) from tbmovcontabil m where m.conta = l.ID and m.data between cast(\'01.'.$data['MES'].'.'.$data['ANO'].'\' as date) and fn_end_of_month(\'01.'.$data['MES2'].'.'.$data['ANO2'].'\')),0)
                                ,
                                coalesce((sum(valor) / 1),0)) as VALOR_CONTA

                        from(
                            select
                                iif(d.tipo = 4,\'PF\',\'\') as DESC_TIPO,
                                d.conta as ID,
                                (select j.descricao from tbcontacontabil j where j.conta = d.conta) as CONTA,
                                (select c.descricao from tbcentro_de_custo c where c.codigo = replace(d.ccusto,\'*\',\'\')) as CCUSTO,
                                d.valor,
                                d.percentual,
                                d.tipo,
                                coalesce((select c.id from tbcusto_padrao_item i, tbcusto_padrao_item_conta c
                                            where i.padrao_id =  '.$mercado['ID'].' and c.padrao_item_id = i.id and c.conta = d.conta),0) as FORA
                            
                            from tbdespesa_historico d
                            where cast(\'01.\'||d.mes||\'.\'||d.ano as date) between cast(\'01.'.$data['MES'].'.'.$data['ANO'].'\' as date) and \'01.'.$data['MES2'].'.'.$data['ANO2'].'\'
                            and d.valor <> 0
                            and d.conta <> \'\'

                        ) l where l.FORA = 0 group by 1,2,3

                        order by GRUPO
                    )';

            $ret1 = $con->query($sql);

            $sql = 'SELECT
                        DESCRICAO,
                        ID,
                        iif(PERCENTUAL is null, 100, PERCENTUAL * 100) as PERCENTUAL,
                        REG,
                        VALOR,
                        TIPO,
                        \'\' as DESC_TIPO
                    from(
                        SELECT
                            coalesce(CCUSTO,\'Contas\') as DESCRICAO,
                            ID as ID,
                            ((100 - (select first 1 h.perc_rateamento from tbrateamento_historico h where h.ano = '.$data['ANO'].' and h.mes = '.$data['MES'].' and replace(h.ccusto,\'*\',\'\') = k.ID)) / 100.000000) AS PERCENTUAL,
                            (count(CCUSTO) / '.$data['FATOR'].')  as REG,
                            (sum(valor) / '.$data['FATOR'].')  as VALOR,
                            (sum(percentual) / '.$data['FATOR'].')  as percentual1,
                            max(TIPO) as TIPO
                        from(
                            select
                                d.ccusto as ID,
                                (select j.descricao from tbcontacontabil j where j.conta = d.conta) as CONTA,
                                (select c.descricao from tbcentro_de_custo c where c.codigo = replace(d.ccusto,\'*\',\'\')) as CCUSTO,
                                d.valor,
                                d.percentual,
                                d.tipo,
                                coalesce((select c.id from tbcusto_padrao_item i, tbcusto_padrao_item_conta c
                                            where i.padrao_id =  '.$mercado['ID'].' and c.padrao_item_id = i.id and c.conta = d.conta),0) as FORA
                            
                            from tbdespesa_historico d
                            where cast(\'01.\'||d.mes||\'.\'||d.ano as date) between \'01.'.$data['MES'].'.'.$data['ANO'].'\' and \'01.'.$data['MES2'].'.'.$data['ANO2'].'\'
                            and d.valor <> 0
                            and d.conta <> \'\'
                        
                        ) k where k.FORA = 0  group by 1,2
                    )
                ';

            $ret2 = $con->query($sql);

            $con->commit();
            
            return ['G1' => $ret1, 'G2' => $ret2];
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Consultar Faturamentode uma Familia
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarPrecoVenda($filtro,$con) {

        $cor     = $filtro['COR']; 
        $modelo  = $filtro['MODELO'];
        $tamanho = $filtro['TAMANHO'];
        $data    = $filtro['DATA'];

        try {

            $sql = 'SELECT
                       coalesce(sum((i.valor + i.margem) * i.quantidade) / sum(i.quantidade),0) as VALOR_PRECO 
                    from tbpedido_item i
                    where i.modelo_codigo = '.$modelo['ID'].'
                    and i.tamanho in ('.$tamanho['LISTA'].')
                    and i.cor_id = '.$cor['ID'].'
                    and i.data_inclusao between fn_start_of_month(\'01.'.$data['MES'].'.'.$data['ANO'].'\') and fn_end_of_month(\'01.'.$data['MES2'].'.'.$data['ANO2'].'\')';
            
            $ret = $con->query($sql);
            
            if(count($ret) > 0){
                $ret = $ret[0]->VALOR_PRECO;
            }else{
                $ret = 0;
            }

            if($ret == 0){
                log_erro('Preço médio de vendas não encontrado no período');
            }

            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Consultar Faturamentode uma Familia
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function FaturamentoFamilia($filtro,$con) {

        $data    = $filtro['DATA']; 
        $mercado = $filtro['MERCADO'];

        try {

            $sql = 'SELECT

                    (FATURAMENTO_FAMILIA / FATURAMENTO_TOTAL) as PERC_FATURAMENTO

                from(
                    select
                    
                       sum(iif(g.familia_id = '.$mercado['FAMILIA_ID'].', g.faturamento,0)) as FATURAMENTO_FAMILIA,
                       sum(g.faturamento) as FATURAMENTO_TOTAL
                    
                    from tbfamilia_faturamento g
                    where cast(\'01.\'||g.mes||\'.\'||g.ano as date) between \'01.'.$data['MES'].'.'.$data['ANO'].'\' and \'01.'.$data['MES2'].'.'.$data['ANO2'].'\'
                )';

            $ret = $con->query($sql);

            if(count($ret) > 0){
                $ret = $ret[0]->PERC_FATURAMENTO;
            }else{
                $ret = 1;
            }
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Consultar Faturamentode uma Familia
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function consultarProduto($filtro,$con) {

        $desc    = strtoupper($filtro['FILTRO']);

        if($desc != ''){
                $desc = 'and p.descricao||\' \'||p.codigo like \'%'.str_replace(' ', '%', $desc).'%\'';
        }else{
            $desc = '';   
        }

        //and p.familia_codigo in (select c.familia_id from TBREGRA_CALCULO_CUSTO c, tbcusto_padrao j where c.familia_producao = j.familia_id and j.id = '.$mercado['ID'].')            

        try {

            $sql = 'SELECT first 50
                        p.codigo as ID,
                        p.descricao,
                        p.grade_codigo,
                        p.modelo_codigo as MODELO_ID,
                        coalesce((select first 1 j.tamanho from tbmodelo_padrao j where j.modelo_id = p.modelo_codigo),0) as TAMANHO
                    from tbproduto p
                    where p.status = \'1\'
                        
                        '.$desc.'

                    ';

            $ret = $con->query($sql);
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Consultar Faturamentode uma Familia
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function consultarDensidade($filtro,$con) {

        $densidade  = strtoupper($filtro['DENSIDADE']);
        $espessura  = strtoupper($filtro['ESPESSURA']); 
        $produto_id = strtoupper($filtro['PRODUTO_ID']);     

        try {

            $sql = "SELECT
                        PRODUTO_ID,
                        MODELO_ID,
                        MODELO_DESCRICAO,
                        PRODUTO_DESCRICAO,
                        DENSIDADE,
                        ESPESSURA,
                        Tipo
                        From (
                            Select P.Codigo Produto_Id,
                                   M.Codigo Modelo_Id, M.Descricao Modelo_Descricao, p.descricao as PRODUTO_DESCRICAO,
                                   Coalesce(
                                  (Select First 1 M2.Densidade
                                     From TbModelo_Consumo MCC, TbProduto P2, TbModelo M2
                                    Where P2.Modelo_Codigo = M2.Codigo
                                      and MCC.modelo_codigo = M.Codigo
                                      and MCC.consumo_produto_codigo = P2.Codigo
                                      and P2.Familia_Codigo = 74
                                      ),
                                  (Select First 1 M2.Densidade
                                     From TbModelo_Consumo MCC, TbProduto P2, TbModelo M2
                                    Where P2.Modelo_Codigo = M2.Codigo
                                      and MCC.modelo_codigo = M.Codigo
                                      and MCC.consumo_produto_codigo = P2.Codigo
                                      and P2.Familia_Codigo = 13
                                      )) Densidade,

                                   Coalesce(
                                  (Select First 1 M2.Espessura
                                     From TbModelo_Consumo MCC, TbProduto P2, TbModelo M2
                                    Where P2.Modelo_Codigo = M2.Codigo
                                      and MCC.modelo_codigo = M.Codigo
                                      and MCC.consumo_produto_codigo = P2.Codigo
                                      and P2.Familia_Codigo = 74
                                      ),
                                  (Select First 1 M2.Espessura
                                     From TbModelo_Consumo MCC, TbProduto P2, TbModelo M2
                                    Where P2.Modelo_Codigo = M2.Codigo
                                      and MCC.modelo_codigo = M.Codigo
                                      and MCC.consumo_produto_codigo = P2.Codigo
                                      and P2.Familia_Codigo = 13
                                      )) Espessura,

                                   Coalesce(
                                  (Select First 1 'DELFA'
                                     From TbModelo_Consumo MCC, TbProduto P2, TbModelo M2
                                    Where P2.Modelo_Codigo = M2.Codigo
                                      and MCC.modelo_codigo = M.Codigo
                                      and MCC.consumo_produto_codigo = P2.Codigo
                                      and P2.Familia_Codigo = 74
                                      ),
                                  (Select First 1 'SP'
                                     From TbModelo_Consumo MCC, TbProduto P2, TbModelo M2
                                    Where P2.Modelo_Codigo = M2.Codigo
                                      and MCC.modelo_codigo = M.Codigo
                                      and MCC.consumo_produto_codigo = P2.Codigo
                                      and P2.Familia_Codigo = 13
                                      )) Tipo

                            From TbProduto P , TbModelo M, tbproduto p3
                            Where P.Modelo_Codigo = M.Codigo
                              and p3.codigo = :PRODUTO_ID
                              And P.Status = '1'
                              and M.Status = '1'
                              and P.Familia_Codigo = p3.familia_codigo
                              and P.Cor_Codigo = p3.cor_codigo
                              and M.T13 = '1'
                        ) Where Densidade      = :DENSIDADE
                            and Espessura      = :ESPESSURA
                    ";

            $args = array(
                ':DENSIDADE'  => $densidade,
                ':ESPESSURA'  => $espessura,
                ':PRODUTO_ID' => $produto_id
            );

            $ret = $con->query($sql,$args);
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Consultar Detalhamento de despesas
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function DetalharDespesa($filtro,$con) {

        $item    = $filtro['ITEM'];
        $flag    = $filtro['FLAG'];
        $data    = $filtro['DATA'];
        $mercado = $filtro['MERCADO'];

        $tipo    = trim($item['DESC_TIPO'] . '') == 'PF' ? ' and tipo = 4' : 'and tipo <> 4';

        try {

            if($flag == 1){
                $sql = 'SELECT
                            ORIGEM,
                            DESCRICAO,
                            VALOR,
                            PERCENTUAL,
                            TIPO
                        from(
                            select
                                d.ccusto as ORIGEM, 
                                d.TIPO,                              
                                (select c.descricao from tbcentro_de_custo c where c.codigo = replace(d.ccusto,\'*\',\'\')) as DESCRICAO,
                                d.valor,
                                d.percentual,
                                coalesce((select first 1 c.id from tbcusto_padrao_item i, tbcusto_padrao_item_conta c
                                            where i.padrao_id = '.$mercado['ID'].' and c.padrao_item_id = i.id and c.conta = d.conta),0) as FORA
                            
                            from tbdespesa_historico d
                            where d.ano = '.$data['ANO'].'
                            and d.mes = '.$data['MES'].'
                            and d.valor <> 0
                            and d.conta = \''.$item['ID'].'\'
                            '.$tipo.'

                        ) where FORA = 0';
            }else{
                if($flag == 3){
                    $sql = 'SELECT
                                ORIGEM,
                                DESCRICAO,
                                VALOR,
                                PERCENTUAL,
                                TIPO
                            from(
                                select
                                    d.conta as ORIGEM,
                                    (select j.descricao from tbcontacontabil j where j.conta = d.conta) as DESCRICAO,
                                    d.valor,
                                    d.percentual,
                                    d.TIPO,
                                    coalesce((select first 1 c.id from tbcusto_padrao_item i, tbcusto_padrao_item_conta c
                                                where i.padrao_id = '.$mercado['ID'].' and c.padrao_item_id = i.id and c.conta = d.conta),0) as FORA
                                
                                from tbdespesa_historico d
                                where d.ano = '.$data['ANO'].'
                                and d.mes = '.$data['MES'].'
                                and d.valor <> 0
                                and d.ccusto = \''.$item['ID'].'\'
                                '.$tipo.'

                            ) where FORA = 0';
                }else{
                    if($flag == 4){
                        $sql = 'SELECT
                                    ORIGEM,
                                    (select j.descricao from tbcontacontabil j where j.conta = ORIGEM) as DESCRICAO,
                                    TIPO,
                                    sum(VALOR) as VALOR,
                                    sum(PERCENTUAL) as PERCENTUAL
                                from(
                                    select
                                        d.conta as ORIGEM,
                                        d.TIPO,
                                        d.valor,
                                        d.percentual,
                                        coalesce((select first 1 c.id from tbcusto_padrao_item i, tbcusto_padrao_item_conta c
                                                     where i.padrao_id = '.$mercado['ID'].' and c.padrao_item_id = i.id and c.conta = d.conta),0) as FORA
                                    
                                    from tbdespesa_historico d
                                    where d.ano = '.$data['ANO'].'
                                    and d.mes = '.$data['MES'].'
                                    and d.valor <> 0
                                    and d.ccusto = \'\'
                                    and d.conta <> \'\'
                                    '.$tipo.'

                                ) where FORA = 0

                                group by 1,2,3';
                    }else{
                        $sql = 'SELECT
                                
                                    ORIGEM,
                                    ORIGEM_DESCRICAO as DESCRICAO,
                                    VALOR,
                                    RATEAMENTO as PERCENTUAL,
                                    TIPO

                                from(

                                    SELECT

                                        PAI,
                                        ORIGEM,
                                        ORIGEM_TIPO,
                                        ORIGEM_DESCRICAO,
                                        FLAG,
                                        (RATEAMENTO2 * VALOR) as VALOR,
                                        TIPO,
                                        RATEAMENTO,
                                        ABRANGENCIA

                                    from(
                                        SELECT
                                            
                                            d.origem as PAI,
                                            replace(d.origem,\'*\',\'\') as origem,
                                            d.ORIGEM_TIPO,
                                            d.ORIGEM_DESCRICAO,
                                            0 as FLAG,
                                            sum(d.valor) as valor,
                                            MAX(d.tipo) AS TIPO,
                                            sum(d.rateamento) as rateamento,
                                            MAX(coalesce(h.abrangencia,-1)) as abrangencia,
                                            1 - (select first 1  j.perc_rateamento / 100.000 from tbrateamento_historico j where j.ano = '.$data['ANO'].' and j.mes = '.$data['MES'].' and (j.ccusto = \''.$item['ID'].'\' or j.ccusto = \''.$item['ID'].'*\')) rateamento2
                                            
                                        from vwrateamento_detalhe d left join tbrateamento_historico h on (h.ano = d.ano and h.mes = d.mes and h.ccusto = d.origem)

                                        where 1=1
                                        and d.grupo_rateamento = 1
                                        and replace(d.ccusto ,\'*\',\'\') = replace(\''.$item['ID'].'\',\'*\',\'\')
                                        and d.mes = '.$data['MES'].'
                                        and d.ano = '.$data['ANO'].'

                                        group by 1,2,3,4,5

                                    )

                                    union

                                    SELECT

                                        \'\' as PAI,
                                        d.conta as origem,
                                        \'CONTA\'  ORIGEM_TIPO,
                                        \'Salário\' ORIGEM_DESCRICAO,
                                        0 as FLAG,
                                        D.VALOR AS SALARIO,
                                        d.TIPO,
                                        D.percentual,
                                        -1 AS abrangencia

                                    from tbdespesa_historico d
                                    where d.conta = \'31100400001\'
                                    and d.ccusto = \''.$item['ID'].'\'
                                    and d.ano = '.$data['ANO'].'
                                    and d.mes = '.$data['MES'].'


                            ) order by abrangencia, ORIGEM_DESCRICAO';    
                    }   
                }   
            }

            $ret1 = $con->query($sql);

            $con->commit();
            
            return $ret1;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Consultar Tempo de um modelo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarTempo($filtro,$con) {

        $modelo  = $filtro['MODELO'];
        $cor     = $filtro['COR'];
        $tamanho = $filtro['TAMANHO']; 
        $tipo    = $filtro['TIPO']; 

        try {

            $sql = 'SELECT
                        FN_PROGRAMACAO_TEMPO_NOVO('.$modelo['ID'].', '.$cor['ID'].', '.$tamanho['ID'].','.$tipo.') as TEMPO
                    from RDB$DATABASE';

            $ret = $con->query($sql);

            $con->commit();

            if(count($ret) > 0){
                $ret = $ret[0]->TEMPO;
            }else{
                $ret = 0;
            }
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Consultar Cor de um modelo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarFicha2($filtro,$con) {
        
        $modelo  = $filtro['MODELO'];
        $cor     = $filtro['COR'];
        $tamanho = $filtro['TAMANHO'];
        $data    = $filtro['DATA'];
        $troca   = $filtro['TROCA'];
        $mercado = $filtro['MERCADO']; 

        $tamanhos = substr_count($tamanho['LISTA'],',') + 1;    

        try {

            $sql = 'SELECT

                        y.*,
                        coalesce((select first 1 e.FISCAL_PERCENTUAL_ICMS from tbnfe_item e
                                    where e.produto_codigo = y.PRODUTO_CONSUMO
                                     and e.VALOR_CUSTO > 0
                                     and e.data_entrada <= fn_end_of_month(\'01.'.$data['MES'].'.'.$data['ANO'].'\')

                                     ORDER BY DATA_ENTRADA DESC
                        ),0) as ICMS,

                        coalesce((select
                            sum(kk.valor) / y.FATOR2
                        from(
                        select
                            d.valor,
                            coalesce((select c.id from tbcusto_padrao_item i, tbcusto_padrao_item_conta c
                            where i.padrao_id = '.$mercado['ID'].' and c.padrao_item_id = i.id and c.conta = d.conta),0) as FORA
                        from tbdespesa_historico d
                        where cast(\'01.\'||d.mes||\'.\'||d.ano as date) between \'01.'.$data['MES'].'.'.$data['ANO'].'\' and \'01.'.$data['MES2'].'.'.$data['ANO2'].'\' and d.conta <> \'\'
                        ) kk where kk.FORA = 0),0) DESPESA,


                        p.unidademedida_sigla,

                        coalesce((select first 1
                                        coalesce((select TAM_DESCRICAO from SP_TAMANHO_GRADE(r.grade_codigo, y.TAMANHO_CONSUMO)),0)
                                     from tbmodelo r, tbproduto t where t.codigo = y.PRODUTO_CONSUMO and r.codigo = t.modelo_codigo),\'0\') as DESC_TAMANHO

                    from(
                        select
                            NIVEL,
                            PRODUTO_DESCRICAO,
                            PRODUTO_CONSUMO,
                            TAMANHO_CONSUMO,
                            ORIGEM,
                            '.$tamanho['ID'].' as TAMANHO,
                            FATOR,
                            FATOR2,

                            sum(TS)              / FATOR2 as  TS,
                            sum("TO")            / FATOR2 as "TO",
                            sum(CMS)             / FATOR2 as CMS,
                            sum(CMO)             / FATOR2 as CMO,
                            sum(CMOIP)           / FATOR2 as CMOIP,
                            sum(CMOIA)           / FATOR2 as CMOIA,
                            sum(CSMOIP)          / FATOR2 as CSMOIP,
                            sum(CSMOIA)          / FATOR2 as CSMOIA,
                            sum(PERDA)           / FATOR2 as PERDA,
                            sum(CONSUMO)         / FATOR2 as CONSUMO,
                            sum(C)               / FATOR2 as C,
                            sum(T)               / FATOR2 as T,
                            sum(CS)              / FATOR2 as CS,
                            sum(CO)              / FATOR2 as CO,
                            sum(COIP)            / FATOR2 as COIP,
                            sum(COIA)            / FATOR2 as COIA,
                            sum(CSIP)            / FATOR2 as CSIP,
                            sum(CSIA)            / FATOR2 as CSIA,
                            sum(CUSTO_MEDIO)     / FATOR2 as CUSTO_MEDIO,
                            sum(FATOR_CONVERSAO) / FATOR2 as FATOR_CONVERSAO,
                            sum(CUSTO_MINUTO)    / FATOR2 as CUSTO_MINUTO,
                            sum(MOI_PROPRIO)     / FATOR2 as MOI_PROPRIO,
                            sum(MOI_ABSORVIDO)   / FATOR2 as MOI_ABSORVIDOBSORVIDO

                        from(
                            select
                                NIVEL,
                                PRODUTO_DESCRICAO,
                                PRODUTO_CONSUMO,
                                max(TAMANHO_CONSUMO) as TAMANHO_CONSUMO,
                                ORIGEM,
                                list(TAMANHO) as TAMANHO,
                                FATOR,
                                '.$data['FATOR'].' as FATOR2,
                                ANO,
                                MES,

                                sum(TS)              / FATOR as  TS,
                                sum("TO")            / FATOR as "TO",
                                sum(CMS)             / FATOR as CMS,
                                sum(CMO)             / FATOR as CMO,
                                sum(CMOIP)           / FATOR as CMOIP,
                                sum(CMOIA)           / FATOR as CMOIA,
                                sum(CSMOIP)          / FATOR as CSMOIP,
                                sum(CSMOIA)          / FATOR as CSMOIA,
                                sum(PERDA)           / FATOR as PERDA,
                                avg(CONSUMO)                 as CONSUMO,
                                sum(C)               / FATOR as C,
                                sum(T)               / FATOR as T,
                                sum(CS)              / FATOR as CS,
                                sum(CO)              / FATOR as CO,
                                sum(COIP)            / FATOR as COIP,
                                sum(COIA)            / FATOR as COIA,
                                sum(CSIP)            / FATOR as CSIP,
                                sum(CSIA)            / FATOR as CSIA,
                                avg(CUSTO_MEDIO)             as CUSTO_MEDIO,
                                avg(FATOR_CONVERSAO)         as FATOR_CONVERSAO,
                                sum(CUSTO_MINUTO)    / FATOR as CUSTO_MINUTO,
                                sum(MOI_PROPRIO)     / FATOR as MOI_PROPRIO,
                                sum(MOI_ABSORVIDO)   / FATOR as MOI_ABSORVIDO
                            from(
                            select

                                NIVEL,
                                PRODUTO_DESCRICAO,
                                PRODUTO_CONSUMO,
                                TAMANHO_CONSUMO,
                                ORIGEM,
                                TAMANHO,
                                '.$tamanhos.' FATOR,
                                TS,
                                "TO",
                                CMS,
                                CMO,
                                CMOIP,
                                CMOIA,
                                CSMOIP,
                                CSMOIA,
                                PERDA,
                                CONSUMO,
                                C,
                                T,
                                CS,
                                CO,
                                COIP,
                                COIA,
                                CSIP,
                                CSIA,
                                CUSTO_MEDIO,
                                FATOR_CONVERSAO,
                                CUSTO_MINUTO,
                                MOI_PROPRIO,
                                MOI_ABSORVIDO,
                                D.ANO,D.MES
                            from (select ANO,MES,DATA1,DATA2, ITAMANHO,count(ANO) over() as FATOR from SPU_TABELA_DATAS('.$data['ANO'].','.$data['ANO2'].','.$data['MES'].','.$data['MES2'].'), (select OSPLIT as ITAMANHO from split(\''.$tamanho['LISTA'].'\',\',\')) A) D,
                            SPC_CALCULAR_CUSTO_V2(D.ANO,D.MES,'.$modelo['ID'].','.$cor['ID'].', D.ITAMANHO, \''.$troca.'\')

                            order by ANO,MES,NIVEL,PRODUTO_DESCRICAO,TAMANHO,TAMANHO_CONSUMO
                            ) group by
                                NIVEL,
                                PRODUTO_DESCRICAO,
                                PRODUTO_CONSUMO,
                                ORIGEM,
                                FATOR,
                                ANO,MES
                        )

                        group by
                            NIVEL,
                            PRODUTO_DESCRICAO,
                            PRODUTO_CONSUMO,
                            TAMANHO_CONSUMO,
                            ORIGEM,
                            FATOR,
                            FATOR2,
                            TAMANHO

                    ) y, tbproduto p
                    where p.codigo = y.PRODUTO_CONSUMO

                        ';

            $ret = $con->query($sql);
            $con->commit();
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Consultar Cor de um modelo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarFicha($filtro,$con) {

        $modelo  = $filtro['MODELO'];
        $cor     = $filtro['COR'];
        $tamanho = $filtro['TAMANHO'];
        $data    = $filtro['DATA'];   

        try {

            $sql = '
                SELECT
                    yy.*,
                    coalesce((select first 1 e.FISCAL_PERCENTUAL_ICMS from tbnfe_item e
                                where e.produto_codigo = yy.PRODUTO_CONSUMO_ID
                                 and e.VALOR_CUSTO > 0
                                 and e.data_entrada <= fn_end_of_month(\'01.'.$data['MES'].'.'.$data['ANO'].'\')

                                 ORDER BY DATA_ENTRADA DESC
                    ),0) as ICMS

                from( 
                    SELECT 
                        CONSUMO,
                        CUSTO,
                        TOTAL,
                        DESCRICAO,
                        UNIDADEMEDIDA_SIGLA,
                        NIVEL,
                        ORIGEM,
                        TAMANHO_PROD,
                        LISTA_TAMANHO,
                        MODELO_ID,
                        COR_ID,
                        PRODUTO_ID,
                        PRODUTO_CONSUMO_ID,
                        TAMANHO_CONSUMO_ID,
                        CLASSE,
                        FATOR_CONVERSAO,
                        SEQ_COMPOSICAO_CUSTO,

                        (sum(CUSTO_MEDIO)  /  FATOR ) as CUSTO_MEDIO,
                        (sum(CUSTO_MINUTO)  /  FATOR ) as CUSTO_MINUTO,
                        
                        (sum(TS00)  /  FATOR ) as TS00,
                        (sum(TS01)  /  FATOR ) as TS01,
                        (sum(TS02)  /  FATOR ) as TS02,
                        (sum(TS03)  /  FATOR ) as TS03,
                        (sum(TS04)  /  FATOR ) as TS04,
                        (sum(TS05)  /  FATOR ) as TS05,
                        (sum(TS06)  /  FATOR ) as TS06,
                        (sum(TS07)  /  FATOR ) as TS07,
                        (sum(TS08)  /  FATOR ) as TS08,
                        (sum(TS09)  /  FATOR ) as TS09,
                        (sum(TS10)  /  FATOR ) as TS10,
                        (sum(TS11)  /  FATOR ) as TS11,
                        (sum(TS12)  /  FATOR ) as TS12,
                        (sum(TS13)  /  FATOR ) as TS13,
                        (sum(TS14)  /  FATOR ) as TS14,
                        (sum(TS15)  /  FATOR ) as TS15,
                        (sum(TS16)  /  FATOR ) as TS16,
                        (sum(TS17)  /  FATOR ) as TS17,
                        (sum(TS18)  /  FATOR ) as TS18,
                        (sum(TS19)  /  FATOR ) as TS19,
                        (sum(TS20)  /  FATOR ) as TS20,
                        (sum(TO00)  /  FATOR ) as TO00,
                        (sum(TO01)  /  FATOR ) as TO01,
                        (sum(TO02)  /  FATOR ) as TO02,
                        (sum(TO03)  /  FATOR ) as TO03,
                        (sum(TO04)  /  FATOR ) as TO04,
                        (sum(TO05)  /  FATOR ) as TO05,
                        (sum(TO06)  /  FATOR ) as TO06,
                        (sum(TO07)  /  FATOR ) as TO07,
                        (sum(TO08)  /  FATOR ) as TO08,
                        (sum(TO09)  /  FATOR ) as TO09,
                        (sum(TO10)  /  FATOR ) as TO10,
                        (sum(TO11)  /  FATOR ) as TO11,
                        (sum(TO12)  /  FATOR ) as TO12,
                        (sum(TO13)  /  FATOR ) as TO13,
                        (sum(TO14)  /  FATOR ) as TO14,
                        (sum(TO15)  /  FATOR ) as TO15,
                        (sum(TO16)  /  FATOR ) as TO16,
                        (sum(TO17)  /  FATOR ) as TO17,
                        (sum(TO18)  /  FATOR ) as TO18,
                        (sum(TO19)  /  FATOR ) as TO19,
                        (sum(TO20)  /  FATOR ) as TO20,
                        (sum(CMS00)  /  FATOR ) as CMS00,
                        (sum(CMS01)  /  FATOR ) as CMS01,
                        (sum(CMS02)  /  FATOR ) as CMS02,
                        (sum(CMS03)  /  FATOR ) as CMS03,
                        (sum(CMS04)  /  FATOR ) as CMS04,
                        (sum(CMS05)  /  FATOR ) as CMS05,
                        (sum(CMS06)  /  FATOR ) as CMS06,
                        (sum(CMS07)  /  FATOR ) as CMS07,
                        (sum(CMS08)  /  FATOR ) as CMS08,
                        (sum(CMS09)  /  FATOR ) as CMS09,
                        (sum(CMS10)  /  FATOR ) as CMS10,
                        (sum(CMS11)  /  FATOR ) as CMS11,
                        (sum(CMS12)  /  FATOR ) as CMS12,
                        (sum(CMS13)  /  FATOR ) as CMS13,
                        (sum(CMS14)  /  FATOR ) as CMS14,
                        (sum(CMS15)  /  FATOR ) as CMS15,
                        (sum(CMS16)  /  FATOR ) as CMS16,
                        (sum(CMS17)  /  FATOR ) as CMS17,
                        (sum(CMS18)  /  FATOR ) as CMS18,
                        (sum(CMS19)  /  FATOR ) as CMS19,
                        (sum(CMS20)  /  FATOR ) as CMS20,
                        (sum(CMO00)  /  FATOR ) as CMO00,
                        (sum(CMO01)  /  FATOR ) as CMO01,
                        (sum(CMO02)  /  FATOR ) as CMO02,
                        (sum(CMO03)  /  FATOR ) as CMO03,
                        (sum(CMO04)  /  FATOR ) as CMO04,
                        (sum(CMO05)  /  FATOR ) as CMO05,
                        (sum(CMO06)  /  FATOR ) as CMO06,
                        (sum(CMO07)  /  FATOR ) as CMO07,
                        (sum(CMO08)  /  FATOR ) as CMO08,
                        (sum(CMO09)  /  FATOR ) as CMO09,
                        (sum(CMO10)  /  FATOR ) as CMO10,
                        (sum(CMO11)  /  FATOR ) as CMO11,
                        (sum(CMO12)  /  FATOR ) as CMO12,
                        (sum(CMO13)  /  FATOR ) as CMO13,
                        (sum(CMO14)  /  FATOR ) as CMO14,
                        (sum(CMO15)  /  FATOR ) as CMO15,
                        (sum(CMO16)  /  FATOR ) as CMO16,
                        (sum(CMO17)  /  FATOR ) as CMO17,
                        (sum(CMO18)  /  FATOR ) as CMO18,
                        (sum(CMO19)  /  FATOR ) as CMO19,
                        (sum(CMO20)  /  FATOR ) as CMO20,
                        (sum(T00)  /  FATOR ) as T00,
                        (sum(T01)  /  FATOR ) as T01,
                        (sum(T02)  /  FATOR ) as T02,
                        (sum(T03)  /  FATOR ) as T03,
                        (sum(T04)  /  FATOR ) as T04,
                        (sum(T05)  /  FATOR ) as T05,
                        (sum(T06)  /  FATOR ) as T06,
                        (sum(T07)  /  FATOR ) as T07,
                        (sum(T08)  /  FATOR ) as T08,
                        (sum(T09)  /  FATOR ) as T09,
                        (sum(T10)  /  FATOR ) as T10,
                        (sum(T11)  /  FATOR ) as T11,
                        (sum(T12)  /  FATOR ) as T12,
                        (sum(T13)  /  FATOR ) as T13,
                        (sum(T14)  /  FATOR ) as T14,
                        (sum(T15)  /  FATOR ) as T15,
                        (sum(T16)  /  FATOR ) as T16,
                        (sum(T17)  /  FATOR ) as T17,
                        (sum(T18)  /  FATOR ) as T18,
                        (sum(T19)  /  FATOR ) as T19,
                        (sum(T20)  /  FATOR ) as T20,
                        (sum(C00)  /  FATOR ) as C00,
                        (sum(C01)  /  FATOR ) as C01,
                        (sum(C02)  /  FATOR ) as C02,
                        (sum(C03)  /  FATOR ) as C03,
                        (sum(C04)  /  FATOR ) as C04,
                        (sum(C05)  /  FATOR ) as C05,
                        (sum(C06)  /  FATOR ) as C06,
                        (sum(C07)  /  FATOR ) as C07,
                        (sum(C08)  /  FATOR ) as C08,
                        (sum(C09)  /  FATOR ) as C09,
                        (sum(C10)  /  FATOR ) as C10,
                        (sum(C11)  /  FATOR ) as C11,
                        (sum(C12)  /  FATOR ) as C12,
                        (sum(C13)  /  FATOR ) as C13,
                        (sum(C14)  /  FATOR ) as C14,
                        (sum(C15)  /  FATOR ) as C15,
                        (sum(C16)  /  FATOR ) as C16,
                        (sum(C17)  /  FATOR ) as C17,
                        (sum(C18)  /  FATOR ) as C18,
                        (sum(C19)  /  FATOR ) as C19,
                        (sum(C20)  /  FATOR ) as C20,
                        (sum(CS00)  /  FATOR ) as CS00,
                        (sum(CO00)  /  FATOR ) as CO00,
                        (sum(CS01)  /  FATOR ) as CS01,
                        (sum(CO01)  /  FATOR ) as CO01,
                        (sum(CS02)  /  FATOR ) as CS02,
                        (sum(CO02)  /  FATOR ) as CO02,
                        (sum(CS03)  /  FATOR ) as CS03,
                        (sum(CO03)  /  FATOR ) as CO03,
                        (sum(CS04)  /  FATOR ) as CS04,
                        (sum(CO04)  /  FATOR ) as CO04,
                        (sum(CS05)  /  FATOR ) as CS05,
                        (sum(CO05)  /  FATOR ) as CO05,
                        (sum(CS06)  /  FATOR ) as CS06,
                        (sum(CO06)  /  FATOR ) as CO06,
                        (sum(CS07)  /  FATOR ) as CS07,
                        (sum(CO07)  /  FATOR ) as CO07,
                        (sum(CS08)  /  FATOR ) as CS08,
                        (sum(CO08)  /  FATOR ) as CO08,
                        (sum(CS09)  /  FATOR ) as CS09,
                        (sum(CO09)  /  FATOR ) as CO09,
                        (sum(CS10)  /  FATOR ) as CS10,
                        (sum(CO10)  /  FATOR ) as CO10,
                        (sum(CS11)  /  FATOR ) as CS11,
                        (sum(CO11)  /  FATOR ) as CO11,
                        (sum(CS12)  /  FATOR ) as CS12,
                        (sum(CO12)  /  FATOR ) as CO12,
                        (sum(CS13)  /  FATOR ) as CS13,
                        (sum(CO13)  /  FATOR ) as CO13,
                        (sum(CS14)  /  FATOR ) as CS14,
                        (sum(CO14)  /  FATOR ) as CO14,
                        (sum(CS15)  /  FATOR ) as CS15,
                        (sum(CO15)  /  FATOR ) as CO15,
                        (sum(CS16)  /  FATOR ) as CS16,
                        (sum(CO16)  /  FATOR ) as CO16,
                        (sum(CS17)  /  FATOR ) as CS17,
                        (sum(CO17)  /  FATOR ) as CO17,
                        (sum(CS18)  /  FATOR ) as CS18,
                        (sum(CO18)  /  FATOR ) as CO18,
                        (sum(CS19)  /  FATOR ) as CS19,
                        (sum(CO19)  /  FATOR ) as CO19,
                        (sum(CS20)  /  FATOR ) as CS20,
                        (sum(CO20)  /  FATOR ) as CO20,
                        (sum(COMOIP00)  /  FATOR ) as COMOIP00,
                        (sum(COMOIP01)  /  FATOR ) as COMOIP01,
                        (sum(COMOIP02)  /  FATOR ) as COMOIP02,
                        (sum(COMOIP03)  /  FATOR ) as COMOIP03,
                        (sum(COMOIP04)  /  FATOR ) as COMOIP04,
                        (sum(COMOIP05)  /  FATOR ) as COMOIP05,
                        (sum(COMOIP06)  /  FATOR ) as COMOIP06,
                        (sum(COMOIP07)  /  FATOR ) as COMOIP07,
                        (sum(COMOIP08)  /  FATOR ) as COMOIP08,
                        (sum(COMOIP09)  /  FATOR ) as COMOIP09,
                        (sum(COMOIP10)  /  FATOR ) as COMOIP10,
                        (sum(COMOIP11)  /  FATOR ) as COMOIP11,
                        (sum(COMOIP12)  /  FATOR ) as COMOIP12,
                        (sum(COMOIP13)  /  FATOR ) as COMOIP13,
                        (sum(COMOIP14)  /  FATOR ) as COMOIP14,
                        (sum(COMOIP15)  /  FATOR ) as COMOIP15,
                        (sum(COMOIP16)  /  FATOR ) as COMOIP16,
                        (sum(COMOIP17)  /  FATOR ) as COMOIP17,
                        (sum(COMOIP18)  /  FATOR ) as COMOIP18,
                        (sum(COMOIP19)  /  FATOR ) as COMOIP19,
                        (sum(COMOIP20)  /  FATOR ) as COMOIP20,
                        (sum(COMOIA00)  /  FATOR ) as COMOIA00,
                        (sum(COMOIA01)  /  FATOR ) as COMOIA01,
                        (sum(COMOIA02)  /  FATOR ) as COMOIA02,
                        (sum(COMOIA03)  /  FATOR ) as COMOIA03,
                        (sum(COMOIA04)  /  FATOR ) as COMOIA04,
                        (sum(COMOIA05)  /  FATOR ) as COMOIA05,
                        (sum(COMOIA06)  /  FATOR ) as COMOIA06,
                        (sum(COMOIA07)  /  FATOR ) as COMOIA07,
                        (sum(COMOIA08)  /  FATOR ) as COMOIA08,
                        (sum(COMOIA09)  /  FATOR ) as COMOIA09,
                        (sum(COMOIA10)  /  FATOR ) as COMOIA10,
                        (sum(COMOIA11)  /  FATOR ) as COMOIA11,
                        (sum(COMOIA12)  /  FATOR ) as COMOIA12,
                        (sum(COMOIA13)  /  FATOR ) as COMOIA13,
                        (sum(COMOIA14)  /  FATOR ) as COMOIA14,
                        (sum(COMOIA15)  /  FATOR ) as COMOIA15,
                        (sum(COMOIA16)  /  FATOR ) as COMOIA16,
                        (sum(COMOIA17)  /  FATOR ) as COMOIA17,
                        (sum(COMOIA18)  /  FATOR ) as COMOIA18,
                        (sum(COMOIA19)  /  FATOR ) as COMOIA19,
                        (sum(COMOIA20)  /  FATOR ) as COMOIA20
                    from(
                        SELECT
                            '.$data['FATOR'].' as FATOR,
                            0 as CONSUMO,
                            0 as CUSTO,
                            0 as TOTAL,
                            p.descricao,
                            p.unidademedida_sigla,
                            
                            a.*
                        
                        
                        from
                            SPC_CONSUMO_MODELO3('.$modelo['ID'].', '.$cor['ID'].', '.$tamanho['ID'].','.$data['ANO'].','.$data['MES'].','.$data['ANO2'].','.$data['MES2'].') A, tbproduto p
                        where p.codigo = a.produto_consumo_id
                    ) kk group by

                        CONSUMO,
                        CUSTO,
                        TOTAL,
                        DESCRICAO,
                        UNIDADEMEDIDA_SIGLA,
                        NIVEL,
                        ORIGEM,
                        TAMANHO_PROD,
                        LISTA_TAMANHO,
                        MODELO_ID,
                        COR_ID,
                        PRODUTO_ID,
                        PRODUTO_CONSUMO_ID,
                        TAMANHO_CONSUMO_ID,
                        CLASSE,
                        FATOR_CONVERSAO,
                        SEQ_COMPOSICAO_CUSTO,
                        FATOR
                ) yy

                ';

            $ret = $con->query($sql);

            $con->commit();
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Consultar Detalhamento Absorcao
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarAbsorcao($filtro,$con) {

        $data = $filtro['DATA'];

        $configuracao = $filtro['CONFIGURACAO'];

        $ccusto = $configuracao['CCUSTO2'];

        $tipo = 1;

        if(array_key_exists('ITEM', $filtro)){
            $ccusto = $filtro['ITEM']['PAI'];
            $tipo = 2;
        }

        try {

            if($tipo == 1){
                $sql = 'SELECT
                        
                        d.origem as PAI,
                        replace(d.origem,\'*\',\'\') as origem,
                        d.ORIGEM_TIPO,
                        d.ORIGEM_DESCRICAO,
                        0 as FLAG,
                        (sum(d.valor) / '.$data['FATOR'].') as valor,
                        MAX(d.tipo) AS TIPO,
                        (sum(d.rateamento) / '.$data['FATOR'].') as rateamento,
                        MAX(h.abrangencia) as abrangencia

                    from vwrateamento_detalhe d, tbrateamento_historico h

                    where h.ano = d.ano
                    and h.mes = d.mes
                    and h.ccusto = d.origem
                    and d.ccusto like \''.$ccusto.'\'
                    and char_length(d.ccusto) > 7
                    and d.grupo_rateamento = 1
                    and cast(\'01.\'||d.mes||\'.\'||d.ano as date) between cast(\'01.'.$data['MES'].'.'.$data['ANO'].'\' as date) and \'01.'.$data['MES2'].'.'.$data['ANO2'].'\'
                    and d.origem_tipo = \'C. CUSTO\'

                    group by 1,2,3,4,5

                    order by abrangencia';
            }

            if($tipo == 2){

                    $sql = '
                    SELECT

                        *

                    from(

                        SELECT

                            PAI,
                            ORIGEM,
                            ORIGEM_TIPO,
                            ORIGEM_DESCRICAO,
                            FLAG,
                            (RATEAMENTO2 * VALOR) as VALOR,
                            TIPO,
                            RATEAMENTO,
                            ABRANGENCIA

                        from(
                            SELECT
                                
                                d.origem as PAI,
                                replace(d.origem,\'*\',\'\') as origem,
                                d.ORIGEM_TIPO,
                                d.ORIGEM_DESCRICAO,
                                0 as FLAG,
                                (sum(d.valor) / '.$data['FATOR'].') as valor,
                                MAX(d.tipo) AS TIPO,
                                (sum(d.rateamento) / '.$data['FATOR'].') as rateamento,
                                MAX(coalesce(h.abrangencia,-1)) as abrangencia,
                                (select first 1  j.perc_rateamento / 100.000 from tbrateamento_historico j where j.ano = '.$data['ANO'].' and j.mes = '.$data['MES'].' and j.ccusto = \''.$ccusto.'\') rateamento2
                                
                            from vwrateamento_detalhe d left join tbrateamento_historico h on (h.ano = d.ano and h.mes = d.mes and h.ccusto = d.origem)

                            where 1=1
                            and d.grupo_rateamento = 1
                            and replace(d.ccusto ,\'*\',\'\') = replace(\''.$ccusto.'\',\'*\',\'\')
                            and cast(\'01.\'||d.mes||\'.\'||d.ano as date) between cast(\'01.'.$data['MES'].'.'.$data['ANO'].'\' as date) and \'01.'.$data['MES2'].'.'.$data['ANO2'].'\'

                            group by 1,2,3,4,5

                        )

                        union

                        SELECT
                            PAI,
                            ORIGEM,
                            ORIGEM_TIPO,
                            ORIGEM_DESCRICAO,
                            FLAG,
                            (RATEAMENTO * SALARIO) as VALOR,
                            TIPO,
                            RATEAMENTO,
                            ABRANGENCIA
                        from
                        (
                            SELECT

                                \'\' as PAI,
                                \'31100400001\' as origem,
                                \'CONTA\'  ORIGEM_TIPO,
                                \'Salário\' ORIGEM_DESCRICAO,
                                0 as FLAG,
                                (sum(SALARIO) / '.$data['FATOR'].') as SALARIO,
                                1 TIPO,
                                (select first 1  ((sum(j.perc_rateamento) / '.$data['FATOR'].') / 100.000) from tbrateamento_historico j where cast(\'01.\'||j.mes||\'.\'||j.ano as date) between \'01.'.$data['MES'].'.'.$data['ANO'].'\' and \'01.'.$data['MES2'].'.'.$data['ANO2'].'\' and j.ccusto = \''.$ccusto.'\') rateamento,
                                -1 abrangencia

                            from SPC_SALARIO_CCUSTO2('.$data['ANO'].', '.$data['MES'].','.$data['ANO2'].', '.$data['MES2'].', 1, \''.$ccusto.'\')
                        )

                ) order by abrangencia, ORIGEM_DESCRICAO
                
                ';
            }



            $ret = $con->query($sql);

            $con->commit();
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }


    /**
     * Consultar Detalhamento Absorcao Proprio
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarEstacoes($filtro,$con) {

        $data = $filtro['DATA']; 
        $conf = $filtro['CONF']; 

        try {

            $sql = 'SELECT

                        ESTACOES,
                        DIAS,
                        (MINUTOS / '.$data['FATOR'].') as FATOR,
                        (MINUTOS_DIA / '.$data['FATOR'].') as MINUTOS_DIA,
                        (MINUTOS_NOITE / '.$data['FATOR'].') as MINUTOS_NOITE,
                        (TOTAL_MINUTOS_DIA / '.$data['FATOR'].') as TOTAL_MINUTOS_DIA,
                        (TOTAL_MINUTOS_NOITE / '.$data['FATOR'].') as TOTAL_MINUTOS_NOITE

                    from
                        SPC_MINUTOS_DISPONIVEIS2('.$conf['FAMILIA_ID'].', \'*'.$conf['GP_ID'].'\', \''.$conf['PERFIL_UP'].'\', '.$data['MES'].', '.$data['ANO'].', '.$data['MES2'].', '.$data['ANO2'].')
                    ';
            
            $ret = $con->query($sql);

            $con->commit();

            if(count($ret ) > 0){
                $ret = $ret[0]; 
            }
            
        } catch (Exception $e) {
            $con->rollback();
            $ret = 0;
        }

        return $ret;
    }

    /**
     * Consultar Configuracoes de um modelo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarConfiguracao($filtro,$con) {

        $data = $filtro['DATA']; 
        $modelo  = $filtro['MODELO'];

        try {

            $sql = 'SELECT

                        c.ID,
                        c.FAMILIA_PRODUCAO,
                        c.SEQUENCIA,
                        c.FAMILIA_ID,
                        c.GP_ID,
                        c.PERFIL_UP,
                        c.UP_PADRAO1,
                        c.UP_PADRAO2,
                        c.CALCULO_REBOBINAMENTO,
                        c.CALCULO_CONFORMACAO,
                        c.CCUSTO,
                        replace(c.CCUSTO,\'*\',\'%\') as CCUSTO2,
                        c.FATOR,
                        c.STATUS,
                        c.REMESSAS_DEFEITO

                    from TBREGRA_CALCULO_CUSTO c, tbmodelo m
                    where m.familia_codigo = c.familia_id
                    and m.codigo = '.$modelo['ID'].'';
            
            $ret = $con->query($sql);

            $con->commit();

            if(count($ret ) > 0){
                $ret = $ret[0]; 
            }
            
        } catch (Exception $e) {
            $con->rollback();
            $ret = 0;
        }

        return $ret;
    }

    /**
     * Consultar Detalhamento Absorcao Proprio
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarProprio($filtro,$con) {

        $data = $filtro['DATA']; 
        $configuracao = $filtro['CONFIGURACAO'];

        $ccusto = $configuracao['CCUSTO2'];///'210%';

        try {


            $sql = 'SELECT

                        *

                    from
                        (SELECT
                                            
                            d.origem as PAI,
                            replace(d.origem,\'*\',\'\') as origem,
                            d.ORIGEM_TIPO,
                            d.ORIGEM_DESCRICAO,
                            (sum(d.valor) / '.$data['FATOR'].') as valor,
                            MAX(d.tipo) AS TIPO,
                            (sum(d.rateamento) / '.$data['FATOR'].') as rateamento
                        
                        from vwrateamento_detalhe d
                        where d.ccusto like \''.$ccusto.'\'
                        and char_length(d.ccusto) > 7
                        and d.grupo_rateamento = 1
                        and cast(\'01.\'||d.mes||\'.\'||d.ano as date) between cast(\'01.'.$data['MES'].'.'.$data['ANO'].'\' as date) and \'01.'.$data['MES2'].'.'.$data['ANO2'].'\'
                        and d.origem_tipo = \'CONTA\'
                        
                        group by 1,2,3,4
                        
                        union
                        
                        Select
                            \'\' as PAI,
                            \'31100400001\' as ORIGEM,
                            \'CONTA\' as ORIGEM_TIPO,
                            \'Salário\' as ORIGEM_DESCRICAO,
                            (Sum(VALOR) / '.$data['FATOR'].') as VALOR,
                            2 as TIPO,
                            1 as rateamento
                            From VWFOLPAG A, TBCENTRO_DE_CUSTO CC
                        Where A.Estabelecimento_id = 1
                        and cast(\'01.\'||a.mes||\'.\'||a.ano as date) between cast(\'01.'.$data['MES'].'.'.$data['ANO'].'\' as date) and \'01.'.$data['MES2'].'.'.$data['ANO2'].'\'
                        and A.CCusto = CC.Codigo
                        and A.CCusto like \''.$ccusto.'\'
                        and CC.TIPO_CUSTO = 2
                        
                        group by 1,2,3,4)

                    order by ORIGEM_DESCRICAO';
            
            $ret = $con->query($sql);

            $con->commit();
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Consultar Cor de um modelo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarFichaTempo($filtro,$con) {

        $modelo  = $filtro['MODELO'];
        $cor     = $filtro['COR'];
        $tamanho = $filtro['TAMANHO'];
        $data    = $filtro['DATA'];    
        $mercado = $filtro['MERCADO']; 

        try {

            $sql = 'SELECT
                        y.*,

                        coalesce((select
                            sum(kk.valor) / FATOR2
                        from(
                        select
                            d.valor,
                            coalesce((select c.id from tbcusto_padrao_item i, tbcusto_padrao_item_conta c
                            where i.padrao_id = '.$mercado['ID'].' and c.padrao_item_id = i.id and c.conta = d.conta),0) as FORA
                        from tbdespesa_historico d
                        where cast(\'01.\'||d.mes||\'.\'||d.ano as date) between \'01.'.$data['MES'].'.'.$data['ANO'].'\' and \'01.'.$data['MES2'].'.'.$data['ANO2'].'\' and d.conta <> \'\'
                        ) kk where kk.FORA = 0),0) DESPESA

                    from(
                    SELECT
                        Codigo        AS PRODUTO_ID, 
                        Descricao     AS DESCRICAO,
                        Grade_Codigo  AS GRADE_ID,
                        MODELO_CODIGO AS MODELO_ID, 
                        COR_CODIGO    AS COR_ID,
                        unidademedida_sigla,
                        FATOR1,
                        FATOR2,

                        COALESCE(SUM(PERCENTUAL_PERDA) / FATOR1,0) as PERCENTUAL_PERDA,

                        sum(COALESCE(TO00,0)) / FATOR1 as TO00, SUM(COALESCE(TO01,0)) / FATOR1 as TO01, SUM(COALESCE(TO02,0)) / FATOR1 as TO02,
                        SUM(COALESCE(TO03,0)) / FATOR1 as TO03, SUM(COALESCE(TO04,0)) / FATOR1 as TO04, SUM(COALESCE(TO05,0)) / FATOR1 as TO05,
                        SUM(COALESCE(TO06,0)) / FATOR1 as TO06, SUM(COALESCE(TO07,0)) / FATOR1 as TO07, SUM(COALESCE(TO08,0)) / FATOR1 as TO08,
                        SUM(COALESCE(TO09,0)) / FATOR1 as TO09, SUM(COALESCE(TO10,0)) / FATOR1 as TO10, SUM(COALESCE(TO11,0)) / FATOR1 as TO11,
                        SUM(COALESCE(TO12,0)) / FATOR1 as TO12, SUM(COALESCE(TO13,0)) / FATOR1 as TO13, SUM(COALESCE(TO14,0)) / FATOR1 as TO14,
                        SUM(COALESCE(TO15,0)) / FATOR1 as TO15, SUM(COALESCE(TO16,0)) / FATOR1 as TO16, SUM(COALESCE(TO17,0)) / FATOR1 as TO17,
                        SUM(COALESCE(TO18,0)) / FATOR1 as TO18, SUM(COALESCE(TO19,0)) / FATOR1 as TO19, SUM(COALESCE(TO20,0)) / FATOR1 as TO20,

                        SUM(COALESCE(TS00,0)) / FATOR1 as TS00, SUM(COALESCE(TS01,0)) / FATOR1 as TS01, SUM(COALESCE(TS02,0)) / FATOR1 as TS02,
                        SUM(COALESCE(TS03,0)) / FATOR1 as TS03, SUM(COALESCE(TS04,0)) / FATOR1 as TS04, SUM(COALESCE(TS05,0)) / FATOR1 as TS05,
                        SUM(COALESCE(TS06,0)) / FATOR1 as TS06, SUM(COALESCE(TS07,0)) / FATOR1 as TS07, SUM(COALESCE(TS08,0)) / FATOR1 as TS08,
                        SUM(COALESCE(TS09,0)) / FATOR1 as TS09, SUM(COALESCE(TS10,0)) / FATOR1 as TS10, SUM(COALESCE(TS11,0)) / FATOR1 as TS11,
                        SUM(COALESCE(TS12,0)) / FATOR1 as TS12, SUM(COALESCE(TS13,0)) / FATOR1 as TS13, SUM(COALESCE(TS14,0)) / FATOR1 as TS14,
                        SUM(COALESCE(TS15,0)) / FATOR1 as TS15, SUM(COALESCE(TS16,0)) / FATOR1 as TS16, SUM(COALESCE(TS17,0)) / FATOR1 as TS17,
                        SUM(COALESCE(TS18,0)) / FATOR1 as TS18, SUM(COALESCE(TS19,0)) / FATOR1 as TS19, SUM(COALESCE(TS20,0)) / FATOR1 as TS20,

                        SUM(C00) / FATOR2 as C00, SUM(C01) / FATOR2 as C01, SUM(C02) / FATOR2 as C02,
                        SUM(C03) / FATOR2 as C03, SUM(C04) / FATOR2 as C04, SUM(C05) / FATOR2 as C05,
                        SUM(C06) / FATOR2 as C06, SUM(C07) / FATOR2 as C07, SUM(C08) / FATOR2 as C08,
                        SUM(C09) / FATOR2 as C09, SUM(C10) / FATOR2 as C10, SUM(C11) / FATOR2 as C11,
                        SUM(C12) / FATOR2 as C12, SUM(C13) / FATOR2 as C13, SUM(C14) / FATOR2 as C14,
                        SUM(C15) / FATOR2 as C15, SUM(C16) / FATOR2 as C16, SUM(C17) / FATOR2 as C17,
                        SUM(C18) / FATOR2 as C18, SUM(C19) / FATOR2 as C19, SUM(C20) / FATOR2 as C20,
                        
                        COALESCE(SUM(CMS00)/ FATOR1,0) CMS00,
                        COALESCE(SUM(CMS01)/ FATOR1,0) CMS01,
                        COALESCE(SUM(CMS02)/ FATOR1,0) CMS02,
                        COALESCE(SUM(CMS03)/ FATOR1,0) CMS03,
                        COALESCE(SUM(CMS04)/ FATOR1,0) CMS04,
                        COALESCE(SUM(CMS05)/ FATOR1,0) CMS05,
                        COALESCE(SUM(CMS06)/ FATOR1,0) CMS06,
                        COALESCE(SUM(CMS07)/ FATOR1,0) CMS07,
                        COALESCE(SUM(CMS08)/ FATOR1,0) CMS08,
                        COALESCE(SUM(CMS09)/ FATOR1,0) CMS09,
                        COALESCE(SUM(CMS10)/ FATOR1,0) CMS10,
                        COALESCE(SUM(CMS11)/ FATOR1,0) CMS11,
                        COALESCE(SUM(CMS12)/ FATOR1,0) CMS12,
                        COALESCE(SUM(CMS13)/ FATOR1,0) CMS13,
                        COALESCE(SUM(CMS14)/ FATOR1,0) CMS14,
                        COALESCE(SUM(CMS15)/ FATOR1,0) CMS15,
                        COALESCE(SUM(CMS16)/ FATOR1,0) CMS16,
                        COALESCE(SUM(CMS17)/ FATOR1,0) CMS17,
                        COALESCE(SUM(CMS18)/ FATOR1,0) CMS18,
                        COALESCE(SUM(CMS19)/ FATOR1,0) CMS19,
                        COALESCE(SUM(CMS20)/ FATOR1,0) CMS20,
                                                        
                        COALESCE(SUM(CMO00)/ FATOR1,0) CMO00,
                        COALESCE(SUM(CMO01)/ FATOR1,0) CMO01,
                        COALESCE(SUM(CMO02)/ FATOR1,0) CMO02,
                        COALESCE(SUM(CMO03)/ FATOR1,0) CMO03,
                        COALESCE(SUM(CMO04)/ FATOR1,0) CMO04,
                        COALESCE(SUM(CMO05)/ FATOR1,0) CMO05,
                        COALESCE(SUM(CMO06)/ FATOR1,0) CMO06,
                        COALESCE(SUM(CMO07)/ FATOR1,0) CMO07,
                        COALESCE(SUM(CMO08)/ FATOR1,0) CMO08,
                        COALESCE(SUM(CMO09)/ FATOR1,0) CMO09,
                        COALESCE(SUM(CMO10)/ FATOR1,0) CMO10,
                        COALESCE(SUM(CMO11)/ FATOR1,0) CMO11,
                        COALESCE(SUM(CMO12)/ FATOR1,0) CMO12,
                        COALESCE(SUM(CMO13)/ FATOR1,0) CMO13,
                        COALESCE(SUM(CMO14)/ FATOR1,0) CMO14,
                        COALESCE(SUM(CMO15)/ FATOR1,0) CMO15,
                        COALESCE(SUM(CMO16)/ FATOR1,0) CMO16,
                        COALESCE(SUM(CMO17)/ FATOR1,0) CMO17,
                        COALESCE(SUM(CMO18)/ FATOR1,0) CMO18,
                        COALESCE(SUM(CMO19)/ FATOR1,0) CMO19,
                        COALESCE(SUM(CMO20)/ FATOR1,0) CMO20,

                        COALESCE(SUM(CS00)/ FATOR2,0) CS00,
                        COALESCE(SUM(CS01)/ FATOR2,0) CS01,
                        COALESCE(SUM(CS02)/ FATOR2,0) CS02,
                        COALESCE(SUM(CS03)/ FATOR2,0) CS03,
                        COALESCE(SUM(CS04)/ FATOR2,0) CS04,
                        COALESCE(SUM(CS05)/ FATOR2,0) CS05,
                        COALESCE(SUM(CS06)/ FATOR2,0) CS06,
                        COALESCE(SUM(CS07)/ FATOR2,0) CS07,
                        COALESCE(SUM(CS08)/ FATOR2,0) CS08,
                        COALESCE(SUM(CS09)/ FATOR2,0) CS09,
                        COALESCE(SUM(CS10)/ FATOR2,0) CS10,
                        COALESCE(SUM(CS11)/ FATOR2,0) CS11,
                        COALESCE(SUM(CS12)/ FATOR2,0) CS12,
                        COALESCE(SUM(CS13)/ FATOR2,0) CS13,
                        COALESCE(SUM(CS14)/ FATOR2,0) CS14,
                        COALESCE(SUM(CS15)/ FATOR2,0) CS15,
                        COALESCE(SUM(CS16)/ FATOR2,0) CS16,
                        COALESCE(SUM(CS17)/ FATOR2,0) CS17,
                        COALESCE(SUM(CS18)/ FATOR2,0) CS18,
                        COALESCE(SUM(CS19)/ FATOR2,0) CS19,
                        COALESCE(SUM(CS20)/ FATOR2,0) CS20,
                        
                        COALESCE(SUM(CO00)/ FATOR2,0) CO00,
                        COALESCE(SUM(CO01)/ FATOR2,0) CO01,
                        COALESCE(SUM(CO02)/ FATOR2,0) CO02,
                        COALESCE(SUM(CO03)/ FATOR2,0) CO03,
                        COALESCE(SUM(CO04)/ FATOR2,0) CO04,
                        COALESCE(SUM(CO05)/ FATOR2,0) CO05,
                        COALESCE(SUM(CO06)/ FATOR2,0) CO06,
                        COALESCE(SUM(CO07)/ FATOR2,0) CO07,
                        COALESCE(SUM(CO08)/ FATOR2,0) CO08,
                        COALESCE(SUM(CO09)/ FATOR2,0) CO09,
                        COALESCE(SUM(CO10)/ FATOR2,0) CO10,
                        COALESCE(SUM(CO11)/ FATOR2,0) CO11,
                        COALESCE(SUM(CO12)/ FATOR2,0) CO12,
                        COALESCE(SUM(CO13)/ FATOR2,0) CO13,
                        COALESCE(SUM(CO14)/ FATOR2,0) CO14,
                        COALESCE(SUM(CO15)/ FATOR2,0) CO15,
                        COALESCE(SUM(CO16)/ FATOR2,0) CO16,
                        COALESCE(SUM(CO17)/ FATOR2,0) CO17,
                        COALESCE(SUM(CO18)/ FATOR2,0) CO18,
                        COALESCE(SUM(CO19)/ FATOR2,0) CO19,
                        COALESCE(SUM(CO20)/ FATOR2,0) CO20,

                        COALESCE(SUM(CMOIP00)/ FATOR1,0) CMOIP00,
                        COALESCE(SUM(CMOIP01)/ FATOR1,0) CMOIP01,
                        COALESCE(SUM(CMOIP02)/ FATOR1,0) CMOIP02,
                        COALESCE(SUM(CMOIP03)/ FATOR1,0) CMOIP03,
                        COALESCE(SUM(CMOIP04)/ FATOR1,0) CMOIP04,
                        COALESCE(SUM(CMOIP05)/ FATOR1,0) CMOIP05,
                        COALESCE(SUM(CMOIP06)/ FATOR1,0) CMOIP06,
                        COALESCE(SUM(CMOIP07)/ FATOR1,0) CMOIP07,
                        COALESCE(SUM(CMOIP08)/ FATOR1,0) CMOIP08,
                        COALESCE(SUM(CMOIP09)/ FATOR1,0) CMOIP09,
                        COALESCE(SUM(CMOIP10)/ FATOR1,0) CMOIP10,
                        COALESCE(SUM(CMOIP11)/ FATOR1,0) CMOIP11,
                        COALESCE(SUM(CMOIP12)/ FATOR1,0) CMOIP12,
                        COALESCE(SUM(CMOIP13)/ FATOR1,0) CMOIP13,
                        COALESCE(SUM(CMOIP14)/ FATOR1,0) CMOIP14,
                        COALESCE(SUM(CMOIP15)/ FATOR1,0) CMOIP15,
                        COALESCE(SUM(CMOIP16)/ FATOR1,0) CMOIP16,
                        COALESCE(SUM(CMOIP17)/ FATOR1,0) CMOIP17,
                        COALESCE(SUM(CMOIP18)/ FATOR1,0) CMOIP18,
                        COALESCE(SUM(CMOIP19)/ FATOR1,0) CMOIP19,
                        COALESCE(SUM(CMOIP20)/ FATOR1,0) CMOIP20,

                        COALESCE(SUM(CMOIA00)/ FATOR1,0) CMOIA00,
                        COALESCE(SUM(CMOIA01)/ FATOR1,0) CMOIA01,
                        COALESCE(SUM(CMOIA02)/ FATOR1,0) CMOIA02,
                        COALESCE(SUM(CMOIA03)/ FATOR1,0) CMOIA03,
                        COALESCE(SUM(CMOIA04)/ FATOR1,0) CMOIA04,
                        COALESCE(SUM(CMOIA05)/ FATOR1,0) CMOIA05,
                        COALESCE(SUM(CMOIA06)/ FATOR1,0) CMOIA06,
                        COALESCE(SUM(CMOIA07)/ FATOR1,0) CMOIA07,
                        COALESCE(SUM(CMOIA08)/ FATOR1,0) CMOIA08,
                        COALESCE(SUM(CMOIA09)/ FATOR1,0) CMOIA09,
                        COALESCE(SUM(CMOIA10)/ FATOR1,0) CMOIA10,
                        COALESCE(SUM(CMOIA11)/ FATOR1,0) CMOIA11,
                        COALESCE(SUM(CMOIA12)/ FATOR1,0) CMOIA12,
                        COALESCE(SUM(CMOIA13)/ FATOR1,0) CMOIA13,
                        COALESCE(SUM(CMOIA14)/ FATOR1,0) CMOIA14,
                        COALESCE(SUM(CMOIA15)/ FATOR1,0) CMOIA15,
                        COALESCE(SUM(CMOIA16)/ FATOR1,0) CMOIA16,
                        COALESCE(SUM(CMOIA17)/ FATOR1,0) CMOIA17,
                        COALESCE(SUM(CMOIA18)/ FATOR1,0) CMOIA18,
                        COALESCE(SUM(CMOIA19)/ FATOR1,0) CMOIA19,
                        COALESCE(SUM(CMOIA20)/ FATOR1,0) CMOIA20,

                        COALESCE(SUM(CSMOIP00)/ FATOR1,0) CSMOIP00,
                        COALESCE(SUM(CSMOIP01)/ FATOR1,0) CSMOIP01,
                        COALESCE(SUM(CSMOIP02)/ FATOR1,0) CSMOIP02,
                        COALESCE(SUM(CSMOIP03)/ FATOR1,0) CSMOIP03,
                        COALESCE(SUM(CSMOIP04)/ FATOR1,0) CSMOIP04,
                        COALESCE(SUM(CSMOIP05)/ FATOR1,0) CSMOIP05,
                        COALESCE(SUM(CSMOIP06)/ FATOR1,0) CSMOIP06,
                        COALESCE(SUM(CSMOIP07)/ FATOR1,0) CSMOIP07,
                        COALESCE(SUM(CSMOIP08)/ FATOR1,0) CSMOIP08,
                        COALESCE(SUM(CSMOIP09)/ FATOR1,0) CSMOIP09,
                        COALESCE(SUM(CSMOIP10)/ FATOR1,0) CSMOIP10,
                        COALESCE(SUM(CSMOIP11)/ FATOR1,0) CSMOIP11,
                        COALESCE(SUM(CSMOIP12)/ FATOR1,0) CSMOIP12,
                        COALESCE(SUM(CSMOIP13)/ FATOR1,0) CSMOIP13,
                        COALESCE(SUM(CSMOIP14)/ FATOR1,0) CSMOIP14,
                        COALESCE(SUM(CSMOIP15)/ FATOR1,0) CSMOIP15,
                        COALESCE(SUM(CSMOIP16)/ FATOR1,0) CSMOIP16,
                        COALESCE(SUM(CSMOIP17)/ FATOR1,0) CSMOIP17,
                        COALESCE(SUM(CSMOIP18)/ FATOR1,0) CSMOIP18,
                        COALESCE(SUM(CSMOIP19)/ FATOR1,0) CSMOIP19,
                        COALESCE(SUM(CSMOIP20)/ FATOR1,0) CSMOIP20,

                        COALESCE(SUM(CSMOIA00)/ FATOR1,0) CSMOIA00,
                        COALESCE(SUM(CSMOIA01)/ FATOR1,0) CSMOIA01,
                        COALESCE(SUM(CSMOIA02)/ FATOR1,0) CSMOIA02,
                        COALESCE(SUM(CSMOIA03)/ FATOR1,0) CSMOIA03,
                        COALESCE(SUM(CSMOIA04)/ FATOR1,0) CSMOIA04,
                        COALESCE(SUM(CSMOIA05)/ FATOR1,0) CSMOIA05,
                        COALESCE(SUM(CSMOIA06)/ FATOR1,0) CSMOIA06,
                        COALESCE(SUM(CSMOIA07)/ FATOR1,0) CSMOIA07,
                        COALESCE(SUM(CSMOIA08)/ FATOR1,0) CSMOIA08,
                        COALESCE(SUM(CSMOIA09)/ FATOR1,0) CSMOIA09,
                        COALESCE(SUM(CSMOIA10)/ FATOR1,0) CSMOIA10,
                        COALESCE(SUM(CSMOIA11)/ FATOR1,0) CSMOIA11,
                        COALESCE(SUM(CSMOIA12)/ FATOR1,0) CSMOIA12,
                        COALESCE(SUM(CSMOIA13)/ FATOR1,0) CSMOIA13,
                        COALESCE(SUM(CSMOIA14)/ FATOR1,0) CSMOIA14,
                        COALESCE(SUM(CSMOIA15)/ FATOR1,0) CSMOIA15,
                        COALESCE(SUM(CSMOIA16)/ FATOR1,0) CSMOIA16,
                        COALESCE(SUM(CSMOIA17)/ FATOR1,0) CSMOIA17,
                        COALESCE(SUM(CSMOIA18)/ FATOR1,0) CSMOIA18,
                        COALESCE(SUM(CSMOIA19)/ FATOR1,0) CSMOIA19,
                        COALESCE(SUM(CSMOIA20)/ FATOR1,0) CSMOIA20,

                        COALESCE(SUM(PERDA00)/ FATOR1,0) PERDA00,
                        COALESCE(SUM(PERDA01)/ FATOR1,0) PERDA01,
                        COALESCE(SUM(PERDA02)/ FATOR1,0) PERDA02,
                        COALESCE(SUM(PERDA03)/ FATOR1,0) PERDA03,
                        COALESCE(SUM(PERDA04)/ FATOR1,0) PERDA04,
                        COALESCE(SUM(PERDA05)/ FATOR1,0) PERDA05,
                        COALESCE(SUM(PERDA06)/ FATOR1,0) PERDA06,
                        COALESCE(SUM(PERDA07)/ FATOR1,0) PERDA07,
                        COALESCE(SUM(PERDA08)/ FATOR1,0) PERDA08,
                        COALESCE(SUM(PERDA09)/ FATOR1,0) PERDA09,
                        COALESCE(SUM(PERDA10)/ FATOR1,0) PERDA10,
                        COALESCE(SUM(PERDA11)/ FATOR1,0) PERDA11,
                        COALESCE(SUM(PERDA12)/ FATOR1,0) PERDA12,
                        COALESCE(SUM(PERDA13)/ FATOR1,0) PERDA13,
                        COALESCE(SUM(PERDA14)/ FATOR1,0) PERDA14,
                        COALESCE(SUM(PERDA15)/ FATOR1,0) PERDA15,
                        COALESCE(SUM(PERDA16)/ FATOR1,0) PERDA16,
                        COALESCE(SUM(PERDA17)/ FATOR1,0) PERDA17,
                        COALESCE(SUM(PERDA18)/ FATOR1,0) PERDA18,
                        COALESCE(SUM(PERDA19)/ FATOR1,0) PERDA19,
                        COALESCE(SUM(PERDA20)/ FATOR1,0) PERDA20

                    from(
                        select
                            
                            p.*,
                            T00, T01, T02, T03, T04, T05, T06,
                            T07, T08, T09, T10, T11, T12, T13,
                            T14, T15, T16, T17, T18, T19, T20,
                            
                            PRODUTO_CONSUMO_ID, TAMANHO_CONSUMO_ID,
                            CUSTO_MEDIO, CLASSE,
                            
                            C00, C01, C02,
                            C03, C04, C05, C06, C07, C08,
                            C09, C10, C11, C12, C13, C14,
                            C15, C16, C17, C18, C19, C20,
                            
                            SEQ_COMPOSICAO_CUSTO,
                            CS00, CO00, CS01, CO01, CS02, CO02,
                            CS03, CO03, CS04, CO04, CS05, CO05,
                            CS06, CO06, CS07, CO07, CS08, CO08, 
                            CS09, CO09, CS10, CO10, CS11, CO11,
                            CS12, CO12, CS13, CO13, CS14, CO14,
                            CS15, CO15, CS16, CO16, CS17, CO17,
                            CS18, CO18, CS19, CO19, CS20, CO20,
                            CONTA_UPDATE,
                            COMOIP00, COMOIP01, COMOIP02, COMOIP03, COMOIP04, COMOIP05,
                            COMOIP06, COMOIP07, COMOIP08, COMOIP09, COMOIP10, COMOIP11,
                            COMOIP12, COMOIP13, COMOIP14, COMOIP15, COMOIP16, COMOIP17,
                            COMOIP18, COMOIP19, COMOIP20, COMOIA00, COMOIA01, COMOIA02,
                            COMOIA03, COMOIA04, COMOIA05, COMOIA06, COMOIA07, COMOIA08,
                            COMOIA09, COMOIA10, COMOIA11, COMOIA12, COMOIA13, COMOIA14,
                            COMOIA15, COMOIA16, COMOIA17, COMOIA18, COMOIA19, COMOIA20,
                            TS00, TS01, TS02, TS03, TS04, TS05,
                            TS06, TS07, TS08, TS09, TS10, TS11,
                            TS12, TS13, TS14, TS15, TS16, TS17,
                            TS18, TS19, TS20, TO00, TO01, TO02,
                            TO03, TO04, TO05, TO06, TO07, TO08,
                            TO09, TO10, TO11, TO12, TO13, TO14,
                            TO15, TO16, TO17, TO18, TO19, TO20,
                            CUSTO_MINUTO,
                            CMS00, CMS01, CMS02, CMS03, CMS04, CMS05,
                            CMS06, CMS07, CMS08, CMS09, CMS10, CMS11,
                            CMS12, CMS13, CMS14, CMS15, CMS16, CMS17,
                            CMS18, CMS19, CMS20, CMO00, CMO01, CMO02,
                            CMO03, CMO04, CMO05, CMO06, CMO07, CMO08,
                            CMO09, CMO10, CMO11, CMO12, CMO13, CMO14,
                            CMO15, CMO16, CMO17, CMO18, CMO19, CMO20,
                            MOI_PROPRIO,
                            CMOIP00, CMOIP01, CMOIP02, CMOIP03, CMOIP04,
                            CMOIP05, CMOIP06, CMOIP07, CMOIP08,
                            CMOIP09, CMOIP10, CMOIP11, CMOIP12,
                            CMOIP13, CMOIP14, CMOIP15, CMOIP16,
                            CMOIP17, CMOIP18, CMOIP19, CMOIP20,
                            MOI_ABSORVIDO,
                            CMOIA00, CMOIA01, CMOIA02,
                            CMOIA03, CMOIA04, CMOIA05, CMOIA06, CMOIA07, CMOIA08,
                            CMOIA09, CMOIA10, CMOIA11, CMOIA12, CMOIA13, CMOIA14, 
                            CMOIA15, CMOIA16, CMOIA17, CMOIA18, CMOIA19, CMOIA20,
                            CSMOIP00, CSMOIP01, CSMOIP02, CSMOIP03, CSMOIP04, CSMOIP05,
                            CSMOIP06, CSMOIP07, CSMOIP08, CSMOIP09, CSMOIP10, CSMOIP11,
                            CSMOIP12, CSMOIP13, CSMOIP14, CSMOIP15, CSMOIP16, CSMOIP17,
                            CSMOIP18, CSMOIP19, CSMOIP20, CSMOIA00, CSMOIA01, CSMOIA02,
                            CSMOIA03, CSMOIA04, CSMOIA05, CSMOIA06, CSMOIA07, CSMOIA08,
                            CSMOIA09, CSMOIA10, CSMOIA11, CSMOIA12, CSMOIA13, CSMOIA14,
                            CSMOIA15, CSMOIA16, CSMOIA17, CSMOIA18, CSMOIA19, CSMOIA20,
                            PERCENTUAL_PERDA,
                            count(p.codigo) over() as FATOR1,
                            '.$data['FATOR'].' as FATOR2,

                            coalesce(PERDA00,0) as PERDA00,
                            coalesce(PERDA01,0) as PERDA01,
                            coalesce(PERDA02,0) as PERDA02,
                            coalesce(PERDA03,0) as PERDA03,
                            coalesce(PERDA04,0) as PERDA04,
                            coalesce(PERDA05,0) as PERDA05,
                            coalesce(PERDA06,0) as PERDA06,
                            coalesce(PERDA07,0) as PERDA07,
                            coalesce(PERDA08,0) as PERDA08,
                            coalesce(PERDA09,0) as PERDA09,
                            coalesce(PERDA10,0) as PERDA10,
                            coalesce(PERDA11,0) as PERDA11,
                            coalesce(PERDA12,0) as PERDA12,
                            coalesce(PERDA13,0) as PERDA13,
                            coalesce(PERDA14,0) as PERDA14,
                            coalesce(PERDA15,0) as PERDA15,
                            coalesce(PERDA16,0) as PERDA16,
                            coalesce(PERDA17,0) as PERDA17,
                            coalesce(PERDA18,0) as PERDA18,
                            coalesce(PERDA19,0) as PERDA19,
                            coalesce(PERDA20,0) as PERDA20
                        
                        FROM (select
                                d.*,
                                l.*
                            from
                            (select ANO,MES,DATA1,DATA2 from SPU_TABELA_DATAS('.$data['ANO'].','.$data['ANO2'].','.$data['MES'].','.$data['MES2'].')) D, TBPRODUTO l
                             WHERE l.FAMILIA_CODIGO in (3, 12, 74, 71,3, 39)
                             and l.codigo = (select first 1
                                                    p.codigo
                                                FROM tbproduto p
                                                where p.modelo_codigo = '.$modelo['ID'].'
                                                and p.cor_codigo      = '.$cor['ID'].')) P

                          Left Join TbFicha_Tecnica FT1 On FT1.Produto_id = P.Codigo and FT1.mes = p.MES and FT1.ano = p.ANO
                          Left Join TbFicha_Tempo   FT2 On FT2.Produto_id = P.Codigo and FT2.mes = p.MES and FT2.ano = p.ANO
                    ) k

                    Group By 1,2,3,4,5,6,7,8) y
                    ';

            $ret = $con->query($sql);

            $con->commit();
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarPerfil($filtro,$con) {

        $modelo  = $filtro['MODELO'];
        $cor     = $filtro['COR'];
        $tamanho = $filtro['TAMANHO'];

        try {

            $sql = 'SELECT

                        j.id,
                        j.tabela,
                        j.descricao

                    from
                        vwsku s, tbperfil j
                    where s.modelo_id = '.$modelo['ID'].'
                    and s.cor_id = '.$cor['ID'].'
                    and s.tamanho = '.$tamanho['ID'].'
                    and j.tabela = \'SKU\'
                    and j.id = s.perfil';

            $ret = $con->query($sql);

            $con->commit();
            
            if(count($ret) > 0){
                $ret = $ret[0];    
            }else{
                $ret = [];   
            }

            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Consultar Tamanho de um modelo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarTamanho($filtro,$con) {

        $modelo = $filtro['OPTIONS']['dados'];
        $desc   = strtoupper($filtro['FILTRO']);
        $paran  = $filtro['PARAN'];
        $padrao = $paran['PADRAO'];


        if($padrao == 1){
            $padrao  = ' where m.TAMANHO = '.$modelo['TAMANHO'].' ';
            $padrao2 = ' ID = '.$modelo['TAMANHO'].' ';
        }else{
            $padrao  = '';
            $padrao2 = '';
        }

        if($desc != ''){
                $desc = 'where DESCRICAO like \'%'.$desc.'%\'';
        }else{
            $desc = '';   
        }

        if(strlen($padrao2) > 0 && strlen($desc) > 0){
            $padrao2 = 'and ' . $padrao2;
        }else{
            if(strlen($padrao2) > 0){
                $padrao2 = 'where ' . $padrao2;
            }
        }

        try {

            $sql = 'SELECT
                        l.*
                    from(
                        SELECT
                            *
                        from(
                            SELECT
                                \'\' REPLICA,
                                m.TAMANHO as LISTA,
                                m.TAMANHO as ID,
                                m.GRADE_ID,
                                fn_tamanho_grade( m.grade_id, m.tamanho) || iif(m.TAMANHO = '.$modelo['TAMANHO'].',\' ★ \',\'\') as DESCRICAO
                            from
                                SPC_MODELO_GRADE_TAMANHO('.$modelo['ID'].') m

                                '.$padrao.'
                        ) j
                        
                        union

                        select
                            coalesce((select
                                           list(LISTA||\'#$#\'||ID||\'#$#\'||GRADE_ID||\'#$#\'||DESCRICAO,\'#@#\') as REPLICA
                                        from(
                                            SELECT
                                                m.TAMANHO as LISTA,
                                                m.TAMANHO as ID,
                                                m.GRADE_ID,
                                            fn_tamanho_grade( m.grade_id, m.tamanho) as DESCRICAO
                                            from SPC_MODELO_GRADE_TAMANHO('.$modelo['ID'].') m
                                        )),\'\')  as REPLICA,

                            coalesce((SELECT list(m.TAMANHO,\',\') from SPC_MODELO_GRADE_TAMANHO('.$modelo['ID'].') m),\'\') as LISTA,
                            21        ID,
                            999       GRADE_ID,
                            \'TODOS\' DESCRICAO
                        from RDB$DATABASE
                    ) l

                    '.$desc.'
                    '.$padrao2.'
                ';

            $ret = $con->query($sql);

            $con->commit();
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Consultar Tamanho de um modelo
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarTamanho2($filtro,$con) {

        $desc    = strtoupper($filtro['FILTRO']);
        $PRODUTO = $filtro['PARAN']['PRODUTO'];
        $padrao  = $filtro['PARAN']['PADRAO'];


        if($padrao == 1){
            if($padrao == $PRODUTO['TAMANHO'] > 0){
                $padrao = ' where m.TAMANHO = '.$PRODUTO['TAMANHO'].' ';
            }else{
                $padrao = '';
            }
        }else{
            $padrao = '';
        }

        if($desc != ''){
                $desc = 'where j.DESCRICAO = \''.$desc.'\'';
        }else{
            $desc = '';   
        }

        try {

            $sql = 'SELECT

                        *

                    from(
                        SELECT
                            m.TAMANHO as ID,
                            m.GRADE_ID,
                            fn_tamanho_grade( m.grade_id, m.tamanho) || iif(m.TAMANHO = '.$PRODUTO['TAMANHO'].',\' ★ \',\'\') as DESCRICAO
                        from
                            SPC_MODELO_GRADE_TAMANHO('.$PRODUTO['MODELO_ID'].') m

                            '.$padrao.'
                    ) j

                    '.$desc.'

                ';

            

            $ret = $con->query($sql);

            if(count($ret) == 0){
                $ret = [];
                array_push($ret, ['ID' => 0, 'GRADE_ID' => 0, 'DESCRICAO' => '0']);
            }

            $con->commit();
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }
	
}