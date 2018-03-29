<?php

namespace App\Models\DAO\Admin;

use App\Models\Conexao\_Conexao;
use Illuminate\Support\Facades\Auth;


class _11060DAO {

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
     * Listar Impressoras cadastradas
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function listar($dados) {
        
        $con = new _Conexao();
        
        $estab = $dados['estab'];
        
        $where = ' where ESTABELECIMENTO_ID = '.$estab;  
        
        $sql = 'select * from tbimpressoras'.$where;

        $ret = $con->query($sql);

        return $ret;

    }
    
    /**
     * Mostrar uma impressoras
     * @access public
     * @param {} $id
     * @return array
     */
    public static function show($id) {
        
        $con = new _Conexao();
        
        $sql = "select lpad(i.id,3,'0') as ID, i.descricao, i.codigo, lpad(i.estabelecimento_id,2,'0') estabelecimento_id from tbimpressoras i where ID = :ID";
        
        $args = array(
                ':ID' => $id
            );
        
        $ret = $con->query($sql, $args);

        return $ret;

    }
    
    /**
     * Gravar nova impressoras
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function store($dados) {
        
        $con = new _Conexao();
        
        try {

            $sql = 'INSERT INTO TBIMPRESSORAS (DESCRICAO, CODIGO, ESTABELECIMENTO_ID)
                    VALUES (:DESCRICAO, :SERIAL, :ESTAB);';

            $args = array(
                ':DESCRICAO' => $dados['descricao'],
                ':SERIAL'    => $dados['serial'],
                ':ESTAB'     => $dados['estab']
            );

            $ret = $con->execute($sql, $args);

            $con->commit();
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }
    
    /**
     * Atualizar impressoras
     * @access public
     * @param {} $dados
     * @param $id
     * @return array
     */
    public static function update($dados,$id) {
        
        $con = new _Conexao();
        
        try {

            $sql = 'update tbimpressoras set
                    descricao = :DESCRICAO,
                    estabelecimento_id = :ESTAB,
                    codigo = :SERIAL
                    where id = :ID';

            $args = array(
                ':DESCRICAO' => $dados['descricao'],
                ':SERIAL'    => $dados['serial'],
                ':ESTAB'     => $dados['estab'],
                ':ID'        => $id
            );

            $ret = $con->execute($sql, $args);

            $con->commit();
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }
    
    
    /**
     * Excluir impressora impressoras
     * @access public
     * @param $id
     * @return array
     */
    public static function destroy($dados) {
        
        $con = new _Conexao();
        
        try {

            $sql = 'delete from tbimpressoras where id = :ID';

            $args = array(
                ':ID' => $dados['id']
            );

            $ret = $con->execute($sql, $args);

            $con->commit();
            
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

}