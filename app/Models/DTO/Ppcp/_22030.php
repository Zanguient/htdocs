<?php

namespace App\Models\DTO\Ppcp;

use App\Models\DAO\Ppcp\_22030DAO;

/**
 * Gestão de Grupos de Produção
 */
class _22030
{
    
    public function __construct($con = null) {
        
        if ( isset($con) ) {
            $this->con = $con;
        }
    }      
    

    public function selectGp($param1) {
        
        $sql = "
            SELECT *
            FROM (
                SELECT X.*,
                   (GP_ID || ' ' ||
                    GP_DESCRICAO ) FILTRO
                FROM (
                    SELECT
                        FN_LPAD(G.ID,3,'0') GP_ID,
                        G.DESCRICAO GP_DESCRICAO,
                        G.FAMILIA_ID GP_FAMILIA_ID,
                        TRIM(G.PERFIL) GP_PERFIL,
                        G.REMESSAS GP_REMESSAS,
                        G.CCUSTO GP_CCUSTO,
                        G.CODIGO1 GP_BARRAS,
                        TRIM(COALESCE(NULLIF(G.VER_PECA_DISPONIVEL      ,''),'0')) GP_VER_PECA_DISPONIVEL,
                        TRIM(COALESCE(NULLIF(G.VER_PARES                ,''),'0')) GP_VER_PARES,
                        TRIM(COALESCE(NULLIF(G.HABILITA_QUEBRA_TALAO_SKU,''),'0')) GP_HABILITA_QUEBRA_TALAO_SKU
        
                    FROM
                        TBGP G
                    ) X
                ) Y
            WHERE TRUE
                /*@FILTRO*/
                /*@GP_ID*/
                /*@GP_FAMILIAS_ID*/
                /*@GP_BARRAS*/
                
            ORDER BY GP_DESCRICAO
        ";
        
        $param = (object)[];

        if ( isset($param1->FILTRO) && trim($param1->FILTRO) != '' ) {
            $param->FILTRO = " LIKE UPPER('%$param1->FILTRO%')";
        }
        
        if ( isset($param1->GP_ID) && $param1->GP_ID > 0 ) {
            $param->GP_ID = " = $param1->GP_ID";
        }
        
        if ( isset($param1->GP_BARRAS) ) {
            $param->GP_BARRAS = " = '$param1->GP_BARRAS'";
        }
        
        if ( isset($param1->GP_FAMILIAS_ID) && trim($param1->GP_FAMILIAS_ID) != '' ) {
            $param->GP_FAMILIAS_ID = " IN ($param1->GP_FAMILIAS_ID)";
        }
        
        $filtro          = array_key_exists('FILTRO'        , $param) ? "AND UPPER(FILTRO) $param->FILTRO        " : '';
        $gp_id           = array_key_exists('GP_ID'         , $param) ? "AND GP_ID         $param->GP_ID         " : '';
        $gp_barras       = array_key_exists('GP_BARRAS'     , $param) ? "AND GP_BARRAS     $param->GP_BARRAS     " : '';
        $gp_familias_id  = array_key_exists('GP_FAMILIAS_ID', $param) ? "AND GP_FAMILIA_ID $param->GP_FAMILIAS_ID" : '';
        
        $args = [
            '@FILTRO'         => $filtro,
            '@GP_ID'          => $gp_id,
            '@GP_BARRAS'      => $gp_barras,
            '@GP_FAMILIAS_ID' => $gp_familias_id
        ];    
        
        return $this->con->query($sql,$args);
    }         

    public function selectUp($param1) {
        
        $sql = "
            SELECT *
            FROM (
                SELECT
                    X.*,
                    (UP_ID || ' ' || UP_DESCRICAO) FILTRO
                FROM
                    (
                    SELECT
                        FN_LPAD(A.GP_ID,3,0) GP_ID,
                        CAST(G.PERFIL AS VARCHAR(10)) GP_PERFIL,
                        FN_LPAD(U.ID,3,0) UP_ID,
                        U.DESCRICAO UP_DESCRICAO,
                        COALESCE(U.FAMILIA_ID,'') UP_FAMILIA_ID,
                        TRIM(U.STATUS) UP_STATUS,
                        CAST(U.PERFIL AS VARCHAR(10)) UP_PERFIL,
                        U.CCUSTO UP_CCUSTO
    
                    FROM    
                        TBUP U,
                        TBGP_UP A,
                        TBGP G
    
                    WHERE
                        U.ID = A.UP_ID
                    AND G.ID = A.GP_ID
    
                    ORDER BY GP_ID, UP_DESCRICAO, UP_ID
                    ) X
                ) Y
            WHERE TRUE
            /*@FILTRO*/
            /*@GP_ID*/
            /*@GP_PERFIL*/
            /*@UP_ID*/
            /*@UP_PERFIL*/
            /*@UP_STATUS*/
            /*@UP_FAMILIA_ID*/

            ORDER BY UP_DESCRICAO
        ";
        
        $param = (object)[];

        if ( isset($param1->FILTRO) && trim($param1->FILTRO) != '' ) {
            $param->FILTRO = " LIKE UPPER('%$param1->FILTRO%')";
        }

        if ( isset($param1->GP_ID) && $param1->GP_ID > 0 ) {
            $param->GP_ID = " = $param1->GP_ID";
        }

        if ( isset($param1->UP_ID) && $param1->UP_ID > 0 ) {
            $param->UP_ID = " = $param1->UP_ID";
        }

        if ( isset($param1->GP_PERFIL) ) {
            $param->GP_PERFIL = " = '$param1->GP_PERFIL'";
        }

        $filtro = array_key_exists('FILTRO', $param) ? "AND UPPER(FILTRO) $param->FILTRO" : '';
        $gp_id  = array_key_exists('GP_ID', $param)  ? "AND GP_ID         $param->GP_ID" : '';
        $up_id  = array_key_exists('UP_ID', $param)  ? "AND UP_ID         $param->UP_ID" : '';
        
        $gp_perfil = array_key_exists('GP_PERFIL', $param)  ? "AND GP_PERFIL $param->GP_PERFIL" : '';
        
        $args = [
            '@FILTRO' => $filtro,
            '@GP_ID'  => $gp_id,
            '@UP_ID'  => $up_id,
            '@GP_PERFIL' => $gp_perfil
        ];    
                
        return $this->con->query($sql,$args);
    }         

    public function selectEstacao($param1) {
        
        $sql = "
            SELECT
                X.*,
               (ESTACAO || ' ' ||
                ESTACAO_DESCRICAO) FILTRO
            FROM (
                SELECT                     
                    FN_LPAD(S.UP_ID,3,0) UP_ID,
                    FN_LPAD(S.ID,3,0) ESTACAO,
                    S.DESCRICAO ESTACAO_DESCRICAO,
                    TRIM(LIST(S.PERFIL_SKU)) ESTACAO_PERFIL_SKU
    
                FROM
                    TBSUB_UP S     
                GROUP BY 1,2,3
                ) X
    
                WHERE TRUE
                /*@FILTRO*/
                /*@UP_ID*/
                /*@ESTACAO*/
                /*@STATUS*/
                /*@PERFIL_SKU*/
                
            ORDER BY ESTACAO_DESCRICAO
        ";
        
        
        $param = (object)[];

        if ( isset($param1->FILTRO) && trim($param1->FILTRO) != '' ) {
            $param->FILTRO = " LIKE UPPER('%$param1->FILTRO%')";
        }

        if ( isset($param1->UP_ID) && $param1->UP_ID > 0 ) {
            $param->UP_ID = " = $param1->UP_ID";
        }

        if ( isset($param1->ESTACAO) && $param1->ESTACAO > 0 ) {
            $param->ESTACAO = " = $param1->ESTACAO";
        }

        $filtro  = array_key_exists('FILTRO' , $param) ? "AND UPPER(FILTRO) $param->FILTRO " : '';
        $up_id   = array_key_exists('UP_ID'  , $param) ? "AND UP_ID         $param->UP_ID  " : '';
        $estacao = array_key_exists('ESTACAO', $param) ? "AND ESTACAO       $param->ESTACAO" : '';
        
        $args = [
            '@FILTRO'  => $filtro,
            '@UP_ID'   => $up_id,
            '@ESTACAO' => $estacao
        ];    
                
        return $this->con->query($sql,$args);
    }         
    
    /**
     * Retorna Listagem Principal
     * @param array $param
     * <ul>
     *  <li>
     *    <b>STATUS</b>: Sequência com os códigos dos status. <br/>
     *      Ex.: STATUS => [1, 2, 3, 4, ...]
     *  </li>
     *  <li>
     *    <b>FAMILIA</b>: Sequência com os códigos das famílias de produto. <br/>
     *      Ex.: FAMILIA => [1, 2, 3, 4, ...]
     *  </li>
     *  <li>
     *    <b>RETORNO</b>: Consultas a serem retornadas na chave.<br/>
     *      Ex.: _22020::listar( RETORNO => [GP] ), retornará os grupos de produção
     *  </li>
     * </ul>
     * <ul>
     * @return type
     */    
    public static function listar($param = []) {
        return _22030DAO::listar((object) $param);
    }
}