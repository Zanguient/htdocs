<?php

namespace App\Models\DAO\Vendas;

use App\Models\Conexao\_Conexao;
use PDO;

/**
 * DAO do objeto 12030
 * @package Vendas
 * @category Vendas
 */
class _12030DAO {
    
    public static function show($dados) {
        $con = new _Conexao();

        $sql1 = "
            select first 1 T.ID,t.data,t.cliente,T.OBSERVACAO
            from tbpersonal1 t where t.ID = ".$dados." order by t.cliente ascending,t.id descending
        ";
        
        $sql2 = "
            SELECT p.modelo,p.valor from tbpersonal2 p where p.aprov_id = ".$dados."
        ";

        
        $Ret1 = $con->query($sql1);
        $Ret2 = $con->query($sql2);

        $dado = ['dados' => $Ret1,'itens' => $Ret2];
        
        //\App\Helpers\Helpers::log_ro($dado['itens']);
        //exit;
        
        return $dado; 
    }
    
    public static function delete($dados) {
        $con = new _Conexao();

        $sql1 = "
            delete from tbpersonal1 p where p.id = ".$dados."
        ";
        
        $sql2 = "
            delete from tbpersonal2 p where p.APROV_ID = ".$dados."
        ";

        $Ret1 = $con->query($sql1);
        $Ret2 = $con->query($sql2);
        
        $con->commit();
        
        return ''; 
    }
    
    public static function gravar($dados) {
        $con = new _Conexao();
        
        $ID = $con->gen_id('GTBPERSONAL1');

        $sql = "
            INSERT INTO TBPERSONAL1 (ID, DATA, MODELO, VALOR, CLIENTE, OBSERVACAO)
                 VALUES (:ID, :DATA, null, null, :CLIENTE, :OBSERVACAO);
        ";
        
        $args = array(
            ':ID' => $ID,
            ':DATA' => date("d.m.Y H:i:s"),
            ':CLIENTE' => $dados['cliente'],
            ':OBSERVACAO' => $dados['observa'],
        );

        $Ret = $con->query($sql, $args);
        
        $corpo = '';
        $corpo .= '<html xmlns="http://www.w3.org/1999/xhtml">';
        $corpo .= '<head></head><body>';
        $corpo .= '<table border="1" cellspacing="0" cellpadding="0">';
        $corpo .= '<tr bgcolor="#33FF99" align="center">';
        $corpo .= ' <td colspan="5">GC</td>';
        $corpo .= '</tr>';

        foreach ($dados['modelos'] as $dado){
            $modelo = $dado[0];
            $valor  = $dado[1];

                $corpo .= '<tr bgcolor="#FFFF99">';
                $corpo .= '<td colspan="5">Modelo: '.$modelo.' - Valor: '.$valor.'</td>';
                $corpo .= '</tr>';

            $sql = "
                insert into tbpersonal2 (id,modelo,valor,APROV_ID) values (gen_id(gtbpersonal2,1),:MODELO,:VALOR,:APROV_ID);
            ";
            
            $args = array(
                ':MODELO' => $modelo,
                ':VALOR' => $valor,
                ':APROV_ID' => $ID
            );
            
            $Ret = $con->query($sql, $args);
        }
        
        $corpo .= '<tr bgcolor="#FFFF99">';
        $corpo .= '<td colspan="5">Cliente: '.$dados['cliente'].'</td>';
        $corpo .= '</tr>';

        $corpo .= '<tr bgcolor="#FFFF99">';
        $corpo .= '<td colspan="5">Obs.: '.$dados['observa'].'</td>';
        $corpo .= '</tr>';
        $corpo .= '</table>';
        $corpo .= '<p>';
        
        $corpo .= '</body></html>';
        
        $email = 'anderson@delfa.com.br,manoel@delfa.com.br';
        if($dados['email'] == 1 ){$email='diana@delfa.com.br,cristiano@delfa.com.br,manoel@delfa.com.br';}
        if($dados['email'] == 2 ){$email='henrique@delfa.com.br,manoel@delfa.com.br';}
        if($dados['email'] == 3 ){$email='marina@delfa.com.br,export@delfa.com.br,manoel@delfa.com.br';}
        
        
        $sql = "
            INSERT INTO TBEMAIL (EMAIL, USUARIO_ID, ASSUNTO, STATUS, DATAHORA, DATAHORA_ENVIO,CORPO)
             VALUES (:EMAIL, 186, 'Custos (".date("d.m.Y H:i:s").")', 0, current_timestamp, null,:CORPO);
        ";
        
        $query = $con->pdo->prepare($sql);
        
        $query->bindParam(':EMAIL', $email);
        $query->bindParam(':CORPO', $corpo,PDO::PARAM_LOB);
        
        $Ret =  $query->execute();
        
        $con->commit();
        
        return $ID;  
    }
    
    public static function index($dados) {
        $con = new _Conexao();
		
        $sql = "
            select first :CONT T.ID,t.data,t.cliente,T.OBSERVACAO,(
                SELECT list(p.modelo||' = '||p.valor,',  ') from tbpersonal2 p where p.aprov_id = t.id) as Modelo
            from tbpersonal1 t order by t.cliente ascending,t.id descending";

        $args = array(
				':CONT'	=>	500,
        );
            
        $Ret = $con->query($sql, $args);

		return $Ret;
    }
    
    public static function index2($dados) {
        $con = new _Conexao();
        
        $filtro = $dados['filtro'];
        
        $sql = "
            select * from (
            select first :CONT T.ID,t.data,t.cliente,T.OBSERVACAO,
            cast((SELECT list(p.modelo||' = '||p.valor,',  ') from tbpersonal2 p where p.aprov_id = t.id) as varchar(300)) as Modelo
            from tbpersonal1 t
            order by t.cliente ascending,t.id descending
            ) z where z.Modelo like '%".$filtro."%' or z.cliente like '%".$filtro."%' or  z.OBSERVACAO like '%".$filtro."%'";

        $args = array(
            ':CONT'     =>	500
        );

        $Ret = $con->query($sql, $args);

        return $Ret;  
    }
      
}
