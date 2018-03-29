<?php

namespace App\Models\DAO\Vendas;

use App\Models\Conexao\_Conexao;

/**
 * DAO do objeto _12090 - Cadastro de Clientes
 */
class _12090DAO {

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