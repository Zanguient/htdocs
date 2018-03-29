<?php

namespace App\Models\DAO\Auth;

use App\Models\DTO\Auth\CustomPassword;

class CustomPasswordDAO {
	
	public static function verificarEmailExiste($param, $con) {
		
		$sql = "
			SELECT FIRST 1 
				U.USUARIO 
			FROM 
				TBUSUARIO U 
			WHERE 
				U.EMAIL_LOGIN = :EMAIL
		";

		$args = [
			':EMAIL' => $param->email
		];
		
		return $con->query($sql, $args);
	}

	public static function gravarEmailRecuperacao($param, $con) {

		$sql = "
			INSERT INTO TBUSUARIO_REDEFINIR_SENHA 
				(EMAIL, TOKEN)
			VALUES 
				(:EMAIL, :TOKEN)
		";

		$args = [
			':EMAIL' => $param->email,
			':TOKEN' => $param->token
		];

		$con->execute($sql, $args);
	}

	public static function verificarToken($param, $con) {

		$sql = "
			SELECT FIRST 1
				U.EMAIL, 
				U.DATAHORA 
			FROM 
				TBUSUARIO_REDEFINIR_SENHA U
			WHERE 
				U.TOKEN = :TOKEN
		";

		$args = [
			':TOKEN' => $param
		];

		return $con->query($sql, $args);
	}

	public static function gravarNovaSenha($param, $con) {

		$sql = "
			UPDATE TBUSUARIO 
			SET 
				PASSWORD = :PASSWORD
			WHERE 
				EMAIL_LOGIN = :EMAIL
		";

		$args = [
			':PASSWORD' => $param->password,
			':EMAIL'	=> $param->email
		];

		$con->execute($sql, $args);
	}
	
}
