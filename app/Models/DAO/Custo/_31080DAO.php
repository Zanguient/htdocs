<?php

namespace App\Models\DAO\Custo;

/**
 * DAO do objeto _31080 - Cadastro de Mercados
 */
class _31080DAO {
	
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
                FAMILIA_ID,
                DESCRICAO,
                PERC_INCENTIVO,
                INCENTIVO,
                IIF(INCENTIVO = 1,'Sim','Não') as DESC_INCENTIVO

            from tbcusto_padrao p
            where p.familia_id = :FAMILIA_ID
        ";

        $args = array(
            ':FAMILIA_ID'     => $param->FAMILIA_ID
        );

        return $con->query($sql, $args);
    }

    /**
     * Consultar.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultar_itens($param, $con) {
        
        $sql = "
            SELECT
                
                ID,
                DESCRICAO,
                PERCENTUAL,
                FATOR,
                AVOS,
                USAR_FATOR,
                EDITAVEL,
                PADRAO_ID,
                INCENTIVO,
                FRETE,
                MARGEM,
                iif(USAR_FATOR = 1,'Sim', 'Não') as DESC_FATOR,
                iif(EDITAVEL = 1,'Sim', 'Não') as DESC_EDITAVEL,
                iif(INCENTIVO = 1,'Sim', 'Não') as DESC_INCENTIVO,
                iif(FRETE = 1,'Sim', 'Não') as DESC_FRETE,
                iif(MARGEM = 1,'Sim', 'Não') as DESC_MARGEM

            from tbcusto_padrao_item i
            where i.padrao_id = :PADRAO_ID
        ";

        $args = array(
            ':PADRAO_ID'     => $param->PADRAO_ID
        );

        return $con->query($sql, $args);
    }

    /**
     * Consultar.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultar_itens_conta($param, $con) {
        
        $sql = "
            SELECT
                
                c.ID,
                b.DESCRICAO,
                c.CONTA

            from tbcusto_padrao_item_conta c, tbcontacontabil b
            where c.padrao_item_id = :ITEM_ID
            and b.conta = c.conta
        ";

        $args = array(
            ':ITEM_ID'     => $param->ITEM_ID
        );

        return $con->query($sql, $args);
    }

    /**
     * Consultar.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarFamilia($param, $con) {

        $filtro = array_key_exists('FILTRO', $param) ? " AND f.codigo||' '||upper(f.descricao) like upper('%" . str_replace(' ', '%', $param->FILTRO) . "%')" : '';
        
        $sql = "
            SELECT

                f.codigo as ID,
                f.descricao

            from tbfamilia f, tbusuario u, tbusuario_familia j
            where u.usuario = current_user
            and j.usuario_id = u.codigo
            and f.codigo = j.familia_id
            and f.status = 1
            and f.codigo in (select distinct j.familia_id from TBREGRA_CALCULO_CUSTO j)

            $filtro

            order by f.descricao
        ";

        return $con->query($sql);
    }

    /**
     * Consultar.
     * @access public
     * @param json $param
     * @param _Conexao $con
     * @return array
     */
    public static function consultarConta($param, $con) {

        $filtro = array_key_exists('FILTRO', $param) ? " and c.conta||' '||upper(c.descricao) like upper('%" . str_replace(' ', '%', $param->FILTRO) . "%')" : '';
        
        $sql = "
            SELECT first 50
                c.id,
                c.conta,
                c.descricao
            from tbcontacontabil c where c.tipo_custo = 3

            $filtro
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
            INSERT INTO TBCUSTO_PADRAO (FAMILIA_ID, DESCRICAO, PERC_INCENTIVO, INCENTIVO)
                    VALUES (:FAMILIA_ID, :DESCRICAO, :PERC_INCENTIVO, :INCENTIVO);
        ";

        $args = array(
            ':FAMILIA_ID'     => $param->FAMILIA_ID,
            ':DESCRICAO'      => $param->DESCRICAO,
            ':PERC_INCENTIVO' => $param->PERC_INCENTIVO,
            ':INCENTIVO'      => $param->INCENTIVO
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

        $param = $param->ITEM;
        
        $sql = "
            INSERT INTO TBCUSTO_PADRAO_ITEM (DESCRICAO, PERCENTUAL, FATOR, AVOS, USAR_FATOR, EDITAVEL, PADRAO_ID, INCENTIVO, FRETE, MARGEM)
                         VALUES (:DESCRICAO, :PERCENTUAL, :FATOR, :AVOS, :USAR_FATOR, :EDITAVEL, :PADRAO_ID, :INCENTIVO, :FRETE, :MARGEM);
        ";

        $args = array(
            ':DESCRICAO'  => $param->DESCRICAO,
            ':PERCENTUAL' => $param->PERCENTUAL,
            ':FATOR'      => $param->FATOR,
            ':AVOS'       => $param->AVOS,
            ':USAR_FATOR' => $param->USAR_FATOR,
            ':EDITAVEL'   => $param->EDITAVEL,
            ':PADRAO_ID'  => $param->PADRAO_ID,
            ':INCENTIVO'  => $param->INCENTIVO,
            ':FRETE'      => $param->FRETE,
            ':MARGEM'     => $param->MARGEM,
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
    public static function incluir_itens_conta($param, $con) {

        $param = $param->ITEM;
        
        $sql = "
            INSERT INTO TBCUSTO_PADRAO_ITEM_CONTA (PADRAO_ITEM_ID, CONTA)
                               VALUES (:PADRAO_ITEM_ID, :CONTA);
        ";

        $args = array(
            ':PADRAO_ITEM_ID' => $param->ITEM_ID,
            ':CONTA'          => $param->CONTA
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
            UPDATE TBCUSTO_PADRAO SET 
                FAMILIA_ID     = :FAMILIA_ID,
                DESCRICAO      = :DESCRICAO,
                PERC_INCENTIVO = :PERC_INCENTIVO,
                INCENTIVO      = :INCENTIVO
            WHERE (ID = :ID);
        ";

        $args = array(
            ':ID'             => $param->ID,
            ':FAMILIA_ID'     => $param->FAMILIA_ID,
            ':DESCRICAO'      => $param->DESCRICAO,
            ':PERC_INCENTIVO' => $param->PERC_INCENTIVO,
            ':INCENTIVO'      => $param->INCENTIVO
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
            UPDATE TBCUSTO_PADRAO_ITEM SET 
                DESCRICAO   = :DESCRICAO,
                PERCENTUAL  = :PERCENTUAL,
                FATOR       = :FATOR,
                AVOS        = :AVOS,
                USAR_FATOR  = :USAR_FATOR,
                EDITAVEL    = :EDITAVEL,
                PADRAO_ID   = :PADRAO_ID,
                INCENTIVO   = :INCENTIVO,
                FRETE       = :FRETE,
                MARGEM      = :MARGEM
            WHERE (ID = :ID);
        ";

        $args = array(
            ':ID'         => $param->ID,
            ':DESCRICAO'  => $param->DESCRICAO,
            ':PERCENTUAL' => $param->PERCENTUAL,
            ':FATOR'      => $param->FATOR,
            ':AVOS'       => $param->AVOS,
            ':USAR_FATOR' => $param->USAR_FATOR,
            ':EDITAVEL'   => $param->EDITAVEL,
            ':PADRAO_ID'  => $param->PADRAO_ID,
            ':INCENTIVO'  => $param->INCENTIVO,
            ':FRETE'      => $param->FRETE,
            ':MARGEM'     => $param->MARGEM
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
    public static function alterar_itens_conta($param, $con) {

        $param = $param->ITEM;
        
        $sql = "
            UPDATE TBCUSTO_PADRAO_ITEM_CONTA SET 
                PADRAO_ITEM_ID = :PADRAO_ITEM_ID,
                CONTA = :CONTA
            WHERE (ID = 1);
        ";

        $args = array(
            ':ID'             => $param->ID,
            ':PADRAO_ITEM_ID' => $param->ITEM_ID,
            ':CONTA'          => $param->CONTA
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
        
        $sql = "DELETE FROM TBCUSTO_PADRAO WHERE (ID = :ID);";

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
        
        $sql = "DELETE FROM TBCUSTO_PADRAO_ITEM WHERE (ID = :ID);";

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
    public static function excluir_itens_conta($param, $con) {

        $param = $param->ITEM;
        
        $sql = "DELETE FROM TBCUSTO_PADRAO_ITEM_CONTA WHERE (ID = :ID);";

        $args = array(
            ':ID' => $param->ID
        );

        return $con->query($sql, $args);
    }
	
}