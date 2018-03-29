<?php

namespace App\Models\DTO\Estoque;

use App\Models\DAO\Estoque\_15110DAO;

/**
 * Registro de Produção de Blocos Laminados / Torneados
 */
class _15110
{
    public function __construct($con) {
        $this->con = $con;
    }  
 

    public function selectEstoque($param) {       
        
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
                   D.UM
 
              FROM VWESTOQUE_SALDO_DISPONIVEL D,
                   TBUSUARIO_FAMILIA UF,
                   TBFAMILIA F,
                   SPC_USUARIO_ESTABELECIMENTOS( FN_CURRENT_USER_ID() ) E,
                   SPC_USUARIO_LOCALIZACAO     ( FN_CURRENT_USER_ID() ) L
             WHERE UPPER(D.FILTRO) LIKE UPPER('%' || REPLACE(CAST(:FILTRO AS VARCHAR(151)),' ','%') || '%')
               AND UF.USUARIO_ID      = FN_CURRENT_USER_ID()
               AND UF.FAMILIA_ID      = D.FAMILIA_ID
               AND F.CODIGO           = UF.FAMILIA_ID
               AND E.CODIGO           = D.ESTABELECIMENTO_ID
               AND L.LOCALIZACAO_ID   = D.LOCALIZACAO_ID
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