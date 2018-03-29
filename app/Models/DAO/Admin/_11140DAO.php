<?php

namespace App\Models\DAO\Admin;

use App\Models\Conexao\_Conexao;

/**
 * DAO do objeto _11140 - Painel de Casos
 */
class _11140DAO {

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

            $sql = 'SELECT FIRST 100

                        LPAD(ID,4,\'0\') AS ID,
                        DESCRICAO,
                        TITULO,
                        MENU_GRUPO,
                        TEMPLATE,
                        FRASE,
                        STATUS

                    FROM TBCASO_PAINEL P';

            $ret = $con->query($sql);

            $con->commit();
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    public static function getClientes($filtro) {
        try {

            $filter = strtoupper($filtro['FILTRO']);
            $desc = !empty($filter) ? 'and c.codigo||\' \'||c.razaosocial like \'%'.  str_replace(' ', '%', $filter) .'%\'' : '';

            $options = $filtro['OPTIONS'];

            $con = new _Conexao;

            $sql = 'SELECT FIRST 50

                    c.codigo as id,
                    c.razaosocial as descricao,
                    c.status,
                    c.uf

                from tbcliente c
                where true

                    '.$desc.'

                ';
            $ret = $con->query($sql);
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }
	
}