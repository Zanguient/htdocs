<?php

namespace App\Models\DAO\Admin;

/**
 * DAO do objeto _11002 - Usuarios
 */
class _11002DAO {
	
	/**
     * Consultar.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultar($param, $con) {
        
        $sql = "
            SELECT

                ID,
                USUARIO,
                STATUS||'' as STATUS,
                iif(STATUS = 1, 'Ativo','Inativo') as DESC_STATUS,
                NOME

            from TBUSUARIO p
        ";

        return $con->query($sql);
    }

    /**
     * Consultar Itens
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultar_itens($param, $con) {
        
        $sql = "
            SELECT
                i.ID as KEY,            
                i.menu_id as ID,
                ''||i.INCLUIR as INCLUIR,
                ''||i.ALTERAR as ALTERAR,
                ''||i.EXCLUIR as EXCLUIR,
                m.DESCRICAO,
                ''||i.VIZUALIZAR as VISUALIZAR,
                m.GRUPO,
                iif(i.incluir = 1, 'Sim', 'Não') as desc_incluir,
                iif(i.alterar = 1, 'Sim', 'Não') as desc_alterar,
                iif(i.excluir = 1, 'Sim', 'Não') as desc_excluir,
                iif(i.VIZUALIZAR = 1, 'Sim', 'Não') as desc_visualizar

            from TBUSUARIO_MENU i, tbmenu m
            where i.usuario_id = :TBUSUARIO_ID
            and m.codigo = i.menu_id
        ";

        $args = array(
            ':TBUSUARIO_ID'     => $param->TBUSUARIO_ID
        );

        return $con->query($sql, $args);
    }

    /**
     * incluir.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function incluir($param, $con) {

        $param = $param->ITEM;
        
        $sql = "
            INSERT INTO TBUSUARIO (NOME, USUARIO, STATUS)
                    VALUES (:NOME, :USUARIO, :STATUS);
        ";

        $args = array(
            ':NOME'      => $param->NOME,
            ':USUARIO'   => $param->USUARIO,
            ':STATUS'    => $param->STATUS
        );

        return $con->query($sql, $args);
    }

    /**
     * incluir.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function resetarSenhaSuper($param, $con) {

        $param = $param->ITEM;
        
        $sql = "
            UPDATE TBUSUARIO SET
                PASSWORD = null
            WHERE (ID = :ID);
        ";

        $args = array(
            ':ID' => $param->ID
        );

        return $con->query($sql, $args);
    }

   /**
     * incluir.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function atualizarMenusUser($param, $con) {

        $param = $param->ITEM;

        $sql = "
            EXECUTE PROCEDURE SPU_MENUS_USUARIO(:TBUSUARIO_ID);
        ";

        $args = array(
            ':TBUSUARIO_ID'     => $param->ID
        );

        $con->execute($sql, $args);

        return ['OK' => 'OK'];
    }

    

    /**
     * incluir.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function incluir_itens($param, $con) {

        $param = $param->ITEM;
        
        $sql = "
            INSERT INTO TBUSUARIO_MENU (TBUSUARIO_ID, DESCRICAO, STATUS)
                         VALUES (:TBUSUARIO_ID, :DESCRICAO, :STATUS);
        ";

        $args = array(
            ':DESCRICAO'     => $param->DESCRICAO,
            ':TBUSUARIO_ID'  => $param->TBUSUARIO_ID,
            ':STATUS'        => $param->STATUS
        );

        return $con->query($sql, $args);
    }
	
    /**
     * alterar.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function alterar($param, $con) {

        $param = $param->ITEM;
        
        $sql = "
            UPDATE TBUSUARIO SET
                NOME      = :NOME,
                USUARIO   = :USUARIO,
                STATUS    = :STATUS
            WHERE (ID = :ID);
        ";

        $args = array(
            ':ID'           => $param->ID,
            ':NOME'         => $param->NOME,
            ':USUARIO'      => $param->USUARIO,
            ':STATUS'       => $param->STATUS
        );

        return $con->query($sql, $args);
    }

    /**
     * alterar.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function alterar_itens($param, $con) {

        $param = $param->ITEM;
        
        $sql = "
            UPDATE TBUSUARIO_MENU SET 
                INCLUIR    = :INCLUIR,
                ALTERAR    = :ALTERAR,
                EXCLUIR    = :EXCLUIR,
                VIZUALIZAR = :VIZUALIZAR
            WHERE (ID = :ID);
        ";

        $args = array(
            ':INCLUIR'      => $param->INCLUIR,
            ':ALTERAR'      => $param->ALTERAR,
            ':EXCLUIR'      => $param->EXCLUIR,
            ':VIZUALIZAR'   => $param->VISUALIZAR,
            ':ID'           => $param->KEY
        );

        return $con->query($sql, $args);
    }

    /**
     * excluir.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function excluir($param, $con) {

        $param = $param->ITEM;
        
        $sql = "DELETE FROM TBUSUARIO WHERE (ID = :ID);";

        $args = array(
            ':ID' => $param->ID
        );

        return $con->query($sql, $args);
    }

    /**
     * excluir.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function excluir_itens($param, $con) {

        $param = $param->ITEM;
        
        $sql = "DELETE FROM TBUSUARIO_MENU WHERE (ID = :ID);";

        $args = array(
            ':ID' => $param->ID
        );

        return $con->query($sql, $args);
    }
	
}