<?php

namespace App\Models\DTO\Admin;

use App\Models\DAO\Admin\_11220DAO;

/**
 * Objeto _11220 - Registro de Producao - Div. Bojo Colante
 */
class _11220
{
    
    public function __construct($con = null) {
        
        if ( isset($con) ) {
            $this->con = $con;
        }
    }      
    
    public function __destruct() {
        
    }      
    

    
    public function getDados($param1 = null) {
        $ret = (object) [];

        $ret->MODULOS          = $this->selectModulo();
        $ret->PERIODOS         = $this->selectPeriodo();
        $ret->ESTABELECIMENTOS = $this->selectEstabelecimento();

        return $ret;
    } 

    
    public function selectModulo($param1 = null) {
        
        $sql = "
            SELECT
                CODIGO ID,
                DESCRICAO
            FROM TBMODULO
            WHERE CODIGO IN (SELECT OSPLIT FROM SPLIT(FN_PARAMETRO('TBUSUARIO.126',FN_CURRENT_USER_ID(5)),','))
        ";
        $args = [];

        return $this->con->query($sql,$args);
    } 

    
    public function selectPeriodo($param1 = null) {
        
        $sql = "
            SELECT X.*,
                    P.DATAINICIAL,
                    P.DATAFINAL
            FROM (
                SELECT
                    E.CODIGO ESTABELECIMENTO_ID,
                    M.CODIGO MODULO_ID
                FROM
                    TBESTABELECIMENTO E,
                    TBMODULO M
                ORDER BY E.CODIGO, M.CODIGO

                    )X
                    LEFT JOIN TBCONFIGURACAO_PERIODO P ON P.ESTABELECIMENTO_CODIGO = X.ESTABELECIMENTO_ID AND P.MODULO_CODIGO = X.MODULO_ID
            WHERE
                MODULO_ID IN (SELECT OSPLIT FROM SPLIT(FN_PARAMETRO('TBUSUARIO.126',FN_CURRENT_USER_ID(5)),','))

            AND IIF((SELECT FIRST 1 E.ESTABELECIMENTO_CODIGO
                       FROM TBUSUARIO_ESTABELECIMENTO E
                      WHERE E.USUARIO_CODIGO = FN_CURRENT_USER_ID(5)) IS NULL,TRUE,
                    ESTABELECIMENTO_ID IN (SELECT E.ESTABELECIMENTO_CODIGO
                                             FROM TBUSUARIO_ESTABELECIMENTO E
                                            WHERE E.USUARIO_CODIGO = FN_CURRENT_USER_ID(5)))
        ";
        
        $args = [];
        
        return $this->con->query($sql,$args);
    } 
    
    public function selectEstabelecimento($param1 = null) {
        
        $sql = "
            SELECT
                X.CODIGO ID,
                X.NOMEFANTASIA
            FROM TBESTABELECIMENTO X
            WHERE TRUE
            AND IIF((SELECT FIRST 1 E.ESTABELECIMENTO_CODIGO
                       FROM TBUSUARIO_ESTABELECIMENTO E
                      WHERE E.USUARIO_CODIGO = FN_CURRENT_USER_ID(5)) IS NULL,TRUE,
                    X.CODIGO IN (SELECT E.ESTABELECIMENTO_CODIGO
                                             FROM TBUSUARIO_ESTABELECIMENTO E
                                            WHERE E.USUARIO_CODIGO = FN_CURRENT_USER_ID(5)))            
        ";
        
        $args = [];
        
        return $this->con->query($sql,$args);
    } 
    
    public function updateInsertModulo($param) {
        
        if ( !isset($param->ID) ) {
            $param->ID = $this->con->gen_id('GTBMODULO');
        }        
        
        $sql = "
            UPDATE OR INSERT INTO TBMODULO (
                CODIGO, 
                DESCRICAO
            ) VALUES (
                :ID, 
                :DESCRICAO
            ) MATCHING (
                CODIGO
            );
        ";
        
        $args = [
            'ID'               => $param->ID,
            'DESCRICAO'        => $param->DESCRICAO
        ];    
        
        return $this->con->query($sql,$args);
    }    
    
    public function updateInsertPeriodo($param = null) {
        
        $sql = "
            UPDATE OR INSERT INTO TBCONFIGURACAO_PERIODO (
                ESTABELECIMENTO_CODIGO, 
                MODULO_CODIGO, 
                DATAINICIAL, 
                DATAFINAL
            ) VALUES (
                :ESTABELECIMENTO_ID, 
                :MODULO_ID,
                :DATAINICIAL,
                :DATAFINAL
            ) MATCHING (
                ESTABELECIMENTO_CODIGO, 
                MODULO_CODIGO
            );
        ";
        
        $args = [
            'ESTABELECIMENTO_ID' => $param->ESTABELECIMENTO_ID,
            'MODULO_ID'          => $param->MODULO_ID,
            'DATAINICIAL'        => $param->DATAINICIAL,
            'DATAFINAL'          => $param->DATAFINAL
        ];
        
        return $this->con->query($sql,$args);
    } 
    

}