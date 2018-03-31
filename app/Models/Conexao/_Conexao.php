<?php

namespace App\Models\Conexao;

use PDO;
use Exception;
use PDOException;

class _Conexao
{
	public $pdo;
    public $banco;
    
	public function __construct($db = false) 
	{
        
        try {

            $this->banco = DB_HOSTNAME  . ':' . DB_DATABASE;
			
	        $user = DB_USERNAME;
	        $pass = DB_PASSWORD;

            $this->pdo = new PDO(DB_DRIVER . ':dbname='.$this->banco.';role=' . DB_ROLE . ';charset=' . DB_CHARSET, $user, $pass);
            
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, false);
            $this->pdo->setAttribute(PDO::ATTR_PERSISTENT, true);
            $this->pdo->setAttribute(PDO::ATTR_TIMEOUT, 0); //no_wait
            
            //read committed (default)
            //Record version (default)
           
            $this->pdo->beginTransaction();
        
        } catch (Exception $e) {
            $this->close();
            throw new $e;
        }
		
	}

    
    //este metodo por algum motivo não deichava a coneccao fechar por completo, por isso seu conteudo foi removido
    public function close(){
     	//$ativo = $this->pdo->inTransaction();
        
        //if($ativo){
        //    $this->pdo->rollBack();
            //log_info('Conecção de '.\Auth::user()->USUARIO.' destruida (rollBack)');
        //}       
    }

	public function __destruct() {
        $this->pdo = null;
	}
    
    public function gen_id($tabela) {

        $stmt = $this->pdo->query('SELECT gen_id('.$tabela.',1) FROM RDB$DATABASE');
        $ret  = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $ret[0]->GEN_ID;

	}

	/**
	 * Executar select no sql.
	 *
	 * @param $sql
	 * @param $args
	 * @return mixed
	 */
	public function query($sql, $args = false, $print = false) {
        
		if ($args){

            $rplc = $this->argsReplace($sql, (array) $args);
            $sql  = $rplc->sql;
            $args = $rplc->args;

			if($print) {
				log_info($sql);
				print_l($args);
			}

			$query = $this->pdo->prepare($sql);
			$query->execute($args);

			$ret = $query->fetchAll(PDO::FETCH_OBJ);
			
            if ( DB_MONITOR ) {
                $this->statics($sql, $args);
            }
			
			return $ret;

		}else{
			
			$stmt = $this->pdo->query($sql);
			$ret = $stmt->fetchAll(PDO::FETCH_OBJ);
			
            if ( DB_MONITOR ) {
                $this->statics($sql, []);
            }

			return $ret;
		}

	}
	
	/**
	 * Estatisticas de uma transacao.
	 *
	 * @param $sql
	 * @param $args
	 */
	public function statics($sql, $args) {
        try{
			$sql_  = 'select
						
						c.mon$sql_text as SQL,
						a.MON$TIMESTAMP as INICIADO,
						b.MON$USER as USUARIO,
						b.MON$REMOTE_HOST as MAQUINA,
						b.MON$REMOTE_ADDRESS as IP,
						d.mon$record_inserts as INSERTS,
						d.mon$record_updates as UPDATES,
						d.mon$record_deletes as DELETES,
						d.MON$RECORD_SEQ_READS as SEM_INDICES,
						d.MON$RECORD_IDX_READS as COM_INDICES,
						e.mon$memory_used as MEMORIA_USADA,
						e.mon$memory_allocated as MEMORIA_ALOCADA

					from

					MON$TRANSACTIONS a , MON$ATTACHMENTS b, MON$STATEMENTS c, MON$RECORD_STATS d, MON$MEMORY_USAGE e

					where b.mon$attachment_id = a.mon$attachment_id
					and c.mon$attachment_id = a.mon$attachment_id
					and d.mon$stat_id = c.mon$stat_id
					and e.mon$stat_id = c.mon$stat_id
					and a.mon$transaction_id = current_transaction'; 

			$stmt  = $this->pdo->query($sql_);
			$ret   = $stmt->fetchAll(PDO::FETCH_OBJ);
			$texto = '';
			
			$texto .= PHP_EOL;

			foreach ($args as $key => $value) {
				$texto .= $key. '=' . $value .PHP_EOL;
			}

			$max = 0;

			foreach ($ret as $key => $value) {
				
				$texto .= 'INICIADO: 		'. $value->SQL              .PHP_EOL;
				
				$texto .= PHP_EOL;
				
				$texto .= 'INICIADO: 		'. $value->INICIADO 		.PHP_EOL;
				$texto .= 'USUARIO: 		'. $value->USUARIO 			.PHP_EOL;
				$texto .= 'MAQUINA: 		'. $value->MAQUINA 			.PHP_EOL;
				$texto .= 'IP: 				'. $value->IP 				.PHP_EOL;
				$texto .= 'INSERTS: 		'. $value->INSERTS 			.PHP_EOL;
				$texto .= 'UPDATES: 		'. $value->UPDATES 			.PHP_EOL;
				$texto .= 'DELETES: 		'. $value->DELETES 			.PHP_EOL;
				$texto .= 'SEM_INDICES: 	'. $value->SEM_INDICES		.PHP_EOL;
				$texto .= 'COM_INDICES:		'. $value->COM_INDICES 		.PHP_EOL;
				$texto .= 'MEMORIA_USADA:	'. $value->MEMORIA_USADA 	.PHP_EOL;
				$texto .= 'MEMORIA_ALOCADA: '. $value->MEMORIA_ALOCADA 	.PHP_EOL;
				
				$texto .= PHP_EOL;

				if($max  < $value->SEM_INDICES){
					$max = $value->SEM_INDICES;
				}
			}
			
			if($max > DB_MONITOR_INDEX_MIN){
				$caminho = "../public/assets/temp/sql/".$max."_".mt_rand().".txt";
				$fp      = fopen($caminho, "a");
				$escreve = fwrite($fp, $texto);
				fclose($fp);
			}
			
		} catch (Exception $e) {
			log_info($e);
		}
	}

	/**
	 * Retorna Campos de um sql.
	 *
	 * @param $sql
	 * @return mixed
	 */
	public function fields($sql) {

		$stmt = $this->pdo->query($sql);
		$ret = $stmt->fetch(PDO::FETCH_ASSOC);

		$retorno = [];

		foreach ($ret as $key => $value){
			array_push($retorno,$key);
		}

		return $retorno;

	}

	/**
	 * Executar insert/update/delete no sql.
	 *
	 * @param $sql
	 * @param $args
	 * @return mixed
	 */
	public function execute($sql, $args = false) {
        
		if ($args){
            $rplc = $this->argsReplace($sql, $args);
            $sql  = $rplc->sql;
            $args = $rplc->args;

            $query = $this->pdo->prepare($sql);

            return $query->execute($args);
            
        }else{
            $stmt = $this->pdo->query($sql);
            return [];
		}
	}
	
	/**
	 * Executar insert/update/delete no sql.<br>
	 * Nessa função deve ser passado o conteúdo (binário).
	 *
	 * @param string $sql
	 * @param $args
	 * @return mixed
	 */
	public function executeBin($sql, $args) {
						
		$query = $this->pdo->prepare($sql);

		//ADEQUADO APENAS PARA EMAIL
		$query->bindParam(':ID', $args[':ID']);
		$query->bindParam(':EMAIL', $args[':EMAIL']);
		$query->bindParam(':USUARIO_ID', $args[':USUARIO_ID']);
		$query->bindParam(':MSG', $args[':MSG']);
		$query->bindParam(':URL', $args[':URL']);
		$query->bindParam(':ASSUNTO', $args[':ASSUNTO']);
		$query->bindParam(':CORPO', $args[':CORPO'], PDO::PARAM_LOB);
		$query->bindParam(':STATUS', $args[':STATUS']);
		$query->bindParam(':DATAHORA', $args[':DATAHORA']);
		$query->bindParam(':CODIGO', $args[':CODIGO']);

		return $query->execute();
	}
    
    /**
	 * Executar insert/update/delete no sql.<br>
	 * Nessa função deve ser passado o conteúdo (binário).
	 *
	 * @param string $sql
	 * @param $args
	 * @return mixed
	 */
	public function executeEamil($sql, $args) {
						
		$query = $this->pdo->prepare($sql);

        log_info('Teste 1');
        
		//ADEQUADO APENAS PARA EMAIL
		$query->bindParam(':ID', $args[':ID']);
		$query->bindParam(':EMAIL', $args[':EMAIL']);
		$query->bindParam(':USUARIO_ID', $args[':USUARIO_ID']);
		$query->bindParam(':ASSUNTO', $args[':ASSUNTO']);
		$query->bindParam(':CORPO', $args[':CORPO'], PDO::PARAM_LOB);
		$query->bindParam(':STATUS', $args[':STATUS']);
		$query->bindParam(':DATAHORA', $args[':DATAHORA']);

		return $query->execute();
	}
  
    /**
	 * Executar insert/update/delete no sql.<br>
	 * Nessa função deve ser passado o conteúdo sql e parametros (opcional).<br>
	 * Tipos de parametro INT, STR, LOB, NUL, STM, BOO
     * 
	 * @param string $sql
	 * @param $args
	 * @return mixed
	 */
	public function executeParan($sql, $args = false) {
		
        $query = $this->pdo->prepare($sql);

		foreach ($args as $paran) {

			$executado = 0;

			if ($paran['TIPO'] == 'INT'){ $query->bindParam($paran['PARN'], $paran['VALOR'], PDO::PARAM_INT );  $executado = 1;}
			if ($paran['TIPO'] == 'STR'){ $query->bindParam($paran['PARN'], $paran['VALOR'], PDO::PARAM_STR );  $executado = 1;}
			if ($paran['TIPO'] == 'LOB'){ $query->bindParam($paran['PARN'], $paran['VALOR'], PDO::PARAM_LOB );  $executado = 1;}
			if ($paran['TIPO'] == 'NUL'){ $query->bindParam($paran['PARN'], $paran['VALOR'], PDO::PARAM_NULL);  $executado = 1;}
			if ($paran['TIPO'] == 'STM'){ $query->bindParam($paran['PARN'], $paran['VALOR'], PDO::PARAM_STMT);  $executado = 1;}
			if ($paran['TIPO'] == 'BOO'){ $query->bindParam($paran['PARN'], $paran['VALOR'], PDO::PARAM_BOOL);  $executado = 1;}
			

			if ($executado  === 0){
				throw new Exception('SQL: erro de tipo no parametro (' . $paran['PARN'] . ')', 99998);
			}

		}

        return $query->execute();
        
	}

	/**
	 * Executar commit no sql.
	 *
	 * @return mixed
	 */
	public function commit() {
		return $this->pdo->commit();
	}

	/**
	 * Executar rollback no sql.
	 *
	 * @return mixed
	 */
	public function rollback() {
            if ( $this->pdo->inTransaction() ) {
                return $this->pdo->rollback();
            }
	}

    /**
	 * detectar Sql Inject.
	 *
	 * @return mixed
	 */
	public function detectSqlInject($arg) {

	    $sql_inject = 0;
/*
        $sql_inject = $sql_inject + strpos(strtoupper('#' . $arg), 'SELECT');
        $sql_inject = $sql_inject + strpos(strtoupper('#' . $arg), 'INSERT');
        $sql_inject = $sql_inject + strpos(strtoupper('#' . $arg), 'UNION');
        $sql_inject = $sql_inject + strpos(strtoupper('#' . $arg), 'DELETE');
        $sql_inject = $sql_inject + strpos(strtoupper('#' . $arg), 'UPDATE');
        $sql_inject = $sql_inject + strpos(strtoupper('#' . $arg), 'DROP');
        $sql_inject = $sql_inject + strpos(strtoupper('#' . $arg), 'CREATE');
*/
        if ($sql_inject > 0) {
            \Log::error('SQL injection detectado (' . $arg . ') | ' . \Auth::user()->USUARIO . ' | ' . \Request::getClientIp());
            throw new Exception('SQL injection detectado (' . $arg . ')', 99998);
        }
	}
    
    /**
     * • Aplica parametro condicional ao comando sql chamado através do "@" no parametro e &#47;*&#64;param*&#47;  no sql<br/>
     * Exemplo de uso:<br/>
     * $sql = SELECT * FROM TABLE WHERE STATUS = 1 &#47;*&#64;teste*&#47;<br/>
     * $args = array('&#64;teste' => 'AND TESTE = 2');<br/>
     * Saída: SELECT * FROM TABLE WHERE STATUS = 1 AND TESTE = 2<br/><br/>
     * • Realiza a verificação de Sql Injection nos argumentos
     * @param string $sql Comando em sql
     * @param string $args Parametros do sql
     * @return string Comando sql parametrizado
     */
    public function argsReplace($sql,$args) {
        
        foreach ( $args as  $key => $arg ) {
            if ( strripos($key,'@') > -1 ) {
                $sql = str_replace('/*' . $key . '*/', $arg, $sql);
                unset($args[$key]);
            }
			else {
				$this->detectSqlInject($arg);
			}
        }


        //////////////////////////////////////////////
        /**
         * Bloco que permite vários parametros iguais na mesma consulta e limpa paramtros que não foram passados na consulta
         */
        /*
        uksort($args, function($a, $b) {
            return strlen($b) - strlen($a);
        });
        
        $new_args = [];
        foreach( $args as $key_1 => $arg ) {
            $arg_count = 0;
            
            $key =  (!(strpos($key_1, ':') === false)) ? $key_1 : ':' . $key_1;
            
            $sql = preg_replace_callback("/$key/", function ($matches) use (&$arg_count, &$new_args, &$key, &$arg) {
                $arg_count++; 
                $hash = randString();
                $key = str_replace(':', ':'.$hash . '_', $key);
                $new_args[$key . '_' . $arg_count] = $arg;
                
                return $key . '_' . $arg_count;
            }, $sql);
        }
        $args = empty($new_args) ? $args : $new_args;
	*/
        ///////////////////////////////////////////////

		
        return (object) array(
            'sql'  => $sql,
            'args' => $args
        );
    }
}