<?php

namespace App\Models\DAO\Opex;
use App\Models\Conexao\_Conexao;
use Illuminate\Support\Facades\Auth;
use PDO;

/**
 * DAO do objeto 25700
 * @package Engenharia
 * @category opex
 */
class _25700DAO {
    
     public static function coletado($dados) {
        $con = new _Conexao();
		
        $sql = "select * from inventario_tecido_coletado(:INV,:TIPO)";
            
            $query = $con->pdo->prepare($sql);

            $paran01 = $dados['INV'];
            $paran02 = $dados['TIPO'];
            
        $args = array(
            ':INV'           => $paran01,
            ':TIPO'          => $paran02
		);
        
		$dado = $con->query($sql, $args);
        $con->commit();

		return $dado;
        
    }
    
    public static function prodgp($dados) {
        $con = new _Conexao();
		
        $sql = "
            select * from (

                select
                GP,
                (select g.descricao from tbgp g where g.id = GP) as DGP,
                    DATA_CONSULTA,
                    PR2,
                    PR4,
                    PR5,
                    PR_SEMANA,
                    PR_MEZ
    
                    from(
    
                    SELECT
                    GP,
                    H.DATA_CONSULTA,
                    sum(PR2) PR2,
                    sum(PR4) PR4,
                    sum(PR5) PR5,
                    sum(PR_SEMANA) PR_SEMANA,
                    sum(PR_MEZ) PR_MEZ
    
                    FROM TBTV_HISTORICO_PROD H WHERE H.GP in(select g.id from tbgp g where g.familia_id = 3) AND H.ESTABELECIMENTO = 1 AND H.DATA_CONSULTA  in (
                       select * from DIAS_MES
                    )

                    group by GP,DATA_CONSULTA
                )

                ) order by DATA_CONSULTA, DGP
                ";
        
		$dado = $con->query($sql);
        $con->commit();

		return $dado;
        
    }
    
    public static function pendente($dados) {
        $con = new _Conexao();
		
        $sql = "select * from inventario_tecido_pendente(:INV,:TIPO)";

        $paran01 = $dados['INV'];
        $paran02 = $dados['TIPO'];
            
        $args = array(
            ':INV'           => $paran01,
            ':TIPO'          => $paran02
		);
        
		$dado = $con->query($sql, $args);
        $con->commit();
        
		return $dado;

        
        
    }
    
    /**
     * Função para gravar  
     * @access public
     * @param {} $dados
     * @return array
     * @static
     */
    public static function store($dados) {
        $con = new _Conexao();
		
        $data_i = date('d.m.Y',strtotime($dados['mes_i'].'/01/'.$dados['ano_i']));
        $data_f = date('d.m.Y',strtotime("-1 days",strtotime(($dados['mes_f'] + 1).'/01/'.$dados['ano_f'].'')));
        
        $sql = "INSERT INTO TBBSC_REGISTRO (
            REGISTRO_INDICADOR_ID,
            VALOR,
            BSC_ID,
            BSC_DETALHE_ID,
            VINCULO,
            TELA,
            SUB_VINCULO,
            C_CUSTO,
            OPERADOR,
			DATA,
            DATAHORA,
            PLANACAO_QUEM,
            PLANACAO_QUANDO,
            PLANACAO_QUANDOF,
            PLANACAO_COMO,
			PLANACAO_STATUS,
            TURNO,
            OBSERVACAO,
            DATA_PLANO
            ) VALUES (
            :REGISTRO,
            :VALOR,
            :BSC_ID,
            :BSC_DETALHE_ID,
            :VINCULO,
            :TELA,
            :SUB_VINCULO,
			:CCUSTO,
			:OPERADOR,
			:DATA,
            :DATAHORA,
			:PLANACAO_QUEM,
            :PLANACAO_QUANDO,
            :PLANACAO_QUANDOF,
            :PLANACAO_COMO,
			:PLANACAO_STATUS,
            :TURNO,
            :OBSERVACAO,
            :DATAPLANO
			);";
            
            $query = $con->pdo->prepare($sql);

            $paran01 = 0;
            $paran02 = 0;
            $paran03 = $dados['class-p-a-indicador'];
            $paran05 = $dados['class-p-a-vinculo'];
			$paran06 = $dados['class-p-a-ccusto'];
			$paran07 = Auth::user()->CODIGO;
			$paran08 = date("d.m.y");
            $paran09 = date("d.m.Y H:i:s");
			$paran10 = $dados['class-p-a-quem'];
            $paran11 = date("d.m.Y",strtotime($dados['class-p-a-quandod']));
            $paran04 = date("d.m.Y",strtotime($dados['class-p-a-quandot']));
			$paran12 = $dados['class-p-a-como'];
            $paran13 = 1;
            $paran14 = $dados['class-p-a-turno']; 
            $paran15 = $dados['class-p-a-oque'];
            $paran18 = $dados['vinculo'];
            $paran19 = $dados['sub_vinc'];
            $paran20 = $dados['tela'];
            $paran21 = $data_i;
            
            $query->bindParam(':REGISTRO'         , $paran01);
            $query->bindParam(':VALOR'            , $paran02);
            $query->bindParam(':BSC_ID'           , $paran03);
            $query->bindParam(':BSC_DETALHE_ID'   , $paran05);
			$query->bindParam(':CCUSTO'           , $paran06);
			$query->bindParam(':OPERADOR'         , $paran07);
			$query->bindParam(':DATA'             , $paran08);
            $query->bindParam(':DATAHORA'         , $paran09);
			$query->bindParam(':PLANACAO_QUEM'    , $paran10);
            $query->bindParam(':PLANACAO_QUANDO'  , $paran11);
            $query->bindParam(':PLANACAO_QUANDOF' , $paran04);
			$query->bindParam(':PLANACAO_COMO'    , $paran12,PDO::PARAM_LOB);
            $query->bindParam(':PLANACAO_STATUS'  , $paran13);
            $query->bindParam(':TURNO'            , $paran14); 
            $query->bindParam(':OBSERVACAO'       , $paran15);
            $query->bindParam(':VINCULO'          , $paran18);
            $query->bindParam(':SUB_VINCULO'      , $paran19);
            $query->bindParam(':TELA'             , $paran20);
            $query->bindParam(':DATAPLANO'        , $paran21);
            
        $ret =  $query->execute();
        $con->commit();

		return $ret;
        
    }
    
    /**
     * Função para alterar  
     * @access public
     * @param {} $dados
     * @return array
     * @static
     */
    public static function alterar($dados) {
        $con = new _Conexao();
        
        $sql = "
                update TBBSC_REGISTRO set

                REGISTRO_INDICADOR_ID = :REGISTRO,
                VALOR = :VALOR ,
                BSC_ID = :BSC_ID ,
                BSC_DETALHE_ID = :BSC_DETALHE_ID ,
                VINCULO = :VINCULO,
                SUB_VINCULO = :SUB_VINCULO,
                TELA = :TELA,
                C_CUSTO = :CCUSTO ,
                OPERADOR = :OPERADOR ,
                DATA = :DATA ,
                DATAHORA = :DATAHORA ,
                PLANACAO_QUEM = :PLANACAO_QUEM ,
                PLANACAO_QUANDO = :PLANACAO_QUANDO ,
                PLANACAO_QUANDOF = :PLANACAO_QUANDOF ,
                PLANACAO_COMO = :PLANACAO_COMO ,
                PLANACAO_STATUS = :PLANACAO_STATUS ,
                TURNO = :TURNO ,
                OBSERVACAO = :OBSERVACAO

                where id = :ID
			";
            
            $query = $con->pdo->prepare($sql);
            
            
            $paran01 = 0;
            $paran02 = 0;
            $paran03 = $dados['class-p-a-indicador'];
            $paran05 = $dados['class-p-a-vinculo'];
			$paran06 = $dados['class-p-a-ccusto'];
			$paran07 = Auth::user()->CODIGO;
			$paran08 = date("d.m.y");
            $paran09 = date("d.m.Y H:i:s");
			$paran10 = $dados['class-p-a-quem'];
            $paran11 = date("d.m.Y",strtotime($dados['class-p-a-quandod']));
            $paran04 = date("d.m.Y",strtotime($dados['class-p-a-quandot']));
			$paran12 = $dados['class-p-a-como'];
            $paran13 = 1;
            $paran14 = $dados['class-p-a-turno']; 
            $paran15 = $dados['class-p-a-oque'];
            $paran17 = $dados['id'];
            $paran18 = $dados['vinculo'];
            $paran19 = $dados['sub_vinc'];
            $paran20 = $dados['tela'];
            
            $query->bindParam(':REGISTRO'         , $paran01);
            $query->bindParam(':VALOR'            , $paran02);
            $query->bindParam(':BSC_ID'           , $paran03);
            $query->bindParam(':BSC_DETALHE_ID'   , $paran05);
			$query->bindParam(':CCUSTO'           , $paran06);
			$query->bindParam(':OPERADOR'         , $paran07);
			$query->bindParam(':DATA'             , $paran08);
            $query->bindParam(':DATAHORA'         , $paran09);
			$query->bindParam(':PLANACAO_QUEM'    , $paran10);
            $query->bindParam(':PLANACAO_QUANDO'  , $paran11);
            $query->bindParam(':PLANACAO_QUANDOF' , $paran04);
			$query->bindParam(':PLANACAO_COMO'    , $paran12,PDO::PARAM_LOB);
            $query->bindParam(':PLANACAO_STATUS'  , $paran13);
            $query->bindParam(':TURNO'            , $paran14); 
            $query->bindParam(':OBSERVACAO'       , $paran15);
            $query->bindParam(':ID'               , $paran17);
            $query->bindParam(':VINCULO'          , $paran18);
            $query->bindParam(':SUB_VINCULO'      , $paran19);
            $query->bindParam(':TELA'             , $paran20);
        
        $ret =  $query->execute();
        $con->commit();

		return $ret;
        
    }
    
    /**
     * Lista de registros filtrador por perfil, vinculo e ccusto  
     * @access public
     * @param {} $dados
     * @return array
     * @static
     */
    public static function getListaPA($dados) {
        $con = new _Conexao();
        
        $data_i = date('d.m.Y',strtotime($dados['mes_i'].'/01/'.$dados['ano_i']));
        $data_f = date('d.m.Y',strtotime("-1 days",strtotime(($dados['mes_f'] + 1).'/01/'.$dados['ano_f'].'')));

        $sql = "
                SELECT R.* FROM TBBSC_REGISTRO R,tbcontrole_n n
                WHERE r.bsc_id = n.valor_ext
                AND R.VINCULO = :VINCULO
                AND R.TELA = :TELA
                AND R.C_CUSTO = :CCUSTOS
                and N.id = :CONTROLE
                and r.DATA_PLANO between '".$data_i."' and '".$data_f."'
                ";
        
		$args = array(
            ':CONTROLE'     => $dados['controlen'],
            ':CCUSTOS'      => $dados['ccusto'],
            ':VINCULO'      => $dados['vinculo'],
            ':TELA'         => $dados['tela']
		);
        
		$dado = $con->query($sql, $args);
        $con->commit();
        
        return $dado;
    }
    
    /**
     * Lista um plano de ação  
     * @access public
     * @param {} $dados
     * @return array
     * @static
     */
    public static function showitem($dados) {
        $con = new _Conexao();
		
        $sql = "SELECT * FROM TBBSC_REGISTRO R WHERE R.ID = :ID";
		
		$args = array(
            ':ID' => $dados['id']
		);
        
		$dado = $con->query($sql, $args);
        $con->commit();
        
        return $dado;
    }
    
    /**
     * Excluir um plano de ação  
     * @access public
     * @param {} $dados
     * @return array
     * @static
     */
    public static function excluir($dados) {
        $con = new _Conexao();
		
        $sql = "DELETE FROM TBBSC_REGISTRO R WHERE R.ID = :ID";
		
		$args = array(
            ':ID' => $dados['id']
		);
        
		$dado = $con->query($sql, $args);
        $con->commit();
        
        return $dado;
    }
      
}
