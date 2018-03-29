<?php

namespace App\Models\DAO\Vendas;

use App\Models\Conexao\_Conexao;
use App\Models\DTO\Vendas\_12040;
use Illuminate\Support\Facades\Auth;

/**
 * DAO do objeto _12100 - NOTAS FISCAIS
 */
class _12100DAO {

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
     * Consultar representantes.
     *
     * @return array
     */
    public static function consultarRepresentante($filtro, $con) {
        
       $user = \Auth::user()->CODIGO;

        $representante = $filtro['PARAN']['REPRESENTANTE_CODIGO'];

        if($representante > 0){
            $representante = ' and r.codigo =' .$representante;
        }else{
            $representante = '';    
        }

       $sql = "
            SELECT
                fn_lpad(R.CODIGO,5,'0') as ID,
                R.RAZAOSOCIAL as DESCRICAO,
                R.UF
            FROM
                TBREPRESENTANTE R
            WHERE R.STATUS = '1'
            and (R.codigo = coalesce((select j.representante_codigo from tbusuario_representante j where j.usuario_codigo = ".$user."),0)
                    or
                 coalesce((select j.representante_codigo from tbusuario_representante j where j.usuario_codigo = ".$user."),0) = 0 )
            and r.razaosocial||' '||r.uf||' '||r.codigo like upper('%".$filtro['FILTRO']."%')
            ".$representante."
        ";



        return $con->query($sql);
    }

    /**
     * Consultar clientes.
     *
     * @return array
     */
    public static function consultarClientePorRepresentante($filtro, $con) {
        
        $cliente = $filtro['PARAN']['CLIENTE_CODIGO'];

        if($cliente > 0){
            $cliente = ' and e.codigo =' .$cliente;
        }else{
            $cliente = '';    
        }

        $sql = "
            SELECT FIRST 40
                fn_lpad(E.CODIGO,5,'0') as ID,
                E.RAZAOSOCIAL as DESCRICAO,
                E.NOMEFANTASIA,
                E.UF,
                C.STATUS

            FROM
                TBEMPRESA E
                INNER JOIN TBCLIENTE C
                    ON C.CODIGO = E.CODIGO
                INNER JOIN TBREPRESENTANTE R
                    ON R.CODIGO = C.REPRESENTANTE_CODIGO

            WHERE R.CODIGO = :REPRESENTANTE_CODIGO
            and E.CODIGO||' '||
                E.RAZAOSOCIAL||' '||
                E.NOMEFANTASIA||' '||
                E.UF like upper('%".$filtro['FILTRO']."%')
                ".$cliente."

                order by E.NOMEFANTASIA
        ";

        $args = [
            ':REPRESENTANTE_CODIGO' => $filtro['OPTIONS']['dados']['ID']
        ];

        return $con->query($sql, $args);
    }

    /**
     * Consultar arquivos.
     * @param array $tarefa
     */
    public static function getArquivo($nota) {

        $conFile = new _Conexao('FILES');

        try {

            if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
                $caminho = env('APP_TEMP', '').'/files/';
            } else {
                $caminho = '/var/www/html/GCWEB/public/assets/temp/files/';
            }

            $dir = '/assets/temp/files/';

            $arquivoRet = [];

            $sql = "
                    SELECT
                        A.ID, 
                        lower(A.ARQUIVO) as ARQUIVO,
                        A.CONTEUDO,
                        A.TAMANHO,
                        lower(replace(A.EXTENSAO,'.','')) as EXTENSAO
                    FROM 
                        TBARQUIVO A
                        INNER JOIN TBVINCULO V ON V.ARQUIVO_ID = A.ID
                    WHERE 
                        V.TABELA = 'TBNFS'
                    AND V.TABELA_ID = :TABELA_ID
                ";

                $args = [
                    ':TABELA_ID' => $nota['ID']
                ];

            $arquivos = $conFile->query($sql, $args);

            if (!empty($arquivos)) {

                try {
                    
                    foreach ($arquivos as $key => $a) {
                      
                        $novoNome = $a->ARQUIVO;

                        // Para o JSON (angular). Não pode retornar o CONTEUDO (blob).
                        $arquivoRet['ID']       = $a->ID;
                        $arquivoRet['NOME']     = $novoNome;
                        $arquivoRet['TIPO']     = $a->EXTENSAO;
                        $arquivoRet['BINARIO']  = $dir . $novoNome;
                        $arquivoRet['DIR']      = $dir;
                        $arquivoRet['CAMINHO']  = $caminho;
                        
                        $novoNome = $caminho . $novoNome;

                        if(file_exists($novoNome)){
                            unlink($novoNome);
                        }

                        $novoArquivo = fopen($novoNome, "a");
                        fwrite($novoArquivo, $a->CONTEUDO);
                        fclose($novoArquivo);                            
                        
                    }

                } catch (Exception $e) {
                    log_info('2 - Erro ao gerar arquivos XML, CONTROLE:'.$nota->CONTROLE);    
                }
            }

            $conFile->commit();

            return $arquivoRet;

        } catch (Exception $e) {
            $conFile->rollback();
            throw $e;
        }
    }

    /**
     * Consultar arquivos.
     * @param array $tarefa
     */
    public static function DadosEtiqueta($nota,$con) {

        $sql = "SELECT
                    x.ID,
                    X.NUMERO_NOTAFISCAL,
                    X.DATA_EMISSAO,
                    X.PEDIDO,
                    X.PEDIDO_CLIENTE,
                    X.PRODUTO_ID,
                    X.MODELO_ID,
                    X.MODELO_DESCRICAO,
                    X.DESCRICAO_NF,
                    X.COR_ID,
                    X.COR_DESCRICAO,
                    X.TAMANHO_ID,
                    X.TAMANHO,
                    X.CODIGO_EDI,
                    X.MULTIPLO,
                    X.COTA_EMBALAGEM,
                    X.QUANTIDADE,
                    fn_format_number(X.PESO_LIQUIDO,4) as PESO_LIQUIDO2,
                    fn_format_number(X.PESO_BRUTO,4) as PESO_BRUTO2,
                    X.MEDIDAS,
                    X.PESO_LIQUIDO,
                    X.PESO_BRUTO,
                    (X.QUANTIDADE / iif(X.MULTIPLO = 0, 1, X.MULTIPLO)) as VOLUMES2,
                    X.COTA_EMBALAGEM / iif(X.MULTIPLO = 0, 1, X.MULTIPLO) QUANTIDADE_SUBEMBALAGEM,
                    X.QUANTIDADE / iif( X.COTA_EMBALAGEM = 0,1,X.COTA_EMBALAGEM) VOLUMES,
                    fn_format_number(X.PESO_BRUTO   / ((X.QUANTIDADE * 1.0000) / iif( X.COTA_EMBALAGEM = 0,1,X.COTA_EMBALAGEM)),4) PESO_BRUTO_VOLUME,
                    fn_format_number(X.PESO_LIQUIDO / ((X.QUANTIDADE * 1.0000) / iif( X.COTA_EMBALAGEM = 0,1,X.COTA_EMBALAGEM)),4) PESO_LIQUIDO_VOLUME
                    From (
                    SELECT a.CONTROLE as ID, A.numero_notafiscal, A.data_emissao, PI.pedido, P.Pedido_Cliente,
                           PI.Produto_Codigo Produto_Id,
                           PI.Modelo_Codigo Modelo_Id,
                           M.Descricao Modelo_Descricao, M.Descricao_Nf,
                           PI.Cor_Id, C.Descricao Cor_Descricao,
                           PI.Tamanho Tamanho_Id, fn_tamanho_grade(M.grade_codigo,PI.Tamanho) Tamanho,

                          (Select First 1 IIF(PI.Tamanho = 0, PE.Codigo,
                                          IIF(PI.Tamanho = 1, PE.T01,IIF(PI.Tamanho = 2, PE.T02,
                                          IIF(PI.Tamanho = 3, PE.T03,IIF(PI.Tamanho = 4, PE.T04,
                                          IIF(PI.Tamanho = 5, PE.T05,IIF(PI.Tamanho = 6, PE.T06,
                                          IIF(PI.Tamanho = 7, PE.T07,IIF(PI.Tamanho = 8, PE.T08,
                                          IIF(PI.Tamanho = 9, PE.T09,IIF(PI.Tamanho =10, PE.T10,
                                          IIF(PI.Tamanho =11, PE.T11,IIF(PI.Tamanho =12, PE.T12,
                                          IIF(PI.Tamanho =13, PE.T13,IIF(PI.Tamanho =14, PE.T14,
                                          IIF(PI.Tamanho =15, PE.T15,IIF(PI.Tamanho =16, PE.T16,
                                          IIF(PI.Tamanho =17, PE.T17,IIF(PI.Tamanho =18, PE.T18,
                                          IIF(PI.Tamanho =19, PE.T19,IIF(PI.Tamanho =20, PE.T20,'')))))))))))))))))))))
                             From TbProduto_Edi PE Where PE.Produto_Codigo = PI.Produto_Codigo and PE.empresa_codigo = P.Cliente_codigo) Codigo_Edi,

                         Coalesce((Select First 1 PE.Multiplo From TbProduto_Edi PE Where PE.Produto_Codigo = PI.Produto_Codigo and PE.empresa_codigo = P.Cliente_codigo),1) Multiplo,
                         Coalesce(Trunc((Select First 1 IIF(PI.Tamanho = 0, ME.Quantidade,
                                          IIF(PI.Tamanho = 1, ME.T01,IIF(PI.Tamanho = 2, ME.T02,
                                          IIF(PI.Tamanho = 3, ME.T03,IIF(PI.Tamanho = 4, ME.T04,
                                          IIF(PI.Tamanho = 5, ME.T05,IIF(PI.Tamanho = 6, ME.T06,
                                          IIF(PI.Tamanho = 7, ME.T07,IIF(PI.Tamanho = 8, ME.T08,
                                          IIF(PI.Tamanho = 9, ME.T09,IIF(PI.Tamanho =10, ME.T10,
                                          IIF(PI.Tamanho =11, ME.T11,IIF(PI.Tamanho =12, ME.T12,
                                          IIF(PI.Tamanho =13, ME.T13,IIF(PI.Tamanho =14, ME.T14,
                                          IIF(PI.Tamanho =15, ME.T15,IIF(PI.Tamanho =16, ME.T16,
                                          IIF(PI.Tamanho =17, ME.T17,IIF(PI.Tamanho =18, ME.T18,
                                          IIF(PI.Tamanho =19, ME.T19,IIF(PI.Tamanho =20, ME.T20,0)))))))))))))))))))))
                             From TbModelo_Embalagem ME Where ME.Modelo_Codigo = PI.Modelo_Codigo)),1) Cota_Embalagem,

                           Trunc(A.Quantidade) Quantidade,

                           A.Peso_Liquido, A.Peso_Bruto,

                          Coalesce((Select First 1 trunc(PF.cubagem_altura)||'x'||trunc(PF.cubagem_largura)||'x'||trunc(PF.cubagem_comprimento)
                             From TbModelo_Consumo MC, TbProduto_Ficha PF
                            Where MC.Modelo_Codigo = PI.Modelo_Codigo
                              and MC.Consumo_Produto_Codigo = PF.produto_codigo
                              and MC.Nivel = 0 and PF.cubagem_altura > 0),'') Medidas

                    FROM tbnfs_item A,
                         tbpedido P,
                         TbPedido_Item PI,
                         TbModelo M,                                                                                
                         TbCor C

                    WHERE A.Nfs_Controle = :CONTROLE
                      and A.pedido = P.Pedido
                      and A.pedido = Pi.Pedido
                      and A.pedido_item_pe_controle = PI.Controle
                      and PI.Modelo_Codigo = M.Codigo
                      and PI.Cor_Id = C.Codigo
                    ) X";

        $args = [
            ':CONTROLE' => $nota['NOTA_FISCAL']['INFO']['ID']
        ];

        $etiquetas= $con->query($sql, $args);

        return $etiquetas;
    }


    /**
     * Consultar arquivos.
     * @param array $tarefa
     */
    public static function modeloEtiqueta($filtro, $con) {

        $sql = "SELECT
                    e.script,
                    e.descricao,
                    e.id
                from tbetiquetas e
                where e.tipo = :TIPO";

        $args = [
            ':TIPO' => '12100'
        ];

        $etiquetas= $con->query($sql, $args);

        return $etiquetas;
    }

    /**
     * Consultar clientes.
     *
     * @return array
     */
    public static function consultarItens($filtro, $con) {
        
        $sql = "
            SELECT  i.volumes,
                    c.codigo as cliente_codigo,
                    c.representante_codigo,
                    I.EMPRESA_CODIGO CLIENTE_ID,
                    I.CONTROLE ID,
                    I.NFS_Controle NFS_ID,
                    I.CFOP_CODIGO CFOP,
                    I.PRODUTO_CODIGO PRODUTO_ID,
                    I.produto_descricao,
                    I.PEDIDO,
                    (select first 1 h.pedido_cliente from tbpedido h where h.pedido = I.pedido) as PEDIDO_CLIENTE,
                    I.PEDIDO_ITEM_PE_CONTROLE PED_ITEM_ID,
                    fn_format_number(I.QUANTIDADE,2) as QUANTIDADE,
                    fn_tamanho_grade(p.grade_codigo,i.tamanho)as TAMANHO,
                    --I.T01,I.T02,I.T03,I.T04,I.T05,I.T06,I.T07,I.T08,I.T09,I.T10,
                    --I.T11,I.T12,I.T13,I.T14,I.T15,I.T16,I.T17,I.T18,I.T19,I.T20,
                    fn_format_number(I.VALOR_UNITARIO,2) as VALOR_UNITARIO,
                    fn_format_number(I.VALOR_DESCONTO,2) as VALOR_DESCONTO,
                    fn_format_number(I.VALOR_ACRESCIMO,2) as VALOR_ACRESCIMO,
                    fn_format_number(I.VALOR_IPI,2) as VALOR_IPI,
                    fn_format_number(I.VALOR_FRETE,2) as VALOR_FRETE,
                    fn_format_number(I.VALOR_TOTAL,2) as VALOR_TOTAL
                From TbNfs N, TBNFS_ITEM I, TBEMPRESA E, tbproduto P, tbcliente c
                Where I.NFS_CONTROLE = N.CONTROLE
                    AND N.Natureza = 1 and N.Situacao = 2
                    and n.controle = :CONTROLE
                    and E.CODIGO = N.EMPRESA_CODIGO
                    and p.codigo = i.produto_codigo
                    and c.codigo = n.empresa_codigo

                order by p.descricao, i.tamanho
        ";

        $args = [
            ':CONTROLE'  => $filtro['ID'],
        ];

        return $con->query($sql, $args);
    }

    /**
     * Consultar clientes.
     *
     * @return array
     */
    public static function consultarNotas($filtro, $con) {

        $cliente = 0;
        
        if(array_key_exists("CLIENTE",$filtro)){
           $cliente = $filtro['CLIENTE'];  
        }
        
        $flag    = $filtro['FLAG'];
        $nota    = $filtro['NOTA'];
        $serie   = $filtro['SERIE'];
        $pedido  = $filtro['PEDIDO'];

        $data_inicio = date('d.m.Y', strtotime($filtro['DATA_NICIO']));
        $data_fim    = date('d.m.Y', strtotime($filtro['DATA_FIM']));

        $sql_nota           = '';
        $sql_representante  = '';
        $sql_cliente        = '';
        $sql_pedido         = '';
        $sql_data           = '';

        $part_sql = '';

        if($nota > 0){

            if($serie > 0){
                $serie = ' and n.serie = ' . $serie;
            }else{
                $serie = ' and n.serie = (select h.valor_ext from tbcontrole_n h where h.id = 19)';
            }

            $sql_nota = ' and n.numero_notafiscal = '.$nota . $serie;
        }

        if($cliente  > 0){
            $sql_cliente = ' and N.Empresa_Codigo = '.$cliente;
        }

        if($flag  > 0){
            $sql_data = 'and N.DATA_EMISSAO BETWEEN \''.$data_inicio.'\' AND \''.$data_fim.'\'';
        }

        if($pedido > 0){
            $sql_pedido = ' and n.controle in (Select distinct I.nfs_controle From TBNFS_ITEM I WHERE I.pedido = '.$pedido.')';
        }

        $param = (object)[];
        $param->USUARIO_CODIGO = Auth::user()->CODIGO;

        $rep = _12040::verificarUsuarioEhRepresentante($param, $con);

        if (count($rep)){
            $sql_representante   = " and j.representante_codigo =  ".$rep[0]->REPRESENTANTE_CODIGO;
        }
        
        $sql = "
        SELECT * from
        (
            SELECT
                n.empresa_razaosocial,
                j.representante_codigo,
                j.codigo as cliente_codigo, 
                N.Controle Id,
                N.Numero_NotaFiscal,
                formatdate(N.Data_Emissao) as Data_Emissao,
                N.Empresa_Codigo Cliente_Id,
                N.Representante_Codigo Representante_Id,
                (Select First 1 E.NOMEFANTASIA From TbEmpresa E Where E.Codigo = T.Transportadora_Codigo) Transportadora,
                IIF(T.Frete=0,'0-CONSIG',IIF(T.Frete=1,'1-CIF',IIF(T.Frete=2,'2-FOB',IIF(T.Frete=3,'3-SEM FRT','')))) FRETE,
                fn_format_number(C.VALOR_DESCONTO,2) as TOTAL_DESCONTO,
                fn_format_number(C.VALOR_ACRESCIMO,2) as TOTAL_ACRESCIMO,
                fn_format_number(C.VALOR_IPI,2) as TOTAL_IPI,
                fn_format_number(C.VALOR_FRETE,2) as TOTAL_FRETE,
                fn_format_number(C.VALOR_TOTAL_NF,2) as TOTAL_NF,
                (SELECT IIF(Max(L.Xml)='07','Autorizado','Pendente')
                    From TbNf_Lote L
                    Where L.Estabelecimento_Codigo = N.Estabelecimento_Codigo
                    and L.Nfs_Controle = N.Controle and L.Nfe_Controle = 0) XML,

                (Select First 1
                   (Case When NE.DATA_SAIDA > '01/01/1899' Then lPad(Extract (Day   From NE.Data_Saida),2,'0')||'/'||
                                                                lPad(Extract (Month From NE.Data_Saida),2,'0')||'/'||
                                                                lPad(Extract (Year  From NE.Data_Saida),4,'0')
                    else '' end)||' / '||
                   (Case When NE.DATA_ENTREGA > '01/01/1899' Then lPad(Extract (Day   From NE.DATA_ENTREGA),2,'0')||'/'||
                                                                  lPad(Extract (Month From NE.DATA_ENTREGA),2,'0')||'/'||
                                                                  lPad(Extract (Year  From NE.DATA_ENTREGA),4,'0')
                    else '' end)
                     
               From TbNfs_Embarque NE
              Where NE.NFS_Controle = n.controle
                AND Ne.Estabelecimento_Codigo = n.estabelecimento_codigo)as EMBARQUE,

                coalesce((select list(pedido,', ') FROM( Select distinct I.pedido From TBNFS_ITEM I WHERE I.nfs_controle = n.CONTROLE and i.pedido > 0)),'') AS PEDIDO,
                fn_format_number((Select sum(I.quantidade) From TBNFS_ITEM I WHERE I.nfs_controle = n.CONTROLE)) AS TOTAL_QUANTIDADE

            From TbNfs N
            Left Join TbNfs_Transportadora T On T.Nfs_Controle = N.Controle
            Left Join TbNfs_Totais C On C.Nfs_Controle = N.Controle
            Left Join TBEMPRESA E On E.CODIGO = N.EMPRESA_CODIGO,
            tbcliente j
                Where N.Natureza = 1 and N.Situacao = 2
                and N.Estabelecimento_Codigo in(select J.CODIGO from SPC_USUARIO_ESTABELECIMENTOS(:USUARIO_ID) J)
                and E.CODIGO = N.EMPRESA_CODIGO
                and j.codigo = n.empresa_codigo

                ".$sql_nota           ."
                ".$sql_representante  ."
                ".$sql_cliente        ."
                ".$sql_pedido         ."
                ".$sql_data           ."

        ) x where XML = 'Autorizado'
        ";

        $args = [
            ':USUARIO_ID'  => Auth::user()->CODIGO,
        ];

        return $con->query($sql, $args);
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