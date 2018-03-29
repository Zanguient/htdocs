<?php

namespace App\Models\DTO\Admin;

use App\Models\DAO\Admin\_11005DAO;

/**
 * Objeto _11005 - Registro de Producao - Div. Bojo Colante
 */
class _11005
{
    
    public function __construct($con = null) {
        
        if ( isset($con) ) {
            $this->con = $con;
        }
    }      
    
    public function __destruct() {
        
    }      
    

    
    public function selectTabela($param = null) {
        
        $sql = "
            SELECT
                TRIM(R.RDB\$RELATION_NAME) TABELA

            FROM
                RDB\$RELATIONS R
            WHERE
                R.RDB\$SYSTEM_FLAG = 0
        ";
        
        $args = [
//            '@FILTRO'    => $filtro,
//            '@TABELA'    => $tabela,
//            '@TABELA_ID' => $tabela_id,
        ];    
        
        return $this->con->query($sql);
    }
    
    public function selectParametroTabela($param = null) {
        
        $sql = "
            SELECT P.TABELA

            FROM
                TBPARAMETRO P

            GROUP BY 1
        ";
        
        $args = [
//            '@FILTRO'    => $filtro,
//            '@TABELA'    => $tabela,
//            '@TABELA_ID' => $tabela_id,
        ];    
        
        return $this->con->query($sql);
    }
    
    public function selectParametro($param = null) {
        
        $sql = "
            SELECT FN_LPAD(ID,4,0) ID,
                   TABELA,
                   FN_LPAD(CONTROLE,4,0) CONTROLE,
                   CODIGO,
                   DESCRICAO,
                   VALOR,
                   OBSERVACAO,
                   GRUPO,
                   DATAHORA,
                   USUARIO_ID
              FROM TBPARAMETRO
             WHERE TRUE
             /*@TABELA*/
        ";
        
        $tabela = array_key_exists('TABELA', $param) ? "AND TABELA = '$param->TABELA'" : '';        
        
        $args = [
            '@TABELA' => $tabela
        ];    
        
        return $this->con->query($sql,$args);
    }
    
    public function selectParametroDetalhe($param = null) {
        
        $sql = "
            SELECT X.*,

                COALESCE(FN_DESCRICAO(X.TABELA,X.TABELA_ID),
                    FN_DESCRICAO(X.TABELA,X.TABELA_ID,
                        (SELECT REPLACE(OSPLIT,X.TABELA||'=','')
                           FROM SPLIT(FN_PARAMETRO('SISTEMA.TB_DESCRICAO'),';')
                          WHERE OSPLIT STARTING WITH X.TABELA))) TABELA_DESCRICAO

            FROM (            
                SELECT
                    FN_LPAD(D.TABELA_ID,4,0) TABELA_ID,
                    P.TABELA,
                    D.VALOR
                FROM
                    TBPARAMETRO_DETALHE D,
                    TBPARAMETRO P

                WHERE P.ID = D.PARAMETRO_ID
                AND D.VALOR <> P.VALOR
                AND P.ID = :PARAMETRO_ID
            ) X
            
        ";
        
        $args = [
            'PARAMETRO_ID' => $param->PARAMETRO_ID
        ];    
        
        return $this->con->query($sql,$args);
    }
    
    public function selectParametroDetalheTabela($param = null) {
        
        $sql = "
            SELECT DISTINCT X.*,

--                FN_DESCRICAO(X.TABELA,X.TABELA_ID) TABELA_DESCRICAO

                COALESCE(FN_DESCRICAO(X.TABELA,X.TABELA_ID),
                    FN_DESCRICAO(X.TABELA,X.TABELA_ID,
                        (SELECT REPLACE(OSPLIT,X.TABELA||'=','')
                           FROM SPLIT(FN_PARAMETRO('SISTEMA.TB_DESCRICAO'),';')
                          WHERE OSPLIT STARTING WITH X.TABELA))) TABELA_DESCRICAO

            FROM (            
                SELECT
                    FN_LPAD(D.TABELA_ID,4,0) TABELA_ID,
                    P.TABELA
                FROM
                    TBPARAMETRO_DETALHE D,
                    TBPARAMETRO P

                WHERE P.ID = D.PARAMETRO_ID
                AND D.VALOR <> P.VALOR
                AND P.TABELA = :TABELA
            ) X            
        ";
        
        $args = [
            'TABELA' => $param->TABELA
        ];    
        
        return $this->con->query($sql,$args);
    }
    
    public function selectParametroDetalheItem($param = null) {
        
        $sql = "
            SELECT FN_LPAD(P.ID,4,0) ID,
                   P.TABELA,
                   FN_LPAD(P.CONTROLE,4,0) CONTROLE,
                   P.CODIGO,
                   P.DESCRICAO,
                   P.VALOR,
                   D.VALOR VALOR_DEFINIDO,
                   P.OBSERVACAO,
                   P.GRUPO,
                   P.DATAHORA,
                   P.USUARIO_ID
              FROM TBPARAMETRO P,
                   TBPARAMETRO_DETALHE D
             WHERE TRUE
             AND P.ID = D.PARAMETRO_ID
             AND P.VALOR <> D.VALOR   
             AND P.TABELA = :TABELA
             AND D.TABELA_ID = :TABELA_ID            
        ";
        
        $args = [
            'TABELA'    => $param->TABELA,
            'TABELA_ID' => $param->TABELA_ID
        ];    
        
        return $this->con->query($sql,$args);
    }
    
}