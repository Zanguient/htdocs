<?php

namespace App\Models\DAO\Opex;

use App\Models\Conexao\_Conexao;
use Illuminate\Support\Facades\Auth;


class _25900DAO {

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
     * Areas do BSC
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function consultarArea($dados) {
        
        $con = new _Conexao();
        
        $filtro = $dados['filtro'];
        
        $filtro = isset($filtro) ? "%" .str_replace(' ', '%', $filtro). "%" : '%';
        
        $estab = $dados['ESTAB'];
        
        
        $sql = "SELECT 

                lpad(a.id,4,0) as MASC,
                ID,
                DESCRICAO,
                GRUPO_ID

                FROM TBINDICADOR_AREA A 
                WHERE 1=1
                AND A.ESTABELECIMENTO_ID = :ESTAB
                AND A.DESCRICAO LIKE :FILTRO";

        $args = array(
            ':ESTAB'  => $dados['ESTAB'],
            ':FILTRO' => $filtro
        );

        $ret = $con->query($sql,$args);

        $con->commit();

        return $ret;

    }
    
    /**
     * Configuracoes de uma Areas do BSC Conf 
     * @access public
     * @param int $area_id
     * @return array
     */
    public static function consultarAreaConf($area_id) {
        
        $con = new _Conexao();
        
        $sql = "SELECT

                    A.ID,
                    A.TELA,
                    A.COMPONENTE_ID,
                    A.AGRUPAMENTO,
                    A.FLAG,
                    A.DESCRICAO,
                    A.AREA_ID,
                    C.TELA TELA_COMPONENTE,
                    C.VARIAVEL_ID VARIAVEL_COMPONENTE,
                    C.SQL SQL_COMPONENTE,
                    C.FLAG

                FROM TBINDICADOR_AREA_CONF A,TBINDICADOR_COMPONENTE C
                    WHERE A.AREA_ID = :AREA_ID
                    AND C.ID = A.COMPONENTE_ID";

        $args = array(
            ':AREA_ID'  => $area_id
        );

        $ret = $con->query($sql,$args);

        $con->commit();
        
        if(isset($ret[0])){
            $res = $ret[0];
        }else{
            log_erro('Não foi encontrada as configurações da área '.$area_id);
        }
        
        return $res;

    }
    
    /**
     * Consulta setores de um ou mais grupos 
     * @access public
     * @param String $grupoList
     * @param String $setorList
     * @return array
     */
    public static function consultarSetoresConf($grupoList,$setorList) {
        
        $con = new _Conexao();
        
        $filtro = (isset($grupoList) && ($grupoList != '')) ? "SELECT a.setor_id FROM tbindicador_setor_grupo_detalhe A WHERE A.grupo_id in ($grupoList)" : $setorList;
       
        $sql = "SELECT
                    ID,
                    DESCRICAO,
                    A.valor

                FROM TBINDICADOR_SETOR A
                    WHERE A.id in($filtro)";

        $ret = $con->query($sql);
        
        $con->commit();
        
        if(!isset($ret[0])){
            log_erro('Não foi encontrada as configurações do setor');
        }
        
        return $ret;

    }
    
    /**
     * Consulta configuracao dos indicadores 
     * @access public
     * @param string $PerspectivaID
     * @return array
     */
    public static function getIndicadores($PerspectivaID) {
        
        $con = new _Conexao();
        
        $sql = "SELECT

                    I.ID,
                    I.DESCRICAO,
                    I.SQL_ID,
                    I.TIPO,
                    I.FAIXA_ID,
                    d.ordem

                FROM TBINDICADOR_ITEM I,tbindicador_perspectiva_d d
                    WHERE I.id = d.indicador_id
                        and d.perspectiva_id = $PerspectivaID
                        order by d.ordem";
        
        $ret = $con->query($sql);
        
        $con->commit();
        
        return $ret;

    }
    
    /**
     * Retorna um sql da TBINDICADORES_SQL 
     * @access public
     * @param int $sqlID
     * @return array
     */
    public static function getSql($sqlID) {
        
        $con = new _Conexao();
        
        $sql = "select s.sql,s.variaveis_id from tbindicador_sql s where s.id = :ID";
        
        $args = array(
            ':ID'  => $sqlID
        );
        
        $ret = $con->query($sql,$args);
        
        $con->commit();

        return $ret;

    }
    
    /**
     * Consulta faixas de um indicador
     * @access public
     * @param string $listID
     * @return array
     */
    public static function getFaixas($FaixaID,$setores,$area,$perspectiva,$data) {
        
        $con = new _Conexao();
        
        $sql = "select
                    *
                from tbindicador_faixa_historico i
                where i.faixa_id = :FAIXA
                and AREA_ID = :AREA
                and PERSPESCTIVA_ID = :PERSPECTIVA
                and '".$setores."' like '%'||i.SETOR_VALOR||'%'
                and i.data = '" . $data . "'";
        
        $args = array(
            ':FAIXA'       => $FaixaID,
            ':AREA'        => $area,
            ':PERSPECTIVA' => $perspectiva
        );

        $ret = $con->query($sql,$args);

        if(!isset($ret[0])){
            log_erro('Faixas '.$FaixaID.' não tratada para o dia '.$data);  
        }
        
        $con->commit();
        
        return $ret;

    }
    
    /**
     * Consulta faixas de um indicador
     * @access public
     * @param string $listID
     * @return array
     */
    public static function getValorIndicador($IndicadorID,$setores,$area,$perspectiva,$data) {
        
        $con = new _Conexao();
        
        $sql = "select
                    coalesce((select first 1 c.valor||' - '||c.descricao from tbindicador_setor c where c.valor = SETOR_VALOR and c.area_id = i.area_id),'') SETOR_VALOR,
                    i.campo,
                    i.valor,
                    i.data_tratado,
                    i.data
                from tbindicador_item_historico i
                where i.indicador_id = :INDICADOR
                and I.AREA_ID = :AREA
                and I.PERSPESCTIVA_ID = :PERSPECTIVA
                and '".$setores."' like '%'||i.SETOR_VALOR||'%'
                and i.data = '" . $data . "'";
        
        
        
        $args = array(
            ':INDICADOR'       => $IndicadorID,
            ':AREA'        => $area,
            ':PERSPECTIVA' => $perspectiva
        );
        
        $ret = $con->query($sql,$args);

        if(!isset($ret[0])){
            log_erro('Indicador :'.$IndicadorID.' não tratado para o dia '.$data);  
        }
        
        $con->commit();
        
        return $ret;

    }
    
    /**
     * Consulta os valores dos indicadores  
     * @access public
     * @param array $dados
     * @return array
     */
    public static function consultarIndicadores($dados) {
        
        $con = new _Conexao();
        
        $listIndicadores = '';
        $listSetores = '';
        $ListIndicadores = [];
        $ItemIndicador   = [];
        
        $PerspectivaConf     = $dados['PerspectivaConf'];
        $SetoresConf         = $dados['SetoresConf'];
        $estabelecimento_id  = $dados['estabelecimento_id'];
        $data_inicial        = $dados['data_inicial'];
        $data_final          = $dados['data_final'];
        $area                = $dados['area'];
        $perspectiva         = $dados['perspectiva'];
        
        foreach ($SetoresConf as $Setores){
            
            $valor = $Setores->VALOR;
                    
            if($listSetores == ''){
                $listSetores = ''.$valor;
            }else{
                $listSetores = $listSetores.','.$valor;
            }     
        }
        
        $indicadorConf = _25900DAO::getIndicadores($perspectiva);
        
        foreach ($indicadorConf as $indicador){
            
            $data_consultar = $data_final;
            
            $faixa = _25900DAO::getFaixas($indicador->FAIXA_ID,$listSetores,$area,$perspectiva,$data_consultar);
            $indca = _25900DAO::getValorIndicador($indicador->ID,$listSetores,$area,$perspectiva,$data_consultar);
            
            $ret = [];
            
            $ItemIndicador = [
                'ID'        => $indicador->ID,
                'INDICADOR' => $indicador,
                'DADOS'     => $indca,
                'FAIXA'     => $faixa
            ];
            
            array_push($ListIndicadores, $ItemIndicador);   
        }
        
        $con->commit();
        
        if(count($ListIndicadores) == 0){
            log_erro('Indicador '.$indicador->ID.' não encontrado para o dia '.$data_final);
        }

        return $ListIndicadores;

    }
    
    /**
     * Consulta os indicadores de uma perspectiva 
     * @access public
     * @param int $perspectiva_id
     * @return array
     */
    public static function consultarPerspectivaConf($perspectiva_id) {
        
        $con = new _Conexao();

        $sql = "SELECT
            
                    ID,
                    INDICADOR_ID,
                    ORDEM,
                    AGRUPAMENTO_ID,
                    PERSPECTIVA_ID,
                    DESCRICAO

                FROM TBINDICADOR_PERSPECTIVA_D A
                    WHERE A.PERSPECTIVA_ID = :PERSPECTIVA_ID
                    order by a.ordem";
        
        $args = array(
            ':PERSPECTIVA_ID'  => $perspectiva_id
        );
        
        $ret = $con->query($sql,$args);
        
        $con->commit();
        
        if(!isset($ret[0])){
            log_erro('Não foi encontrada perspectivas (INDICADORES)');
        }
        
        return $ret;

    }
    
    /**
     * Consulta os indicadores de uma perspectiva 
     * @access public
     * @param int $perspectiva_id
     * @return array
     */
    public static function consultarDescricao($setorList,$grupoList) {
        
        $con = new _Conexao();

        if(strlen($grupoList) == 0){
            $sql = "select list(s.descricao,',') as descricao,list(s.ccusto,',') as ccusto   from tbindicador_setor s where s.id in ($setorList)";
        }else{
            $sql = "select

                    list(descricao,',') as descricao,
                    list(ccusto,',') as ccusto

                from (
                    select s.descricao, list( c.ccusto,',') as ccusto from
                    
                    tbindicador_setor_grupo s,tbindicador_setor_grupo_detalhe d, tbindicador_setor c
                     where s.id in ($grupoList)
                     and d.grupo_id = s.id
                     and c.id = d.setor_id
                     group by s.descricao
                )";
        }
        
        $ret = $con->query($sql);
        
        $con->commit();
        
        if(!isset($ret[0])){
            log_erro('Setor(es) não encontrado(s)');
        }
        
        return [$ret[0]->DESCRICAO,$ret[0]->CCUSTO];

    }
    
    /**
     * Areas do BSC
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function filtarIndicador($dados) {
        
        $con = new _Conexao();
        
        $sql = "SELECT 

                lpad(a.id,4,0) as MASC,
                ID,
                DESCRICAO,
                GRUPO_ID

                FROM TBINDICADOR_AREA A 
                WHERE 1=1
                AND A.ESTABELECIMENTO_ID = :ESTAB";

        $args = array(
            ':ESTAB'  => $dados['estb']
        );

        $ret = $con->query($sql,$args);

        $con->commit();

        return $ret;

    }
    
    /**
     * Setores do BSC
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function consultarSetor($dados) {
        
        $con = new _Conexao();
            
            $filtro = $dados['filtro'];
        
            $filtro = isset($filtro) ? "%" .str_replace(' ', '%', $filtro). "%" : '%';

            $sql = 'SELECT
                
                    lpad(a.id,4,0) as MASC,
                    a.ID,
                    a.DESCRICAO
                    
                    FROM TBINDICADOR_SETOR A, tbindicador_setor_agrupamento B
                        WHERE A.AREA_ID = B.valor
                        AND A.DESCRICAO LIKE :FILTRO
                        AND B.area_id = :AREA';

            $args = array(
                ':AREA'   => $dados['AREA'],
                ':FILTRO' => $filtro
            );

            $ret = $con->query($sql, $args);

            $con->commit();
            
			return $ret;

    }
    
    /**
     * Grupos de setores do BSC
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function consultarGrupos($dados) {
        
        $con = new _Conexao();
            
            $filtro = $dados['filtro'];
        
            $filtro = isset($filtro) ? "%" .str_replace(' ', '%', $filtro). "%" : '%';

            $sql = 'SELECT
                
                    lpad(a.id,4,0) as MASC,
                    a.ID,
                    a.DESCRICAO
                    
                    FROM TBINDICADOR_SETOR_GRUPO A, tbindicador_setor_agrupamento B
                        WHERE A.AREA_ID = B.valor
                        AND A.DESCRICAO LIKE :FILTRO
                        AND B.area_id = :AREA';

            $args = array(
                ':AREA'   => $dados['AREA'],
                ':FILTRO' => $filtro
            );

            $ret = $con->query($sql, $args);

            $con->commit();
            
			return $ret;

    }
    
    /**
     * Areas do BSC
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function consultarPerspectiva($dados) {
        
        $con = new _Conexao();
            
            $filtro = $dados['filtro'];
        
            $filtro = isset($filtro) ? "%" .str_replace(' ', '%', $filtro). "%" : '%';

            $sql = "SELECT
                
                    lpad(a.id,4,0) as MASC,
                    ID,
                    DESCRICAO
                    
                    FROM TBINDICADOR_PERSPECTIVA A
                        WHERE A.AREA_ID = :AREA
                        AND A.DESCRICAO LIKE :FILTRO";

            $args = array(
                ':AREA'   => $dados['AREA'],
                ':FILTRO' => $filtro
            );

            $ret = $con->query($sql, $args);

            $con->commit();
            
			return $ret;

    }
    
    /**
     * Consulta agrupamentos  
     * @access public
     * @param string $perpectiva_id
     * @return array
     */
    public static function consultarAgrupamentos($perpectiva_id){
        $con = new _Conexao();

        $sql = "select  a.descricao,c.codigo,count(d.agrupamento_id) as linhas,a.ordem
                from tbindicador_perspectiva_d d, tbindicador_agrupamento a, tbindicador_cor c
                where d.perspectiva_id = $perpectiva_id
                and a.id = d.agrupamento_id
                and c.id = a.cor_id
                group by a.descricao,c.codigo, a.ordem
                order by a.ordem";

        $ret = $con->query($sql);

        $con->commit();

        return $ret;
	}
    
    /**
     * Executa o sql de um componente 
     * @access public
     * @param string $sql
     * @return array
     */
    public static function execComponente($sql,$ccusto,$flag){
        $con = new _Conexao();

        $sql = str_replace(":VALOR", $ccusto, $sql);
        $sql = str_replace(":FLAG",  $flag,   $sql);

        $ret = $con->query($sql);

        $con->commit();

        return $ret;
	}

    /**
     * Data de producao
     * @access public
     * @param string $familha
     * @return array
     */
    public static function consultarIndicadorFaixa($id) {
        
        $con = new _Conexao();

        $sql = "SELECT first 1

                    I.ID,
                    I.perfil1_a,
                    I.perfil1_b,
                    I.perfil2_a,
                    I.perfil2_b,
                    I.perfil3_a,
                    I.perfil3_b
                FROM tbbsc_indicadores I

                WHERE I.id = $id";

        $ret = $con->query($sql);

        $con->commit();

        return $ret;

    }

    /**
     * Data de producao
     * @access public
     * @param string $familha
     * @return array
     */
    public static function consultarDataProd($familha) {
        
        $con = new _Conexao();

        $sql = "Select

                DATA_PRODUCAO,
                cast(lpad(1,2,'0')||'.'||lpad(extract(MONTH FROM DATA_PRODUCAO),2,'0')
                ||'.'||extract(YEAR FROM DATA_PRODUCAO) as timestamp) as DATA_INICIO_MES,
                (select cast(DIA_INICIO as timestamp) DIA_INICIO from CALC_PERILDO_SEMANA_PROD($familha))as DIA_INICIO_SEMANA

                From TbFamilia Where Codigo = $familha";

        $ret = $con->query($sql);

        $con->commit();

        return $ret;

    }
    
    
    
}