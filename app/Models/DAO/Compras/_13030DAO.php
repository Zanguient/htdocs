<?php
namespace App\Models\DAO\Compras;

use App\Helpers\Helpers;
use App\Models\Conexao\_Conexao;
use App\Models\DTO\Compras\_13030;
use Illuminate\Support\Facades\Auth;
use Exception;

class _13030DAO
{
    
    public static function selectCota( $param, _Conexao $con = null ) {
        
        $sql = "
            SELECT
                C.ID,
                C.CCUSTO,
                FN_CCUSTO_MASK(C.CCUSTO) CCUSTO_MASK,
                FN_CCUSTO_DESCRICAO(C.CCUSTO) CCUSTO_DESCRICAO,
                C.CONTACONTABIL CCONTABIL,
                FN_CCONTABIL_MASK(C.CONTACONTABIL) CCONTABIL_MASK,
                UPPER((SELECT FIRST 1 DESCRICAO FROM TBCONTACONTABIL WHERE CONTA = C.CONTACONTABIL)) CCONTABIL_DESCRICAO,
                C.MES,
                C.ANO,
                TRIM(FN_MES_DESCRICAO(C.MES) || '/' || C.ANO) PERIODO_DESCRICAO,
                C.VALOR,
                C.EXTRA,
                (C.VALOR+C.EXTRA) TOTAL,
                C.OUTROS,
                (C.VALOR+C.EXTRA-C.OUTROS-C.SALDO) UTIL,
                IIF(C.VALOR+C.EXTRA > 0, ((1-(C.SALDO/(C.VALOR+C.EXTRA)))*100), IIF(C.VALOR+C.EXTRA = 0 AND C.SALDO < 0, 100, 0))  PERC_UTIL,
                C.SALDO,
                COALESCE(C.DESTAQUE,'0')DESTAQUE,
                COALESCE(C.TOTALIZA,'0')TOTALIZA,
                TRIM('COTA') TIPO
            FROM
                TBCCUSTO_COTA C,
                TBUSUARIO_CCUSTO UC

            WHERE TRUE
            AND C.STATUSEXCLUSAO = '0'
            AND CAST((C.ANO || '.' || C.MES || '.01') AS DATE) BETWEEN :DATA_1 AND :DATA_2     
            AND C.CCUSTO = UC.CCUSTO
            AND UC.USUARIO_ID = :USUARIO_ID            
        ";
        
        $args = [
            
        ];
        
        return $con->query($sql,$args);
    }

    /**
	 * Similar ao CREATE (CRIAR) do CRUD
	 * @param _13030 $obj
	 * @return array
	 */
	public static function gravar(_13030 $obj)
	{
		$con = new _Conexao();
		try
		{
            if ( Helpers::objCount($obj) > 0 ) {

            	$i = -1;
                foreach ($obj->getId() as $prod_id) {
                	$i++;
                    self::gravarCota($con, $obj, $i);
                }
            }else{
            	log_erro('NÃO HÁ REGISTROS À GRAVAR');
            }

            $con->commit();
            
        } catch (Exception $e) {
        	$con->rollback(); 
        	throw $e;
        }
	}
	
	/**
	 * Similar ao RETRIEVE (CONSULTAR) do CRUD
	 * Select da página inicial.
	 * @return array
	 */
	public static function listar($param = array()) 
    {
		$con = new _Conexao();
        
        if ( isset($param->DADOS) ) {
            $ret = [];
            
            if ( isset($param->DADOS->COTA) ) {
                $ret['cota'] = self::exibirCotas($con,$param);
            }
            
            if ( isset($param->DADOS->ITEM) ) {
                $ret['item'] = self::exibirCotas      ($con,$param);
            }
            
            if ( isset($param->DADOS->FAT) ) {

                if ($param->COTA_FATURAMENTO == '1') {
                    $ret['fat'] = self::exibirFaturamento($con, $param);
                } else {
                    $ret['fat'] = [];
                }                   
            }
//            
//            $ret = isset($param->DADOS->COTA) ? $ret+array('cota' => self::exibirCotas      ($con,$param)) : $ret;
//            $ret = isset($param->DADOS->ITEM) ? $ret+array('item' => self::exibirCotaItem   ($con,$param)) : $ret;
//            $ret = isset($param->DADOS->FAT)  ? $ret+array('fat'  => self::exibirFaturamento($con,$param)) : $ret;
        } else {
            
            $ret = [];
            
            $ret['cota'] = self::exibirCotas($con,$param);
            $ret['item'] = $param->COTA_GGF ? self::exibirCotaItem($con,$param) : [];
            
            if ($param->COTA_FATURAMENTO == '1') {
                $ret['fat'] = self::exibirFaturamento($con, $param);
            } else {
                $ret['fat'] = [];
            }                
        }

		return $ret;
	}    

	/**
	 * Similar ao UPDATE (ATUALIZAR) do CRUD
	 * Atualiza dados do objeto na base de dados.
	 *
	 * @param _13030 $obj
	 * @return array
	 */
	public static function alterar(_13030 $obj)
	{
        $con = new _Conexao();
        try {
        	
        	//Executa a alteração da cota
        	self::alterarCota($con, $obj);

        	//Executa a gravação da cota extra
            if ( Helpers::objCount($obj->getCotaExtraAdd()) > 0 ) {
                    
            	$i = -1;
                foreach ( $obj->getCotaExtraAdd()['valor'] as $extra_add ) {
                	$i++;
                    self::gravarCotaExtra($con, $obj, $i);
                }
            }
            
            //Executa a exclusão da cota extra
            if ( Helpers::objCount($obj->getCotaExtraDel()) > 0 ) {
                
            	$i = -1;
                foreach ( $obj->getCotaExtraDel() as $extra_del ) {
                	$i++;
                	self::excluirCotaExtra($con, $obj, $i);
                }
            }
            
            //Executa a gravação da cota outros
            if ( Helpers::objCount($obj->getCotaOutroAdd()) > 0 ) {
            
            	$i = -1;
            	foreach ( $obj->getCotaOutroAdd()['valor'] as $outro_add ) {
            		$i++;
            		self::gravarCotaOutro($con, $obj, $i);
            	}
            }
            
            //Executa a exclusão da cota outros
            if ( Helpers::objCount($obj->getCotaOutroDel()) > 0 ) {
            
            	$i = -1;
            	foreach ( $obj->getCotaOutroDel() as $outro_del ) {
            		$i++;
            		self::excluirCotaOutro($con, $obj, $i);
            	}
            }            
            
            $con->commit();
            
        } catch (Exception $e) {
			$con->rollback();
			throw $e;
		}
	}

	/**
	 * Similar ao DESTROY (EXCLUIR) do CRUD
	 * Exclui dados do objeto na base de dados.
	 * @param int $id
	 * @return array
	 */
	public static function excluir($id)
	{
		$con = new _Conexao();
		try {
			self::excluirCota($con, $id);
			
		    $con->commit();
		    
        } catch (Exception $e) {
        	$con->rollback(); 
        	throw $e;
        }
	}

	/**
	 * Similar ao SHOW do LARAVEL
	 * Retorna dados do objeto na base de dados.
	 * @param int $id
	 * @return array
	 */
	public static function exibir($id)
	{
		$con = new _Conexao();

		$cota 			= self::exibirItem($con, $id);		
		$cota_detalhe   = self::exibirItemDetalhe($con, $cota);
		$cota_extra		= self::exibirCotaExtra($con, $id);
		$cota_outro		= self::exibirCotaOutro($con, $id);
		
		return array(
			'cota'			=> $cota[0],
			'cota_itens' 	=> $cota_detalhe,
			'cota_extra'	=> $cota_extra,
			'cota_outro'	=> $cota_outro
		);
		
	}

	/**
	 * @param $con
	 * @param $obj
	 * @param $i
	 */
	public static function gravarCota(_Conexao $con, _13030 $obj, $i){
		
		$sql = /** @lang text */
		"
			INSERT INTO TBCCUSTO_COTA (
				ID,
				CCUSTO,
				CONTACONTABIL,
				MES,
				ANO,
				BLOQUEIO,
				NOTIFICACAO,
				DESTAQUE,
				TOTALIZA,
				VALOR
			) VALUES (
				:ID,
				:CCUSTO,
				:CONTACONTABIL,
				:MES,
				:ANO,
				:BLOQUEIO,
				:NOTIFICACAO,
				:DESTAQUE,
				:TOTALIZA,
				:VALOR
			)
		";

		$args = array(
			':ID'			 => $obj->getId()	      [$i],
			':CCUSTO'		 => $obj->getCcusto()	  [$i],
			':CONTACONTABIL' => $obj->getConta()	  [$i],
			':MES'			 => $obj->getMes()		  [$i],
			':ANO'			 => $obj->getAno()	 	  [$i],
			':BLOQUEIO'		 => $obj->getBloqueio()	  [$i],
			':NOTIFICACAO'	 => $obj->getNotificacao()[$i],
			':DESTAQUE'		 => $obj->getDestaque()	  [$i],
			':TOTALIZA'		 => $obj->getTotaliza()	  [$i],
			':VALOR'		 => $obj->getValor() 	  [$i]
		);

		$con->execute($sql,$args);
	}      

	/**
	 * @param $con
	 * @param $obj
	 */
	public static function alterarCota(_Conexao $con, _13030 $obj){

	    $sql = /** @lang DAO */
	    "
	        UPDATE TBCCUSTO_COTA
	        SET VALOR 		     = :VALOR,
	    		BLOQUEIO 	     = :BLOQUEIO,
	    		NOTIFICACAO      = :NOTIFICACAO,
	    		DESTAQUE	     = :DESTAQUE,
	    		TOTALIZA	     = :TOTALIZA,
	    		OBSERVACAO_GERAL = :OBSERVACAO_GERAL
	        WHERE
	            CCUSTO        = :CCUSTO
	        AND CONTACONTABIL = :CONTA
	        AND MES           = :MES
	        AND ANO           = :ANO
	        AND ID            = :ID
	    ";

		$args = array(
            ':ID'               => $obj->getId()    	     [0],
            ':CCUSTO'   	    => $obj->getCcusto()	     [0],
            ':CONTA'            => $obj->getConta() 	     [0],
            ':MES'              => $obj->getMes()   	     [0],
            ':ANO'              => $obj->getAno()   	     [0],
			':VALOR'            => $obj->getValor() 	     [0],
			':BLOQUEIO'         => $obj->getBloqueio() 	     [0],
			':NOTIFICACAO'      => $obj->getNotificacao()    [0],
			':DESTAQUE'  	    => $obj->getDestaque()	     [0],
			':TOTALIZA'  	    => $obj->getTotaliza()	     [0],
			':OBSERVACAO_GERAL' => $obj->getObservacaoGeral()[0]
		);

		$con->execute($sql, $args);
	}

	/**
	 * 
	 * @param _Conexao $con
	 * @param int $id
	 */
	public static function excluirCota(_Conexao $con, $id){
		
		$sql = /** @lang DAO */
		"
        	UPDATE TBCCUSTO_COTA SET STATUSEXCLUSAO = '1' WHERE ID = :ID
    	";

		$args = array(
			':ID'	=> $id
		);

		$con->execute($sql, $args);
	}

	/**
	 * @param $con
	 * @param $obj
	 * @param $i
	 */
	public static function gravarCotaExtra(_Conexao $con, _13030 $obj, $i){
	
		$sql = /** @lang text */
		"
            INSERT INTO TBCCUSTO_COTA_EXTRA (
                CCUSTO_COTA_ID,
                USUARIO_ID,
                VALOR,
                OBSERVACAO
            ) VALUES (
                :CCUSTO_COTA_ID,
                :USUARIO_ID,
                :VALOR,
                :OBSERVACAO
            )
		";
	
		$args = array(
			':CCUSTO_COTA_ID'   => $obj->getId()[0],
			':USUARIO_ID'       => Auth::user()->CODIGO,
			':VALOR'            => $obj->getCotaExtraAdd()['valor'][$i],
			':OBSERVACAO'       => $obj->getCotaExtraAdd()['obs'][$i]
		);
	
		$con->execute($sql,$args);
	}	
	
	/**
	 * 
	 * @param _Conexao $con
	 * @param _13030 $obj
	 * @param int $i
	 */
	public static function excluirCotaExtra(_Conexao $con, _13030 $obj, $i){
		$sql = /** @lang DAO */
		"
        	UPDATE TBCCUSTO_COTA_EXTRA SET STATUSEXCLUSAO = '1' WHERE ID = :ID
    	";
	
		$args = array(
				':ID'	=> $obj->getCotaExtraDel()[$i]
		);
	
		$con->execute($sql, $args);
	}
	
	/**
	 * 
	 * @param _Conexao $con
	 * @param _13030 $obj
	 * @param int $i
	 */
	public static function gravarCotaOutro(_Conexao $con, _13030 $obj, $i){
	
		$sql = /** @lang text */
		"
            INSERT INTO TBCCUSTO_COTA_OUTROS (
                CCUSTO_COTA_ID,
                USUARIO_ID,
                VALOR,
                OBSERVACAO
            ) VALUES (
                :CCUSTO_COTA_ID,
                :USUARIO_ID,
                :VALOR,
                :OBSERVACAO
            )
		";
	
		$args = array(
				':CCUSTO_COTA_ID'   => $obj->getId()[0],
				':USUARIO_ID'       => Auth::user()->CODIGO,
				':VALOR'            => $obj->getCotaOutroAdd()['valor'][$i],
				':OBSERVACAO'       => $obj->getCotaOutroAdd()['obs'][$i]
		);
	
		$con->execute($sql,$args);
	}	

	/**
	 * 
	 * @param _Conexao $con
	 * @param _13030 $obj
	 * @param unknown $i
	 */
	public static function excluirCotaOutro(_Conexao $con, _13030 $obj, $i){
		$sql = /** @lang DAO */
		"
        	UPDATE TBCCUSTO_COTA_OUTROS SET STATUSEXCLUSAO = '1' WHERE ID = :ID
    	";
	
		$args = array(
				':ID'	=> $obj->getCotaOutroDel()[$i]
		);
	
		$con->execute($sql, $args);
	}

	/**
	 * @param _Conexao $con (_Conexao)
	 * @return mixed $con->query()
	 * @throws Exception
	 */
	public static function exibirCotas( _Conexao $con, $param)
    {
		$filtro = isset($param->FILTRO) ? "AND BUSCA LIKE '" . $param->FILTRO . "'" : '';

        $cotas = '';
		if	   ( $param->COTA_ZERADA && $param->COTA_VALIDA ) $cotas = 'AND (A.VALOR > 0 OR A.VALOR = 0 )';
		elseif ( $param->COTA_ZERADA )                        $cotas = 'AND A.VALOR = 0';
		elseif ( $param->COTA_VALIDA )                        $cotas = 'AND A.VALOR > 0';
        
		$sql =
		"
            SELECT
                ID,
                CCUSTO,
                CCUSTO_MASK,
                CCUSTO_DESCRICAO,
                MES,
                ANO,
                PERIODO_DESCRICAO,
                CCONTABIL,
                CCONTABIL_MASK,
                CCONTABIL_DESCRICAO,
                DESTAQUE,
                TOTALIZA,
                FILTRO,
                VALOR,
                EXTRA,
                TOTAL,
                OUTROS,
                UTIL,
                PERC_UTIL,
                SALDO,
                TIPO

            FROM (
                SELECT
                    Y.ID,
                    Y.CCUSTO,
                    Y.CCUSTO_MASK,
                    Y.CCUSTO_DESCRICAO,
                    Y.MES,
                    Y.ANO,
                    Y.PERIODO_DESCRICAO,
                    Y.CCONTABIL,  
                    Y.CCONTABIL_MASK,  
                    Y.CCONTABIL_DESCRICAO,
                    Y.DESTAQUE,
                    Y.TOTALIZA,
                    Y.FILTRO,
                    Y.VALOR,
                    Y.EXTRA,
                    Y.TOTAL,
                    Y.OUTROS,
                    Y.UTIL,
                    Y.PERC_UTIL,
                    Y.SALDO,
                    TRIM('COTA') TIPO

                FROM
                   (SELECT
                        X.ID,
                        X.CCUSTO,
                        X.CCUSTO_MASK,
                        X.CCUSTO_DESCRICAO,
                        X.MES,
                        X.ANO,
                        X.PERIODO_DESCRICAO,
                        X.CCONTABIL,    
                        X.CCONTABIL_MASK,   
                        X.CCONTABIL_DESCRICAO,
                        X.DESTAQUE,
                        X.TOTALIZA,
                        X.FILTRO,
                        X.VALOR,
                        X.EXTRA,
                        X.TOTAL,
                        X.OUTROS,
                        X.UTIL,
                        X.PERC_UTIL,
                        X.SALDO,
                        (
                        X.CCUSTO              || ' ' ||
                        X.CCUSTO_MASK         || ' ' ||
                        X.CCUSTO_DESCRICAO    || ' ' ||
                        X.PERIODO_DESCRICAO   || ' ' ||
                        X.CCONTABIL           || ' ' ||
                        X.CCONTABIL_DESCRICAO || ' ' ||
                        X.CCONTABIL_MASK      || ' '
                        ) BUSCA
                    FROM
                        (SELECT
                            A.ID,

                            COALESCE(A.DESTAQUE,'0')DESTAQUE,
                            COALESCE(A.TOTALIZA,'0')TOTALIZA,
                            0 FILTRO,

                            C.CODIGO CCUSTO,
                            IIF(char_length(C.CODIGO)=2,C.CODIGO,
                            IIF(char_length(C.CODIGO)=5,Substring(C.CODIGO From 1 For 2)||'.'||Substring(C.CODIGO From 3 For 3),
                            IIF(char_length(C.CODIGO)=8,Substring(C.CODIGO From 1 For 2)||'.'||Substring(C.CODIGO From 3 For 3)||'.'||Substring(C.CODIGO From 6 For 3),''))) CCUSTO_MASK,
                            UPPER(IIF(char_length(C.CODIGO)=2,C.DESCRICAO,
                                  IIF(char_length(C.CODIGO)=5,(Select First 1 U.Descricao From TbCentro_de_Custo U Where U.CODIGO = SUBSTRING(C.CODIGO FROM 1 FOR 2))||' - '||C.DESCRICAO,
                                  IIF(char_length(C.CODIGO)=8,(Select First 1 U.Descricao From TbCentro_de_Custo U Where U.CODIGO = SUBSTRING(C.CODIGO FROM 1 FOR 5))||' - '||C.DESCRICAO,'')))) CCUSTO_DESCRICAO,

                            A.MES,
                            A.ANO,
                            (CASE WHEN A.MES =  1 THEN 'JAN/' || A.ANO
                                  WHEN A.MES =  2 THEN 'FEV/' || A.ANO
                                  WHEN A.MES =  3 THEN 'MAR/' || A.ANO
                                  WHEN A.MES =  4 THEN 'ABR/' || A.ANO
                                  WHEN A.MES =  5 THEN 'MAI/' || A.ANO
                                  WHEN A.MES =  6 THEN 'JUN/' || A.ANO
                                  WHEN A.MES =  7 THEN 'JUL/' || A.ANO
                                  WHEN A.MES =  8 THEN 'AGO/' || A.ANO
                                  WHEN A.MES =  9 THEN 'SET/' || A.ANO
                                  WHEN A.MES = 10 THEN 'OUT/' || A.ANO
                                  WHEN A.MES = 11 THEN 'NOV/' || A.ANO
                                  WHEN A.MES = 12 THEN 'DEZ/' || A.ANO
                                  ELSE '#N/D' END)PERIODO_DESCRICAO,

                            B.CONTA             CCONTABIL,
                            UPPER(B.DESCRICAO)  CCONTABIL_DESCRICAO,
                            IIF(char_length(B.CONTA)= 1,B.CONTA,
                            IIF(char_length(B.CONTA)= 2,Substring(B.CONTA From 1 For 1)||'.'||Substring(B.CONTA From 2 For 1),
                            IIF(char_length(B.CONTA)= 4,Substring(B.CONTA From 1 For 1)||'.'||Substring(B.CONTA From 2 For 1)||'.'||Substring(B.CONTA From 3 For 2),
                            IIF(char_length(B.CONTA)= 7,Substring(B.CONTA From 1 For 1)||'.'||Substring(B.CONTA From 2 For 1)||'.'||Substring(B.CONTA From 3 For 2)||'.'||Substring(B.CONTA From 5 For 3),
                            IIF(char_length(B.CONTA)=11,Substring(B.CONTA From 1 For 1)||'.'||Substring(B.CONTA From 2 For 1)||'.'||Substring(B.CONTA From 3 For 2)||'.'||Substring(B.CONTA From 5 For 3)||'.'||Substring(B.CONTA From 8 For 4),''))))) CCONTABIL_MASK,

                            A.VALOR,
                            A.EXTRA,
                            (A.VALOR+A.EXTRA) TOTAL,
                            A.OUTROS,
                            (A.VALOR+A.EXTRA-A.OUTROS-A.SALDO)UTIL,
                            IIF(A.VALOR+A.EXTRA > 0, ((1-(A.SALDO/(A.VALOR+A.EXTRA)))*100),
                            IIF(A.VALOR+A.EXTRA = 0 AND A.SALDO < 0, 100, 0))  PERC_UTIL,
                            A.SALDO

                        FROM
                            TBCCUSTO_COTA A,
                            TBCONTACONTABIL B,
                            TBCENTRO_DE_CUSTO C,
                            TBUSUARIO_CCUSTO D

                        WHERE             
                            A.STATUSEXCLUSAO = '0'
                        AND B.CONTA = A.CONTACONTABIL
                        AND A.CCUSTO = C.CODIGO
                        AND C.CODIGO = D.CCUSTO
                        AND D.USUARIO_ID = :USUARIO_ID
                        AND CAST((A.ANO || '.' || A.MES || '.01') AS DATE) BETWEEN :DATA_1 AND :DATA_2
                        /*@COTA_TIPO*/
                        )X)Y
                WHERE
                    1 = 1
                    /*@FILTRO*/
                    ";
        

		$args = array(
            ':DATA_1' 		=> $param->DATA_1,
            ':DATA_2'		=> $param->DATA_2,
            ':USUARIO_ID'	=> Auth::user()->CODIGO,
            '@FILTRO'       => $filtro,
            '@COTA_TIPO'    => $cotas           
		);
        
            
        if ( $param->COTA_GGF == '1' ) {

            $sql .= "
                    UNION

                    SELECT

                        99999 || MES || ANO || CCUSTO   ID,
                        CCUSTO,
                        CCUSTO_MASK,
                        CCUSTO_DESCRICAO,
                        MES,
                        ANO,
                        PERIODO_DESCRICAO,
                        CCONTABIL,
                        CCONTABIL_MASK,
                        CCONTABIL_DESCRICAO,
                        DESTAQUE,
                        TOTALIZA,
                        FILTRO,
                        VALOR,
                        EXTRA,
                        VALOR TOTAL,
                        0 OUTROS,
                        UTIL,
                        PERC_UTIL,
                        SALDO,
                        TRIM('GGF') TIPO
                    FROM (

                        SELECT
                            W.*,
                            CAST(SUBSTRING(GGF FROM   1 FOR 30) AS NUMERIC(15,4))VALOR,
                            CAST(SUBSTRING(GGF FROM  31 FOR 30) AS NUMERIC(15,4)) UTIL,
                            CAST(SUBSTRING(GGF FROM  61 FOR 30) AS NUMERIC(15,4)) PERC_UTIL,
                            CAST(SUBSTRING(GGF FROM  91 FOR 30) AS NUMERIC(15,4)) SALDO,
                            CAST(SUBSTRING(GGF FROM 121 FOR 30) AS NUMERIC(15,4)) EXTRA
                        FROM (
                            SELECT
                                (SELECT
                                    LPAD(SUM(VALOR_COTA),30)||
                                    LPAD(SUM(VALOR_UTILIZADO),30)||
                                    LPAD(SUM(PERCENTUAL_UTILIZADO),30)||
                                    LPAD(SUM(SALDO),30)||
                                    LPAD(SUM(VALOR_CREDITO),30)
                                FROM SPC_COTA_GGF(CCUSTO,CAST(ANO||'.'||MES||'.01' AS DATE),(DATEADD(1 MONTH TO CAST(ANO||'.'||MES||'.01' AS DATE))-1))) GGF,
                                Z.*
                            FROM (

                                SELECT DISTINCT
                                    Y.CCUSTO,
                                    Y.CCUSTO_MASK,
                                    Y.CCUSTO_DESCRICAO,
                                    Y.MES,
                                    Y.ANO,
                                    Y.PERIODO_DESCRICAO,
                                    Y.CCONTABIL,  
                                    Y.CCONTABIL_MASK,  
                                    Y.CCONTABIL_DESCRICAO,
                                    Y.DESTAQUE,
                                    Y.TOTALIZA,
                                    Y.FILTRO

                                FROM
                                   (SELECT
                                        X.ID,
                                        X.CCUSTO,
                                        X.CCUSTO_MASK,
                                        X.CCUSTO_DESCRICAO,
                                        X.MES,
                                        X.ANO,
                                        X.PERIODO_DESCRICAO,
                                        X.CCONTABIL,    
                                        X.CCONTABIL_MASK,   
                                        X.CCONTABIL_DESCRICAO,
                                        X.DESTAQUE,
                                        X.TOTALIZA,
                                        X.FILTRO,
                                        (
                                        X.CCUSTO              || ' ' ||
                                        X.CCUSTO_MASK         || ' ' ||
                                        X.CCUSTO_DESCRICAO    || ' ' ||
                                        X.PERIODO_DESCRICAO   || ' ' ||
                                        X.CCONTABIL           || ' ' ||
                                        X.CCONTABIL_DESCRICAO || ' ' ||
                                        X.CCONTABIL_MASK      || ' '
                                        ) BUSCA
                                    FROM
                                        (SELECT
                                            A.ID,

                                            0 DESTAQUE,
                                            0 TOTALIZA,
                                            0 FILTRO,

                                            C.CODIGO CCUSTO,
                                            IIF(char_length(C.CODIGO)=2,C.CODIGO,
                                            IIF(char_length(C.CODIGO)=5,Substring(C.CODIGO From 1 For 2)||'.'||Substring(C.CODIGO From 3 For 3),
                                            IIF(char_length(C.CODIGO)=8,Substring(C.CODIGO From 1 For 2)||'.'||Substring(C.CODIGO From 3 For 3)||'.'||Substring(C.CODIGO From 6 For 3),''))) CCUSTO_MASK,
                                            UPPER(IIF(char_length(C.CODIGO)=2,C.DESCRICAO,
                                                  IIF(char_length(C.CODIGO)=5,(Select First 1 U.Descricao From TbCentro_de_Custo U Where U.CODIGO = SUBSTRING(C.CODIGO FROM 1 FOR 2))||' - '||C.DESCRICAO,
                                                  IIF(char_length(C.CODIGO)=8,(Select First 1 U.Descricao From TbCentro_de_Custo U Where U.CODIGO = SUBSTRING(C.CODIGO FROM 1 FOR 5))||' - '||C.DESCRICAO,'')))) CCUSTO_DESCRICAO,

                                            A.MES,
                                            A.ANO,
                                            (CASE WHEN A.MES =  1 THEN 'JAN/' || A.ANO
                                                  WHEN A.MES =  2 THEN 'FEV/' || A.ANO
                                                  WHEN A.MES =  3 THEN 'MAR/' || A.ANO
                                                  WHEN A.MES =  4 THEN 'ABR/' || A.ANO
                                                  WHEN A.MES =  5 THEN 'MAI/' || A.ANO
                                                  WHEN A.MES =  6 THEN 'JUN/' || A.ANO
                                                  WHEN A.MES =  7 THEN 'JUL/' || A.ANO
                                                  WHEN A.MES =  8 THEN 'AGO/' || A.ANO
                                                  WHEN A.MES =  9 THEN 'SET/' || A.ANO
                                                  WHEN A.MES = 10 THEN 'OUT/' || A.ANO
                                                  WHEN A.MES = 11 THEN 'NOV/' || A.ANO
                                                  WHEN A.MES = 12 THEN 'DEZ/' || A.ANO
                                                  ELSE '#N/D' END)PERIODO_DESCRICAO,

                                            99999999999999 CCONTABIL,
                                            'G.G.F./G.G.A.' CCONTABIL_DESCRICAO,
                                            '' CCONTABIL_MASK

                                        FROM
                                            TBCCUSTO_COTA A,
                                            TBCONTACONTABIL B,
                                            TBCENTRO_DE_CUSTO C,
                                            TBUSUARIO_CCUSTO D

                                        WHERE             
                                            A.STATUSEXCLUSAO = '0'
                                        AND B.CONTA = A.CONTACONTABIL
                                        AND A.CCUSTO = C.CODIGO
                                        AND C.CODIGO = D.CCUSTO
                                        AND D.USUARIO_ID = :USUARIO_IDX
                                        AND CAST((A.ANO || '.' || A.MES || '.01') AS DATE) BETWEEN :DATA_1X AND :DATA_2X
                                        /*@COTA_TIPO*/
                                        )X)Y
                                WHERE
                                    1 = 1
                                    /*@FILTRO*/

                                ORDER BY
                                    CCUSTO,
                                    ANO,
                                    MES,
                                    CCONTABIL
                                )Z
                            )W
                        )K

            ";



            $args[':DATA_1X' 	] = $param->DATA_1;
            $args[':DATA_2X'	] = $param->DATA_2;
            $args[':USUARIO_IDX'] = Auth::user()->CODIGO;
        }

        if ( isset($param->COTA_INV) && $param->COTA_INV == '1' ) {

            $sql .= "
                UNION

                SELECT
                    ID,
                    CCUSTO,
                    CCUSTO_MASK,
                    CCUSTO_DESCRICAO,
                    MES,
                    ANO,
                    PERIODO_DESCRICAO,
                    CCONTABIL,
                    CCONTABIL_MASK,
                    CCONTABIL_DESCRICAO,
                    DESTAQUE,
                    TOTALIZA,
                    '' FILTRO,
                    VALOR,
                    EXTRA,
                    TOTAL,
                    OUTROS,
                    UTIL,
                    PERC_UTIL,
                    SALDO,
                    TIPO
                FROM (
                    SELECT
                    ID,
                    CCUSTO,
                    FN_CCUSTO_MASK(Y.CCUSTO) CCUSTO_MASK,
                    FN_CCUSTO_DESCRICAO(Y.CCUSTO) CCUSTO_DESCRICAO,
                    MES,
                    ANO,
                            TRIM(FN_MES_DESCRICAO(Y.MES)||'/'||Y.ANO) PERIODO_DESCRICAO,
                    999999999999999 CCONTABIL,
                    '' CCONTABIL_MASK,
                    'AJUSTES DE INVENTÁRIO' CCONTABIL_DESCRICAO,
                    0 DESTAQUE,
                    0 TOTALIZA,
                    0 NOTIFICA,
                    0 BLOQUEIA,
                    0 VALOR,
                    0 EXTRA,
                    0 TOTAL,
                    0 OUTROS,
                    UTIL,
                    UTIL / SUM(UTIL) OVER (PARTITION BY MES,ANO) * 100 PERC_UTIL,
                    0 SALDO,
                    'INV' TIPO,
                    '' OBSERVACAO_GERAL


                    FROM (
                        SELECT
                            999999999 || MES || ANO || CCUSTO ID,
                            X.CCUSTO,
                            X.ANO,
                            X.MES,
                            CAST(SUM(UTIL) AS NUMERIC(15,2)) UTIL
                        FROM (
                            SELECT
                                CC.CCUSTO_CONTABILIZACAO CCUSTO,
                                EXTRACT (YEAR FROM A.DATA) ANO,
                                EXTRACT (MONTH FROM A.DATA) MES,
                                A.QUANTIDADE *
                                    (SELECT FIRST 1 S.CUSTO_MEDIO
                                       FROM TBESTOQUE_SALDO_DIARIO S
                                      WHERE S.ESTABELECIMENTO_CODIGO = A.ESTABELECIMENTO_CODIGO
                                        AND S.LOCALIZACAO_CODIGO     = A.LOCALIZACAO_CODIGO
                                        AND S.PRODUTO_CODIGO         = A.PRODUTO_CODIGO
                                        AND S.DATA = A.DATA) * (IIF(A.TIPO='E',-1.000,1.000)) UTIL
                            FROM
                                TBESTOQUE_TRANSACAO_ITEM A,
                                TBPRODUTO P,
                                TBOPERACAO O,
                                TBCENTRO_DE_CUSTO CC,
                                TBUSUARIO_CCUSTO US

                            WHERE
                                A.PRODUTO_CODIGO   = P.CODIGO
                            AND A.OPERACAO_CODIGO  = O.CODIGO
                            AND O.ACERTO           = '1'
                            AND P.INVENTARIO       = '1'
                            AND A.CENTRO_DE_CUSTO  = CC.CODIGO
                            AND CC.CCUSTO_CONTABILIZACAO = US.CCUSTO
                            AND US.USUARIO_ID = COALESCE(:USUARIO_IDZ,5) /* HAROLDO */
                            AND A.DATA BETWEEN :DATA_1Z AND :DATA_2Z

                            ) X
                        GROUP BY X.CCUSTO, X.ANO, X.MES
                    ) Y

                    ) Z  

            ";



            $args[':DATA_1Z' 	] = $param->DATA_1;
            $args[':DATA_2Z'	] = $param->DATA_2;
            $args[':USUARIO_IDZ'] = Auth::user()->CODIGO;
        }

        

        
        $sql .= "

                    )J

                ORDER BY  
                    CCUSTO,
                    ANO,
                    MES,
                    CCONTABIL                
            ";
        

		return $con->query($sql, $args);	
	}
	
	/**
	 * @param _Conexao $con (_Conexao)
	 * @return mixed $con->query()
	 * @throws Exception
	 */
	public static function exibirCotaItem(_Conexao $con, $param)
    {
//		$filtro = isset($param->FILTRO) ? '%' . str_replace(' ', '%', $param->FILTRO) . '%' : '%';
		$filtro = '%';

        $cotas = '';
		if	   ( $param->COTA_ZERADA && $param->COTA_VALIDA ) $cotas = 'AND (B.VALOR > 0 OR B.VALOR = 0 )';
		elseif ( $param->COTA_ZERADA )                        $cotas = 'AND B.VALOR = 0';
		elseif ( $param->COTA_VALIDA )                        $cotas = 'AND B.VALOR > 0';
	
		$sql = /** @lang text */
		"
            SELECT
                ID,
                CCUSTO,
                CCONTABIL,
                MES,
                ANO,
                DESCRICAO,
                VALOR,
                DESCONTO_IMPOSTO,
                TRIM(NATUREZA) NATUREZA,
                DATA,
                FILTRO,
                VALOR_COTA,
                VALOR_UTILIZADO,
                PERCENTUAL_UTILIZADO,
                SALDO,
                FAMILIA_ID,
                VALOR_CREDITO

            FROM (
                SELECT
                    0 ID,      
                    CCUSTO,
                    99999999999999 CCONTABIL,
                    MES,
                    ANO,         
                    DESCRICAO,
                    0 VALOR,
                    0 DESCONTO_IMPOSTO,
                    'G' NATUREZA,
                    NULL DATA,
                    0 FILTRO,
                    VALOR_COTA,
                    VALOR_UTILIZADO,
                    PERCENTUAL_UTILIZADO,
                    SALDO,
                    FAMILIA_ID,
                    VALOR_CREDITO

                FROM (SELECT * FROM SPC_COTA_GGF(NULL,:DATA_1X,:DATA_2X)) W
            ) Y
            ORDER BY MES, ANO, CCUSTO, CCONTABIL, DESCRICAO
		";
	
//		$sql = str_replace('/*COTAS#FILTROS*/', $cotas, $sql);
	
		$args = array(
				':DATA_1X' 		=> $param->DATA_1,
				':DATA_2X'		=> $param->DATA_2,
//				':DATA_1' 		=> $param->DATA_1,
//				':DATA_2'		=> $param->DATA_2,
//				':FILTRO'		=> $filtro
		);
	
        $ret = $con->query($sql, $args);
        
		return $ret;
	}

	/**
	 * @param _Conexao $con (_Conexao)
	 * @return mixed $con->query()
	 * @throws Exception
	 */
	public static function exibirFaturamento(_Conexao $con, $param)
    {
        $estabelecimento = isset($param->ESTABELECIMENTO) ? 'AND A.ESTABELECIMENTO_CODIGO IN ('. $param->ESTABELECIMENTO .')' : '';    
	
		$sql = /** @lang text */
		"
            SELECT
                ANO,
                MES,
                PERIODO_DESCRICAO,
                SUM(VALOR_TOTAL)VALOR_TOTAL,
                SUM(VALOR_TOTAL_DEV)VALOR_TOTAL_DEV,
                SUM(VALOR_TOTAL_EXTRA)VALOR_TOTAL_EXTRA

            FROM
           (SELECT
                EXTRACT(YEAR FROM A.DATA_EMISSAO)ANO,
                EXTRACT(MONTH FROM A.DATA_EMISSAO)MES,   

                (CASE WHEN EXTRACT(MONTH FROM A.DATA_EMISSAO) =  1 THEN 'JAN/' || EXTRACT(YEAR FROM A.DATA_EMISSAO)
                      WHEN EXTRACT(MONTH FROM A.DATA_EMISSAO) =  2 THEN 'FEV/' || EXTRACT(YEAR FROM A.DATA_EMISSAO)
                      WHEN EXTRACT(MONTH FROM A.DATA_EMISSAO) =  3 THEN 'MAR/' || EXTRACT(YEAR FROM A.DATA_EMISSAO)
                      WHEN EXTRACT(MONTH FROM A.DATA_EMISSAO) =  4 THEN 'ABR/' || EXTRACT(YEAR FROM A.DATA_EMISSAO)
                      WHEN EXTRACT(MONTH FROM A.DATA_EMISSAO) =  5 THEN 'MAI/' || EXTRACT(YEAR FROM A.DATA_EMISSAO)
                      WHEN EXTRACT(MONTH FROM A.DATA_EMISSAO) =  6 THEN 'JUN/' || EXTRACT(YEAR FROM A.DATA_EMISSAO)
                      WHEN EXTRACT(MONTH FROM A.DATA_EMISSAO) =  7 THEN 'JUL/' || EXTRACT(YEAR FROM A.DATA_EMISSAO)
                      WHEN EXTRACT(MONTH FROM A.DATA_EMISSAO) =  8 THEN 'AGO/' || EXTRACT(YEAR FROM A.DATA_EMISSAO)
                      WHEN EXTRACT(MONTH FROM A.DATA_EMISSAO) =  9 THEN 'SET/' || EXTRACT(YEAR FROM A.DATA_EMISSAO)
                      WHEN EXTRACT(MONTH FROM A.DATA_EMISSAO) = 10 THEN 'OUT/' || EXTRACT(YEAR FROM A.DATA_EMISSAO)
                      WHEN EXTRACT(MONTH FROM A.DATA_EMISSAO) = 11 THEN 'NOV/' || EXTRACT(YEAR FROM A.DATA_EMISSAO)
                      WHEN EXTRACT(MONTH FROM A.DATA_EMISSAO) = 12 THEN 'DEZ/' || EXTRACT(YEAR FROM A.DATA_EMISSAO)
                      ELSE '#N/D' END)PERIODO_DESCRICAO,

                SUM(A.VALOR_TOTAL) VALOR_TOTAL,
                0 VALOR_TOTAL_DEV,
                0 VALOR_TOTAL_EXTRA

            FROM
                TBNFS_ITEM A,
                TBNFS B,
                TBNFS_TOTAIS C,
                TBOPERACAO D

            WHERE
                A.NFS_CONTROLE = B.CONTROLE
            AND A.NFS_CONTROLE = C.NFS_CONTROLE
            AND A.OPERACAO_CODIGO = D.CODIGO
            AND D.CONTROLE_FATURAMENTO = 1
            AND ((B.SITUACAO = 2) OR (B.SITUACAO = 1 AND B.NATUREZA = 2))
            AND B.DATA_EMISSAO BETWEEN :DATA_1 AND :DATA_2
            /*#ESTABELECIMENTO#*/

            GROUP BY 1,2,3


            UNION

            SELECT          
                EXTRACT(YEAR FROM A.DATA_ENTRADA)ANO,
                EXTRACT(MONTH FROM A.DATA_ENTRADA)MES,
            
                (CASE WHEN EXTRACT(MONTH FROM A.DATA_ENTRADA) =  1 THEN 'JAN/' || EXTRACT(YEAR FROM A.DATA_ENTRADA)
                      WHEN EXTRACT(MONTH FROM A.DATA_ENTRADA) =  2 THEN 'FEV/' || EXTRACT(YEAR FROM A.DATA_ENTRADA)
                      WHEN EXTRACT(MONTH FROM A.DATA_ENTRADA) =  3 THEN 'MAR/' || EXTRACT(YEAR FROM A.DATA_ENTRADA)
                      WHEN EXTRACT(MONTH FROM A.DATA_ENTRADA) =  4 THEN 'ABR/' || EXTRACT(YEAR FROM A.DATA_ENTRADA)
                      WHEN EXTRACT(MONTH FROM A.DATA_ENTRADA) =  5 THEN 'MAI/' || EXTRACT(YEAR FROM A.DATA_ENTRADA)
                      WHEN EXTRACT(MONTH FROM A.DATA_ENTRADA) =  6 THEN 'JUN/' || EXTRACT(YEAR FROM A.DATA_ENTRADA)
                      WHEN EXTRACT(MONTH FROM A.DATA_ENTRADA) =  7 THEN 'JUL/' || EXTRACT(YEAR FROM A.DATA_ENTRADA)
                      WHEN EXTRACT(MONTH FROM A.DATA_ENTRADA) =  8 THEN 'AGO/' || EXTRACT(YEAR FROM A.DATA_ENTRADA)
                      WHEN EXTRACT(MONTH FROM A.DATA_ENTRADA) =  9 THEN 'SET/' || EXTRACT(YEAR FROM A.DATA_ENTRADA)
                      WHEN EXTRACT(MONTH FROM A.DATA_ENTRADA) = 10 THEN 'OUT/' || EXTRACT(YEAR FROM A.DATA_ENTRADA)
                      WHEN EXTRACT(MONTH FROM A.DATA_ENTRADA) = 11 THEN 'NOV/' || EXTRACT(YEAR FROM A.DATA_ENTRADA)
                      WHEN EXTRACT(MONTH FROM A.DATA_ENTRADA) = 12 THEN 'DEZ/' || EXTRACT(YEAR FROM A.DATA_ENTRADA)
                      ELSE '#N/D' END)PERIODO_DESCRICAO,
            
                0 VALOR_TOTAL,
            
                SUM(A.VALOR_TOTAL-A.VALOR_DESCONTO+A.VALOR_ACRESCIMO+A.VALOR_IPI+A.VALOR_ICMS_ST) VALOR_TOTAL_DEV,

                0 VALOR_TOTAL_EXTRA
            
            FROM TBNFE_ITEM A, TBNFE B, TBOPERACAO C
            
            WHERE A.NFE_CONTROLE = B.CONTROLE
              AND A.OPERACAO_CODIGO = C.CODIGO
              AND B.SITUACAO < 3
              AND B.DATA_ENTRADA BETWEEN :_DATA_1 AND :_DATA_2
              AND C.CONTROLE_DEVOLUCAO = 1
              /*#ESTABELECIMENTO#*/
            
            GROUP BY 1,2,3

            UNION

            SELECT     
                F.ANO,
                F.MES,
                (CASE WHEN F.MES =  1 THEN 'JAN/' || F.ANO
                      WHEN F.MES =  2 THEN 'FEV/' || F.ANO
                      WHEN F.MES =  3 THEN 'MAR/' || F.ANO
                      WHEN F.MES =  4 THEN 'ABR/' || F.ANO
                      WHEN F.MES =  5 THEN 'MAI/' || F.ANO
                      WHEN F.MES =  6 THEN 'JUN/' || F.ANO
                      WHEN F.MES =  7 THEN 'JUL/' || F.ANO
                      WHEN F.MES =  8 THEN 'AGO/' || F.ANO
                      WHEN F.MES =  9 THEN 'SET/' || F.ANO
                      WHEN F.MES = 10 THEN 'OUT/' || F.ANO
                      WHEN F.MES = 11 THEN 'NOV/' || F.ANO
                      WHEN F.MES = 12 THEN 'DEZ/' || F.ANO
                      ELSE '#N/D' END)PERIODO_DESCRICAO,

                0 VALOR_TOTAL,
                0 VALOR_TOTAL_DEV,
                SUM(F.VALOR)VALOR_TOTAL_EXTRA

            FROM
                TBCCUSTO_FATURAMENTO F

            WHERE
                F.STATUSEXCLUSAO = '0'
            AND Cast(F.Ano||'.'||F.Mes||'.01' As Date) BETWEEN :DATA_1_ AND :DATA_2_  
            /*#ESTABELECIMENTO#*/

            GROUP BY 1,2,3,4,5)X

            GROUP BY 1,2,3
            
            ORDER BY ANO,MES
		";
	
		$sql = str_replace('/*#ESTABELECIMENTO#*/', $estabelecimento, $sql);
	
		$args = array(
            ':DATA_1' 		=> $param->DATA_1,
            ':DATA_2'		=> $param->DATA_2,
            ':_DATA_1' 		=> $param->DATA_1,
            ':_DATA_2'		=> $param->DATA_2,
            ':DATA_1_' 		=> $param->DATA_1,
            ':DATA_2_'		=> $param->DATA_2
		);
	
		return $con->query($sql, $args);
	} 
    
	/**
	 * @param _Conexao $con
	 * @param $id
	 * @return mixed $con->query()
	 * @throws Exception
	 */
	public static function exibirItem(_Conexao $con, $id){
		$sql = /** @lang text */
		"
            SELECT FIRST 1
                LPAD(A.ID,4,'0')ID,
    
                B.CODIGO CCUSTO,
                IIF(char_length(B.CODIGO)=2,B.CODIGO,
                IIF(char_length(B.CODIGO)=5,Substring(B.CODIGO From 1 For 2)||'.'||Substring(B.CODIGO From 3 For 3),
                IIF(char_length(B.CODIGO)=8,Substring(B.CODIGO From 1 For 2)||'.'||Substring(B.CODIGO From 3 For 3)||'.'||Substring(B.CODIGO From 6 For 3),''))) CCUSTO_MASK,
    
                UPPER(IIF(char_length(B.CODIGO)=2,B.DESCRICAO,
                      IIF(char_length(B.CODIGO)=5,
                         (Select First 1 C.Descricao From TbCentro_de_Custo C Where C.CODIGO = SUBSTRING(B.CODIGO FROM 1 FOR 2))||' - '||B.DESCRICAO,
                      IIF(char_length(B.CODIGO)=8,
                         (Select First 1 C.Descricao From TbCentro_de_Custo C Where C.CODIGO = SUBSTRING(B.CODIGO FROM 1 FOR 5))||' - '||B.DESCRICAO,'')))) CCUSTO_DESCRICAO,
    
                IIF(char_length(A.CONTACONTABIL)= 1,A.CONTACONTABIL,
                IIF(char_length(A.CONTACONTABIL)= 2,Substring(A.CONTACONTABIL From 1 For 1)||'.'||Substring(A.CONTACONTABIL From 2 For 1),
                IIF(char_length(A.CONTACONTABIL)= 4,Substring(A.CONTACONTABIL From 1 For 1)||'.'||Substring(A.CONTACONTABIL From 2 For 1)||'.'||Substring(A.CONTACONTABIL From 3 For 2),
                IIF(char_length(A.CONTACONTABIL)= 7,Substring(A.CONTACONTABIL From 1 For 1)||'.'||Substring(A.CONTACONTABIL From 2 For 1)||'.'||Substring(A.CONTACONTABIL From 3 For 2)||'.'||Substring(A.CONTACONTABIL From 5 For 3),
                IIF(char_length(A.CONTACONTABIL)=11,Substring(A.CONTACONTABIL From 1 For 1)||'.'||Substring(A.CONTACONTABIL From 2 For 1)||'.'||Substring(A.CONTACONTABIL From 3 For 2)||'.'||Substring(A.CONTACONTABIL From 5 For 3)||'.'||Substring(A.CONTACONTABIL From 8 For 4),''))))) CCONTABIL_MASK,
    
                C.CONTA CCONTABIL,
                UPPER(C.DESCRICAO) CCONTABIL_DESCRICAO,
                A.MES,
                A.ANO,
                (CASE WHEN A.MES =  1 THEN 'JANEIRO DE ' || A.ANO
                      WHEN A.MES =  2 THEN 'FEVEREIRO DE ' || A.ANO
                      WHEN A.MES =  3 THEN 'MARÇO DE ' || A.ANO
                      WHEN A.MES =  4 THEN 'ABRIL DE ' || A.ANO
                      WHEN A.MES =  5 THEN 'MAIO DE ' || A.ANO
                      WHEN A.MES =  6 THEN 'JUNHO DE ' || A.ANO
                      WHEN A.MES =  7 THEN 'JULHO DE ' || A.ANO
                      WHEN A.MES =  8 THEN 'AGOSTO DE ' || A.ANO
                      WHEN A.MES =  9 THEN 'SETEMBRO DE ' || A.ANO
                      WHEN A.MES = 10 THEN 'OUTUBRO DE ' || A.ANO
                      WHEN A.MES = 11 THEN 'NOVEMBRO DE ' || A.ANO
                      WHEN A.MES = 12 THEN 'DEZEMBRO DE ' || A.ANO
                      ELSE '#N/D' END)PERIODO_DESCRICAO,
                
                A.VALOR,
                A.EXTRA,
                (A.VALOR+A.EXTRA) TOTAL,
                A.OUTROS,
                (A.VALOR+A.EXTRA-A.OUTROS-A.SALDO)UTIL,
                IIF(A.VALOR > 0 , ((A.OUTROS+(A.VALOR+A.EXTRA-A.OUTROS-A.SALDO))/A.VALOR)*100 , IIF((A.VALOR = 0) AND ((A.OUTROS+(A.VALOR+A.EXTRA-A.OUTROS-A.SALDO)) < 0) , 100 , 0 ))PERC_UTIL,
                A.SALDO,
    
                A.BLOQUEIO,
                A.NOTIFICACAO,
                A.DESTAQUE,
                A.TOTALIZA,
                A.OBSERVACAO_GERAL COTA_OBSERVACAO
    
            FROM
                TBCCUSTO_COTA A,
                VWCENTRO_DE_CUSTO B,
                TBCONTACONTABIL C
    
            WHERE
                A.ID     = :ID
            AND B.CODIGO = A.CCUSTO
            AND C.CONTA  = A.CONTACONTABIL
    	";
	
		/** @var int $id */
		$args = array(':ID' => $id);
	
		return $con->query($sql, $args);
	}
	
	public static function exibirItemDetalhe(_Conexao $con, $cota){
	
		$ccusto		= $cota[0]->CCUSTO;
		$ccontabil	= $cota[0]->CCONTABIL;
		$data_1	= date('Y.m.d', strtotime("+0 days",strtotime($cota[0]->ANO . '-' .  $cota[0]->MES    . '-01')));
		$data_2	= date('Y.m.d', strtotime("-1 days",strtotime($cota[0]->ANO . '-' . ($cota[0]->MES+1) . '-01')));
	
		$sql = /** @lang text */
		"
            SELECT
                X.*,
                VALOR + DESCONTO_IMPOSTO VALOR_SUBTOTAL 
            FROM
                (SELECT
                    A.ID,
                    A.CCUSTO,
                    A.CONTACONTABIL CCONTABIL,
    
                    (IIF(A.TABELA = 'NFE',
    
                        (SELECT FIRST 1
                        ('NFE.'|| LPAD(E.NUMERO_NOTAFISCAL,6,'0')||' - '||
                        (SELECT FIRST 1 NOMEFANTASIA FROM TBEMPRESA WHERE CODIGO = E.EMPRESA_CODIGO) || ' - ' ||
                        SUBSTRING(E.PRODUTO_DESCRICAO FROM 1 FOR 25) || ' - ' ||
                        'QTD. ' || REPLACE(CAST(E.QUANTIDADE AS NUMERIC(15,4)), '.', ',')
                        )STRING
                        FROM
                        TBNFE_ITEM E
                        WHERE
                        E.CONTROLE = A.TABELA_ID),
    
                    IIF(A.TABELA = 'NFS',
    
                        (SELECT FIRST 1
                        ('NFS.'|| LPAD(E.NUMERO_NOTAFISCAL,6,'0')||' - '||
                        (SELECT FIRST 1 NOMEFANTASIA FROM TBEMPRESA WHERE CODIGO = E.EMPRESA_CODIGO) || ' - ' ||
                        SUBSTRING(E.PRODUTO_DESCRICAO FROM 1 FOR 25) || ' - ' ||
                        'QTD. ' || REPLACE(CAST(E.QUANTIDADE AS NUMERIC(15,4)), '.', ',')
                        )STRING
                        FROM
                        TBNFS_ITEM E
                        WHERE
                        E.CONTROLE = A.TABELA_ID),
    
                    IIF(A.TABELA = 'OC',
    
                        (SELECT FIRST 1
                        ('OC.'|| LPAD(E.OC,6,'0')||' - '||
                        (SELECT FIRST 1 NOMEFANTASIA FROM TBEMPRESA WHERE CODIGO = E.FORNECEDOR_CODIGO) || ' - ' ||
                        SUBSTRING((SELECT FIRST 1 DESCRICAO FROM TBPRODUTO WHERE CODIGO = E.PRODUTO_CODIGO) FROM 1 FOR 25) || ' - ' ||
                        'QTD. ' || REPLACE(CAST(E.QUANTIDADE AS NUMERIC(15,4)), '.', ',')
                        )STRING
                        FROM
                        TBOC_ITEM E
                        WHERE
                        E.CONTROLE = A.TABELA_ID),
    
                    ''))) || ' ' || IIF(CHAR_LENGTH(A.DESCRICAO) > 0,A.DESCRICAO, '')) DESCRICAO,
    
                    A.VALOR,
                    (A.ICMS+A.PISCOFINS)*-1 DESCONTO_IMPOSTO,
                    A.NATUREZA,
                    A.DATA,
                    EXTRACT(MONTH FROM A.DATA)MES,
                    EXTRACT(YEAR FROM A.DATA)ANO,
                    0 as FILTRO
    
                FROM
                    TBCCUSTO_COTA_DETALHE A,
                    TBCCUSTO_COTA B
    
                WHERE
                    A.CCUSTO = B.CCUSTO
                AND A.CONTACONTABIL = B.CONTACONTABIL
                AND A.DATA BETWEEN CAST(B.ANO||'.'||B.MES||'.01' As Date)
                AND (DATEADD(1 MONTH TO CAST(B.ANO||'.'||B.MES||'.01' AS DATE))-1)
                AND B.STATUSEXCLUSAO = '0'
                AND A.STATUSEXCLUSAO = '0'
                AND A.CCUSTO = :CCUSTO
                AND A.CONTACONTABIL = :CCONTABIL
                AND A.DATA BETWEEN :DATA_1 AND :DATA_2
    
                ORDER BY
                    DESCRICAO)X
    	";
	
		$args = array(
				':CCUSTO'		=>	$ccusto,
				':CCONTABIL'	=>	$ccontabil,
				':DATA_1'		=>	$data_1,
				':DATA_2'		=>	$data_2
		);
	
		return $con->query($sql, $args);
	}
	
	
	public static function exibirCota(_Conexao $con, $id = null, $ccusto = '', $ccontabil = '', $periodoInicial, $periodoFinal = null) {
	
		if ( !$id ) $id = 0;
		if ( !$periodoFinal ) $periodoFinal = $periodoInicial;
	
		$sql = /** @lang text */
		"
            SELECT
                X.ID,
                X.CCUSTO,
                X.CONTACONTABIL CCONTABIL,
                X.MES,
                X.ANO,
                X.VALOR,
                X.SALDO,
                X.PERC_UTIL,
                X.STATUSEXCLUSAO
	
            FROM
                (SELECT
                    C.ID,
                    C.CCUSTO,
                    C.CONTACONTABIL,
                    C.MES,
                    C.ANO,
                    C.VALOR,
                    C.SALDO,
                    C.STATUSEXCLUSAO,
	
                    IIF(C.VALOR+C.EXTRA > 0, ((1-(C.SALDO/(C.VALOR+C.EXTRA)))*100),
                    IIF(C.VALOR+C.EXTRA = 0 AND C.SALDO < 0, 100, 0))  PERC_UTIL,
	
                    CAST((C.ANO||'.'||C.MES||'.01') AS DATE)DATA
	
                FROM
                    TBCCUSTO_COTA C
	
                WHERE
                    C.STATUSEXCLUSAO = '0'
                AND(C.CCUSTO        = :CCUSTO
                AND C.CONTACONTABIL = :CCONTABIL)
                OR (C.ID            = :ID))X
	
            WHERE
                X.DATA BETWEEN :DATA_1 AND :DATA_2
	
            ORDER BY
                MES, ANO
		";
		$args = array(
				':ID'			=> $id,
				':CCUSTO'		=> $ccusto,
				':CCONTABIL'	=> $ccontabil,
				':DATA_1'		=> $periodoInicial,
				':DATA_2'		=> $periodoFinal
		);
	
		return $con->query($sql, $args);
	}
	
	/**
	 * Retorna a Consulta de Cotas Extras
	 * @param _Conexao $con
	 * @param int $id
	 */
	public static function exibirCotaExtra(_Conexao $con, $id) {
	
		$sql = /** @lang text */
		"
		SELECT
					A.ID,
                    B.CODIGO    USUARIO,
                    B.NOME		USUARIO_NOME,
                    A.VALOR,
                    A.DATAHORA_INSERT DATAHORA,
                    A.OBSERVACAO
	
		FROM
                    TBCCUSTO_COTA_EXTRA A,
                    TBUSUARIO B
	
		WHERE
                    B.CODIGO = A.USUARIO_ID
		AND A.CCUSTO_COTA_ID = :ID
		AND A.STATUSEXCLUSAO = '0'
	
                ORDER BY
                    A.DATAHORA_INSERT
		";
	
		$args = array(
				':ID' => $id
		);
	
		return $con->query($sql,$args);
	}
	
	/**
	 * Retorna a Consulta de Outros Lançamentos de Cotas
	 * @param _Conexao $con
	 * @param int $id
	 */
	public static function exibirCotaOutro(_Conexao $con, $id) {
	
		$sql = /** @lang text */
		"
            SELECT
                A.ID,
                B.CODIGO	USUARIO,
                B.NOME      USUARIO_NOME,
                A.VALOR,
                A.DATAHORA_INSERT DATAHORA,
                A.OBSERVACAO
	
            FROM
                TBCCUSTO_COTA_OUTROS A,
                TBUSUARIO B
	
            WHERE
                B.CODIGO = A.USUARIO_ID
            AND A.CCUSTO_COTA_ID = :ID
            AND A.STATUSEXCLUSAO = '0'
	
            ORDER BY
                A.DATAHORA_INSERT
		";
	
		$args = array(
				':ID' => $id
		);
	
		return $con->query($sql,$args);
	}
    
    public static function faturamentoQuery($param)
    {
        $con = new _Conexao;
        
        $sql =
        "
            SELECT
                LPAD(F.ID,4,'0')ID,
                LPAD(E.CODIGO,2,'0') ESTABELECIMENTO_ID,
                E.NOMEFANTASIA ESTABELECIMENTO_DESCRICAO,
                F.MES,
                F.ANO,
                F.VALOR,
                F.DATAHORA_INSERT DATAHORA,

                (CASE WHEN F.MES =  1 THEN 'JANEIRO DE ' || F.ANO
                      WHEN F.MES =  2 THEN 'FEVEREIRO DE ' || F.ANO
                      WHEN F.MES =  3 THEN 'MARÇO DE ' || F.ANO
                      WHEN F.MES =  4 THEN 'ABRIL DE ' || F.ANO
                      WHEN F.MES =  5 THEN 'MAIO DE ' || F.ANO
                      WHEN F.MES =  6 THEN 'JUNHO DE ' || F.ANO
                      WHEN F.MES =  7 THEN 'JULHO DE ' || F.ANO
                      WHEN F.MES =  8 THEN 'AGOSTO DE ' || F.ANO
                      WHEN F.MES =  9 THEN 'SETEMBRO DE ' || F.ANO
                      WHEN F.MES = 10 THEN 'OUTUBRO DE ' || F.ANO
                      WHEN F.MES = 11 THEN 'NOVEMBRO DE ' || F.ANO
                      WHEN F.MES = 12 THEN 'DEZEMBRO DE ' || F.ANO
                      ELSE '#N/D' END)DATA_DESCRICAO

            FROM
                TBCCUSTO_FATURAMENTO F,
                TBESTABELECIMENTO E

            WHERE
                F.STATUSEXCLUSAO = '0'
            AND E.CODIGO = F.ESTABELECIMENTO_ID
            AND F.ANO = :ANO
            
            ORDER BY ESTABELECIMENTO_ID, ANO, MES
        ";
        
		$args = array(
            ':ANO' => $param->ANO
		);
	
		return $con->query($sql,$args);
    }
    
    public static function faturamentoStore($param)
    {
        $con = new _Conexao;
		try {
            $sql =
            "
                INSERT INTO TBCCUSTO_FATURAMENTO (
                    ID,
                    ESTABELECIMENTO_ID,
                    MES,
                    ANO,
                    VALOR
                ) VALUES (
                    :ID,
                    :ESTABELECIMENTO_ID,
                    :MES,
                    :ANO,
                    :VALOR
                )
            ";

            $args = array(
                ':ID'                 => $param->ID,
                ':ESTABELECIMENTO_ID' => $param->ESTABELECIMENTO_ID,
                ':MES'                => $param->MES,
                ':ANO'                => $param->ANO,
                ':VALOR'              => $param->VALOR
            );

            $con->execute($sql,$args);
            $con->commit();
		} catch (Exception $e) {
			$con->rollback();
			throw $e;
		}
    }
    
    public static function faturamentoUpdate($param)
    {
        $con = new _Conexao;
        try {
            $sql =
            "
                UPDATE TBCCUSTO_FATURAMENTO SET
                    ESTABELECIMENTO_ID = :ESTABELECIMENTO_ID,
                    MES   = :MES,
                    ANO   = :ANO,
                    VALOR = :VALOR
                WHERE (ID = :ID);
            ";

            $args = array(
                ':ID'                 => $param->ID,
                ':ESTABELECIMENTO_ID' => $param->ESTABELECIMENTO_ID,
                ':MES'                => $param->MES,
                ':ANO'                => $param->ANO,
                ':VALOR'              => $param->VALOR
            );

            $con->execute($sql, $args);
                $con->commit();
		} catch (Exception $e) {
			$con->rollback();
			throw $e;
		}
    }
    
    public static function faturamentoDestroy($param = [])
    {
        $con = new _Conexao;
        try {
            $sql =
            "
                UPDATE TBCCUSTO_FATURAMENTO SET
                    STATUSEXCLUSAO = '1'
                WHERE (ID = :ID);
            ";

            $args = array(
                ':ID' => $param->ID
            );

            $con->execute($sql, $args);
            $con->commit();
		} catch (Exception $e) {
			$con->rollback();
			throw $e;
		}
    }
    
	public static function faturamentoGerarId()
	{
		$con = new _Conexao();
		
		$sql = '
			SELECT GEN_ID(GTBCCUSTO_FATURAMENTO, 1) ID FROM RDB$DATABASE
		';
		$qry = $con->query($sql);

		return $qry[0]->ID;
	}    

    public static function replicarStore($param)
    {
        $con = new _Conexao;
		try {
            
            $con1 = new _Conexao;
            $con1->query("ALTER TRIGGER TTBCCUSTO_COTA_B0U_01 INACTIVE;");
            $con1->commit();
            
            $sql = "EXECUTE PROCEDURE SPU_REPLICAR_COTAS(:DATA_ORIGEM,:DATA_DESTINO);";

            $args = array(
                ':DATA_ORIGEM'  => $param->DATA_ORIGEM,
                ':DATA_DESTINO' => $param->DATA_DESTINO,
            );

            $con->execute($sql,$args);
            $con->commit();
            
            $con2 = new _Conexao;
            $con2->query("ALTER TRIGGER TTBCCUSTO_COTA_B0U_01 ACTIVE;");
            $con2->commit();
            
		} catch (Exception $e) {
			$con->rollback();
			throw $e;
		}
    }
    
   /**
	 * Gerar id do objeto.
	 * @return integer
	 */
	public static function gerarId()
	{
		$con = new _Conexao();
		
		$sql = '
			SELECT GEN_ID(GTBCCUSTO_COTA, 1) ID FROM RDB$DATABASE
		';
		$qry = $con->query($sql);

		return $qry[0]->ID;
	}
	
	/**
	 * Similar ao DESTROY (EXCLUIR) do CRUD
	 * Exclui dados do objeto na base de dados.
	 * @param int $id
	 * @return array
	 */
	public static function excluirContaAjx($id)
	{
		$con = new _Conexao();
		try {
			self::excluirCota($con, $id);
			$con->commit();
			return array(
				'0' => 'sucesso',
				'1' => 'Cota excluída com sucesso!'
			);
        } catch (Exception $e) {$con->rollback(); throw new Exception($e->getMessage());}
	}
	
	/**
	 * Lista a cota e saldo de um Centro de Custo
	 * @param null $id
	 * @param null $ccusto
	 * @param null $ccontabil
	 * @param $periodoInicial
	 * @param null $periodoFinal
	 * @return array
	 */
	public static function consultaCota($id = null, $ccusto = null, $ccontabil = null, $periodoInicial, $periodoFinal = null) {
		
		$con = new _Conexao();

		return self::exibirCota($con, $id, $ccusto, $ccontabil, $periodoInicial, $periodoFinal);
		
	}
    
    public static function selectGgf($param, _Conexao $con = null)
    {
        
        /**
         * Campos para condição WHERE
         */
        $familia_id = array_key_exists('FAMILIA_ID', $param) ? "AND FAMILIA_ID = ". $param->FAMILIA_ID	: '';
                
        $sql = "
            SELECT
                CCUSTO,
                C.DESCRICAO CCUSTO_DESCRICAO,
                F.CODIGO AS FAMILIA_ID,
                F.DESCRICAO AS FAMILIA_DESCRICAO,
                MES,
                TRIM(CASE MES
                WHEN 01 THEN 'JANEIRO'
                WHEN 02 THEN 'FEVEREIRO'
                WHEN 03 THEN 'MARCO'
                WHEN 04 THEN 'ABRIL'
                WHEN 05 THEN 'MAIO'
                WHEN 06 THEN 'JUNHO'
                WHEN 07 THEN 'JULHO'
                WHEN 08 THEN 'AGOSTO'
                WHEN 09 THEN 'SETEMBRO'
                WHEN 10 THEN 'OUTUBRO'
                WHEN 11 THEN 'NOVEMBRO'
                WHEN 12 THEN 'DEZEMBRO'
                ELSE 'INDEFINDO' END) MES_DESCRICAO,

                ANO,
                SUM(VALOR_COTA) VALOR_COTA,
                SUM(VALOR_UTILIZADO) VALOR_UTILIZADO,
                SUM(PERCENTUAL_UTILIZADO) PERCENTUAL_UTILIZADO,
                SUM(SALDO) SALDO
            FROM
                SPC_COTA_GGF(
                    :CCUSTO,
                    CAST(CAST(:ANO_1 AS INTEGER)||'.'||CAST(:MES_1 AS INTEGER)||'.01' AS DATE),
                    (DATEADD(1 MONTH TO CAST(CAST(:ANO_2 AS INTEGER)||'.'||CAST(:MES_2 AS INTEGER)||'.01' AS DATE))-1)),
                VWCENTRO_DE_CUSTO C,
                TBFAMILIA F

            WHERE
                C.CODIGO = CCUSTO
            AND F.CODIGO = FAMILIA_ID
            /*@FAMILIA_ID*/

            GROUP BY 1,2,3,4,5,6,7
        ";
        

        
        /**
         * Campos para condição de agrupamento
         */
        if ( isset($param->GROUP_FAMILIA) && (!$param->GROUP_FAMILIA) ) {
            $sql = str_replace('F.CODIGO AS'    , '0', $sql);
            $sql = str_replace('F.DESCRICAO AS' , '0', $sql);
        }
        
        $args = [
            ':CCUSTO'     => $param->CCUSTO,
            '@FAMILIA_ID' => $familia_id,
            ':ANO_1'      => $param->ANO,
            ':MES_1'      => $param->MES,
            ':ANO_2'      => $param->ANO,
            ':MES_2'      => $param->MES,
        ];
        
        return $con->query($sql,$args);
    }
    
    public static function selectAjusteInventario($param, _Conexao $con = null)
    {
        
        /**
         * Campos para condição WHERE
         */
        $familia_id = array_key_exists('FAMILIA_ID', $param) ? "AND FAMILIA_ID = ". $param->FAMILIA_ID	: '';
                
        $sql = "
            SELECT
                W.*,
                VALOR_UTILIZADO / SUM(VALOR_UTILIZADO) OVER (PARTITION BY MES,ANO) * 100 PERCENTUAL_UTILIZADO
            FROM (
                SELECT
                    Z.ANO,
                    Z.MES,
                    Z.MES_DESCRICAO,
                    Z.CCUSTO,
                    Z.CCUSTO_MASK,
                    Z.CCUSTO_DESCRICAO,
                    Z.DESCRICAO AS DESCRICAO,
                    Z.FAMILIA_ID AS FAMILIA_ID,
                    Z.FAMILIA_DESCRICAO AS FAMILIA_DESCRICAO,
                    SUM(Z.VALOR_COTA) VALOR_COTA,
                    SUM(Z.VALOR) VALOR,
                    SUM(Z.VALOR_UTILIZADO) VALOR_UTILIZADO,
                    SUM(0) SALDO
    
                FROM (
                SELECT
                    X.ANO,
                    X.MES,
                    TRIM(FN_MES_DESCRICAO(X.MES))MES_DESCRICAO,
                    X.CC_DETALHE CCUSTO,
                    FN_CCUSTO_MASK(X.CC_DETALHE) CCUSTO_MASK,
                    FN_CCUSTO_DESCRICAO(X.CC_DETALHE) CCUSTO_DESCRICAO,
    
                    X.FAMILIA_ID || ' - ' || 
                        (SELECT FIRST 1 F.DESCRICAO
                           FROM TBFAMILIA F
                          WHERE F.CODIGO = X.FAMILIA_ID) DESCRICAO,
    
                    X.FAMILIA_ID,
                    (SELECT FIRST 1 F.DESCRICAO
                       FROM TBFAMILIA F
                      WHERE F.CODIGO = X.FAMILIA_ID) FAMILIA_DESCRICAO,
                    0 VALOR_COTA,  
                    CAST(SUM(CUSTO) AS NUMERIC(15,2)) VALOR,
                    CAST(SUM(CUSTO) AS NUMERIC(15,2)) VALOR_UTILIZADO
    
                FROM (
                SELECT CC.CCUSTO_CONTABILIZACAO CC,
                       A.CENTRO_DE_CUSTO CC_DETALHE,
                       CC.DESCRICAO CC_DESCRICAO,
                       P.FAMILIA_CODIGO FAMILIA_ID,
                       EXTRACT (YEAR FROM A.DATA) ANO,
                       EXTRACT (MONTH FROM A.DATA) MES,
                       A.QUANTIDADE *
                      (SELECT FIRST 1 S.CUSTO_MEDIO
                         FROM TBESTOQUE_SALDO_DIARIO S
                        WHERE S.ESTABELECIMENTO_CODIGO = A.ESTABELECIMENTO_CODIGO
                          AND S.LOCALIZACAO_CODIGO = A.LOCALIZACAO_CODIGO
                          AND S.PRODUTO_CODIGO = A.PRODUTO_CODIGO
                          AND S.DATA = A.DATA) * (IIF(A.TIPO='E',-1.000,1.000)) CUSTO
                  FROM TBESTOQUE_TRANSACAO_ITEM A, TBPRODUTO P, TBOPERACAO O, TBCENTRO_DE_CUSTO CC
                 WHERE
                       A.PRODUTO_CODIGO = P.CODIGO
                   AND A.OPERACAO_CODIGO = O.CODIGO
                   AND O.ACERTO = '1'
                   AND P.INVENTARIO = '1'
                   AND A.CENTRO_DE_CUSTO = CC.CODIGO 
                   AND (CC.CCUSTO_CONTABILIZACAO = :CCUSTO_1 OR CC.CODIGO = :CCUSTO_2)
                   AND A.DATA BETWEEN FN_START_OF_MONTH('01.'||CAST(:MES_1 AS INTEGER)||'.'||CAST(:ANO_1 AS INTEGER)) AND FN_END_OF_MONTH('01.'||CAST(:MES_2 AS INTEGER)||'.'||CAST(:ANO_2 AS INTEGER))
                ) X
                GROUP BY ANO, MES, CC, CC_DETALHE, CC_DESCRICAO, FAMILIA_ID
                ) Z
                GROUP BY 1,2,3,4,5,6,7,8,9
                ) W
        ";
        
        /**
         * Campos para condição de agrupamento
         */
        if ( isset($param->GROUP_FAMILIA) && (!$param->GROUP_FAMILIA) ) {
            $sql = str_replace('Z.DESCRICAO AS'        , '0', $sql);
            $sql = str_replace('Z.FAMILIA_ID AS'       , '0', $sql);
            $sql = str_replace('Z.FAMILIA_DESCRICAO AS', '0', $sql);
        }
        
//        $args = [
//            'CCUSTO'   => setDefValue($param->CCUSTO  , null),
//            'MES_1'   => setDefValue($param->MES_1  , null),
//            'MES_2'   => setDefValue($param->MES_2  , null),
//            'ANO_1'   => setDefValue($param->ANO_1  , null),
//            'ANO_2'   => setDefValue($param->ANO_2  , null),
//        ];

        
        
        $args = [
            ':CCUSTO_1'     => $param->CCUSTO,
            ':CCUSTO_2'     => $param->CCUSTO,
//            '@FAMILIA_ID' => $familia_id,
            ':ANO_1'      => $param->ANO,
            ':MES_1'      => $param->MES,
            ':ANO_2'      => $param->ANO,
            ':MES_2'      => $param->MES,
        ];
        
        return $con->query($sql,$args);
    }

    public static function selectGgfDetalhe($param, _Conexao $con = null)
    {        
        $sql = "
            SELECT * FROM SPC_COTA_GGF_DETALHE(
                :CCUSTO,
                :FAMILIA_ID,
                :DATA_1,
                :DATA_2
            )
        ";
        
        $args = [
            ':CCUSTO'       => $param->CCUSTO,
            ':FAMILIA_ID'   => $param->FAMILIA_ID,
            ':DATA_1'       => $param->DATA_1,
            ':DATA_2'       => $param->DATA_2,
        ];
        
        return $con->query($sql,$args);
    }

    public static function selectAjusteInventarioDetalhe($param, _Conexao $con = null)
    {        
        $sql = "
            SELECT * FROM SPC_COTA_AJUSTE_DETALHE(
                :CCUSTO,
                :FAMILIA_ID,
                :DATA_1,
                :DATA_2
            )
        ";
        
        $args = [
            ':CCUSTO'       => $param->CCUSTO,
            ':FAMILIA_ID'   => $param->FAMILIA_ID,
            ':DATA_1'       => $param->DATA_1,
            ':DATA_2'       => $param->DATA_2,
        ];
        
        return $con->query($sql,$args);
    }

}