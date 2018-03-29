<?php

namespace App\Models\DAO\Admin;

use App\Models\Conexao\_Conexao;

/**
 * DAO do objeto _11100 - Qlik Sense
 */
class _11100DAO {

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
     * Listar
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function getProjetos($dados,$con) {
            
        $sql = "
            SELECT

                s.*,
                u.usuario

            FROM
                TBUSUARIO_BI S,
                tbusuario u

            WHERE
                S.USUARIO_ID = :USUARIO_ID
                and u.codigo = s.usuario_id            
            ";
        
        $args = [
            'USUARIO_ID' => $dados['USUARIO_ID']
        ];
        
        $rest = $con->query($sql,$args);

        $con->query($sql,$args);

        $con->rollback();

        return $rest;

    }
	
}