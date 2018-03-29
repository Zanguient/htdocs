<?php

namespace App\Models\DAO\Vendas;

use App\Models\Conexao\_Conexao;

/**
 * DAO do objeto _12050 - RELATORIO DE PEDIDOS X FATURAMENTO X PRODUCAO
 */
class _12050DAO {

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
     * pedidos
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function pedidos($dados,$con) {

        $entrada = '';
        if($dados['periodo_pedido'] == 'd'){
            $entrada = 'Data';
        }else{
            $entrada = 'Data_Cliente';
        }
        
        try {
            
            $data_inicio = date('d.m.Y', strtotime($dados['periodo_inicial']));
            $data_fim    = date('d.m.Y', strtotime($dados['periodo_final']));
            
            $sql = "
                SELECT Distinct X.DATA, Sum(X.QUANTIDADE) as QUANTIDADE, Sum(X.QUANTIDADE1)
                From (
                Select B.".$entrada." DATA, IIF(A.Situacao = 3, A.Quantidade - D.Quantidade, A.Quantidade) Quantidade, A.Quantidade Quantidade1
                  From TbPedido_Item A, TbPedido B, TbPedido_Item_Saldo D, tbproduto p
                    Where A.Pedido = B.Pedido
                    and A.Controle = D.pedido_item_controle
                    and B.Status = 1
                    and B.Situacao = 1
                    and p.codigo = a.produto_codigo
                    and B.Estabelecimento_Codigo = :ESTABELECIMENTO
                    and p.familia_codigo = :FAMILIA
                   and B.".$entrada." between '".$data_inicio."' and '".$data_fim."'
                ) X
                Group By 1";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias']
            );

            $ret = $con->query($sql, $args);
            
            return $ret;
            
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * pedidos dia
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function pedidosDia($dados) {

        $con = new _Conexao();

        $entrada = '';
        if($dados['periodo_pedido'] == 'd'){
            $entrada = 'Data';
        }else{
            $entrada = 'Data_Cliente';
        }
        
        try {
            
            $data_inicio = date('d.m.Y', strtotime($dados['periodo_inicial']));
            $data_fim    = date('d.m.Y', strtotime($dados['periodo_final']));
            
            $sql = "
                SELECT

                X.CLIENTE,
                Sum(X.QUANTIDADE1) as QUANTIDADE

                From (

                Select

                    lpad( p.codigo,6,'0')||' - '||P.razaosocial||' ('||p.UF||')' as CLIENTE,
                    IIF(A.Situacao = 3, A.Quantidade - D.Quantidade, A.Quantidade) Quantidade1,
                    A.Quantidade Quantidade2

                  From TbPedido_Item A, TbPedido B, TbPedido_Item_Saldo D,tbcliente P
                 Where A.Pedido = B.Pedido
                   and A.Controle = D.pedido_item_controle
                   and B.Status = 1
                   and B.Situacao = 1
                   and B.Estabelecimento_Codigo = :ESTABELECIMENTO
                   and B.Familia_Codigo = :FAMILIA
                   and P.codigo = a.cliente_codigo
                   and B.".$entrada." between '".$data_inicio."' and '".$data_fim."'

                ) X
                Group By 1
                order by QUANTIDADE desc";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias']
            );

            $ret = $con->query($sql, $args);

            $sql = "
                SELECT REPRESENTANTE, List(EMPRESA_UF) UF, Sum(QUANTIDADE) Quantidade
                From(
                SELECT

                    X.REPRESENTANTE,
                    X.Empresa_uf,
                    X.UF,
                    Sum(X.QUANTIDADE1) as QUANTIDADE

                    From (

                    Select

                        lpad( e.codigo,6,'0')||' - '||e.nomefantasia||' ('||E.UF||')' REPRESENTANTE, e.uf, p.uf as Empresa_uf,
                        IIF(A.Situacao = 3, A.Quantidade - D.Quantidade, A.Quantidade) Quantidade1,
                        A.Quantidade Quantidade2

                      From TbPedido_Item A, TbPedido B, TbPedido_Item_Saldo D,tbcliente P, tbempresa e
                     Where A.Pedido = B.Pedido
                       and A.Controle = D.pedido_item_controle
                       and B.Status = 1
                       and B.Situacao = 1
                       and B.Estabelecimento_Codigo = :ESTABELECIMENTO
                       and B.Familia_Codigo = :FAMILIA
                       and P.codigo = a.cliente_codigo
                       and e.codigo = p.representante_codigo
                       and B.".$entrada." between '".$data_inicio."' and '".$data_fim."'

                    ) X
                    Group By 1,2,3
                ) Z Group By 1 order by 3 desc";

            $ret2 = $con->query($sql, $args);

            $sql = "
                SELECT

                X.UF,
                Sum(X.QUANTIDADE1) as QUANTIDADE

                From (

                Select

                    lpad( s.codigo,6,'0')||' - '||s.razaosocial||' ('||s.UF||')' as CLIENTE,p.uf,
                    IIF(A.Situacao = 3, A.Quantidade - D.Quantidade, A.Quantidade) Quantidade1,
                    A.Quantidade Quantidade2

                  From TbPedido_Item A, TbPedido B, TbPedido_Item_Saldo D,tbcliente P, tbrepresentante s
                 Where A.Pedido = B.Pedido
                   and A.Controle = D.pedido_item_controle
                   and B.Status = 1
                   and B.Situacao = 1
                   and B.Estabelecimento_Codigo = :ESTABELECIMENTO
                   and B.Familia_Codigo = :FAMILIA
                   and P.codigo = a.cliente_codigo
                   and S.codigo = p.representante_codigo
                   and B.".$entrada." between '".$data_inicio."' and '".$data_fim."'

                ) X
                Group By 1
                order by QUANTIDADE desc";

            $ret3 = $con->query($sql, $args);

            $sql = "
                SELECT

                   DESCRICAO,
                   SUM(QUANTIDADE) AS QUANTIDADE

                FROM
                (SELECT

                    (SELECT FIRST 1 P.DESCRICAO FROM TBPERFIL P WHERE P.ID = X.PERFIL AND P.TABELA = 'SKU' and p.familia_id = :FAMILIA3) AS DESCRICAO,
                    X.QUANTIDADE1 AS QUANTIDADE

                FROM

                    (SELECT

                        A.PERFIL,

                        IIF(A.SITUACAO = 3, A.QUANTIDADE - D.QUANTIDADE, A.QUANTIDADE) QUANTIDADE1,
                        A.QUANTIDADE QUANTIDADE2

                      FROM TBPEDIDO_ITEM A, TBPEDIDO B, TBPEDIDO_ITEM_SALDO D
                     WHERE A.PEDIDO = B.PEDIDO
                       AND A.CONTROLE = D.PEDIDO_ITEM_CONTROLE
                       AND B.STATUS = 1
                       AND B.SITUACAO = 1
                       AND B.ESTABELECIMENTO_CODIGO = :ESTABELECIMENTO
                       AND B.FAMILIA_CODIGO = :FAMILIA
                       AND B.".$entrada." between '".$data_inicio."' and '".$data_fim."'
                    ) X
                  ) Y
                  
                  WHERE QUANTIDADE > 0
                  GROUP BY 1
                  ORDER BY QUANTIDADE DESC";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias'],
                ':FAMILIA3'         => $dados['familias']
            );

            $ret4 = $con->query($sql, $args);
            
            $sql = "
                SELECT
                    coalesce((select l.descricao from tbmodelo_linha l where l.codigo = x.LINHA_CODIGO),'') as DESCRICAO,
                    sum(QUANTIDADE1) as QUANTIDADE
                from(
                    SELECT
                    
                        p.linha_codigo,
                        p.cor_codigo,
                        IIF(A.SITUACAO = 3, A.QUANTIDADE - D.QUANTIDADE, A.QUANTIDADE) QUANTIDADE1,
                        A.QUANTIDADE QUANTIDADE2
                    
                    FROM TBPEDIDO_ITEM A, TBPEDIDO B, TBPEDIDO_ITEM_SALDO D, tbproduto p
                    WHERE A.PEDIDO = B.PEDIDO
                        AND A.CONTROLE = D.PEDIDO_ITEM_CONTROLE
                        AND B.STATUS = 1
                        AND B.SITUACAO = 1
                        AND B.ESTABELECIMENTO_CODIGO = :ESTABELECIMENTO
                        AND B.FAMILIA_CODIGO = :FAMILIA
                        AND B.".$entrada." between '".$data_inicio."' and '".$data_fim."'
                        and p.codigo = a.produto_codigo
                ) x

                group by
                    LINHA_CODIGO

                ORDER BY QUANTIDADE DESC";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias']
            );

            $ret5 = $con->query($sql, $args);

            $sql = "
                SELECT
                    coalesce((select l.descricao from tbcor l where l.codigo = x.cor_codigo),'') as DESCRICAO,
                    sum(QUANTIDADE1) as QUANTIDADE
                from(
                    SELECT
                    
                        p.linha_codigo,
                        p.cor_codigo,
                        IIF(A.SITUACAO = 3, A.QUANTIDADE - D.QUANTIDADE, A.QUANTIDADE) QUANTIDADE1,
                        A.QUANTIDADE QUANTIDADE2
                    
                    FROM TBPEDIDO_ITEM A, TBPEDIDO B, TBPEDIDO_ITEM_SALDO D, tbproduto p
                    WHERE A.PEDIDO = B.PEDIDO
                        AND A.CONTROLE = D.PEDIDO_ITEM_CONTROLE
                        AND B.STATUS = 1
                        AND B.SITUACAO = 1
                        AND B.ESTABELECIMENTO_CODIGO = :ESTABELECIMENTO
                        AND B.FAMILIA_CODIGO = :FAMILIA
                        AND B.".$entrada." between '".$data_inicio."' and '".$data_fim."'
                        and p.codigo = a.produto_codigo
                ) x

                group by
                    cor_codigo

                ORDER BY QUANTIDADE DESC";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias']
            );

            $ret6 = $con->query($sql, $args);
            
            $con->commit();

            return [$ret,$ret2,$ret3,$ret4,$ret5,$ret6];

        } catch (Exception $e) {
            
            $con->rollback();
            throw $e;
        }
    }

    /**
     * devolucao dia
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function devolucaoDia($dados) {

        $con = new _Conexao();

        $entrada = '';
        if($dados['periodo_pedido'] == 'd'){
            $entrada = 'Data';
        }else{
            $entrada = 'Data_Cliente';
        }
        
        try {
            
            $data_inicio = date('d.m.Y', strtotime($dados['periodo_inicial']));
            $data_fim    = date('d.m.Y', strtotime($dados['periodo_final']));
            
            $sql = "
                 SELECT lpad( s.codigo,6,'0')||' - '||s.razaosocial||' ('||s.UF||')' as Cliente, SUM(A.Quantidade) Quantidade
                From TBNFE D, TbNfE_Item A, TbOperacao C, TbProduto P, tbcliente S
                Where D.CONTROLE  = A.NfE_controle
                  and A.Operacao_Codigo = C.Codigo
                  and a.Produto_Codigo = P.Codigo
                  and ((D.Natureza = 1 and D.Situacao = 2) or (D.Natureza = 2 and D.Situacao = 1))
                  and C.Controle_devolucao = 1
                  and A.Numero_NotaFiscal > 0
                  and A.Estabelecimento_Codigo = :ESTABELECIMENTO
                  and A.Data_Entrada between '".$data_inicio."' and '".$data_fim."'
                  and P.Familia_Codigo = :FAMILIA
                  and s.codigo = d.empresa_codigo
                Group By 1
                order by QUANTIDADE desc";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias']
            );

            $ret1 = $con->query($sql, $args);

            $sql = "
                SELECT REPRESENTANTE, List(EMPRESA_UF) UF, Sum(QUANTIDADE) Quantidade
                From(
                    SELECT
                    
                        r.NomeFantasia||' ('||r.UF||')' REPRESENTANTE,
                        D.Empresa_uf,
                        r.uf ,
                        SUM(A.Quantidade) Quantidade
                    
                    From TBNFE D, TbNfE_Item A, TbOperacao C, TbProduto P, tbcliente S, tbempresa r
                    Where D.CONTROLE  = A.NfE_controle
                      and A.Operacao_Codigo = C.Codigo
                      and a.Produto_Codigo = P.Codigo
                      and ((D.Natureza = 1 and D.Situacao = 2) or (D.Natureza = 2 and D.Situacao = 1))
                      and C.Controle_devolucao = 1
                      and A.Numero_NotaFiscal > 0
                      and A.Estabelecimento_Codigo = :ESTABELECIMENTO
                      and A.Data_Entrada between '".$data_inicio."' and '".$data_fim."'
                      and P.Familia_Codigo = :FAMILIA
                      and s.codigo = d.empresa_codigo
                      and r.codigo = s.representante_codigo
                    Group By 1,2,3
                ) Z Group By 1 order by 3 desc";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias']
            );

            $ret2 = $con->query($sql, $args);

            $sql = "
                SELECT s.uf , SUM(A.Quantidade) Quantidade
                From TBNFE D, TbNfE_Item A, TbOperacao C, TbProduto P, tbcliente S, tbrepresentante r
                Where D.CONTROLE  = A.NfE_controle
                  and A.Operacao_Codigo = C.Codigo
                  and a.Produto_Codigo = P.Codigo
                  and ((D.Natureza = 1 and D.Situacao = 2) or (D.Natureza = 2 and D.Situacao = 1))
                  and C.Controle_devolucao = 1
                  and A.Numero_NotaFiscal > 0
                  and A.Estabelecimento_Codigo = :ESTABELECIMENTO
                  and A.Data_Entrada between '".$data_inicio."' and '".$data_fim."'
                  and P.Familia_Codigo = :FAMILIA
                  and s.codigo = d.empresa_codigo
                  and r.codigo = s.representante_codigo
                Group By 1
                order by QUANTIDADE desc";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias']
            );

            $ret3 = $con->query($sql, $args);

            $sql = "
                  SELECT 
                    DESCRICAO,
                    SUM(QUANTIDADE) AS QUANTIDADE
                  FROM
      
                    (SELECT

                      (
                          SELECT FIRST 1 T.DESCRICAO FROM TBPEDIDO_ITEM Z,TBPERFIL T
                          where z.pedido = i.pedido
                      --and z.produto_codigo = a.produto_codigo
                      and t.id = z.perfil
                      and z.controle = i.pedido_item_pe_controle
                      and t.familia_id = :FAMILIA3
                      and t.tabela = 'SKU'
                      ) AS DESCRICAO,

                      X.QUANTIDADE

                   FROM
                      
                      (
                          SELECT A.NFS, A.NFS_ITEM_CONTROLE , SUM(A.QUANTIDADE) QUANTIDADE
                          FROM TBNFE D, TBNFE_ITEM A, TBOPERACAO C, TBPRODUTO P
                          WHERE D.CONTROLE  = A.NFE_CONTROLE
                            AND A.OPERACAO_CODIGO = C.CODIGO
                            AND A.PRODUTO_CODIGO = P.CODIGO
                            AND ((D.NATUREZA = 1 AND D.SITUACAO = 2) OR (D.NATUREZA = 2 AND D.SITUACAO = 1))
                            AND C.CONTROLE_DEVOLUCAO = 1
                            AND A.NUMERO_NOTAFISCAL > 0
                            AND A.ESTABELECIMENTO_CODIGO = :ESTABELECIMENTO
                            AND A.DATA_ENTRADA BETWEEN '".$data_inicio."' and '".$data_fim."'
                            AND P.FAMILIA_CODIGO = :FAMILIA
                      
                          GROUP BY 1,2
                          ORDER BY QUANTIDADE DESC
                      
                      ) X, TBNFS_ITEM I

                  WHERE 1=1
                    AND I.NUMERO_NOTAFISCAL = X.NFS
                    AND I.CONTROLE = X.NFS_ITEM_CONTROLE
                ) Q
                GROUP BY 1
                ORDER BY QUANTIDADE DESC
                ";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias'],
                ':FAMILIA3'         => $dados['familias']
            );

            $ret4 = $con->query($sql, $args);

            $sql = "
                  SELECT 
                    DESCRICAO,
                    SUM(QUANTIDADE) AS QUANTIDADE
                  FROM
      
                    (SELECT

                      coalesce((select l.descricao from tbmodelo_linha l where l.codigo = p.LINHA_CODIGO),'') as DESCRICAO,

                      X.QUANTIDADE

                   FROM
                      
                      (
                          SELECT A.NFS, A.NFS_ITEM_CONTROLE, SUM(A.QUANTIDADE) QUANTIDADE
                          FROM TBNFE D, TBNFE_ITEM A, TBOPERACAO C, TBPRODUTO P
                          WHERE D.CONTROLE  = A.NFE_CONTROLE
                            AND A.OPERACAO_CODIGO = C.CODIGO
                            AND A.PRODUTO_CODIGO = P.CODIGO
                            AND ((D.NATUREZA = 1 AND D.SITUACAO = 2) OR (D.NATUREZA = 2 AND D.SITUACAO = 1))
                            AND C.CONTROLE_DEVOLUCAO = 1
                            AND A.NUMERO_NOTAFISCAL > 0
                            AND A.ESTABELECIMENTO_CODIGO = :ESTABELECIMENTO
                            AND A.DATA_ENTRADA BETWEEN '".$data_inicio."' and '".$data_fim."'
                            AND P.FAMILIA_CODIGO = :FAMILIA
                      
                          GROUP BY 1,2
                          ORDER BY QUANTIDADE DESC
                      
                      ) X, TBNFS_ITEM I, tbproduto p

                  WHERE I.NUMERO_NOTAFISCAL = X.NFS
                    AND I.CONTROLE = X.NFS_ITEM_CONTROLE
                    and p.codigo = i.produto_codigo
                ) Q
                GROUP BY DESCRICAO
                ORDER BY QUANTIDADE DESC
                ";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias']
            );

            $ret5 = $con->query($sql, $args);

            $sql = "
                  SELECT 
                    DESCRICAO,
                    SUM(QUANTIDADE) AS QUANTIDADE
                  FROM
      
                    (SELECT

                      coalesce((select l.descricao from tbcor l where l.codigo = p.cor_codigo),'') as DESCRICAO,

                      X.QUANTIDADE

                   FROM
                      
                      (
                          SELECT A.NFS, A.NFS_ITEM_CONTROLE, SUM(A.QUANTIDADE) QUANTIDADE
                          FROM TBNFE D, TBNFE_ITEM A, TBOPERACAO C, TBPRODUTO P
                          WHERE D.CONTROLE  = A.NFE_CONTROLE
                            AND A.OPERACAO_CODIGO = C.CODIGO
                            AND A.PRODUTO_CODIGO = P.CODIGO
                            AND ((D.NATUREZA = 1 AND D.SITUACAO = 2) OR (D.NATUREZA = 2 AND D.SITUACAO = 1))
                            AND C.CONTROLE_DEVOLUCAO = 1
                            AND A.NUMERO_NOTAFISCAL > 0
                            AND A.ESTABELECIMENTO_CODIGO = :ESTABELECIMENTO
                            AND A.DATA_ENTRADA BETWEEN '".$data_inicio."' and '".$data_fim."'
                            AND P.FAMILIA_CODIGO = :FAMILIA
                      
                          GROUP BY 1,2
                          ORDER BY QUANTIDADE DESC
                      
                      ) X, TBNFS_ITEM I, tbproduto p

                  WHERE I.NUMERO_NOTAFISCAL = X.NFS
                    AND I.CONTROLE = X.NFS_ITEM_CONTROLE
                    and p.codigo = i.produto_codigo
                ) Q
                GROUP BY DESCRICAO
                ORDER BY QUANTIDADE DESC
                ";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias']
            );

            $ret6 = $con->query($sql, $args);
            
            return [$ret1,$ret2,$ret3,$ret4,$ret5,$ret6];
            
            $con->commit();

        } catch (Exception $e) {
            
            $con->rollback();
            throw $e;
        }
    }


    /**
     * faturamento dia
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function faturamentoDia($dados) {

        $con = new _Conexao();

        $entrada = '';
        if($dados['periodo_pedido'] == 'd'){
            $entrada = 'Data';
        }else{
            $entrada = 'Data_Cliente';
        }
        
        try {
            
            $data_inicio = date('d.m.Y', strtotime($dados['periodo_inicial']));
            $data_fim    = date('d.m.Y', strtotime($dados['periodo_final']));
            
            $sql = "
                SELECT Z.cliente, Sum(Z.Quantidade) Quantidade
                From
                (
                SELECT lpad( s.codigo,6,'0')||' - '||s.razaosocial||' ('||s.UF||')' as cliente, A.Quantidade
                From TBCF_VENDA_CABECALHO D, TbCF_VENDA_DETALHE A, TbProduto P, tbcliente s
                Where D.estabelecimento_id = A.estabelecimento_id
                  and D.caixa_id = A.caixa_id
                  and D.ID = A.Id_ecf_venda_cabecalho
                  and A.id_ecf_produto = P.Codigo
                  and A.Cancelado = 'N'
                  and D.Cupom_cancelado = 'N'
                  and D.estabelecimento_id =  :ESTABELECIMENTO1
                  and D.Data_Venda between '".$data_inicio."' and '".$data_fim."'
                  and P.Familia_Codigo = :FAMILIA1
                  and s.codigo = d.id_cliente

                UNION ALL

                SELECT lpad( s.codigo,6,'0')||' - '||s.razaosocial||' ('||s.UF||')' as cliente, A.Quantidade
                From TBNFS d, TbNfs_Item A, TbOperacao C, TbProduto P, tbcliente s
                Where D.CONTROLE  = A.Nfs_controle
                  and A.Operacao_Codigo = C.Codigo
                  and a.Produto_Codigo = P.Codigo
                  and ((D.Natureza = 1 and D.Situacao = 2) or (D.Natureza = 2 and D.Situacao = 1))
                  and C.Controle_Faturamento = 1
                  and A.Numero_NotaFiscal > 0
                  and A.Estabelecimento_Codigo = :ESTABELECIMENTO2
                  and A.Data_Emissao between '".$data_inicio."' and '".$data_fim."'
                  and P.Familia_Codigo = :FAMILIA2
                  and s.codigo = d.empresa_codigo

            ) Z Group By 1 order by Quantidade desc";

            $args = array(
                ':ESTABELECIMENTO1'  => $dados['estabelecimento'],
                ':ESTABELECIMENTO2'  => $dados['estabelecimento'],
                ':FAMILIA1'          => $dados['familias'],
                ':FAMILIA2'          => $dados['familias']
            );

            $ret1 = $con->query($sql, $args);

            $sql = "
              SELECT REPRESENTANTE, List(EMPRESA_UF) UF, Sum(QUANTIDADE) Quantidade
              From(

              SELECT D.Representante_Codigo Representante_Id,
                     E.NomeFantasia||' ('||E.UF||')' REPRESENTANTE,
                     D.Empresa_uf,
                     Sum(A.Quantidade) Quantidade
              From TBNFS d, TbNfs_Item A, TbOperacao C, TbProduto P, tbcliente s, tbempresa e
              Where D.CONTROLE  = A.Nfs_controle
                and A.Operacao_Codigo = C.Codigo
                and a.Produto_Codigo = P.Codigo
                and ((D.Natureza = 1 and D.Situacao = 2) or (D.Natureza = 2 and D.Situacao = 1))
                and C.Controle_Faturamento = 1
                and A.Numero_NotaFiscal > 0
                and A.Estabelecimento_Codigo = :ESTABELECIMENTO
                and A.Data_Emissao between '".$data_inicio."' and '".$data_fim."'
                and P.Familia_Codigo = :FAMILIA
                and s.codigo = d.empresa_codigo
                and e.codigo = s.representante_codigo

              Group by 1,2,3
              ) Z Group By 1 order by 3 desc";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias']
            );

            $ret2 = $con->query($sql, $args);

            $sql = "
                SELECT Z.UF, Sum(Z.Quantidade) Quantidade
                From
                (
                SELECT s.uf, A.Quantidade
                From TBCF_VENDA_CABECALHO D, TbCF_VENDA_DETALHE A, TbProduto P, tbcliente s, tbrepresentante r
                Where D.estabelecimento_id = A.estabelecimento_id
                  and D.caixa_id = A.caixa_id
                  and D.ID = A.Id_ecf_venda_cabecalho
                  and A.id_ecf_produto = P.Codigo
                  and A.Cancelado = 'N'
                  and D.Cupom_cancelado = 'N'
                  and D.estabelecimento_id =  :ESTABELECIMENTO1
                  and D.Data_Venda between '".$data_inicio."' and '".$data_fim."'
                  and P.Familia_Codigo = :FAMILIA1
                  and s.codigo = d.id_cliente
                  and r.codigo = s.representante_codigo

                UNION ALL

                SELECT s.uf, A.Quantidade
                From TBNFS d, TbNfs_Item A, TbOperacao C, TbProduto P, tbcliente s, tbrepresentante r
                Where D.CONTROLE  = A.Nfs_controle
                  and A.Operacao_Codigo = C.Codigo
                  and a.Produto_Codigo = P.Codigo
                  and ((D.Natureza = 1 and D.Situacao = 2) or (D.Natureza = 2 and D.Situacao = 1))
                  and C.Controle_Faturamento = 1
                  and A.Numero_NotaFiscal > 0
                  and A.Estabelecimento_Codigo = :ESTABELECIMENTO2
                  and A.Data_Emissao between '".$data_inicio."' and '".$data_fim."'
                  and P.Familia_Codigo = :FAMILIA2
                  and s.codigo = d.empresa_codigo
                  and r.codigo = s.representante_codigo

            ) Z Group By 1 order by Quantidade desc";

            $args = array(
                ':ESTABELECIMENTO1'  => $dados['estabelecimento'],
                ':ESTABELECIMENTO2'  => $dados['estabelecimento'],
                ':FAMILIA1'          => $dados['familias'],
                ':FAMILIA2'          => $dados['familias']
            );

            $ret3 = $con->query($sql, $args);

            $sql = "
                SELECT u.descricao, Sum(u.Quantidade) Quantidade
                From
                (
                SELECT
                A.Quantidade,
                (
                    select first 1 t.descricao from TbPedido_Item z,tbperfil t
                    where z.pedido = a.pedido
                    --and z.produto_codigo = a.produto_codigo
                    and t.id = z.perfil
                    and z.controle = a.pedido_item_pe_controle
                    and t.familia_id = p.familia_codigo
                    and t.tabela = 'SKU'
                ) as descricao

                From TBNFS d, TbNfs_Item A, TbOperacao C, TbProduto P, tbcliente s, tbrepresentante r
                Where D.CONTROLE  = A.Nfs_controle
                  and A.Operacao_Codigo = C.Codigo
                  and a.Produto_Codigo = P.Codigo
                  and ((D.Natureza = 1 and D.Situacao = 2) or (D.Natureza = 2 and D.Situacao = 1))
                  and C.Controle_Faturamento = 1
                  and A.Numero_NotaFiscal > 0
                  and A.Estabelecimento_Codigo = :ESTABELECIMENTO
                  and A.Data_Emissao between '".$data_inicio."' and '".$data_fim."'
                  and P.Familia_Codigo = :FAMILIA
                  and s.codigo = d.empresa_codigo
                  and r.codigo = s.representante_codigo

            ) u 
            where QUANTIDADE > 0
            Group By 1 order by Quantidade desc";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias']
            );

            $ret4 = $con->query($sql, $args);

            $sql = "
                SELECT
                  coalesce((select l.descricao from tbmodelo_linha l where l.codigo = z.LINHA_CODIGO),'') as DESCRICAO,
                  Sum(Z.Quantidade) Quantidade
                From
                (
                SELECT p.linha_codigo, A.Quantidade
                From TBCF_VENDA_CABECALHO D, TbCF_VENDA_DETALHE A, TbProduto P, tbcliente s, tbrepresentante r
                Where D.estabelecimento_id = A.estabelecimento_id
                  and D.caixa_id = A.caixa_id
                  and D.ID = A.Id_ecf_venda_cabecalho
                  and A.id_ecf_produto = P.Codigo
                  and A.Cancelado = 'N'
                  and D.Cupom_cancelado = 'N'
                  and D.estabelecimento_id =  :ESTABELECIMENTO1
                  and D.Data_Venda between '".$data_inicio."' and '".$data_fim."'
                  and P.Familia_Codigo = :FAMILIA1
                  and s.codigo = d.id_cliente
                  and r.codigo = s.representante_codigo

                UNION ALL

                SELECT p.linha_codigo, A.Quantidade
                From TBNFS d, TbNfs_Item A, TbOperacao C, TbProduto P, tbcliente s, tbrepresentante r
                Where D.CONTROLE  = A.Nfs_controle
                  and A.Operacao_Codigo = C.Codigo
                  and a.Produto_Codigo = P.Codigo
                  and ((D.Natureza = 1 and D.Situacao = 2) or (D.Natureza = 2 and D.Situacao = 1))
                  and C.Controle_Faturamento = 1
                  and A.Numero_NotaFiscal > 0
                  and A.Estabelecimento_Codigo = :ESTABELECIMENTO2
                  and A.Data_Emissao between '".$data_inicio."' and '".$data_fim."'
                  and P.Familia_Codigo = :FAMILIA2
                  and s.codigo = d.empresa_codigo
                  and r.codigo = s.representante_codigo

            ) Z Group By z.linha_codigo order by Quantidade desc";

            $args = array(
                ':ESTABELECIMENTO1'  => $dados['estabelecimento'],
                ':ESTABELECIMENTO2'  => $dados['estabelecimento'],
                ':FAMILIA1'          => $dados['familias'],
                ':FAMILIA2'          => $dados['familias']
            );

            $ret5 = $con->query($sql, $args);

            $sql = "
                SELECT 
                  coalesce((select l.descricao from tbcor l where l.codigo = z.cor_codigo),'') as DESCRICAO,
                  Sum(z.Quantidade) Quantidade
                From
                (
                SELECT p.cor_codigo, A.Quantidade
                From TBCF_VENDA_CABECALHO D, TbCF_VENDA_DETALHE A, TbProduto P, tbcliente s, tbrepresentante r
                Where D.estabelecimento_id = A.estabelecimento_id
                  and D.caixa_id = A.caixa_id
                  and D.ID = A.Id_ecf_venda_cabecalho
                  and A.id_ecf_produto = P.Codigo
                  and A.Cancelado = 'N'
                  and D.Cupom_cancelado = 'N'
                  and D.estabelecimento_id =  :ESTABELECIMENTO1
                  and D.Data_Venda between '".$data_inicio."' and '".$data_fim."'
                  and P.Familia_Codigo = :FAMILIA1
                  and s.codigo = d.id_cliente
                  and r.codigo = s.representante_codigo

                UNION ALL

                SELECT p.cor_codigo, A.Quantidade
                From TBNFS d, TbNfs_Item A, TbOperacao C, TbProduto P, tbcliente s, tbrepresentante r
                Where D.CONTROLE  = A.Nfs_controle
                  and A.Operacao_Codigo = C.Codigo
                  and a.Produto_Codigo = P.Codigo
                  and ((D.Natureza = 1 and D.Situacao = 2) or (D.Natureza = 2 and D.Situacao = 1))
                  and C.Controle_Faturamento = 1
                  and A.Numero_NotaFiscal > 0
                  and A.Estabelecimento_Codigo = :ESTABELECIMENTO2
                  and A.Data_Emissao between '".$data_inicio."' and '".$data_fim."'
                  and P.Familia_Codigo = :FAMILIA2
                  and s.codigo = d.empresa_codigo
                  and r.codigo = s.representante_codigo

            ) Z Group By z.cor_codigo order by Quantidade desc";

            $args = array(
                ':ESTABELECIMENTO1'  => $dados['estabelecimento'],
                ':ESTABELECIMENTO2'  => $dados['estabelecimento'],
                ':FAMILIA1'          => $dados['familias'],
                ':FAMILIA2'          => $dados['familias']
            );

            $ret6 = $con->query($sql, $args);
            
            return [$ret1,$ret2,$ret3,$ret4,$ret5,$ret6];
            
            $con->commit();

        } catch (Exception $e) {
            
            $con->rollback();
            throw $e;
        }
    }

    /**
     * faturamento dia
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function defeitoDia($dados) {

        $con = new _Conexao();

        $entrada = '';
        if($dados['periodo_pedido'] == 'd'){
            $entrada = 'Data';
        }else{
            $entrada = 'Data_Cliente';
        }
        
        try {
            
            $data_inicio = date('d.m.Y', strtotime($dados['periodo_inicial']));
            $data_fim    = date('d.m.Y', strtotime($dados['periodo_final']));
            
            $sql = "
                SELECT

                    DESCRICAO,
                    QUANTIDADE,
                    QTD_TURNO1,
                    QTD_TURNO2

                    from
                       (
                       SELECT
                        X.descricao,
                        SUM(X.QUANTIDADE) QUANTIDADE,
                        SUM(X.QTD_TURNO1) QTD_TURNO1,
                        SUM(X.QTD_TURNO2) QTD_TURNO2
                        From (
                        Select m.descricao, B.Quantidade, IIF(B.TURNO=1,B.Quantidade,0) QTD_TURNO1, IIF(B.TURNO=2,B.Quantidade,0) QTD_TURNO2
                        From TbDefeito_Transacao_Item B, TbEsteira E, TbProduto P, tbmodelo M
                        Where B.Esteira_id = E.Codigo
                          and B.Produto_id = P.Codigo
                          and B.Estabelecimento_Id = :ESTABELECIMENTO
                          and B.Data between '".$data_inicio."' and '".$data_fim."'
                          AND (B.DEFEITO_ID > 0)
                          and P.Familia_Codigo = :FAMILIA
                          and m.codigo = p.modelo_codigo
                        ) X
                        Group By X.descricao
                        ) T order by QUANTIDADE desc";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias'],
            );

            $ret1 = $con->query($sql, $args);

            $sql = "
                SELECT

                    LINHA,
                    QUANTIDADE,
                    QTD_TURNO1,
                    QTD_TURNO2

                    from
                       (
                       SELECT
                        x.linha,
                        SUM(X.QUANTIDADE) QUANTIDADE,
                        SUM(X.QTD_TURNO1) QTD_TURNO1,
                        SUM(X.QTD_TURNO2) QTD_TURNO2
                        From (
                        Select r.descricao as linha, B.Quantidade, IIF(B.TURNO=1,B.Quantidade,0) QTD_TURNO1, IIF(B.TURNO=2,B.Quantidade,0) QTD_TURNO2
                        From TbDefeito_Transacao_Item B, TbEsteira E, TbProduto P, tbmodelo M, tbmodelo_linha r
                        Where B.Esteira_id = E.Codigo
                          and B.Produto_id = P.Codigo
                          and B.Estabelecimento_Id = :ESTABELECIMENTO
                          and B.Data between '".$data_inicio."' and '".$data_fim."'
                          AND (B.DEFEITO_ID > 0)
                          and P.Familia_Codigo = :FAMILIA
                          and m.codigo = p.modelo_codigo
                          and r.codigo = m.linha_codigo
                        ) X
                        Group By x.linha
                        ) T order by QUANTIDADE desc";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias'],
            );

            $ret2 = $con->query($sql, $args);

            $sql = "
                SELECT

                    descricao,
                    QUANTIDADE,
                    QTD_TURNO1,
                    QTD_TURNO2

                    from
                       (
                       SELECT
                        x.descricao,
                        SUM(X.QUANTIDADE) QUANTIDADE,
                        SUM(X.QTD_TURNO1) QTD_TURNO1,
                        SUM(X.QTD_TURNO2) QTD_TURNO2
                        From (
                        Select d.descricao, B.Quantidade, IIF(B.TURNO=1,B.Quantidade,0) QTD_TURNO1, IIF(B.TURNO=2,B.Quantidade,0) QTD_TURNO2
                        From TbDefeito_Transacao_Item B, TbEsteira E, TbProduto P, tbsac_defeito d
                        Where B.Esteira_id = E.Codigo
                          and B.Produto_id = P.Codigo
                          and B.Estabelecimento_Id = :ESTABELECIMENTO
                          and B.Data between '".$data_inicio."' and '".$data_fim."'
                          AND (B.DEFEITO_ID > 0)
                          and P.Familia_Codigo = :FAMILIA
                          and d.codigo = b.defeito_id
                        ) X
                        Group By x.descricao
                        ) T order by QUANTIDADE desc";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias'],
            );

            $ret3 = $con->query($sql, $args);

            $sql = "
                SELECT

                    descricao,
                    QUANTIDADE,
                    QTD_TURNO1,
                    QTD_TURNO2

                    from
                       (
                       SELECT
                        x.descricao,
                        SUM(X.QUANTIDADE) QUANTIDADE,
                        SUM(X.QTD_TURNO1) QTD_TURNO1,
                        SUM(X.QTD_TURNO2) QTD_TURNO2
                        From (
                        Select e.descricao, B.Quantidade, IIF(B.TURNO=1,B.Quantidade,0) QTD_TURNO1, IIF(B.TURNO=2,B.Quantidade,0) QTD_TURNO2
                        From TbDefeito_Transacao_Item B, TbEsteira E, TbProduto P
                        Where B.Esteira_id = E.Codigo
                          and B.Produto_id = P.Codigo
                          and B.Estabelecimento_Id = :ESTABELECIMENTO
                          and B.Data between '".$data_inicio."' and '".$data_fim."'
                          AND (B.DEFEITO_ID > 0)
                          and P.Familia_Codigo = :FAMILIA
                        ) X
                        Group By x.descricao
                        ) T order by QUANTIDADE desc";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias'],
            );

            $ret4 = $con->query($sql, $args);

            $sql = "
                SELECT

                descricao as LINHA,
                UNIDADE,
                QUANTIDADE,
                QTD_TURNO1,
                QTD_TURNO2,
                TALOES


                from (
                SELECT
                    descricao,
                    unidademedida_sigla as UNIDADE,
                    sum(QUANTIDADE) as QUANTIDADE,
                    sum(T1) as QTD_TURNO1,
                    sum(T2) as QTD_TURNO2,
                    sum(TALOES) as TALOES

                from
                (SELECT

                   b.descricao,
                   b.QUANTIDADE,
                   b.unidademedida_sigla,
                   iif(b.turno = 1,b.quantidade,0) as T1,
                   iif(b.turno = 2,b.quantidade,0) as T2,
                   b.TALOES

                from
                (Select
                        l.descricao,
                        A.turno,
                        F.unidademedida_sigla,
                        sum( Trunc(Coalesce(A.QUANTIDADE_PEDIDO,0))) as quantidade,
                        count(remessa) as TALOES

                  From TbEsteira E,TbPedido_Item_Processado a,tbperfil p, tbfamilia f, tbmodelo m, tbmodelo_linha l where
                       A.Esteira_Producao = E.Codigo
                   and A.Estabelecimento_Codigo = :ESTABELECIMENTO
                   and A.Data_Producao between '".$data_inicio."' and '".$data_fim."'
                   and A.Esteira_Producao = E.CODIGO
                   and p.id = a.perfil
                   and p.tabela = 'SKU'
                   and a.familia_codigo = :FAMILIA
                   and f.codigo = a.familia_codigo
                   and m.codigo = a.modelo_codigo
                   and l.codigo = m.linha_codigo

                   group by 1,2,3
                )b
                )c group by 1,2
                )d order by QUANTIDADE desc";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias']
            );
            
            $ret5 = $con->query($sql, $args);

            $sql = "
                SELECT

                    descricao,
                    QUANTIDADE,
                    QTD_TURNO1,
                    QTD_TURNO2

                    from
                       (
                       SELECT
                        x.descricao,
                        SUM(X.QUANTIDADE) QUANTIDADE,
                        SUM(X.QTD_TURNO1) QTD_TURNO1,
                        SUM(X.QTD_TURNO2) QTD_TURNO2
                        From (
                        Select s.descricao, B.Quantidade, IIF(B.TURNO=1,B.Quantidade,0) QTD_TURNO1, IIF(B.TURNO=2,B.Quantidade,0) QTD_TURNO2
                        From TbDefeito_Transacao_Item B, TbEsteira E, TbProduto P, tbperfil s
                        Where B.Esteira_id = E.Codigo
                          and B.Produto_id = P.Codigo
                          and B.Estabelecimento_Id = :ESTABELECIMENTO
                          and B.Data between '".$data_inicio."' and '".$data_fim."'
                          AND (B.DEFEITO_ID > 0)
                          and P.Familia_Codigo = :FAMILIA
                          and s.id = b.perfil
                          and s.tabela = 'SKU'
                          and s.familia_id = :FAMILIA3
                        ) X
                        Group By x.descricao
                        ) T order by QUANTIDADE desc";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias'],
                ':FAMILIA3'         => $dados['familias']
            );
            
            $ret6 = $con->query($sql, $args);

            $sql = "
                SELECT

                descricao,
                UNIDADE,
                QUANTIDADE,
                QTD_TURNO1,
                QTD_TURNO2,
                TALOES


                from (
                SELECT
                    descricao,
                    unidademedida_sigla as UNIDADE,
                    sum(QUANTIDADE) as QUANTIDADE,
                    sum(T1) as QTD_TURNO1,
                    sum(T2) as QTD_TURNO2,
                    sum(TALOES) as TALOES

                from
                (SELECT

                   b.descricao,
                   b.QUANTIDADE,
                   b.unidademedida_sigla,
                   iif(b.turno = 1,b.quantidade,0) as T1,
                   iif(b.turno = 2,b.quantidade,0) as T2,
                   b.TALOES

                from
                (Select
                        e.descricao,
                        A.turno,
                        F.unidademedida_sigla,
                        sum( Trunc(Coalesce(A.QUANTIDADE_PEDIDO,0))) as quantidade,
                        count(remessa) as TALOES

                  From TbEsteira E,TbPedido_Item_Processado a,tbperfil p, tbfamilia f, tbmodelo m, tbmodelo_linha l where
                       A.Esteira_Producao = E.Codigo
                   and A.Estabelecimento_Codigo = :ESTABELECIMENTO
                   and A.Data_Producao between '".$data_inicio."' and '".$data_fim."'
                   and A.Esteira_Producao = E.CODIGO
                   and p.id = a.perfil
                   and p.tabela = 'SKU'
                   and a.familia_codigo = :FAMILIA
                   and f.codigo = a.familia_codigo
                   and m.codigo = a.modelo_codigo
                   and l.codigo = m.linha_codigo

                   group by 1,2,3
                )b
                )c group by 1,2
                )d order by QUANTIDADE desc";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias']
            );

            $ret7 = $con->query($sql, $args);

            $sql = "
                SELECT

                descricao,
                UNIDADE,
                QUANTIDADE,
                QTD_TURNO1,
                QTD_TURNO2,
                TALOES

                from (
                SELECT
                    descricao,
                    unidademedida_sigla as UNIDADE,
                    sum(QUANTIDADE) as QUANTIDADE,
                    sum(T1) as QTD_TURNO1,
                    sum(T2) as QTD_TURNO2,
                    sum(TALOES) as TALOES


                from
                (SELECT

                   b.descricao,
                   b.QUANTIDADE,
                   b.unidademedida_sigla,
                   iif(b.turno = 1,b.quantidade,0) as T1,
                   iif(b.turno = 2,b.quantidade,0) as T2,
                   b.TALOES

                from
                (Select
                        m.descricao,
                        A.turno,
                        F.unidademedida_sigla,
                        sum( Trunc(Coalesce(A.QUANTIDADE_PEDIDO,0))) as quantidade,
                        count(remessa) as TALOES

                  From TbEsteira E,TbPedido_Item_Processado a,tbperfil p, tbfamilia f, tbmodelo m where
                       A.Esteira_Producao = E.Codigo
                   and A.Estabelecimento_Codigo = :ESTABELECIMENTO
                   and A.Data_Producao between '".$data_inicio."' and '".$data_fim."'
                   and A.Esteira_Producao = E.CODIGO
                   and p.id = a.perfil
                   and p.tabela = 'SKU'
                   and a.familia_codigo = :FAMILIA
                   and f.codigo = a.familia_codigo
                   and m.codigo = a.modelo_codigo

                   group by 1,2,3
                )b
                )c group by 1,2
                )d order by QUANTIDADE desc";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias']
            );

            $ret8 = $con->query($sql, $args);

            $sql = "
                SELECT

                descricao,
                UNIDADE,
                QUANTIDADE,
                QTD_TURNO1,
                QTD_TURNO2,
                TALOES


                from (
                SELECT
                    descricao,
                    unidademedida_sigla as UNIDADE,
                    sum(QUANTIDADE) as QUANTIDADE,
                    sum(T1) as QTD_TURNO1,
                    sum(T2) as QTD_TURNO2,
                    sum(TALOES) as TALOES

                from
                (SELECT

                   b.descricao,
                   b.QUANTIDADE,
                   b.unidademedida_sigla,
                   iif(b.turno = 1,b.quantidade,0) as T1,
                   iif(b.turno = 2,b.quantidade,0) as T2,
                   b.TALOES

                from
                (Select
                        p.descricao,
                        A.turno,
                        F.unidademedida_sigla,
                        sum( Trunc(Coalesce(A.QUANTIDADE_PEDIDO,0))) as quantidade,
                        count(remessa) as TALOES

                  From TbEsteira E,TbPedido_Item_Processado a,tbperfil p, tbfamilia f, tbmodelo m, tbmodelo_linha l where
                       A.Esteira_Producao = E.Codigo
                   and A.Estabelecimento_Codigo = :ESTABELECIMENTO
                   and A.Data_Producao between '".$data_inicio."' and '".$data_fim."'
                   and A.Esteira_Producao = E.CODIGO
                   and p.id = a.perfil
                   and p.tabela = 'SKU'
                   and p.familia_id = :FAMILIA3
                   and a.familia_codigo = :FAMILIA
                   and f.codigo = a.familia_codigo
                   and m.codigo = a.modelo_codigo
                   and l.codigo = m.linha_codigo

                   group by 1,2,3
                )b
                )c group by 1,2
                )d order by QUANTIDADE desc";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias'],
                ':FAMILIA3'         => $dados['familias']
            );

            $ret9 = $con->query($sql, $args);

            return [$ret1,$ret2,$ret3,$ret4,$ret5,$ret6,$ret7,$ret8,$ret9];
            
            $con->commit();

        } catch (Exception $e) {
            
            $con->rollback();
            throw $e;
        }
    }

    /**
     * faturamento dia
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function defeitoDia2($dados) {

        $con = new _Conexao();

        $entrada = '';
        if($dados['periodo_pedido'] == 'd'){
            $entrada = 'Data';
        }else{
            $entrada = 'Data_Cliente';
        }

        try {
            
            $data_inicio = date('d.m.Y', strtotime($dados['periodo_inicial']));
            $data_fim    = date('d.m.Y', strtotime($dados['periodo_final']));

            $sql = "SELECT
                    
                      DEFEITO,
                      MODELO,
                      COR,
                      GP,
                      LINHA,
                      PERFIL,
                      DEFEITO_SETOR,
                      defeito_id,
                      MODELO_ID,
                      COR_ID,
                      GP_ID,
                      LINHA_ID,
                      PERFIL_ID,
                      Densidade,
                      espessura,
                      tamanho,
                      sum(d.quantidade) as quantidade,
                      sum(d.qtd_turno1) as qtd_turno1,
                      sum(d.qtd_turno2) as qtd_turno2
                    
                    from vwregistro_defeito_dia d
                    
                    where d.estabelecimento_id = :ESTABELECIMENTO
                    and d.familia_id = :FAMILIA
                    and d.data_registro between '".$data_inicio."' and '".$data_fim."'

                    group by
                    DEFEITO,
                    MODELO,
                    COR,
                    GP,
                    LINHA,
                    PERFIL,
                    DEFEITO_SETOR,
                    defeito_id,
                    MODELO_ID,
                    PRODUTO_ID,
                    COR_ID,
                    GP_ID,
                    LINHA_ID,
                    PERFIL_ID,
                    Densidade,
                    espessura,
                    tamanho
                     
                ";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias'],
            );

            $defeito = $con->query($sql, $args);

            $sql = "SELECT
                    
                    MODELO,
                    COR,
                    GP,
                    LINHA,
                    PERFIL,
                    MODELO_ID,
                    COR_ID,
                    GP_ID,
                    LINHA_ID,
                    PERFIL_ID,
                    Densidade,
                    espessura,
                    tamanho,
                    sum(d.quantidade) as quantidade,
                    sum(d.qtd_turno1) as qtd_turno1,
                    sum(d.qtd_turno2) as qtd_turno2
                    
                    from vwregistro_producao_dia d
                    
                    where d.estabelecimento_id = :ESTABELECIMENTO
                    and d.familia_id = :FAMILIA
                    and d.data_registro between '".$data_inicio."' and '".$data_fim."'

                    group by
                    MODELO,
                    COR,
                    GP,
                    LINHA,
                    PERFIL,
                    MODELO_ID,
                    PRODUTO_ID,
                    COR_ID,
                    GP_ID,
                    LINHA_ID,
                    PERFIL_ID,
                    tamanho,
                    Densidade,
                    espessura
                ";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias']
            );
            
            $producao = $con->query($sql, $args);

            return [
              $defeito,
              $producao
            ];
            
            $con->commit();

        } catch (Exception $e) {
            
            $con->rollback();
            throw $e;
        }
    }

    /**
     * faturamento dia
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function producaoDia2($dados) {

        $con = new _Conexao();

        $entrada = '';
        if($dados['periodo_pedido'] == 'd'){
            $entrada = 'Data';
        }else{
            $entrada = 'Data_Cliente';
        }
        
        try {
            
            $data_inicio = date('d.m.Y', strtotime($dados['periodo_inicial']));
            $data_fim    = date('d.m.Y', strtotime($dados['periodo_final']));
            
            $sql = "
              SELECT
            
                W.DESCRICAO,
                W.UNIDADE,
                W.TALOES,
                W.QUANTIDADE,
                W.QTD_TURNO1,
                W.QTD_TURNO2
                
                from(  SELECT
                    Z.descricao,
                    Z.UM as UNIDADE,

                    sum(Z.TALOES) as TALOES,
                    sum(Z.PRODUZIDO) as QUANTIDADE,

                    sum(T1) as QTD_TURNO1,
                    sum(T2) as QTD_TURNO2

                    FROM(

                        SELECT
                            X.descricao,
                            IIF(X.UM_ALTERNATIVA <> '', X.UM_ALTERNATIVA, X.UM) UM,

                            iif(X.turno = '1',X.PRODUZIDO,0) as T1,
                            iif(X.turno = '2',X.PRODUZIDO,0) as T2,

                            --/ PRODUZIDO /
                            SUM(X.TALOES) TALOES,
                            SUM(X.PRODUZIDO) PRODUZIDO

                            FROM (

                                Select  Y.descricao,
                                        Y.UM,
                                        Y.UM_ALTERNATIVA,
                                        Y.TURNO,
                                        SUM(Y.PRODUZIDO) PRODUZIDO,
                                        COUNT(remessa_talao_id) TALOES
                                   From (

                                    SELECT R.REMESSA, T.remessa_talao_id, T.DATA_PRODUCAO,
                                        F.UNIDADEMEDIDA_SIGLA UM,
                                        F.UNIDADEMEDIDA_ALTERNATIVO UM_ALTERNATIVA,
                                        T.TURNO,
                                        M.descricao,

                                        coalesce ((SELECT SUM(IIF(D1.QUANTIDADE_ALTERN_PRODUCAO > 0, D1.QUANTIDADE_ALTERN_PRODUCAO,D1.QUANTIDADE_PRODUCAO))
                                                FROM VWREMESSA_TALAO_DETALHE D1
                                                WHERE D1.REMESSA_ID = T.REMESSA_ID AND D1.REMESSA_TALAO_ID = T.REMESSA_TALAO_ID),0) PRODUZIDO


                                     FROM VWREMESSA_TALAO T, VWREMESSA R, TBFAMILIA F, tbmodelo M

                                    WHERE 1=1
                                      AND R.REMESSA_ID = T.REMESSA_ID
                                      AND F.CODIGO = R.FAMILIA_ID
                                      AND f.codigo        = :FAMILIA
                                      and r.familia_id    = f.codigo
                                      AND R.ESTABELECIMENTO_ID = :ESTABELECIMENTO
                                      AND T.DATA_PRODUCAO BETWEEN '".$data_inicio."' and '".$data_fim."'
                                      and M.codigo = t.modelo_id
                                   ) Y

                                   Group By
                                        Y.descricao,
                                        Y.UM,
                                        Y.UM_ALTERNATIVA,
                                        Y.TURNO


                            ) X

                        GROUP BY
                            X.descricao,
                            UM,
                            T1,
                            T2

                    ) Z where z.PRODUZIDO > 0
                    GROUP BY 1,2
                    ) W order by QUANTIDADE desc";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias']
            );

            $ret1 = $con->query($sql, $args);

            $sql = "
              SELECT
            
                W.DESCRICAO,
                W.UNIDADE,
                W.TALOES,
                W.QUANTIDADE,
                W.QTD_TURNO1,
                W.QTD_TURNO2
                
                from(  SELECT
                    Z.descricao,
                    Z.UM as UNIDADE,

                    sum(Z.TALOES) as TALOES,
                    sum(Z.PRODUZIDO) as QUANTIDADE,

                    sum(T1) as QTD_TURNO1,
                    sum(T2) as QTD_TURNO2

                    FROM(

                        SELECT
                            X.descricao,
                            IIF(X.UM_ALTERNATIVA <> '', X.UM_ALTERNATIVA, X.UM) UM,

                            iif(X.turno = '1',X.PRODUZIDO,0) as T1,
                            iif(X.turno = '2',X.PRODUZIDO,0) as T2,

                            --/ PRODUZIDO /
                            SUM(X.TALOES) TALOES,
                            SUM(X.PRODUZIDO) PRODUZIDO

                            FROM (

                                Select  Y.descricao,
                                        Y.UM,
                                        Y.UM_ALTERNATIVA,
                                        Y.TURNO,
                                        SUM(Y.PRODUZIDO) PRODUZIDO,
                                        COUNT(remessa_talao_id) TALOES
                                   From (

                                    SELECT R.REMESSA, T.remessa_talao_id, T.DATA_PRODUCAO,
                                        F.UNIDADEMEDIDA_SIGLA UM,
                                        F.UNIDADEMEDIDA_ALTERNATIVO UM_ALTERNATIVA,
                                        T.TURNO,
                                        e.descricao,

                                        coalesce ((SELECT SUM(IIF(D1.QUANTIDADE_ALTERN_PRODUCAO > 0, D1.QUANTIDADE_ALTERN_PRODUCAO,D1.QUANTIDADE_PRODUCAO))
                                                FROM VWREMESSA_TALAO_DETALHE D1
                                                WHERE D1.REMESSA_ID = T.REMESSA_ID AND D1.REMESSA_TALAO_ID = T.REMESSA_TALAO_ID),0) PRODUZIDO


                                     FROM VWREMESSA_TALAO T, VWREMESSA R, TBFAMILIA F, tbmodelo M, tbup e

                                    WHERE 1=1
                                      AND R.REMESSA_ID = T.REMESSA_ID
                                      AND F.CODIGO = R.FAMILIA_ID
                                      AND f.codigo        = :FAMILIA
                                      and r.familia_id    = f.codigo
                                      AND R.ESTABELECIMENTO_ID = :ESTABELECIMENTO
                                      AND T.DATA_PRODUCAO BETWEEN '".$data_inicio."' and '".$data_fim."'
                                      and M.codigo = t.modelo_id
                                      and e.id = t.up_id

                                   ) Y

                                   Group By
                                        Y.descricao,
                                        Y.UM,
                                        Y.UM_ALTERNATIVA,
                                        Y.TURNO


                            ) X

                        GROUP BY
                            X.descricao,
                            UM,
                            T1,
                            T2

                    ) Z where z.PRODUZIDO > 0
                    GROUP BY 1,2
                    ) W order by QUANTIDADE desc";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias']
            );

            $ret2 = $con->query($sql, $args);


            $sql = "
              SELECT
            
                W.DESCRICAO,
                W.UNIDADE,
                W.TALOES,
                W.QUANTIDADE,
                W.QTD_TURNO1,
                W.QTD_TURNO2
                
                from(  SELECT
                    Z.descricao,
                    Z.UM as UNIDADE,

                    sum(Z.TALOES) as TALOES,
                    sum(Z.PRODUZIDO) as QUANTIDADE,

                    sum(T1) as QTD_TURNO1,
                    sum(T2) as QTD_TURNO2

                    FROM(

                        SELECT
                            X.descricao,
                            IIF(X.UM_ALTERNATIVA <> '', X.UM_ALTERNATIVA, X.UM) UM,

                            iif(X.turno = '1',X.PRODUZIDO,0) as T1,
                            iif(X.turno = '2',X.PRODUZIDO,0) as T2,

                            --/ PRODUZIDO /
                            SUM(X.TALOES) TALOES,
                            SUM(X.PRODUZIDO) PRODUZIDO

                            FROM (

                                Select  Y.PERFIL as descricao,
                                        Y.UM,
                                        Y.UM_ALTERNATIVA,
                                        Y.TURNO,
                                        SUM(Y.PRODUZIDO) PRODUZIDO,
                                        COUNT(remessa_talao_id) TALOES
                                   From (

                                    SELECT R.REMESSA, T.remessa_talao_id, T.DATA_PRODUCAO,
                                        F.UNIDADEMEDIDA_SIGLA UM,
                                        F.UNIDADEMEDIDA_ALTERNATIVO UM_ALTERNATIVA,
                                        T.TURNO,
                                        e.descricao,

                                        coalesce ((SELECT SUM(IIF(D1.QUANTIDADE_ALTERN_PRODUCAO > 0, D1.QUANTIDADE_ALTERN_PRODUCAO,D1.QUANTIDADE_PRODUCAO))
                                                FROM VWREMESSA_TALAO_DETALHE D1
                                                WHERE D1.REMESSA_ID = T.REMESSA_ID AND D1.REMESSA_TALAO_ID = T.REMESSA_TALAO_ID),0) PRODUZIDO,

                                        coalesce ((SELECT first 1 h.descricao FROM VWREMESSA_TALAO_DETALHE D1,tbperfil h
                                                WHERE D1.REMESSA_ID = T.REMESSA_ID AND D1.REMESSA_TALAO_ID = T.REMESSA_TALAO_ID
                                                and h.id = d1.perfil
                                                and h.tabela = 'SKU'
                                                and h.familia_id = :FAMILIA2),0) PERFIL

                                     FROM VWREMESSA_TALAO T, VWREMESSA R, TBFAMILIA F, tbmodelo M, tbup e

                                    WHERE 1=1
                                      AND R.REMESSA_ID = T.REMESSA_ID
                                      AND F.CODIGO = R.FAMILIA_ID
                                      AND f.codigo        = :FAMILIA
                                      and r.familia_id    = f.codigo
                                      AND R.ESTABELECIMENTO_ID = :ESTABELECIMENTO
                                      AND T.DATA_PRODUCAO BETWEEN '".$data_inicio."' and '".$data_fim."'
                                      and M.codigo = t.modelo_id
                                      and e.id = t.up_id


                                   ) Y

                                   Group By
                                        Y.PERFIL,
                                        Y.UM,
                                        Y.UM_ALTERNATIVA,
                                        Y.TURNO


                            ) X

                        GROUP BY
                            X.descricao,
                            UM,
                            T1,
                            T2

                    ) Z where z.PRODUZIDO > 0
                    GROUP BY 1,2
                    ) W order by QUANTIDADE desc";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias'],
                ':FAMILIA2'         => $dados['familias']
            );

            $ret3 = $con->query($sql, $args);

            return [$ret1, $ret2, $ret3];
            
            $con->commit();

        } catch (Exception $e) {
            
            $con->rollback();
            throw $e;
        }
    }

    /**
     * faturamento dia
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function producaoDia($dados) {

        $con = new _Conexao();

        $entrada = '';
        if($dados['periodo_pedido'] == 'd'){
            $entrada = 'Data';
        }else{
            $entrada = 'Data_Cliente';
        }
        
        try {
            
            $data_inicio = date('d.m.Y', strtotime($dados['periodo_inicial']));
            $data_fim    = date('d.m.Y', strtotime($dados['periodo_final']));
            
            $sql = "
                SELECT

                descricao,
                UNIDADE,
                QUANTIDADE,
                QTD_TURNO1,
                QTD_TURNO2,
                TALOES

                from (
                SELECT
                    descricao,
                    unidademedida_sigla as UNIDADE,
                    sum(QUANTIDADE) as QUANTIDADE,
                    sum(T1) as QTD_TURNO1,
                    sum(T2) as QTD_TURNO2,
                    sum(TALOES) as TALOES


                from
                (SELECT

                   b.descricao,
                   b.QUANTIDADE,
                   b.unidademedida_sigla,
                   iif(b.turno = 1,b.quantidade,0) as T1,
                   iif(b.turno = 2,b.quantidade,0) as T2,
                   b.TALOES

                from
                (Select
                        m.descricao,
                        A.turno,
                        F.unidademedida_sigla,
                        sum( Trunc(Coalesce(A.QUANTIDADE_PEDIDO,0))) as quantidade,
                        count(remessa) as TALOES

                  From TbEsteira E,TbPedido_Item_Processado a,tbperfil p, tbfamilia f, tbmodelo m where
                       A.Esteira_Producao = E.Codigo
                   and A.Estabelecimento_Codigo = :ESTABELECIMENTO
                   and A.Data_Producao between '".$data_inicio."' and '".$data_fim."'
                   and A.Esteira_Producao = E.CODIGO
                   and p.id = a.perfil
                   and p.tabela = 'SKU'
                   and a.familia_codigo = :FAMILIA
                   and f.codigo = a.familia_codigo
                   and m.codigo = a.modelo_codigo

                   group by 1,2,3
                )b
                )c group by 1,2
                )d order by QUANTIDADE desc";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias']
            );

            $ret1 = $con->query($sql, $args);

            $sql = "
                SELECT

                descricao as LINHA,
                UNIDADE,
                QUANTIDADE,
                QTD_TURNO1,
                QTD_TURNO2,
                TALOES


                from (
                SELECT
                    descricao,
                    unidademedida_sigla as UNIDADE,
                    sum(QUANTIDADE) as QUANTIDADE,
                    sum(T1) as QTD_TURNO1,
                    sum(T2) as QTD_TURNO2,
                    sum(TALOES) as TALOES

                from
                (SELECT

                   b.descricao,
                   b.QUANTIDADE,
                   b.unidademedida_sigla,
                   iif(b.turno = 1,b.quantidade,0) as T1,
                   iif(b.turno = 2,b.quantidade,0) as T2,
                   b.TALOES

                from
                (Select
                        l.descricao,
                        A.turno,
                        F.unidademedida_sigla,
                        sum( Trunc(Coalesce(A.QUANTIDADE_PEDIDO,0))) as quantidade,
                        count(remessa) as TALOES

                  From TbEsteira E,TbPedido_Item_Processado a,tbperfil p, tbfamilia f, tbmodelo m, tbmodelo_linha l where
                       A.Esteira_Producao = E.Codigo
                   and A.Estabelecimento_Codigo = :ESTABELECIMENTO
                   and A.Data_Producao between '".$data_inicio."' and '".$data_fim."'
                   and A.Esteira_Producao = E.CODIGO
                   and p.id = a.perfil
                   and p.tabela = 'SKU'
                   and a.familia_codigo = :FAMILIA
                   and f.codigo = a.familia_codigo
                   and m.codigo = a.modelo_codigo
                   and l.codigo = m.linha_codigo

                   group by 1,2,3
                )b
                )c group by 1,2
                )d order by QUANTIDADE desc";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias']
            );

            $ret2 = $con->query($sql, $args);

            $sql = "
                SELECT

                descricao,
                UNIDADE,
                QUANTIDADE,
                QTD_TURNO1,
                QTD_TURNO2,
                TALOES


                from (
                SELECT
                    descricao,
                    unidademedida_sigla as UNIDADE,
                    sum(QUANTIDADE) as QUANTIDADE,
                    sum(T1) as QTD_TURNO1,
                    sum(T2) as QTD_TURNO2,
                    sum(TALOES) as TALOES

                from
                (SELECT

                   b.descricao,
                   b.QUANTIDADE,
                   b.unidademedida_sigla,
                   iif(b.turno = 1,b.quantidade,0) as T1,
                   iif(b.turno = 2,b.quantidade,0) as T2,
                   b.TALOES

                from
                (Select
                        e.descricao,
                        A.turno,
                        F.unidademedida_sigla,
                        sum( Trunc(Coalesce(A.QUANTIDADE_PEDIDO,0))) as quantidade,
                        count(remessa) as TALOES

                  From TbEsteira E,TbPedido_Item_Processado a,tbperfil p, tbfamilia f, tbmodelo m, tbmodelo_linha l where
                       A.Esteira_Producao = E.Codigo
                   and A.Estabelecimento_Codigo = :ESTABELECIMENTO
                   and A.Data_Producao between '".$data_inicio."' and '".$data_fim."'
                   and A.Esteira_Producao = E.CODIGO
                   and p.id = a.perfil
                   and p.tabela = 'SKU'
                   and a.familia_codigo = :FAMILIA
                   and f.codigo = a.familia_codigo
                   and m.codigo = a.modelo_codigo
                   and l.codigo = m.linha_codigo

                   group by 1,2,3
                )b
                )c group by 1,2
                )d order by QUANTIDADE desc";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias']
            );

            $ret3 = $con->query($sql, $args);

            $sql = "
                SELECT

                descricao,
                UNIDADE,
                QUANTIDADE,
                QTD_TURNO1,
                QTD_TURNO2,
                TALOES


                from (
                SELECT
                    descricao,
                    unidademedida_sigla as UNIDADE,
                    sum(QUANTIDADE) as QUANTIDADE,
                    sum(T1) as QTD_TURNO1,
                    sum(T2) as QTD_TURNO2,
                    sum(TALOES) as TALOES

                from
                (SELECT

                   b.descricao,
                   b.QUANTIDADE,
                   b.unidademedida_sigla,
                   iif(b.turno = 1,b.quantidade,0) as T1,
                   iif(b.turno = 2,b.quantidade,0) as T2,
                   b.TALOES

                from
                (Select
                        p.descricao,
                        A.turno,
                        F.unidademedida_sigla,
                        sum( Trunc(Coalesce(A.QUANTIDADE_PEDIDO,0))) as quantidade,
                        count(remessa) as TALOES

                  From TbEsteira E,TbPedido_Item_Processado a,tbperfil p, tbfamilia f, tbmodelo m, tbmodelo_linha l where
                       A.Esteira_Producao = E.Codigo
                   and A.Estabelecimento_Codigo = :ESTABELECIMENTO
                   and A.Data_Producao between '".$data_inicio."' and '".$data_fim."'
                   and A.Esteira_Producao = E.CODIGO
                   and p.id = a.perfil
                   and p.tabela = 'SKU'
                   and p.familia_id = :FAMILIA3
                   and a.familia_codigo = :FAMILIA
                   and f.codigo = a.familia_codigo
                   and m.codigo = a.modelo_codigo
                   and l.codigo = m.linha_codigo

                   group by 1,2,3
                )b
                )c group by 1,2
                )d order by QUANTIDADE desc";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias'],
                ':FAMILIA3'         => $dados['familias']
            );

            $ret4 = $con->query($sql, $args);

            $sql = "
                SELECT
                    *
                from(
                    select
                    
                        d.cor as DESCRICAO,
                        sum(d.quantidade) as quantidade,
                        sum(d.qtd_turno1) as qtd_turno1,
                        sum(d.qtd_turno2) as qtd_turno2,
                        sum(d.TALOES) TALOES
                    
                    from vwregistro_producao_dia d
                    
                    where 1 = 1
                    and d.estabelecimento_id = :ESTABELECIMENTO
                    and d.familia_id = :FAMILIA
                    and d.data_registro between '".$data_inicio."' and '".$data_fim."'
                    
                    group by d.cor
                ) order by quantidade desc";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias']
            );

            $ret5 = $con->query($sql, $args);

            $sql = "
                SELECT
                    *
                from(
                    select
                    
                        d.densidade as DESCRICAO,
                        sum(d.quantidade) as quantidade,
                        sum(d.qtd_turno1) as qtd_turno1,
                        sum(d.qtd_turno2) as qtd_turno2,
                        sum(d.TALOES) TALOES
                    
                    from vwregistro_producao_dia d
                    
                    where 1 = 1
                    and d.estabelecimento_id = :ESTABELECIMENTO
                    and d.familia_id = :FAMILIA
                    and d.data_registro between '".$data_inicio."' and '".$data_fim."'
                    
                    group by d.densidade
                ) order by quantidade desc";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias']
            );

            $ret6 = $con->query($sql, $args);

            return [$ret1, $ret2, $ret3, $ret4, $ret5, $ret6];
            
            $con->commit();

        } catch (Exception $e) {
            
            $con->rollback();
            throw $e;
        }
    }
    
    /**
     * faturamento
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function faturamento($dados,$con) {

        $entrada = '';
        if($dados['periodo_pedido'] == 'd'){
            $entrada = 'Data';
        }else{
            $entrada = 'Data_Cliente';
        }
        
        try {
            
            $data_inicio = date('d.m.Y', strtotime($dados['periodo_inicial']));
            $data_fim    = date('d.m.Y', strtotime($dados['periodo_final']));
            
            $sql = "
                SELECT Z.Data_Emissao, Sum(Z.Quantidade) Quantidade
                From
                (
                SELECT D.Data_Venda Data_Emissao, SUM(A.Quantidade) Quantidade
                From TBCF_VENDA_CABECALHO D, TbCF_VENDA_DETALHE A, TbProduto P
                Where D.estabelecimento_id = A.estabelecimento_id
                  and D.caixa_id = A.caixa_id
                  and D.ID = A.Id_ecf_venda_cabecalho
                  and A.id_ecf_produto = P.Codigo
                  and A.Cancelado = 'N'
                  and D.Cupom_cancelado = 'N'
                  and D.estabelecimento_id =  :ESTABELECIMENTO1
                  and D.Data_Venda between '".$data_inicio."' and '".$data_fim."'
                  and P.Familia_Codigo = :FAMILIA1
                Group By 1

                UNION

                SELECT A.Data_Emissao, SUM(A.Quantidade) Quantidade
                From TBNFS D, TbNfs_Item A, TbOperacao C, TbProduto P
                Where D.CONTROLE  = A.Nfs_controle
                  and A.Operacao_Codigo = C.Codigo
                  and a.Produto_Codigo = P.Codigo
                  and ((D.Natureza = 1 and D.Situacao = 2) or (D.Natureza = 2 and D.Situacao = 1))
                  and C.Controle_Faturamento = 1
                  and A.Numero_NotaFiscal > 0
                  and A.Estabelecimento_Codigo = :ESTABELECIMENTO2
                  and A.Data_Emissao between '".$data_inicio."' and '".$data_fim."'
                  and P.Familia_Codigo = :FAMILIA2
                Group By 1

                ) Z Group By 1";

            $args = array(
                ':ESTABELECIMENTO1'  => $dados['estabelecimento'],
                ':FAMILIA1'          => $dados['familias'],
                ':ESTABELECIMENTO2'  => $dados['estabelecimento'],
                ':FAMILIA2'          => $dados['familias']
            );
            
            $ret = $con->query($sql, $args);

			return $ret;
            
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    /**
     * devolucao
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function devolucao($dados,$con) {

        $entrada = '';
        if($dados['periodo_pedido'] == 'd'){
            $entrada = 'Data';
        }else{
            $entrada = 'Data_Cliente';
        }
        
        try {
            
            $data_inicio = date('d.m.Y', strtotime($dados['periodo_inicial']));
            $data_fim    = date('d.m.Y', strtotime($dados['periodo_final']));
            
            $sql = "
                SELECT A.Data_Entrada, SUM(A.Quantidade) Quantidade
                From TBNFE D, TbNfE_Item A, TbOperacao C, TbProduto P
                Where D.CONTROLE  = A.NfE_controle
                  and A.Operacao_Codigo = C.Codigo
                  and a.Produto_Codigo = P.Codigo
                  and ((D.Natureza = 1 and D.Situacao = 2) or (D.Natureza = 2 and D.Situacao = 1))
                  and C.Controle_devolucao = 1
                  and A.Numero_NotaFiscal > 0
                  and A.Estabelecimento_Codigo = :ESTABELECIMENTO
                  and A.Data_Entrada between '".$data_inicio."' and '".$data_fim."'
                  and P.Familia_Codigo = :FAMILIA
                Group By 1";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias']
            );

            $ret = $con->query($sql, $args);
            
            return $ret;
            
        } catch (Exception $e) {
            throw $e;
        }
    }


    
    /**
     * defeito
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function defeito($dados,$con) {

        $entrada = '';
        if($dados['periodo_pedido'] == 'd'){
            $entrada = 'Data';
        }else{
            $entrada = 'Data_Cliente';
        }
        
        try {
            
            $data_inicio = date('d.m.Y', strtotime($dados['periodo_inicial']));
            $data_fim    = date('d.m.Y', strtotime($dados['periodo_final']));
            
            $sql = "
                SELECT
                X.DATA,
                SUM(X.QUANTIDADE) QUANTIDADE,
                SUM(X.QTD_TURNO1) QTD_TURNO1,
                SUM(X.QTD_TURNO2) QTD_TURNO2
                From (
                Select B.Data, B.Quantidade, IIF(B.TURNO=1,B.Quantidade,0) QTD_TURNO1, IIF(B.TURNO=2,B.Quantidade,0) QTD_TURNO2
                From TbDefeito_Transacao_Item B, TbEsteira E, TbProduto P
                Where B.Esteira_id = E.Codigo
                  and B.Produto_id = P.Codigo
                  and B.Estabelecimento_Id = :ESTABELECIMENTO
                  and B.Data between '".$data_inicio."' and '".$data_fim."'
                  AND (B.DEFEITO_ID > 0)
                  and P.Familia_Codigo = :FAMILIA
                ) X
                Group By X.Data";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $dados['familias']
            );

            $ret = $con->query($sql, $args);
			
			return $ret;
            
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    /**
     * producao
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function producao($dados,$con) {

        $entrada = '';
        if($dados['periodo_pedido'] == 'd'){
            $entrada = 'Data';
        }else{
            $entrada = 'Data_Cliente';
        }
        
        try {
            
            $data_inicio = date('d.m.Y', strtotime($dados['periodo_inicial']));
            $data_fim    = date('d.m.Y', strtotime($dados['periodo_final']));
            
            $sql = "
                SELECT

                DATA_PRODUCAO,
                UNIDADE,
                QUANTIDADE,
                QTD_TURNO1,
                QTD_TURNO2,
                0 DEFEITO,
                0 as TALOES

                from      (SELECT

                    DATA_PRODUCAO,
                    unidademedida_sigla as UNIDADE,
                    sum(QUANTIDADE) as QUANTIDADE,
                    sum(T1) as QTD_TURNO1,
                    sum(T2) as QTD_TURNO2

                from
                (SELECT

                   b.Data_Producao,
                   b.QUANTIDADE,
                   b.unidademedida_sigla,
                   iif(b.turno = 1,b.quantidade,0) as T1,
                   iif(b.turno = 2,b.quantidade,0) as T2

                from
                  (Select

                          A.Data_Producao,
                          A.turno,
                          F.unidademedida_sigla,
                          sum( Trunc(Coalesce(A.QUANTIDADE_PEDIDO,0))) as quantidade

                    From TbEsteira E,TbPedido_Item_Processado a,tbperfil p, tbfamilia f where
                         A.Esteira_Producao = E.Codigo
                     and A.Estabelecimento_Codigo = :ESTABELECIMENTO
                     and A.Data_Producao between '".$data_inicio."' and '".$data_fim."'
                     and A.Esteira_Producao = E.CODIGO
                     and p.id = a.perfil
                     and p.tabela = 'SKU'
                     and a.familia_codigo = :FAMILIA
                     and f.codigo = a.familia_codigo

                     group by 1,2,3
                )b
                )c group by 1,2
                )d

                ";

            $args = array(
                ':ESTABELECIMENTO' => $dados['estabelecimento'],
                ':FAMILIA'         => $dados['familias']
            );

            $ret = $con->query($sql, $args);
			
			return $ret;
            
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    /**
     * familias
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function familias($dados) {
        
        $con = new _Conexao();
        
        try {
            
            $data_inicio = date('d.m.Y', strtotime($dados['periodo_inicial']));
            $data_fim    = date('d.m.Y', strtotime($dados['periodo_final']));
            
            $sql = "
                SELECT
                    f.descricao_amigavel as descricao, r.familia_codigo
                from
                tbremessa r,tbfamilia f, vwremessa_talao t

                where r.data between '".$data_inicio."' and '".$data_fim."'
                and r.estabelecimento_codigo = :ESTABELECIMENTO
                and f.codigo = r.familia_codigo
                and t.remessa_id = r.numero
                and t.status = 2
                group by 1,2";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento']
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
     * producaoes
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function producaoes($dados,$familia){
        
        $con = new _Conexao();

        $entrada = '';
        if($dados['periodo_pedido'] == 'd'){
            $entrada = 'Data';
        }else{
            $entrada = 'Data_Cliente';
        }
        
        try {
            
            $data_inicio = date('d.m.Y', strtotime($dados['periodo_inicial']));
            $data_fim    = date('d.m.Y', strtotime($dados['periodo_final']));
            
            $sql = "
                SELECT

                    DATA_PRODUCAO,
                    UNIDADE,
                    sum(QUANTIDADE) as QUANTIDADE,
                    sum(T1) as QTD_TURNO1,
                    sum(T2) as QTD_TURNO2

                from
                (select

                   b.Data_Producao,
                   b.QUANTIDADE,
                   b.unidade,
                   iif(b.turno = '1',b.quantidade,0) as T1,
                   iif(b.turno = '2',b.quantidade,0) as T2


                from
                (Select

                        A.Data_Producao,
                        A.turno,
                        f.unidademedida_sigla as unidade,
                        sum( Coalesce(A.quantidade,0)) as quantidade


                  From tbremessa_item_processado a
                  ,tbmodelo m
                  ,tbfamilia f

                   where
                       1 = 1
                   and A.Estabelecimento_Codigo = :ESTABELECIMENTO
                   and A.Data_Producao between '".$data_inicio."' and '".$data_fim."'
                   and m.codigo = a.modelo_codigo
                   and m.familia_codigo = :FAMILIA
                   and f.codigo = m.familia_codigo

                   group by 1,2,3
                )b
                )c group by 1,2";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $familia
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
     * producaoes
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function producaoes2($dados,$familia,$con){

        $entrada = '';
        if($dados['periodo_pedido'] == 'd'){
            $entrada = 'Data';
        }else{
            $entrada = 'Data_Cliente';
        }
        
        try {
            
            $data_inicio = date('d.m.Y', strtotime($dados['periodo_inicial']));
            $data_fim    = date('d.m.Y', strtotime($dados['periodo_final']));
            
            $sql = "
                SELECT
                Z.DATA_PRODUCAO,
                Z.UM as UNIDADE,

                sum(Z.TALOES) as TALOES,
                sum(Z.PRODUZIDO) as QUANTIDADE,

                sum(T1) as QTD_TURNO1,
                sum(T2) as QTD_TURNO2,

                0 as DEFEITO

                FROM(

                    SELECT
                        X.DATA_PRODUCAO,
                        IIF(X.UM_ALTERNATIVA <> '', X.UM_ALTERNATIVA, X.UM) UM,

                        iif(X.turno = '1',X.PRODUZIDO,0) as T1,
                        iif(X.turno = '2',X.PRODUZIDO,0) as T2,

                        --/ PRODUZIDO /
                        SUM(X.TALOES) TALOES,
                        SUM(X.PRODUZIDO) PRODUZIDO

                        FROM (

                            Select  Y.DATA_PRODUCAO,
                                    Y.UM,
                                    Y.UM_ALTERNATIVA,
                                    Y.TURNO,
                                    SUM(Y.PRODUZIDO) PRODUZIDO,
                                    COUNT(remessa_talao_id) TALOES
                               From (

                                SELECT R.REMESSA, T.remessa_talao_id, T.DATA_PRODUCAO,
                                    F.UNIDADEMEDIDA_SIGLA UM,
                                    F.UNIDADEMEDIDA_ALTERNATIVO UM_ALTERNATIVA,
                                    T.TURNO,

                                        (SELECT SUM(IIF(D1.QUANTIDADE_ALTERN_PRODUCAO > 0, D1.QUANTIDADE_ALTERN_PRODUCAO, D1.QUANTIDADE_PRODUCAO))
                                            FROM VWREMESSA_TALAO_DETALHE D1
                                            WHERE D1.REMESSA_ID = T.REMESSA_ID AND D1.REMESSA_TALAO_ID = T.REMESSA_TALAO_ID) PRODUZIDO


                                 FROM VWREMESSA_TALAO T, VWREMESSA R, TBFAMILIA F

                                WHERE 1=1
                                  AND R.REMESSA_ID = T.REMESSA_ID
                                  AND F.CODIGO = R.FAMILIA_ID
                                  AND f.codigo        = :FAMILIA
                                  and r.familia_id    = f.codigo
                                  AND R.ESTABELECIMENTO_ID = :ESTABELECIMENTO
                                  AND T.STATUS = 2
                                  AND T.DATA_PRODUCAO BETWEEN '".$data_inicio."' and '".$data_fim."'
                                  

                               ) Y

                               Group By
                                    Y.DATA_PRODUCAO,
                                    Y.UM,
                                    Y.UM_ALTERNATIVA,
                                    Y.TURNO

                        ) X

                    GROUP BY
                        X.DATA_PRODUCAO,
                        UM,
                        T1,
                        T2

                ) Z
                GROUP BY 1,2";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $familia
            );

            $ret = $con->query($sql, $args);

      return $ret;
            
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * producaoes
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function defeito2($dados,$familia,$con){

        $entrada = '';
        if($dados['periodo_pedido'] == 'd'){
            $entrada = 'Data';
        }else{
            $entrada = 'Data_Cliente';
        }
        
        try {
            
            $data_inicio = date('d.m.Y', strtotime($dados['periodo_inicial']));
            $data_fim    = date('d.m.Y', strtotime($dados['periodo_final']));
            
            $sql = "
                SELECT

                    y.DATA as DATA_PRODUCAO,
                    SUM(y.QUANTIDADE) QUANTIDADE,
                    SUM(y.QTD_1) QTD_1,
                    SUM(y.QTD_2) QTD_2,
                    SUM(y.QTD_3) QTD_3,
                    SUM(y.QTD_TURNO1) QTD_TURNO1,
                    SUM(y.QTD_TURNO2) QTD_TURNO2

                from (

                   SELECT

                        data,
                        QUANTIDADE,
                        IIF(classificacao=2,Quantidade,0) QTD_1,
                        IIF(classificacao=3,Quantidade,0) QTD_2,
                        IIF(classificacao=4,Quantidade,0) QTD_3,

                        IIF(turno=1,Quantidade,0) QTD_TURNO1,
                        IIF(turno=2,Quantidade,0) QTD_TURNO2


                    From (
                    Select

                        B.data,
                        B.Quantidade,
                        (select first 1 j.CLASSIFICACAO from tbsac_defeito j where j.codigo = b.defeito_id) as classificacao,
                        IIF(B.turno = 0,(select first 1 x.codigo from tbturno x where (COMPARE_HORA(x.hora_inicio,x.hora_fim,b.defeito_hora)) = 1),B.turno) as turno

                    From TbDefeito_Transacao_Item B,  TbProduto P
                    Where TRUE
                      and B.produto_id = P.codigo
                      and B.Estabelecimento_Id = :ESTABELECIMENTO
                      and B.Data between '".$data_inicio."' and '".$data_fim."'
                      AND (B.DEFEITO_ID > 0)
                      and P.Familia_Codigo = :FAMILIA

                    ) X

                ) y group by 1


                ";

            $args = array(
                ':ESTABELECIMENTO'  => $dados['estabelecimento'],
                ':FAMILIA'          => $familia
            );

            $ret = $con->query($sql, $args);

      return $ret;
            
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    /**
     * faturamentoPorFamilia
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function faturamentoPorFamilia($dados,$con){

        $entrada = '';
        if($dados['periodo_pedido'] == 'd'){
            $entrada = 'Data';
        }else{
            $entrada = 'Data_Cliente';
        }
        
        try {
            
            $data_inicio = date('d.m.Y', strtotime($dados['periodo_inicial']));
            $data_fim    = date('d.m.Y', strtotime($dados['periodo_final']));
            
            $sql = "
                SELECT
                    s.CODIGO,
                    s.CODIGO||' - '||s.descricao as DESCRICAO,
                    s.QUANTIDADE

                    from(
                    Select
                     lpad(Z.familia_codigo,4,0) as CODIGO,
                     f.descricao_amigavel as descricao,
                     Sum(Z.Quantidade) Quantidade
                            From
                            (
                            Select p.familia_codigo , SUM(A.Quantidade) Quantidade
                            From TBCF_VENDA_CABECALHO D, TbCF_VENDA_DETALHE A, TbProduto P
                            Where D.estabelecimento_id = A.estabelecimento_id
                              and D.caixa_id = A.caixa_id
                              and D.ID = A.Id_ecf_venda_cabecalho
                              and A.id_ecf_produto = P.Codigo
                              and A.Cancelado = 'N'
                              and D.Cupom_cancelado = 'N'
                              and D.estabelecimento_id =  :ESTABELECIMENTO1
                              and D.Data_Venda between '".$data_inicio."' and '".$data_fim."'
                            Group By 1

                            UNION

                            SELECT p.familia_codigo, SUM(A.Quantidade) Quantidade
                            From TBNFS D, TbNfs_Item A, TbOperacao C, TbProduto P
                            Where D.CONTROLE  = A.Nfs_controle
                              and A.Operacao_Codigo = C.Codigo
                              and a.Produto_Codigo = P.Codigo
                              and ((D.Natureza = 1 and D.Situacao = 2) or (D.Natureza = 2 and D.Situacao = 1))
                              and C.Controle_Faturamento = 1
                              and A.Numero_NotaFiscal > 0
                              and A.Estabelecimento_Codigo = :ESTABELECIMENTO2
                              and A.Data_Emissao between '".$data_inicio."' and '".$data_fim."'
                            Group By 1

                            ) Z, tbfamilia f where f.codigo = Z.familia_codigo Group By 1,2
                        ) s order by DESCRICAO";

            $args = array(
                ':ESTABELECIMENTO1'  => $dados['estabelecimento'],
                ':ESTABELECIMENTO2'  => $dados['estabelecimento'],
            );

            $ret = $con->query($sql, $args);
			
			return $ret;
            
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    /**
     * faturamentoPorFamilia
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function detalharFamilia($dados){
        
        $con = new _Conexao();
        
        try {
            
            $data_inicio = date('d.m.Y', strtotime($dados['periodo_inicial']));
            $data_fim    = date('d.m.Y', strtotime($dados['periodo_final']));
            
            $sql = "SELECT
                    
                    CODIGO,
                    RAZAOSOCIAL,
                    QUANTIDADE
                    
                    from( SELECT
                     lpad(Z.ID_CLIENTE,6,0) as CODIGO,
                     lpad( f.codigo,6,'0')||' - '||F.razaosocial||' ('||f.UF||')' as razaosocial,
                     sum(QUANTIDADE) as QUANTIDADE

                    From
                    (
                    SELECT S.id_cliente,'ECF' TIPO, S.id DOCFISCAL, p.familia_codigo,s.data_venda as data_emissao, SUM(A.Quantidade) AS Quantidade
                    From TBCF_VENDA_CABECALHO S, TbCF_VENDA_DETALHE A, TbProduto P
                    Where S.estabelecimento_id = A.estabelecimento_id
                      and S.caixa_id = A.caixa_id
                      and S.ID = A.Id_ecf_venda_cabecalho
                      and A.id_ecf_produto = P.Codigo
                      and A.Cancelado = 'N'
                      and S.Cupom_cancelado = 'N'
                      and S.estabelecimento_id =  :ESTABELECIMENTO1
                      and S.Data_Venda between  '".$data_inicio."' and '".$data_fim."'
                      AND P.familia_codigo = :FAMILIA1
                    Group By 1,2,3,4,5

                    UNION

                    SELECT D.empresa_codigo AS id_cliente,'NF' TIPO,D.numero_notafiscal AS DOCFISCAL, p.familia_codigo,d.data_emissao, SUM(A.Quantidade) AS Quantidade
                    From TBNFS D, TbNfs_Item A, TbOperacao C, TbProduto P
                    Where D.CONTROLE  = A.Nfs_controle
                      and A.Operacao_Codigo = C.Codigo
                      and a.Produto_Codigo = P.Codigo
                      and ((D.Natureza = 1 and D.Situacao = 2) or (D.Natureza = 2 and D.Situacao = 1))
                      and C.Controle_Faturamento = 1
                      and A.Numero_NotaFiscal > 0
                      and A.Estabelecimento_Codigo = :ESTABELECIMENTO2
                      and A.Data_Emissao between  '".$data_inicio."' and '".$data_fim."'
                      AND P.familia_codigo = :FAMILIA2
                    Group By 1,2,3,4,5

                    ) Z, tbempresa f where f.codigo = Z.id_cliente
                    group by 1,2
                    ) q order by 3 desc";

            $args = array(
                ':ESTABELECIMENTO1'  => $dados['estabelecimento'],
                ':ESTABELECIMENTO2'  => $dados['estabelecimento'],
                ':FAMILIA1'          => $dados['familias'],
                ':FAMILIA2'          => $dados['familias']
            );

            $ret1 = $con->query($sql, $args);

            $sql = "SELECT
                    
                      CODIGO,
                      RAZAOSOCIAL,
                      UF,
                      QUANTIDADE
                      
                      from( SELECT
                       lpad(r.codigo,6,0) as CODIGO,
                       lpad( r.codigo,6,'0')||' - '||r.razaosocial||' ('||r.UF||')' as razaosocial,
                       f.uf,
                       sum(QUANTIDADE) as QUANTIDADE

                      From
                      (
                      SELECT S.id_cliente,'ECF' TIPO, S.id DOCFISCAL, p.familia_codigo,s.data_venda as data_emissao, SUM(A.Quantidade) AS Quantidade
                      From TBCF_VENDA_CABECALHO S, TbCF_VENDA_DETALHE A, TbProduto P
                      Where S.estabelecimento_id = A.estabelecimento_id
                        and S.caixa_id = A.caixa_id
                        and S.ID = A.Id_ecf_venda_cabecalho
                        and A.id_ecf_produto = P.Codigo
                        and A.Cancelado = 'N'
                        and S.Cupom_cancelado = 'N'
                        and S.estabelecimento_id =  :ESTABELECIMENTO1
                        and S.Data_Venda between  '".$data_inicio."' and '".$data_fim."'
                        AND P.familia_codigo = :FAMILIA1
                      Group By 1,2,3,4,5

                      UNION

                      SELECT D.empresa_codigo AS id_cliente,'NF' TIPO,D.numero_notafiscal AS DOCFISCAL, p.familia_codigo,d.data_emissao, SUM(A.Quantidade) AS Quantidade
                      From TBNFS D, TbNfs_Item A, TbOperacao C, TbProduto P
                      Where D.CONTROLE  = A.Nfs_controle
                        and A.Operacao_Codigo = C.Codigo
                        and a.Produto_Codigo = P.Codigo
                        and ((D.Natureza = 1 and D.Situacao = 2) or (D.Natureza = 2 and D.Situacao = 1))
                        and C.Controle_Faturamento = 1
                        and A.Numero_NotaFiscal > 0
                        and A.Estabelecimento_Codigo = :ESTABELECIMENTO2
                        and A.Data_Emissao between  '".$data_inicio."' and '".$data_fim."'
                        AND P.familia_codigo = :FAMILIA2
                      Group By 1,2,3,4,5

                      ) Z, tbcliente f, tbrepresentante r
                          where f.codigo = Z.id_cliente
                          and r.codigo = f.representante_codigo
                      group by 1,2,3
                      ) q order by 4 desc";

            $args = array(
                ':ESTABELECIMENTO1'  => $dados['estabelecimento'],
                ':ESTABELECIMENTO2'  => $dados['estabelecimento'],
                ':FAMILIA1'          => $dados['familias'],
                ':FAMILIA2'          => $dados['familias']
            );

            $ret2 = $con->query($sql, $args);


            $sql = "SELECT
                    
                      UF,
                      QUANTIDADE
                      
                      from( SELECT
                       f.uf,
                       sum(QUANTIDADE) as QUANTIDADE

                      From
                      (
                      SELECT S.id_cliente,'ECF' TIPO, S.id DOCFISCAL, p.familia_codigo,s.data_venda as data_emissao, SUM(A.Quantidade) AS Quantidade
                      From TBCF_VENDA_CABECALHO S, TbCF_VENDA_DETALHE A, TbProduto P
                      Where S.estabelecimento_id = A.estabelecimento_id
                        and S.caixa_id = A.caixa_id
                        and S.ID = A.Id_ecf_venda_cabecalho
                        and A.id_ecf_produto = P.Codigo
                        and A.Cancelado = 'N'
                        and S.Cupom_cancelado = 'N'
                        and S.estabelecimento_id =  :ESTABELECIMENTO1
                        and S.Data_Venda between  '".$data_inicio."' and '".$data_fim."'
                        AND P.familia_codigo = :FAMILIA1
                      Group By 1,2,3,4,5

                      UNION

                      SELECT D.empresa_codigo AS id_cliente,'NF' TIPO,D.numero_notafiscal AS DOCFISCAL, p.familia_codigo,d.data_emissao, SUM(A.Quantidade) AS Quantidade
                      From TBNFS D, TbNfs_Item A, TbOperacao C, TbProduto P
                      Where D.CONTROLE  = A.Nfs_controle
                        and A.Operacao_Codigo = C.Codigo
                        and a.Produto_Codigo = P.Codigo
                        and ((D.Natureza = 1 and D.Situacao = 2) or (D.Natureza = 2 and D.Situacao = 1))
                        and C.Controle_Faturamento = 1
                        and A.Numero_NotaFiscal > 0
                        and A.Estabelecimento_Codigo = :ESTABELECIMENTO2
                        and A.Data_Emissao between  '".$data_inicio."' and '".$data_fim."'
                        AND P.familia_codigo = :FAMILIA2
                      Group By 1,2,3,4,5

                      ) Z, tbcliente f, tbrepresentante r
                          where f.codigo = Z.id_cliente
                          and r.codigo = f.representante_codigo
                      group by 1
                      ) q order by 2 desc";

            $args = array(
                ':ESTABELECIMENTO1'  => $dados['estabelecimento'],
                ':ESTABELECIMENTO2'  => $dados['estabelecimento'],
                ':FAMILIA1'          => $dados['familias'],
                ':FAMILIA2'          => $dados['familias']
            );

            $ret3 = $con->query($sql, $args);

            $con->commit();
      
      return [$ret1,$ret2,$ret3];
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * faturamentoPorFamilia
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function detalharFamilia2($dados){
        
        $con = new _Conexao();
        
        try {
            
            $data_inicio = date('d.m.Y', strtotime($dados['periodo_inicial']));
            $data_fim    = date('d.m.Y', strtotime($dados['periodo_final']));
            
            $sql = "SELECT
                    
                    CODIGO,
                    RAZAOSOCIAL,
                    QUANTIDADE
                    
                    from( SELECT
                     lpad(Z.ID_CLIENTE,6,0) as CODIGO,
                     lpad( f.codigo,6,'0')||' - '||F.razaosocial||' ('||f.UF||')' as razaosocial,
                     sum(QUANTIDADE) as QUANTIDADE

                    From
                    (
                    SELECT S.id_cliente,'ECF' TIPO, S.id DOCFISCAL, p.familia_codigo,s.data_venda as data_emissao, SUM(A.Quantidade) AS Quantidade
                    From TBCF_VENDA_CABECALHO S, TbCF_VENDA_DETALHE A, TbProduto P
                    Where S.estabelecimento_id = A.estabelecimento_id
                      and S.caixa_id = A.caixa_id
                      and S.ID = A.Id_ecf_venda_cabecalho
                      and A.id_ecf_produto = P.Codigo
                      and A.Cancelado = 'N'
                      and S.Cupom_cancelado = 'N'
                      and S.estabelecimento_id =  :ESTABELECIMENTO1
                      and S.Data_Venda between  '".$data_inicio."' and '".$data_fim."'
                      AND P.familia_codigo  in (".$dados['familias'].")
                    Group By 1,2,3,4,5

                    UNION

                    SELECT D.empresa_codigo AS id_cliente,'NF' TIPO,D.numero_notafiscal AS DOCFISCAL, p.familia_codigo,d.data_emissao, SUM(A.Quantidade) AS Quantidade
                    From TBNFS D, TbNfs_Item A, TbOperacao C, TbProduto P
                    Where D.CONTROLE  = A.Nfs_controle
                      and A.Operacao_Codigo = C.Codigo
                      and a.Produto_Codigo = P.Codigo
                      and ((D.Natureza = 1 and D.Situacao = 2) or (D.Natureza = 2 and D.Situacao = 1))
                      and C.Controle_Faturamento = 1
                      and A.Numero_NotaFiscal > 0
                      and A.Estabelecimento_Codigo = :ESTABELECIMENTO2
                      and A.Data_Emissao between  '".$data_inicio."' and '".$data_fim."'
                      AND P.familia_codigo  in (".$dados['familias'].")
                    Group By 1,2,3,4,5

                    ) Z, tbempresa f where f.codigo = Z.id_cliente
                    group by 1,2
                    ) q order by 3 desc";

            $args = array(
                ':ESTABELECIMENTO1'  => $dados['estabelecimento'],
                ':ESTABELECIMENTO2'  => $dados['estabelecimento']
            );

            $ret1 = $con->query($sql, $args);

            $sql = "SELECT
                    
                      CODIGO,
                      RAZAOSOCIAL,
                      UF,
                      QUANTIDADE
                      
                      from( SELECT
                       lpad(r.codigo,6,0) as CODIGO,
                       lpad( r.codigo,6,'0')||' - '||r.razaosocial||' ('||r.UF||')' as razaosocial,
                       f.uf,
                       sum(QUANTIDADE) as QUANTIDADE

                      From
                      (
                      SELECT S.id_cliente,'ECF' TIPO, S.id DOCFISCAL, p.familia_codigo,s.data_venda as data_emissao, SUM(A.Quantidade) AS Quantidade
                      From TBCF_VENDA_CABECALHO S, TbCF_VENDA_DETALHE A, TbProduto P
                      Where S.estabelecimento_id = A.estabelecimento_id
                        and S.caixa_id = A.caixa_id
                        and S.ID = A.Id_ecf_venda_cabecalho
                        and A.id_ecf_produto = P.Codigo
                        and A.Cancelado = 'N'
                        and S.Cupom_cancelado = 'N'
                        and S.estabelecimento_id =  :ESTABELECIMENTO1
                        and S.Data_Venda between  '".$data_inicio."' and '".$data_fim."'
                        AND P.familia_codigo  in (".$dados['familias'].")
                      Group By 1,2,3,4,5

                      UNION

                      SELECT D.empresa_codigo AS id_cliente,'NF' TIPO,D.numero_notafiscal AS DOCFISCAL, p.familia_codigo,d.data_emissao, SUM(A.Quantidade) AS Quantidade
                      From TBNFS D, TbNfs_Item A, TbOperacao C, TbProduto P
                      Where D.CONTROLE  = A.Nfs_controle
                        and A.Operacao_Codigo = C.Codigo
                        and a.Produto_Codigo = P.Codigo
                        and ((D.Natureza = 1 and D.Situacao = 2) or (D.Natureza = 2 and D.Situacao = 1))
                        and C.Controle_Faturamento = 1
                        and A.Numero_NotaFiscal > 0
                        and A.Estabelecimento_Codigo = :ESTABELECIMENTO2
                        and A.Data_Emissao between  '".$data_inicio."' and '".$data_fim."'
                        AND P.familia_codigo  in (".$dados['familias'].")
                      Group By 1,2,3,4,5

                      ) Z, tbcliente f, tbrepresentante r
                          where f.codigo = Z.id_cliente
                          and r.codigo = f.representante_codigo
                      group by 1,2,3
                      ) q order by 4 desc";

            $args = array(
                ':ESTABELECIMENTO1'  => $dados['estabelecimento'],
                ':ESTABELECIMENTO2'  => $dados['estabelecimento']
            );

            $ret2 = $con->query($sql, $args);


            $sql = "SELECT
                    
                      UF,
                      QUANTIDADE
                      
                      from( SELECT
                       f.uf,
                       sum(QUANTIDADE) as QUANTIDADE

                      From
                      (
                      SELECT S.id_cliente,'ECF' TIPO, S.id DOCFISCAL, p.familia_codigo,s.data_venda as data_emissao, SUM(A.Quantidade) AS Quantidade
                      From TBCF_VENDA_CABECALHO S, TbCF_VENDA_DETALHE A, TbProduto P
                      Where S.estabelecimento_id = A.estabelecimento_id
                        and S.caixa_id = A.caixa_id
                        and S.ID = A.Id_ecf_venda_cabecalho
                        and A.id_ecf_produto = P.Codigo
                        and A.Cancelado = 'N'
                        and S.Cupom_cancelado = 'N'
                        and S.estabelecimento_id =  :ESTABELECIMENTO1
                        and S.Data_Venda between  '".$data_inicio."' and '".$data_fim."'
                        AND P.familia_codigo  in (".$dados['familias'].")
                      Group By 1,2,3,4,5

                      UNION

                      SELECT D.empresa_codigo AS id_cliente,'NF' TIPO,D.numero_notafiscal AS DOCFISCAL, p.familia_codigo,d.data_emissao, SUM(A.Quantidade) AS Quantidade
                      From TBNFS D, TbNfs_Item A, TbOperacao C, TbProduto P
                      Where D.CONTROLE  = A.Nfs_controle
                        and A.Operacao_Codigo = C.Codigo
                        and a.Produto_Codigo = P.Codigo
                        and ((D.Natureza = 1 and D.Situacao = 2) or (D.Natureza = 2 and D.Situacao = 1))
                        and C.Controle_Faturamento = 1
                        and A.Numero_NotaFiscal > 0
                        and A.Estabelecimento_Codigo = :ESTABELECIMENTO2
                        and A.Data_Emissao between  '".$data_inicio."' and '".$data_fim."'
                        AND P.familia_codigo in (".$dados['familias'].")
                      Group By 1,2,3,4,5

                      ) Z, tbcliente f, tbrepresentante r
                          where f.codigo = Z.id_cliente
                          and r.codigo = f.representante_codigo
                      group by 1
                      ) q order by 2 desc";

            $args = array(
                ':ESTABELECIMENTO1'  => $dados['estabelecimento'],
                ':ESTABELECIMENTO2'  => $dados['estabelecimento']
            );

            $ret3 = $con->query($sql, $args);

            $con->commit();
      
      return [$ret1,$ret2,$ret3];
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }
    
    /**
     * relatorio
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function relatorio($dados) {

        try{

            $con = new _Conexao();

            $pedidos        = _12050DAO::pedidos($dados,$con);
            $faturamento    = _12050DAO::faturamento($dados,$con);
            $devolucao      = _12050DAO::devolucao($dados,$con);
            $defeito        = _12050DAO::defeito($dados,$con);
            $fat_familias   = _12050DAO::faturamentoPorFamilia($dados,$con);
            $familias       = _12050DAO::familias($dados,$con);

            $producao_todos = [];
            $defeito_todos = [];
            $producao = [];

            foreach ($familias as $familia){
                
                unset($producao);
                
                if(  intval($familia->FAMILIA_CODIGO) === intval(3)){

                  $aa = [
                    'estabelecimento' => $dados['estabelecimento'],
                    'familias'        => 3,
                    'periodo_inicial' => $dados['periodo_inicial'],
                    'periodo_final'   => $dados['periodo_final'],
                    'perfil_grupo'    => $dados['perfil_grupo'],
                    'periodo_pedido'  => $dados['periodo_pedido'],
                  ];

                    $producao   = [
                        'DADO' => _12050DAO::producao($aa,$con),
                        'DESC'  => $familia->DESCRICAO,
                        'CODE'  => $familia->FAMILIA_CODIGO
                        ];

                    $defeito2 = [
                        'DADO'  => [],
                        'DESC'  => $familia->DESCRICAO,
                        'CODE'  => $familia->FAMILIA_CODIGO
                        ];
                }else{
                    $producao   = [
                        'DADO'  => _12050DAO::producaoes2($dados,$familia->FAMILIA_CODIGO,$con),
                        'DESC'  => $familia->DESCRICAO,
                        'CODE'  => $familia->FAMILIA_CODIGO
                        ];

                    $defeito2 = [
                        'DADO' => _12050DAO::defeito2($dados,$familia->FAMILIA_CODIGO,$con),
                        'DESC'  => $familia->DESCRICAO,
                        'CODE'  => $familia->FAMILIA_CODIGO
                        ];
                }
                
                if(isset($producao)){
                    array_push($producao_todos,$producao);
                    array_push($defeito_todos,$defeito2);
                }
                
            }
            
            $retorno = [
              'PEIDOS'      => $pedidos,
              'FATURAMENTO' => $faturamento,
              'DEVOLUCAO'   => $devolucao,
              'DEFEITO'     => $defeito,
              'PRODUCAO'    => $producao_todos,
              'DEFEITOS'    => $defeito_todos,
              'FAMILIAS'    => $familias,
              'FATFAMILIA'  => $fat_familias
            ];

            return $retorno;

        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
        
    }  
	
}