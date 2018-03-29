<?php

namespace App\Models\DAO\Opex;
use App\Models\Conexao\_Conexao;
use Exception;

/**
 * DAO do objeto 25600
 * @package Engenharia
 * @category 18800
 * @use App\Models\DTO\Opex\_25600;
 * @use App\Models\DTO\Helper\Historico;
 * @use App\Models\Conexao\_Conexao;
 * @use Exception;
 */
class _25600DAO {
    
    public static function idR() {

        $con = new _Conexao();

        $sql = 'select gen_id(GTBBSC_REGISTRO_INDICADOR, 1) ID from RDB$DATABASE';

        $ID = $con->query($sql);
        return $ID[0]->ID;
    }
    
    public static function idN() {

        $con = new _Conexao();

        $sql = 'select gen_id(GTBBSC_REGISTRO, 1) ID from RDB$DATABASE';

        $ID = $con->query($sql);
        return $ID[0]->ID;
    }

    public static function store($dados,$bsc,$data,$ccusto) {
        
        $con = new _Conexao();
       
        try {
            $time = date('Y.m.d', strtotime($data));
            
            $id = self::idR();
            
            $sql = 'INSERT INTO     
                    TBBSC_REGISTRO_INDICADOR
                    (ID,BSC_ID,C_CUSTO,DATA)
                    VALUES
                    (:ID,:BSC_ID,:C_CUSTO,:DATA)';

            $args = array(
                ':BSC_ID' => $bsc,
                ':C_CUSTO' => $ccusto,
                ':DATA' => $time,
                ':ID' => $id                
            );

            $retorno = $con->query($sql, $args);
            
            $cont = 0;
            foreach ($dados as $dado) {
                $cont++;
                
                $idn = self::idN();
                
                $sql = 'INSERT INTO  
                        TBBSC_REGISTRO (       
                            ID,                    
                            BSC_ID,                
                            BSC_DETALHE_ID,        
                            REGISTRO_INDICADOR_ID, 
                            C_CUSTO,               
                            TURNO,                 
                            VALOR,                 
                            DATA,                            
                            PLANACAO_STATUS,           
                            PESO,
                            OBSERVACAO
                        ) VALUES (             
                            :ID,                   
                            :BSC_ID,               
                            :BSC_DETALHE_ID,       
                            :REGISTRO_INDICADOR_ID,
                            :C_CUSTO,              
                            :TURNO,                
                            :VALOR,                
                            :DATA,                           
                            :PLANACAO_STATUS,          
                            :PESO,
                            :OBSERVACAO
                        )     
                        ';

                if ($dado['addPlano'] == 0){$desc = ' ';} else {
                    
                    $desc = $dado['plano'];
                    if (strlen($desc) < 10){log_erro('OBSERVAÇÃO MENOR DO QUE 10 CARACTERES');}
                    
                }
                
                $registro_indicador_id =  $id;
                $bsc_id = $bsc;
                $bsc_detalhe_id =  $dado['detalhe'];       
                $c_custo =  $ccusto;              
                $turno =  $dado['turno'];                
                $valor =  $dado['valor'];                
                $data =  $time;                 
                $observacao =   utf8_decode($desc);           
                $planacao_status =  $dado['addPlano'];          
                $peso =  $dado['peso'];
                
                $args = array(
                    0  => array('TIPO' => 'STR', 'PARN' => ':ID',                    'VALOR' => $idn                         ),
                    1  => array('TIPO' => 'STR', 'PARN' => ':BSC_ID',                'VALOR' => $bsc_id                      ),
                    2  => array('TIPO' => 'STR', 'PARN' => ':BSC_DETALHE_ID',        'VALOR' => $bsc_detalhe_id              ),
                    4  => array('TIPO' => 'STR', 'PARN' => ':REGISTRO_INDICADOR_ID', 'VALOR' => $registro_indicador_id       ),
                    5  => array('TIPO' => 'STR', 'PARN' => ':C_CUSTO',               'VALOR' => $c_custo                     ),
                    6  => array('TIPO' => 'STR', 'PARN' => ':TURNO',                 'VALOR' => $turno                       ),
                    7  => array('TIPO' => 'STR', 'PARN' => ':VALOR',                 'VALOR' => $valor                       ),
                    8  => array('TIPO' => 'STR', 'PARN' => ':DATA',                  'VALOR' => $data                        ),
                    9  => array('TIPO' => 'STR', 'PARN' => ':PLANACAO_STATUS',       'VALOR' => $planacao_status             ),
                    10 => array('TIPO' => 'STR', 'PARN' => ':PESO',                  'VALOR' => $peso                        ),
                    11 => array('TIPO' => 'LOB', 'PARN' => ':OBSERVACAO',            'VALOR' => $observacao                  )
                );
        
                $retorno = $con->executeParan($sql, $args);
            }

            $con->commit();
            return $cont;
            
        } catch (Exception $e) {
            
            $con->rollback();
            throw $e;
         
        }
        
        return $dados;
    }

    /**
     * Função para listar indicadores  
     * @access public
     * @param string $ccusto
     * @param string $data
     * @return array
     * @static
     */
    public static function consultarIndicadores($ccusto, $data) {

        $con = new _Conexao();
        
            $time = date('Y.m.d', strtotime($data));

            $sql = 'SELECT
                            RI.ID,
                            RI.BSC_ID,
                            I.DESCRICAO,
                            RI.C_CUSTO,
                            RI.DATA_HORA,
                            RI.DATA,
                            (SELECT FIRST 1 A.TURNO FROM TBBSC_REGISTRO A WHERE A.REGISTRO_INDICADOR_ID = RI.ID)TURNO

                    FROM
                            TBBSC_REGISTRO_INDICADOR RI
                            LEFT JOIN
                            TBBSC_INDICADORES I
                            ON
                            I.ID = RI.BSC_ID

                    WHERE

                            RI.C_CUSTO   = :CCUSTO
                    AND     RI.DATA = :DATA
                    ';


            $args = array(
                ':CCUSTO' => $ccusto,
                ':DATA' => $time
            );
             

            $retorno = $con->query($sql, $args);
            return $retorno;
    }

    /**
     * Função para listar valore de um registro de indicadores  
     * @access public
     * @param integer $id
     * @return array
     * @static
     */
    public static function consultarRegistroIndicadores($id) {

        $con = new _Conexao();
		
            $sql = 'SELECT
                    R.ID,
                    R.BSC_ID,
                    R.BSC_DETALHE_ID,
                    D.DESCRICAO,
                    R.PESO,
                    I.PERFIL1_B VALORMAX,
                    R.VALOR,
                    I.PERFIL1_A,
                    I.PERFIL1_B,
                    I.PERFIL2_A,
                    I.PERFIL2_B,
                    I.PERFIL3_A,
                    I.PERFIL3_B,
                    R.OBSERVACAO,
                   (0)FLAG,
                    R.PLANACAO_STATUS,
                    D.SEQUENCIA
                FROM
                    TBBSC_REGISTRO R,
                    TBBSC_DETALHE D,
                    TBBSC_INDICADORES I
                WHERE
                    R.REGISTRO_INDICADOR_ID = :ID
                    AND     D.ID = R.BSC_DETALHE_ID
                    AND     I.ID = D.BSC_ID
                    order by d.SEQUENCIA
                    ';

            $args = array(
                ':ID' => $id
            );

            $retorno = $con->query($sql, $args);
            return $retorno;      
    }
    
    /**
     * Função para alterar nota de um indicador 
     * @access public
     * @param integer $id
     * @return array
     * @static
     */
    public static function alterarIndicador($id,$valor,$indicador,$plano,$idDetalhe,$descPlano,$idIndicador) {

        $con = new _Conexao();

        try{
            
            if (isset($indicador)){} else{ log_erro('ID DO INDICADOR NÃO DEFINIDO');}
            
            $sql = '
                SELECT first 1
                IIF( X.MAIOR_1 > MAIOR_2, IIF( X.MAIOR_1 > MAIOR_3, MAIOR_1 , MAIOR_3 ) , IIF( X.MAIOR_3 > MAIOR_3, MAIOR_3 , MAIOR_3 ) ) AS MAIOR,
                IIF( X.MENOR_1 < MENOR_2, IIF( X.MENOR_1 < MENOR_3, MENOR_1 , MENOR_3 ) , IIF( X.MENOR_3 < MENOR_3, MENOR_3 , MENOR_3 ) ) AS MENOR,
                
                X.PERFIL1_A
                ,X.PERFIL1_B

                ,X.PERFIL2_A
                ,X.PERFIL2_B

                ,X.PERFIL3_A
                ,X.PERFIL3_B
                
                ,X.OBSERVACAO
                
                ,X.PLANACAO_STATUS,X.PLANACAO_QUEM,X.PLANACAO_COMO,X.PLANACAO_QUANDO,X.PLANACAO_DATAHORA,X.PLANACAO_DATAHORA_EXEC
              
                FROM (
                      SELECT FIRST 1
                      IIF( R.PERFIL1_A > R.PERFIL1_B, R.PERFIL1_A , R.PERFIL1_B ) AS MAIOR_1,
                      IIF( R.PERFIL2_A > R.PERFIL2_B, R.PERFIL2_A , R.PERFIL2_B ) AS MAIOR_2,
                      IIF( R.PERFIL3_A > R.PERFIL3_B, R.PERFIL3_A , R.PERFIL3_B ) AS MAIOR_3,

                      IIF( R.PERFIL1_A < R.PERFIL1_B, R.PERFIL1_A , R.PERFIL1_B ) AS MENOR_1,
                      IIF( R.PERFIL2_A < R.PERFIL2_B, R.PERFIL2_A , R.PERFIL2_B ) AS MENOR_2,
                      IIF( R.PERFIL3_A < R.PERFIL3_B, R.PERFIL3_A , R.PERFIL3_B ) AS MENOR_3
                      
                      ,R.PERFIL1_A
                      ,R.PERFIL1_B

                      ,R.PERFIL2_A
                      ,R.PERFIL2_B

                      ,R.PERFIL3_A
                      ,R.PERFIL3_B
                      
                      ,J.OBSERVACAO
  
                      ,J.PLANACAO_STATUS,J.PLANACAO_QUEM,J.PLANACAO_COMO,J.PLANACAO_QUANDO,J.PLANACAO_DATAHORA,J.PLANACAO_DATAHORA_EXEC
                      
                       FROM TBBSC_INDICADORES R, TBBSC_REGISTRO_INDICADOR I,TBBSC_REGISTRO J WHERE I.ID = :ID1
                       AND I.BSC_ID = R.ID
                       AND J.BSC_ID = R.ID
                       AND J.ID = :ID2
              ) X';
            
            $args = array(
                ':ID1' =>  $indicador,
                ':ID2' =>  $id,
            );
            
            $retorno = $con->query($sql, $args);
 
            if (empty($retorno)){log_erro('FAIXA DO INDICADOR NÃO ENCONTRADA');} 
            
            $maior                  = $retorno[0]->MAIOR;
            $menor                  = $retorno[0]->MENOR;
            $planacaoStatus         = $retorno[0]->PLANACAO_STATUS;
            
            $observacao             = $retorno[0]->OBSERVACAO;
            
            $perfil1_a              = $retorno[0]->PERFIL1_A;
            $perfil1_b              = $retorno[0]->PERFIL1_B;
            
            if (isset($valor)){} else{ log_erro('VALOR NÃO DEFINIDO');}
            if (isset($id)){} else{ log_erro('ID NÃO DEFINIDO');}
            
            if ($valor > $maior){log_erro('O VALOR MÁXIMO PERMITIDO É '.$maior);}
            if ($valor < $menor){log_erro('O VALOR MÍNIMO PERMITIDO É '.$menor);}
            
            //0 => Não necessário ; 1 => Pendente ; 2 => Realizado ; 3 => Executado ; 4 => Auditado
            if ($planacaoStatus > 1){log_erro('ESTE ITEM NÃO PODERÁ SER ALTERADO, POIS JÁ EXISTE UM PLANO DE AÇÃO EM EXECUÇÃO');}
            
            if($perfil1_a <= $perfil1_b){
                if (($valor >= $perfil1_a) & ($valor <= $perfil1_b)){
                  $plano_acao = 0;  
                }else{
                  if (strlen($observacao) == 0){  
                    $plano_acao = 1;
                  }else{
                    $plano_acao = 2;
                  }
                }
            }else{
                if (($valor <= $perfil1_a) & ($valor >= $perfil1_b)){
                  $plano_acao = 0;  
                }else{
                  if (strlen($observacao) == 0){  
                    $plano_acao = 1;
                  }else{
                    $plano_acao = 2;
                  }  
                }
            }
            
            if($plano == 1){$plano_acao = 0;}
            
            $desc ='';
            
            
            if ($plano_acao == 0){
                
                if($plano == 0){
                    $sql = 'UPDATE TBBSC_REGISTRO SET VALOR = :VALOR, PLANACAO_STATUS = :PLANACAO_STATUS WHERE ID = :ID';
                    $args = array(
                            0 => array('TIPO' => 'INT', 'PARN' => ':ID'              ,'VALOR' => $id),
                            1 => array('TIPO' => 'INT', 'PARN' => ':VALOR'           ,'VALOR' => $valor),
                            2 => array('TIPO' => 'INT', 'PARN' => ':PLANACAO_STATUS' ,'VALOR' => 0)
                    );
                    
                    $desc = 'verde';
                    
                }else{
                    if (strlen($descPlano) < 10){log_erro('OBSERVAÇÃO MENOR DO QUE 10 CARACTERES');}
                    
                    $sql = 'UPDATE TBBSC_REGISTRO SET VALOR = :VALOR, OBSERVACAO = :DESC , PLANACAO_STATUS = :PLANACAO_STATUS  WHERE ID = :ID';
                    $args = array(
                            0 => array('TIPO' => 'INT', 'PARN' => ':ID',               'VALOR' => $id),
                            1 => array('TIPO' => 'INT', 'PARN' => ':VALOR',            'VALOR' => $valor),
                            2 => array('TIPO' => 'INT', 'PARN' => ':PLANACAO_STATUS',  'VALOR' => 1),
                            3 => array('TIPO' => 'LOB', 'PARN' => ':DESC',             'VALOR' => utf8_decode($descPlano))
                    );
                    
                  $desc = 'vermelho';      
                }

                $con->executeParan($sql, $args);
                $con->commit();

            }else{
                
                $desc = self::consultaDescricaoFaixa($idIndicador, $idDetalhe, $valor);

            }
            
            return array('PLANO'=>$plano_acao,'DESC'=>$desc);
            
        } catch (Exception $e) {
        	$con->rollback(); 
        	throw $e;
        }
    }
    
    /**
	 * Filtrar faixas de um indicador.
	 * Função chamada via Ajax.
	 *
	 * @param string $filtro
	 * @return array
	 */
	public static function filtrarFaixa($id) {
        
		$con = new _Conexao();
        
        return self::listaFaixas($con,$id);
	}
    
    public static function listaFaixas(_Conexao $con,$id){

		$sql = /** @lang text */
			"SELECT
                X.ID,
                X.BSC_ID,
                X.ID AS BSC_DETALHE_ID,
                X.DESCRICAO,
                X.PESO,
                IIF( X.MAIOR_1 > MAIOR_2, IIF( X.MAIOR_1 > MAIOR_3, MAIOR_1 , MAIOR_3 ) , IIF( X.MAIOR_3 > MAIOR_3, MAIOR_3 , MAIOR_3 ) ) AS MAIOR,
                IIF( X.MENOR_1 < MENOR_2, IIF( X.MENOR_1 < MENOR_3, MENOR_1 , MENOR_3 ) , IIF( X.MENOR_3 < MENOR_3, MENOR_3 , MENOR_3 ) ) AS MENOR,
                x.MAIOR_1,
                x.MENOR_1
                
            FROM (
                    SELECT                             
                    D.ID,                              
                    D.BSC_ID,                          
                    D.ID AS BSC_DETALHE_ID,            
                    D.DESCRICAO,                       
                    D.PESO,                            
                    I.PERFIL1_B VALORMAX,              
                    CAST(NULL AS NUMERIC(15,5))VALOR,  
                    
                    IIF( I.PERFIL1_A > I.PERFIL1_B, I.PERFIL1_A , I.PERFIL1_B ) AS MAIOR_1,
                    IIF( I.PERFIL2_A > I.PERFIL2_B, I.PERFIL2_A , I.PERFIL2_B ) AS MAIOR_2,
                    IIF( I.PERFIL3_A > I.PERFIL3_B, I.PERFIL3_A , I.PERFIL3_B ) AS MAIOR_3,

                    IIF( I.PERFIL1_A < I.PERFIL1_B, I.PERFIL1_A , I.PERFIL1_B ) AS MENOR_1,
                    IIF( I.PERFIL2_A < I.PERFIL2_B, I.PERFIL2_A , I.PERFIL2_B ) AS MENOR_2,
                    IIF( I.PERFIL3_A < I.PERFIL3_B, I.PERFIL3_A , I.PERFIL3_B ) AS MENOR_3,

                    CAST(NULL AS BLOB) OBSERVACAO,     
                    (0) FLAG,                          
                    CAST(0 AS Char(1)) PLANACAO_STATUS,
                    D.SEQUENCIA                        
                    FROM                               
                    TBBSC_DETALHE D,                   
                    TBBSC_INDICADORES I                
                    WHERE                              
                    D.BSC_ID = :ID            
                    AND I.ID = D.BSC_ID                
                    and D.STATUS = '1'
                    ORDER BY D.SEQUENCIA                        
        ) X";

		$args = array(
			':ID'	=> $id
		);

        return $con->query($sql, $args);
	}
    
    public static function consultaDescricaoFaixa($indicador,$idDetalhe,$valor){
        
        $con = new _Conexao();
        
		$sql = 'SELECT first 1
                            F.DESCRICAO
                        FROM
                            TBBSC_DESCRICAO_FAIXA F

                        WHERE
                            F.INDICADOR_ID = :INDICADOR_ID
                        AND F.DETALHE_ID   = :DETALHE_ID
                        AND F.FAIXA_1     >= :VALOR1
                        AND F.FAIXA_2     <= :VALOR2';

                $args = array(
                    ':INDICADOR_ID' => $indicador,
                    ':DETALHE_ID' => $idDetalhe,
                    ':VALOR1' => $valor,
                    ':VALOR2' => $valor    
                );

                $retorno = $con->query($sql, $args);
 
                if (empty($retorno)){
                  $desc ='';
                }else{
                  $desc = $retorno[0]->DESCRICAO;  
                }

        return $desc;
	}
    
    public static function consultaDescricaoFaixas($indicador,$idDetalhe){
        
        $con = new _Conexao();
        
		$sql = 'SELECT 

                        F.FAIXA_1,
                        F.DESCRICAO
                        FROM
                            TBBSC_DESCRICAO_FAIXA F

                        WHERE
                            F.INDICADOR_ID = :INDICADOR_ID
                        AND F.DETALHE_ID   = :DETALHE_ID';

                $args = array(
                    ':INDICADOR_ID' => $indicador,
                    ':DETALHE_ID' => $idDetalhe   
                );

                $retorno = $con->query($sql, $args);
                
        return $retorno;
	}
    
    
      
}
