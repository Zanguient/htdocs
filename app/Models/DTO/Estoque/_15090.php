<?php

namespace App\Models\DTO\Estoque;

use App\Models\DAO\Estoque\_15090DAO;

/**
 * Registro de Produção de Blocos Laminados / Torneados
 */
class _15090
{
    public function __construct($con) {
        $this->con = $con;
    }  
 

    public function selectConferenciaItens($param1) {       
        
        $sql =
        "
            SELECT
            
                LIST(X.ESTOQUE_ID,',') ESTOQUE_ID,
                FN_LPAD(X.PRODUTO_ID,6,0) PRODUTO_ID,
                X.PRODUTO_DESCRICAO,
                X.LOCALIZACAO_CODIGO LOCALIZACAO_ID,
                X.CONFERENCIA,
                X.TAMANHO,
                FN_TAMANHO_PRODUTO(X.PRODUTO_ID,X.TAMANHO) TAMANHO_DESCRICAO,
                SUM(X.QUANTIDADE) AS QUANTIDADE,
                P.UNIDADEMEDIDA_SIGLA UM,
                X.PECA_ID

            FROM SPC_CONFERENCIA_TRANSACAO2(:CODIGO_BARRAS,:CONFERENCIA_TIPO) X,
                 TBPRODUTO P

            WHERE
                P.CODIGO = X.PRODUTO_ID

            GROUP BY
                X.PRODUTO_ID,
                X.PRODUTO_DESCRICAO,
                X.CONFERENCIA,
                X.TAMANHO,
                X.LOCALIZACAO_CODIGO,
                P.UNIDADEMEDIDA_SIGLA,
                X.PECA_ID
        ";
        
        $args = [
            'CODIGO_BARRAS'    => $param1->CODIGO_BARRAS,
            'CONFERENCIA_TIPO' => $param1->CONFERENCIA_TIPO
        ];
        
        return $this->con->query($sql,$args);
    }

    public function selectConferenciaPendentes($param) {       
        
        $sql =
        "
            SELECT
               d.*,
               l.descricao as FAMILIA
            FROM
                VWCONFERENCIA_PENDENTE d, TBUSUARIO_FAMILIA f, tbfamilia l
            where d.familia_codigo = f.familia_id
                and f.usuario_id = fn_current_user_id()
                and l.codigo = d.familia_codigo
            --d.datahora between  :DATA1 and :DATA2
        ";
        
        /*
        $args = [
            'DATA1' => $param->DATA1,
            'DATA2' => $param->DATA2,
        ]; 
        */

        return $this->con->query($sql); 
    }

    public function selectConferenciaPendentesLote() {       
        
        $sql =
        "
              SELECT distinct
                  lpad(c.ID, 11, 0) as ID,
                  formatdatetime(c.DATA_HORA) as DATA_HORA,
                  c.OPERADOR_CONFERENCIA,
                  c.OPERADOR_LOTE
              from tblote_conferencia c, tblote_conferencia_detalhe d,  tbestoque_transacao_item i
              where d.lote_id = c.id
              and i.controle = d.estoue_id
              and i.conferencia = 1
              and c.data_hora > dateadd(day, -45, current_date)
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