<?php

namespace App\Models\DAO\Admin;

use App\Models\Conexao\_Conexao;

/**
 * DAO do objeto _11180 - Blok
 */
class _11180DAO {

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
        
        $con = new _Conexao('BLOK');
        
        try {

            $sql = 'select * from tbusuario';

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

        $filt = !empty($filtro['FILTRO']) ? ' where id||\' \'||nome like  \'%'.  str_replace(' ', '%', $filtro['FILTRO']) .'%\'' : '';

        $sql = "select * from tbusuario ".$filt." order by nome";

        $ret = $con->query($sql);

        $con->commit();
        
        return $ret;
    }

    /**
     * url
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function url($filtro,$con) {

        $sql = "select * from tbbloqueio_url j where j.usuario_id = :USUARIO_ID";

        $args = array(
            ':USUARIO_ID' => $filtro['ID']
        );

        $ret = $con->query($sql, $args);

        $con->commit();
        
        return $ret;
    }

    /**
     * url
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function excluir($filtro,$con) {

        $sql = "delete from tbusuario j where j.id = :USUARIO_ID";

        $args = array(
            ':USUARIO_ID' => $filtro['ID']
        );

        $ret = $con->query($sql, $args);
        
        return $ret;
    }

    /**
     * gravar
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function gravar($filtro,$con) {

        if($filtro['ID'] == 0){
            $filtro['ID'] = $con->gen_id('gen_tbusuario_id');
        }

        $sql = "UPDATE OR INSERT INTO TBUSUARIO (
                    ID, 
                    NOME, 
                    USB, 
                    CDDVD, 
                    LIBERAR1, 
                    LIBERAR2, 
                    LIBERAR_T, 
                    LIBERAR_N, 
                    LIBERAR_C1, 
                    LIBERAR_C2, 
                    LIBERAR_H1, 
                    LIBERAR_H2, 
                    HORA1, 
                    HORA2, 
                    INVERT_URL, 
                    INVERT_TELA, 
                    FLAG, 
                    GRUPO
                )VALUES (
                    :ID, 
                    :NOME, 
                    :USB, 
                    :CDDVD, 
                    :LIBERAR1, 
                    :LIBERAR2, 
                    :LIBERAR_T, 
                    :LIBERAR_N, 
                    :LIBERAR_C1, 
                    :LIBERAR_C2, 
                    :LIBERAR_H1, 
                    :LIBERAR_H2, 
                    :HORA1, 
                    :HORA2, 
                    :INVERT_URL, 
                    :INVERT_TELA, 
                    :FLAG, 
                    :GRUPO
                ) MATCHING (ID);";

        $args = array(
            ':ID'            => $filtro['ID'         ],  
            ':NOME'          => $filtro['NOME'       ],  
            ':USB'           => $filtro['USB'        ],  
            ':CDDVD'         => $filtro['CDDVD'      ],  
            ':LIBERAR1'      => $filtro['LIBERAR1'   ],  
            ':LIBERAR2'      => $filtro['LIBERAR2'   ],  
            ':LIBERAR_T'     => $filtro['LIBERAR_T'  ],  
            ':LIBERAR_N'     => $filtro['LIBERAR_N'  ],  
            ':LIBERAR_C1'    => $filtro['LIBERAR_C1' ],  
            ':LIBERAR_C2'    => $filtro['LIBERAR_C2' ],  
            ':LIBERAR_H1'    => $filtro['LIBERAR_H1' ],  
            ':LIBERAR_H2'    => $filtro['LIBERAR_H2' ],  
            ':HORA1'         => $filtro['HORA1'      ],  
            ':HORA2'         => $filtro['HORA2'      ],  
            ':INVERT_URL'    => $filtro['INVERT_URL' ],  
            ':INVERT_TELA'   => $filtro['INVERT_TELA'],  
            ':FLAG'          => $filtro['FLAG'       ],  
            ':GRUPO'         => $filtro['GRUPO'      ],    
        );

        $ret = $con->query($sql, $args);
        
        return $filtro['ID'];
    }

    /**
     * janela
     * @access public
     * @param {} $filtro
     * @param Conection $con
     * @return array
     */
    public static function janela($filtro,$con) {

        $sql = "select * from tbbloqueio_janela j where j.usuario_id = :USUARIO_ID";

        $args = array(
            ':USUARIO_ID' => $filtro['ID']
        );

        $ret = $con->query($sql, $args);

        $con->commit();
        
        return $ret;

    }
	
}