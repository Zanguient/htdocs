<?php

namespace App\Models\DAO\Estoque;

use App\Models\Conexao\_Conexao;

/**
 * DAO do objeto _15100 - Abastecer estoque
 */
class _15100DAO {

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

    /**
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarUP($paran) {
        
        $con = new _Conexao();

        $sql = 'SELECT

                    g.descricao as DESCRICAO,
                    p.perfil    as PERFIL,
                    u.gp_id     as ID

                from tbup p, tbgp_up u, tbgp g
                where p.codigo1 = :CODBARRAS
                and u.up_id = p.id
                and g.id = u.gp_id';

        $args = array(
            ':CODBARRAS' => $paran['CODBARRAS'],
        );

        $ret = $con->query($sql, $args);
        
        if(count($ret) > 0){
            $ret = $ret[0];
        }else{
            $ret = ['ID' => 'ERRO'];
        }

        return $ret;
    }

    /**
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarOperador($paran) {
        
        $con = new _Conexao();

        $sql = 'SELECT

                    valor_ext as VALOR,
                    codigo    as ID,
                    nome      as DESCRICAO

                from tbcontrole_operador ,tboperador
                where operador_id = codigo
                and codigo_barras = :CODBARRAS
                and id = 26';

        $args = array(
            ':CODBARRAS' => $paran['CODBARRAS'],
        );

        $ret = $con->query($sql, $args);
        
        if(count($ret) > 0){
            $ret = $ret[0];
        }else{
            $ret = ['ID' => 'ERRO'];
        }

        return $ret;
    }

    /**
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function ConsultarPeca($paran) {
        
        $con = new _Conexao();

        $COD_BARRAS  = $paran['COD_BARRAS'];
        $OPERADOR_ID = $paran['OPERADOR_ID'];
        $COD_UP      = $paran['COD_UP'];

        $sql = 'select * from spu_liberacao_talao_coletor(\''.$COD_BARRAS.'\', '.$OPERADOR_ID.', \''.$COD_UP.'\')';
        
        $ret = $con->query($sql);
        
        if(count($ret) > 0){
            $ret = $ret[0];
        }else{
            $ret = ['ID' => 'ERRO'];
        }

        return $ret;
    }

    /**
     * Consultar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function Abastercer($paran) {
        
        $con = new _Conexao();

        $COD_BARRAS  = $paran['COD_BARRAS'];
        $OPERADOR_ID = $paran['OPERADOR_ID'];
        $COD_UP      = $paran['COD_UP'];

        $sql = 'execute procedure SPU_LIBERAR_TALAO_COLETOR(\''.$COD_BARRAS.'\', '.$OPERADOR_ID.', \''.$COD_UP.'\')';

        $ret = $con->execute($sql);

        $con->commit();

        $ret = ['ID' => 'OK'];

        return $ret;
    }

}