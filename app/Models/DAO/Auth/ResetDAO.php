<?php

namespace App\Models\DAO\Auth;

use App\Models\Conexao\_Conexao;
use Exception;
use App\Models\DTO\Auth\Reset;

class ResetDAO {

	/**
	 * Verifica se o usuário existe.
	 * 
	 * @param Reset $obj
	 * @return array
	 */
	public static function verificarUsuario(Reset $obj) {
		
		$con = new _Conexao();

		try {
			
			$existe_usuario = self::verificaUsuarioExiste($obj, $con);
			$existe_senha	= self::verificaSenhaExiste($obj, $con);
			
			$resposta = array('0' => 'sucesso');

			$retorno = array(
				'existe_usuario'	=> $existe_usuario,
				'existe_senha'		=> $existe_senha,
				'resposta'			=> $resposta
			);

		} catch(ValidationException $e1) {
			$retorno = array('resposta'	=> array('0' => 'erro', '1' => $e1->getMessage()));
		} catch(Exception $e2) {
			$retorno = array('resposta'	=> array('0' => 'erro', '1' => $e2->getMessage()));
		}

		return $retorno;
		
	}
	
	public static function verificaUsuarioExiste(Reset $obj, _Conexao $con) {
		
		$sql = "
			SELECT FIRST 1 
				U.USUARIO 
			FROM 
				TBUSUARIO U 
			WHERE 
				U.USUARIO = :USUARIO
			 OR U.EMAIL_LOGIN = :EMAIL
			 OR U.CNPJ = :CNPJ
		";

		$args = array(
			':USUARIO'	=> $obj->getUsuario(),
			':EMAIL' 	=> $obj->getEmail(),
			':CNPJ' 	=> $obj->getCnpj()
		);
		
		return $con->query($sql, $args);
	}
	
	public static function verificaSenhaExiste(Reset $obj, _Conexao $con) {
		
		$sql = "
			SELECT FIRST 1 
				U.PASSWORD 
			FROM 
				TBUSUARIO U 
			WHERE 
				U.USUARIO = :USUARIO
			 OR U.EMAIL_LOGIN = :EMAIL
			 OR U.CNPJ = :CNPJ
		";

		$args = array(
			':USUARIO'	=> $obj->getUsuario(),
			':EMAIL' 	=> $obj->getEmail(),
			':CNPJ' 	=> $obj->getCnpj()
		);

		return $con->query($sql, $args);
	}
	
	
	
	public static function compararUsuario(Reset $obj) {
		
		$con = new _Conexao();

		try {

			$existe_usuario = self::verificaUsuarioExiste($obj, $con);
			$existe_senha	= self::verificaSenhaExiste($obj, $con);
			
			$resposta = array('0' => 'sucesso');

			$retorno = array(
				'existe_usuario'	=> $existe_usuario,
				'existe_senha'		=> $existe_senha,
				'resposta'		=> $resposta
			);

		} catch(ValidationException $e1) {
			$retorno = array('resposta'	=> array('0' => 'erro', '1' => $e1->getMessage()));
		} catch(Exception $e2) {
			$retorno = array('resposta'	=> array('0' => 'erro', '1' => $e2->getMessage()));
		}

		return $retorno;
		
	}
	
	
	

	public static function alterarSenha(Reset $obj) {
		
		$con = new _Conexao();

		try {
			
			$sql = '
				UPDATE TBUSUARIO 
				SET PASSWORD = :SENHA
				WHERE 
					USUARIO = :USUARIO
				 OR EMAIL_LOGIN = :EMAIL
				 OR CNPJ = :CNPJ
			';
			
			$args = array(
				':SENHA'	=> $obj->getSenha(),
				':USUARIO'	=> $obj->getUsuario(),
				':EMAIL'	=> $obj->getEmail(),
				':CNPJ'		=> $obj->getCnpj()
			);
			
			$con->execute($sql, $args, true);
			
			$con->commit();
			
			$resposta = array('0' => 'sucesso');

		} catch(ValidationException $e1) {
			
			$con->rollback();
			$resposta = array('0' => 'erro', '1' => $e1->getMessage());
                        
		} catch(Exception $e2) {

			$con->rollback();
			$resposta = array('0' => 'erro', '1' => $e2->getMessage());

		}

		return $resposta;
		
	}
	
}
