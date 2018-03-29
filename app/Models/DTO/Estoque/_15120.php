<?php

namespace App\Models\DTO\Estoque;

use App\Models\DAO\Estoque\_15120DAO;

/**
 * Registro de Produção de Blocos Laminados / Torneados
 */
class _15120
{
    public function __construct($con) {
        $this->con = $con;
    }

    public function selectFamilia($param) {       
        
        $filtro = $param->FILTRO;

        $sql =
        "
          SELECT
              f.codigo as ID,
              f.descricao
          from tbfamilia f
          where f.codigo > 0
          and f.codigo||'-'||f.descricao LIKE '%". strtoupper($filtro) ."%'

          order by f.descricao

        ";
        
        return $this->con->query($sql);
    }

    public function selectEstoque($param) {    

        $familia = '';
        if(count($param->FAMILIA) > 0){
          $familia = ' AND F.CODIGO = ' . $param->FAMILIA->ID;  
        }else{
          log_erro('Selecione uma família');
        }

        $saldo = '';
        if($param->SALDO == true){
          $saldo = ' AND D.SALDO > 0';  
        }
              

        $sql =
        "
            SELECT FIRST :FIRST SKIP :SKIP
                   D.ESTABELECIMENTO_ID,
                   E.NOMEFANTASIA ESTABELECIMENTO_NOMEFANTASIA,
                   E.UF ESTABELECIMENTO_UF,
                   D.LOCALIZACAO_ID,
                   L.LOCALIZACAO_DESCRICAO,
                   D.FAMILIA_ID,
                   F.DESCRICAO FAMILIA_DESCRICAO,
                   FN_LPAD(D.PRODUTO_ID,6,0) PRODUTO_ID,
                   D.PRODUTO_DESCRICAO,
                   D.GRADE_ID,
                   D.TAMANHO,
                   D.TAMANHO_DESCRICAO,
                   D.SALDO,
                   D.UM,
                   D.saldo_deterceiro,
                   d.saldo_emterceiro,

                   Coalesce((Select First 1 Cast(

                    iif(d.tamanho = 1, r.t01_saldo,
                    iif(d.tamanho = 2, r.t02_saldo,
                    iif(d.tamanho = 3, r.t03_saldo,
                    iif(d.tamanho = 4, r.t04_saldo,
                    iif(d.tamanho = 5, r.t05_saldo,
                    iif(d.tamanho = 6, r.t06_saldo,
                    iif(d.tamanho = 7, r.t07_saldo,
                    iif(d.tamanho = 8, r.t08_saldo,
                    iif(d.tamanho = 9, r.t09_saldo,
                    iif(d.tamanho = 10, r.t10_saldo,
                    iif(d.tamanho = 11, r.t11_saldo,
                    iif(d.tamanho = 12, r.t12_saldo,
                    iif(d.tamanho = 13, r.t13_saldo,
                    iif(d.tamanho = 14, r.t14_saldo,
                    iif(d.tamanho = 15, r.t15_saldo,
                    iif(d.tamanho = 16, r.t16_saldo,
                    iif(d.tamanho = 17, r.t17_saldo,
                    iif(d.tamanho = 18, r.t18_saldo,
                    iif(d.tamanho = 19, r.t19_saldo,
                    iif(d.tamanho = 20, r.t20_saldo,
                    R.Saldo))))))))))))))))))))
                    as Numeric(15,4)) From TBEstoque_Saldo R, TbFamilia_Ficha FF
                      Where R.Estabelecimento_Codigo = D.ESTABELECIMENTO_ID
                        and R.Produto_Codigo = p.Codigo
                        and R.Estabelecimento_Codigo = FF.Estabelecimento_Codigo
                        and FF.Familia_Codigo = p.Familia_Codigo
                        and R.Localizacao_Codigo = FF.Localizacao_Revisao and R.Saldo > 0),0) Saldo_Revisao,
                    
                    Coalesce((Select First 1 Cast(iif(d.tamanho = 1, r.t01_saldo,
                    iif(d.tamanho = 2, r.t02_saldo,
                    iif(d.tamanho = 3, r.t03_saldo,
                    iif(d.tamanho = 4, r.t04_saldo,
                    iif(d.tamanho = 5, r.t05_saldo,
                    iif(d.tamanho = 6, r.t06_saldo,
                    iif(d.tamanho = 7, r.t07_saldo,
                    iif(d.tamanho = 8, r.t08_saldo,
                    iif(d.tamanho = 9, r.t09_saldo,
                    iif(d.tamanho = 10, r.t10_saldo,
                    iif(d.tamanho = 11, r.t11_saldo,
                    iif(d.tamanho = 12, r.t12_saldo,
                    iif(d.tamanho = 13, r.t13_saldo,
                    iif(d.tamanho = 14, r.t14_saldo,
                    iif(d.tamanho = 15, r.t15_saldo,
                    iif(d.tamanho = 16, r.t16_saldo,
                    iif(d.tamanho = 17, r.t17_saldo,
                    iif(d.tamanho = 18, r.t18_saldo,
                    iif(d.tamanho = 19, r.t19_saldo,
                    iif(d.tamanho = 20, r.t20_saldo,
                    R.Saldo)))))))))))))))))))) as Numeric(15,4)) From TBEstoque_Saldo R, TbFamilia_Ficha FF
                      Where R.Estabelecimento_Codigo = D.ESTABELECIMENTO_ID
                        and R.Produto_Codigo = p.Codigo
                        and R.Estabelecimento_Codigo = FF.Estabelecimento_Codigo
                        and FF.Familia_Codigo = p.Familia_Codigo
                        and R.Localizacao_Codigo = FF.Localizacao_Estragado and R.Saldo > 0),0) Saldo_Estragado,

                    Coalesce((Select Sum(Z.Quantidade) From TbOc X, TbOc_Item Y, TbOC_Item_Saldo Z
                      Where  X.Oc = Y.Oc and Y.Controle = Z.Oc_Item_Controle and Y.Situacao = 1 and X.Status = 1
                        and  X.Estabelecimento_Codigo = D.ESTABELECIMENTO_ID and Y.Produto_Codigo = p.CODIGO),0) OC,

                    Coalesce((Select (R.QUANTIDADE) From TbEstoque_Minimo R
                      Where R.Estabelecimento_Codigo = D.ESTABELECIMENTO_ID and R.Localizacao_Codigo = D.LOCALIZACAO_ID and R.Produto_Codigo = p.Codigo),0) ESTOQUE_MINIMO


                 
              FROM vwestoque_saldo_produto2 D,
                   TBPRODUTO P,
                   TBUSUARIO_FAMILIA UF,
                   TBFAMILIA F,
                   SPC_USUARIO_ESTABELECIMENTOS( FN_CURRENT_USER_ID() ) E,
                   SPC_USUARIO_LOCALIZACAO     ( FN_CURRENT_USER_ID() ) L
             WHERE UPPER(D.FILTRO||' '||E.NOMEFANTASIA||' '||L.LOCALIZACAO_DESCRICAO) LIKE UPPER('%' || REPLACE(CAST(:FILTRO AS VARCHAR(151)),' ','%') || '%')
               AND UF.USUARIO_ID      = FN_CURRENT_USER_ID()
               AND UF.FAMILIA_ID      = D.FAMILIA_ID
               AND F.CODIGO           = UF.FAMILIA_ID
               AND E.CODIGO           = D.ESTABELECIMENTO_ID
               AND L.LOCALIZACAO_ID   = D.LOCALIZACAO_ID
               AND P.CODIGO           = D.PRODUTO_ID
               AND P.STATUS           = 1

               $familia
               $saldo

          ORDER BY PRODUTO_DESCRICAO, TAMANHO_DESCRICAO
        ";
        
        if ( $param->FIRST == '' ) {
            $param->FIRST = null;
        }
        
        $args = [
            'FIRST'  => setDefValue($param->FIRST , 500),
            'SKIP'   => setDefValue($param->SKIP  , 0),
            'FILTRO' => setDefValue($param->FILTRO, '%') 
        ];
        
        return $this->con->query($sql,$args);
    }

    public function selectConferenciaPendentes() {       
        
        $sql =
        "
            SELECT
                *
            FROM
                VWCONFERENCIA_PENDENTE
        ";
        
        return $this->con->query($sql);
    }
    

    public function insertKanbanLote($param) {

        $id = $this->con->gen_id('GTBKANBAN_LOTE');
        
        $sql = "
            INSERT INTO TBKANBAN_LOTE (
                ID, 
                LOCALIZACAO_ID
            ) VALUES (
                :ID,
                :LOCALIZACAO_ID
            );
        ";
        
        $args = [
            'ID'             => $id,
            'LOCALIZACAO_ID' => $param->LOCALIZACAO_ID,
        ]; 
        
        $this->con->query($sql,$args);       
        
        return $id;
    }
         
    public function updateConferencia($param) {
        
        $sql = "
            EXECUTE PROCEDURE SPU_CONFERENCIA_TRANSACAO(:ESTOQUE_ID,:OPERADOR_ID,:CONFERIR);
        ";
        
        $args = [
            'ESTOQUE_ID'  => $param->ESTOQUE_ID,
            'OPERADOR_ID' => $param->OPERADOR_ID,
            'CONFERIR'    => $param->CONFERIR,
        ]; 
        
        return $this->con->query($sql,$args);       
    }
    
    public function deleteKanbanLoteDetalhe($param) {

        $sql = "
            DELETE
              FROM TBKANBAN_LOTE_DETALHE D
             WHERE D.ID = :KANBAN_LOTE_DETALHE_ID

        ";
        
        $args = [
            'KANBAN_LOTE_DETALHE_ID' => $param->KANBAN_LOTE_DETALHE_ID
        ]; 
        
        return $this->con->query($sql,$args);       
    }
        
    
}