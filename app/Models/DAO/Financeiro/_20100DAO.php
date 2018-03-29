<?php

namespace App\Models\DAO\Financeiro;

use App\Models\Conexao\_Conexao;

/**
 * DAO do objeto _20100 - Relatorio de Extrato de Caixa/Bancos
 */
class _20100DAO {

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
    public static function ConsultarBanco($filtro,$con) {
        
        try {

            $FILTRAGEM = $filtro['FILTRO'];

            $sql = "SELECT
                        c.codigo as ID,
                        c.nome as DESCRICAO
                    from tbcaixabanco c
                    where c.codigo||'-'||c.nome like '%".$FILTRAGEM."%'

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
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarExtrato($filtro,$con) {
        
        try {

            $DATA1 = date('d.m.Y', strtotime($filtro['DATA1']));
            $DATA2 = date('d.m.Y', strtotime($filtro['DATA2']));
            $BANCO = $filtro['BANCO']['ID'];

            $sql = "SELECT 
                        B.*,
                        (SALDO_ANTERIOR - ACUMULADO_CREDITO + ACUMULADO_DEBITO) as SALDO
                    FROM
                        (SELECT

                            DATA_LANCAMENTO,
                            formatdate(DATA_LANCAMENTO) as DATA,
                            HISTORICO,
                            NATUREZA,
                            CAIXABANCO_CODIGO,
                            CONTROLE,
                            NUMERO_CHEQUE,
                            TIPO,
                            CAIXABANCO_NOME,
                            VALOR_TOTAL,
                            VALOR_DEBITO,
                            VALOR_CREDITO,
                            SALDO_ANTERIOR,
                            SALDO_DIARIO,
                            sum(VALOR_DEBITO) over(Order By Data_Lancamento, Controle) as ACUMULADO_DEBITO,
                            sum(VALOR_CREDITO) over(Order By Data_Lancamento, Controle) as ACUMULADO_CREDITO,
                            sum(VALOR_DEBITO)  over(PARTITION BY Data_Lancamento Order By Data_Lancamento, Controle) as ACUMULADO_DEBITO_DIA,
                            sum(VALOR_CREDITO) over(PARTITION BY Data_Lancamento Order By Data_Lancamento, Controle) as ACUMULADO_CREDITO_DIA

                        from
                        (
                            Select A.DATA_LANCAMENTO, A.HISTORICO, A.NATUREZA,  
                            
                            A.CAIXABANCO_CODIGO, A.CONTROLE, A.NUMERO_CHEQUE, A.TIPO, 
                            
                            (Select First 1 C.Nome from TbCaixaBanco C Where A.CAIXABANCO_CODIGO = C.Codigo) Caixabanco_Nome,
                            
                            Cast(A.VALOR_TOTAL as Numeric(15,2)) Valor_Total,
                            (Case when A.Natureza = 'D' Then Cast(A.VALOR_TOTAL as Numeric(15,2)) else 0 end) Valor_Debito,
                            (Case when A.Natureza = 'C' Then Cast(A.VALOR_TOTAL as Numeric(15,2)) else 0 end) Valor_Credito,
                            
                            coalesce(j.Saldo_Anterior,0) as Saldo_Anterior,
                            
                            Coalesce((Select First 1 Cast(B.Saldo as Numeric(15,2)) from TbCaixaBanco_Saldo_Diario B
                            Where B.Codigo = :BANCO2 and B.Data = A.Data_Lancamento),0) Saldo_Diario
                            
                            From TbCaixaBanco_Lancamento A,
                            
                            (Select First 1 Cast(B.Saldo as Numeric(15,2)) as Saldo_Anterior from TbCaixaBanco_Saldo_Diario B
                            Where B.Codigo = :BANCO3 and B.Data < :DATA3 Order By B.Data Desc) j
                            
                            
                            Where A.CaixaBanco_Codigo = :BANCO1
                                and A.Data_Lancamento Between :DATA1 and :DATA2
                                and A.Valor_Total > 0
                            
                            Order By A.Data_Lancamento, A.Controle
                        ) Order By Data_Lancamento, Controle
                    ) B
            ";

            $args = array(
                ':DATA1'  => $DATA1,
                ':DATA2'  => $DATA2,
                ':DATA3'  => $DATA1,
                ':BANCO1' => $BANCO,
                ':BANCO2' => $BANCO,
                ':BANCO3' => $BANCO,
            );

            $ret = $con->query($sql, $args);
            
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
    public static function ConsultarDetalhe($filtro,$con) {
        
        try {

            $DATA1 = date('d.m.Y', strtotime($filtro['DATA1']));
            $DATA2 = date('d.m.Y', strtotime($filtro['DATA2']));
            $BANCO = $filtro['BANCO']['ID'];

            $sql = "SELECT
                        A.CLASSIFICACAO_CONTABIL,
                        A.CONTROLE,
                        A.Historico_Contabil,
                        A.CaixaBanco_Controle,
                        A.Conta_Contabil,
                        A.Tipo,
                        CAST(A.VALOR AS NUMERIC(15,2)) VALOR,
                        (SELECT First 1 B.DESCRICAO FROM TBCLASSIFICACAO_CONTABIL B WHERE A.Classificacao_Contabil = B.CODIGO) CLASSIFICACAO_DESCRICAO

                    From TbCaixaBanco_Lancamento_Class A,
                    (
                        Select

                            A.CONTROLE,
                            iif(A.Natureza = 'C','D','C') as TIPO

                        From TbCaixaBanco_Lancamento A
                        
                        Where A.CaixaBanco_Codigo = :BANCO1
                            and A.Data_Lancamento Between :DATA1 and :DATA2
                            and A.Valor_Total > 0
                    ) Z


                    Where A.CaixaBanco_Controle = z.Controle
                    And (A.Tipo = z.Tipo or A.Tipo Is Null)

                     order by A.CaixaBanco_Controle
            ";

            $args = array(
                ':DATA1'  => $DATA1,
                ':DATA2'  => $DATA2,
                ':BANCO1' => $BANCO,
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
        
        try {

            $sql = 'SELECT \'TELA 100% FUNCIONAL\' as FRASE from RDB$DATABASE WHERE 0 = :ID';

            $args = array(
                ':ID' => $filtro['ID'],
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