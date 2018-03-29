<?php

namespace App\Models\DAO\Admin;

/**
 * DAO do objeto _11210 - Cadastro de Perfil de Usuario
 */
class _11210DAO {
	
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

                *

            from TBUSUARIO_PERFIL p
            where id > 0
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
    public static function ConsultaUsuario($param, $con) {

        $filtro = array_key_exists('FILTRO', $param) ? " and upper(u.codigo||' '||u.nome||' '||u.usuario) like upper('%" . str_replace(' ', '%', $param->FILTRO) . "%')" : '';
        
        $sql = "
            select first 20
                u.codigo as id,
                u.usuario,
                u.nome
            from tbusuario u where u.status = 1
            $filtro
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
                
                i.*,
                u.nome as DESCRICAO

            from TBUSUARIO_PERFIL_DETALHE i, tbusuario u
            where i.PERFIL_ID = :PERFIL_ID
            and u.codigo = i.usuario_id
        ";

        $args = array(
            ':PERFIL_ID'     => $param->TBUSUARIO_PERFIL_ID
        );

        return $con->query($sql, $args);
    }

    /**
     * Consultar Itens
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultar_perfil_menu($param, $con) {
        
        $sql = "
            SELECT distinct
                m.controle as ID,
                m.descricao,
                g.INCLUIR,
                g.ALTERAR,
                g.EXCLUIR,
                g.IMPRIMIR,
                g.NEGAR
            from tbmenu m, tbusuario_perfil_menu g
            where g.perfil_id = :PERFIL_ID
            and m.controle = g.menu_controle
            order by m.descricao
        ";

        $args = array(
            ':PERFIL_ID'     => $param->PERFIL_ID
        );

        return $con->query($sql, $args);
    }

    /**
     * Consultar Itens
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultar_perfil_grupo($param, $con) {
        
        $sql = "
            SELECT distinct
                g.id, g.menu_grupo
            from tbusuario_perfil_grupo g
            where g.perfil_id = :PERFIL_ID
            order by g.menu_grupo
        ";

        $args = array(
            ':PERFIL_ID'     => $param->PERFIL_ID
        );

        return $con->query($sql, $args);
    }

    /**
     * Consultar Itens
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultar_menu($param, $con) {

        $filtro = array_key_exists('FILTRO', $param) ? " where upper(m.controle||' '||m.descricao) like upper('%" . str_replace(' ', '%', $param->FILTRO) . "%')" : '';
        
        $sql = "
            SELECT first 20 distinct
                m.controle as ID,
                m.descricao
            from tbmenu m
            $filtro
            order by m.descricao
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
    public static function consultar_grupo($param, $con) {
        
        $filtro = array_key_exists('FILTRO', $param) ? " where upper(m.grupo) like upper('%" . str_replace(' ', '%', $param->FILTRO) . "%')" : '';
        
        $sql = "
            SELECT distinct
                m.grupo
            from tbmenu m
            $filtro
            order by m.descricao
        ";

        return $con->query($sql);
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
            INSERT INTO TBUSUARIO_PERFIL (DESCRICAO, STATUS)
                    VALUES (:DESCRICAO, :STATUS);
        ";

        $args = array(
            ':DESCRICAO' => $param->DESCRICAO,
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
    public static function incluir_itens($param, $con) {

        log_info($param);

        $PERFIL_ID  = $param->PERFIL_ID; 
        $USUARIO_ID = $param->ITEM->ID;
        
        $sql = "
            INSERT INTO TBUSUARIO_PERFIL_DETALHE (PERFIL_ID, USUARIO_ID)
                         VALUES (:PERFIL_ID, :USUARIO_ID);
        ";

        $args = array(
            ':PERFIL_ID'  => $PERFIL_ID ,
            ':USUARIO_ID' => $USUARIO_ID
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
    public static function incluir_menu($param, $con) {

        log_info($param);

        $PERFIL_ID  = $param->PERFIL_ID; 
        $USUARIO_ID = $param->ITEM->ID;
        
        $sql = "
            INSERT INTO TBUSUARIO_PERFIL_MENU (PERFIL_ID, MENU_CONTROLE, INCLUIR, ALTERAR, EXCLUIR, IMPRIMIR, NEGAR)
                           VALUES (:PERFIL_ID, :MENU_CONTROLE, :INCLUIR, :ALTERAR, :EXCLUIR, :IMPRIMIR, :NEGAR);
        ";

        $args = array(
            'PERFIL_ID'     => $param->PERFIL_ID,
            'MENU_CONTROLE' => $param->MENU_CONTROLE,
            'INCLUIR'       => $param->INCLUIR,
            'ALTERAR'       => $param->ALTERAR,
            'EXCLUIR'       => $param->EXCLUIR,
            'IMPRIMIR'      => $param->IMPRIMIR,
            'NEGAR'         => $param->NEGAR
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
    public static function incluir_grupo($param, $con) {

        log_info($param);

        $PERFIL_ID  = $param->PERFIL_ID; 
        $USUARIO_ID = $param->ITEM->ID;
        
        $sql = "
            INSERT INTO TBUSUARIO_PERFIL_GRUPO (PERFIL_ID, MENU_GRUPO, INCLUIR, ALTERAR, EXCLUIR, IMPRIMIR, NEGAR)
                            VALUES (:PERFIL_ID, :MENU_GRUPO, :INCLUIR, :ALTERAR, :EXCLUIR, :IMPRIMIR, :NEGAR);
        ";

        $args = array(
            'PERFIL_ID'  => $param->PERFIL_ID,
            'MENU_GRUPO' => $param->MENU_GRUPO,
            'INCLUIR'    => $param->INCLUIR,
            'ALTERAR'    => $param->ALTERAR,
            'EXCLUIR'    => $param->EXCLUIR,
            'IMPRIMIR'   => $param->IMPRIMIR,
            'NEGAR'      => $param->NEGAR
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
            UPDATE TBUSUARIO_PERFIL SET
                DESCRICAO  = :DESCRICAO,
                STATUS     = :STATUS
            WHERE (ID = :ID);
        ";

        $args = array(
            ':ID'        => $param->ID,
            ':DESCRICAO' => $param->DESCRICAO,
            ':STATUS'    => $param->STATUS
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
            UPDATE TBUSUARIO_PERFIL_DETALHE SET 
                DESCRICAO   = :DESCRICAO
            WHERE (ID = :ID);
        ";

        $args = array(
            ':ID'         => $param->ID,
            ':DESCRICAO'  => $param->DESCRICAO
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
        
        $sql = "DELETE FROM TBUSUARIO_PERFIL WHERE (ID = :ID);";

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
        
        $sql = "DELETE FROM TBUSUARIO_PERFIL_DETALHE WHERE (ID = :ID);";

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
    public static function excluir_grupo($param, $con) {

        $param = $param->ITEM;
        
        $sql = "DELETE FROM TBUSUARIO_PERFIL_GRUPO WHERE (ID = :ID);";

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
    public static function excluir_menu($param, $con) {

        $param = $param->ITEM;
        
        $sql = "DELETE FROM TBUSUARIO_PERFIL_MENU WHERE (ID = :ID);";

        $args = array(
            ':ID' => $param->ID
        );

        return $con->query($sql, $args);
    }
	
}