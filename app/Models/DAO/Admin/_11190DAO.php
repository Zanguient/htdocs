<?php

namespace App\Models\DAO\Admin;

use App\Models\Conexao\_Conexao;

/**
 * DAO do objeto _11190 - Notificacao
 */
class _11190DAO {

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

    /**
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function getUsuarios($filtro,$con) {
        
        try {

            $sql = 'SELECT
                        n.codigo as ID,
                        n.usuario,
                        n.setor,
                        n.nome
                    from
                        tbusuario n
                    where n.status = 1';

            $ret = $con->query($sql);
            
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
    public static function getUserNotfi($filtro,$con) {
        
        try {

            $sql = 'SELECT
                        *
                    from
                        tbnotificacao n
                    where n.leitura = 0';

            $ret = $con->query($sql);

            $con->commit();
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }
	
}