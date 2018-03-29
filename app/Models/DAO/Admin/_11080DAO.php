<?php

namespace App\Models\DAO\Admin;

use App\Models\Conexao\_Conexao;
use Exception;
use PDO;
use PDOException;
use Illuminate\Support\Facades\Auth;

/**
 * DAO do objeto _11080 - Criar Relatorio
 */
class _11080DAO {

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
     * Listar relatorios cadastrados
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function listarRelatorios($filtro) {
        
        $con = new _Conexao();

        $Palavra = $filtro ? '%' . str_replace(' ', '%', $filtro) . '%' : '%';

        try {

            $sql = 'select  first 100

                        ID,
                        NOME,
                        NOME TITULO,
                        TIPO

                    from TBRELATORIO_WEB r where r.STATUS = 1 and r.NOME like \''.$Palavra.'\'
                    order BY NOME';

            $ret = $con->query($sql);

            $con->commit();
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            log_erro($e->getMessage());
        }
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
            log_erro($e->getMessage());
        }
    }

    /**
     * getRetornoSql campos
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function getRetornoSql($dados) {
        
        $con = new _Conexao();
        
        try {

            $sql = $dados['SQL'];

            $sql_inject = 0;

            $sql_inject = $sql_inject + strpos(strtoupper('#' . $sql), 'INSERT');
            $sql_inject = $sql_inject + strpos(strtoupper('#' . $sql), 'DELETE');
            $sql_inject = $sql_inject + strpos(strtoupper('#' . $sql), 'UPDATE');
            $sql_inject = $sql_inject + strpos(strtoupper('#' . $sql), 'DROP');
            $sql_inject = $sql_inject + strpos(strtoupper('#' . $sql), 'CREATE');

            if ($sql_inject > 0) {
                \Log::error('Esta instrução SQL não é permitida  ('+$sql+') | ' . \Auth::user()->USUARIO . ' | ' . \Request::getClientIp());
                throw new Exception('Esta instrução SQL não é permitida | ' . \Auth::user()->USUARIO . ' | ' . \Request::getClientIp(), 99998);
            }

            $ret = $con->fields($sql);

            $con->commit();
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            log_erro($e->getMessage());
        }
    }

    /**
     * getRetornoSql
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function getRetorno($dados) {
        
        $con = new _Conexao();
        
        try {

            $sql = $dados['SQL'];

            $sql_inject = 0;

            $sql_inject = $sql_inject + strpos(strtoupper('#' . $sql), 'INSERT');
            $sql_inject = $sql_inject + strpos(strtoupper('#' . $sql), 'DELETE');
            $sql_inject = $sql_inject + strpos(strtoupper('#' . $sql), 'UPDATE');
            $sql_inject = $sql_inject + strpos(strtoupper('#' . $sql), 'DROP');
            $sql_inject = $sql_inject + strpos(strtoupper('#' . $sql), 'CREATE');

            if ($sql_inject > 0) {
                \Log::error('Esta instrução SQL não é permitida  ('+$sql+') | ' . \Auth::user()->USUARIO . ' | ' . \Request::getClientIp());
                throw new Exception('Esta instrução SQL não é permitida | ' . \Auth::user()->USUARIO . ' | ' . \Request::getClientIp(), 99998);
            }

            $ret = $con->query($sql);

            $con->commit();
            
            return $ret;
            
        } catch (Exception $e) {
            $con->rollback();
            log_erro($e->getMessage());
        }
    }

    /**
     * Gravar
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function Gravar($dados) {
        
        $con = new _Conexao();

        $info = $dados['info'];
        $grupo = $dados['grupo'];
        $campos = $dados['campos'];

        try {
            if(array_key_exists('imputis',$dados)){
                $imputis = $dados['imputis']; 
            }else{
                $imputis = [];     
            }
                 
        } catch (Exception $e) {
            $imputis = [];    
        } 
        
        if(count($campos) == 0){
            throw new Exception('Relatório não teve campos definidos | ' . \Auth::user()->USUARIO . ' | ' . \Request::getClientIp(), 99998);    
        }
        
        try {

            $rel_id = $con->gen_id('GTBRELATORIO_WEB');

            $sql = "INSERT INTO TBRELATORIO_WEB (ID,NOME, DESCRICAO, TIPO, TEMPLATE_ID, STATUS,MENU_GRUPO)
                    VALUES (".$rel_id.",'".$info[0]."', '', '".$info[2]."', ".$info[3].", 1, '".$info[10]."')
                    returning ID";

            $ret = $con->execute($sql);

            $sql = "INSERT INTO TBRELATORIO_WEB_CONF (\"relatorio_id\", \"filtro\", \"agrupamento\", \"agrupar\", \"zebrado\", \"versao\",\"cor\",\"fonte\", \"fonteHTML\", \"totalizador\", PAISAGEM)
                    VALUES ( ".$rel_id.", '".$info[1]."', '".$grupo[0]."', ".$grupo[1].", ".$info[5].", '".$info[4]."','".$grupo[2]."','".$info[8]."','".$info[7]."','".$info[9]."',".$info[11].")";

            $ret = $con->execute($sql);

            foreach ($campos as $key => $value) {
                $sql = "INSERT INTO TBRELATORIO_WEB_DETALHE (RELATORIO_ID, PERCENTUAL, DESCRICAO, CLASSE, CAMPO, ORDEM, MASCARA, VISIVEL,COR, TOTALIZAR, CASAS,\"INDEX\",AGRUPAR,TOTAL_GRUPO,TOTAL_TIPO,FORMULA,PREFIX,SUFIX,COL_TAMANHO,URL_LINK)
                             VALUES (".$rel_id.", 0, '".$value[4]."', '".$value[5]."', '".$value[0]."', ".$value[8].", '', ".$value[1].", '".$value[3]."', ".$value[6].", ".$value[7].",".$value[9].",".$value[10].",".$value[11].",".$value[12].",'".$value[13]."','".$value[14]."','".$value[15]."',".$value[16].",'".$value[17]."');";

                $ret = $con->execute($sql);
            }

            foreach ($imputis as $key => $value) {

                $tipo = 0;
                $tipo = $value[2];
                

                $sql = "INSERT INTO TBRELATORIO_WEB_PARAMETRO (RELATORIO_ID, DESCRICAO, PARAMETRO, TIPO)
                               VALUES (".$rel_id.", '".$value[1]."', '".$value[0]."', ".$tipo.");";

                $ret = $con->execute($sql);
            }

                $sql = "INSERT INTO TBRELATORIO_WEB_USUARIO (USUARIO_ID, RELATORIO_ID)
                                   VALUES (".Auth::user()->CODIGO.", ".$rel_id.");";

                $ret = $con->execute($sql);

                $query = $con->pdo->prepare("INSERT INTO TBRELATORIO_WEB_SQL (\"descricao\", \"relatorio_id\",SQL) VALUES ('', :RELID, :SQL);");

                //ADEQUADO APENAS PARA EMAIL
                $query->bindParam(':RELID', $rel_id);
                $query->bindParam(':SQL', $info[6], PDO::PARAM_LOB);

                $query->execute();
            
            $con->commit();
            //$con->rollback();

            return $rel_id;
            
        } catch (Exception $e) {
            $con->rollback();
            log_erro($e->getMessage());
        }
    }

    /**
     * Gravar
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function show($id) {
        
        $con = new _Conexao();

        try {

            $sql = "SELECT * FROM TBRELATORIO_WEB WHERE ID = ".$id;

            $ret1 = $con->query($sql);

            $sql = "SELECT * FROM TBRELATORIO_WEB_CONF WHERE \"relatorio_id\" = ".$id;

            $ret2 = $con->query($sql);

            $sql = "SELECT * FROM TBRELATORIO_WEB_DETALHE WHERE RELATORIO_ID = ".$id."  order by ORDEM";

            $ret3 = $con->query($sql);

            $sql = "SELECT * FROM TBRELATORIO_WEB_PARAMETRO WHERE RELATORIO_ID = ".$id;

            $ret4 = $con->query($sql);

            $sql = "SELECT * FROM TBRELATORIO_WEB_SQL WHERE \"relatorio_id\" = ".$id;

            $ret5 = $con->query($sql);
            
            $con->rollback();

            $retorno = [
                "INFO"      => $ret1,
                "CONF"      => $ret2,
                "CAMPOS"    => $ret3,
                "IMPUTS"    => $ret4,
                "SQL"       => $ret5
            ];

            return $retorno; 
            
        } catch (Exception $e) {
            $con->rollback();
            log_erro($e->getMessage());
        }
    }

    /**
     * Excluir
     * @access public
     * @param {} $dados
     * @return array
     */
    public static function Excluir($dados) {
        
        $con = new _Conexao();

        $id = $dados['id'];

        try {

            $sql = "DELETE FROM TBRELATORIO_WEB WHERE ID = ".$id;

            $ret1 = $con->query($sql);

            $sql = "DELETE FROM TBRELATORIO_WEB_CONF WHERE \"relatorio_id\" = ".$id;

            $ret2 = $con->query($sql);

            $sql = "DELETE FROM TBRELATORIO_WEB_DETALHE WHERE RELATORIO_ID = ".$id;

            $ret3 = $con->query($sql);

            $sql = "DELETE FROM TBRELATORIO_WEB_PARAMETRO WHERE RELATORIO_ID = ".$id;

            $ret4 = $con->query($sql);

            $sql = "DELETE FROM TBRELATORIO_WEB_SQL WHERE \"relatorio_id\" = ".$id;

            $ret5 = $con->query($sql);
            
            $con->commit();

            return $id; 
            
        } catch (Exception $e) {
            $con->rollback();
            log_erro($e->getMessage());
        }
    }
	
}