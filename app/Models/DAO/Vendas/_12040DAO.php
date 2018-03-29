<?php

namespace App\Models\DAO\Vendas;

use App\Models\DTO\Vendas\_12040;
use Illuminate\Support\Facades\Auth;

/**
 * DAO do objeto _12040 - Registro de Pedidos
 */
class _12040DAO {

	/**
	 * Verificar se o usuário é um representante.
	 * @param json $param
	 * @param _Conexao $con
	 * @return array
	 */
	public static function verificarUsuarioEhRepresentante($param, $con) {

		$sql = "
			SELECT FIRST 1
			    UR.REPRESENTANTE_CODIGO

			FROM
			    TBUSUARIO_REPRESENTANTE UR

			WHERE
			    UR.USUARIO_CODIGO = :USUARIO_CODIGO
		";

		$args = [
			':USUARIO_CODIGO' => $param->USUARIO_CODIGO
		];

		return $con->query($sql, $args);
	}

    /**
     * Consultar representante do cliente.
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarRepresentanteDoCliente($param, $con) {

        $sql = "
            SELECT FIRST 1
                C.REPRESENTANTE_CODIGO

            FROM
                TBCLIENTE C

            WHERE
                C.CODIGO = :CLIENTE_ID
        ";

        $args = [
            ':CLIENTE_ID' => $param->CLIENTE_ID
        ];

        return $con->query($sql, $args);
    }

	/**
     * Consultar pedidos.
     * @access public
     * @param json $filtro
     * @param _Conexao $con
     * @return array
     */
    public static function consultarPedido($filtro, $con) {
        
        $sql = "SELECT
                      (Select First 1 PU.Mensagem From TbPedido_Uf PU
                             Where PU.Estabelecimento_id = x.Estabelecimento_Codigo
                               and PU.Uf = x.UF) Mensagem,

                    coalesce((select list(numero_notafiscal) from (select distinct
                                            g.numero_notafiscal
                                        from tbpedido_item i, tbnfs_item s, tbnfs g
                                        where i.pedido = x.PEDIDO
                                        and s.pedido = i.pedido
                                        and s.pedido_item_pe_controle = i.controle
                                        and g.controle = s.nfs_controle)),'') as NFS,

                    FN_ST_PEDIDO(PEDIDO) VALOR_ST,

                    iif(DATA_CLIENTE < current_date, 1,0) as ATRASADO,
                    
                    formatdate(DATA_ENTREGA) as PREVFAT,
                    DATA_ENTREGA,
                    TIPO,
                    cnpj,
                    fax,
                    fone,
                    email,
                    ENDERECO,
                    CIDADE,
                    BAIRRO,
                    NUMERO,
                    IE,
                    CEP,
                    UF,
                    valor_frete,
                    ESTABELECIMENTO_CODIGO,
                    REPRESENTANTE_CODIGO,
                    DATA,
                    PRIORIDADE,
                    PEDIDO,
                    DATA_CLIENTE,
                    PEDIDO_CLIENTE,
                    CLIENTE_CODIGO,
                    PERFIL,
                    BLOQUEIA_ATENDIMENTO,
                    AGRUP,
                    TRANSPORTADORA_CODIGO,
                    PAGAMENTO_FORMA,
                    PAGAMENTO_CONDICAO,
                    FAMILIA_CODIGO,
                    OBSERVACAO,
                    USUARIO_CODIGO,
                    SITUACAO,
                    FORMA_ANALISE,
                    CHAVE_LIBERACAO,
                    FRETE,
                    FRETE_DESCRICAO,
                    QUANTIDADE_TOTAL,
                    VALOR_TOTAL,
                    MODELO,
                    CLIENTE,
                    TRATAMENTO_PEDIDO_MP,
                    REPRESENTANTE_DESCRICAO,
                    CLIENTE_RAZAOSOCIAL,
                    EMAIL_XML,
                    TRANSPORTADORA_DESCRICAO,
                    PAGAMENTO_FORMA_DESCRICAO,
                    PAGAMENTO_CONDICAO_DESCRICAO,
                    AGRUPAMENTO,
                    MP,
                    BLOQ,
                    SUM(X.QUANTIDADE_TOTAL - X.SALDO_FATURAR - X.ENCERRADO) as FATURADO,
                    SUM(X.SALDO_FATURAR - (X.ALOCADO + X.EMPRODUCAO + X.ENCERRADO - X.ENCERRADO)) as PRODUZIR,
                    SUM(X.SALDO_FATURAR) SALDO_FATURAR,
                    SUM(X.ALOCADO) ALOCADO,
                    SUM(X.EMPRODUCAO) EMPRODUCAO,
                    SUM(X.ENCERRADO) ENCERRADO

                From (

                    Select

                        /*@FIRST*/

                        Distinct(A.Data_Entrega),
                        iif(a.tipo_pedido = 1, '1 - Venda', iif(a.tipo_pedido = 2, '2 - Amostra',a.tipo_pedido || ' - Pedido')) as Tipo,
                        h.cnpj,
                        h.fax,
                        h.fone,
                        h.email,
                        h.ENDERECO,
                        h.CIDADE,
                        h.BAIRRO,
                        h.NUMERO,
                        h.IE,
                        h.CEP,
                        h.UF,
                        a.valor_frete,
                        a.estabelecimento_codigo,
                        a.representante_codigo,
                        A.Data,
                        A.Prioridade,
                        A.Pedido,
                        A.Data_Cliente,
                        A.Pedido_Cliente,
                        A.Cliente_Codigo,
                        A.Classificacao Perfil,
                        A.Bloqueia_Atendimento,
                        'X' Agrup,
                        a.TRANSPORTADORA_CODIGO,
                        a.PAGAMENTO_FORMA,
                        a.PAGAMENTO_CONDICAO,
                        a.FAMILIA_CODIGO,
                        a.OBSERVACAO,
                        a.USUARIO_CODIGO,
                        TRIM(a.PROGRAMADO) PROGRAMADO,
                        TRIM(a.SITUACAO) SITUACAO,
                        TRIM(a.FORMA_ANALISE) FORMA_ANALISE,
                        COALESCE(a.CHAVE_LIBERACAO, '0') CHAVE_LIBERACAO,
                        IIF(a.FRETE=2, 2, 1) FRETE, IIF(a.Frete=2,'2-FOB','1-CIF') FRETE_DESCRICAO,

                        (SELECT FIRST 1 SUM(I.QUANTIDADE) FROM TBPEDIDO_ITEM I WHERE I.PEDIDO = a.PEDIDO ) QUANTIDADE_TOTAL,
                        (SELECT FIRST 1 SUM(I.QUANTIDADE * I.VALOR)  FROM TBPEDIDO_ITEM I WHERE I.PEDIDO = a.PEDIDO ) VALOR_TOTAL,
                        (SELECT FIRST 1 LIST(DISTINCT  (SELECT FIRST 1 M.DESCRICAO FROM TBMODELO M WHERE M.CODIGO = I.MODELO_CODIGO), ', ') FROM TBPEDIDO_ITEM I WHERE I.PEDIDO = a.PEDIDO) MODELO,
                        (Select First 1 D.NomeFantasia||' ('||D.Uf||')' From TbEmpresa D Where A.Cliente_Codigo = D.Codigo) Cliente,
                        (Select First 1 P.Tratamento_Pedido_MP From TbPerfil P Where P.Tabela = 'PED' and P.Id = A.Classificacao) Tratamento_Pedido_MP,
                        (SELECT FIRST 1 R.RAZAOSOCIAL  FROM TBREPRESENTANTE R WHERE R.CODIGO = a.REPRESENTANTE_CODIGO ) REPRESENTANTE_DESCRICAO,
                        (SELECT FIRST 1 E.RAZAOSOCIAL FROM TBEMPRESA E  WHERE E.CODIGO = a.CLIENTE_CODIGO ) CLIENTE_RAZAOSOCIAL,
                        (SELECT FIRST 1 REPLACE(E.EMAIL_XML, ';', ',') FROM TBEMPRESA E WHERE E.CODIGO = a.CLIENTE_CODIGO ) EMAIL_XML,
                        (SELECT FIRST 1 T.RAZAOSOCIAL FROM TBTRANSPORTADORA T WHERE T.CODIGO = a.TRANSPORTADORA_CODIGO ) TRANSPORTADORA_DESCRICAO,
                        (SELECT FIRST 1 PF.DESCRICAO  FROM TBPAGAMENTO_FORMA PF  WHERE PF.CODIGO = a.PAGAMENTO_FORMA ) PAGAMENTO_FORMA_DESCRICAO,
                        (SELECT FIRST 1 PC.DESCRICAO FROM TBPAGAMENTO_CONDICAO PC WHERE PC.CODIGO = a.PAGAMENTO_CONDICAO ) PAGAMENTO_CONDICAO_DESCRICAO,

                        Cast(Sum(IIF(B.Situacao=1,C.Quantidade,0)) as Numeric(15,4)) Saldo_Faturar,
                        Cast(Sum(B.Qtd_Est) as Numeric(15,4)) Alocado,
                        Cast(Sum(B.Qtd_Rem) as Numeric(15,4)) EMPRODUCAO,
                        Cast(Sum(B.Qtd_Agp) as Numeric(15,4)) AGRUPAMENTO,
                        Cast(Sum(IIF(B.Situacao=3,C.Quantidade,0)) as Numeric(15,4)) ENCERRADO,
                        Max(Coalesce((Select Sum(G.Necessidade) From TbPedido_Consumo G  Where G.Estabelecimento_ID = A.Estabelecimento_Codigo  and G.Pedido_Id = A.Pedido),-1)) MP,
                        Coalesce((Select First 1 IIF(E.Bloqueia_Notafiscal='1','B','') From TbCliente E Where A.Cliente_Codigo = E.Codigo),'') BLOQ

                    From TbPedido A, TbPedido_Item B, TbPedido_Item_Saldo C, tbempresa h
                    
                    Where A.Estabelecimento_Codigo = B.Estabelecimento_Codigo
                        and A.Pedido   = B.Pedido
                        and B.Pedido   = C.Pedido
                        and B.Controle = C.Pedido_Item_Controle
                        and A.Situacao = 1
                        and A.Status = '1' 
                        and h.codigo = a.cliente_codigo
                        /*@CLIENTE*/
                        /*@DATA*/
                        /*@PEDIDO*/
                        /*@PEDIDO_CLIENTE*/
                        /*@REPRESENTANTE*/
                    
                     Group By
                            DATA_ENTREGA,
                            TIPO,
                            cnpj,
                            fax,
                            fone,
                            email,
                            ENDERECO,
                            CIDADE,
                            BAIRRO,
                            NUMERO,
                            IE,
                            CEP,
                            UF,
                            valor_frete,
                            ESTABELECIMENTO_CODIGO,
                            REPRESENTANTE_CODIGO,
                            DATA,
                            PRIORIDADE,
                            PEDIDO,
                            DATA_CLIENTE,
                            PEDIDO_CLIENTE,
                            CLIENTE_CODIGO,
                            PERFIL,
                            BLOQUEIA_ATENDIMENTO,
                            AGRUP,
                            TRANSPORTADORA_CODIGO,
                            PAGAMENTO_FORMA,
                            PAGAMENTO_CONDICAO,
                            FAMILIA_CODIGO,
                            OBSERVACAO,
                            USUARIO_CODIGO,
                            PROGRAMADO,
                            SITUACAO,
                            FORMA_ANALISE,
                            CHAVE_LIBERACAO,
                            FRETE,
                            FRETE_DESCRICAO,
                            QUANTIDADE_TOTAL,
                            VALOR_TOTAL,
                            MODELO,
                            CLIENTE,
                            TRATAMENTO_PEDIDO_MP,
                            REPRESENTANTE_DESCRICAO,
                            CLIENTE_RAZAOSOCIAL,
                            EMAIL_XML,
                            TRANSPORTADORA_DESCRICAO,
                            PAGAMENTO_FORMA_DESCRICAO,
                            PAGAMENTO_CONDICAO_DESCRICAO

                    Order By a.PEDIDO desc, A.Data_Entrega, A.Prioridade, A.Pedido

                ) X
                    
                    /*@FATURADO*/

                    Group By

                        DATA_ENTREGA,
                        VALOR_ST,
                        TIPO,
                        cnpj,
                        fax,
                        fone,
                        email,
                        ENDERECO,
                        CIDADE,
                        BAIRRO,
                        NUMERO,
                        IE,
                        CEP,
                        UF,
                        valor_frete,
                        ESTABELECIMENTO_CODIGO,
                        REPRESENTANTE_CODIGO,
                        DATA,
                        PRIORIDADE,
                        PEDIDO,
                        DATA_CLIENTE,
                        PEDIDO_CLIENTE,
                        CLIENTE_CODIGO,
                        PERFIL,
                        BLOQUEIA_ATENDIMENTO,
                        AGRUP,
                        TRANSPORTADORA_CODIGO,
                        PAGAMENTO_FORMA,
                        PAGAMENTO_CONDICAO,
                        FAMILIA_CODIGO,
                        OBSERVACAO,
                        USUARIO_CODIGO,
                        SITUACAO,
                        FORMA_ANALISE,
                        CHAVE_LIBERACAO,
                        FRETE,
                        FRETE_DESCRICAO,
                        QUANTIDADE_TOTAL,
                        VALOR_TOTAL,
                        MODELO,
                        CLIENTE,
                        TRATAMENTO_PEDIDO_MP,
                        REPRESENTANTE_DESCRICAO,
                        CLIENTE_RAZAOSOCIAL,
                        EMAIL_XML,
                        TRANSPORTADORA_DESCRICAO,
                        PAGAMENTO_FORMA_DESCRICAO,
                        PAGAMENTO_CONDICAO_DESCRICAO,
                        AGRUPAMENTO,
                        MP,
                        BLOQ
        ";

        //IIF(P.Frete=0,'0-CONSIG',IIF(P.Frete=1,'1-CIF',IIF(P.Frete=2,'2-FOB',IIF(P.Frete=3,'3-SEM FRT','')))) FRETE_DESCRICAO,

        // forma inteligente
        // IIF(CAST(:DATA_INI_0 AS DATE) IS NULL, TRUE, P.DATA BETWEEN :DATA_INI AND :DATA_FIM)
        // AND IIF(CAST(:PEDIDO_0 AS INTEGER) IS NULL, TRUE, P.PEDIDO = :PEDIDO)
        // AND P.CLIENTE_CODIGO = :CLIENTE_ID
        
        $data    = '';
        $pedido  = '';
        $cliente = '';
        $representante  = '';
        $pedido_cliente = '';
        $faturado  = '';
        $tipo_data = '';
        $first = 'first 50';

        $param = (object)[];
        $param->USUARIO_CODIGO = Auth::user()->CODIGO;

        $rep = _12040::verificarUsuarioEhRepresentante($param, $con);

        if (count($rep) > 0){
            $representante   = " AND a.representante_codigo = ".$rep[0]->REPRESENTANTE_CODIGO;
        }else{
            if ($filtro->REPRESENTANTE > 0)
            $representante = " AND a.representante_codigo = " . $filtro->REPRESENTANTE;
        }


        if (!empty($filtro->DATA_INI)){

            if($filtro->TIPO_DATA == 1){
                $data = " AND a.DATA BETWEEN '$filtro->DATA_INI' AND '$filtro->DATA_FIM'";
            }else{
                if($filtro->TIPO_DATA == 2){
                    $data = " AND a.data_cliente BETWEEN '$filtro->DATA_INI' AND '$filtro->DATA_FIM'";
                }else{
                    $data = "";
                }
            }

        }

        if (!empty($filtro->PEDIDO))
            $pedido = " AND a.PEDIDO = $filtro->PEDIDO";

        if (!empty($filtro->PEDIDO_CLIENTE))
            $pedido_cliente = " AND a.PEDIDO_CLIENTE = '$filtro->PEDIDO_CLIENTE'";

        if ($filtro->CLIENTE_ID > 0)
            $cliente = " and a.CLIENTE_CODIGO = $filtro->CLIENTE_ID";

        if (in_array('FATURADO', (array) $filtro)){
            if($filtro->FATURADO == true){
                $faturado = " WHERE X.SALDO_FATURAR > 0";
                $first = '';
            }else{
                $first = '';    
            } 
        }else{
           $first = '';    
        }

        $args = [
            '@CLIENTE'        => $cliente,
            '@DATA'           => $data,
            '@PEDIDO'         => $pedido,
            '@PEDIDO_CLIENTE' => $pedido_cliente,
            '@REPRESENTANTE'  => $representante,
            '@FIRST'          => $first,
            '@FATURADO'       => $faturado
        ];

        return $con->query($sql, $args);

    }

    /**
     * Consultar pedidos.
     * @access public
     * @param json $filtro
     * @param _Conexao $con
     * @return array
     */
    public static function consultarPedido2($filtro, $con) {
      set_time_limit(300);

      $pedidos = self::consultarPedido($filtro, $con);

      $dados = [];

      $filtro = (object) ['CHAVE' => 0, 'PEDIDO' =>  0 ,'OBJ' => (object) [ 'FAMILIA_CODIGO' => 0]];

        foreach ($pedidos as $key => $pedido) {
            $filtro->PEDIDO = $pedido->PEDIDO;
            $itens = self::consultarPedidoItem($filtro, $con); 

            foreach ($itens as $key => $item) {

                $item->FATURADO2         = $item->FATURADO;
                $item->SALDO_FATURAR2    = $item->SALDO_FATURAR;
                $item->PRODUZIR2         = $item->PRODUZIR;    
                $item->EMPRODUCAO2       = $item->EMPRODUCAO; 
                $item->ALOCADO2          = $item->ALOCADO;
                $item->ENCERRADO2        = $item->ENCERRADO;
                $item->DATA_CLIENTE2     = $item->DATA_CLIENTE;

                array_push($dados, array_merge((array) $item, (array) $pedido));

            }
        }

      return (object) $dados;

    }

    /**
     * Consultar itens de pedidos.
     * @access public
     * @param json $filtro
     * @param _Conexao $con
     * @return array
     */
    public static function consultarPedidoItem($filtro, $con) {

        $pedido = $filtro->OBJ;

        $flag_sku = " AND P.TABELA = 'SKU'";

        $flag_familia = '';
        if($pedido->FAMILIA_CODIGO > 0){
            $flag_familia = " AND P.FAMILIA_ID = ".$pedido->FAMILIA_CODIGO ;
        }

	    $sql = "
	    	SELECT
                ESTABELECIMENTO_CODIGO,
                PEDIDO,
                SEQUENCIA,
                CLIENTE_CODIGO,
                PRODUTO_CODIGO,
                PRODUTO_DESCRICAO,
                PRODUTO_UM,
                MODELO_CODIGO,
                MODELO_DESCRICAO,
                FAMILIA_CODIGO,
                QUANTIDADE,
                VALOR,
                T01,
                T02,
                T03,
                T04,
                T05,
                T06,
                T07,
                T08,
                T09,
                T10,
                T11,
                T12,
                T13,
                T14,
                T15,
                T16,
                T17,
                T18,
                T19,
                T20,
                TAMANHO,
                TAMANHO_DESCRICAO,
                COR_ID,
                COR_DESCRICAO,
                DATA_CLIENTE,
                PERFIL,
                EST_MIN,
                PERFIL_DESCRICAO,
                COR_SOBENCOMENDA,
                QTD_MIN_SOBENC,
                QTD_MULT_SOBENC,
                QTD_MIN,
                QTD_MULT,
                QTD_MIN_MODELO,
                QTD_MULT_MODELO,
                QTD_MIN_LIBERADA,
                ((QUANTIDADE * VALOR) * (ALIQUOTA_ST / 100)) as VALOR_SBT,
                 (QUANTIDADE * VALOR) as TOTAL_ITEM,
                Saldo_Faturar,
                Alocado,
                EMPRODUCAO,
                AGRUPAMENTO,
                ENCERRADO,
                (QUANTIDADE - SALDO_FATURAR - ENCERRADO) as FATURADO,
                (SALDO_FATURAR - (ALOCADO + EMPRODUCAO + ENCERRADO - ENCERRADO)) as PRODUZIR


            from (SELECT
                            I.ESTABELECIMENTO_CODIGO,
                            I.PEDIDO,
                            I.SEQUENCIA,
                            I.CLIENTE_CODIGO,

                            I.PRODUTO_CODIGO,

                            (SELECT FIRST 1 P.DESCRICAO
                                FROM TBPRODUTO P
                                WHERE P.CODIGO = I.PRODUTO_CODIGO
                            ) PRODUTO_DESCRICAO,

                            (SELECT FIRST 1 P.UNIDADEMEDIDA_SIGLA
                                FROM TBPRODUTO P
                                WHERE P.CODIGO = I.PRODUTO_CODIGO
                            ) PRODUTO_UM,

                            I.MODELO_CODIGO,

                            (SELECT FIRST 1 M.DESCRICAO
                                FROM TBMODELO M
                                WHERE M.CODIGO = I.MODELO_CODIGO
                            ) MODELO_DESCRICAO,

                            (SELECT FIRST 1 M.FAMILIA_CODIGO
                                FROM TBMODELO M
                                WHERE M.CODIGO = I.MODELO_CODIGO
                            ) FAMILIA_CODIGO,

                            I.QUANTIDADE,
                            I.VALOR,
                            I.T01, I.T02, I.T03, I.T04, I.T05, I.T06, I.T07, I.T08, I.T09, I.T10,
                            I.T11, I.T12, I.T13, I.T14, I.T15, I.T16, I.T17, I.T18, I.T19, I.T20,

                            I.TAMANHO,

                            (SELECT FIRST 1 TAM_DESCRICAO
                                FROM SP_TAMANHO_GRADE(
                                    (SELECT FIRST 1 PROD.GRADE_CODIGO FROM TBPRODUTO PROD WHERE PROD.CODIGO = I.PRODUTO_CODIGO),
                                    I.TAMANHO
                                )
                            ) TAMANHO_DESCRICAO,

                            I.COR_ID,
                            
                            (SELECT FIRST 1 C.DESCRICAO
                                FROM TBCOR C
                                WHERE C.CODIGO = I.COR_ID
                            ) COR_DESCRICAO,

                            I.DATA_CLIENTE,
                            I.PERFIL,
                            I.EST_MIN,
                            P.DESCRICAO PERFIL_DESCRICAO,

                            IIF(P.COR_SOBENCOMENDA>0,COALESCE((SELECT '1' FROM TBCOR_COMPOSICAO CC
                                WHERE CC.COR_ID = P.COR_SOBENCOMENDA
                                  AND CC.COR_COMPOSICAO_ID = I.COR_ID),'0'),'0'
                            ) COR_SOBENCOMENDA,

                            P.QTD_MIN_SOBENC,
                            P.QTD_MULT_SOBENC,
                            P.QTD_MIN,
                            P.QTD_MULT,

                            CASE MB.TAMANHO
                                WHEN '1'  THEN MPC.MI01 WHEN '2'  THEN MPC.MI02 WHEN '3'  THEN MPC.MI03 WHEN '4'  THEN MPC.MI04
                                WHEN '5'  THEN MPC.MI05 WHEN '6'  THEN MPC.MI06 WHEN '7'  THEN MPC.MI07 WHEN '8'  THEN MPC.MI08
                                WHEN '9'  THEN MPC.MI09 WHEN '10' THEN MPC.MI10 WHEN '11' THEN MPC.MI11 WHEN '12' THEN MPC.MI12
                                WHEN '13' THEN MPC.MI13 WHEN '14' THEN MPC.MI14 WHEN '15' THEN MPC.MI15 WHEN '16' THEN MPC.MI16
                                WHEN '17' THEN MPC.MI17 WHEN '18' THEN MPC.MI18 WHEN '19' THEN MPC.MI19 WHEN '20' THEN MPC.MI20
                            END QTD_MIN_MODELO,

                            CASE MB.TAMANHO
                                WHEN '1'  THEN MPC.MU01 WHEN '2'  THEN MPC.MU02 WHEN '3'  THEN MPC.MU03 WHEN '4'  THEN MPC.MU04
                                WHEN '5'  THEN MPC.MU05 WHEN '6'  THEN MPC.MU06 WHEN '7'  THEN MPC.MU07 WHEN '8'  THEN MPC.MU08
                                WHEN '9'  THEN MPC.MU09 WHEN '10' THEN MPC.MU10 WHEN '11' THEN MPC.MU11 WHEN '12' THEN MPC.MU12
                                WHEN '13' THEN MPC.MU13 WHEN '14' THEN MPC.MU14 WHEN '15' THEN MPC.MU15 WHEN '16' THEN MPC.MU16
                                WHEN '17' THEN MPC.MU17 WHEN '18' THEN MPC.MU18 WHEN '19' THEN MPC.MU19 WHEN '20' THEN MPC.MU20
                            END QTD_MULT_MODELO,

                            (SELECT FIRST 1 PL.QUANTIDADE 
                                FROM TBPEDIDO_LIBERACAO PL 
                                WHERE PL.COR_ID = I.COR_ID
                                  AND PL.CHAVE = :CHAVE
                            ) QTD_MIN_LIBERADA,

                            IIF( k.UF = e.uf AND k.Substituido_Tributario = '1' and e.Substituto_Tributario = '1',
                                Coalesce((Select First 1 F.Aliquota_St From TbFamilia_Ficha F
                                            Where F.Familia_Codigo = j.Familia_Codigo
                                            and F.Estabelecimento_Codigo = e.codigo),0.00) ,0.000) Aliquota_ST,

                            Cast(IIF(i.Situacao=1,i.Quantidade,0) as Numeric(15,4)) Saldo_Faturar,
                            Cast(i.Qtd_Est as Numeric(15,4)) Alocado,
                            Cast(i.Qtd_Rem as Numeric(15,4)) EMPRODUCAO,
                            Cast(i.Qtd_Agp as Numeric(15,4)) AGRUPAMENTO,
                            Cast(IIF(i.Situacao=3,i.Quantidade,0) as Numeric(15,4)) ENCERRADO

                       FROM
                            TBPEDIDO_ITEM I
                            LEFT JOIN tbpedido o
                                on o.pedido = i.pedido
                            LEFT JOIN TBPERFIL P
                                ON P.ID = I.PERFIL $flag_sku
                            LEFT JOIN TBMODELO_BLOQUEIO MB
                                ON MB.PERFIL = P.ID
                                AND MB.MODELO_ID= I.MODELO_CODIGO
                                AND MB.COR_ID  = I.COR_ID
                                AND MB.TAMANHO = I.TAMANHO
                            LEFT JOIN TBMODELO_PEDIDO_COTA MPC
                                ON MPC.MODELO_ID = MB.MODELO_ID

                            LEFT JOIN tbcliente k
                                ON k.codigo = i.cliente_codigo

                            LEFT JOIN tbproduto j
                                ON j.codigo = i.produto_codigo

                            LEFT JOIN tbestabelecimento e
                                ON e.codigo = i.estabelecimento_codigo

                        WHERE
                            I.PEDIDO = :PEDIDO
                        
                        $flag_familia
                        
            ) y
	    ";

	    $args = [
	    	':PEDIDO' => $filtro->PEDIDO,
            ':CHAVE'  => $filtro->CHAVE
	    ];

	    return $con->query($sql, $args);
    }
	
	/**
     * Consultar informações gerais.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarInfoGeral($param, $con) {

		$sql = "
			SELECT
				C.CODIGO,
				C.RAZAOSOCIAL,
				C.REPRESENTANTE_CODIGO,
				(SELECT FIRST 1 R.RAZAOSOCIAL FROM TBREPRESENTANTE R WHERE R.CODIGO = C.REPRESENTANTE_CODIGO) REPRESENTANTE_DESCRICAO,
				(SELECT FIRST 1 REPLACE(E.EMAIL_XML, ';', ',') FROM TBEMPRESA E WHERE E.CODIGO = C.CODIGO) EMAIL_XML,
				C.TRANSPORTADORA_CODIGO,
				(SELECT FIRST 1 T.RAZAOSOCIAL FROM TBTRANSPORTADORA T WHERE T.CODIGO = C.TRANSPORTADORA_CODIGO) TRANSPORTADORA_DESCRICAO,
				IIF(C.FRETE=2, 2, 1) FRETE,
				IIF(C.Frete=2,'2-FOB','1-CIF') FRETE_DESCRICAO,
				C.PAGAMENTO_FORMA,
				(SELECT FIRST 1 PF.DESCRICAO FROM TBPAGAMENTO_FORMA PF WHERE PF.CODIGO = C.PAGAMENTO_FORMA) PAGAMENTO_FORMA_DESCRICAO,
				C.PAGAMENTO_CONDICAO,
				(SELECT FIRST 1 PC.DESCRICAO FROM TBPAGAMENTO_CONDICAO PC WHERE PC.CODIGO = C.PAGAMENTO_CONDICAO) PAGAMENTO_CONDICAO_DESCRICAO,
                C.PRIORIDADE,
                GEN_ID(GTBPEDIDO_LIBERACAO_CHAVE, 1) CHAVE
			FROM
				TBCLIENTE C
			WHERE
				C.CODIGO = :CODIGO
		";

        //IIF(C.Frete=0,'0-CONSIG',IIF(C.Frete=1,'1-CIF',IIF(C.Frete=2,'2-FOB',IIF(C.Frete=3,'3-SEM FRT','')))) FRETE_DESCRICAO,

		$args = array(
			':CODIGO' => $param->CLIENTE_ID
		);

		return $con->query($sql, $args);
    }

    /**
     * Consultar tamanho com preço.
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarTamanhoComPreco($param, $con) {

    	$sql = "
    		SELECT
			    A.TAMANHO,
			    A.TAMANHO_DESCRICAO,
			    A.TAMANHO_PRECO,
			    TRIM(MB.BLQ_PED) BLQ_PED,

			    TRIM(COALESCE(
                    (SELECT FIRST 1 IIF(IIF(MB.TAMANHO= 1,E.T01, IIF(MB.TAMANHO= 2,E.T02,
                          IIF(MB.TAMANHO= 3,E.T03, IIF(MB.TAMANHO= 4,E.T04,
                          IIF(MB.TAMANHO= 5,E.T05, IIF(MB.TAMANHO= 6,E.T06,
                          IIF(MB.TAMANHO= 7,E.T07, IIF(MB.TAMANHO= 8,E.T08,
                          IIF(MB.TAMANHO= 9,E.T09, IIF(MB.TAMANHO=10,E.T10,
                          IIF(MB.TAMANHO=11,E.T11, IIF(MB.TAMANHO=12,E.T12,
                          IIF(MB.TAMANHO=13,E.T13, IIF(MB.TAMANHO=14,E.T14,
                          IIF(MB.TAMANHO=15,E.T15, IIF(MB.TAMANHO=16,E.T16,
                          IIF(MB.TAMANHO=17,E.T17, IIF(MB.TAMANHO=18,E.T18,
                          IIF(MB.TAMANHO=19,E.T19, IIF(MB.TAMANHO=20,E.T20,
                          0))))))))))))))))))))>0,'1','0')  FROM TBESTOQUE_MINIMO E
                    WHERE E.ESTABELECIMENTO_CODIGO = :ESTABELECIMENTO_ID_1
                        AND E.PRODUTO_CODIGO = :PRODUTO_ID_1
                        AND E.LOCALIZACAO_CODIGO = COALESCE((SELECT FIRST 1 PF.LOCALIZACAO_ID FROM TBPERFIL PF WHERE PF.ID = ' ' AND PF.TABELA = 'PED'),2))
                    , '0'
                )) EST_MIN,

                CASE MB.TAMANHO
                    WHEN '1'  THEN MPC.MI01 WHEN '2'  THEN MPC.MI02 WHEN '3'  THEN MPC.MI03 WHEN '4'  THEN MPC.MI04
                    WHEN '5'  THEN MPC.MI05 WHEN '6'  THEN MPC.MI06 WHEN '7'  THEN MPC.MI07 WHEN '8'  THEN MPC.MI08
                    WHEN '9'  THEN MPC.MI09 WHEN '10' THEN MPC.MI10 WHEN '11' THEN MPC.MI11 WHEN '12' THEN MPC.MI12
                    WHEN '13' THEN MPC.MI13 WHEN '14' THEN MPC.MI14 WHEN '15' THEN MPC.MI15 WHEN '16' THEN MPC.MI16
                    WHEN '17' THEN MPC.MI17 WHEN '18' THEN MPC.MI18 WHEN '19' THEN MPC.MI19 WHEN '20' THEN MPC.MI20
                END QTD_MIN_MODELO,

                CASE MB.TAMANHO
                    WHEN '1'  THEN MPC.MU01 WHEN '2'  THEN MPC.MU02 WHEN '3'  THEN MPC.MU03 WHEN '4'  THEN MPC.MU04
                    WHEN '5'  THEN MPC.MU05 WHEN '6'  THEN MPC.MU06 WHEN '7'  THEN MPC.MU07 WHEN '8'  THEN MPC.MU08
                    WHEN '9'  THEN MPC.MU09 WHEN '10' THEN MPC.MU10 WHEN '11' THEN MPC.MU11 WHEN '12' THEN MPC.MU12
                    WHEN '13' THEN MPC.MU13 WHEN '14' THEN MPC.MU14 WHEN '15' THEN MPC.MU15 WHEN '16' THEN MPC.MU16
                    WHEN '17' THEN MPC.MU17 WHEN '18' THEN MPC.MU18 WHEN '19' THEN MPC.MU19 WHEN '20' THEN MPC.MU20
                END QTD_MULT_MODELO

            FROM
                SPC_TAMANHO_PRECO_ETAPA_02(:CLIENTE_ID, :ESTABELECIMENTO_ID, :PRODUTO_ID, :MODELO_ID, :GRADE_ID) A
                INNER JOIN TBMODELO_BLOQUEIO MB
                    ON  MB.MODELO_ID = :MODELO_ID_1
                    AND MB.COR_ID    = :COR_ID
                    AND MB.TAMANHO   = A.TAMANHO
                LEFT JOIN TBMODELO_PEDIDO_COTA MPC
                    ON MPC.MODELO_ID = MB.MODELO_ID
    	";

   		$args = [
    		':CLIENTE_ID' 			=> $param->CLIENTE_ID,
    		':ESTABELECIMENTO_ID'	=> $param->ESTABELECIMENTO_ID,
    		':ESTABELECIMENTO_ID_1'	=> $param->ESTABELECIMENTO_ID,
    		':PRODUTO_ID'			=> $param->PRODUTO_ID,
    		':PRODUTO_ID_1'			=> $param->PRODUTO_ID,
    		':MODELO_ID'			=> $param->MODELO_ID,
    		':MODELO_ID_1'			=> $param->MODELO_ID,
    		':GRADE_ID'				=> $param->GRADE_ID,
    		':COR_ID'				=> $param->COR_ID
    	];

    	return $con->query($sql, $args);

    }

    /**
     * Consultar informações de perfil (quantidades e prazos) e 
     * quantidade mínima e múltipla do modelo de acordo com o tamanho.
     * @param array $filtro
     * @param _Conexao $con
     */
    public static function consultarQtdEPrazoPorTamanho($filtro, $con) {

    	$sql = "
    		Select A.Blq_Ped, A.Perfil,
			    P.ID, P.Descricao, P.Descricao_Completa, P.Qtd_Min, P.Qtd_Mult, P.Qtd_Tipo,

			    Coalesce((Select First 1 C.Perfil From TbCor C
			        Where C.Codigo = /*@Cor_1*/),'') Perfil_Cor,

			    IIF(P.Cor_Diferenciada>0,Coalesce((Select '1' From TbCor_Composicao CC
			        Where CC.Cor_Id = P.Cor_Diferenciada
			          and CC.Cor_Composicao_Id = /*@Cor_1*/),'0'),'0') Cor_Diferenciada,

			    IIF(P.Cor_SobEncomenda>0,Coalesce((Select '1' From TbCor_Composicao CC
			        Where CC.Cor_Id = P.Cor_SobEncomenda
			          and CC.Cor_Composicao_Id = /*@Cor_1*/),'0'),'0') Cor_SobEncomenda,

			    P.Qtd_Min_SobEnc, P.Qtd_Mult_SobEnc,
			    
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=1,P.Pzo1_Ped_D1,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=2,P.Pzo1_Ped_D2,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=3,P.Pzo1_Ped_D3,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=4,P.Pzo1_Ped_D4,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=5,P.Pzo1_Ped_D5,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=6,P.Pzo1_Ped_D6,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=7,P.Pzo1_Ped_D7,0))))))) PZO_Prod_Cor_Normal,
			    
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=1,P.Pzo2_Ped_D1,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=2,P.Pzo2_Ped_D2,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=3,P.Pzo2_Ped_D3,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=4,P.Pzo2_Ped_D4,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=5,P.Pzo2_Ped_D5,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=6,P.Pzo2_Ped_D6,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=7,P.Pzo2_Ped_D7,0))))))) PZO_Cli_Cor_Normal,
			    
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=1,P.PzoD1_Ped_D1,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=2,P.PzoD1_Ped_D2,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=3,P.PzoD1_Ped_D3,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=4,P.PzoD1_Ped_D4,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=5,P.PzoD1_Ped_D5,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=6,P.PzoD1_Ped_D6,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=7,P.PzoD1_Ped_D7,0))))))) PZO_Prod_Cor_Dif,
			    
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=1,P.PzoD2_Ped_D1,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=2,P.PzoD2_Ped_D2,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=3,P.PzoD2_Ped_D3,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=4,P.PzoD2_Ped_D4,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=5,P.PzoD2_Ped_D5,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=6,P.PzoD2_Ped_D6,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=7,P.PzoD2_Ped_D7,0))))))) PZO_Cli_Cor_Dif,
			    
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=1,P.PzoS1_Ped_D1,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=2,P.PzoS1_Ped_D2,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=3,P.PzoS1_Ped_D3,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=4,P.PzoS1_Ped_D4,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=5,P.PzoS1_Ped_D5,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=6,P.PzoS1_Ped_D6,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=7,P.PzoS1_Ped_D7,0))))))) PZO_Prod_Cor_SobEnc,
			    
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=1,P.PzoS2_Ped_D1,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=2,P.PzoS2_Ped_D2,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=3,P.PzoS2_Ped_D3,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=4,P.PzoS2_Ped_D4,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=5,P.PzoS2_Ped_D5,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=6,P.PzoS2_Ped_D6,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=7,P.PzoS2_Ped_D7,0))))))) PZO_Cli_Cor_SobEnc,
			    
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=1,P.PzoE1_Ped_D1,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=2,P.PzoE1_Ped_D2,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=3,P.PzoE1_Ped_D3,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=4,P.PzoE1_Ped_D4,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=5,P.PzoE1_Ped_D5,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=6,P.PzoE1_Ped_D6,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=7,P.PzoE1_Ped_D7,0))))))) PZO_Prod_Cor_EstMin,
			    
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=1,P.PzoE2_Ped_D1,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=2,P.PzoE2_Ped_D2,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=3,P.PzoE2_Ped_D3,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=4,P.PzoE2_Ped_D4,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=5,P.PzoE2_Ped_D5,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=6,P.PzoE2_Ped_D6,
			    IIF(Extract(WeekDay from Cast(/*@Data_1*/ as Date))+1=7,P.PzoE2_Ped_D7,0))))))) PZO_Cli_Cor_EstMin,

			    Coalesce((Select First 1 IIF(IIF(A.Tamanho= 1,E.T01, IIF(A.Tamanho= 2,E.T02,
			                      IIF(A.Tamanho= 3,E.T03, IIF(A.Tamanho= 4,E.T04,
			                      IIF(A.Tamanho= 5,E.T05, IIF(A.Tamanho= 6,E.T06,
			                      IIF(A.Tamanho= 7,E.T07, IIF(A.Tamanho= 8,E.T08,
			                      IIF(A.Tamanho= 9,E.T09, IIF(A.Tamanho=10,E.T10,
			                      IIF(A.Tamanho=11,E.T11, IIF(A.Tamanho=12,E.T12,
			                      IIF(A.Tamanho=13,E.T13, IIF(A.Tamanho=14,E.T14,
			                      IIF(A.Tamanho=15,E.T15, IIF(A.Tamanho=16,E.T16,
			                      IIF(A.Tamanho=17,E.T17, IIF(A.Tamanho=18,E.T18,
			                      IIF(A.Tamanho=19,E.T19, IIF(A.Tamanho=20,E.T20,
			                      0))))))))))))))))))))>0,'1','0')  From TbEstoque_Minimo E
			    Where E.Estabelecimento_Codigo = :Estabelecimento_1
			         and E.Produto_codigo = :Produto_1 
			         and E.Localizacao_Codigo = Coalesce((Select First 1 PF.Localizacao_Id From TbPerfil PF Where PF.Id = ' ' and PF.Tabela = 'PED'),2)),'0') Est_Min,

			    Coalesce((Select First 1 Coalesce(E.Prefixo||E.Codigo,'')
			                From TbEAN E
			               Where E.Modelo_id = A.Modelo_Id
			                 and E.Cor_id = A.Cor_Id
			                 and E.Tamanho = A.Tamanho),'') EAN,
			    
			    Coalesce((SELECT First 1 F.ID
			                From TbFerramentaria F, TbFerramentaria_Item FI, TbModelo M
			               Where F.Id = FI.Ferramentaria_Id
			                 and M.Linha_Codigo = FI.Linha_Id
			                 and M.Codigo = A.Modelo_Id
			                 and FI.TAMANHO = /*@Tamanho_1*/
			                 and F.Status = '1'),0) Ferramenta_Id,

			    CASE A.TAMANHO
			        WHEN '1'  THEN MPC.MI01 WHEN '2'  THEN MPC.MI02 WHEN '3'  THEN MPC.MI03 WHEN '4'  THEN MPC.MI04
			        WHEN '5'  THEN MPC.MI05 WHEN '6'  THEN MPC.MI06 WHEN '7'  THEN MPC.MI07 WHEN '8'  THEN MPC.MI08
			        WHEN '9'  THEN MPC.MI09 WHEN '10' THEN MPC.MI10 WHEN '11' THEN MPC.MI11 WHEN '12' THEN MPC.MI12
			        WHEN '13' THEN MPC.MI13 WHEN '14' THEN MPC.MI14 WHEN '15' THEN MPC.MI15 WHEN '16' THEN MPC.MI16
			        WHEN '17' THEN MPC.MI17 WHEN '18' THEN MPC.MI18 WHEN '19' THEN MPC.MI19 WHEN '20' THEN MPC.MI20
			    END QTD_MIN_MODELO,

			    CASE A.TAMANHO
			        WHEN '1'  THEN MPC.MU01 WHEN '2'  THEN MPC.MU02 WHEN '3'  THEN MPC.MU03 WHEN '4'  THEN MPC.MU04
			        WHEN '5'  THEN MPC.MU05 WHEN '6'  THEN MPC.MU06 WHEN '7'  THEN MPC.MU07 WHEN '8'  THEN MPC.MU08
			        WHEN '9'  THEN MPC.MU09 WHEN '10' THEN MPC.MU10 WHEN '11' THEN MPC.MU11 WHEN '12' THEN MPC.MU12
			        WHEN '13' THEN MPC.MU13 WHEN '14' THEN MPC.MU14 WHEN '15' THEN MPC.MU15 WHEN '16' THEN MPC.MU16
			        WHEN '17' THEN MPC.MU17 WHEN '18' THEN MPC.MU18 WHEN '19' THEN MPC.MU19 WHEN '20' THEN MPC.MU20
			    END QTD_MULT_MODELO,

                (SELECT FIRST 1 PL.QUANTIDADE 
                    FROM TBPEDIDO_LIBERACAO PL 
                    WHERE PL.COR_ID = A.COR_ID
                      AND PL.CHAVE = :CHAVE
                ) QTD_MIN_LIBERADA

			From TbModelo_Bloqueio A, TbPerfil P, TBMODELO_PEDIDO_COTA MPC
			Where A.Modelo_Id=:Modelo_1
			  and A.Cor_Id  = /*@Cor_1*/
			  and A.Tamanho = /*@Tamanho_1*/
			  and A.Perfil = P.Id
			  and P.Tabela = 'SKU'
			  and P.Familia_Id = :Familia_1
			  AND MPC.MODELO_ID = A.MODELO_ID
    	";

    	$args = [
    		'@Cor_1'				=> $filtro['COR_ID'],
    		'@Data_1'				=> "'".$filtro['DATA']."'",
    		':Estabelecimento_1'	=> $filtro['ESTABELECIMENTO_ID'],
    		':Produto_1'			=> $filtro['PRODUTO_ID'],
    		'@Tamanho_1'			=> $filtro['TAMANHO_ID'],
    		':Modelo_1'				=> $filtro['MODELO_ID'],
    		':Familia_1'			=> $filtro['FAMILIA_ID'],
            ':CHAVE'                => $filtro['CHAVE']
    	];

    	return $con->query($sql, $args);

    }

    /**
     * Consultar a quantidade mínima liberada para uma cor.
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarQtdLiberada($param, $con) {

        $sql = "
            SELECT FIRST 1
                PL.QUANTIDADE

            FROM 
                TBPEDIDO_LIBERACAO PL

            WHERE 
                PL.CHAVE = :CHAVE
            AND PL.COR_ID = :COR_ID
        ";

        $args = [
            ':CHAVE' => $param->CHAVE,
            ':COR_ID'=> $param->COR_ID
        ];

        return $con->query($sql, $args);
    }

    /**
     * Consultar se o número do pedido do cliente já existe.
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarNumPedidoCliente($param, $con) {

        $sql = "
            SELECT
                P.PEDIDO_CLIENTE
                
            FROM
                TBPEDIDO P
                
            WHERE
                P.PEDIDO_CLIENTE = :PEDIDO_CLIENTE
            AND P.CLIENTE_CODIGO = :CLIENTE_CODIGO
        ";

        $args = [
            ':PEDIDO_CLIENTE' => $param->PEDIDO_CLIENTE,
            ':CLIENTE_CODIGO' => $param->CLIENTE_ID
        ];

        return $con->query($sql, $args);
    }

    /**
     * Gerar id do objeto.
     * @param _Conexao $con
     * @return integer
     */
    public static function gerarId($con) {

        $sql = 'SELECT GEN_ID(GTBPEDIDO, 1) ID FROM RDB$DATABASE';

        return $con->query($sql)[0]->ID;

    }

    public static function gravarPedido($dados, $con) {

    	$sql = "
    		UPDATE OR INSERT INTO TBPEDIDO (
    			PEDIDO,
			    ESTABELECIMENTO_CODIGO,
			    PEDIDO_CLIENTE,
			    REPRESENTANTE_CODIGO,
			    CLIENTE_CODIGO,
			    TRANSPORTADORA_CODIGO,
			    FRETE,
			    PAGAMENTO_FORMA,
			    PAGAMENTO_CONDICAO,
			    FAMILIA_CODIGO,
			    OBSERVACAO,
			    USUARIO_CODIGO,
			    DATA_CLIENTE,
                PRIORIDADE,
			    PROGRAMADO,
			    SITUACAO,
                FORMA_ANALISE,
                CHAVE_LIBERACAO
			)
			VALUES (
				:PEDIDO,
			    :ESTABELECIMENTO_CODIGO,
			    :PEDIDO_CLIENTE,
			    :REPRESENTANTE_CODIGO,
			    :CLIENTE_CODIGO,
			    :TRANSPORTADORA_CODIGO,
			    :FRETE,
			    :PAGAMENTO_FORMA,
			    :PAGAMENTO_CONDICAO,
			    :FAMILIA_CODIGO,
			    :OBSERVACAO,
			    :USUARIO_CODIGO,
			    :DATA_CLIENTE,
                :PRIORIDADE,
			    :PROGRAMADO,
			    :SITUACAO,
                :FORMA_ANALISE,
                :CHAVE_LIBERACAO
			)
			MATCHING (PEDIDO)
    	";

    	$args = [
    		':PEDIDO'					=> $dados->PEDIDO,
    		':ESTABELECIMENTO_CODIGO'	=> $dados->ESTABELECIMENTO_CODIGO,
			':PEDIDO_CLIENTE'			=> $dados->PEDIDO_CLIENTE,
			':REPRESENTANTE_CODIGO'		=> $dados->REPRESENTANTE_CODIGO,
			':CLIENTE_CODIGO'			=> $dados->CLIENTE_ID,
			':TRANSPORTADORA_CODIGO'	=> $dados->TRANSPORTADORA_CODIGO,
			':FRETE'					=> $dados->FRETE,
			':PAGAMENTO_FORMA'			=> $dados->PAGAMENTO_FORMA,
			':PAGAMENTO_CONDICAO'		=> $dados->PAGAMENTO_CONDICAO,
			':FAMILIA_CODIGO'			=> $dados->FAMILIA_CODIGO,
			':OBSERVACAO'				=> $dados->OBSERVACAO,
			':USUARIO_CODIGO'			=> $dados->USUARIO_CODIGO,
			':DATA_CLIENTE'				=> $dados->DATA_CLIENTE,
            ':PRIORIDADE'               => $dados->PRIORIDADE,
			':PROGRAMADO'				=> $dados->PROGRAMADO,
			':SITUACAO'					=> $dados->SITUACAO,
            ':FORMA_ANALISE'            => $dados->FORMA_ANALISE,
            ':CHAVE_LIBERACAO'          => $dados->CHAVE_LIBERACAO
    	];

    	$con->execute($sql, $args);
    }

    public static function alterarEmpresaEmailXml($dados, $con) {

    	$sql = "
    		UPDATE TBEMPRESA E
			SET E.EMAIL_XML = :EMAIL_XML
			WHERE E.CODIGO = :EMPRESA_ID
    	";

    	$args = [
    		':EMAIL_XML'	=> $dados->EMAIL_XML,
    		':EMPRESA_ID'	=> $dados->CLIENTE_ID
    	];

    	$con->execute($sql, $args);

    }

    public static function gravarPedidoItem($pedido, $pedidoItem, $con) {

    	$sql = "
    		UPDATE OR INSERT INTO TBPEDIDO_ITEM (
			    ESTABELECIMENTO_CODIGO,
			    PEDIDO,
			    SEQUENCIA,
			    CONTROLE,
			    CLIENTE_CODIGO,
			    PRODUTO_CODIGO,
			    MODELO_CODIGO,
			    QUANTIDADE,
			    VALOR,
			    T01, T02, T03, T04, T05, T06, T07, T08, T09, T10,
				T11, T12, T13, T14, T15, T16, T17, T18, T19, T20,
                VR_TAB,
			    TAMANHO,
                COR_ID,
			    COR_COND,
                PERFIL_COR,
			    DATA_CLIENTE,
			    PERFIL,
			    EST_MIN
			)
			VALUES (
			    :ESTABELECIMENTO_CODIGO,
			    :PEDIDO,
			    :SEQUENCIA,
			    :CONTROLE,
			    :CLIENTE_CODIGO,
			    :PRODUTO_CODIGO,
			    :MODELO_CODIGO,
			    :QUANTIDADE,
			    :VALOR,
			    :T01, :T02, :T03, :T04, :T05, :T06, :T07, :T08, :T09, :T10,
				:T11, :T12, :T13, :T14, :T15, :T16, :T17, :T18, :T19, :T20,
                :VR_TAB,
			    :TAMANHO,
                :COR_ID,
			    :COR_COND,
                :PERFIL_COR,
			    :DATA_CLIENTE,
			    :PERFIL,
			    :EST_MIN
			)
			MATCHING (PEDIDO, SEQUENCIA)
    	";

    	$args = [
    		':ESTABELECIMENTO_CODIGO'	=> $pedido->ESTABELECIMENTO_CODIGO,
		    ':PEDIDO'					=> $pedido->PEDIDO,
		    ':SEQUENCIA'				=> $pedidoItem->SEQUENCIA,
		    ':CONTROLE'					=> $pedidoItem->CONTROLE,
		    ':CLIENTE_CODIGO'			=> $pedido->CLIENTE_ID,
		    ':PRODUTO_CODIGO'			=> $pedidoItem->PRODUTO_ID,
		    ':MODELO_CODIGO'			=> $pedidoItem->MODELO_ID,
		    ':QUANTIDADE'				=> $pedidoItem->QUANTIDADE,
		    ':VALOR'					=> $pedidoItem->VALOR_UNITARIO,
		    ':T01'						=> $pedidoItem->T01,
		    ':T02'						=> $pedidoItem->T02,
		    ':T03'						=> $pedidoItem->T03,
		    ':T04'						=> $pedidoItem->T04,
		    ':T05'						=> $pedidoItem->T05,
		    ':T06'						=> $pedidoItem->T06,
		    ':T07'						=> $pedidoItem->T07,
		    ':T08'						=> $pedidoItem->T08,
		    ':T09'						=> $pedidoItem->T09,
		    ':T10'						=> $pedidoItem->T10,
		    ':T11'						=> $pedidoItem->T11,
		    ':T12'						=> $pedidoItem->T12,
		    ':T13'						=> $pedidoItem->T13,
		    ':T14'						=> $pedidoItem->T14,
		    ':T15'						=> $pedidoItem->T15,
		    ':T16'						=> $pedidoItem->T16,
		    ':T17'						=> $pedidoItem->T17,
		    ':T18'						=> $pedidoItem->T18,
		    ':T19'						=> $pedidoItem->T19,
		    ':T20'						=> $pedidoItem->T20,
            ':VR_TAB'                   => $pedidoItem->VALOR_UNITARIO,
		    ':TAMANHO'					=> $pedidoItem->TAMANHO,
            ':COR_ID'                   => $pedidoItem->COR_ID,
            ':COR_COND'                 => $pedidoItem->COR_CONDICAO,
		    ':PERFIL_COR'				=> $pedidoItem->PERFIL_COR,
		    ':DATA_CLIENTE'				=> $pedidoItem->DATA_CLIENTE,
		    ':PERFIL'					=> $pedidoItem->PERFIL,
		    ':EST_MIN' 					=> $pedidoItem->EST_MIN
    	];

    	$con->execute($sql, $args);

    }

    public static function excluirPedido($pedido, $con) {

    	$sql = "
    		DELETE FROM TBPEDIDO 
    		WHERE PEDIDO = :PEDIDO
    	";

    	$args = [
    		':PEDIDO' => $pedido
    	];

    	$con->execute($sql, $args);

    }

    public static function excluirPedidoItem($pedidoItemExcluir, $con) {

    	$sql = "
    		DELETE FROM TBPEDIDO_ITEM 
    		WHERE PEDIDO = :PEDIDO
    		AND SEQUENCIA = :SEQUENCIA
    	";

    	$args = [
    		':PEDIDO'	 => $pedidoItemExcluir->PEDIDO,
    		':SEQUENCIA' => $pedidoItemExcluir->SEQUENCIA
    	];

    	$con->execute($sql, $args);
    	
    }

    /**
     * Gerar chave para liberação de nova quantidade mínima para cor.
     * @access public
     * @param _Conexao $con
     * @return array
     */
    public static function gerarChave($con) {

        $sql = '
            SELECT GEN_ID(GTBPEDIDO_LIBERACAO_CHAVE, 1) CHAVE FROM RDB$DATABASE
        ';

        return $con->query($sql);
    }

    /**
     * Gravar liberação de nova quantidade mínima para cor.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function gravarLiberacao($param, $con) {

        $sql = '
            INSERT INTO TBPEDIDO_LIBERACAO (
                CHAVE, 
                COR_ID, 
                QUANTIDADE, 
                USUARIO_ID
            ) 
            VALUES (
                :CHAVE, 
                :COR_ID, 
                :QUANTIDADE,
                :USUARIO_ID
            )
        ';

        $args = [
            ':CHAVE'        => $param->CHAVE,
            ':COR_ID'       => $param->COR_ID,
            ':QUANTIDADE'   => $param->QUANTIDADE,
            ':USUARIO_ID'   => $param->USUARIO_ID
        ];

        $con->execute($sql, $args);
    }
	
}