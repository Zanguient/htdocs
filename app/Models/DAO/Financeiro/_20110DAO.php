<?php

namespace App\Models\DAO\Financeiro;

use App\Models\Conexao\_Conexao;

/**
 * DAO do objeto _20110 - Relatorio de Extrato de Caixa/Bancos
 */
class _20110DAO {

    /**
     * Consultar Bancos
     */
    public static function ConsultarBancos($filtro,$con){

        $banco1 = 0;
        $banco2 = 999;

        $DATA1 = date('d.m.Y', strtotime($filtro['DATA1']));
        $DATA2 = date('d.m.Y', strtotime($filtro['DATA2']));
        $BANCO = $filtro['BANCO']['ID'];
        $TODOS = $filtro['TODOS'];

        if($TODOS == true){
            $DATA1 = date('d.m.Y', strtotime('01/01/2000'));
            $DATA2 = date('d.m.Y', strtotime('31/12/3000'));
        }

        if($BANCO > 0){
            $banco1 = $BANCO;
            $banco2 = $BANCO;    
        }

        $sql = 'SELECT
                    *
                from(
                    SELECT A.Codigo, A.Nome, A.Nome as Nome2,
                    Coalesce((Select First 1 Cast(S.SALDO as Numeric(15,2))
                                 from TbCaixaBanco_Saldo_Diario S
                                Where A.Codigo = S.Codigo
                                  and S.Data = (Select First 1 (L.Data_Lancamento)
                                                  From TbCaixaBanco_Lancamento L
                                                 Where L.CaixaBanco_Codigo = A.Codigo
                                                 Order By L.Data_Lancamento Desc)),0) SALDO,
                    
                     A.Natureza, A.SistemaCompensacao
                    From TbCaixaBanco A
                    Where A.Codigo between :BANCO1 and :BANCO2
                    and a.codigo > 0
                    
                    union
                    
                    select
                        Codigo,
                        Nome,
                        Nome2,
                        sum(SALDO) as SALDO,
                        Natureza, SistemaCompensacao
                    from
                    (SELECT 0 Codigo, \'SALDO DOS CAIXAS E BANCOS  *************** \' as Nome , \'ZZZZZZZZZZZ\' as Nome2,
                    Coalesce((Select First 1 Cast(S.SALDO as Numeric(15,2))
                                 from TbCaixaBanco_Saldo_Diario S
                                Where A.Codigo = S.Codigo
                                  and S.Data = (Select First 1 (L.Data_Lancamento)
                                                  From TbCaixaBanco_Lancamento L
                                                 Where L.CaixaBanco_Codigo = A.Codigo
                                                 Order By L.Data_Lancamento Desc)),0) SALDO,
                    
                     0 Natureza, 0 SistemaCompensacao
                    From TbCaixaBanco A
                    Where A.Codigo between :BANCO3 and :BANCO4) group by 1,2,3,5,6
                )
                Order By Nome2';

        $args = array(
            ':BANCO1' => $banco1,
            ':BANCO2' => $banco2,
            ':BANCO3' => $banco1,
            ':BANCO4' => $banco2,
        );

        $ret = $con->query($sql, $args);
        
        return $ret;

    }

    /**
     * Consultar Negociados
     */
    public static function ConsultarNegociados($filtro,$con){

        $banco1 = 0;
        $banco2 = 999;

        $DATA1 = date('d.m.Y', strtotime($filtro['DATA1']));
        $DATA2 = date('d.m.Y', strtotime($filtro['DATA2']));
        $BANCO = $filtro['BANCO']['ID'];
        $TODOS = $filtro['TODOS'];

        if($TODOS == true){
            $DATA1 = '01.01.2000';
            $DATA2 = '01.01.3000';
        }

        if($BANCO > 0){
            $banco1 = $BANCO;
            $banco2 = $BANCO;    
        }

        $sql = 'SELECT
                    FN_DIA_SEMANA(fn_dia_util(A.Data_Vencimento)) as DIA_SEMANA,
                    FORMATDATE(fn_dia_util(A.Data_Vencimento)) as Data_Fluxo2,
                    FORMATDATE(A.Data_Vencimento) as DATA_D,
                    A.Data_Vencimento, lpad(A.Controle,8,0) as Controle, A.Valor, A.Valor_Saldo, A.Conta_Receber_Banco, A.Conta_Receber_Posicao, fn_dia_util(A.Data_Vencimento) as Data_Fluxo,
                (Select First 1 B.SistemaCompensacao from TbCaixaBanco B Where A.Conta_Receber_Banco = B.Codigo) SistemaCompensacao,
                A.Numero_NotaFiscal, A.Empresa_Codigo, A.Conta_Receber_Cobranca,
                (Select First 1 C.RazaoSocial from TbEmpresa C Where A.Empresa_Codigo = C.Codigo) Empresa_RazaoSocial,
                (Select First 1  D.Descricao from TbConta_Receber_Cobranca  D Where A.Conta_Receber_Cobranca = D.Codigo) Cobranca_Descricao,
                (Select First 1 E.Nome from TbCaixaBanco E Where A.Conta_Receber_Banco = E.Codigo) Banco_Descricao

                From TbNfS_Parcelamento A
                Where A.Conta_Receber_Posicao = Cast(Coalesce((Select First 1 CN.Valor_EXT From TbControle_N CN Where CN.id = 134),999) as integer)
                and A.Valor_Saldo > 0 and A.Situacao = 1 and Aceite = \'S\'
                and (A.Data_Vencimento between :DATA1 and :DATA2)
                and (A.Conta_Receber_Banco between :BANCO1 and :BANCO2)

                Order By A.Data_Vencimento';

        $args = array(
            ':BANCO1' => $banco1,
            ':BANCO2' => $banco2,
            ':DATA1'  => $DATA1,
            ':DATA2'  => $DATA2
        );

        $ret = $con->query($sql, $args);
        
        return $ret;
    }

    /**
     * Consulta Provisoes
     */
    public static function ConsultaProvisoes($filtro,$con){

        $banco1 = 0;
        $banco2 = 999;

        $DATA1 = date('d.m.Y', strtotime($filtro['DATA1']));
        $DATA2 = date('d.m.Y', strtotime($filtro['DATA2']));
        $BANCO = $filtro['BANCO']['ID'];
        $TODOS = $filtro['TODOS'];

        if($TODOS == true){
            $DATA1 = '01.01.2000';
            $DATA2 = '01.01.3000';
        }

        if($BANCO > 0){
            $banco1 = $BANCO;
            $banco2 = $BANCO;    
        }

        $sql = 'SELECT
                    FN_DIA_SEMANA(fn_dia_util(A.Data_Previsao)) as DIA_SEMANA,
                    FORMATDATE(fn_dia_util(A.Data_Previsao)) as Data_Fluxo2,
                    FORMATDATE(A.Data_Previsao) as DATA_D,
                    A.Data_Previsao, lpad(A.Controle,8,0) as Controle, A.Tipo, A.Valor_Total,fn_dia_util(A.Data_Previsao) as Data_Fluxo, A.Historico
                From TbCaixaBanco_Previsao A, TbCaixaBanco G
                Where A.CaixaBanco_Codigo between :BANCO1 and :BANCO2
                     and A.CaixaBanco_Codigo = G.Codigo
                     and A.Data_Previsao between :DATA1 and :DATA2
                     and A.Valor_Total > 0
                     and A.Data_Previsao >= current_date
                Order By A.Data_Previsao';

        $args = array(
            ':DATA1'  => $DATA1,
            ':DATA2'  => $DATA2,
            ':BANCO1' => $banco1,
            ':BANCO2' => $banco2
        );

        $ret = $con->query($sql, $args);
        
        return $ret;
    }

    /**
     * Consultar Conta Pagar
     */
    public static function ConsultarContaPagar($filtro,$con){

        $banco1 = 0;
        $banco2 = 999;

        $DATA1 = date('d.m.Y', strtotime($filtro['DATA1']));
        $DATA2 = date('d.m.Y', strtotime($filtro['DATA2']));
        $BANCO = $filtro['BANCO']['ID'];
        $TODOS = $filtro['TODOS'];

        if($TODOS == true){
            $DATA1 = '01.01.2000';
            $DATA2 = '01.01.3000';
        }

        if($BANCO > 0){
            $banco1 = $BANCO;
            $banco2 = $BANCO;    
        }

        $sql = 'SELECT
                    FN_DIA_SEMANA(fn_dia_util(A.Data_Vencimento)) as DIA_SEMANA,
                    FORMATDATE(fn_dia_util(A.Data_Vencimento)) as Data_Fluxo2,
                    FORMATDATE(A.Data_Vencimento) as DATA_D,
                    A.Data_Vencimento,lpad(A.Controle,8,0) as Controle, A.Valor_Saldo, fn_dia_util(A.Data_Vencimento) as Data_Fluxo,
                    lpad(A.Numero_NotaFiscal,8,0) as Numero_NotaFiscal, A.Empresa_Codigo, 
                (Select First 1 C.RazaoSocial from TbEmpresa C Where A.Empresa_Codigo = C.Codigo) Empresa_RazaoSocial

                From TbNfE_Parcelamento A
                Where A.Valor_Saldo > 0 and A.Situacao = 1 and A.Aceite = \'S\'
                and (A.Data_Vencimento between :DATA1 AND :DATA2)

                Order By A.Data_Vencimento
                ';

        $args = array(
            ':DATA1'  => $DATA1,
            ':DATA2'  => $DATA2
        );

        $ret = $con->query($sql, $args);
        
        return $ret;
    }

    /**
     * Consultar Conta Receber
     */
    public static function ConsultarContaReceber($filtro,$con){

        $banco1 = 0;
        $banco2 = 999;

        $DATA1 = date('d.m.Y', strtotime($filtro['DATA1']));
        $DATA2 = date('d.m.Y', strtotime($filtro['DATA2']));
        $BANCO = $filtro['BANCO']['ID'];
        $TODOS = $filtro['TODOS'];

        if($TODOS == true){
            $DATA1 = '01.01.2000';
            $DATA2 = '01.01.3000';
        }

        if($BANCO > 0){
            $banco1 = $BANCO;
            $banco2 = $BANCO;    
        }

        $sql = 'SELECT

                    fn_dia_util(h.Data_Fluxo) as Data_Fluxo,
                    formatdate(fn_dia_util(h.Data_Fluxo)) as Data_Fluxo2,
                    FN_DIA_SEMANA(fn_dia_util(h.Data_Fluxo)) as DIA_SEMANA,
                    formatdate(h.Data_Vencimento) as DATA_D,
                    h.*

                from(
                    SELECT
                     
                        A.Data_Vencimento, A.Controle, A.Valor_Saldo, A.Conta_Receber_Banco, A.Conta_Receber_Posicao,
                        dateadd(day,coalesce((SELECT c.sistemacompensacao from tbcaixabanco c where c.codigo = 94),0), fn_dia_util(A.Data_Vencimento)) as Data_Fluxo,
                        (Select First 1 B.SistemaCompensacao from TbCaixaBanco B Where A.Conta_Receber_Banco = B.Codigo) SistemaCompensacao,
                        lpad(A.Numero_NotaFiscal,8,0) as Numero_NotaFiscal, A.Empresa_Codigo, A.Conta_Receber_Cobranca,
                        (Select First 1 C.RazaoSocial from TbEmpresa C Where A.Empresa_Codigo = C.Codigo) Empresa_RazaoSocial,
                        (Select First 1 D.Descricao from TbConta_Receber_Cobranca  D Where A.Conta_Receber_Cobranca = D.Codigo) Cobranca_Descricao
                    
                    From TbNfS_Parcelamento A

                    Where A.Conta_Receber_Posicao <> Cast(Coalesce((Select First 1 CN.Valor_EXT From TbControle_N CN Where CN.id = 134),999) as integer)
                    and A.Valor_Saldo > 0 and a.Situacao = 1 and Aceite = \'S\'
                    and (A.Data_Vencimento between :DATA1 AND :DATA2)
                    and (A.Conta_Receber_Banco between :BANCO1 AND :BANCO2)

                    Order By A.Data_Vencimento
                ) h

        ';

        $args = array(
            ':DATA1'  => $DATA1,
            ':DATA2'  => $DATA2,
            ':BANCO1' => $banco1,
            ':BANCO2' => $banco2
        );

        $ret = $con->query($sql, $args);
        
        return $ret;
    }

    /**
     * Consultar Ordens Compra
     */
    public static function ConsultarOrdensCompra($filtro,$con){

        $banco1 = 0;
        $banco2 = 999;

        $DATA1 = date('d.m.Y', strtotime($filtro['DATA1']));
        $DATA2 = date('d.m.Y', strtotime($filtro['DATA2']));
        $BANCO = $filtro['BANCO']['ID'];
        $TODOS = $filtro['TODOS'];

        if($TODOS == true){
            $DATA1 = '01.01.2000';
            $DATA2 = '01.01.3000';
        }

        if($BANCO > 0){
            $banco1 = $BANCO;
            $banco2 = $BANCO;    
        }

        $sql = 'SELECT

                    j.*,
                    fn_dia_util(j.Data) as Data_Fluxo,
                    FN_DIA_SEMANA(fn_dia_util(j.Data)) as DIA_SEMANA,
                    FORMATDATE(fn_dia_util(j.Data)) as Data_Fluxo2,
                    FORMATDATE(j.Data) as DATA_D
                from(                    
                    SELECT

                        (Case When extract(weekday from OI.DATA_SAIDA+A.Prazo) = 0 Then OI.DATA_SAIDA+A.Prazo+1 else
                        (Case When extract(weekday from OI.DATA_SAIDA+A.Prazo) = 6 Then OI.DATA_SAIDA+A.Prazo+2 else OI.DATA_SAIDA+A.Prazo end) end) Data,

                        lpad(O.OC,8,0) as OC, O.Fornecedor_Codigo,
                        Max((Select First 1 E.RazaoSocial||\'(\'||E.Uf||\')\' From TbEmpresa E Where O.Fornecedor_Codigo = E.Codigo)) Fornecedor,
                        Sum(CAST ( (CAST ( (Cast((OI.VALOR * OS.QUANTIDADE) as Numeric(18,4))) + (OI.Acrescimo - OI.Desconto) +
                                 ( (CAST((OI.VALOR  * OI.QUANTIDADE) as Numeric(18,4))) * (Cast((OI.Ipi/100) as numeric(10,4))) ) AS NUMERIC(18,2) ) *
                                 (Cast((A.Quota/100) as numeric(18,4))) )  AS NUMERIC(18,2)) ) Valor

                        From TbPagamento_Condicao_Prazo A, TbOc O, TbOc_Item OI, TbOC_Item_Saldo OS
                        Where A.condicaopagamento_codigo = O.pagamento_condicao
                          and O.Oc = OI.Oc
                          and O.Oc = OS.OC
                          and OI.controle = OS.oc_item_controle
                          and O.Status = \'1\'
                          and OI.Situacao = 1
                          and O.Autorizacao = \'2\'

                        Group by 1,2,3
                        Order by 1,2
                    ) j

                where Data between :DATA1 AND :DATA2

            ';

        $args = array(
            ':DATA1'  => $DATA1,
            ':DATA2'  => $DATA2
        );

        $ret = $con->query($sql, $args);
        
        return $ret;
    }

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